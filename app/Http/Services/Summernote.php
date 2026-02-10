<?php

namespace App\Http\Services;

class Summernote
{
    /**
     * Clean HTML content from Summernote editor
     * Removes potentially harmful scripts while keeping valid HTML
     *
     * @param string|null $html
     * @return string
     */
    public static function clean($html)
    {
        if (empty($html)) {
            return '';
        }

        // Remove any script tags and their content
        $html = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $html);

        // Remove any on* event handlers (onclick, onload, etc.)
        $html = preg_replace('/\s*on\w+\s*=\s*(["\'])[^"\']*\1/i', '', $html);
        $html = preg_replace('/\s*on\w+\s*=\s*[^\s>]*/i', '', $html);

        // Remove javascript: protocol in href/src
        $html = preg_replace('/\s*(href|src)\s*=\s*(["\'])javascript:[^"\']*\2/i', '', $html);

        // Remove data: protocol in src (can be used for XSS)
        $html = preg_replace('/\s*src\s*=\s*(["\'])data:[^"\']*\1/i', '', $html);

        // Remove style tags
        $html = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', '', $html);

        // Remove any iframe tags
        $html = preg_replace('/<iframe\b[^>]*>(.*?)<\/iframe>/is', '', $html);

        // Remove any object, embed, and applet tags
        $html = preg_replace('/<(object|embed|applet)\b[^>]*>(.*?)<\/\1>/is', '', $html);

        return $html;
    }
}
