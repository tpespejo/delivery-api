<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class HttpService
{
    /**
     * Send a GET request with authentication headers.
     *
     * @param string $url
     * @param array $query
     * @param array $headers
     * @return \Illuminate\Http\Client\Response
     */
    public function get(string $url, array $query = [], array $headers = [])
    {
        return Http::withHeaders($headers)->get($url, $query);
    }

    /**
     * Send a POST request with authentication headers.
     *
     * @param string $url
     * @param mixed $payload
     * @param array $headers
     * @return \Illuminate\Http\Client\Response
     */
    public function post(string $url, $payload = null, array $headers = [])
    {
        return Http::withHeaders($headers)->post($url, $payload);
    }

    /**
     * Send a PUT request with authentication headers.
     *
     * @param string $url
     * @param mixed $payload
     * @param array $headers
     * @return \Illuminate\Http\Client\Response
     */
    public function put(string $url, $payload = null, array $headers = [])
    {
        return Http::withHeaders($headers)->put($url, $payload);
    }

    /**
     * Send a DELETE request with authentication headers.
     *
     * @param string $url
     * @param mixed $payload
     * @param array $headers
     * @return \Illuminate\Http\Client\Response
     */
    public function delete(string $url, $payload = null, array $headers = [])
    {
        return Http::withHeaders($headers)->delete($url, $payload);
    }
}
