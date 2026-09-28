<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    protected $table = 'visitors';
    protected $primaryKey = 'Visitor_ID';

    protected $fillable = [
        'visitor_name',
        'visitor_company',
        'visitor_position',
        'visitor_contact',
        'Tag_id',
    ];

    public function problemTag()
    {
        return $this->belongsTo(ProblemTag::class, 'Tag_id', 'Tag_ID');
    }
}


