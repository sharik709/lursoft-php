<?php

namespace Lursoft\LursoftPhp\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Lursoft\LursoftPhp\Services\LursoftService;
use Lursoft\LursoftPhp\Exceptions\LursoftException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Lursoft\LursoftPhp\Services\LursoftService
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

        $this->service = new LursoftService('test_api_key', 'https://api.lursoft.lv');
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

        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('data', $result);
        $this->assertEquals('success', $result['status']);
    }

    public function testGetLegalEntityReport(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'data' => []
        ])));

        $result = $this->service->getLegalEntityReport('123456789');

        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('data', $result);
        $this->assertEquals('success', $result['status']);
    }

    public function testGetAnnualReportsList(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'data' => []
        ])));

        $result = $this->service->getAnnualReportsList('123456789');

        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('data', $result);
        $this->assertEquals('success', $result['status']);
    }

    public function testGetAnnualReport(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'data' => []
        ])));

        $result = $this->service->getAnnualReport('123456789', '2023');

        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('data', $result);
        $this->assertEquals('success', $result['status']);
    }

    public function testGetPersonProfile(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'data' => []
        ])));

        $result = $this->service->getPersonProfile('123456-12345');

        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('data', $result);
        $this->assertEquals('success', $result['status']);
    }

    public function testGetPublicPersonReport(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'data' => []
        ])));

        $result = $this->service->getPublicPersonReport('123456-12345');

        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('data', $result);
        $this->assertEquals('success', $result['status']);
    }

    public function testSearchSanctionsList(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'data' => []
        ])));

        $result = $this->service->searchSanctionsList(['q' => 'test']);

        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('data', $result);
        $this->assertEquals('success', $result['status']);
    }

    public function testGetSanctionsReport(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'data' => []
        ])));

        $result = $this->service->getSanctionsReport('123456789');

        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('data', $result);
        $this->assertEquals('success', $result['status']);
    }

    public function testVerifySanctionByRegNumber(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'data' => []
        ])));

        $result = $this->service->verifySanctionByRegNumber('123456789');

        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('data', $result);
        $this->assertEquals('success', $result['status']);
    }

    public function testSearchEstonianLegalEntity(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'data' => []
        ])));

        $result = $this->service->searchEstonianLegalEntity('123456789');

        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('data', $result);
        $this->assertEquals('success', $result['status']);
    }

    public function testGetEstonianLegalEntityReport(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'data' => []
        ])));

        $result = $this->service->getEstonianLegalEntityReport('123456789');

        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('data', $result);
        $this->assertEquals('success', $result['status']);
    }

    public function testSearchLithuanianLegalEntity(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'data' => []
        ])));

        $result = $this->service->searchLithuanianLegalEntity('123456789');

        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('data', $result);
        $this->assertEquals('success', $result['status']);
    }

    public function testGetLithuanianLegalEntityReport(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'data' => []
        ])));

        $result = $this->service->getLithuanianLegalEntityReport('123456789');

        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('data', $result);
        $this->assertEquals('success', $result['status']);
    }

    public function testGetDeceasedPersonData(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'data' => []
        ])));

        $result = $this->service->getDeceasedPersonData('123456-12345');

        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('data', $result);
        $this->assertEquals('success', $result['status']);
    }

    public function testSearchLatvianAddress(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'data' => []
        ])));

        $result = $this->service->searchLatvianAddress(['q' => 'test']);

        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('data', $result);
        $this->assertEquals('success', $result['status']);
    }

    public function testSearchLithuanianAddress(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'data' => []
        ])));

        $result = $this->service->searchLithuanianAddress(['q' => 'test']);

        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('data', $result);
        $this->assertEquals('success', $result['status']);
    }

    public function testSearchEstonianAddress(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'data' => []
        ])));

        $result = $this->service->searchEstonianAddress(['q' => 'test']);

        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('data', $result);
        $this->assertEquals('success', $result['status']);
    }

    public function testGetCsddVehicleStatement(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'data' => []
        ])));

        $result = $this->service->getCsddVehicleStatement('123456789');

        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('data', $result);
        $this->assertEquals('success', $result['status']);
    }

    public function testSearchInsolvencyProcess(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'data' => []
        ])));

        $result = $this->service->searchInsolvencyProcess('123456789');

        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('data', $result);
        $this->assertEquals('success', $result['status']);
    }

    public function testGetInsolvencyProcessData(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'data' => []
        ])));

        $result = $this->service->getInsolvencyProcessData('123456789');

        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('data', $result);
        $this->assertEquals('success', $result['status']);
    }

    public function testGetInsolvencyProcessReport(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'data' => []
        ])));

        $result = $this->service->getInsolvencyProcessReport('123456789');

        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('data', $result);
        $this->assertEquals('success', $result['status']);
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
