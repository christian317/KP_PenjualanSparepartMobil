<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegistrasiSuksesMail extends Mailable
{
    use Queueable, SerializesModels;

    public $nama_pelanggan;
    public $token;
    
    public function __construct($nama, $token)
    {
        $this->nama_pelanggan = $nama;
        $this->token = $token;
    }

    public function build()
    {
        return $this->subject('Selamat Datang di CV. Jaya Abadi!')
                    ->view('emails.registrasi');
    }
}