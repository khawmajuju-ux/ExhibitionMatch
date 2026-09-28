<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exhibitor extends Model
{
    protected $table = 'exhibitors';
    protected $primaryKey = 'Ex_ID';

    protected $fillable = [
        'ex_companyName',
        'ex_companyEmail',
        'ex_companyPhone',
        'Tag_id',
        'ex_website',
        'ex_logo',
    ];

    public function problemTag()
    {
        return $this->belongsTo(ProblemTag::class, 'Tag_id', 'Tag_ID');
    }
}


