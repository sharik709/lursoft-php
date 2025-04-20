<?php

namespace Sharik709\LursoftPhp\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Sharik709\LursoftPhp\Services\LursoftService;
use Sharik709\LursoftPhp\Exceptions\LursoftException;
use Sharik709\LursoftPhp\Response\LursoftResponse;
use PHPUnit\Framework\TestCase;
use Psr\SimpleCache\CacheInterface;

/**
 * @covers \Sharik709\LursoftPhp\Services\LursoftService
 */
class LursoftServiceTest extends TestCase
{
    private LursoftService $service;
    private MockHandler $mockHandler;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->mockHandler = new MockHandler();
        $handlerStack = HandlerStack::create($this->mockHandler);
        $client = new Client(['handler' => $handlerStack]);

        // Create a proper PSR-16 compatible cache implementation for testing
        $cache = new class implements CacheInterface {
            private array $cache = [];
            private const TTL_SECONDS = 3600; // Default TTL of 1 hour

            public function get(string $key, mixed $default = null): mixed
            {
                if (!$this->has($key)) {
                    return $default;
                }

                $item = $this->cache[$key];

                // Check if item has expired
                if ($item['expiry'] !== null && time() > $item['expiry']) {
                    $this->delete($key);
                    return $default;
                }

                return $item['value'];
            }

            public function set(string $key, mixed $value, null|\DateInterval|int $ttl = null): bool
            {
                $expiry = $this->convertTtlToTimestamp($ttl);

                $this->cache[$key] = [
                    'value' => $value,
                    'expiry' => $expiry
                ];

                return true;
            }

            public function delete(string $key): bool
            {
                if (!$this->has($key)) {
                    return false;
                }

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
                $result = true;
                foreach ($values as $key => $value) {
                    $result = $result && $this->set($key, $value, $ttl);
                }
                return $result;
            }

            public function deleteMultiple(iterable $keys): bool
            {
                $result = true;
                foreach ($keys as $key) {
                    $result = $result && $this->delete($key);
                }
                return $result;
            }

            public function has(string $key): bool
            {
                if (!array_key_exists($key, $this->cache)) {
                    return false;
                }

                $item = $this->cache[$key];

                // Check if item has expired
                if ($item['expiry'] !== null && time() > $item['expiry']) {
                    $this->delete($key);
                    return false;
                }

                return true;
            }

            private function convertTtlToTimestamp(null|\DateInterval|int $ttl): ?int
            {
                // If null, use default TTL
                if ($ttl === null) {
                    return time() + self::TTL_SECONDS;
                }

                // If DateInterval, convert to seconds
                if ($ttl instanceof \DateInterval) {
                    $reference = new \DateTimeImmutable();
                    $endTime = $reference->add($ttl);
                    return $endTime->getTimestamp();
                }

                // If integer, it's already seconds
                if ($ttl > 0) {
                    return time() + $ttl;
                }

                // TTL <= 0 means item has no expiration
                if ($ttl <= 0) {
                    return null;
                }

                // Default fallback
                return time() + self::TTL_SECONDS;
            }
        };

        $this->service = new LursoftService($cache, 'https://api.lursoft.lv');
        $this->setProtectedProperty($this->service, 'client', $client);
    }

    /**
     * @param object $object
     * @param string $property
     * @param mixed $value
     */
    private function setProtectedProperty(object $object, string $property, mixed $value): void
    {
        $reflection = new \ReflectionClass($object);
        $property = $reflection->getProperty($property);
        $property->setAccessible(true);
        $property->setValue($object, $value);
    }

    /**
     * @test
     * @return void
     */
    public function testSearchLegalEntity(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'data' => []
        ])));

        $result = $this->service->searchLegalEntity(['q' => 'test']);

        $this->assertInstanceOf(LursoftResponse::class, $result);
        $this->assertTrue($result->isSuccess());
        $this->assertEquals([], $result->getResults());
    }

    public function testGetLegalEntityReport(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'data' => []
        ])));

        $result = $this->service->getLegalEntityReport('123456789');

        $this->assertInstanceOf(LursoftResponse::class, $result);
        $this->assertTrue($result->isSuccess());
        $this->assertEquals([], $result->getResults());
    }

    public function testGetAnnualReportsList(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'data' => []
        ])));

        $result = $this->service->getAnnualReportsList('123456789');

        $this->assertInstanceOf(LursoftResponse::class, $result);
        $this->assertTrue($result->isSuccess());
        $this->assertEquals([], $result->getResults());
    }

    public function testGetAnnualReport(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'data' => []
        ])));

        $result = $this->service->getAnnualReport('123456789', '2023');

        $this->assertInstanceOf(LursoftResponse::class, $result);
        $this->assertTrue($result->isSuccess());
        $this->assertEquals([], $result->getResults());
    }

    public function testGetPersonProfile(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'data' => []
        ])));

        $result = $this->service->getPersonProfile('123456-12345');

        $this->assertInstanceOf(LursoftResponse::class, $result);
        $this->assertTrue($result->isSuccess());
        $this->assertEquals([], $result->getResults());
    }

    public function testGetPublicPersonReport(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'data' => []
        ])));

        $result = $this->service->getPublicPersonReport('123456-12345');

        $this->assertInstanceOf(LursoftResponse::class, $result);
        $this->assertTrue($result->isSuccess());
        $this->assertEquals([], $result->getResults());
    }

    public function testSearchSanctionsList(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'data' => []
        ])));

        $result = $this->service->searchSanctionsList(['q' => 'test']);

        $this->assertInstanceOf(LursoftResponse::class, $result);
        $this->assertTrue($result->isSuccess());
        $this->assertEquals([], $result->getResults());
    }

    public function testGetSanctionsReport(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'data' => []
        ])));

        $result = $this->service->getSanctionsReport('123456789');

        $this->assertInstanceOf(LursoftResponse::class, $result);
        $this->assertTrue($result->isSuccess());
        $this->assertEquals([], $result->getResults());
    }

    public function testVerifySanctionByRegNumber(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'data' => []
        ])));

        $result = $this->service->verifySanctionByRegNumber('123456789');

        $this->assertInstanceOf(LursoftResponse::class, $result);
        $this->assertTrue($result->isSuccess());
        $this->assertEquals([], $result->getResults());
    }

    public function testSearchEstonianLegalEntity(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'data' => []
        ])));

        $result = $this->service->searchEstonianLegalEntity('123456789');

        $this->assertInstanceOf(LursoftResponse::class, $result);
        $this->assertTrue($result->isSuccess());
        $this->assertEquals([], $result->getResults());
    }

    public function testGetEstonianLegalEntityReport(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'data' => []
        ])));

        $result = $this->service->getEstonianLegalEntityReport('123456789');

        $this->assertInstanceOf(LursoftResponse::class, $result);
        $this->assertTrue($result->isSuccess());
        $this->assertEquals([], $result->getResults());
    }

    public function testSearchLithuanianLegalEntity(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'data' => []
        ])));

        $result = $this->service->searchLithuanianLegalEntity('123456789');

        $this->assertInstanceOf(LursoftResponse::class, $result);
        $this->assertTrue($result->isSuccess());
        $this->assertEquals([], $result->getResults());
    }

    public function testGetLithuanianLegalEntityReport(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'data' => []
        ])));

        $result = $this->service->getLithuanianLegalEntityReport('123456789');

        $this->assertInstanceOf(LursoftResponse::class, $result);
        $this->assertTrue($result->isSuccess());
        $this->assertEquals([], $result->getResults());
    }

    public function testGetDeceasedPersonData(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'data' => []
        ])));

        $result = $this->service->getDeceasedPersonData('123456-12345');

        $this->assertInstanceOf(LursoftResponse::class, $result);
        $this->assertTrue($result->isSuccess());
        $this->assertEquals([], $result->getResults());
    }

    public function testSearchLatvianAddress(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'data' => []
        ])));

        $result = $this->service->searchLatvianAddress(['q' => 'test']);

        $this->assertInstanceOf(LursoftResponse::class, $result);
        $this->assertTrue($result->isSuccess());
        $this->assertEquals([], $result->getResults());
    }

    public function testSearchLithuanianAddress(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'data' => []
        ])));

        $result = $this->service->searchLithuanianAddress(['q' => 'test']);

        $this->assertInstanceOf(LursoftResponse::class, $result);
        $this->assertTrue($result->isSuccess());
        $this->assertEquals([], $result->getResults());
    }

    public function testSearchEstonianAddress(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'data' => []
        ])));

        $result = $this->service->searchEstonianAddress(['q' => 'test']);

        $this->assertInstanceOf(LursoftResponse::class, $result);
        $this->assertTrue($result->isSuccess());
        $this->assertEquals([], $result->getResults());
    }

    public function testGetCsddVehicleStatement(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'data' => []
        ])));

        $result = $this->service->getCsddVehicleStatement('123456789');

        $this->assertInstanceOf(LursoftResponse::class, $result);
        $this->assertTrue($result->isSuccess());
        $this->assertEquals([], $result->getResults());
    }

    public function testSearchInsolvencyProcess(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'data' => []
        ])));

        $result = $this->service->searchInsolvencyProcess('123456789');

        $this->assertInstanceOf(LursoftResponse::class, $result);
        $this->assertTrue($result->isSuccess());
        $this->assertEquals([], $result->getResults());
    }

    public function testGetInsolvencyProcessData(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'data' => []
        ])));

        $result = $this->service->getInsolvencyProcessData('123456789');

        $this->assertInstanceOf(LursoftResponse::class, $result);
        $this->assertTrue($result->isSuccess());
        $this->assertEquals([], $result->getResults());
    }

    public function testGetInsolvencyProcessReport(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'data' => []
        ])));

        $result = $this->service->getInsolvencyProcessReport('123456789');

        $this->assertInstanceOf(LursoftResponse::class, $result);
        $this->assertTrue($result->isSuccess());
        $this->assertEquals([], $result->getResults());
    }

    public function testErrorHandling(): void
    {
        $this->mockHandler->append(new Response(400, [], json_encode([
            'status' => 'error',
            'message' => 'Invalid parameters'
        ])));

        $this->expectException(LursoftException::class);
        $this->service->searchLegalEntity(['invalid' => 'params']);
    }
}
