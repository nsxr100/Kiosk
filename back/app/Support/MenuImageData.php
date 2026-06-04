<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

class MenuImageData
{
    public static function fromPublicPath(mixed $path): ?string
    {
        $path = self::path($path);

        if (! $path || ! Storage::disk('public')->exists($path)) {
            return null;
        }

        $bytes = Storage::disk('public')->get($path);
        $info = getimagesizefromstring($bytes);

        if (! $info) {
            return null;
        }

        $mime = $info['mime'] ?? Storage::disk('public')->mimeType($path) ?: 'image/jpeg';

        return "data:{$mime};base64," . base64_encode($bytes);
    }

    public static function bytesFromDataUrl(?string $dataUrl): ?array
    {
        if (! $dataUrl || ! str_contains($dataUrl, ',')) {
            return null;
        }

        [$meta, $data] = explode(',', $dataUrl, 2);
        preg_match('/data:(.*);base64/', $meta, $match);

        $bytes = base64_decode($data, true);

        if (! $bytes || ! getimagesizefromstring($bytes)) {
            return null;
        }

        return [
            'bytes' => $bytes,
            'mime' => $match[1] ?? 'image/jpeg',
        ];
    }

    private static function path(mixed $path): ?string
    {
        if (is_string($path)) {
            return $path;
        }

        if (is_array($path)) {
            foreach ($path as $item) {
                if (is_string($item)) {
                    return $item;
                }
            }
        }

        return null;
    }
}
