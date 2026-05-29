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
        ]);
        Staff::create($data);
        return back()->with('success', 'Personel başarıyla eklendi.');
    }

    public function staffUpdate(Request $request, Staff $staff)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
        ]);
        $staff->update($data);
        return back()->with('success', 'Personel güncellendi.');
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
        
        $totalIncome = $incomeNakit + $incomeKrediKarti + $incomeHavale;
        $totalExpense = $expenses->sum('amount');
        $netBalance = $totalIncome - $totalExpense;

        // Staff Premiums
        $staffPremiums = [];
        $receptionPremiums = [];

        foreach ($records as $record) {
            if ($record->package) {
                // Staff Premium
                if ($record->staff) {
                    $staffName = $record->staff->first_name . ' ' . $record->staff->last_name;
                    if (!isset($staffPremiums[$staffName])) {
                        $staffPremiums[$staffName] = 0;
                    }
                    $staffPremiums[$staffName] += $record->package->staff_premium;
                }

                // Reception Premium
                if ($record->creator) {
                    $receptionName = $record->creator->name . ' ' . $record->creator->surname;
                    if (!isset($receptionPremiums[$receptionName])) {
                        $receptionPremiums[$receptionName] = 0;
                    }
                    $receptionPremiums[$receptionName] += $record->package->reception_premium;
                }
            }
        }

        return view('admin.cari', compact(
            'incomeNakit', 'incomeKrediKarti', 'incomeHavale', 
            'totalIncome', 'totalExpense', 'netBalance', 
            'staffPremiums', 'receptionPremiums', 'records'
        ));
    }

    public function expenseStore(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
        ]);
        
        $data['created_by'] = \Illuminate\Support\Facades\Auth::id();
        \App\Models\Expense::create($data);

        return back()->with('success', 'Gider eklendi.');
    }
}
