<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Sharik709\LursoftPhp\Services\LursoftService;
use Sharik709\LursoftPhp\Exceptions\LursoftException;
use Psr\SimpleCache\CacheInterface;

// First, create a simple cache implementation
$cache = new class implements CacheInterface {
    private array $cache = [];

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->cache[$key] ?? $default;
    }

    public function set(string $key, mixed $value, null|\DateInterval|int $ttl = null): bool
    {
        $this->cache[$key] = $value;
        return true;
    }

    public function delete(string $key): bool
    {
        unset($this->cache[$key]);
        return true;
    }

    public function clear(): bool
    {
        $this->cache = [];
        return true;
    }

    public function getMultiple(iterable $keys, mixed $default = null): iterable
    {
        $result = [];
        foreach ($keys as $key) {
            $result[$key] = $this->get($key, $default);
        }
        return $result;
    }

    public function setMultiple(iterable $values, null|\DateInterval|int $ttl = null): bool
    {
        foreach ($values as $key => $value) {
            $this->set($key, $value, $ttl);
        }
        return true;
    }

    public function deleteMultiple(iterable $keys): bool
    {
        foreach ($keys as $key) {
            $this->delete($key);
        }
        return true;
    }

    public function has(string $key): bool
    {
        return isset($this->cache[$key]);
    }
};

// Create the Lursoft service
try {
    $lursoft = new LursoftService($cache);

    // Example 1: Handling successful response with results
    echo "Example 1: Successful response with results\n";
    echo "--------------------------------------------\n";

    // This is a mock example - replace with your actual API key and query
    // $response = $lursoft->searchLegalEntity(['q' => 'Company Name']);

    // For demonstration, let's create a mock response
    $mockSuccessResponse = new \Sharik709\LursoftPhp\Response\LursoftResponse([
        'status' => 'success',
        'data' => [
            ['name' => 'Company A', 'regcode' => '12345678901'],
            ['name' => 'Company B', 'regcode' => '12345678902'],
        ]
    ], 200);

    // Check if the response was successful
    if ($mockSuccessResponse->isSuccess()) {
        echo "Success: " . ($mockSuccessResponse->isSuccess() ? 'true' : 'false') . "\n";
        echo "Status code: " . $mockSuccessResponse->getStatusCode() . "\n";

        // Get the results array
        $results = $mockSuccessResponse->getResults();

        // Check if there are any results
        if ($mockSuccessResponse->hasResults()) {
            echo "Found " . count($results) . " companies:\n";
            foreach ($results as $company) {
                echo "- " . $company['name'] . " (" . $company['regcode'] . ")\n";
            }
        } else {
            echo "No results found\n";
        }
    } else {
        echo "Error: " . $mockSuccessResponse->getMessage() . "\n";
    }

    // Example 2: Handling successful response with empty results
    echo "\nExample 2: Successful response with empty results\n";
    echo "-----------------------------------------------\n";

    $mockEmptyResponse = new \Sharik709\LursoftPhp\Response\LursoftResponse([
        'status' => 'success',
        'data' => []
    ], 200);

    if ($mockEmptyResponse->isSuccess()) {
        echo "Success: " . ($mockEmptyResponse->isSuccess() ? 'true' : 'false') . "\n";
        echo "Status code: " . $mockEmptyResponse->getStatusCode() . "\n";

        if ($mockEmptyResponse->hasResults()) {
            echo "Found results (unexpected in this example)\n";
        } else {
            echo "No results found - expected behavior for this example\n";
        }
    } else {
        echo "Error: " . $mockEmptyResponse->getMessage() . "\n";
    }

    // Example 3: Handling error response
    echo "\nExample 3: Error response\n";
    echo "----------------------\n";

    $mockErrorResponse = new \Sharik709\LursoftPhp\Response\LursoftResponse([
        'status' => 'error',
        'message' => 'Invalid parameters provided'
    ], 400);

    if ($mockErrorResponse->isSuccess()) {
        echo "Success: " . ($mockErrorResponse->isSuccess() ? 'true' : 'false') . "\n";
    } else {
        echo "Success: " . ($mockErrorResponse->isSuccess() ? 'true' : 'false') . "\n";
        echo "Status code: " . $mockErrorResponse->getStatusCode() . "\n";
        echo "Error message: " . $mockErrorResponse->getMessage() . "\n";
    }

    // Example 4: Using toArray() method
    echo "\nExample 4: Using toArray() method\n";
    echo "-----------------------------\n";

    $arrayResponse = $mockSuccessResponse->toArray();
    echo "Converting response to array:\n";
    echo "- success: " . ($arrayResponse['success'] ? 'true' : 'false') . "\n";
    echo "- status_code: " . $arrayResponse['status_code'] . "\n";
    echo "- message: " . ($arrayResponse['message'] ?? 'null') . "\n";
    echo "- data count: " . count($arrayResponse['data']) . "\n";
    echo "- Has raw_response: " . (isset($arrayResponse['raw_response']) ? 'yes' : 'no') . "\n";

} catch (LursoftException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
