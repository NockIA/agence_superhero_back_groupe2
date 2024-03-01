<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class heroPowerRelation extends Model
{
    use HasFactory;
    protected $table = "superheroPowerRelation";
    public function superHeroes()
    {
        return $this->belongsTo(SuperHero::class, "UUID");
    }
    public function powers()
    {
        return $this->belongsTo(Powers::class, "id");
    }
}
