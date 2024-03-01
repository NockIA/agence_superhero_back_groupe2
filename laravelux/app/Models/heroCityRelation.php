<?php

namespace App\Models;

use App\Http\Controllers\City;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class heroCityRelation extends Model
{
    use HasFactory;
    protected $table = "superheroCityRelation";
    public function superHeroes()
    {
        return $this->belongsTo(SuperHero::class, "UUID");
    }
    public function citys()
    {
        return $this->belongsTo(Citys::class, "id");
    }
}
