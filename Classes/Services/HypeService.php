<?php

/*
* This file is part of the "ca_hype_plugin" Extension for TYPO3 CMS.
*
* For the full copyright and license information, please read the
* LICENSE.txt file that was distributed with this source code.
*/

namespace CA\HypePlugin\Services;

use Org\Heigl\Hyphenator\Hyphenator;
use Org\Heigl\Hyphenator\Options;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Core\SystemEnvironmentBuilder;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class HypeService implements \TYPO3\CMS\Core\SingletonInterface
{
    private Hyphenator $hyphenator;
    private Options $options;

    /**
     * Constructs a new HypeService.
     *
     * @param ExtensionConfiguration $extensionConfiguration The ExtensionConfiguration instance.
     */
    public function __construct(
        private readonly ExtensionConfiguration $extensionConfiguration
    ) {
        $this->hyphenator = Hyphenator::factory();
        $this->options = new Options();
    }

    /**
     * Hyphenates the given text using the configured method(s).
     *
     * @param string $text The text to hyphenate.
     * @param array $options The options for the hyphens.
     * @return string The hyphenated text.
     */
    public function hyphenateText(string $text, array $options = []): string
    {
        $mode = $this->getOptionValue('mode', $options, 'both');

        if ($mode === 'manual') {
            return $this->applyManualHyphenation($text);
        }

        if ($mode === 'auto') {
            return $this->applyAutomaticHyphenation($text, $options);
        }

        // Mode is 'both' - apply both methods
        $text = $this->applyAutomaticHyphenation($text, $options);
        return $this->applyManualHyphenation($text);
    }

    /**
     * Applies automatic hyphenation using the TeX algorithm.
     *
     * @param string $text The text to hyphenate.
     * @param array $options The options for the hyphens.
     * @return string The hyphenated text.
     */
    protected function applyAutomaticHyphenation(string $text, array $options): string
    {
        $hyphenator = $this->getHyphens($options);
        $value = $hyphenator->hyphenate($text);
        $return = is_array($value) ? reset($value) : $value;

        $hyphenChar = $hyphenator->getOptions()->getHyphen();
        return preg_replace('@(' . preg_quote($hyphenChar, '@') . '){2,}@usi', $hyphenChar, $return) ?? $return;
    }

    /**
     * Applies manual hyphenation using the configured terms.
     *
     * @param string $text The text to hyphenate.
     * @return string The hyphenated text.
     */
    protected function applyManualHyphenation(string $text): string
    {
        $terms = $this->getHyphenationTerms();
        if (empty($terms)) {
            return $text;
        }

        foreach ($terms as $term) {
            $replacement = str_replace('|', $this->getHyphenCharacter([]), strip_tags($term['to']));
            $text = preg_replace('/(?<=[\>.*\s])' . $term['from'] . '(?!.*\s\<\/head\>)/s', $replacement, $text);
        }

        return $text;
    }

    /**
     * Fetches all hyphenation terms from the database.
     *
     * @return array The hyphenation terms.
     */
    protected function getHyphenationTerms(): array
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('tx_hype_term');

        $terms = $queryBuilder
            ->select('from', 'to')
            ->from('tx_hype_term')
            ->where(
                $queryBuilder->expr()->eq('hidden', 0),
                $queryBuilder->expr()->eq('deleted', 0)
            )
            ->execute()
            ->fetchAllAssociative();

        return $terms ?: [];
    }

    /**
     * Get an instance of the Hyphenator with the given options.
     *
     * You can pass the following options:
     *
     * - defaultLocale: The default locale for the hyphenation rules. Defaults to "de_DE".
     * - rightMin: The minimum number of characters that must be left unhyphenated on the right of the word. Defaults to 2.
     * - leftMin: The minimum number of characters that must be left unhyphenated on the left of the word. Defaults to 2.
     * - wordMin: The minimum length of words that can be hyphenated. Defaults to 6.
     * - quality: The quality of the hyphenation. Can be an integer from 0 (no hyphenation) to 9 (best quality). Defaults to 9.
     * - hyphen: The string to use (e.g. '&shy;', "\u{00AD}", '-')
     *
     * @param array $options The options for the hyphenator.
     * @return Hyphenator The hyphenator instance.
     */
    public function getHyphens(array $options): Hyphenator
    {
        $this->options
            ->setHyphen($this->getHyphenCharacter($options))
            ->setDefaultLocale($this->getLocale() ?? $this->getOptionValue('defaultLocale', $options, 'de-DE'))
            ->setRightMin($this->getOptionValue('rightMin', $options, 2))
            ->setLeftMin($this->getOptionValue('leftMin', $options, 2))
            ->setWordMin($this->getOptionValue('wordMin', $options, 6))
            ->setQuality($this->getOptionValue('quality', [], 9))
            ->setFilters(['Simple', 'CustomMarkup'])
            ->setTokenizers(['Whitespace', 'Punctuation']);

        // @extensionScannerIgnoreLine
        $this->hyphenator->setOptions($this->options);

        return $this->hyphenator;
    }

    protected function getHyphenCharacter(array $options): string
    {
        $char = $this->getOptionValue('hyphen', $options, '-');

        // The problem is that values from the settings (constant-editor) may not be correctly escaped
        // as a result, we receive the string here and not the charcode. We try to correct this
        if (preg_match('@\\\\x([0-9a-fA-F]{2})@', $char, $matches)) {
            $char = html_entity_decode('&#x' . $matches[1] . ';', ENT_QUOTES, 'UTF-8');
        }
        if (preg_match('@\\\\u\{?([0-9a-fA-F]{4})\}?@', $char, $matches)) {
            $char = html_entity_decode('&#x' . $matches[1] . ';', ENT_QUOTES, 'UTF-8');
        }

        return $char;
    }

    /**
     * Retrieves the value of a specified option key from provided options or extension configuration.
     *
     * The function first checks the provided options array for the specified key.
     * If the key does not exist in the options array, it then checks the extension
     * configuration with the key under the 'ca_hype_plugin' namespace. If the key
     * is not found in either, it returns the provided default value.
     *
     * @param string $key The key for the option to retrieve.
     * @param array $options An array of options to search for the key.
     * @param mixed $default The default value to return if the key is not found.
     *
     * @return mixed The value of the option if found, or the default value if not.
     */
    protected function getOptionValue(string $key, array $options, $default = null): mixed
    {
        return $options[$key] ?? $this->extensionConfiguration->get('ca_hype_plugin', $key) ?? $default;
    }

    /**
     * Tries to determine the locale of the current request.
     *
     * It will first check the "language" attribute of the current request.
     * If that does not exist, it will try to get the default language of
     * the current site.
     *
     * @return string|null The locale if found, otherwise null.
     */
    private function getLocale(): ?string
    {
        $request = $this->getRequest();
        $language = $request->getAttribute('language');
        $site = $request->getAttribute('site');

        if ($language !== null) {
            return $language->getLocale();
        }

        if ($site !== null) {
            return $site->getDefaultLanguage()->getLocale();
        }

        return null;
    }

    /**
     * Gets the current request. If none is available, a request is constructed.
     *
     * @return ServerRequest The current request.
     */
    private function getRequest(): ServerRequest
    {
        if (!empty($GLOBALS['TYPO3_REQUEST'])) {
            return $GLOBALS['TYPO3_REQUEST'];
        }

        return (new ServerRequest())->withAttribute('applicationType', SystemEnvironmentBuilder::REQUESTTYPE_FE);
    }
}
