<?php

namespace App\Jobs;

use App\Models\User;
use App\Services\Notification\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public User $user, public String $message)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(Notification $notification): mixed {
        return $notification->sendSms($this->user,$this->message);
    }
}
