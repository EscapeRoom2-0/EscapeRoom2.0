<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Password extends Model
{
    use HasFactory;

    // Nome da tabela no banco de dados
    protected $table = 'password'; // Especifica a tabela correta

    protected $fillable = [
        'password',
        'game_id',
    ];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
}
