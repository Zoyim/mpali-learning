<?php

namespace App\Mail;

use App\Etudiant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EtudiantMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $etudiants;
    public function __construct(Etudiant $etudiants)
    {
        $this->etudiants = $etudiants;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.etudiant')
            ->with([
                "name" => $this->etudiants->name,
                "prenom" => $this->etudiants->prenom,
                "Mot de passe" => $this->etudiants->mobile,
            ]);
    }
}
