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
composer require sharik709/lursoft-php
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

## Basic Usage

### Laravel

```php
use Sharik709\LursoftPhp\Facades\Lursoft;
use Sharik709\LursoftPhp\Exceptions\LursoftException;

try {
    // Search for legal entities
    $response = Lursoft::searchLegalEntity(['q' => 'Company Name']);

    // Check if the response was successful
    if ($response->isSuccess()) {
        // Get the results array
        $results = $response->getResults();

        // Check if there are any results
        if ($response->hasResults()) {
            foreach ($results as $company) {
                echo $company['name'] . "\n";
            }
        } else {
            echo "No results found";
        }
    } else {
        echo "Error: " . $response->getMessage();
    }

} catch (LursoftException $e) {
    // Handle any errors
    echo $e->getMessage();
}
```

### Standalone PHP

```php
use Sharik709\LursoftPhp\Services\LursoftService;
use Sharik709\LursoftPhp\Exceptions\LursoftException;
use Psr\SimpleCache\CacheInterface;

try {
    // You need to provide a PSR-16 compatible cache implementation
    // Here's an example of creating a cache service
    $cache = /* Your PSR-16 compatible cache implementation */;

    $lursoft = new LursoftService($cache);

    // All methods are available the same way as in Laravel
    $response = $lursoft->getLegalEntityReport('123456789');

    if ($response->isSuccess()) {
        $companyData = $response->getResults();
        // Process data
    } else {
        echo "Error: " . $response->getMessage();
    }
} catch (LursoftException $e) {
    // Handle any errors
    echo $e->getMessage();
}
```

## Response Object

All API calls return a `LursoftResponse` object which provides a consistent interface:

```php
// Check if the request was successful
$isSuccess = $response->isSuccess();

// Get the full response data as received from Lursoft API
$rawData = $response->getData();

// Get only the results data (the 'data' key from the response)
$results = $response->getResults();

// Check if there are any results in the response
$hasResults = $response->hasResults();

// Get any error message if the request failed
$errorMessage = $response->getMessage();

// Get the HTTP status code
$statusCode = $response->getStatusCode();

// Convert the response to an array with standardized structure
$array = $response->toArray();
// The array contains: 'success', 'message', 'status_code', 'data', and 'raw_response' keys
```

## Available Methods and Examples

### Legal Entity Methods

#### Search for Legal Entities

```php
// Basic search by name
$response = Lursoft::searchLegalEntity(['q' => 'Lursoft']);

// Search with additional filters
$response = Lursoft::searchLegalEntity([
    'q' => 'Lursoft',
    'limit' => 10,
    'offset' => 0
]);
```

#### Get Legal Entity Report

```php
// Get detailed company information by registration number
$response = Lursoft::getLegalEntityReport('40003170000');

// Get company information with specific sections
$response = Lursoft::getLegalEntityReport('40003170000', [
    'section' => 'ASX' // Current officials, shareholders, and address coordinates
]);
```

#### Annual Reports

```php
// Get a list of all annual reports for a company
$response = Lursoft::getAnnualReportsList('40003170000');

// Get a specific annual report
$response = Lursoft::getAnnualReport('40003170000', '2022');
```

### Person Methods

#### Get Person Profile

```php
// Get a person's profile information
// Format: '123456-12345'
$response = Lursoft::getPersonProfile('123456-12345');

// Get profile with specific sections
$response = Lursoft::getPersonProfile('123456-12345', [
    'section' => 'ASP' // Current posts, memberships, procurations
]);
```

#### Get Deceased Person Data

```php
// Check if a person is deceased
$response = Lursoft::getDeceasedPersonData('123456-12345');
```

### Public Person/Institution Methods

```php
// Get information about a public institution
$response = Lursoft::getPublicPersonReport('90000000000');

// Get with subordinate institutions
$response = Lursoft::getPublicPersonReport('90000000000', [
    'section' => 'S'
]);
```

### Sanctions Methods

#### Search in Sanctions Lists

```php
// Search for a person or entity in sanctions lists
$response = Lursoft::searchSanctionsList([
    'name' => 'John+Doe'
]);

// Search with specific lists and parameters
$response = Lursoft::searchSanctionsList([
    'q' => 'John Doe',
    'list' => 'UN,EU,OFAC',
    'type' => 'FP', // FP for individuals, JP for entities
    'precise' => 1
]);
```

#### Get Sanctions Report

```php
// Get detailed sanctions information
$response = Lursoft::getSanctionsReport('SANCTIONED_ID');
```

#### Verify Sanction by Registration Number

```php
// Check if a company is under sanctions
$response = Lursoft::verifySanctionByRegNumber('40003170000');
```

### Baltic Countries Methods

#### Estonian Legal Entities

```php
// Search for Estonian companies
$response = Lursoft::searchEstonianLegalEntity('Company Name');

// Get Estonian company report
$response = Lursoft::getEstonianLegalEntityReport('EE12345678');
```

#### Lithuanian Legal Entities

```php
// Search for Lithuanian companies
$response = Lursoft::searchLithuanianLegalEntity('Company Name');

// Get Lithuanian company report
$response = Lursoft::getLithuanianLegalEntityReport('LT12345678');
```

### Address Methods

```php
// Search for Latvian addresses
$response = Lursoft::searchLatvianAddress([
    'name' => 'Riga+Street',
    'level' => 'street'
]);

// Search for Lithuanian addresses
$response = Lursoft::searchLithuanianAddress([
    'name' => 'Vilnius+Street'
]);

// Search for Estonian addresses
$response = Lursoft::searchEstonianAddress([
    'name' => 'Tallinn+Street'
]);
```

### CSDD Methods

```php
// Get vehicle statement from CSDD
$response = Lursoft::getCsddVehicleStatement('VEHICLE_REG_NUMBER');
```

### Insolvency Methods

```php
// Search for insolvency processes
$response = Lursoft::searchInsolvencyProcess('40003170000');

// Get insolvency process data
$response = Lursoft::getInsolvencyProcessData('PROCESS_ID');

// Get insolvency process report
$response = Lursoft::getInsolvencyProcessReport('PROCESS_ID');
```

## Error Handling

The package provides comprehensive error handling through the `LursoftException` class. All API errors will be thrown as exceptions that you can catch and handle appropriately:

```php
try {
    $result = Lursoft::searchLegalEntity(['q' => 'Company Name']);
} catch (Sharik709\LursoftPhp\Exceptions\LursoftException $e) {
    // Get error message
    echo $e->getMessage();

    // Get error code
    echo $e->getCode();

    // Handle specific error types
    if ($e->getCode() === 401) {
        // Handle authentication error
    } elseif ($e->getCode() === 404) {
        // Handle not found error
    } elseif ($e->getCode() === 429) {
        // Handle rate limit exceeded
    } else {
        // Handle other errors
    }
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
