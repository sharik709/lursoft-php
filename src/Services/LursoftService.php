<?php

namespace Lursoft\LursoftPhp\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Lursoft\LursoftPhp\Exceptions\LursoftException;
use Throwable;

class LursoftService
{
    protected Client $client;
    protected string $baseUrl;
    protected string $apiKey;

    public function __construct(string $apiKey, string $baseUrl = 'https://api.lursoft.lv', ?Client $client = null)
    {
        $this->apiKey = $apiKey;
        $this->baseUrl = $baseUrl;
        $this->client = $client ?? new Client([
            'base_uri' => $this->baseUrl,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    public function setClient(Client $client): void
    {
        $this->client = $client;
    }

    /**
     * @throws LursoftException
     */
    protected function request(string $method, string $endpoint, array $data = []): array
    {
        try {
            $response = $this->client->request($method, $endpoint, [
                'json' => array_merge($data, ['api_key' => $this->apiKey]),
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            throw new LursoftException($e->getMessage(), $e->getCode(), $e);
        }
    }

    // 2.2 Request to search for a legal entity
    public function searchLegalEntity(string $query, array $params = []): array
    {
        return $this->request('POST', '/search/legal-entity', array_merge(['q' => $query], $params));
    }

    // 2.3 Report of legal entity
    public function getLegalEntityReport(string $regNumber): array
    {
        return $this->request('POST', '/legal-entity/report', ['reg_number' => $regNumber]);
    }

    // 2.4 List of annual reports
    public function getAnnualReportsList(string $regNumber): array
    {
        return $this->request('POST', '/annual-reports/list', ['reg_number' => $regNumber]);
    }

    // 2.5 Annual report
    public function getAnnualReport(string $regNumber, string $year): array
    {
        return $this->request('POST', '/annual-report', [
            'reg_number' => $regNumber,
            'year' => $year
        ]);
    }

    // 2.6 Person's profile
    public function getPersonProfile(string $personCode): array
    {
        return $this->request('POST', '/person/profile', ['person_code' => $personCode]);
    }

    // 2.7 Report of public person or institution
    public function getPublicPersonReport(string $regNumber): array
    {
        return $this->request('POST', '/public-person/report', ['reg_number' => $regNumber]);
    }

    // 2.8 Checking in the lists of sanctions
    public function searchSanctionsList(string $query, array $params = []): array
    {
        return $this->request('POST', '/sanctions/search', array_merge(['q' => $query], $params));
    }

    public function getSanctionsReport(string $subjectId): array
    {
        return $this->request('POST', '/sanctions/report', ['subject_id' => $subjectId]);
    }

    public function verifySanctionByRegNumber(string $regNumber): array
    {
        return $this->request('POST', '/sanctions/verify', ['reg_number' => $regNumber]);
    }

    // 2.10 Legal entity data of Estonia
    public function searchEstonianLegalEntity(string $query): array
    {
        return $this->request('POST', '/estonia/legal-entity/search', ['q' => $query]);
    }

    public function getEstonianLegalEntityReport(string $regNumber): array
    {
        return $this->request('POST', '/estonia/legal-entity/report', ['reg_number' => $regNumber]);
    }

    // 2.11 Legal entity data of Lithuanian
    public function searchLithuanianLegalEntity(string $query): array
    {
        return $this->request('POST', '/lithuania/legal-entity/search', ['q' => $query]);
    }

    public function getLithuanianLegalEntityReport(string $regNumber): array
    {
        return $this->request('POST', '/lithuania/legal-entity/report', ['reg_number' => $regNumber]);
    }

    // 2.12 Deceased Person Data
    public function getDeceasedPersonData(string $personCode): array
    {
        return $this->request('POST', '/deceased-person/data', ['person_code' => $personCode]);
    }

    // 3. Address data
    public function searchLatvianAddress(string $query, array $params = []): array
    {
        return $this->request('POST', '/latvia/address/search', array_merge(['q' => $query], $params));
    }

    public function searchLithuanianAddress(string $query, array $params = []): array
    {
        return $this->request('POST', '/lithuania/address/search', array_merge(['q' => $query], $params));
    }

    public function searchEstonianAddress(string $query, array $params = []): array
    {
        return $this->request('POST', '/estonia/address/search', array_merge(['q' => $query], $params));
    }

    // 4. Data from "Road Traffic Safety Directorate" (CSDD)
    public function getCsddVehicleStatement(string $regNumber): array
    {
        return $this->request('POST', '/csdd/vehicle-statement', ['reg_number' => $regNumber]);
    }

    // 5. Insolvency Process Data
    public function searchInsolvencyProcess(string $query): array
    {
        return $this->request('POST', '/insolvency/search', ['q' => $query]);
    }

    public function getInsolvencyProcessData(string $processId): array
    {
        return $this->request('POST', '/insolvency/data', ['process_id' => $processId]);
    }

    public function getInsolvencyProcessReport(string $processId): array
    {
        return $this->request('POST', '/insolvency/report', ['process_id' => $processId]);
    }
}
