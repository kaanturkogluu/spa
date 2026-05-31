<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MassageRecord;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function daily()
    {
        $todayStart = Carbon::today()->startOfDay();
        $todayEnd = Carbon::today()->endOfDay();
        
        $data = $this->getReportData($todayStart, $todayEnd);
        $data['reportTitle'] = 'Günlük Rapor (' . Carbon::today()->format('d/m/Y') . ')';

        return view('reports.daily', $data);
    }

    public function endOfDayReports()
    {
        $reports = \App\Models\EndOfDayReport::with('creator')->orderBy('created_at', 'desc')->get();
        return view('admin.end_of_day_reports', compact('reports'));
    }

    public function showEndOfDayReport($id)
    {
        $report = \App\Models\EndOfDayReport::findOrFail($id);
        $data = $this->getReportData($report->start_date, $report->end_date);
        $data['reportTitle'] = $report->title;

        return view('reports.daily', $data);
    }

    private function getReportData($startDate, $endDate)
    {
        $records = MassageRecord::with(['staff', 'staff2', 'package', 'creator'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();
            
        $staffStats = [];
        $receptionStats = [];
        $totalMassages = $records->count();
        $totalIncome = $records->sum('final_price');

        foreach ($records as $record) {
            if ($record->package) {
                // If 2 staffs
                $hasStaff2 = $record->staff_id_2 ? true : false;
                $premiumAmount = $hasStaff2 ? ($record->package->staff_premium / 2) : $record->package->staff_premium;

                // Process Staff 1
                if ($record->staff) {
                    $staffId = $record->staff->id;
                    if (!isset($staffStats[$staffId])) {
                        $staffStats[$staffId] = [
                            'name' => $record->staff->first_name . ' ' . $record->staff->last_name,
                            'count' => 0,
                            'premium' => 0,
                            'details' => []
                        ];
                    }
                    $staffStats[$staffId]['count'] += 1;
                    $staffStats[$staffId]['premium'] += $premiumAmount;

                    $detailKey = $record->package->name . ' (' . ucfirst($record->payment_method) . ')';
                    if (!isset($staffStats[$staffId]['details'][$detailKey])) {
                        $staffStats[$staffId]['details'][$detailKey] = [
                            'count' => 0,
                            'premium' => 0
                        ];
                    }
                    $staffStats[$staffId]['details'][$detailKey]['count'] += 1;
                    $staffStats[$staffId]['details'][$detailKey]['premium'] += $premiumAmount;
                }

                // Process Staff 2
                if ($hasStaff2 && $record->staff2) {
                    $staffId2 = $record->staff2->id;
                    if (!isset($staffStats[$staffId2])) {
                        $staffStats[$staffId2] = [
                            'name' => $record->staff2->first_name . ' ' . $record->staff2->last_name,
                            'count' => 0,
                            'premium' => 0,
                            'details' => []
                        ];
                    }
                    $staffStats[$staffId2]['count'] += 1; // It counts as a session for them too
                    $staffStats[$staffId2]['premium'] += $premiumAmount;

                    $detailKey = $record->package->name . ' (' . ucfirst($record->payment_method) . ')';
                    if (!isset($staffStats[$staffId2]['details'][$detailKey])) {
                        $staffStats[$staffId2]['details'][$detailKey] = [
                            'count' => 0,
                            'premium' => 0
                        ];
                    }
                    $staffStats[$staffId2]['details'][$detailKey]['count'] += 1;
                    $staffStats[$staffId2]['details'][$detailKey]['premium'] += $premiumAmount;
                }

                // Process Receptionist
                if ($record->creator) {
                    $creatorId = $record->creator->id;
                    if (!isset($receptionStats[$creatorId])) {
                        $receptionStats[$creatorId] = [
                            'name' => $record->creator->name . ' ' . $record->creator->surname,
                            'count' => 0,
                            'premium' => 0
                        ];
                    }
                    $receptionStats[$creatorId]['count'] += 1;
                    $receptionStats[$creatorId]['premium'] += $record->package->reception_premium;
                }
            }
        }

        $todayExpenses = \App\Models\Expense::whereBetween('created_at', [$startDate, $endDate])
            ->where('type', 'expense')
            ->sum('amount');
            
        $todayIncomes = \App\Models\Expense::whereBetween('created_at', [$startDate, $endDate])
            ->where('type', 'income')
            ->sum('amount');

        $expensesDetails = \App\Models\Expense::whereBetween('created_at', [$startDate, $endDate])
            ->where('type', 'expense')
            ->get();

        $grossIncome = $totalIncome; // Total from massages
        $totalGrossIncome = $grossIncome + $todayIncomes;
        
        $totalPremiums = collect($staffStats)->sum('premium') + collect($receptionStats)->sum('premium');
        $netCiro = $totalGrossIncome - $todayExpenses - $totalPremiums;

        return compact(
            'records', 'staffStats', 'receptionStats', 'totalMassages', 'totalIncome',
            'grossIncome', 'todayIncomes', 'todayExpenses', 'expensesDetails', 'totalGrossIncome', 'totalPremiums', 'netCiro'
        );
    }
}
