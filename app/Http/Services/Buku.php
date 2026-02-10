<?php

namespace App\Http\Services;

class Buku
{
    /**
     * Get all buku data
     * Endpoint: GET /buku
     */
    public static function all($params = [])
    {
        $baseUrl = config('external_api.base_url');
        $apiKey = config('external_api.api_key');
        $url = $baseUrl . "buku";

        // Build query string from params
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Accept: application/json",
            "apikey: $apiKey",
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, config('external_api.timeout', 30));

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            return null;
        }

        $response = json_decode($response);
        return $response->data ?? $response;
    }

    /**
     * Get buku data for DataTables
     * Endpoint: GET /buku/data
     */
    public static function getData($params = [])
    {
        $baseUrl = config('external_api.base_url');
        $apiKey = config('external_api.api_key');
        $url = $baseUrl . "buku/data";

        // Build query string from params
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Accept: application/json",
            "apikey: $apiKey",
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, config('external_api.timeout', 30));

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            return null;
        }

        return json_decode($response);
    }

    /**
     * Find buku by ID
     * Endpoint: GET /buku/{id}
     */
    public static function find($id)
    {
        $baseUrl = config('external_api.base_url');
        $apiKey = config('external_api.api_key');
        $url = $baseUrl . "buku/{$id}";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Accept: application/json",
            "apikey: $apiKey",
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, config('external_api.timeout', 30));

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            return null;
        }

        $response = json_decode($response);
        return $response->data ?? $response;
    }
}
