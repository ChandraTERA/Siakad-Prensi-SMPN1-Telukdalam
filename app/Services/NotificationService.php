<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Create a new notification for a user.
     */
    public static function create($userId, $type, $title, $message, $data = null)
    {
        // Validate user_id before creating notification
        if (!$userId || $userId <= 0) {
            Log::warning('Attempted to create notification with invalid user_id', [
                'user_id' => $userId,
                'type' => $type,
                'title' => $title
            ]);
            return false;
        }

        return Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
        ]);
    }

    /**
     * Create notification for presensi verification.
     */
    public static function presensiVerification($userId, $siswaName, $presensiId)
    {
        return self::create(
            $userId,
            'presensi_verification',
            'Verifikasi Presensi Siswa',
            "Siswa {$siswaName} telah mengirim presensi mandiri yang perlu diverifikasi.",
            ['presensi_id' => $presensiId, 'siswa_name' => $siswaName]
        );
    }

    /**
     * Create notification for materi upload.
     */
    public static function materiUpload($userId, $materiTitle, $materiId)
    {
        return self::create(
            $userId,
            'materi_upload',
            'Materi Baru Diupload',
            "Materi '{$materiTitle}' telah berhasil diupload.",
            ['materi_id' => $materiId, 'materi_title' => $materiTitle]
        );
    }

    /**
     * Create notification for tugas upload.
     */
    public static function tugasUpload($userId, $tugasTitle, $tugasId)
    {
        return self::create(
            $userId,
            'tugas_upload',
            'Tugas Baru Diupload',
            "Tugas '{$tugasTitle}' telah berhasil diupload.",
            ['tugas_id' => $tugasId, 'tugas_title' => $tugasTitle]
        );
    }

    /**
     * Create notification for presensi session opened.
     */
    public static function presensiOpen($userId, $kelasName)
    {
        return self::create(
            $userId,
            'presensi_open',
            'Presensi Dibuka',
            "Presensi untuk kelas {$kelasName} telah dibuka.",
            ['kelas_name' => $kelasName]
        );
    }

    /**
     * Create notification for presensi session closed.
     */
    public static function presensiClose($userId, $kelasName)
    {
        return self::create(
            $userId,
            'presensi_close',
            'Presensi Ditutup',
            "Presensi untuk kelas {$kelasName} telah ditutup.",
            ['kelas_name' => $kelasName]
        );
    }

    /**
     * Create notification for multiple users (e.g., all teachers).
     */
    public static function createForMultipleUsers($userIds, $type, $title, $message, $data = null)
    {
        $notifications = [];
        
        foreach ($userIds as $userId) {
            $notifications[] = self::create($userId, $type, $title, $message, $data);
        }
        
        return $notifications;
    }

    /**
     * Create notification for all teachers.
     */
    public static function createForAllTeachers($type, $title, $message, $data = null)
    {
        $teacherIds = User::where('role', 'guru')->pluck('id')->toArray();
        
        return self::createForMultipleUsers($teacherIds, $type, $title, $message, $data);
    }

    /**
     * Create notification for all students in a class.
     */
    public static function createForClassStudents($kelasId, $type, $title, $message, $data = null)
    {
        $studentIds = User::whereHas('siswa', function($query) use ($kelasId) {
            $query->where('kelas_id', $kelasId);
        })->pluck('id')->toArray();
        
        return self::createForMultipleUsers($studentIds, $type, $title, $message, $data);
    }

    /**
     * Create notification for all admins.
     */
    public static function createForAllAdmins($type, $title, $message, $data = null)
    {
        $adminIds = User::where('role', 'admin')->pluck('id')->toArray();
        
        return self::createForMultipleUsers($adminIds, $type, $title, $message, $data);
    }

    /**
     * Create notification for all students.
     */
    public static function createForAllStudents($type, $title, $message, $data = null)
    {
        $studentIds = User::where('role', 'siswa')->pluck('id')->toArray();
        
        return self::createForMultipleUsers($studentIds, $type, $title, $message, $data);
    }

    /**
     * Create notification for specific user by role.
     */
    public static function createForUserByRole($userId, $type, $title, $message, $data = null)
    {
        $user = User::find($userId);
        if (!$user) {
            return false;
        }

        return self::create($userId, $type, $title, $message, $data);
    }

    /**
     * Create notification for admin activities.
     */
    public static function adminActivity($type, $title, $message, $data = null)
    {
        return self::createForAllAdmins($type, $title, $message, $data);
    }

    /**
     * Create notification for student activities.
     */
    public static function studentActivity($type, $title, $message, $data = null)
    {
        return self::createForAllStudents($type, $title, $message, $data);
    }
}
