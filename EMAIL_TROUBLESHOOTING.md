# 🔧 Troubleshooting Email Tidak Masuk ke Gmail

## ✅ **Status Saat Ini:**

-   ✅ Konfigurasi email sudah benar di .env
-   ✅ Laravel berhasil mengirim email (tidak ada error)
-   ✅ Test email berhasil dikirim
-   ❓ Email tidak masuk ke Gmail

## 🔍 **Kemungkinan Penyebab:**

### **1. Gmail App Password Tidak Valid**

**Masalah**: App Password yang digunakan mungkin sudah expired atau tidak valid.

**Solusi**:

1. Buka [Google Account Settings](https://myaccount.google.com/)
2. Pergi ke **Security** → **2-Step Verification**
3. Scroll ke bawah ke **App passwords**
4. Buat App Password baru untuk "Mail"
5. Update file `.env` dengan App Password yang baru

### **2. Gmail Security Settings**

**Masalah**: Gmail mungkin memblokir email dari aplikasi yang tidak dikenal.

**Solusi**:

1. Buka Gmail → Settings → **Filters and Blocked Addresses**
2. Pastikan tidak ada filter yang memblokir email dari `672019153@student.uksw.edu`
3. Cek folder **Spam** - email mungkin masuk ke sana

### **3. Gmail Account Settings**

**Masalah**: Akun Gmail mungkin memiliki pengaturan keamanan yang ketat.

**Solusi**:

1. Pastikan **2-Step Verification** sudah aktif
2. Pastikan **Less secure app access** sudah diizinkan (jika diperlukan)
3. Cek **Sign-in & security** untuk melihat aktivitas login yang mencurigakan

### **4. Email Delivery Delay**

**Masalah**: Email mungkin membutuhkan waktu untuk sampai.

**Solusi**:

1. Tunggu 5-10 menit
2. Cek folder **All Mail** di Gmail
3. Refresh halaman Gmail

## 🧪 **Test yang Sudah Dilakukan:**

### **✅ Test 1: Konfigurasi Email**

```bash
php artisan config:show mail
```

**Hasil**: Konfigurasi sudah benar (smtp.gmail.com, port 587, TLS)

### **✅ Test 2: Test Email Command**

```bash
php artisan test:email chandrawpsiloto@gmail.com
```

**Hasil**: "Test email sent successfully"

### **✅ Test 3: Mailable Test**

```bash
php artisan tinker
Mail::to('chandrawpsiloto@gmail.com')->send(new PasswordResetMail('http://localhost:8000/reset-password/test-token', 60));
```

**Hasil**: "Email sent successfully via Mailable"

### **✅ Test 4: Custom Notification**

```bash
php artisan tinker
$user = App\Models\User::where('email', 'chandrawpsiloto@gmail.com')->first();
$user->sendPasswordResetNotification('test-token');
```

**Hasil**: "Password reset notification sent successfully"

## 🔧 **Langkah Troubleshooting:**

### **Step 1: Cek Folder Spam**

1. Buka Gmail
2. Klik **Spam** di sidebar
3. Cari email dari `672019153@student.uksw.edu`
4. Jika ada, klik **Not spam**

### **Step 2: Cek All Mail**

1. Buka Gmail
2. Klik **All Mail** di sidebar
3. Cari email dengan subject "Reset Password - SIAKAD"

### **Step 3: Buat App Password Baru**

1. Buka [Google Account](https://myaccount.google.com/)
2. Security → 2-Step Verification
3. App passwords → Generate new password
4. Update `.env` dengan password baru

### **Step 4: Test dengan Email Lain**

Coba kirim ke email lain untuk memastikan masalahnya di Gmail atau di aplikasi.

## 📧 **Konfigurasi .env yang Benar:**

```env
MAIL_MAILER=smtp
MAIL_SCHEME=null
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=chandrawpsiloto@gmail.com
MAIL_PASSWORD="wdyg inuq krgq jari"
MAIL_FROM_ADDRESS=672019153@student.uksw.edu
MAIL_FROM_NAME="${APP_NAME}"
MAIL_ENCRYPTION=tls
```

## 🚨 **Jika Masih Tidak Masuk:**

### **Alternative 1: Gunakan Email Lain**

Coba kirim ke email lain (Yahoo, Outlook, dll) untuk memastikan masalahnya di Gmail.

### **Alternative 2: Cek Log Laravel**

```bash
tail -f storage/logs/laravel.log
```

Kemudian coba kirim email lagi dan lihat apakah ada error.

### **Alternative 3: Test dengan Mailtrap**

Gunakan Mailtrap untuk testing email tanpa mengirim email sungguhan.

## 📱 **Cara Test Forgot Password:**

### **1. Via Web Interface**

1. Buka `http://localhost:8000/login`
2. Klik "Forgot password?"
3. Input email `chandrawpsiloto@gmail.com`
4. Klik "Send Reset Link"
5. Cek email

### **2. Via Command Line**

```bash
php artisan tinker
$user = App\Models\User::where('email', 'chandrawpsiloto@gmail.com')->first();
$user->sendPasswordResetNotification('test-token');
```

## 🎯 **Next Steps:**

1. **Cek folder Spam** di Gmail
2. **Buat App Password baru** jika diperlukan
3. **Test dengan email lain** untuk memastikan
4. **Cek All Mail** di Gmail
5. **Tunggu beberapa menit** untuk delivery delay

---

**Email sudah berhasil dikirim dari Laravel!** Masalahnya kemungkinan di pengaturan Gmail atau email masuk ke folder Spam. 🎯
