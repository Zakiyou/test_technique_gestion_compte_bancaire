<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompteBancaire extends Model
{
    use HasFactory;

    protected $table = 'comptes_bancaires';

    protected $fillable = [
        'user_id',
        'numero_compte',
        'statut',
        'solde',
        'titulaire_compte',

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
