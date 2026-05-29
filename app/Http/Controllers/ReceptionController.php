<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MassageRecord;
use App\Models\Staff;
use App\Models\MassagePackage;
use App\Models\Expense;
use Illuminate\Support\Facades\Auth;

class ReceptionController extends Controller
{
    public function dashboard()
    {
        $todayRecords = MassageRecord::whereDate('created_at', today())->count();
        $todayExpenses = Expense::whereDate('created_at', today())->sum('amount');
        return view('reception.dashboard', compact('todayRecords', 'todayExpenses'));
    }

    // --- Records ---
    public function recordsIndex()
    {
        $records = MassageRecord::with(['staff', 'package'])->orderBy('created_at', 'desc')->get();
        $staffs = Staff::where('is_active', true)->get();
        $packages = MassagePackage::all();
        return view('reception.records', compact('records', 'staffs', 'packages'));
    }

    public function recordsStore(Request $request)
    {
        $data = $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'massage_package_id' => 'required|exists:massage_packages,id',
            'duration_minutes' => 'required|integer|min:1',
            'payment_method' => 'required|in:nakit,havale,kredi_karti',
            'discount' => 'nullable|numeric|min:0',
        ]);

        $package = MassagePackage::findOrFail($data['massage_package_id']);
        
        $data['base_price'] = $package->price;
        $data['discount'] = $data['discount'] ?? 0;
        $data['final_price'] = max(0, $data['base_price'] - $data['discount']);
        $data['created_by'] = Auth::id();

        MassageRecord::create($data);
        return back()->with('success', 'Kayıt başarıyla eklendi.');
    }

    public function recordsUpdate(Request $request, MassageRecord $record)
    {
        $data = $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'massage_package_id' => 'required|exists:massage_packages,id',
            'duration_minutes' => 'required|integer|min:1',
            'payment_method' => 'required|in:nakit,havale,kredi_karti',
            'discount' => 'nullable|numeric|min:0',
        ]);

        $package = MassagePackage::findOrFail($data['massage_package_id']);
        
        $data['base_price'] = $package->price;
        $data['discount'] = $data['discount'] ?? 0;
        $data['final_price'] = max(0, $data['base_price'] - $data['discount']);
        $data['updated_by'] = Auth::id();

        $record->update($data);
        return back()->with('success', 'Kayıt güncellendi.');
    }

    public function recordsDestroy(MassageRecord $record)
    {
        $record->deleted_by = Auth::id();
        $record->save();
        $record->delete();
        return back()->with('success', 'Kayıt silindi.');
    }

    // --- Expenses ---
    public function expensesIndex()
    {
        $expenses = Expense::orderBy('created_at', 'desc')->get();
        return view('reception.expenses', compact('expenses'));
    }

    public function expensesStore(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
        ]);
        
        $data['created_by'] = Auth::id();
        Expense::create($data);

        return back()->with('success', 'Gider eklendi.');
    }
}
