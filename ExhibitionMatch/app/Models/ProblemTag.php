<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProblemTag extends Model
{
    protected $table = 'problemtags';
    protected $primaryKey = 'Tag_ID';

    protected $fillable = [
        'tag_name',
        'tag_color',
        'tag_detail',
    ];

    /**
     * Normalize tag_color to a safe CSS hex color (#RRGGBB) or null if invalid.
     */
    public function cssColor(): ?string
    {
        $raw = trim((string) ($this->tag_color ?? ''));
        if ($raw === '') {
            return null;
        }

        // Accept "#RGB" or "#RRGGBB"
        if (preg_match('/^#([0-9a-fA-F]{3})$/', $raw, $m)) {
            $r = str_repeat($m[1][0], 2);
            $g = str_repeat($m[1][1], 2);
            $b = str_repeat($m[1][2], 2);
            return '#'.$r.$g.$b;
        }

        if (preg_match('/^#([0-9a-fA-F]{6})$/', $raw, $m)) {
            return '#'.strtolower($m[1]);
        }

        // Accept "RRGGBB"
        if (preg_match('/^([0-9a-fA-F]{6})$/', $raw, $m)) {
            return '#'.strtolower($m[1]);
        }

        return null;
    }

    /**
     * Choose a readable text color (dark/light) based on cssColor luminance.
     */
    public function cssTextColor(): ?string
    {
        $hex = $this->cssColor();
        if (!$hex) {
            return null;
        }

        [$r, $g, $b] = [
            hexdec(substr($hex, 1, 2)),
            hexdec(substr($hex, 3, 2)),
            hexdec(substr($hex, 5, 2)),
        ];

        // YIQ luma approximation
        $yiq = (($r * 299) + ($g * 587) + ($b * 114)) / 1000;

        return $yiq >= 160 ? '#111827' : '#ffffff';
    }
}


