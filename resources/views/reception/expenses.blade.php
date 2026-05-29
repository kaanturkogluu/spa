@extends('layouts.app')

@section('content')
<div class="mb-8 flex justify-between items-center">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">Günlük Giderler</h2>
        <p class="text-gray-500 mt-1">Sisteme yeni gider ekleyin ve geçmiş giderleri görüntüleyin.</p>
    </div>
    <button onclick="document.getElementById('addModal').classList.remove('hidden')" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium shadow transition-colors">
        <i class="fa-solid fa-plus mr-2"></i> Gider Ekle
    </button>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-600 border-b border-gray-100">
                    <th class="py-4 px-6 font-semibold">Tarih</th>
                    <th class="py-4 px-6 font-semibold">Gider Adı</th>
                    <th class="py-4 px-6 font-semibold">Tutar</th>
                    <th class="py-4 px-6 font-semibold">Ekleyen</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-800">
                @foreach($expenses as $expense)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="py-4 px-6 text-sm">
                        <div class="font-medium">{{ $expense->created_at->format('d.m.Y') }}</div>
                        <div class="text-gray-500">{{ $expense->created_at->format('H:i') }}</div>
                    </td>
                    <td class="py-4 px-6">
                        <span class="font-medium text-gray-800"><i class="fa-solid fa-receipt text-gray-400 mr-2"></i>{{ $expense->name }}</span>
                    </td>
                    <td class="py-4 px-6">
                        <span class="font-bold text-red-600">-{{ number_format($expense->amount, 2) }} ₺</span>
                    </td>
                    <td class="py-4 px-6 text-sm">
                        {{ $expense->creator->name ?? 'Bilinmiyor' }}
                    </td>
                </tr>
                @endforeach
                @if($expenses->isEmpty())
                <tr>
                    <td colspan="4" class="py-8 text-center text-gray-500">Kayıtlı gider bulunamadı.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

<!-- Add Modal -->
<div id="addModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl w-full max-w-md p-6 m-4 shadow-xl border-t-4 border-red-500">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-800">Yeni Gider Ekle</h3>
            <button onclick="document.getElementById('addModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-times text-xl"></i></button>
        </div>
        
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fa-solid fa-triangle-exclamation text-yellow-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        Dikkat: Eklenen giderler sonradan değiştirilemez veya silinemez! Lütfen bilgileri doğru girdiğinizden emin olun.
                    </p>
                </div>
            </div>
        </div>

        <form action="{{ route('reception.expenses.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Gider Adı / Açıklama</label>
                <input type="text" name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tutar (₺)</label>
                <input type="number" step="0.01" name="amount" required min="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500">
            </div>
            
            <div class="pt-4 flex justify-end space-x-2">
                <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="px-4 py-2 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg">İptal</button>
                <button type="submit" class="px-4 py-2 text-white bg-red-600 hover:bg-red-700 rounded-lg font-medium">Gideri Ekle</button>
            </div>
        </form>
    </div>
</div>
@endsection
