<?php

return [
    'frontend' => [
        'ca/hype-plugin' => [
            'target' => \CA\HypePlugin\Middleware\HypeMiddleware::class,
            'description' => 'Adds automatic hyphenation to text content',
            'after' => [
                'typo3/cms-frontend/prepare-tsfe-rendering',
                'typo3/cms-frontend/content-length-headers'
            ],
            'before' => [
                'typo3/cms-frontend/output-compression'
            ]
        ],
    ],
];
