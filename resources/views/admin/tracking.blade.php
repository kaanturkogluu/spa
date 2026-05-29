@extends('layouts.app')

@section('content')
<div class="mb-8">
    <h2 class="text-3xl font-bold text-gray-800">Sistem İzleme</h2>
    <p class="text-gray-500 mt-1">Resepsiyonistler tarafından yapılan tüm masaj kayıtları ve değişiklikler.</p>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-600 border-b border-gray-100">
                    <th class="py-4 px-6 font-semibold">Tarih</th>
                    <th class="py-4 px-6 font-semibold">İşlem Yapan</th>
                    <th class="py-4 px-6 font-semibold">Personel / Paket</th>
                    <th class="py-4 px-6 font-semibold">Süre / Ödeme</th>
                    <th class="py-4 px-6 font-semibold">Tutar Bilgisi</th>
                    <th class="py-4 px-6 font-semibold">Durum</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-800">
                @foreach($records as $record)
                <tr class="hover:bg-gray-50 transition-colors {{ $record->trashed() ? 'bg-red-50' : '' }}">
                    <td class="py-4 px-6 text-sm">
                        <div class="font-medium">{{ $record->created_at->format('d.m.Y') }}</div>
                        <div class="text-gray-500">{{ $record->created_at->format('H:i') }}</div>
                    </td>
                    <td class="py-4 px-6 text-sm">
                        <div><span class="text-gray-500 text-xs block mb-1">Oluşturan:</span> <span class="font-medium">{{ $record->creator->name ?? 'Bilinmiyor' }}</span></div>
                        @if($record->updated_by && $record->created_at != $record->updated_at)
                            <div class="mt-1"><span class="text-gray-500 text-xs block mb-1">Güncelleyen:</span> <span class="font-medium">{{ $record->updater->name ?? 'Bilinmiyor' }}</span></div>
                        @endif
                        @if($record->trashed())
                            <div class="mt-1"><span class="text-red-500 text-xs block mb-1">Silen:</span> <span class="font-medium text-red-600">{{ $record->deleter->name ?? 'Bilinmiyor' }}</span></div>
                        @endif
                    </td>
                    <td class="py-4 px-6 text-sm">
                        <div class="font-medium text-blue-600 mb-1"><i class="fa-solid fa-user-nurse mr-1"></i> {{ $record->staff->first_name ?? 'Silinmiş' }} {{ $record->staff->last_name ?? 'Personel' }}</div>
                        <div class="font-medium text-purple-600"><i class="fa-solid fa-spa mr-1"></i> {{ $record->package->name ?? 'Silinmiş Paket' }}</div>
                    </td>
                    <td class="py-4 px-6 text-sm">
                        <div class="mb-1"><i class="fa-regular fa-clock mr-1 text-gray-400"></i> {{ $record->duration_minutes }} dk</div>
                        <div>
                            @if($record->payment_method == 'nakit')
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-semibold">Nakit</span>
                            @elseif($record->payment_method == 'kredi_karti')
                                <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-semibold">Kredi Kartı</span>
                            @else
                                <span class="px-2 py-1 bg-orange-100 text-orange-700 rounded text-xs font-semibold">Havale/EFT</span>
                            @endif
                        </div>
                    </td>
                    <td class="py-4 px-6 text-sm">
                        <div class="text-gray-500 line-through text-xs">{{ number_format($record->base_price, 2) }} ₺</div>
                        @if($record->discount > 0)
                            <div class="text-orange-500 text-xs">-{{ number_format($record->discount, 2) }} ₺ İndirim</div>
                        @endif
                        <div class="font-bold text-lg text-gray-900">{{ number_format($record->final_price, 2) }} ₺</div>
                    </td>
                    <td class="py-4 px-6">
                        @if($record->trashed())
                            <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold"><i class="fa-solid fa-trash-can mr-1"></i> Silindi</span>
                        @elseif($record->updated_by && $record->created_at != $record->updated_at)
                            <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold"><i class="fa-solid fa-pen mr-1"></i> Düzenlendi</span>
                        @else
                            <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold"><i class="fa-solid fa-check mr-1"></i> Aktif</span>
                        @endif
                    </td>
                </tr>
                @endforeach
                @if($records->isEmpty())
                <tr>
                    <td colspan="6" class="py-8 text-center text-gray-500">Henüz hiç kayıt bulunamadı.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
