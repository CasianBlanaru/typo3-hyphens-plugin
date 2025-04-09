<?php

namespace CasianBlanaru\HyphensPlugin\Service;

class HyphenatorService
{
    public function hyphenateText(string $text): string
    {
        if (empty($text)) {
            return '';
        }

        // Implementiere hier die Silbentrennungslogik
        // Vorerst nur ein einfaches Beispiel
        $patterns = [
            'Energieversorgungssysteme' => 'Energie|versorgungs|systeme',
            'Automatisierungstechnologien' => 'Automatisierungs|technologien',
            'Industrieanwendungen' => 'Industrie|anwendungen'
        ];

        foreach ($patterns as $word => $hyphenated) {
            $text = str_replace($word, $hyphenated, $text);
        }

        return $text;
    }
}
