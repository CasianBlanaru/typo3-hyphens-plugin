.. include:: ../Includes.txt

.. _configuration:

=============
Konfiguration
=============

Zielgruppe: **Entwickler und Integratoren**

Extension-Konfiguration
=====================

Die Extension kann über das TYPO3-Backend unter *Einstellungen > Extension Configuration* konfiguriert werden:

.. code-block:: php

   $GLOBALS['TYPO3_CONF_VARS']['EXTENSIONS']['ca_hyphens_plugin'] = [
       'enableAutoHyphenation' => true,
       'defaultLanguage' => 'de',
       'customPatterns' => true
   ];

TypoScript-Konfiguration
======================

.. code-block:: typoscript

   plugin.tx_cahyphensplugin {
       settings {
           # Aktiviere Silbentrennung global
           enable = 1

           # Minimale Wortlänge für Silbentrennung
           minWordLength = 10

           # CSS-Klasse für Elemente mit Silbentrennung
           cssClass = hyphenate
       }
   }

Page TSconfig
============

.. code-block:: typoscript

   mod.web_layout.tt_content.preview.ca_hyphens_plugin = EXT:ca_hyphens_plugin/Resources/Private/Templates/Preview/HyphenationPreview.html

Fluid-Templates anpassen
======================

Die Templates können über TypoScript überschrieben werden:

.. code-block:: typoscript

   plugin.tx_cahyphensplugin {
       view {
           templateRootPaths {
               0 = EXT:ca_hyphens_plugin/Resources/Private/Templates/
               1 = {$plugin.tx_cahyphensplugin.view.templateRootPath}
           }
           partialRootPaths {
               0 = EXT:ca_hyphens_plugin/Resources/Private/Partials/
               1 = {$plugin.tx_cahyphensplugin.view.partialRootPath}
           }
           layoutRootPaths {
               0 = EXT:ca_hyphens_plugin/Resources/Private/Layouts/
               1 = {$plugin.tx_cahyphensplugin.view.layoutRootPath}
           }
       }
   }
