<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HtmlMinifyMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($response->headers->get('Content-Type') === 'text/html; charset=UTF-8') {
            $html = $response->getContent();

            // Perform minification
            $html = preg_replace('/\s+/', ' ', $html);  // Removes excessive white spaces
            $html = preg_replace('/<!--(.*?)-->/', '', $html);  // Removes comments
            // $html = preg_replace('/\s*(<[^>]+>)\s*/', '$1', $html);  // Removes spaces around tags

            $response->setContent($html);
        }

        return $response;
    }
}
