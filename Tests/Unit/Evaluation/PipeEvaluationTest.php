<?php

declare(strict_types=1);

namespace TPWD\Hyphens\Tests\Unit\Evaluation;

use TPWD\Hyphens\Evaluation\PipeEvaluation;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class PipeEvaluationTest extends UnitTestCase
{
    protected $pipeEvaluation;
    protected $extensionConfigurationProphecy;

    protected function setUp(): void
    {
        parent::setUp();

        $this->extensionConfigurationProphecy = $this->prophesize(ExtensionConfiguration::class);
        GeneralUtility::addInstance(ExtensionConfiguration::class, $this->extensionConfigurationProphecy->reveal());

        $this->pipeEvaluation = new PipeEvaluation();
    }

    /**
     * @test
     */
    public function returnFieldJSReturnsExpectedJavaScript(): void
    {
        $result = $this->pipeEvaluation->returnFieldJS();
        self::assertEquals('return value;', $result);
    }

    /**
     * @test
     */
    public function evaluateFieldValueUsesDefaultRegexWhenNoConfigurationFound(): void
    {
        $this->extensionConfigurationProphecy
            ->get('hyphens', 'backendEvaluationRegex')
            ->willReturn('');

        $result = $this->pipeEvaluation->evaluateFieldValue('test@value', '', $set);

        self::assertEquals('testvalue', $result);
    }

    /**
     * @test
     */
    public function evaluateFieldValueUsesCustomRegexFromConfiguration(): void
    {
        $this->extensionConfigurationProphecy
            ->get('hyphens', 'backendEvaluationRegex')
            ->willReturn('/[^a-z]/');

        $result = $this->pipeEvaluation->evaluateFieldValue('Test@123', '', $set);

        self::assertEquals('test', $result);
    }

    /**
     * @test
     */
    public function deevaluateFieldValueReturnsOriginalValue(): void
    {
        $parameters = ['value' => 'test|value'];
        $result = $this->pipeEvaluation->deevaluateFieldValue($parameters);

        self::assertEquals('test|value', $result);
    }
}
