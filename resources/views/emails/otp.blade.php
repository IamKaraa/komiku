<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP Verifikasi</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
        <h2 style="color: #333; text-align: center;">Verifikasi Akun KOMIKU</h2>
        <p>Halo,</p>
        <p>Terima kasih telah mendaftar di KOMIKU. Untuk menyelesaikan proses registrasi, silakan gunakan kode OTP berikut:</p>
        <div style="text-align: center; margin: 20px 0;">
            <span style="font-size: 24px; font-weight: bold; color: #007bff; background-color: #e9ecef; padding: 10px 20px; border-radius: 5px; display: inline-block;">
                {{ $otpCode }}
            </span>
        </div>
        <p>Kode OTP ini akan kedaluwarsa dalam 10 menit. Jika Anda tidak meminta kode ini, abaikan email ini.</p>
        <p>Salam,<br>Tim KOMIKU</p>
    </div>
</body>
</html>
