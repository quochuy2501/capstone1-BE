<?php

namespace App\Jobs;

use App\Mail\PaymentMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $mail_to;
    private $subject;
    private $data;
    private $view;

    public function __construct($mail_to, $subject, $data, $view)
    {
        $this->mail_to = $mail_to;
        $this->subject = $subject;
        $this->data = $data;
        $this->view = $view;
    }

    public function handle()
    {
        Mail::to($this->mail_to)->send(new PaymentMail($this->subject, $this->data, $this->view));
    }
}
