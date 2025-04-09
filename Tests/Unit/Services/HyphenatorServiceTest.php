<?php

/*
* This file is part of the "ca_hyphens" Extension for TYPO3 CMS.
*
* For the full copyright and license information, please read the
* LICENSE.txt file that was distributed with this source code.
*/

namespace CA\HypePlugin\Tests\Unit\Services;

use CA\HypePlugin\Services\HypeService;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

/**
 * Test for HypeService
 */
class HyphenatorServiceTest extends TestCase
{
    use ProphecyTrait;

    protected ?HypeService $subject = null;

    /** @var ObjectProphecy|ExtensionConfiguration */
    protected $extensionConfigurationMock;

    /**
     * Have Hyphanator extension loaded
     */
    protected array $testExtensionsToLoad = [
        'typo3conf/ext/ca_hyphens_plugin',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->extensionConfigurationMock = $this->prophesize(ExtensionConfiguration::class);
        $this->subject = new HypeService($this->extensionConfigurationMock->reveal());
    }

    #[Test]
    public function isHypeServiceInstantiated(): void
    {
        self::assertInstanceOf(HypeService::class, $this->subject);
    }

    /**
     * Test if default options work
     */
    #[DataProvider('ensureCorrectConfigurationTestDataProvider')]
    #[Test]
    public function ensureCorrectConfigurationTest($key, $options, $extensionConfiguration, $default, $expectedResult): void
    {
        // Define expected return value for the get method
        $this->extensionConfigurationMock
            ->get('ca_hyphens_plugin', $key)
            ->willReturn($extensionConfiguration[$key] ?? null);

        $result = $this->subject->getOptionValue($key, $options, $default);

        self::assertSame($expectedResult, $result);
    }

    /**
     * Data provider
     *
     * @return array
     */
    public static function ensureCorrectConfigurationTestDataProvider()
    {
        return [
            // minLeft
            'leftMinValueFromExtensionSettings' => [
                'key' => 'leftMin',
                'options' => [],
                'extensionConfiguration' => ['leftMin' => '2'],
                'default' => '1',
                'expectedResult' => '2', // ExtensionSetting
            ],
            'leftMinValueFromOptions' => [
                'key' => 'leftMin',
                'options' => ['leftMin' => 4],
                'extensionConfiguration' => ['leftMin' => '2'],
                'default' => '1',
                'expectedResult' => 4, // options
            ],
            'leftMinValueFromDefault' => [
                'key' => 'leftMin',
                'options' => ['leftMin' => null],
                'extensionConfiguration' => [],
                'default' => '1',
                'expectedResult' => '1', // default
            ],
            // minRight
            'rightMinValueFromExtensionSettings' => [
                'key' => 'rightMin',
                'options' => [],
                'extensionConfiguration' => ['rightMin' => '2'],
                'default' => '1',
                'expectedResult' => '2', // ExtensionSetting
            ],
            'rightMinValueFromOptions' => [
                'key' => 'rightMin',
                'options' => ['rightMin' => 4],
                'extensionConfiguration' => ['rightMin' => '2'],
                'default' => '1',
                'expectedResult' => 4, // options
            ],
            'rightMinValueFromDefault' => [
                'key' => 'rightMin',
                'options' => ['rightMin' => null],
                'extensionConfiguration' => ['rightMin'],
                'default' => '1',
                'expectedResult' => '1', // default
            ],
            // wordMin
            'wordMinValueFromExtensionSettings' => [
                'key' => 'wordMin',
                'options' => [],
                'extensionConfiguration' => ['wordMin' => '6'],
                'default' => '1',
                'expectedResult' => '6', // ExtensionSetting
            ],
            'wordMinValueFromOptions' => [
                'key' => 'wordMin',
                'options' => ['wordMin' => 4],
                'extensionConfiguration' => ['wordMin' => '6'],
                'default' => '1',
                'expectedResult' => 4, // options
            ],
            'wordMinValueFromDefault' => [
                'key' => 'wordMin',
                'options' => ['wordMin' => null],
                'extensionConfiguration' => ['wordMin'],
                'default' => '1',
                'expectedResult' => '1', // default
            ],
        ];
    }

    /**
     * @test
     */
    public function hyphenateTextReturnsHyphenatedText(): void
    {
        $this->extensionConfigurationMock
            ->get('ca_hyphens_plugin', 'hyphenChar')
            ->willReturn('-');

        $input = 'Energieversorgungssysteme';
        $expected = 'Energie-versorgungs-systeme';

        $result = $this->subject->hyphenateText($input);
        self::assertEquals($expected, $result);
    }

    /**
     * @test
     */
    public function hyphenateTextWithCustomHyphenCharReturnsHyphenatedText(): void
    {
        $this->extensionConfigurationMock
            ->get('ca_hyphens_plugin', 'hyphenChar')
            ->willReturn('|');

        $input = 'Energieversorgungssysteme';
        $expected = 'Energie|versorgungs|systeme';

        $result = $this->subject->hyphenateText($input);
        self::assertEquals($expected, $result);
    }

    /**
     * @test
     */
    public function emptyTextReturnsEmptyString(): void
    {
        $result = $this->subject->hyphenateText('');

        self::assertEmpty($result);
    }
}
