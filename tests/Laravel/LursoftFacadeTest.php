<?php

namespace Lursoft\LursoftPhp\Tests\Laravel;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Illuminate\Foundation\Application;
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
        $handlerStack = HandlerStack::create($this->mockHandler);
        $client = new Client(['handler' => $handlerStack]);

        /** @var Application $app */
        $app = $this->app;
        $app->singleton(Client::class, function () use ($client) {
            return $client;
        });

        config(['lursoft.api_key' => 'test_api_key']);
        config(['lursoft.base_url' => 'https://api.lursoft.lv']);
    }

    /**
     * @param Application $app
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [LursoftServiceProvider::class];
    }

    public function testFacadeUsage(): void
    {
        $this->mockHandler->append(new Response(200, [], json_encode([
            'status' => 'success',
            'data' => []
        ])));

        $result = Lursoft::searchLegalEntity(['q' => 'test']);

        $this->assertArrayHasKey('status', $result);
        $this->assertArrayHasKey('data', $result);
        $this->assertEquals('success', $result['status']);
    }

    public function testFacadeErrorHandling(): void
    {
        $this->mockHandler->append(new Response(400, [], json_encode([
            'status' => 'error',
            'message' => 'Invalid parameters'
        ])));

        $this->expectException(\Lursoft\LursoftPhp\Exceptions\LursoftException::class);

        Lursoft::searchLegalEntity(['invalid' => 'params']);
    }
}
