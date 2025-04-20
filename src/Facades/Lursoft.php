<?php

namespace Sharik709\LursoftPhp\Facades;

use Illuminate\Support\Facades\Facade;
use Sharik709\LursoftPhp\Response\LursoftResponse;
use Sharik709\LursoftPhp\Services\LursoftService;

/**
 * @method static LursoftResponse searchLegalEntity(array $params)
 * @method static LursoftResponse getLegalEntityReport(string $regNumber)
 * @method static LursoftResponse getAnnualReportsList(string $regNumber)
 * @method static LursoftResponse getAnnualReport(string $regNumber, string $year)
 * @method static LursoftResponse getPersonProfile(string $personId)
 * @method static LursoftResponse getPublicPersonReport(string $personId)
 * @method static LursoftResponse searchSanctionsList(array $params)
 * @method static LursoftResponse getSanctionsReport(string $regNumber)
 * @method static LursoftResponse verifySanctionByRegNumber(string $regNumber)
 * @method static LursoftResponse searchEstonianLegalEntity(string $regNumber)
 * @method static LursoftResponse getEstonianLegalEntityReport(string $regNumber)
 * @method static LursoftResponse searchLithuanianLegalEntity(string $regNumber)
 * @method static LursoftResponse getLithuanianLegalEntityReport(string $regNumber)
 * @method static LursoftResponse getDeceasedPersonData(string $personId)
 * @method static LursoftResponse searchLatvianAddress(array $params)
 * @method static LursoftResponse searchLithuanianAddress(array $params)
 * @method static LursoftResponse searchEstonianAddress(array $params)
 * @method static LursoftResponse getCsddVehicleStatement(string $regNumber)
 * @method static LursoftResponse searchInsolvencyProcess(string $regNumber)
 * @method static LursoftResponse getInsolvencyProcessData(string $processId)
 * @method static LursoftResponse getInsolvencyProcessReport(string $processId)
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
