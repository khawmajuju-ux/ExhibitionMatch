<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebSetting extends Model
{
    protected $table = 'web_settings';
    protected $primaryKey = 'Web_ID';

    protected $fillable = [
        'web_title',
        'web_detail',
        'landing_image_path',
        'seminar_map_image_path',
    ];
}


