<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Staff;
use App\Models\MassagePackage;
use App\Models\MassageRecord;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalUsers = User::where('role', 'reception')->count();
        $totalStaff = Staff::count();
        $totalPackages = MassagePackage::count();
        $totalRecords = MassageRecord::count();
        
        return view('admin.dashboard', compact('totalUsers', 'totalStaff', 'totalPackages', 'totalRecords'));
    }

    // --- Users (Receptionists) ---
    public function usersIndex()
    {
        $users = User::where('role', 'reception')->get();
        return view('admin.users', compact('users'));
    }

    public function usersStore(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username',
            'password' => 'required|string|min:6',
        ]);
        $data['role'] = 'reception';
        $data['password'] = Hash::make($data['password']);

        User::create($data);
        return back()->with('success', 'Resepsiyon kullanıcısı başarıyla eklendi.');
    }

    public function usersUpdate(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username,' . $user->id,
        ]);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        return back()->with('success', 'Kullanıcı güncellendi.');
    }

    public function usersDestroy(User $user)
    {
        $user->delete();
        return back()->with('success', 'Kullanıcı silindi.');
    }

    // --- Staff ---
    public function staffIndex()
    {
        $staffs = Staff::all();
        return view('admin.staff', compact('staffs'));
    }

    public function staffStore(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);
        
        $data['is_active'] = $request->has('is_active') ? $request->is_active : true;

        Staff::create($data);
        return redirect()->route('admin.staff.index')->with('success', 'Personel başarıyla eklendi.');
    }

    public function staffUpdate(Request $request, Staff $staff)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        $data['is_active'] = $request->has('is_active') ? $request->is_active : false;

        $staff->update($data);
        return redirect()->route('admin.staff.index')->with('success', 'Personel başarıyla güncellendi.');
    }

    public function staffDestroy(Staff $staff)
    {
        $staff->delete();
        return back()->with('success', 'Personel silindi.');
    }

    // --- Packages ---
    public function packagesIndex()
    {
        $packages = MassagePackage::all();
        return view('admin.packages', compact('packages'));
    }

    public function packagesStore(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'staff_premium' => 'required|numeric|min:0',
            'reception_premium' => 'required|numeric|min:0',
        ]);
        MassagePackage::create($data);
        return back()->with('success', 'Paket eklendi.');
    }

    public function packagesUpdate(Request $request, MassagePackage $package)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'staff_premium' => 'required|numeric|min:0',
            'reception_premium' => 'required|numeric|min:0',
        ]);
        $package->update($data);
        return back()->with('success', 'Paket güncellendi.');
    }

    public function packagesDestroy(MassagePackage $package)
    {
        $package->delete();
        return back()->with('success', 'Paket silindi.');
    }

    // --- Tracking ---
    public function tracking()
    {
        $records = MassageRecord::withTrashed()->with(['staff', 'package', 'creator', 'updater', 'deleter'])->orderBy('created_at', 'desc')->get();
        return view('admin.tracking', compact('records'));
    }

    // --- Cari (Finance) ---
    public function cari(Request $request)
    {
        $queryRecords = MassageRecord::query();
        $queryExpenses = \App\Models\Expense::query();

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $startDate = \Carbon\Carbon::parse($request->start_date)->startOfDay();
            $endDate = \Carbon\Carbon::parse($request->end_date)->endOfDay();
            $queryRecords->whereBetween('created_at', [$startDate, $endDate]);
            $queryExpenses->whereBetween('created_at', [$startDate, $endDate]);
        }

        $records = $queryRecords->with(['staff', 'package', 'creator'])->get();
        $expenses = $queryExpenses->get();

        // Income by payment method
        $incomeNakit = $records->where('payment_method', 'nakit')->sum('final_price');
        $incomeKrediKarti = $records->where('payment_method', 'kredi_karti')->sum('final_price');
        $incomeHavale = $records->where('payment_method', 'havale')->sum('final_price');
        
        // External Incomes
        $externalIncome = $expenses->where('type', 'income')->sum('amount');

        $totalIncome = $incomeNakit + $incomeKrediKarti + $incomeHavale + $externalIncome;
        $totalExpense = $expenses->where('type', 'expense')->sum('amount');
        // Staff Premiums
        $staffPremiums = [];
        $receptionPremiums = [];
        $totalPremiums = 0;

        foreach ($records as $record) {
            if ($record->package) {
                // Staff Premium Splitting
                $hasStaff2 = $record->staff_id_2 ? true : false;
                $premiumAmount = $hasStaff2 ? ($record->package->staff_premium / 2) : $record->package->staff_premium;

                if ($record->staff) {
                    $staffName1 = $record->staff->first_name . ' ' . $record->staff->last_name;
                    
                    if (!isset($staffPremiums[$staffName1])) {
                        $staffPremiums[$staffName1] = ['name' => $staffName1, 'count' => 0, 'premium' => 0, 'details' => []];
                    }
                    $staffPremiums[$staffName1]['count'] += 1;
                    $staffPremiums[$staffName1]['premium'] += $premiumAmount;
                    $totalPremiums += $premiumAmount;

                    $detailKey = $record->package->name . ' (' . ucfirst($record->payment_method) . ')';
                    if (!isset($staffPremiums[$staffName1]['details'][$detailKey])) {
                        $staffPremiums[$staffName1]['details'][$detailKey] = ['count' => 0, 'premium' => 0];
                    }
                    $staffPremiums[$staffName1]['details'][$detailKey]['count'] += 1;
                    $staffPremiums[$staffName1]['details'][$detailKey]['premium'] += $premiumAmount;
                }

                // Add to Staff 2 if exists
                if ($hasStaff2 && $record->staff2) {
                    $staffName2 = $record->staff2->first_name . ' ' . $record->staff2->last_name;
                    if (!isset($staffPremiums[$staffName2])) {
                        $staffPremiums[$staffName2] = ['name' => $staffName2, 'count' => 0, 'premium' => 0, 'details' => []];
                    }
                    $staffPremiums[$staffName2]['count'] += 1;
                    $staffPremiums[$staffName2]['premium'] += $premiumAmount;
                    $totalPremiums += $premiumAmount;

                    $detailKey = $record->package->name . ' (' . ucfirst($record->payment_method) . ')';
                    if (!isset($staffPremiums[$staffName2]['details'][$detailKey])) {
                        $staffPremiums[$staffName2]['details'][$detailKey] = ['count' => 0, 'premium' => 0];
                    }
                    $staffPremiums[$staffName2]['details'][$detailKey]['count'] += 1;
                    $staffPremiums[$staffName2]['details'][$detailKey]['premium'] += $premiumAmount;
                }

                // Reception Premium
                if ($record->creator) {
                    $receptionName = $record->creator->name . ' ' . $record->creator->surname;
                    if (!isset($receptionPremiums[$receptionName])) {
                        $receptionPremiums[$receptionName] = ['name' => $receptionName, 'count' => 0, 'premium' => 0];
                    }
                    $receptionPremiums[$receptionName]['count'] += 1;
                    $receptionPremiums[$receptionName]['premium'] += $record->package->reception_premium;
                    $totalPremiums += $record->package->reception_premium;
                }
            }
        }

        $netBalance = $totalIncome - $totalExpense - $totalPremiums;

        return view('admin.cari', compact(
            'incomeNakit', 'incomeKrediKarti', 'incomeHavale', 
            'totalIncome', 'totalExpense', 'netBalance', 
            'staffPremiums', 'receptionPremiums', 'records', 'totalPremiums'
        ));
    }

    public function expenseStore(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'type' => 'required|in:income,expense'
        ]);
        
        $data['created_by'] = \Illuminate\Support\Facades\Auth::id();
        \App\Models\Expense::create($data);

        $msg = $data['type'] == 'income' ? 'Gelir eklendi.' : 'Gider eklendi.';
        return back()->with('success', $msg);
    }
}
