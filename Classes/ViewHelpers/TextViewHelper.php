<?php

/*
* This file is part of the "ca_hype_plugin" Extension for TYPO3 CMS.
*
* For the full copyright and license information, please read the
* LICENSE.txt file that was distributed with this source code.
*/

namespace CA\HypePlugin\ViewHelpers;

use CA\HypePlugin\Services\HypeService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * ViewHelper für die automatische Silbentrennung von Texten.
 *
 * Beispiele
 * ========
 *
 * Basis-Nutzung:
 * -----------
 *
 * ..  code-block:: html
 *     <hype:text>{text}</hype:text>
 *
 * Mit spezifischem Modus:
 * -----------------
 *
 * ..  code-block:: html
 *     <hype:text mode="auto">{text}</hype:text>
 *     <hype:text mode="manual">{text}</hype:text>
 *     <hype:text mode="both">{text}</hype:text>
 *
 * Mit zusätzlichen Optionen:
 * ----------------------
 *
 * ..  code-block:: html
 *     <hype:text mode="auto" leftMin="2" rightMin="3" wordMin="4" defaultLocale="de_CH">{text}</hype:text>
 *
 * Inline-Nutzung:
 * --------------------------------
 *
 * ..  code-block:: fluid
 *     {stringContent -> hype:text()}
 *     {hype:text(value: stringContent)}
 *     <hype:text value="{stringContent}" />
 *     <hype:text>{stringContent}</hype:text>
 */
final class TextViewHelper extends AbstractViewHelper
{
    protected $escapeOutput = false;

    /**
     * Initialisiert alle benötigten Argumente.
     */
    public function initializeArguments(): void
    {
        $this->registerArgument('value', 'string', 'Text, der getrennt werden soll.', false);
        $this->registerArgument('mode', 'string', 'Trennungsmodus (auto, manual oder both).', false, 'both');
        $this->registerArgument('hyphen', 'string', 'Zeichen für die Trennung (z.B. &shy; / "\xAD" / "\u{00AD}").', false);
        $this->registerArgument('leftMin', 'int', 'Minimale Anzahl von Zeichen am Wortanfang.');
        $this->registerArgument('rightMin', 'int', 'Minimale Anzahl von Zeichen am Wortende.');
        $this->registerArgument('wordMin', 'int', 'Minimale Wortlänge für Trennung.');
        $this->registerArgument('quality', 'int', 'Qualitätsstufe der Trennung (0-9, höher ist besser).');
        $this->registerArgument('defaultLocale', 'string', 'Wörterbuch für die Trennung.');
    }

    /**
     * Rendert den getrennten Text.
     *
     * @return string Getrennter Text.
     *
     * @throws \Exception
     */
    public function render(): string
    {
        $value = $this->arguments['value'] ?? $this->renderChildren();

        if ($value === null || !is_string($value)) {
            throw new \Exception(
                'TextViewHelper: Inkompatible oder fehlende Inhalte.',
                1733408400,
            );
        }

        $hypeService = GeneralUtility::makeInstance(HypeService::class);
        return $hypeService->hyphenateText($value, $this->arguments);
    }

    /**
     * Gibt den Namen des Content-Arguments zurück
     *
     * @return string Name des Content-Arguments
     */
    public function getContentArgumentName(): string
    {
        return 'value';
    }
}
