<?php

namespace Sharik709\LursoftPhp\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Sharik709\LursoftPhp\Exceptions\LursoftException;
use Sharik709\LursoftPhp\Response\LursoftResponse;
use Psr\SimpleCache\CacheInterface;
use Throwable;

class LursoftService
{
    private Client $client;
    private string $apiKey;
    private string $baseUrl;
    private CacheInterface $cache;
    private ?string $clientId;
    private ?string $clientSecret;
    private ?string $username;
    private ?string $password;
    private ?string $scope;

    public function __construct(
        CacheInterface $cache,
        string $baseUrl = 'https://b2b.lursoft.lv',
        ?string $clientId = null,
        ?string $clientSecret = null,
        ?string $username = null,
        ?string $password = null,
        ?string $scope = null
    ) {
        $this->baseUrl = $baseUrl;
        $this->cache = $cache;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->username = $username;
        $this->password = $password;
        $this->scope = $scope;
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
     * @return LursoftResponse
     * @throws LursoftException
     */
    private function request(string $endpoint, array $data = []): LursoftResponse
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

            $statusCode = $response->getStatusCode();
            $result = json_decode($response->getBody()->getContents(), true);

            if (!is_array($result)) {
                throw new LursoftException('Invalid response format');
            }

            return new LursoftResponse($result, $statusCode);
        } catch (GuzzleException $e) {
            throw new LursoftException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @param array<string, mixed> $params
     * @return LursoftResponse
     * @throws LursoftException
     */
    public function searchLegalEntity(array $params): LursoftResponse
    {
        return $this->request('/?r=search', $params);
    }

    /**
     * @return LursoftResponse
     * @throws LursoftException
     */
    public function getLegalEntityReport(string $regNumber): LursoftResponse
    {
        return $this->request('/?r=company', ['code' => $regNumber]);
    }

    /**
     * @return LursoftResponse
     * @throws LursoftException
     */
    public function getAnnualReportsList(string $regNumber): LursoftResponse
    {
        return $this->request('/?r=annual-report/list', ['code' => $regNumber]);
    }

    /**
     * @return LursoftResponse
     * @throws LursoftException
     */
    public function getAnnualReport(string $regNumber, string $year): LursoftResponse
    {
        return $this->request('/?r=annual-report/view', [
            'code' => $regNumber,
            'year' => $year,
        ]);
    }

    /**
     * @return LursoftResponse
     * @throws LursoftException
     */
    public function getPersonProfile(string $personId): LursoftResponse
    {
        return $this->request('/?r=profile', ['code' => $personId]);
    }

    /**
     * @return LursoftResponse
     * @throws LursoftException
     */
    public function getPublicPersonReport(string $personId): LursoftResponse
    {
        return $this->request('/?r=authority', ['code' => $personId]);
    }

    /**
     * @param array<string, mixed> $params
     * @return LursoftResponse
     * @throws LursoftException
     */
    public function searchSanctionsList(array $params): LursoftResponse
    {
        return $this->request('/?r=sanction/search', $params);
    }

    /**
     * @return LursoftResponse
     * @throws LursoftException
     */
    public function getSanctionsReport(string $regNumber): LursoftResponse
    {
        return $this->request('/?r=sanction/view', ['regcode' => $regNumber]);
    }

    /**
     * @return LursoftResponse
     * @throws LursoftException
     */
    public function verifySanctionByRegNumber(string $regNumber): LursoftResponse
    {
        return $this->request('/?r=sanction/verification', ['regcode' => $regNumber]);
    }

    /**
     * @return LursoftResponse
     * @throws LursoftException
     */
    public function searchEstonianLegalEntity(string $regNumber): LursoftResponse
    {
        return $this->request('/?r=search/est', ['code' => $regNumber]);
    }

    /**
     * @return LursoftResponse
     * @throws LursoftException
     */
    public function getEstonianLegalEntityReport(string $regNumber): LursoftResponse
    {
        return $this->request('/?r=company/est', ['code' => $regNumber]);
    }

    /**
     * @return LursoftResponse
     * @throws LursoftException
     */
    public function searchLithuanianLegalEntity(string $regNumber): LursoftResponse
    {
        return $this->request('/?r=search/ltu', ['code' => $regNumber]);
    }

    /**
     * @return LursoftResponse
     * @throws LursoftException
     */
    public function getLithuanianLegalEntityReport(string $regNumber): LursoftResponse
    {
        return $this->request('/?r=company/ltu', ['code' => $regNumber]);
    }

    /**
     * @return LursoftResponse
     * @throws LursoftException
     */
    public function getDeceasedPersonData(string $personId): LursoftResponse
    {
        return $this->request('/?r=deceased-person', ['code' => $personId]);
    }

    /**
     * @param array<string, mixed> $params
     * @return LursoftResponse
     * @throws LursoftException
     */
    public function searchLatvianAddress(array $params): LursoftResponse
    {
        return $this->request('/?r=address/name-lva', $params);
    }

    /**
     * @param array<string, mixed> $params
     * @return LursoftResponse
     * @throws LursoftException
     */
    public function searchLithuanianAddress(array $params): LursoftResponse
    {
        return $this->request('/?r=address/name-ltu', $params);
    }

    /**
     * @param array<string, mixed> $params
     * @return LursoftResponse
     * @throws LursoftException
     */
    public function searchEstonianAddress(array $params): LursoftResponse
    {
        return $this->request('/?r=address/name-est', $params);
    }

    /**
     * @return LursoftResponse
     * @throws LursoftException
     */
    public function getCsddVehicleStatement(string $regNumber): LursoftResponse
    {
        return $this->request('/?r=vehicle/count', ['regcode' => $regNumber]);
    }

    /**
     * @return LursoftResponse
     * @throws LursoftException
     */
    public function searchInsolvencyProcess(string $regNumber): LursoftResponse
    {
        return $this->request('/?r=insolvency/search', ['code' => $regNumber]);
    }

    /**
     * @return LursoftResponse
     * @throws LursoftException
     */
    public function getInsolvencyProcessData(string $processId): LursoftResponse
    {
        return $this->request('/?r=insolvency/view', ['id' => $processId]);
    }

    /**
     * @return LursoftResponse
     * @throws LursoftException
     */
    public function getInsolvencyProcessReport(string $processId): LursoftResponse
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

        // Try to get credentials from environment if not provided in constructor
        $clientId = $this->clientId ?? getenv('LURSOFT_CLIENT_ID');
        $clientSecret = $this->clientSecret ?? getenv('LURSOFT_CLIENT_SECRET');
        $username = $this->username ?? getenv('LURSOFT_USERNAME');
        $password = $this->password ?? getenv('LURSOFT_PASSWORD');
        $scope = $this->scope ?? getenv('LURSOFT_SCOPE');

        if (!$clientId || !$clientSecret || !$username || !$password) {
            throw new LursoftException('Missing authentication credentials');
        }

        $response = $this->client->post('https://oauth.lursoft.lv/authorize/token', [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'username' => $username,
                'password' => $password,
                'scope' => $scope,
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
