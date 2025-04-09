<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'CA Hypes Plugin',
    'description' => 'TYPO3 Extension für automatische Silbentrennung',
    'category' => 'fe',
    'author' => 'Casian Blanaru',
    'author_email' => 'cab@tpwd.de',
    'state' => 'stable',
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '12.4.0-12.4.99',
            'php' => '8.1.0-8.9.99'
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
