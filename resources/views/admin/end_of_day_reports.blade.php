@extends('layouts.app')

@section('content')
<div class="mb-8">
    <h2 class="text-3xl font-bold text-gray-800"><i class="fa-solid fa-file-invoice-dollar text-green-500 mr-3"></i> Gün Sonu Raporları</h2>
    <p class="text-gray-500 mt-1">Geçmişe dönük tüm gün sonu kayıtlarının listesi.</p>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-5 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
        <h3 class="text-lg font-bold text-gray-800"><i class="fa-solid fa-list text-blue-500 mr-2"></i> Kayıtlı Raporlar</h3>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-600 border-b border-gray-100 text-sm">
                    <th class="py-3 px-6 font-semibold">Rapor Başlığı</th>
                    <th class="py-3 px-6 font-semibold">Tarih Aralığı</th>
                    <th class="py-3 px-6 font-semibold">Oluşturan</th>
                    <th class="py-3 px-6 font-semibold">Oluşturulma Zamanı</th>
                    <th class="py-3 px-6 font-semibold text-right">İşlem</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-800">
                @forelse($reports as $report)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="py-4 px-6 font-medium text-blue-600">
                        <a href="{{ route('shared.end_of_day.show', $report->id) }}" class="hover:underline">
                            {{ $report->title }}
                        </a>
                    </td>
                    <td class="py-4 px-6 text-sm text-gray-600">
                        {{ $report->start_date->format('d.m.Y H:i') }} - {{ $report->end_date->format('d.m.Y H:i') }}
                    </td>
                    <td class="py-4 px-6">
                        @if($report->creator)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                {{ $report->creator->name }} {{ $report->creator->surname }}
                            </span>
                        @else
                            <span class="text-gray-400 text-sm">Bilinmiyor</span>
                        @endif
                    </td>
                    <td class="py-4 px-6 text-sm text-gray-500">
                        {{ $report->created_at->format('d.m.Y H:i') }}
                    </td>
                    <td class="py-4 px-6 text-right">
                        <a href="{{ route('shared.end_of_day.show', $report->id) }}" class="text-blue-500 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg text-sm font-medium transition-colors">
                            Detayı Gör <i class="fa-solid fa-arrow-right ml-1"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-12 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fa-solid fa-box-open text-4xl text-gray-300 mb-3"></i>
                            <p>Henüz oluşturulmuş bir gün sonu raporu bulunmuyor.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
