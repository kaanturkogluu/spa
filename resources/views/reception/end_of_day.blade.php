@extends('layouts.app')

@section('content')
<div class="mb-8">
    <h2 class="text-3xl font-bold text-gray-800">Gün Sonu Yap</h2>
    <p class="text-gray-500 mt-1">Belirli bir tarih aralığındaki işlemleri gün sonu olarak kaydedin.</p>
</div>

<div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100 max-w-2xl mx-auto">
    <form action="{{ route('reception.end_of_day.store') }}" method="POST" onsubmit="return disableSubmitButton(this);">
        @csrf
        <div class="space-y-6">
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Başlangıç Tarihi ve Saati <span class="text-red-500">*</span></label>
                <input type="datetime-local" id="start_date" name="start_date" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" required>
                <p class="text-xs text-gray-500 mt-1">Raporun başlatılacağı tarihi manuel olarak seçin.</p>
            </div>

            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Bitiş Tarihi ve Saati <span class="text-red-500">*</span></label>
                <input type="datetime-local" id="end_date" name="end_date" value="{{ \Carbon\Carbon::now()->format('Y-m-d\TH:i') }}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" required>
                <p class="text-xs text-gray-500 mt-1">Varsayılan olarak şu anki zaman seçilidir.</p>
            </div>

            <div class="pt-4 border-t border-gray-100 flex justify-end">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-6 rounded-lg shadow-sm transition-colors flex items-center">
                    <i class="fa-solid fa-save mr-2"></i> Gün Sonunu Kaydet
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
