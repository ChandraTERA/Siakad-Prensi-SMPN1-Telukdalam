# 📧 Fitur Forgot Password dengan Verifikasi Email

Fitur forgot password telah berhasil diimplementasikan dengan menggunakan konfigurasi Gmail yang Anda berikan. Berikut adalah panduan lengkap untuk menggunakan fitur ini.

## 🚀 **Fitur yang Sudah Diimplementasikan:**

### ✅ **1. View Forgot Password**

-   **File**: `resources/views/auth/forgot-password.blade.php`
-   **Design**: Modern dan konsisten dengan tema SIAKAD
-   **Fitur**: Form input email dengan validasi

### ✅ **2. View Reset Password**

-   **File**: `resources/views/auth/reset-password.blade.php`
-   **Design**: Modern dan konsisten dengan tema SIAKAD
-   **Fitur**: Form input password baru dengan konfirmasi

### ✅ **3. Email Template**

-   **File**: `resources/views/emails/password-reset.blade.php`
-   **Design**: Professional HTML email template
-   **Fitur**: Responsive design dengan branding SIAKAD

### ✅ **4. Custom Notification**

-   **File**: `app/Notifications/CustomPasswordResetNotification.php`
-   **Fitur**: Custom email notification dengan styling yang menarik

### ✅ **5. Mailable Class**

-   **File**: `app/Mail/PasswordResetMail.php`
-   **Fitur**: Custom email template dengan parameter yang dinamis

### ✅ **6. User Model Update**

-   **File**: `app/Models/User.php`
-   **Fitur**: Custom password reset notification method

## ⚙️ **Konfigurasi Email (.env)**

Untuk mengaktifkan fitur forgot password, Anda perlu mengkonfigurasi file `.env` dengan pengaturan berikut:

```env
# Email Configuration
MAIL_MAILER=smtp
MAIL_SCHEME=null
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=chandrawpsiloto@gmail.com
MAIL_PASSWORD=wdyg inuq krgq jari
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=672019153@student.uksw.edu
MAIL_FROM_NAME="${APP_NAME}"
```

## 🔧 **Langkah-langkah Setup:**

### **1. Update File .env**

Tambahkan konfigurasi email di file `.env` dengan pengaturan yang sudah Anda berikan.

### **2. Generate App Key (jika belum ada)**

```bash
php artisan key:generate
```

### **3. Clear Cache**

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

### **4. Test Email (Opsional)**

```bash
php artisan test:email your-email@example.com
```

## 🌐 **Cara Menggunakan:**

### **1. Akses Halaman Forgot Password**

-   Pergi ke halaman login: `http://localhost:8000/login`
-   Klik link "Forgot password?" di bagian bawah form

### **2. Input Email**

-   Masukkan email yang terdaftar di sistem
-   Klik tombol "Send Reset Link"

### **3. Cek Email**

-   Buka inbox email yang Anda masukkan
-   Cari email dari SIAKAD dengan subject "Reset Password - SIAKAD School Management System"
-   Klik tombol "Reset Password" di email

### **4. Reset Password**

-   Masukkan password baru
-   Konfirmasi password baru
-   Klik tombol "Reset Password"

## 🎨 **Fitur UI/UX:**

### **Design Modern:**

-   ✅ Gradient background yang menarik
-   ✅ Font Awesome icons untuk visual yang lebih baik
-   ✅ Responsive design untuk semua device
-   ✅ Consistent dengan tema SIAKAD

### **User Experience:**

-   ✅ Clear instructions dan messaging
-   ✅ Error handling yang user-friendly
-   ✅ Success messages yang informatif
-   ✅ Link kembali ke login yang mudah diakses

## 🔒 **Keamanan:**

### **Security Features:**

-   ✅ Token-based password reset
-   ✅ Time-limited reset links (60 menit)
-   ✅ One-time use reset links
-   ✅ CSRF protection
-   ✅ Email validation

## 📱 **Responsive Design:**

### **Mobile-First Approach:**

-   ✅ Optimized untuk mobile devices
-   ✅ Touch-friendly buttons
-   ✅ Readable typography
-   ✅ Proper spacing dan layout

## 🧪 **Testing:**

### **Test Scenarios:**

1. **Valid Email**: Email terdaftar di sistem
2. **Invalid Email**: Email tidak terdaftar
3. **Expired Token**: Link reset yang sudah expired
4. **Used Token**: Link reset yang sudah digunakan
5. **Password Validation**: Password baru yang tidak memenuhi kriteria

## 🚨 **Troubleshooting:**

### **Jika Email Tidak Terkirim:**

1. Pastikan konfigurasi SMTP di `.env` benar
2. Pastikan Gmail App Password sudah benar
3. Cek firewall dan network settings
4. Pastikan Gmail account tidak di-lock

### **Jika Link Reset Tidak Bekerja:**

1. Pastikan link tidak expired (60 menit)
2. Pastikan link belum digunakan sebelumnya
3. Pastikan token di URL masih valid

## 📋 **File yang Dibuat/Dimodifikasi:**

### **File Baru:**

-   `resources/views/emails/password-reset.blade.php`
-   `app/Mail/PasswordResetMail.php`
-   `app/Notifications/CustomPasswordResetNotification.php`
-   `app/Console/Commands/TestEmailCommand.php`

### **File yang Dimodifikasi:**

-   `resources/views/auth/forgot-password.blade.php`
-   `resources/views/auth/reset-password.blade.php`
-   `app/Models/User.php`

## 🎯 **Next Steps:**

1. **Update .env file** dengan konfigurasi email yang benar
2. **Test functionality** dengan email yang valid
3. **Customize email template** sesuai kebutuhan branding
4. **Monitor email delivery** untuk memastikan reliability

---

**Fitur forgot password sudah siap digunakan!** 🎉

Pastikan untuk mengkonfigurasi file `.env` dengan pengaturan email yang benar sebelum testing.
