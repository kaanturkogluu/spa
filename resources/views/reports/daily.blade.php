@extends('layouts.app')

@section('content')
<div class="mb-8">
    <h2 class="text-3xl font-bold text-gray-800">{{ $reportTitle ?? 'Gün Raporu' }}</h2>
    <p class="text-gray-500 mt-1">İşlemlerin ve personel hak edişlerinin özeti.</p>
</div>

<!-- Özet Kartları -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
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
            <h3 class="text-3xl font-bold text-gray-800">{{ number_format($totalGrossIncome, 2) }} ₺</h3>
        </div>
        <div class="w-12 h-12 bg-green-100 text-green-500 rounded-full flex items-center justify-center text-xl">
            <i class="fa-solid fa-coins"></i>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-emerald-500 flex items-center justify-between hover:shadow-md transition-shadow">
        <div>
            <p class="text-sm font-medium text-gray-500 mb-1">Bugünkü Net Ciro</p>
            <h3 class="text-3xl font-bold text-emerald-600">{{ number_format($netCiro, 2) }} ₺</h3>
        </div>
        <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center text-xl">
            <i class="fa-solid fa-vault"></i>
        </div>
    </div>
</div>

<!-- Günün Özeti (Detaylı) -->
<h3 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2"><i class="fa-solid fa-chart-bar text-gray-400 mr-2"></i> Günün Özeti (Detaylı)</h3>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    
    <!-- Gelirler Özeti -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-4 border-b border-gray-100 bg-gray-50 flex items-center">
            <i class="fa-solid fa-coins text-green-500 mr-2"></i>
            <h4 class="font-bold text-gray-800">Gelirler</h4>
        </div>
        <div class="p-4 space-y-3">
            <div class="flex justify-between items-center text-sm">
                <span class="text-gray-600">Masaj Gelirleri</span>
                <span class="font-bold text-gray-800">{{ number_format($grossIncome, 2) }} ₺</span>
            </div>
            <div class="flex justify-between items-center text-sm">
                <span class="text-gray-600">Ekstra Gelirler</span>
                <span class="font-bold text-gray-800">{{ number_format($todayIncomes, 2) }} ₺</span>
            </div>
            <div class="pt-2 mt-2 border-t border-gray-100 flex justify-between items-center">
                <span class="font-bold text-gray-800">Toplam Brüt</span>
                <span class="font-bold text-green-600">{{ number_format($totalGrossIncome, 2) }} ₺</span>
            </div>
        </div>
    </div>

    <!-- Prim Giderleri -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-4 border-b border-gray-100 bg-gray-50 flex items-center">
            <i class="fa-solid fa-user-nurse text-blue-500 mr-2"></i>
            <h4 class="font-bold text-gray-800">Prim Giderleri</h4>
        </div>
        <div class="p-4 space-y-3">
            <div class="max-h-32 overflow-y-auto pr-2 space-y-2">
                @forelse($staffStats as $stat)
                <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-600"><i class="fa-solid fa-user text-xs text-gray-400 mr-1"></i> {{ $stat['name'] }}</span>
                    <span class="font-bold text-blue-600">-{{ number_format($stat['premium'], 2) }} ₺</span>
                </div>
                @empty
                <div class="text-sm text-gray-500 text-center py-2">Henüz personel işlemi yok.</div>
                @endforelse

                @forelse($receptionStats as $stat)
                <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-600"><i class="fa-solid fa-desktop text-xs text-purple-400 mr-1"></i> {{ $stat['name'] }} (Res.)</span>
                    <span class="font-bold text-purple-600">-{{ number_format($stat['premium'], 2) }} ₺</span>
                </div>
                @empty
                @endforelse
            </div>
            <div class="pt-2 mt-2 border-t border-gray-100 flex justify-between items-center">
                <span class="font-bold text-gray-800">Toplam Prim</span>
                <span class="font-bold text-red-600">-{{ number_format($totalPremiums, 2) }} ₺</span>
            </div>
        </div>
    </div>

    <!-- Diğer Giderler -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-4 border-b border-gray-100 bg-gray-50 flex items-center">
            <i class="fa-solid fa-receipt text-red-500 mr-2"></i>
            <h4 class="font-bold text-gray-800">Diğer Giderler</h4>
        </div>
        <div class="p-4 space-y-3">
            <div class="max-h-32 overflow-y-auto pr-2 space-y-2">
                @forelse($expensesDetails as $expense)
                <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-600 truncate mr-2" title="{{ $expense->name }}">{{ $expense->name }}</span>
                    <span class="font-bold text-red-600 whitespace-nowrap">-{{ number_format($expense->amount, 2) }} ₺</span>
                </div>
                @empty
                <div class="text-sm text-gray-500 text-center py-2">Henüz dışarıdan gider eklenmedi.</div>
                @endforelse
            </div>
            <div class="pt-2 mt-2 border-t border-gray-100 flex justify-between items-center">
                <span class="font-bold text-gray-800">Toplam Gider</span>
                <span class="font-bold text-red-600">-{{ number_format($todayExpenses, 2) }} ₺</span>
            </div>
        </div>
    </div>
</div>

<!-- Personel Raporu -->
<h3 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2"><i class="fa-solid fa-users text-purple-500 mr-2"></i> Personel Performans ve Prim Raporu</h3>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    @forelse($staffStats as $stat)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col hover:shadow-md transition-shadow">
        <div class="p-4 border-b border-gray-100 bg-purple-50 flex justify-between items-center">
            <h4 class="font-bold text-gray-800 text-lg"><i class="fa-solid fa-user-nurse text-purple-400 mr-2"></i> {{ $stat['name'] }}</h4>
        </div>
        
        <div class="flex-1 p-0 overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 border-b border-gray-100 text-xs uppercase tracking-wider">
                        <th class="py-3 px-4 font-semibold">İşlem Türü</th>
                        <th class="py-3 px-4 font-semibold text-center">Adet</th>
                        <th class="py-3 px-4 font-semibold text-right">Prim</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @if(isset($stat['details']) && count($stat['details']) > 0)
                        @foreach($stat['details'] as $desc => $detail)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-3 px-4 text-gray-700 font-medium">{{ $desc }}</td>
                            <td class="py-3 px-4 text-center font-semibold text-gray-600 bg-gray-50/50">{{ $detail['count'] }}</td>
                            <td class="py-3 px-4 text-right font-semibold text-green-600">{{ number_format($detail['premium'], 2) }} ₺</td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="3" class="py-6 text-center text-gray-400 text-sm">Detay bulunamadı.</td>
                        </tr>
                    @endif
                </tbody>
                <tfoot class="bg-gray-100 border-t border-gray-200">
                    <tr>
                        <th class="py-3 px-4 text-right font-bold text-gray-700">TOPLAM:</th>
                        <th class="py-3 px-4 text-center font-black text-gray-800 text-base">{{ $stat['count'] }}</th>
                        <th class="py-3 px-4 text-right font-black text-green-700 text-base whitespace-nowrap">{{ number_format($stat['premium'], 2) }} ₺</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    @empty
    <div class="col-span-full bg-white rounded-2xl shadow-sm border border-gray-100 p-10 text-center text-gray-500">
        <i class="fa-solid fa-users text-5xl text-gray-300 mb-4 block"></i>
        Bu zaman aralığında henüz hiçbir personel işlemi kaydedilmedi.
    </div>
    @endforelse
</div>

<!-- Resepsiyon Raporu -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
    <div class="p-5 border-b border-gray-100 bg-gray-50 flex items-center">
        <i class="fa-solid fa-desktop text-blue-500 text-xl mr-3"></i>
        <h3 class="text-lg font-bold text-gray-800">Resepsiyon Performans ve Prim Raporu (Bugün)</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-600 border-b border-gray-100 text-sm">
                    <th class="py-3 px-6 font-semibold">Resepsiyonist Adı</th>
                    <th class="py-3 px-6 font-semibold text-center">Oluşturulan Masaj Sayısı</th>
                    <th class="py-3 px-6 font-semibold text-right">Hak Edilen Toplam Prim</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-800">
                @forelse($receptionStats as $stat)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="py-4 px-6 font-medium">
                        {{ $stat['name'] }}
                    </td>
                    <td class="py-4 px-6 text-center font-bold text-gray-700">
                        {{ $stat['count'] }}
                    </td>
                    <td class="py-4 px-6 text-right font-bold text-blue-600 text-lg">
                        {{ number_format($stat['premium'], 2) }} ₺
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="py-8 text-center text-gray-500">Bugün henüz hiçbir resepsiyon işlemi kaydedilmedi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
