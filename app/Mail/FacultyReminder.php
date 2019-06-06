<?php

namespace App\Mail;

use App\User;
use App\Exam;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class FacultyReminder extends Mailable
{
    use Queueable, SerializesModels;

    public $user, $exam, $proctors;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, Exam $exam, $proctors)
    {
        $this->user = $user;
        $this->exam = $exam;
        $this->proctors = $proctors;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.facultyReminder')->subject('Exam Reminder - BSC Proctor Management System');
    }
}
