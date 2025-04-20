<?php

namespace Sharik709\LursoftPhp\Response;

/**
 * A wrapper class for Lursoft API responses that provides a consistent interface
 * for handling API responses, including empty data and error states.
 */
class LursoftResponse
{
    /**
     * The HTTP status code from the API response
     *
     * @var int
     */
    private int $statusCode;

    /**
     * The parsed response data from the API
     *
     * @var array<string, mixed>
     */
    private array $data;

    /**
     * The success state of the API call
     *
     * @var bool
     */
    private bool $success;

    /**
     * Error message if the call was not successful
     *
     * @var string|null
     */
    private ?string $message;

    /**
     * @param array<string, mixed> $data The API response data
     * @param int $statusCode The HTTP status code
     */
    public function __construct(array $data, int $statusCode = 200)
    {
        $this->statusCode = $statusCode;
        $this->data = $data;

        // Determine success status based on the response
        if (isset($data['status']) && $data['status'] === 'success') {
            $this->success = true;
            $this->message = null;
        } else {
            $this->success = false;
            $this->message = $data['message'] ?? 'Unknown error';
        }
    }

    /**
     * Check if the request was successful
     *
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->success;
    }

    /**
     * Get the full response data
     *
     * @return array<string, mixed>
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Get specific data from the response
     * Returns an empty array if the key doesn't exist or data is not an array
     *
     * @return array<int|string, mixed>
     */
    public function getResults(): array
    {
        if (isset($this->data['data']) && is_array($this->data['data'])) {
            return $this->data['data'];
        }

        return [];
    }

    /**
     * Check if the response contains any results
     *
     * @return bool
     */
    public function hasResults(): bool
    {
        $results = $this->getResults();
        return !empty($results);
    }

    /**
     * Get the error message if the request failed
     *
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * Get the HTTP status code
     *
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Convert the response to an array
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'success' => $this->success,
            'message' => $this->message,
            'status_code' => $this->statusCode,
            'data' => $this->getResults(),
            'raw_response' => $this->data,
        ];
    }
}
