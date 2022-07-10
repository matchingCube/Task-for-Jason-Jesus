<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use Illuminate\Database\Eloquent\Collection;
use stdClass;

class ScorecardsMail extends Mailable
{
    use Queueable, SerializesModels;

    public $person;
    public $scorecards;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(stdClass $person, Collection $scorecards)
    {
        //
        $this->person = $person;
        $this->scorecards = $scorecards;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Scorecards')
                    ->view('scorecards.email');
    }
}
