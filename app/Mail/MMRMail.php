<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MMRMail extends Mailable
{
    use Queueable, SerializesModels;

    public $date;
    public $files;
    public $tractors;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($date, $files, $tractors)
    {
        $this->date = $date;
        $this->files = $files;
        $this->tractors = $tractors;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->view('fleets.mmr-email')
                        ->subject('Monthly Maintenance Record');

        // $files is an array with paths of files
        foreach ($this->files as $filePath) {
            $email->attach(storage_path($filePath), [
                'mime' => 'application/pdf',
            ]);
        }
        return $email;
    }
}
