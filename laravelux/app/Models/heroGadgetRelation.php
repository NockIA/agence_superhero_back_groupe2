<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class heroGadgetRelation extends Model
{
    use HasFactory;
    protected $table = "superHeroGadgetRelation";
    public function superHeroes()
    {
        return $this->belongsTo(SuperHero::class, "UUID");
    }
    public function gadgets()
    {
        return $this->belongsTo(Gadgets::class, "id");
    }
}
