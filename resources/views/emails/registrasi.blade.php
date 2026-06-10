<!DOCTYPE html>
<html>
<head>
    <title>Verifikasi Akun - CV. Jaya Abadi</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px;">
        <h2 style="color: #dc3545;">CV. Jaya Abadi</h2>
        <p>Halo, <strong>{{ $nama_pelanggan }}</strong>!</p>
        <p>Terima kasih telah mendaftar di platform Sparepart Mobil CV. Jaya Abadi.</p>
        <p>Untuk memastikan keamanan dan memvalidasi alamat email Anda, silakan klik tombol di bawah ini untuk mengaktifkan akun Anda:</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ url('/verifikasi-email/' . $token) }}" style="padding: 12px 25px; background-color: #dc3545; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;">
                Verifikasi Akun Saya
            </a>
        </div>

        <p style="font-size: 14px; color: #666;">Jika tombol di atas tidak berfungsi, Anda juga bisa menyalin dan menempelkan tautan berikut ke browser Anda:</p>
        <p style="font-size: 14px; color: #0056b3; word-break: break-all;">
            {{ url('/verifikasi-email/' . $token) }}
        </p>
        
        <br>
        <p>Salam hangat,</p>
        <p><strong>Tim CV. Jaya Abadi</strong></p>
    </div>
</body>
</html>