<?php

namespace App\Jobs;

use App\Domains\Travel\Models\TravelRequest;
use App\Domains\Travel\ValueObjects\Status;
use App\Mail\TravelRequestApprovedMail;
use App\Mail\TravelRequestCanceledMail;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotifyTravelRequesterJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public TravelRequest $travelRequest;

    /**
     * Create a new job instance.
     */
    public function __construct( TravelRequest $travelRequest)
    {
        $this->travelRequest = $travelRequest;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            if($this->travelRequest->status == Status::Approved){
                Mail::to($this->travelRequest->requester->email)
                    ->send(new TravelRequestApprovedMail($this->travelRequest));
            }else{
                Mail::to($this->travelRequest->requester->email)
                    ->send(new TravelRequestCanceledMail($this->travelRequest));
            }

            Log::info('The e-mail was sent sucessufully.');

        } catch (\Throwable $th) {
            Log::error('An error occurred while sending the email. ' . $th->getMessage(),$th->getTrace());
        }
    }
}
