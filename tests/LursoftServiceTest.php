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
        $this->mockHandler = new MockHandler();
        $handlerStack = HandlerStack::create($this->mockHandler);
        $client = new Client(['handler' => $handlerStack]);
        $this->service = new LursoftService('test-api-key', 'https://api.lursoft.lv', $client);
    }

    /**
     * Helper method to create a mock response
     * @param array<string, mixed> $data
     * @param array<string, string> $headers
     */
    private function createMockResponse(array $data, int $status = 200, array $headers = []): Response
    {
        return new Response($status, $headers, (string)json_encode($data));
    }

    /**
     * @test
     * @return void
     */
    public function testSearchLegalEntity(): void
    {
        $responseData = [
            'success' => true,
            'data' => [
                'entities' => []
            ]
        ];

        $this->mockHandler->append($this->createMockResponse($responseData));

        $result = $this->service->searchLegalEntity('test query');

        $this->assertIsArray($result);
        $this->assertArrayHasKey('success', $result);
        $this->assertArrayHasKey('data', $result);
    }

    public function testGetLegalEntityReport(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'data' => [
                'name' => 'Test Company',
                'reg_number' => '123456789',
                'address' => 'Test Street 1',
                'registration_date' => '2020-01-01'
            ]
        ])));

        $result = $this->service->getLegalEntityReport('123456789');
        $this->assertTrue(is_array($result));
        $this->assertTrue(array_key_exists('status', $result));
        $this->assertTrue(array_key_exists('data', $result));
        $this->assertSame('success', $result['status']);
    }

    public function testGetAnnualReportsList(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'data' => [
                [
                    'year' => '2020',
                    'report_id' => '123'
                ]
            ]
        ])));

        $result = $this->service->getAnnualReportsList('123456789');
        $this->assertTrue(is_array($result));
        $this->assertTrue(array_key_exists('status', $result));
        $this->assertTrue(array_key_exists('data', $result));
        $this->assertSame('success', $result['status']);
    }

    public function testGetAnnualReport(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'data' => [
                'year' => '2020',
                'revenue' => 100000,
                'profit' => 10000
            ]
        ])));

        $result = $this->service->getAnnualReport('123456789', '2020');
        $this->assertTrue(is_array($result));
        $this->assertTrue(array_key_exists('status', $result));
        $this->assertTrue(array_key_exists('data', $result));
        $this->assertSame('success', $result['status']);
    }

    public function testGetPersonProfile(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'data' => [
                'name' => 'John Doe',
                'personal_code' => '123456-12345',
                'address' => 'Test Street 1'
            ]
        ])));

        $result = $this->service->getPersonProfile('123456-12345');
        $this->assertTrue(is_array($result));
        $this->assertTrue(array_key_exists('status', $result));
        $this->assertTrue(array_key_exists('data', $result));
        $this->assertSame('success', $result['status']);
    }

    public function testGetPublicPersonReport(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'data' => [
                'name' => 'John Doe',
                'personal_code' => '123456-12345',
                'address' => 'Test Street 1',
                'companies' => []
            ]
        ])));

        $result = $this->service->getPublicPersonReport('123456-12345');
        $this->assertTrue(is_array($result));
        $this->assertTrue(array_key_exists('status', $result));
        $this->assertTrue(array_key_exists('data', $result));
        $this->assertSame('success', $result['status']);
    }

    public function testSearchSanctionsList(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'data' => [
                [
                    'name' => 'John Doe',
                    'personal_code' => '123456-12345',
                    'sanction_type' => 'EU'
                ]
            ]
        ])));

        $result = $this->service->searchSanctionsList('John Doe');
        $this->assertTrue(is_array($result));
        $this->assertTrue(array_key_exists('status', $result));
        $this->assertTrue(array_key_exists('data', $result));
        $this->assertSame('success', $result['status']);
    }

    public function testGetSanctionsReport(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'data' => [
                'name' => 'John Doe',
                'personal_code' => '123456-12345',
                'sanction_type' => 'EU',
                'sanction_date' => '2020-01-01'
            ]
        ])));

        $result = $this->service->getSanctionsReport('123456-12345');
        $this->assertTrue(is_array($result));
        $this->assertTrue(array_key_exists('status', $result));
        $this->assertTrue(array_key_exists('data', $result));
        $this->assertSame('success', $result['status']);
    }

    public function testVerifySanctionByRegNumber(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'data' => [
                'is_sanctioned' => false
            ]
        ])));

        $result = $this->service->verifySanctionByRegNumber('123456789');
        $this->assertTrue(is_array($result));
        $this->assertTrue(array_key_exists('status', $result));
        $this->assertTrue(array_key_exists('data', $result));
        $this->assertSame('success', $result['status']);
    }

    public function testSearchEstonianLegalEntity(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'data' => [
                [
                    'name' => 'Test Company',
                    'reg_number' => '123456789',
                    'address' => 'Test Street 1'
                ]
            ]
        ])));

        $result = $this->service->searchEstonianLegalEntity('Test Company');
        $this->assertTrue(is_array($result));
        $this->assertTrue(array_key_exists('status', $result));
        $this->assertTrue(array_key_exists('data', $result));
        $this->assertSame('success', $result['status']);
    }

    public function testGetEstonianLegalEntityReport(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'data' => [
                'name' => 'Test Company',
                'reg_number' => '123456789',
                'address' => 'Test Street 1',
                'registration_date' => '2020-01-01'
            ]
        ])));

        $result = $this->service->getEstonianLegalEntityReport('123456789');
        $this->assertTrue(is_array($result));
        $this->assertTrue(array_key_exists('status', $result));
        $this->assertTrue(array_key_exists('data', $result));
        $this->assertSame('success', $result['status']);
    }

    public function testSearchLithuanianLegalEntity(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'data' => [
                [
                    'name' => 'Test Company',
                    'reg_number' => '123456789',
                    'address' => 'Test Street 1'
                ]
            ]
        ])));

        $result = $this->service->searchLithuanianLegalEntity('Test Company');
        $this->assertTrue(is_array($result));
        $this->assertTrue(array_key_exists('status', $result));
        $this->assertTrue(array_key_exists('data', $result));
        $this->assertSame('success', $result['status']);
    }

    public function testGetLithuanianLegalEntityReport(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'data' => [
                'name' => 'Test Company',
                'reg_number' => '123456789',
                'address' => 'Test Street 1',
                'registration_date' => '2020-01-01'
            ]
        ])));

        $result = $this->service->getLithuanianLegalEntityReport('123456789');
        $this->assertTrue(is_array($result));
        $this->assertTrue(array_key_exists('status', $result));
        $this->assertTrue(array_key_exists('data', $result));
        $this->assertSame('success', $result['status']);
    }

    public function testGetDeceasedPersonData(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'data' => [
                'name' => 'John Doe',
                'personal_code' => '123456-12345',
                'death_date' => '2020-01-01'
            ]
        ])));

        $result = $this->service->getDeceasedPersonData('123456-12345');
        $this->assertTrue(is_array($result));
        $this->assertTrue(array_key_exists('status', $result));
        $this->assertTrue(array_key_exists('data', $result));
        $this->assertSame('success', $result['status']);
    }

    public function testSearchLatvianAddress(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'data' => [
                [
                    'address' => 'Test Street 1',
                    'city' => 'Riga',
                    'postal_code' => 'LV-1234'
                ]
            ]
        ])));

        $result = $this->service->searchLatvianAddress('Test Street');
        $this->assertTrue(is_array($result));
        $this->assertTrue(array_key_exists('status', $result));
        $this->assertTrue(array_key_exists('data', $result));
        $this->assertSame('success', $result['status']);
    }

    public function testSearchLithuanianAddress(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'data' => [
                [
                    'address' => 'Test Street 1',
                    'city' => 'Vilnius',
                    'postal_code' => 'LT-12345'
                ]
            ]
        ])));

        $result = $this->service->searchLithuanianAddress('Test Street');
        $this->assertTrue(is_array($result));
        $this->assertTrue(array_key_exists('status', $result));
        $this->assertTrue(array_key_exists('data', $result));
        $this->assertSame('success', $result['status']);
    }

    public function testSearchEstonianAddress(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'data' => [
                [
                    'address' => 'Test Street 1',
                    'city' => 'Tallinn',
                    'postal_code' => '12345'
                ]
            ]
        ])));

        $result = $this->service->searchEstonianAddress('Test Street');
        $this->assertTrue(is_array($result));
        $this->assertTrue(array_key_exists('status', $result));
        $this->assertTrue(array_key_exists('data', $result));
        $this->assertSame('success', $result['status']);
    }

    public function testGetCsddVehicleStatement(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'data' => [
                'plate_number' => 'AB-1234',
                'make' => 'Toyota',
                'model' => 'Corolla',
                'year' => '2020'
            ]
        ])));

        $result = $this->service->getCsddVehicleStatement('AB-1234');
        $this->assertTrue(is_array($result));
        $this->assertTrue(array_key_exists('status', $result));
        $this->assertTrue(array_key_exists('data', $result));
        $this->assertSame('success', $result['status']);
    }

    public function testSearchInsolvencyProcess(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'data' => [
                [
                    'case_number' => '12345',
                    'debtor_name' => 'Test Company',
                    'status' => 'active'
                ]
            ]
        ])));

        $result = $this->service->searchInsolvencyProcess('Test Company');
        $this->assertTrue(is_array($result));
        $this->assertTrue(array_key_exists('status', $result));
        $this->assertTrue(array_key_exists('data', $result));
        $this->assertSame('success', $result['status']);
    }

    public function testGetInsolvencyProcessData(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'data' => [
                'case_number' => '12345',
                'debtor_name' => 'Test Company',
                'status' => 'active',
                'opening_date' => '2020-01-01'
            ]
        ])));

        $result = $this->service->getInsolvencyProcessData('12345');
        $this->assertTrue(is_array($result));
        $this->assertTrue(array_key_exists('status', $result));
        $this->assertTrue(array_key_exists('data', $result));
        $this->assertSame('success', $result['status']);
    }

    public function testGetInsolvencyProcessReport(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'data' => [
                'case_number' => '12345',
                'debtor_name' => 'Test Company',
                'status' => 'active',
                'opening_date' => '2020-01-01',
                'creditors' => []
            ]
        ])));

        $result = $this->service->getInsolvencyProcessReport('12345');
        $this->assertTrue(is_array($result));
        $this->assertTrue(array_key_exists('status', $result));
        $this->assertTrue(array_key_exists('data', $result));
        $this->assertSame('success', $result['status']);
    }

    public function testErrorHandling(): void
    {
        $this->mockHandler->append(new Response(400, [], json_encode([
            'status' => 'error',
            'message' => 'Invalid API key'
        ])));

        $this->expectException(LursoftException::class);
        $this->service->searchLegalEntity('Test Company');
    }
}
