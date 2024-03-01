<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gadgets extends Model
{
    use HasFactory;
    protected $table = "gadgets";
    protected $fillable = [
        'name',
        'linkImage',
        'creatorId',
        'description',
    ];
    public function superheroes()
    {
        return $this->belongsToMany(SuperHero::class, 'superHeroGadgetRelation', 'gadgetId', 'heroUuid');
    }
}
