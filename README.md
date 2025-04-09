# TYPO3 Hyphenation Plugin

A TYPO3 extension that provides advanced hyphenation capabilities for your website content. This plugin allows both automatic and manual hyphenation of text, with special focus on German compound words.

## Features

- Manual hyphenation through database-stored hyphenation rules
- Middleware-based text processing for automatic integration
- Support for custom hyphenation delimiters
- Database-driven term management
- Configurable through TYPO3 backend
- Unit and functional test coverage

## Requirements

- TYPO3 CMS 12.4 or higher
- PHP 8.1 or higher
- Composer

## Installation

1. Install via composer:
```bash
composer require casianblanaru/typo3-hyphens-plugin
```

2. Activate the extension in the TYPO3 Extension Manager or via CLI:
```bash
vendor/bin/typo3 extension:activate ca_hyphens_plugin
```

## Configuration

### Backend Configuration

1. Create a new SysFolder for hyphenation terms
2. Add "Hyphenation Term" records with:
   - Original text
   - Hyphenated version
   - Optional description

### TypoScript Setup

```typoscript
plugin.tx_hyphens {
    settings {
        # Custom delimiter for hyphenation (default: '|')
        delimiter = '|'
        
        # Enable/disable hyphenation
        enable = 1
    }
}
```

## Usage

### Adding Hyphenation Terms

1. Navigate to your hyphenation terms SysFolder
2. Create a new "Hyphenation Term" record
3. Enter the original word in "Original Text"
4. Enter the hyphenated version in "Hyphenated Text"
5. Save and close

Example:
- Original Text: Energieversorgungssysteme
- Hyphenated Text: Energie|versorgungs|systeme

### Programmatic Usage

```php
// Inject the HyphenParser
public function __construct(
    private readonly HyphenParser $parser
) {
}

// Use the parser
$text = 'Energieversorgungssysteme';
$hyphenatedText = $this->parser->replace($text);
```

### Middleware Integration

The extension automatically processes HTML responses through its middleware. No additional configuration is needed.

## Development

### Running Tests

```bash
# Unit Tests
vendor/bin/phpunit Tests/Unit

# Functional Tests
vendor/bin/phpunit Tests/Functional

# All Tests with Coverage
composer ci:tests
```

### Code Quality

```bash
# PHP CS Fixer
composer ci:php:cs

# PHPStan
composer ci:php:stan
```

## Architecture

### Components

- **HyphenParser**: Core component for text processing
- **TermRepository**: Database interaction for hyphenation terms
- **HyphenatorMiddleware**: Automatic content processing
- **Domain Models**: Term management

### Database Schema

Table: tx_hype_term

| Column   | Type         | Description           |
|----------|-------------|-----------------------|
| uid      | int(11)     | Primary Key          |
| from     | varchar(255)| Original text        |
| to       | varchar(255)| Hyphenated version   |
| hidden   | tinyint(1)  | Hidden flag          |
| deleted  | tinyint(1)  | Deletion flag        |

## Contributing

1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

## License

GPL-2.0-or-later. See LICENSE file for details.

## Support

- Issues: GitHub Issue Tracker
- Email: cab@tpwd.de
- Documentation: https://docs.typo3.org/p/tpwd/hyphens/main/en-us/ 
