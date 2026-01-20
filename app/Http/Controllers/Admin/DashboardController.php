<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\User;
use App\Services\AdminStatisticsService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Get filter parameters
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        
        // Get basic counts
        $totalGuru = User::where('role', 'guru')->count();
        $totalSiswa = User::where('role', 'siswa')->count();
        $totalKelas = Kelas::count();
        
        // Get comprehensive dashboard statistics
        $dashboardStats = AdminStatisticsService::getDashboardStatistics($startDate, $endDate);
        
        // Get pie chart data
        $globalAttendancePieChart = AdminStatisticsService::getGlobalAttendancePieChartData($startDate, $endDate);
        $classComparisonChart = AdminStatisticsService::getClassComparisonData($startDate, $endDate);
        $teacherDistributionChart = AdminStatisticsService::getTeacherPresensiDistribution($startDate, $endDate);
        
        // Get date ranges for filters
        $currentWeek = [
            'start' => Carbon::now()->startOfWeek()->toDateString(),
            'end' => Carbon::now()->endOfWeek()->toDateString(),
            'label' => Carbon::now()->startOfWeek()->format('d M') . ' - ' . Carbon::now()->endOfWeek()->format('d M Y')
        ];
        
        $currentMonth = [
            'start' => Carbon::now()->startOfMonth()->toDateString(),
            'end' => Carbon::now()->endOfMonth()->toDateString(),
            'label' => Carbon::now()->format('M Y')
        ];
        
        $previousWeek = [
            'start' => Carbon::now()->subWeek()->startOfWeek()->toDateString(),
            'end' => Carbon::now()->subWeek()->endOfWeek()->toDateString(),
            'label' => Carbon::now()->subWeek()->startOfWeek()->format('d M') . ' - ' . Carbon::now()->subWeek()->endOfWeek()->format('d M Y')
        ];
        
        $previousMonth = [
            'start' => Carbon::now()->subMonth()->startOfMonth()->toDateString(),
            'end' => Carbon::now()->subMonth()->endOfMonth()->toDateString(),
            'label' => Carbon::now()->subMonth()->format('M Y')
        ];
        
        return view('admin.dashboard', compact(
            'totalGuru',
            'totalSiswa', 
            'totalKelas',
            'dashboardStats',
            'globalAttendancePieChart',
            'classComparisonChart',
            'teacherDistributionChart',
            'startDate',
            'endDate',
            'currentWeek',
            'currentMonth',
            'previousWeek',
            'previousMonth'
        ));
    }

    /**
     * Export dashboard data to CSV
     */
    public function export(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        
        $csvData = AdminStatisticsService::exportToCsv($startDate, $endDate);
        
        $filename = 'admin_dashboard_report_' . Carbon::now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($csvData) {
            $file = fopen('php://output', 'w');
            
            foreach ($csvData as $row) {
                fputcsv($file, $row);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}