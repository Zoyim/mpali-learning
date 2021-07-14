<?php

namespace App\Mail;

use App\Formateur;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FormateurMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $formateurs;
    public function __construct(Formateur $formateurs)
    {
        $this->formateurs = $formateurs;
    }
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.formateur')
            ->with([
                "name" => $this->formateurs->name,
                "prenom" => $this->formateurs->prenom,
                "Mot de passe" => $this->formateurs->mobile,
            ]);
    }
}
