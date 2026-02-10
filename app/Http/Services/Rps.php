<?php

namespace App\Http\Services;

class Rps
{
    public static function all($offset = null, $limit = null, $search = null, $order = null, $dir = null, $where = null, $pluck = null)
    {
        $post = [
            'offset' => $offset,
            'limit' => $limit,
            'search' => $search,
            'order' => $order,
            'dir' => $dir,
            'where' => $where != null ? json_encode($where) : null,
            'pluck' => $pluck != null ? json_encode($pluck) : null,
        ];

        $apiKey = config('simkeu.simkeu_api_key');
        $url = config('simkeu.simkeu_url') . "rps";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "apikey: $apiKey",

        ]);
        $response = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($response);
        return $response->data;
    }

    public static function find($id)
    {
        $post = [
            'id' => $id,
        ];

        $apiKey = config('simkeu.simkeu_api_key');
        $url = config('simkeu.simkeu_url') . "rps/id";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "apikey: $apiKey",

        ]);
        $response = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($response);
        return $response->data;
    }

    public static function table($request)
    {
        $apiKey = config('simkeu.simkeu_api_key');
        $baseUrl = config('simkeu.simkeu_url') . "rps";

        // Build URL with query string (like browser GET request)
        $queryString = http_build_query($request->all());
        $url = $queryString ? $baseUrl . '?' . $queryString : $baseUrl;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPGET, true);

        $headers = [];
        if ($apiKey) {
            $headers[] = "apikey: $apiKey";
        }
        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }
}
