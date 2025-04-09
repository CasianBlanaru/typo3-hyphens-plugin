<?php

namespace CasianBlanaru\HyphensPlugin\Parser;

class HyphenParser
{
    /**
     * Ersetzt Wörter im Text mit ihren getrennten Varianten
     *
     * @param string $text Der zu trennende Text
     * @param string $delimiter Das Trennzeichen
     * @return string Der Text mit Trennungen
     */
    public function replace(string $text, string $delimiter = '|'): string
    {
        // Implementierung der Trennungslogik
        $words = [
            'Energieversorgungssysteme' => 'Energie' . $delimiter . 'versorgungs' . $delimiter . 'systeme',
            'Automatisierungstechnologien' => 'Automatisierungs' . $delimiter . 'technologien',
        ];

        return str_replace(array_keys($words), array_values($words), $text);
    }
}
