<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'brand_name',
        'site_title',
        'site_description',
        'brand_logo',
        'favicon',
        'seo_image',
    ];
}
