<?php

namespace Lursoft\LursoftPhp\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Lursoft\LursoftPhp\Exceptions\LursoftException;
use Throwable;

class LursoftService
{
    private Client $client;
    private string $apiKey;
    private string $baseUrl;

    public function __construct(string $baseUrl = 'https://api.lursoft.lv')
    {
        $this->baseUrl = $baseUrl;
        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'timeout' => 30,
        ]);
    }

    public function setClient(Client $client): void
    {
        $this->client = $client;
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     * @throws LursoftException
     */
    private function request(string $endpoint, array $data = []): array
    {
        try {
            $response = $this->client->post($endpoint, [
                'json' => $data,
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->getAccessToken(),
                ],
            ]);

            $result = json_decode($response->getBody()->getContents(), true);

            if (!is_array($result)) {
                throw new LursoftException('Invalid response format');
            }

            return $result;
        } catch (GuzzleException $e) {
            throw new LursoftException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     * @throws LursoftException
     */
    public function searchLegalEntity(array $params): array
    {
        return $this->request('/search_legal_entity', $params);
    }

    /**
     * @return array<string, mixed>
     * @throws LursoftException
     */
    public function getLegalEntityReport(string $regNumber): array
    {
        return $this->request('/legal_entity_report', ['reg_number' => $regNumber]);
    }

    /**
     * @return array<string, mixed>
     * @throws LursoftException
     */
    public function getAnnualReportsList(string $regNumber): array
    {
        return $this->request('/annual_reports_list', ['reg_number' => $regNumber]);
    }

    /**
     * @return array<string, mixed>
     * @throws LursoftException
     */
    public function getAnnualReport(string $regNumber, string $year): array
    {
        return $this->request('/annual_report', [
            'reg_number' => $regNumber,
            'year' => $year,
        ]);
    }

    /**
     * @return array<string, mixed>
     * @throws LursoftException
     */
    public function getPersonProfile(string $personId): array
    {
        return $this->request('/person_profile', ['person_id' => $personId]);
    }

    /**
     * @return array<string, mixed>
     * @throws LursoftException
     */
    public function getPublicPersonReport(string $personId): array
    {
        return $this->request('/public_person_report', ['person_id' => $personId]);
    }

    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     * @throws LursoftException
     */
    public function searchSanctionsList(array $params): array
    {
        return $this->request('/search_sanctions_list', $params);
    }

    /**
     * @return array<string, mixed>
     * @throws LursoftException
     */
    public function getSanctionsReport(string $regNumber): array
    {
        return $this->request('/sanctions_report', ['reg_number' => $regNumber]);
    }

    /**
     * @return array<string, mixed>
     * @throws LursoftException
     */
    public function verifySanctionByRegNumber(string $regNumber): array
    {
        return $this->request('/verify_sanction_by_reg_number', ['reg_number' => $regNumber]);
    }

    /**
     * @return array<string, mixed>
     * @throws LursoftException
     */
    public function searchEstonianLegalEntity(string $regNumber): array
    {
        return $this->request('/search_estonian_legal_entity', ['reg_number' => $regNumber]);
    }

    /**
     * @return array<string, mixed>
     * @throws LursoftException
     */
    public function getEstonianLegalEntityReport(string $regNumber): array
    {
        return $this->request('/estonian_legal_entity_report', ['reg_number' => $regNumber]);
    }

    /**
     * @return array<string, mixed>
     * @throws LursoftException
     */
    public function searchLithuanianLegalEntity(string $regNumber): array
    {
        return $this->request('/search_lithuanian_legal_entity', ['reg_number' => $regNumber]);
    }

    /**
     * @return array<string, mixed>
     * @throws LursoftException
     */
    public function getLithuanianLegalEntityReport(string $regNumber): array
    {
        return $this->request('/lithuanian_legal_entity_report', ['reg_number' => $regNumber]);
    }

    /**
     * @return array<string, mixed>
     * @throws LursoftException
     */
    public function getDeceasedPersonData(string $personId): array
    {
        return $this->request('/deceased_person_data', ['person_id' => $personId]);
    }

    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     * @throws LursoftException
     */
    public function searchLatvianAddress(array $params): array
    {
        return $this->request('/search_latvian_address', $params);
    }

    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     * @throws LursoftException
     */
    public function searchLithuanianAddress(array $params): array
    {
        return $this->request('/search_lithuanian_address', $params);
    }

    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     * @throws LursoftException
     */
    public function searchEstonianAddress(array $params): array
    {
        return $this->request('/search_estonian_address', $params);
    }

    /**
     * @return array<string, mixed>
     * @throws LursoftException
     */
    public function getCsddVehicleStatement(string $regNumber): array
    {
        return $this->request('/csdd_vehicle_statement', ['reg_number' => $regNumber]);
    }

    /**
     * @return array<string, mixed>
     * @throws LursoftException
     */
    public function searchInsolvencyProcess(string $regNumber): array
    {
        return $this->request('/search_insolvency_process', ['reg_number' => $regNumber]);
    }

    /**
     * @return array<string, mixed>
     * @throws LursoftException
     */
    public function getInsolvencyProcessData(string $processId): array
    {
        return $this->request('/insolvency_process_data', ['process_id' => $processId]);
    }

    /**
     * @return array<string, mixed>
     * @throws LursoftException
     */
    public function getInsolvencyProcessReport(string $processId): array
    {
        return $this->request('/insolvency_process_report', ['process_id' => $processId]);
    }

    public function getAccessToken(): string
    {
        $cacheKey = 'lursoft_access_token';
        $cache = $this->cache->get($cacheKey);

        if ($cache) {
            return $cache;
        }

        $response = $this->client->post('/oauth/token', [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => config('lursoft.client_id'),
                'client_secret' => config('lursoft.client_secret'),
                'username' => config('lursoft.username'),
                'password' => config('lursoft.password'),
                'scope' => config('lursoft.scope'),
            ],
        ]);

        $data = json_decode($response->getBody()->getContents(), true);

        if (!isset($data['access_token'])) {
            throw new LursoftException('Invalid response format');
        }

        $this->cache->set($cacheKey, $data['access_token'], $data['expires_in']);

        return $data['access_token'];
    }
}
