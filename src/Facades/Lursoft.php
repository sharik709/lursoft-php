<?php

namespace Lursoft\LursoftPhp\Facades;

use Illuminate\Support\Facades\Facade;
use Lursoft\LursoftPhp\Services\LursoftService;

/**
 * @method static array searchLegalEntity(string $query, array $params = [])
 * @method static array getLegalEntityReport(string $regNumber)
 * @method static array getAnnualReportsList(string $regNumber)
 * @method static array getAnnualReport(string $regNumber, string $year)
 * @method static array getPersonProfile(string $personCode)
 * @method static array getPublicPersonReport(string $regNumber)
 * @method static array searchSanctionsList(string $query, array $params = [])
 * @method static array getSanctionsReport(string $subjectId)
 * @method static array verifySanctionByRegNumber(string $regNumber)
 * @method static array searchEstonianLegalEntity(string $query)
 * @method static array getEstonianLegalEntityReport(string $regNumber)
 * @method static array searchLithuanianLegalEntity(string $query)
 * @method static array getLithuanianLegalEntityReport(string $regNumber)
 * @method static array getDeceasedPersonData(string $personCode)
 * @method static array searchLatvianAddress(string $query, array $params = [])
 * @method static array searchLithuanianAddress(string $query, array $params = [])
 * @method static array searchEstonianAddress(string $query, array $params = [])
 * @method static array getCsddVehicleStatement(string $regNumber)
 * @method static array searchInsolvencyProcess(string $query)
 * @method static array getInsolvencyProcessData(string $processId)
 * @method static array getInsolvencyProcessReport(string $processId)
 *
 * @see LursoftService
 */
class Lursoft extends Facade
{
    protected static function getFacadeAccessor()
    {
        return LursoftService::class;
    }
}
