<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegistrasiSuksesMail extends Mailable
{
    use Queueable, SerializesModels;

    public $nama_pelanggan;

    // Menangkap nama pelanggan dari Controller
    public function __construct($nama_pelanggan)
    {
        $this->nama_pelanggan = $nama_pelanggan;
    }

    public function build()
    {
        return $this->subject('Selamat Datang di CV. Jaya Abadi!')
                    ->view('emails.registrasi');
    }
}