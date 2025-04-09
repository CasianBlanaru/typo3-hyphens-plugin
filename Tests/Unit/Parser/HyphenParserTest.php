<?php

namespace CasianBlanaru\HyphensPlugin\Tests\Unit\Parser;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use CasianBlanaru\HyphensPlugin\Parser\HyphenParser;

class HyphenParserTest extends TestCase
{
    private HyphenParser $subject;

    protected function setUp(): void
    {
        parent::setUp();
        $this->subject = new HyphenParser();
    }

    #[Test]
    public function replaceReturnsHyphenatedText(): void
    {
        $text = 'Energieversorgungssysteme';
        $expected = 'Energie|versorgungs|systeme';

        $result = $this->subject->replace($text, '|');
        $this->assertEquals($expected, $result);
    }

    #[Test]
    public function replaceWithCustomDelimiterReturnsHyphenatedText(): void
    {
        $text = 'Automatisierungstechnologien';
        $expected = 'Automatisierungs-technologien';

        $result = $this->subject->replace($text, '-');
        $this->assertEquals($expected, $result);
    }
}
