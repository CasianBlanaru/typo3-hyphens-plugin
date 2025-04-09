<?php

// Configuration for the hyphenation term table
return [
    'ctrl' => [
        'label' => 'from',
        'default_sortby' => 'ORDER BY from',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'delete' => 'deleted',
        'title' => 'LLL:EXT:ca_hype_plugin/Resources/Private/Language/locallang_db.xlf:tx_hyphens_term',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'iconfile' => 'EXT:ca_hype_plugin/Resources/Public/Icons/tx_hyphens_term.svg',
        'searchFields' => 'from',
    ],
    'interface' => [
        'showRecordFieldList' => 'hidden, from, to',
    ],
    'columns' => [
        'hidden' => [
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
            ],
        ],
        'from' => [
            'label' => 'LLL:EXT:ca_hype_plugin/Resources/Private/Language/locallang_db.xlf:tx_hyphens_term.from',
            'description' => 'LLL:EXT:ca_hype_plugin/Resources/Private/Language/locallang_db.xlf:tx_hyphens_term.from.description',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'eval' => 'trim,required',
                'max' => 255,
            ],
        ],
        'to' => [
            'label' => 'LLL:EXT:ca_hype_plugin/Resources/Private/Language/locallang_db.xlf:tx_hyphens_term.to',
            'description' => 'LLL:EXT:ca_hype_plugin/Resources/Private/Language/locallang_db.xlf:tx_hyphens_term.to.description',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'eval' => 'trim,required,TPWD\\Hyphens\\Evaluation\\PipeEvaluation',
                'max' => 255,
            ],
        ],
    ],
    'types' => [
        '0' => [
            'showitem' => 'hidden, --palette--;;term',
        ],
    ],
    'palettes' => [
        'term' => [
            'showitem' => 'from, to',
        ],
    ],
];
