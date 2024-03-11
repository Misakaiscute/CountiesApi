<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'county_id', 'postal_code'];
    protected $table = 'cities';

    function county() : HasMany{
        return $this->hasMany(County::class);
    }
}
