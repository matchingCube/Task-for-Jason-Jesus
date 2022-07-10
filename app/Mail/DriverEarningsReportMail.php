<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DriverEarningsReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $payroll;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($payroll)
    {
        $this->payroll = $payroll;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Driver Payroll Report')
                    ->view('payroll.wder_email');
    }
}
