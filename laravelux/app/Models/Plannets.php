<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plannets extends Model
{
    use HasFactory;
    protected $table = "plannets";
    protected $fillable = [
        'name',
        'linkImage',
        'creatorId',
        'description',
    ];
}
