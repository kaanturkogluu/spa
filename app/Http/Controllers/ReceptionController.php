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
        $todayExpenses = Expense::whereDate('created_at', today())
            ->whereDoesntHave('creator', function($q) {
                $q->where('role', 'admin');
            })->sum('amount');
        
        $activeRooms = [];
        for ($i = 1; $i <= 10; $i++) {
            $activeRooms[$i] = false;
        }

        $recordsToday = MassageRecord::whereDate('created_at', today())->get();
        $now = \Carbon\Carbon::now();
        foreach ($recordsToday as $rec) {
            if ($rec->room_number && $rec->start_time && $rec->end_time) {
                if ($now->between($rec->start_time, $rec->end_time)) {
                    $activeRooms[$rec->room_number] = true;
                }
            }
        }

        return view('reception.dashboard', compact('todayRecords', 'todayExpenses', 'activeRooms'));
    }

    // --- Records ---
    public function recordsIndex(Request $request)
    {
        $query = MassageRecord::with(['staff', 'staff2', 'package']);
        
        if ($request->has('room')) {
            // Eğer odaya tıklandıysa sadece o odanın bugünkü kayıtlarını getir.
            $query->where('room_number', $request->room)
                  ->whereDate('created_at', today());
        } else {
            // Sadece son 1 haftanın kayıtlarını getir.
            $oneWeekAgo = \Carbon\Carbon::now()->subWeek();
            $query->where('created_at', '>=', $oneWeekAgo);
        }
        
        $records = $query->orderBy('created_at', 'desc')->get();
            
        $staffs = Staff::where('is_active', true)->get();
        $packages = MassagePackage::all();
        
        return view('reception.records', compact('records', 'staffs', 'packages'));
    }

    public function recordsStore(Request $request)
    {
        $data = $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'staff_id_2' => 'nullable|exists:staff,id',
            'massage_package_id' => 'required|exists:massage_packages,id',
            'room_number' => 'required|integer|min:1|max:10',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'payment_method' => 'required|string',
            'discount' => 'nullable|numeric|min:0'
        ]);

        $startDate = today()->setTimeFromTimeString($request->start_time);
        $endDate = today()->setTimeFromTimeString($request->end_time);
        if ($endDate->lessThan($startDate)) {
            $endDate->addDay();
        }

        $package = MassagePackage::findOrFail($request->massage_package_id);
        $data['base_price'] = $package->price;
        $data['discount'] = $data['discount'] ?? 0;
        $data['final_price'] = max(0, $data['base_price'] - $data['discount']);
        $data['start_time'] = $startDate;
        $data['end_time'] = $endDate;
        $data['duration_minutes'] = $startDate->diffInMinutes($endDate);
        $data['created_by'] = Auth::id();

        MassageRecord::create($data);
        return back()->with('success', 'Masaj kaydı oluşturuldu.');
    }

    public function recordsUpdate(Request $request, MassageRecord $record)
    {
        if (!$record->created_at->isToday()) {
            return back()->withErrors('Sadece bugün eklenen kayıtları düzenleyebilirsiniz.');
        }

        $data = $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'staff_id_2' => 'nullable|exists:staff,id',
            'massage_package_id' => 'required|exists:massage_packages,id',
            'room_number' => 'required|integer|min:1|max:10',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'payment_method' => 'required|string',
            'discount' => 'nullable|numeric|min:0'
        ]);

        $startDate = today()->setTimeFromTimeString($request->start_time);
        $endDate = today()->setTimeFromTimeString($request->end_time);
        if ($endDate->lessThan($startDate)) {
            $endDate->addDay();
        }

        $package = MassagePackage::findOrFail($request->massage_package_id);
        $data['base_price'] = $package->price;
        $data['discount'] = $data['discount'] ?? 0;
        $data['final_price'] = max(0, $data['base_price'] - $data['discount']);
        $data['start_time'] = $startDate;
        $data['end_time'] = $endDate;
        $data['duration_minutes'] = $startDate->diffInMinutes($endDate);
        $data['updated_by'] = Auth::id();

        $record->update($data);
        return back()->with('success', 'Kayıt güncellendi.');
    }

    public function recordsDestroy(MassageRecord $record)
    {
        if (!$record->created_at->isToday()) {
            return back()->withErrors('Sadece bugün eklenen kayıtları silebilirsiniz.');
        }
        
        $record->deleted_by = Auth::id();
        $record->save();
        $record->delete();
        
        return back()->with('success', 'Kayıt silindi.');
    }

    // --- Expenses ---
    public function expensesIndex()
    {
        $expenses = Expense::whereDoesntHave('creator', function($q) {
            $q->where('role', 'admin');
        })->orderBy('created_at', 'desc')->get();
        
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
