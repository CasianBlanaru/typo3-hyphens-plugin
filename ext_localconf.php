<?php

defined('TYPO3') || die('Access denied.');

(static function () {
    // Register hyphenation service
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addService(
        'ca_hyphens',
        'hyphens',
        \LIA\Hyphens\Service\HyphenationService::class,
        [
            'title' => 'Text Hyphenation Service',
            'description' => 'Service for hyphenating text content',
            'subtype' => 'hyphens',
            'available' => true,
            'priority' => 60,
            'quality' => 80,
            'os' => '',
            'exec' => '',
            'className' => \LIA\Hyphens\Service\HyphenationService::class
        ]
    );

    // Configure PageTS
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
        '@import "EXT:ca_hyphens_plugin/Configuration/TsConfig/Page/Mod/WebLayout/BackendLayouts.tsconfig"'
    );

    // Configure TypoScript
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptSetup(
        '@import "EXT:ca_hyphens_plugin/Configuration/TypoScript/setup.typoscript"'
    );

    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tce']['formevals'][\CA\Hyphens\Evaluation\PipeEvaluation::class] = '';

    // Configure content element wizard
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
        'mod {
            wizards.newContentElement.wizardItems.plugins {
                elements {
                    hypens_plugin {
                        iconIdentifier = content-text
                        title = Hyphens Plugin
                        description = Plugin for managing text hyphenation
                        tt_content_defValues {
                            CType = list
                            list_type = ca_hypens_plugin
                        }
                    }
                }
                show = *
            }
        }'
    );
})();
