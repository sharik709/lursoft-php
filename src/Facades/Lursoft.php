<?php

namespace Lursoft\LursoftPhp\Facades;

use Illuminate\Support\Facades\Facade;
use Lursoft\LursoftPhp\Services\LursoftService;

/**
 * @method static array searchLegalEntity(array $params)
 * @method static array getLegalEntityReport(string $regNumber)
 * @method static array getAnnualReportsList(string $regNumber)
 * @method static array getAnnualReport(string $regNumber, string $year)
 * @method static array getPersonProfile(string $personId)
 * @method static array getPublicPersonReport(string $personId)
 * @method static array searchSanctionsList(array $params)
 * @method static array getSanctionsReport(string $regNumber)
 * @method static array verifySanctionByRegNumber(string $regNumber)
 * @method static array searchEstonianLegalEntity(string $regNumber)
 * @method static array getEstonianLegalEntityReport(string $regNumber)
 * @method static array searchLithuanianLegalEntity(string $regNumber)
 * @method static array getLithuanianLegalEntityReport(string $regNumber)
 * @method static array getDeceasedPersonData(string $personId)
 * @method static array searchLatvianAddress(array $params)
 * @method static array searchLithuanianAddress(array $params)
 * @method static array searchEstonianAddress(array $params)
 * @method static array getCsddVehicleStatement(string $regNumber)
 * @method static array searchInsolvencyProcess(string $regNumber)
 * @method static array getInsolvencyProcessData(string $processId)
 * @method static array getInsolvencyProcessReport(string $processId)
 *
 * @see LursoftService
 */
class Lursoft extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return LursoftService::class;
    }
}
