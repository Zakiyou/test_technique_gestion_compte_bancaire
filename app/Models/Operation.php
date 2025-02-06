<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    use HasFactory;

    protected $fillable = ['comptes_bancaire_id', 'montant', 'type', 'solde'];

    // Relation avec le compte bancaire
    public function compteBancaire()
    {
        return $this->belongsTo(CompteBancaire::class);
    }
}
