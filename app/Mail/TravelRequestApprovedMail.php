<?php

namespace App\Mail;

use App\Domains\Travel\Models\TravelRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TravelRequestApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public TravelRequest $travelRequest)
    {
    }

    public function build()
    {
        return $this->subject('Sua solicitação de viagem foi aprovada')
                    ->markdown('emails.travel.approved');
    }
}
