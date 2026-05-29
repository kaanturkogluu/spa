@extends('layouts.app')

@section('content')
<div class="mb-8">
    <h2 class="text-3xl font-bold text-gray-800">Resepsiyon Dashboard</h2>
    <p class="text-gray-500 mt-1">Bugünkü masaj kayıtları ve günlük giderler.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <!-- Stat Card 1 -->
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center justify-between hover:shadow-md transition-shadow">
        <div>
            <p class="text-sm font-medium text-gray-500 mb-1">Bugünkü Kayıt Sayısı</p>
            <h3 class="text-3xl font-bold text-gray-800">{{ $todayRecords }}</h3>
        </div>
        <div class="w-12 h-12 bg-blue-100 text-blue-500 rounded-full flex items-center justify-center text-xl">
            <i class="fa-solid fa-clipboard-check"></i>
        </div>
    </div>

    <!-- Stat Card 2 -->
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center justify-between hover:shadow-md transition-shadow">
        <div>
            <p class="text-sm font-medium text-gray-500 mb-1">Bugünkü Giderler</p>
            <h3 class="text-3xl font-bold text-gray-800">{{ number_format($todayExpenses, 2) }} ₺</h3>
        </div>
        <div class="w-12 h-12 bg-red-100 text-red-500 rounded-full flex items-center justify-center text-xl">
            <i class="fa-solid fa-money-bill-wave"></i>
        </div>
    </div>
</div>

<h3 class="text-xl font-bold text-gray-800 mb-4">Odalar (Kafe Düzeni)</h3>
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-3 gap-6 mb-8">
    @for($i = 1; $i <= 10; $i++)
        @if($activeRooms[$i] ?? false)
            <a href="{{ route('reception.records.index', ['room' => $i]) }}" class="bg-red-500 hover:bg-red-600 rounded-2xl p-6 text-center text-white shadow-md transition-all transform hover:-translate-y-1 relative overflow-hidden block border-b-4 border-red-700 cursor-pointer">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-red-600 rounded-full opacity-20"></div>
                <div class="relative z-10 flex flex-col items-center">
                    <i class="fa-solid fa-bed text-3xl mb-2 opacity-90"></i>
                    <h4 class="text-xl font-bold mb-1">Oda {{ $i }}</h4>
                    <span class="inline-block px-3 py-1 mt-2 bg-red-800 bg-opacity-40 rounded-full text-xs font-semibold uppercase tracking-wider shadow-sm">Dolu (Kayıtları Gör)</span>
                </div>
            </a>
        @else
            <a href="{{ route('reception.records.index', ['room' => $i, 'action' => 'add']) }}" class="bg-green-500 hover:bg-green-600 rounded-2xl p-6 text-center text-white shadow-md transition-all transform hover:-translate-y-1 relative overflow-hidden group cursor-pointer block border-b-4 border-green-700">
                <div class="absolute -left-4 -bottom-4 w-24 h-24 bg-green-600 rounded-full opacity-0 group-hover:opacity-20 transition-opacity"></div>
                <div class="relative z-10 flex flex-col items-center">
                    <i class="fa-solid fa-door-open text-3xl mb-2 opacity-90"></i>
                    <h4 class="text-xl font-bold mb-1">Oda {{ $i }}</h4>
                    <span class="inline-block px-3 py-1 mt-2 bg-green-800 bg-opacity-40 rounded-full text-xs font-semibold uppercase tracking-wider shadow-sm">Müsait (Yeni Kayıt)</span>
                </div>
            </a>
        @endif
    @endfor
</div>

<div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex justify-center">
    <a href="{{ route('reception.expenses.index') }}" class="flex items-center justify-center px-6 py-3 bg-gray-50 rounded-xl hover:bg-blue-50 hover:text-blue-600 transition-colors border border-dashed border-gray-300 font-medium w-full sm:w-auto">
        <i class="fa-solid fa-wallet mr-2 text-gray-500"></i> Günlük Gelir / Gider İşlemi Ekle
    </a>
</div>
@endsection
