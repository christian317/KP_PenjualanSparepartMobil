<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        DB::table('role')->insertOrIgnore([
            'id' => 1,
            'nama' => 'Admin Testing'
        ]);

        // 2. BUAT DATA ADMIN
        DB::table('user_admin')->insert([
            'id'       => 9999,
            'role_id'  => 1,
            'email'    => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
        ]);

        // 3. BUAT DATA PELANGGAN
        DB::table('user_pelanggan')->insert([
            'id'           => 9999, 
            'nama'         => 'Pelanggan Budi',
            'email'        => 'pelanggan@gmail.com',
            'password'     => Hash::make('password123'),
            'telepon'      => '0812345678', 
            'alamat'       => 'Bandung',
            'status'       => 1,
            'status_mitra' => 0,
        ]);
    }

    public function test_login_email_dan_password_kosong()
    {
        $response = $this->post('/login', [
            'email' => '',
            'password' => '',
        ]);

        $response->assertSessionHasErrors(['email', 'password']);
    }

    public function test_login_email_kosong_password_isi()
    {
        $response = $this->post('/login', [
            'email' => '',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_login_email_isi_password_kosong()
    {
        $response = $this->post('/login', [
            'email' => 'pelanggan@gmail.com',
            'password' => '',
        ]);

        // Pastikan hanya kolom password yang terkena error validasi
        $response->assertSessionHasErrors(['password']);
    }

    public function test_login_email_benar_password_salah()
    {
        $response = $this->post('/login', [
            'email' => 'pelanggan@gmail.com',
            'password' => 'salah123',
        ]);

        $response->assertSessionHas('error', 'Email atau password salah!');
    }

    public function test_login_email_salah_password_benar()
    {
        $response = $this->post('/login', [
            'email' => 'salah_alamat@gmail.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHas('error', 'Email atau password salah!');
    }

    public function test_login_email_salah_password_salah()
    {
        $response = $this->post('/login', [
            'email' => 'ngawur@gmail.com',
            'password' => 'ngawur123',
        ]);

        $response->assertSessionHas('error', 'Email atau password salah!');
    }


    public function test_login_email_benar_password_benar_sebagai_pelanggan()
    {
        $response = $this->post('/login', [
            'email' => 'pelanggan@gmail.com',
            'password' => 'password123',
        ]);

        // Pastikan Session berhasil dibuat
        $response->assertSessionHas('role', 'pelanggan');
        $response->assertSessionHas('nama', 'Pelanggan Budi');
        
        // Pastikan sistem me-redirect ke halaman pelanggan.index
        $response->assertRedirect(route('pelanggan.index'));
        $response->assertSessionHas('toast_success', 'Login berhasil!');
    }

    public function test_login_email_benar_password_benar_sebagai_admin()
    {
        $response = $this->post('/login', [
            'email' => 'admin@gmail.com',
            'password' => 'admin123',
        ]);

        // Pastikan Session admin berhasil dibuat
        $response->assertSessionHas('role', 'admin');
        
        // Pastikan sistem me-redirect ke halaman admin.index
        $response->assertRedirect(route('admin.index'));
        $response->assertSessionHas('toast_success', 'Login berhasil!');
    }
}