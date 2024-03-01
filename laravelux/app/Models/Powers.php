<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Powers extends Model
{
    use HasFactory;
    protected $table = "powers";
    protected $fillable = [
        'name',
        'linkImage',
        'description',
        'creatorId',
    ];
    public function superheroes()
    {
        return $this->belongsToMany(SuperHero::class, 'superheroPowerRelation', 'powerId', 'heroUuid');
    }
}
