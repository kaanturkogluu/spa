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

<div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
    <h3 class="text-xl font-bold text-gray-800 mb-4">Hızlı İşlemler</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <a href="{{ route('reception.records.index') }}" class="flex flex-col items-center justify-center p-6 bg-gray-50 rounded-xl hover:bg-green-50 hover:text-green-600 transition-colors border border-dashed border-gray-300">
            <i class="fa-solid fa-plus text-2xl mb-2 text-gray-400"></i>
            <span class="font-medium">Masaj Kaydı Ekle</span>
        </a>
        <a href="{{ route('reception.expenses.index') }}" class="flex flex-col items-center justify-center p-6 bg-gray-50 rounded-xl hover:bg-green-50 hover:text-green-600 transition-colors border border-dashed border-gray-300">
            <i class="fa-solid fa-plus text-2xl mb-2 text-gray-400"></i>
            <span class="font-medium">Gider Ekle</span>
        </a>
    </div>
</div>
@endsection
