<?php

namespace Sharik709\LursoftPhp\Tests\Laravel;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Illuminate\Foundation\Application;
use Sharik709\LursoftPhp\Facades\Lursoft;
use Sharik709\LursoftPhp\Providers\LursoftServiceProvider;
use Sharik709\LursoftPhp\Response\LursoftResponse;
use Sharik709\LursoftPhp\Exceptions\LursoftException;
use Orchestra\Testbench\TestCase;

class LursoftFacadeTest extends TestCase
{
    private MockHandler $mockHandler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockHandler = new MockHandler();
        $handlerStack = HandlerStack::create($this->mockHandler);
        $client = new Client(['handler' => $handlerStack]);

        /** @var Application $app */
        $app = $this->app;
        $app->singleton(Client::class, function () use ($client) {
            return $client;
        });

        // Setup environment configuration
        config(['lursoft.base_url' => 'https://api.lursoft.lv']);
        config(['lursoft.client_id' => 'test_client_id']);
        config(['lursoft.client_secret' => 'test_client_secret']);
        config(['lursoft.username' => 'test_username']);
        config(['lursoft.password' => 'test_password']);
        config(['lursoft.scope' => 'organization:LURSOFT']);
    }

    /**
     * @param Application $app
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [LursoftServiceProvider::class];
    }

    /**
     * @return array<int, class-string>
     */
    protected function getPackageAliases($app): array
    {
        return [
            'Lursoft' => Lursoft::class,
        ];
    }

    public function testSearchLegalEntity(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'data' => [
                ['name' => 'Test Company', 'regcode' => '12345678901'],
                ['name' => 'Another Company', 'regcode' => '12345678902']
            ]
        ])));

        $result = Lursoft::searchLegalEntity(['q' => 'Test Company']);

        $this->assertInstanceOf(LursoftResponse::class, $result);
        $this->assertTrue($result->isSuccess());
        $this->assertTrue($result->hasResults());
        $this->assertCount(2, $result->getResults());
        $this->assertEquals('Test Company', $result->getResults()[0]['name']);
    }

    public function testGetLegalEntityReport(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'data' => [
                'name' => 'Test Company',
                'regcode' => '12345678901',
                'registered' => '2000-01-01',
                'Status' => ['code' => 1, 'value' => 'Active']
            ]
        ])));

        $result = Lursoft::getLegalEntityReport('12345678901');

        $this->assertInstanceOf(LursoftResponse::class, $result);
        $this->assertTrue($result->isSuccess());
        $this->assertTrue($result->hasResults());
        $this->assertEquals('Test Company', $result->getResults()['name']);
        $this->assertEquals('12345678901', $result->getResults()['regcode']);
    }

    public function testGetAnnualReportsList(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'data' => [
                ['year' => '2022', 'submitted' => '2023-04-01'],
                ['year' => '2021', 'submitted' => '2022-04-01']
            ]
        ])));

        $result = Lursoft::getAnnualReportsList('12345678901');

        $this->assertInstanceOf(LursoftResponse::class, $result);
        $this->assertTrue($result->isSuccess());
        $this->assertTrue($result->hasResults());
        $this->assertCount(2, $result->getResults());
        $this->assertEquals('2022', $result->getResults()[0]['year']);
    }

    public function testEmptyResponse(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'data' => []
        ])));

        $result = Lursoft::searchLegalEntity(['q' => 'Non-existent Company']);

        $this->assertInstanceOf(LursoftResponse::class, $result);
        $this->assertTrue($result->isSuccess());
        $this->assertFalse($result->hasResults());
        $this->assertEmpty($result->getResults());
    }

    public function testErrorResponse(): void
    {
        $this->mockHandler->append(new Response(400, [], json_encode([
            'status' => 'error',
            'message' => 'Invalid parameters'
        ])));

        $result = Lursoft::searchLegalEntity(['invalid' => 'params']);

        $this->assertInstanceOf(LursoftResponse::class, $result);
        $this->assertFalse($result->isSuccess());
        $this->assertEquals('Invalid parameters', $result->getMessage());
        $this->assertEquals(400, $result->getStatusCode());
    }

    public function testAuthenticationError(): void
    {
        $this->mockHandler->append(new Response(401, [], json_encode([
            'status' => 'error',
            'message' => 'Authentication failed'
        ])));

        $result = Lursoft::searchLegalEntity(['q' => 'Test Company']);

        $this->assertInstanceOf(LursoftResponse::class, $result);
        $this->assertFalse($result->isSuccess());
        $this->assertEquals('Authentication failed', $result->getMessage());
        $this->assertEquals(401, $result->getStatusCode());
    }

    public function testResponseConversion(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'data' => ['name' => 'Test Company', 'regcode' => '12345678901']
        ])));

        $result = Lursoft::searchLegalEntity(['q' => 'Test Company']);
        $array = $result->toArray();

        $this->assertTrue($array['success']);
        $this->assertNull($array['message']);
        $this->assertEquals(200, $array['status_code']);
        $this->assertEquals(['name' => 'Test Company', 'regcode' => '12345678901'], $array['data']);
        $this->assertArrayHasKey('raw_response', $array);
    }
}
