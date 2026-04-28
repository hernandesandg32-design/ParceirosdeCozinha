<?php

namespace App\Helpers;

class YoutubeHelper
{
    /**
     * Converte qualquer URL do YouTube para o formato embed.
     * Retorna null se não for uma URL válida do YouTube.
     *
     * Suporta:
     *   https://www.youtube.com/watch?v=ID
     *   https://youtu.be/ID
     *   https://www.youtube.com/embed/ID
     *   https://www.youtube.com/shorts/ID
     */
    public static function toEmbed(?string $url): ?string
    {
        if (!$url) return null;

        $id = self::extractId($url);

        return $id ? "https://www.youtube.com/embed/{$id}" : null;
    }
/**
     * Extrai o ID do vídeo de qualquer URL do YouTube.
     */
    public static function extractId(?string $url): ?string
    {
        if (!$url) return null;

        $patterns = [
            // youtube.com/watch?v=ID
            '/youtube\.com\/watch\?.*v=([a-zA-Z0-9_-]{11})/',
            // youtu.be/ID
            '/youtu\.be\/([a-zA-Z0-9_-]{11})/',
            // youtube.com/embed/ID (já no formato embed)
            '/youtube\.com\/embed\/([a-zA-Z0-9_-]{11})/',
            // youtube.com/shorts/ID
            '/youtube\.com\/shorts\/([a-zA-Z0-9_-]{11})/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return $matches[1];
            }
        }

        return null;
    }

    /**
     * Verifica se a URL é um YouTube válido.
     */
    public static function isValid(?string $url): bool
    {
        return self::extractId($url) !== null;
    }
}
