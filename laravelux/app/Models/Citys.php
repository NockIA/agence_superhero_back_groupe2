<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Citys extends Model
{
    use HasFactory;
    protected $table = "city";
    protected $fillable = [
        'name',
        'creatorId',
        'planetLocationId',
    ];
    public function superHeroes()
    {
        return $this->belongsToMany(SuperHero::class, 'superheroCityRelation', 'cityId', 'heroUuid');
    }
}
