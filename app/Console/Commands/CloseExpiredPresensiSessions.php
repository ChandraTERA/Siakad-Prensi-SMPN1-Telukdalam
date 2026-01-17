<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PresensiSession;
use Carbon\Carbon;

class CloseExpiredPresensiSessions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'presensi:close-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Close presensi sessions that have exceeded their auto-close time';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();
        
        // Find sessions that should be auto-closed
        $expiredSessions = PresensiSession::where('status', PresensiSession::STATUS_BUKA)
            ->whereNotNull('auto_close_at')
            ->where('auto_close_at', '<=', $now)
            ->get();
        
        $closedCount = 0;
        
        foreach ($expiredSessions as $session) {
            $session->update([
                'status' => PresensiSession::STATUS_TUTUP,
                'waktu_selesai' => $now,
            ]);
            
            $closedCount++;
            
            $this->info("Closed presensi session for class {$session->kelas->nama_kelas} (ID: {$session->id})");
        }
        
        if ($closedCount > 0) {
            $this->info("Successfully closed {$closedCount} expired presensi sessions.");
        } else {
            $this->info("No expired presensi sessions found.");
        }
        
        return Command::SUCCESS;
    }
}
