.. include:: ../Includes.txt

.. _developer:

====================
Entwickler-Handbuch
====================

Zielgruppe: **Entwickler**

Die Extension verwendet den HyphenatorService für die Verarbeitung der Silbentrennung.

Installation
===========

Die Extension kann über Composer installiert werden:

.. code-block:: bash

   composer req ca/hyphens-plugin

API Referenz
===========

HyphenatorService
---------------

Der HyphenatorService ist die Hauptklasse für die Silbentrennung:

.. code-block:: php

   use CA\HypePlugin\Service\HyphenatorService;

   class MyClass {
       public function __construct(
           protected readonly HyphenatorService $hyphenatorService
       ) {}

       public function processText(string $text): string {
           return $this->hyphenatorService->hyphenateText($text);
       }
   }

ViewHelper
---------

Der ViewHelper kann in Fluid-Templates verwendet werden:

.. code-block:: html

   <html xmlns:f="http://typo3.org/ns/TYPO3/CMS/Fluid/ViewHelpers"
         xmlns:hype="http://typo3.org/ns/CA/HypePlugin/ViewHelpers"
         data-namespace-typo3-fluid="true">

   <hype:hyphenate>{text}</hype:hyphenate>

   </html>

Tests
=====

Die Extension verwendet PHPUnit für Tests. Tests können wie folgt ausgeführt werden:

.. code-block:: bash

   vendor/bin/phpunit -c Tests/Unit/UnitTests.xml

Hooks und Events
==============

Die Extension bietet verschiedene Hooks und Events für die Erweiterung der Funktionalität:

.. code-block:: php

   $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['ca_hyphens_plugin']['beforeHyphenation']
