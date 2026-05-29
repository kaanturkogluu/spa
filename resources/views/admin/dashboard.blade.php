@extends('layouts.app')

@section('content')
<div class="mb-8">
    <h2 class="text-3xl font-bold text-gray-800">Admin Dashboard</h2>
    <p class="text-gray-500 mt-1">Sistem genelindeki istatistikler ve özet bilgiler.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Stat Card 1 -->
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center justify-between hover:shadow-md transition-shadow">
        <div>
            <p class="text-sm font-medium text-gray-500 mb-1">Toplam Resepsiyonist</p>
            <h3 class="text-3xl font-bold text-gray-800">{{ $totalUsers }}</h3>
        </div>
        <div class="w-12 h-12 bg-blue-100 text-blue-500 rounded-full flex items-center justify-center text-xl">
            <i class="fa-solid fa-user-tie"></i>
        </div>
    </div>

    <!-- Stat Card 2 -->
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center justify-between hover:shadow-md transition-shadow">
        <div>
            <p class="text-sm font-medium text-gray-500 mb-1">Toplam Personel</p>
            <h3 class="text-3xl font-bold text-gray-800">{{ $totalStaff }}</h3>
        </div>
        <div class="w-12 h-12 bg-green-100 text-green-500 rounded-full flex items-center justify-center text-xl">
            <i class="fa-solid fa-users"></i>
        </div>
    </div>

    <!-- Stat Card 3 -->
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center justify-between hover:shadow-md transition-shadow">
        <div>
            <p class="text-sm font-medium text-gray-500 mb-1">Masaj Paketleri</p>
            <h3 class="text-3xl font-bold text-gray-800">{{ $totalPackages }}</h3>
        </div>
        <div class="w-12 h-12 bg-purple-100 text-purple-500 rounded-full flex items-center justify-center text-xl">
            <i class="fa-solid fa-spa"></i>
        </div>
    </div>

    <!-- Stat Card 4 -->
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center justify-between hover:shadow-md transition-shadow">
        <div>
            <p class="text-sm font-medium text-gray-500 mb-1">Toplam Kayıt</p>
            <h3 class="text-3xl font-bold text-gray-800">{{ $totalRecords }}</h3>
        </div>
        <div class="w-12 h-12 bg-orange-100 text-orange-500 rounded-full flex items-center justify-center text-xl">
            <i class="fa-solid fa-clipboard-list"></i>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
    <h3 class="text-xl font-bold text-gray-800 mb-4">Hızlı İşlemler</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('admin.users.index') }}" class="flex flex-col items-center justify-center p-6 bg-gray-50 rounded-xl hover:bg-green-50 hover:text-green-600 transition-colors border border-dashed border-gray-300">
            <i class="fa-solid fa-plus text-2xl mb-2 text-gray-400"></i>
            <span class="font-medium">Resepsiyonist Ekle</span>
        </a>
        <a href="{{ route('admin.packages.index') }}" class="flex flex-col items-center justify-center p-6 bg-gray-50 rounded-xl hover:bg-green-50 hover:text-green-600 transition-colors border border-dashed border-gray-300">
            <i class="fa-solid fa-plus text-2xl mb-2 text-gray-400"></i>
            <span class="font-medium">Paket Ekle</span>
        </a>
        <a href="{{ route('admin.tracking') }}" class="flex flex-col items-center justify-center p-6 bg-gray-50 rounded-xl hover:bg-green-50 hover:text-green-600 transition-colors border border-dashed border-gray-300">
            <i class="fa-solid fa-eye text-2xl mb-2 text-gray-400"></i>
            <span class="font-medium">Son Kayıtları İzle</span>
        </a>
    </div>
</div>
@endsection
