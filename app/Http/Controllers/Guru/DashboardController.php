<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Services\PresensiStatisticsService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request){
        $guruId = Auth::user()->id;
        
        // Get filter parameters
        $periode = $request->get('periode', 'daily');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        
        // Get classes taught by this teacher
        $kelasList = Kelas::whereHas('guruPengajar', function ($query) use ($guruId) {
            $query->where('user_id', $guruId);
        })->withCount('siswaData as siswa_count')->get();

        // Generate statistics if needed
        PresensiStatisticsService::generateStatisticsForGuru($guruId, $periode, $startDate, $endDate);

        // Get pie chart data
        $pieChartData = PresensiStatisticsService::getPieChartDataForGuru($guruId, $periode, $startDate, $endDate);
        
        // Get dashboard statistics
        $dashboardStats = PresensiStatisticsService::getDashboardStatisticsForGuru($guruId, $periode, $startDate, $endDate);
        
        // Get attendance trend data
        $trendData = PresensiStatisticsService::getAttendanceTrendForGuru($guruId, 7);
        
        // Get date ranges for filters
        $currentWeek = PresensiStatisticsService::getCurrentWeekRange();
        $currentMonth = PresensiStatisticsService::getCurrentMonthRange();
        $previousWeek = PresensiStatisticsService::getPreviousWeekRange();
        $previousMonth = PresensiStatisticsService::getPreviousMonthRange();

        return view('guru.dashboard', [
            'kelasList' => $kelasList,
            'pieChartData' => $pieChartData,
            'dashboardStats' => $dashboardStats,
            'trendData' => $trendData,
            'periode' => $periode,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'currentWeek' => $currentWeek,
            'currentMonth' => $currentMonth,
            'previousWeek' => $previousWeek,
            'previousMonth' => $previousMonth,
        ]);
    }
}
