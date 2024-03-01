<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuperHero extends Model
{
    use HasFactory;
    protected $table = "superheros";
    protected $fillable = [
        'uuid',
        'firstname',
        'lastname',
        'heroname',
        'sexe',
        'hairColor',
        'description',
        'linkImage',
        'team',
        'originPlannet',
        'vehicle',
        'creatorId',
    ];
    public function powers()
    {
        return $this->belongsToMany(Powers::class, 'superheroPowerRelation', 'heroUuid', 'powerId');
    }
    public function cities()
    {
        return $this->belongsToMany(Citys::class, 'superheroCityRelation', 'heroUuid', 'cityId');
    }
    public function gadgets()
    {
        return $this->belongsToMany(Gadgets::class, 'superHeroGadgetRelation', 'heroUuid', 'gadgetId');
    }
}
