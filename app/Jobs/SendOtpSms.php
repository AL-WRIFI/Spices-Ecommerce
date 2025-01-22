<?php

namespace App\Jobs;

use App\Support\Services\Otp\Provider\Vonage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendOtpSms implements ShouldQueue
{
    use Dispatchable,InteractsWithQueue, Queueable, SerializesModels;

    protected $model;
    protected $message;
    public function __construct(Model $model, string $message)
    {
        $this->model = $model;
        $this->message = $message;
    }

    public function handle(Vonage $vonage): void
    {
        return $vonage->sendOtp($this->model, $this->message);
    }
}
