<?php

namespace CasianBlanaru\HyphensPlugin\Tests\Unit\Service;

use PHPUnit\Framework\TestCase;
use CasianBlanaru\HyphensPlugin\Service\HyphenatorService;

class HyphenatorServiceTest extends TestCase
{
    protected HyphenatorService $hyphenatorService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->hyphenatorService = new HyphenatorService();
    }

    /**
     * @test
     * @dataProvider hyphenationDataProvider
     */
    public function hyphenateTextReturnsExpectedResult(string $input, string $expected): void
    {
        $result = $this->hyphenatorService->hyphenateText($input);
        self::assertEquals($expected, $result);
    }

    public static function hyphenationDataProvider(): array
    {
        return [
            'compound word' => [
                'Energieversorgungssysteme',
                'Energie|versorgungs|systeme'
            ],
            'multiple words' => [
                'Automatisierungstechnologien und Industrieanwendungen',
                'Automatisierungs|technologien und Industrie|anwendungen'
            ],
            'empty string' => [
                '',
                ''
            ]
        ];
    }
}
