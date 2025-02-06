<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Operation;
use App\Models\CompteBancaire;

class OperationEffectuee extends Mailable
{
    use Queueable, SerializesModels;

    public $operation;
    public $compte;

    /**
     * Créer une nouvelle instance du Mailable.
     */
    public function __construct(Operation $operation, CompteBancaire $compte)
    {
        $this->operation = $operation;
        $this->compte = $compte;
    }

    /**
     * Construire l'email.
     */
    public function build()
    {
        return $this->subject('Confirmation de votre opération bancaire')
                    ->view('emails.operation_effectuee');
    }
}
