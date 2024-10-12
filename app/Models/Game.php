<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $table = 'game';
    
    // Relacionamento entre Game e Password (um para muitos)
    public function passwords()
    {
        return $this->hasMany(Password::class);
    }
}
