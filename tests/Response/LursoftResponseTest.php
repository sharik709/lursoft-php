<?php

namespace Sharik709\LursoftPhp\Tests\Response;

use PHPUnit\Framework\TestCase;
use Sharik709\LursoftPhp\Response\LursoftResponse;

class LursoftResponseTest extends TestCase
{
    public function testSuccessfulResponse(): void
    {
        $responseData = [
            'status' => 'success',
            'data' => [
                'item1' => 'value1',
                'item2' => 'value2'
            ]
        ];

        $response = new LursoftResponse($responseData, 200);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($responseData, $response->getData());
        $this->assertEquals($responseData['data'], $response->getResults());
        $this->assertTrue($response->hasResults());
        $this->assertNull($response->getMessage());

        $expected = [
            'success' => true,
            'message' => null,
            'status_code' => 200,
            'data' => $responseData['data'],
            'raw_response' => $responseData,
        ];
        $this->assertEquals($expected, $response->toArray());
    }

    public function testErrorResponse(): void
    {
        $responseData = [
            'status' => 'error',
            'message' => 'Something went wrong'
        ];

        $response = new LursoftResponse($responseData, 400);

        $this->assertFalse($response->isSuccess());
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals($responseData, $response->getData());
        $this->assertEquals([], $response->getResults());
        $this->assertFalse($response->hasResults());
        $this->assertEquals('Something went wrong', $response->getMessage());

        $expected = [
            'success' => false,
            'message' => 'Something went wrong',
            'status_code' => 400,
            'data' => [],
            'raw_response' => $responseData,
        ];
        $this->assertEquals($expected, $response->toArray());
    }

    public function testEmptyDataResponse(): void
    {
        $responseData = [
            'status' => 'success',
            'data' => []
        ];

        $response = new LursoftResponse($responseData, 200);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($responseData, $response->getData());
        $this->assertEquals([], $response->getResults());
        $this->assertFalse($response->hasResults());
        $this->assertNull($response->getMessage());

        $expected = [
            'success' => true,
            'message' => null,
            'status_code' => 200,
            'data' => [],
            'raw_response' => $responseData,
        ];
        $this->assertEquals($expected, $response->toArray());
    }

    public function testMissingDataKeyResponse(): void
    {
        $responseData = [
            'status' => 'success',
            'other_key' => 'value'
        ];

        $response = new LursoftResponse($responseData, 200);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals([], $response->getResults());
        $this->assertFalse($response->hasResults());

        $expected = [
            'success' => true,
            'message' => null,
            'status_code' => 200,
            'data' => [],
            'raw_response' => $responseData,
        ];
        $this->assertEquals($expected, $response->toArray());
    }

    public function testDataKeyIsNotArrayResponse(): void
    {
        $responseData = [
            'status' => 'success',
            'data' => 'string_value_not_array'
        ];

        $response = new LursoftResponse($responseData, 200);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals([], $response->getResults());
        $this->assertFalse($response->hasResults());

        $expected = [
            'success' => true,
            'message' => null,
            'status_code' => 200,
            'data' => [],
            'raw_response' => $responseData,
        ];
        $this->assertEquals($expected, $response->toArray());
    }

    public function testMissingStatusResponse(): void
    {
        $responseData = [
            'data' => ['item' => 'value']
        ];

        $response = new LursoftResponse($responseData, 200);

        $this->assertFalse($response->isSuccess());
        $this->assertEquals(['item' => 'value'], $response->getResults());
        $this->assertTrue($response->hasResults());
        $this->assertEquals('Unknown error', $response->getMessage());
    }
}
