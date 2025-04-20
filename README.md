# Lursoft PHP

[![Latest Version](https://img.shields.io/packagist/v/lursoft/lursoft-php.svg?style=flat-square)](https://packagist.org/packages/lursoft/lursoft-php)
[![Total Downloads](https://img.shields.io/packagist/dt/lursoft/lursoft-php.svg?style=flat-square)](https://packagist.org/packages/lursoft/lursoft-php)
[![License](https://img.shields.io/packagist/l/lursoft/lursoft-php.svg?style=flat-square)](https://packagist.org/packages/lursoft/lursoft-php)
[![PHP Version](https://img.shields.io/packagist/php-v/lursoft/lursoft-php.svg?style=flat-square)](https://packagist.org/packages/lursoft/lursoft-php)
[![Tests](https://github.com/sharik709/lursoft-php/actions/workflows/tests.yml/badge.svg)](https://github.com/sharik709/lursoft-php/actions/workflows/tests.yml)
[![PHPStan](https://github.com/sharik709/lursoft-php/actions/workflows/phpstan.yml/badge.svg)](https://github.com/sharik709/lursoft-php/actions/workflows/phpstan.yml)

A PHP package for integrating with the Lursoft API service. This package provides a simple and elegant way to interact with the Lursoft API in your PHP applications.

## Features

- Complete coverage of Lursoft API endpoints
- Laravel integration with service provider and facade
- Type-safe API responses
- Comprehensive error handling
- Built-in rate limiting support
- Detailed documentation
- Extensive test coverage
- PSR-12 compliant code

## Requirements

- PHP 8.1 or higher
- Composer
- Laravel 9.x or 10.x (for Laravel integration)

## Installation

You can install the package via composer:

```bash
composer require lursoft/lursoft-php
```

### Laravel

For Laravel applications, the package will automatically register its service provider.

Publish the configuration file:

```bash
php artisan vendor:publish --provider="Lursoft\LursoftPhp\Providers\LursoftServiceProvider" --tag="config"
```

## Authentication

The package uses OAuth 2.0 for authentication. The `getAccessToken()` method handles the OAuth token acquisition and caching automatically. The following environment variables are required:

```
LURSOFT_CLIENT_ID=your_client_id
LURSOFT_CLIENT_SECRET=your_client_secret
LURSOFT_USERNAME=your_username
LURSOFT_PASSWORD=your_password
LURSOFT_SCOPE=your_scope
LURSOFT_BASE_URL=https://api.lursoft.lv
```

The access token is automatically cached and refreshed when expired. You don't need to manually handle the token management as it's taken care of by the service.

## Usage

### Laravel

```php
use Lursoft\LursoftPhp\Facades\Lursoft;

// Search for a legal entity
$results = Lursoft::searchLegalEntity('Company Name');

// Get legal entity report
$companyInfo = Lursoft::getLegalEntityReport('123456789');

// Get annual reports list
$annualReports = Lursoft::getAnnualReportsList('123456789');

// Get specific annual report
$annualReport = Lursoft::getAnnualReport('123456789', '2023');

// Get person's profile
$personProfile = Lursoft::getPersonProfile('123456-12345');

// Get public person/institution report
$publicPersonInfo = Lursoft::getPublicPersonReport('123456789');

// Search sanctions list
$sanctions = Lursoft::searchSanctionsList('Company Name');

// Get sanctions report
$sanctionDetails = Lursoft::getSanctionsReport('subject_id');

// Verify sanction by registration number
$sanctionVerification = Lursoft::verifySanctionByRegNumber('123456789');

// Search Estonian legal entities
$estonianCompanies = Lursoft::searchEstonianLegalEntity('Company Name');

// Get Estonian legal entity report
$estonianCompanyInfo = Lursoft::getEstonianLegalEntityReport('123456789');

// Search Lithuanian legal entities
$lithuanianCompanies = Lursoft::searchLithuanianLegalEntity('Company Name');

// Get Lithuanian legal entity report
$lithuanianCompanyInfo = Lursoft::getLithuanianLegalEntityReport('123456789');

// Get deceased person data
$deceasedPersonData = Lursoft::getDeceasedPersonData('123456-12345');

// Search Latvian addresses
$latvianAddresses = Lursoft::searchLatvianAddress('Riga, Brivibas');

// Search Lithuanian addresses
$lithuanianAddresses = Lursoft::searchLithuanianAddress('Vilnius, Gedimino');

// Search Estonian addresses
$estonianAddresses = Lursoft::searchEstonianAddress('Tallinn, Narva');

// Get CSDD vehicle statement
$vehicleStatement = Lursoft::getCsddVehicleStatement('123456789');

// Search insolvency processes
$insolvencyProcesses = Lursoft::searchInsolvencyProcess('Company Name');

// Get insolvency process data
$insolvencyData = Lursoft::getInsolvencyProcessData('process_id');

// Get insolvency process report
$insolvencyReport = Lursoft::getInsolvencyProcessReport('process_id');
```

### Standalone PHP

```php
use Lursoft\LursoftPhp\Services\LursoftService;

$lursoft = new LursoftService();

// All methods are available the same way as in Laravel
$companyInfo = $lursoft->getLegalEntityReport('123456789');
```

## Available Methods

### Authentication
- `getAccessToken()`: You don't need to call this. It would be triggered and cached automatically

### Legal Entity Methods
- `searchLegalEntity(string $query, array $params = [])`: Search for legal entities
- `getLegalEntityReport(string $regNumber)`: Get detailed information about a legal entity
- `getAnnualReportsList(string $regNumber)`: Get list of annual reports
- `getAnnualReport(string $regNumber, string $year)`: Get specific annual report

### Person Methods
- `getPersonProfile(string $personCode)`: Get person's profile information
- `getDeceasedPersonData(string $personCode)`: Get deceased person data

### Public Person/Institution Methods
- `getPublicPersonReport(string $regNumber)`: Get public person/institution report

### Sanctions Methods
- `searchSanctionsList(string $query, array $params = [])`: Search in sanctions lists
- `getSanctionsReport(string $subjectId)`: Get sanctions report
- `verifySanctionByRegNumber(string $regNumber)`: Verify sanction by registration number

### Baltic Countries Methods
- `searchEstonianLegalEntity(string $query)`: Search Estonian legal entities
- `getEstonianLegalEntityReport(string $regNumber)`: Get Estonian legal entity report
- `searchLithuanianLegalEntity(string $query)`: Search Lithuanian legal entities
- `getLithuanianLegalEntityReport(string $regNumber)`: Get Lithuanian legal entity report

### Address Methods
- `searchLatvianAddress(string $query, array $params = [])`: Search Latvian addresses
- `searchLithuanianAddress(string $query, array $params = [])`: Search Lithuanian addresses
- `searchEstonianAddress(string $query, array $params = [])`: Search Estonian addresses

### CSDD Methods
- `getCsddVehicleStatement(string $regNumber)`: Get CSDD vehicle statement

### Insolvency Methods
- `searchInsolvencyProcess(string $query)`: Search insolvency processes
- `getInsolvencyProcessData(string $processId)`: Get insolvency process data
- `getInsolvencyProcessReport(string $processId)`: Get insolvency process report

## Error Handling

The package provides comprehensive error handling through the `LursoftException` class. All API errors will be thrown as exceptions that you can catch and handle appropriately:

```php
try {
    $result = $lursoft->searchLegalEntity('Company Name');
} catch (Lursoft\LursoftPhp\Exceptions\LursoftException $e) {
    // Handle the error
    echo $e->getMessage();
}
```

## Rate Limiting

The package supports rate limiting to prevent hitting API limits. You can configure the rate limiting settings in the configuration file:

```php
// config/lursoft.php
return [
    'rate_limit' => [
        'enabled' => true,
        'requests_per_minute' => 60,
    ],
];
```

## Testing

```bash
composer test
```

## Contributing

Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

## Security

If you discover any security-related issues, please email shaikhsharik709@gmail.com instead of using the issue tracker.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Author

- **Sharik Shaikh** - [GitHub](https://github.com/sharik709) - [Email](mailto:shaikhsharik709@gmail.com)
