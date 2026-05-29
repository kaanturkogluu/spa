@extends('layouts.app')

@section('content')
<div class="mb-8">
    <h2 class="text-3xl font-bold text-gray-800">Gün Raporu</h2>
    <p class="text-gray-500 mt-1">Bugün yapılan işlemlerin ve personel hak edişlerinin özeti ({{ \Carbon\Carbon::now()->format('d.m.Y') }}).</p>
</div>

<!-- Özet Kartları -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-blue-500 flex items-center justify-between hover:shadow-md transition-shadow">
        <div>
            <p class="text-sm font-medium text-gray-500 mb-1">Bugün Toplam Masaj</p>
            <h3 class="text-3xl font-bold text-gray-800">{{ $totalMassages }}</h3>
        </div>
        <div class="w-12 h-12 bg-blue-100 text-blue-500 rounded-full flex items-center justify-center text-xl">
            <i class="fa-solid fa-bed"></i>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-green-500 flex items-center justify-between hover:shadow-md transition-shadow">
        <div>
            <p class="text-sm font-medium text-gray-500 mb-1">Bugün Toplam Ciro (Brüt)</p>
            <h3 class="text-3xl font-bold text-gray-800">{{ number_format($totalIncome, 2) }} ₺</h3>
        </div>
        <div class="w-12 h-12 bg-green-100 text-green-500 rounded-full flex items-center justify-center text-xl">
            <i class="fa-solid fa-coins"></i>
        </div>
    </div>
</div>

<!-- Personel Raporu -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
    <div class="p-5 border-b border-gray-100 bg-gray-50 flex items-center">
        <i class="fa-solid fa-users text-purple-500 text-xl mr-3"></i>
        <h3 class="text-lg font-bold text-gray-800">Personel Performans ve Prim Raporu (Bugün)</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-600 border-b border-gray-100 text-sm">
                    <th class="py-3 px-6 font-semibold">Personel Adı</th>
                    <th class="py-3 px-6 font-semibold text-center">Girilen Masaj Sayısı</th>
                    <th class="py-3 px-6 font-semibold text-right">Hak Edilen Toplam Prim</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-800">
                @forelse($staffStats as $stat)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="py-4 px-6 font-medium">
                        {{ $stat['name'] }}
                    </td>
                    <td class="py-4 px-6 text-center font-bold text-gray-700">
                        {{ $stat['count'] }}
                    </td>
                    <td class="py-4 px-6 text-right font-bold text-green-600 text-lg">
                        {{ number_format($stat['premium'], 2) }} ₺
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="py-8 text-center text-gray-500">Bugün henüz hiçbir personel işlemi kaydedilmedi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
