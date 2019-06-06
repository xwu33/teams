<?php

namespace bioproc\Mail;

use App\User;
use bioproc\Exam;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CancelledSignup extends Mailable
{
    use Queueable, SerializesModels;

    public $user, $exam;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, Exam $exam)
    {
        $this->user = $user;
        $this->exam = $exam;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.cancelledSignup');
    }
}
