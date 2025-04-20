<?php

namespace Sharik709\LursoftPhp\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Sharik709\LursoftPhp\Exceptions\LursoftException;
use Psr\SimpleCache\CacheInterface;
use Throwable;

class LursoftService
{
    private Client $client;
    private string $apiKey;
    private string $baseUrl;
    private CacheInterface $cache;

    public function __construct(
        CacheInterface $cache,
        string $baseUrl = 'https://b2b.lursoft.lv'
    ) {
        $this->baseUrl = $baseUrl;
        $this->cache = $cache;
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
            // Parse the endpoint URL to extract existing query parameters
            $parsedUrl = parse_url($endpoint);
            $path = $parsedUrl['path'] ?? '/';

            // Parse any query parameters in the endpoint
            $queryParams = [];
            if (isset($parsedUrl['query'])) {
                parse_str($parsedUrl['query'], $queryParams);
            }

            // Merge the endpoint query parameters with the user-provided data
            $mergedParams = array_merge($queryParams, $data);

            $response = $this->client->get($path, [
                'query' => $mergedParams,
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
        return $this->request('/?r=search', $params);
    }

    /**
     * @return array<string, mixed>
     * @throws LursoftException
     */
    public function getLegalEntityReport(string $regNumber): array
    {
        return $this->request('/?r=company', ['code' => $regNumber]);
    }

    /**
     * @return array<string, mixed>
     * @throws LursoftException
     */
    public function getAnnualReportsList(string $regNumber): array
    {
        return $this->request('/?r=annual-report/list', ['code' => $regNumber]);
    }

    /**
     * @return array<string, mixed>
     * @throws LursoftException
     */
    public function getAnnualReport(string $regNumber, string $year): array
    {
        return $this->request('/?r=annual-report/view', [
            'code' => $regNumber,
            'year' => $year,
        ]);
    }

    /**
     * @return array<string, mixed>
     * @throws LursoftException
     */
    public function getPersonProfile(string $personId): array
    {
        return $this->request('/?r=profile', ['code' => $personId]);
    }

    /**
     * @return array<string, mixed>
     * @throws LursoftException
     */
    public function getPublicPersonReport(string $personId): array
    {
        return $this->request('/?r=authority', ['code' => $personId]);
    }

    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     * @throws LursoftException
     */
    public function searchSanctionsList(array $params): array
    {
        return $this->request('/?r=sanction/search', $params);
    }

    /**
     * @return array<string, mixed>
     * @throws LursoftException
     */
    public function getSanctionsReport(string $regNumber): array
    {
        return $this->request('/?r=sanction/view', ['regcode' => $regNumber]);
    }

    /**
     * @return array<string, mixed>
     * @throws LursoftException
     */
    public function verifySanctionByRegNumber(string $regNumber): array
    {
        return $this->request('/?r=sanction/verification', ['regcode' => $regNumber]);
    }

    /**
     * @return array<string, mixed>
     * @throws LursoftException
     */
    public function searchEstonianLegalEntity(string $regNumber): array
    {
        return $this->request('/?r=search/est', ['code' => $regNumber]);
    }

    /**
     * @return array<string, mixed>
     * @throws LursoftException
     */
    public function getEstonianLegalEntityReport(string $regNumber): array
    {
        return $this->request('/?r=company/est', ['code' => $regNumber]);
    }

    /**
     * @return array<string, mixed>
     * @throws LursoftException
     */
    public function searchLithuanianLegalEntity(string $regNumber): array
    {
        return $this->request('/?r=search/ltu', ['code' => $regNumber]);
    }

    /**
     * @return array<string, mixed>
     * @throws LursoftException
     */
    public function getLithuanianLegalEntityReport(string $regNumber): array
    {
        return $this->request('/?r=company/ltu', ['code' => $regNumber]);
    }

    /**
     * @return array<string, mixed>
     * @throws LursoftException
     */
    public function getDeceasedPersonData(string $personId): array
    {
        return $this->request('/?r=deceased-person', ['code' => $personId]);
    }

    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     * @throws LursoftException
     */
    public function searchLatvianAddress(array $params): array
    {
        return $this->request('/?r=address/name-lva', $params);
    }

    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     * @throws LursoftException
     */
    public function searchLithuanianAddress(array $params): array
    {
        return $this->request('/?r=address/name-ltu', $params);
    }

    /**
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     * @throws LursoftException
     */
    public function searchEstonianAddress(array $params): array
    {
        return $this->request('/?r=address/name-est', $params);
    }

    /**
     * @return array<string, mixed>
     * @throws LursoftException
     */
    public function getCsddVehicleStatement(string $regNumber): array
    {
        return $this->request('/?r=vehicle/count', ['regcode' => $regNumber]);
    }

    /**
     * @return array<string, mixed>
     * @throws LursoftException
     */
    public function searchInsolvencyProcess(string $regNumber): array
    {
        return $this->request('/?r=insolvency/search', ['code' => $regNumber]);
    }

    /**
     * @return array<string, mixed>
     * @throws LursoftException
     */
    public function getInsolvencyProcessData(string $processId): array
    {
        return $this->request('/?r=insolvency/view', ['id' => $processId]);
    }

    /**
     * @return array<string, mixed>
     * @throws LursoftException
     */
    public function getInsolvencyProcessReport(string $processId): array
    {
        return $this->request('/?r=insolvency/date-status', ['id' => $processId]);
    }

    public function getAccessToken(): string
    {
        $cacheKey = 'lursoft_access_token';
        $cache = $this->cache->get($cacheKey);

        if ($cache) {
            return $cache;
        }

        $response = $this->client->post('https://oauth.lursoft.lv/authorize/token', [
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
