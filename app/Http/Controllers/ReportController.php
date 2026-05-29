<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MassageRecord;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function daily()
    {
        $today = Carbon::today();
        
        $records = MassageRecord::with(['staff', 'staff2', 'package', 'creator'])
            ->whereDate('created_at', $today)
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
                            'premium' => 0
                        ];
                    }
                    $staffStats[$staffId]['count'] += 1;
                    $staffStats[$staffId]['premium'] += $premiumAmount;
                }

                // Process Staff 2
                if ($hasStaff2 && $record->staff2) {
                    $staffId2 = $record->staff2->id;
                    if (!isset($staffStats[$staffId2])) {
                        $staffStats[$staffId2] = [
                            'name' => $record->staff2->first_name . ' ' . $record->staff2->last_name,
                            'count' => 0,
                            'premium' => 0
                        ];
                    }
                    $staffStats[$staffId2]['count'] += 1; // It counts as a session for them too
                    $staffStats[$staffId2]['premium'] += $premiumAmount;
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

        $todayExpenses = \App\Models\Expense::whereDate('created_at', $today)
            ->where('type', 'expense')
            ->sum('amount');
            
        $todayIncomes = \App\Models\Expense::whereDate('created_at', $today)
            ->where('type', 'income')
            ->sum('amount');

        $expensesDetails = \App\Models\Expense::whereDate('created_at', $today)
            ->where('type', 'expense')
            ->get();

        $grossIncome = $totalIncome; // Total from massages
        $totalGrossIncome = $grossIncome + $todayIncomes;
        
        $totalPremiums = collect($staffStats)->sum('premium') + collect($receptionStats)->sum('premium');
        $netCiro = $totalGrossIncome - $todayExpenses - $totalPremiums;

        return view('reports.daily', compact(
            'records', 'staffStats', 'receptionStats', 'totalMassages', 'totalIncome',
            'grossIncome', 'todayIncomes', 'todayExpenses', 'expensesDetails', 'totalGrossIncome', 'totalPremiums', 'netCiro'
        ));
    }
}
