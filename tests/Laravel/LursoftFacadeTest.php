<?php

namespace Lursoft\LursoftPhp\Tests\Laravel;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Lursoft\LursoftPhp\Facades\Lursoft;
use Lursoft\LursoftPhp\Providers\LursoftServiceProvider;
use Orchestra\Testbench\TestCase;

class LursoftFacadeTest extends TestCase
{
    private MockHandler $mockHandler;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mockHandler = new MockHandler();
        $client = new Client(['handler' => HandlerStack::create($this->mockHandler)]);

        // Mock the HTTP client in the service container
        $this->app->singleton('lursoft.client', function () use ($client) {
            return $client;
        });
    }

    protected function getPackageProviders($app)
    {
        return [LursoftServiceProvider::class];
    }

    public function testFacadeUsage()
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'success' => true,
            'data' => [
                'name' => 'Test Company',
                'reg_number' => '123456789'
            ]
        ])));

        $result = Lursoft::getLegalEntityReport('123456789');
        $this->assertArrayHasKey('success', $result);
        $this->assertArrayHasKey('data', $result);
        $this->assertEquals('Test Company', $result['data']['name']);
    }

    public function testFacadeErrorHandling()
    {
        $this->mockHandler->append(new Response(400, [], json_encode([
            'success' => false,
            'error' => 'Invalid API key'
        ])));

        $this->expectException(\Lursoft\LursoftPhp\Exceptions\LursoftException::class);
        Lursoft::getLegalEntityReport('123456789');
    }
}
