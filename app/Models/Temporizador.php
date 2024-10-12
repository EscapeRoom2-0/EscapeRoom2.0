<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Temporizador extends Model
{
    use HasFactory;

    protected $table = 'temporizador';

    protected $fillable = ['tempT', 'tempJ1', 'tempJ2'];
}
