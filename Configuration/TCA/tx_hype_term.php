<?php

// Configuration for the hyphenation term table
return [
    'ctrl' => [
        'title' => 'LLL:EXT:ca_hype_plugin/Resources/Private/Language/locallang_db.xlf:tx_hype_term',
        'label' => 'original_text',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'original_text,hyphenated_text',
        'iconfile' => 'EXT:ca_hype_plugin/Resources/Public/Icons/Extension.svg'
    ],
    // Define available types of records
    'types' => [
        '1' => ['showitem' => 'hidden,--palette--;;1,original_text,hyphenated_text,--div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access,starttime,endtime'],
    ],
    // Define palettes for field grouping
    'palettes' => [
        'timeRestriction' => [
            'showitem' => 'starttime, endtime'
        ]
    ],
    // Configure database fields
    'columns' => [
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
                'default' => 0
            ]
        ],
        'starttime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
                'default' => 0
            ]
        ],
        'endtime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
                'default' => 0
            ]
        ],
        'original_text' => [
            'exclude' => false,
            'label' => 'LLL:EXT:ca_hype_plugin/Resources/Private/Language/locallang_db.xlf:tx_hype_term.original_text',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ]
        ],
        'hyphenated_text' => [
            'exclude' => false,
            'label' => 'LLL:EXT:ca_hype_plugin/Resources/Private/Language/locallang_db.xlf:tx_hype_term.hyphenated_text',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim,required'
            ]
        ]
    ]
];
