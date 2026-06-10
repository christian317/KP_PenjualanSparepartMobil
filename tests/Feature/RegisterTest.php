<?php

namespace Tests\Feature;

use App\Models\UserPelanggan;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use DatabaseTransactions;

    // =========================================================================
    // SKENARIO NEGATIF REGISTRASI (GAGAL)
    // =========================================================================

    public function test_register_semua_kolom_kosong()
    {
        // Kondisi: Pengguna langsung klik tombol Daftar tanpa mengisi apa-apa
        $response = $this->post('/register', [
            'nama' => '',
            'email' => '',
            'password' => '',
            'telepon' => '',
            'alamat' => ''
        ]);

        $response->assertSessionHasErrors(['nama', 'email', 'password', 'telepon', 'alamat']);
    }

    public function test_register_ada_kolom_wajib_yang_kosong()
    {
        // Kondisi: Pengguna hanya mengisi email dan password, tapi identitas lain dikosongkan
        $response = $this->post('/register', [
            'nama' => '',
            'email' => 'pelanggan@gmail.com',
            'password' => 'password123',
            'telepon' => '',
            'alamat' => ''
        ]);

        $response->assertSessionHasErrors(['nama', 'telepon', 'alamat']);
    }

    public function test_register_email_bukan_gmail()
    {
        // Kondisi: Pengguna mengisi email selain domain @gmail.com
        $response = $this->post('/register', [
            'nama' => 'Budi Tester',
            'email' => 'budi@yahoo.com', 
            'password' => 'password123',
            'telepon' => '081234567890',
            'alamat' => 'Bandung'
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_register_email_sudah_digunakan()
    {
        // Kondisi: Email sudah terdaftar sebelumnya di sistem
        UserPelanggan::create([
            'id' => 9999,
            'nama' => 'User Lama',
            'email' => 'sudah_ada@gmail.com',
            'password' => Hash::make('password123'),
            'telepon' => '081111111',
            'alamat' => 'Bandung',
            'status' => 1,
            'status_mitra' => 0
        ]);

        // Pengguna mencoba mendaftar dengan email yang sama persis
        $response = $this->post('/register', [
            'nama' => 'Budi Tester',
            'email' => 'sudah_ada@gmail.com', 
            'password' => 'password123',
            'telepon' => '081234567890',
            'alamat' => 'Bandung'
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_register_password_kurang_dari_8_karakter()
    {
        // Kondisi: Pengguna membuat password yang terlalu pendek
        $response = $this->post('/register', [
            'nama' => 'Budi Tester',
            'email' => 'tester@gmail.com',
            'password' => '1234567', // Hanya 7 karakter
            'telepon' => '081234567890',
            'alamat' => 'Bandung'
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    // =========================================================================
    // SKENARIO POSITIF REGISTRASI (BERHASIL)
    // =========================================================================

    public function test_register_semua_kondisi_benar()
    {
        // Menahan email agar tidak benar-benar terkirim saat proses testing
        Mail::fake(); 

        // Kondisi: Pengguna mengisi semua form dengan benar sesuai aturan
        $response = $this->post('/register', [
            'nama' => 'Pelanggan Baru',
            'nama_toko' => 'Toko Maju',
            'email' => 'pelanggan_baru@gmail.com',
            'password' => 'password123', 
            'telepon' => '0899999999',
            'alamat' => 'Bandung'
        ]);

        // Pastikan proses lolos validasi, diarahkan ke halaman login, dan ada notifikasi sukses
        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('login'));
        $response->assertSessionHas('success');
    }

    public function test_register_format_email_salah_ketik()
    {
        // Kondisi: User lupa mengetik simbol '@'
        $response = $this->post('/register', [
            'nama' => 'Budi Tester',
            'email' => 'budigmail.com', // Salah format
            'password' => 'password123',
            'telepon' => '081234567890',
            'alamat' => 'Bandung'
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_register_kolom_diisi_hanya_dengan_spasi()
    {
        // Kondisi: User iseng menekan spasi berkali-kali tanpa huruf
        $response = $this->post('/register', [
            'nama' => '     ', // Hanya berisi spasi
            'email' => 'pelanggan@gmail.com',
            'password' => 'password123',
            'telepon' => '081234567890',
            'alamat' => 'Bandung'
        ]);

        // Sistem harus tetap membaca nama sebagai "kosong" dan menolaknya
        $response->assertSessionHasErrors(['nama']);
    }

    public function test_register_berhasil_tanpa_mengisi_nama_toko()
    {
        Mail::fake(); 

        // Kondisi: User mengisi form lengkap, tapi kolom nama_toko sengaja dikosongkan
        $response = $this->post('/register', [
            'nama' => 'Pelanggan Biasa',
            'nama_toko' => '', // Dikosongkan karena tidak punya toko
            'email' => 'pelanggan_biasa@gmail.com',
            'password' => 'password123', 
            'telepon' => '0899999999',
            'alamat' => 'Bandung'
        ]);

        // Pastikan tidak ada error dan pendaftaran tetap sukses
        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('login'));
    }
}