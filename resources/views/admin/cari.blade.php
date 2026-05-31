@extends('layouts.app')

@section('content')
<div class="mb-8 flex flex-col md:flex-row md:justify-between md:items-end gap-4">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">Cari & Finans</h2>
        <p class="text-gray-500 mt-1">Gelir-gider tabloları, kasa durumu ve prim hak edişleri.</p>
    </div>
    
    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex-shrink-0">
        <form action="{{ route('admin.cari') }}" method="GET" class="flex flex-col sm:flex-row gap-3 items-end">
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Başlangıç Tarihi</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-green-500 focus:border-green-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Bitiş Tarihi</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-green-500 focus:border-green-500">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium shadow transition-colors text-sm">
                    Filtrele
                </button>
                @if(request('start_date') || request('end_date'))
                <a href="{{ route('admin.cari') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg font-medium transition-colors text-sm flex items-center justify-center">
                    Temizle
                </a>
                @endif
                <button type="button" onclick="document.getElementById('addExpenseModal').classList.remove('hidden')" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium shadow transition-colors text-sm whitespace-nowrap">
                    <i class="fa-solid fa-plus mr-1"></i> Yeni İşlem Ekle
                </button>
            </div>
        </form>
    </div>
</div>

@if(request('start_date') && request('end_date'))
<div class="mb-6 bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-xl flex items-center">
    <i class="fa-solid fa-calendar-day mr-3 text-xl"></i>
    <div>
        <strong>{{ \Carbon\Carbon::parse(request('start_date'))->format('d.m.Y') }}</strong> ile <strong>{{ \Carbon\Carbon::parse(request('end_date'))->format('d.m.Y') }}</strong> tarihleri arasındaki kayıtlar gösteriliyor.
    </div>
</div>
@endif

<!-- Genel Kasa Durumu -->
<h3 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2"><i class="fa-solid fa-vault text-gray-400 mr-2"></i> Kasa Özeti</h3>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-2xl shadow-sm p-6 border-t-4 border-blue-500 hover:shadow-md transition-shadow">
        <p class="text-sm font-medium text-gray-500 mb-1">Toplam Brüt Gelir</p>
        <h3 class="text-3xl font-bold text-blue-600">{{ number_format($totalIncome, 2) }} ₺</h3>
    </div>
    
    <div class="bg-white rounded-2xl shadow-sm p-6 border-t-4 border-red-500 hover:shadow-md transition-shadow">
        <p class="text-sm font-medium text-gray-500 mb-1">Toplam Gider</p>
        <h3 class="text-3xl font-bold text-red-600">-{{ number_format($totalExpense, 2) }} ₺</h3>
    </div>
    
    <div class="bg-white rounded-2xl shadow-sm p-6 border-t-4 border-green-500 hover:shadow-md transition-shadow">
        <p class="text-sm font-medium text-gray-500 mb-1">Net Kasa (Bakiye)</p>
        <h3 class="text-3xl font-bold text-green-600">{{ number_format($netBalance, 2) }} ₺</h3>
    </div>
</div>

<!-- Ödeme Yöntemleri Dağılımı -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-500 mb-1">Nakit Kasa</p>
            <h3 class="text-2xl font-bold text-gray-800">{{ number_format($incomeNakit, 2) }} ₺</h3>
        </div>
        <div class="w-12 h-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center text-xl">
            <i class="fa-solid fa-money-bill-wave"></i>
        </div>
    </div>
    
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-500 mb-1">Kredi Kartı (POS)</p>
            <h3 class="text-2xl font-bold text-gray-800">{{ number_format($incomeKrediKarti, 2) }} ₺</h3>
        </div>
        <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center text-xl">
            <i class="fa-solid fa-credit-card"></i>
        </div>
    </div>
    
    <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-500 mb-1">Havale / EFT</p>
            <h3 class="text-2xl font-bold text-gray-800">{{ number_format($incomeHavale, 2) }} ₺</h3>
        </div>
        <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-xl flex items-center justify-center text-xl">
            <i class="fa-solid fa-building-columns"></i>
        </div>
    </div>
</div>

<!-- Personel Hak Edişleri -->
<h3 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2"><i class="fa-solid fa-users text-blue-500 mr-2"></i> Personel Hak Edişleri (Primler)</h3>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    @forelse($staffPremiums as $stat)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col hover:shadow-md transition-shadow">
        <div class="p-4 border-b border-gray-100 bg-blue-50 flex justify-between items-center">
            <h4 class="font-bold text-gray-800 text-lg"><i class="fa-solid fa-user-nurse text-blue-400 mr-2"></i> {{ $stat['name'] }}</h4>
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
                            <td class="py-3 px-4 text-right font-semibold text-blue-600">{{ number_format($detail['premium'], 2) }} ₺</td>
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
                        <th class="py-3 px-4 text-right font-black text-blue-700 text-base whitespace-nowrap">{{ number_format($stat['premium'], 2) }} ₺</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    @empty
    <div class="col-span-full bg-white rounded-2xl shadow-sm border border-gray-100 p-10 text-center text-gray-500">
        <i class="fa-solid fa-users text-5xl text-gray-300 mb-4 block"></i>
        Bu zaman aralığında hiçbir personel işlemi kaydedilmedi.
    </div>
    @endforelse
</div>

<!-- Resepsiyonist Hak Edişleri -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
    <div class="p-5 border-b border-gray-100 bg-gray-50 flex items-center">
        <i class="fa-solid fa-desktop text-purple-500 text-xl mr-3"></i>
        <h3 class="text-lg font-bold text-gray-800">Resepsiyonist Hak Edişleri (Primler)</h3>
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
                @forelse($receptionPremiums as $stat)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="py-4 px-6 font-medium">
                        {{ $stat['name'] }}
                    </td>
                    <td class="py-4 px-6 text-center font-bold text-gray-700">
                        {{ $stat['count'] }}
                    </td>
                    <td class="py-4 px-6 text-right font-bold text-purple-600 text-lg">
                        {{ number_format($stat['premium'], 2) }} ₺
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="py-8 text-center text-gray-500">Bu zaman aralığında hiçbir resepsiyon işlemi kaydedilmedi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Filtrelenen Masaj Kayıtları -->
<div class="mt-8 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-5 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
        <div class="flex items-center">
            <i class="fa-solid fa-list text-green-500 text-xl mr-3"></i>
            <h3 class="text-lg font-bold text-gray-800">Seçili Tarihteki Masaj Kayıtları</h3>
        </div>
        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold">{{ $records->count() }} Kayıt</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-600 border-b border-gray-100 text-sm">
                    <th class="py-3 px-6 font-semibold">Tarih</th>
                    <th class="py-3 px-6 font-semibold">Resepsiyon / Personel</th>
                    <th class="py-3 px-6 font-semibold">Paket / Süre</th>
                    <th class="py-3 px-6 font-semibold">Ödeme</th>
                    <th class="py-3 px-6 font-semibold text-right">Tutar</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-800">
                @forelse($records as $record)
                <tr class="hover:bg-gray-50 transition-colors text-sm">
                    <td class="py-3 px-6">
                        <div class="font-medium">{{ $record->created_at->format('d.m.Y') }}</div>
                        <div class="text-gray-500 text-xs">{{ $record->created_at->format('H:i') }}</div>
                    </td>
                    <td class="py-3 px-6">
                        <div class="text-xs text-gray-500">Giren: <span class="font-medium text-gray-700">{{ $record->creator->name ?? '-' }}</span></div>
                        <div class="text-xs text-gray-500 mt-1">Personel: <span class="font-medium text-blue-600">{{ $record->staff->first_name ?? '-' }} {{ $record->staff->last_name ?? '' }}</span></div>
                    </td>
                    <td class="py-3 px-6">
                        <div class="font-medium text-purple-600"><i class="fa-solid fa-spa mr-1"></i> {{ $record->package->name ?? '-' }}</div>
                        <div class="text-xs text-gray-500 mt-1"><i class="fa-regular fa-clock mr-1"></i> {{ $record->duration_minutes }} dk</div>
                    </td>
                    <td class="py-3 px-6">
                        @if($record->payment_method == 'nakit')
                            <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-semibold">Nakit</span>
                        @elseif($record->payment_method == 'kredi_karti')
                            <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-semibold">Kredi Kartı</span>
                        @else
                            <span class="px-2 py-1 bg-orange-100 text-orange-700 rounded text-xs font-semibold">Havale</span>
                        @endif
                    </td>
                    <td class="py-3 px-6 text-right">
                        @if($record->discount > 0)
                            <div class="text-xs text-orange-500">-{{ number_format($record->discount, 2) }} ₺</div>
                        @endif
                        <div class="font-bold text-gray-900">{{ number_format($record->final_price, 2) }} ₺</div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-6 text-center text-gray-500">Seçili tarihte kayıt bulunamadı.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Add Expense Modal for Admin -->
<div id="addExpenseModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl w-full max-w-md p-6 m-4 shadow-xl border-t-4 border-blue-500">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-800">Cari İşlem Ekle</h3>
            <button onclick="document.getElementById('addExpenseModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-times text-xl"></i></button>
        </div>
        
        <form action="{{ route('admin.cari.expense') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">İşlem Türü</label>
                <div class="flex space-x-4">
                    <label class="inline-flex items-center">
                        <input type="radio" name="type" value="income" class="form-radio text-green-600 h-4 w-4" required>
                        <span class="ml-2 text-gray-700">Gelir Ekle</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="type" value="expense" class="form-radio text-red-600 h-4 w-4" checked required>
                        <span class="ml-2 text-gray-700">Gider Ekle</span>
                    </label>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">İşlem Adı / Açıklama</label>
                <input type="text" name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tutar (₺)</label>
                <input type="number" step="0.01" name="amount" required min="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
            </div>
            
            <div class="pt-4 flex justify-end space-x-2">
                <button type="button" onclick="document.getElementById('addExpenseModal').classList.add('hidden')" class="px-4 py-2 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg">İptal</button>
                <button type="submit" class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-700 rounded-lg font-medium">Kaydet</button>
            </div>
        </form>
    </div>
</div>

@endsection
