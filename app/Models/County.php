<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class County extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'chief_town', 'population', 'flag', 'coat_of_arms'];
    protected $table = 'counties';
}
