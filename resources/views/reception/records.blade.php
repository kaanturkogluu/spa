@extends('layouts.app')

@section('content')
<div class="mb-8 flex justify-between items-center">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">Masaj Kayıtları</h2>
        <p class="text-gray-500 mt-1">Masaj seanslarını ekleyin, güncelleyin veya silin.</p>
    </div>
    <button onclick="openAddModal()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium shadow transition-colors">
        <i class="fa-solid fa-plus mr-2"></i> Yeni Kayıt
    </button>
</div>

@if(request()->has('room'))
<div class="mb-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg flex justify-between items-center">
    <div>
        <p class="text-blue-700 font-medium">
            <i class="fa-solid fa-filter mr-2"></i> 
            <strong>Oda {{ request()->room }}</strong> için bugünkü kayıtlar listeleniyor.
        </p>
    </div>
    <a href="{{ route('reception.records.index') }}" class="text-sm bg-white text-blue-600 border border-blue-200 px-3 py-1 rounded hover:bg-blue-100 transition-colors">Tüm Kayıtları Gör</a>
</div>
@endif

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-600 border-b border-gray-100">
                    <th class="py-4 px-6 font-semibold">Tarih</th>
                    <th class="py-4 px-6 font-semibold">Oda</th>
                    <th class="py-4 px-6 font-semibold">Personel / Paket</th>
                    <th class="py-4 px-6 font-semibold">Süre / Ödeme</th>
                    <th class="py-4 px-6 font-semibold">Tutar</th>
                    <th class="py-4 px-6 font-semibold text-right">İşlemler</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-800">
                @foreach($records as $record)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="py-4 px-6 text-sm">
                        <div class="font-medium">{{ $record->created_at->format('d.m.Y') }}</div>
                        <div class="text-gray-500">{{ $record->created_at->format('H:i') }}</div>
                    </td>
                    <td class="py-4 px-6 text-sm font-bold text-gray-700">
                        Oda {{ $record->room_number }}
                    </td>
                    <td class="py-4 px-6 text-sm">
                        <div class="font-medium text-blue-600 mb-1"><i class="fa-solid fa-user-nurse mr-1"></i> {{ $record->staff->first_name ?? 'Silinmiş' }} {{ $record->staff->last_name ?? '' }}</div>
                        @if($record->staff2)
                        <div class="font-medium text-blue-500 mb-1"><i class="fa-solid fa-user-nurse mr-1"></i> {{ $record->staff2->first_name }} {{ $record->staff2->last_name }}</div>
                        @endif
                        <div class="font-medium text-purple-600"><i class="fa-solid fa-spa mr-1"></i> {{ $record->package->name ?? 'Silinmiş Paket' }}</div>
                    </td>
                    <td class="py-4 px-6 text-sm">
                        @if($record->start_time && $record->end_time)
                            <div class="mb-1 text-gray-600 font-medium">
                                <i class="fa-regular fa-clock mr-1 text-gray-400"></i> {{ $record->start_time->format('H:i') }} - {{ $record->end_time->format('H:i') }}
                            </div>
                        @else
                            <div class="mb-1 text-gray-600 font-medium"><i class="fa-regular fa-clock mr-1 text-gray-400"></i> {{ $record->duration_minutes }} dk</div>
                        @endif
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
                        <div class="font-bold text-lg text-gray-900">{{ number_format($record->final_price, 2) }} ₺</div>
                        @if($record->discount > 0)
                            <div class="text-orange-500 text-xs">(-{{ number_format($record->discount, 2) }} ₺ İndirim)</div>
                        @endif
                    </td>
                    <td class="py-4 px-6 text-right space-x-2">
                        @if($record->created_at->isToday())
                        <button onclick="openEditModal({{ $record->id }}, {{ $record->room_number }}, {{ $record->staff_id }}, {{ $record->staff_id_2 ?? 'null' }}, {{ $record->massage_package_id }}, '{{ $record->start_time ? $record->start_time->format('H:i') : '' }}', '{{ $record->end_time ? $record->end_time->format('H:i') : '' }}', '{{ $record->payment_method }}', {{ $record->discount }})" class="text-blue-500 hover:bg-blue-50 p-2 rounded-lg transition-colors">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                        <form action="{{ route('reception.records.destroy', $record) }}" method="POST" class="inline-block" onsubmit="return confirm('Silmek istediğinize emin misiniz?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:bg-red-50 p-2 rounded-lg transition-colors">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                        @else
                        <span class="text-xs text-gray-400 italic">Geçmiş Kayıt</span>
                        @endif
                    </td>
                </tr>
                @endforeach
                @if($records->isEmpty())
                <tr>
                    <td colspan="6" class="py-8 text-center text-gray-500">Son 1 haftada hiç kayıt bulunamadı.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div id="recordModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl w-full max-w-md p-6 m-4 shadow-xl">
        <div class="flex justify-between items-center mb-4">
            <h3 id="modalTitle" class="text-xl font-bold text-gray-800">Yeni Masaj Kaydı</h3>
            <button onclick="document.getElementById('recordModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-times text-xl"></i></button>
        </div>
        <form id="recordForm" action="{{ route('reception.records.store') }}" method="POST" class="space-y-4" onsubmit="return disableSubmitButton(this);">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Oda Seçin</label>
                    <select name="room_number" id="room_number" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">
                        <option value="">Seçiniz</option>
                        @for($i = 1; $i <= 10; $i++)
                            <option value="{{ $i }}">Oda {{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Masaj Paketi Seçin</label>
                    <select name="massage_package_id" id="massage_package_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500" onchange="updatePrice()">
                        <option value="" data-price="0">Seçiniz</option>
                        @foreach($packages as $package)
                            <option value="{{ $package->id }}" data-price="{{ $package->price }}">{{ $package->name }} ({{ number_format($package->price, 2) }} ₺)</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Personel</label>
                    <select name="staff_id" id="staff_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">
                        <option value="">Seçiniz</option>
                        @foreach($staffs as $staff)
                            <option value="{{ $staff->id }}">{{ $staff->first_name }} {{ $staff->last_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">2. Personel (Çift Kişilik)</label>
                    <select name="staff_id_2" id="staff_id_2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 text-gray-500">
                        <option value="">Yok</option>
                        @foreach($staffs as $staff)
                            <option value="{{ $staff->id }}">{{ $staff->first_name }} {{ $staff->last_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Giriş Saati</label>
                    <input type="time" name="start_time" id="start_time" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Çıkış Saati</label>
                    <div class="flex">
                        <input type="time" name="end_time" id="end_time" required class="w-full px-3 py-2 border border-gray-300 rounded-l-lg focus:ring-green-500 focus:border-green-500">
                        <button type="button" onclick="add60Minutes()" class="bg-gray-100 border border-gray-300 border-l-0 rounded-r-lg px-3 text-sm font-medium text-gray-600 hover:bg-gray-200" title="Giriş saatine 60 dakika ekle">+60 Dk</button>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ödeme Yöntemi</label>
                <select name="payment_method" id="payment_method" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">
                    <option value="nakit">Nakit</option>
                    <option value="kredi_karti">Kredi Kartı</option>
                    <option value="havale">Havale / EFT</option>
                </select>
            </div>

            <div class="border-t border-gray-100 pt-4 mt-4">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm text-gray-500">Paket Fiyatı:</span>
                    <span id="basePriceDisplay" class="font-bold">0.00 ₺</span>
                </div>
                
                <button type="button" id="toggleDiscountBtn" onclick="toggleDiscountField()" class="text-sm text-orange-500 hover:text-orange-600 mb-2 font-medium">
                    <i class="fa-solid fa-tag"></i> İndirim Uygula
                </button>
                
                <div id="discountContainer" class="hidden mb-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">İndirim Miktarı (₺)</label>
                    <input type="number" name="discount" id="discount" value="0" step="0.01" min="0" oninput="calculateFinalPrice()" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">
                </div>
                
                <div class="flex justify-between items-center mt-4 p-3 bg-gray-50 rounded-lg border border-gray-200">
                    <span class="text-sm font-bold text-gray-800">Ödenecek Tutar:</span>
                    <span id="finalPriceDisplay" class="text-xl font-black text-green-600">0.00 ₺</span>
                </div>
            </div>

            <div class="pt-4 flex justify-end space-x-2">
                <button type="button" onclick="document.getElementById('recordModal').classList.add('hidden')" class="px-4 py-2 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg">İptal</button>
                <button type="submit" class="px-4 py-2 text-white bg-green-600 hover:bg-green-700 rounded-lg font-medium">Kaydet</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openAddModal() {
        document.getElementById('modalTitle').innerText = 'Yeni Masaj Kaydı';
        document.getElementById('formMethod').value = 'POST';
        document.getElementById('recordForm').action = "{{ route('reception.records.store') }}";
        
        document.getElementById('room_number').value = '';
        document.getElementById('staff_id').value = '';
        document.getElementById('staff_id_2').value = '';
        document.getElementById('massage_package_id').value = '';
        
        // Şimdiki saati giriş saati olarak ayarla
        const now = new Date();
        const currentHours = String(now.getHours()).padStart(2, '0');
        const currentMinutes = String(now.getMinutes()).padStart(2, '0');
        document.getElementById('start_time').value = `${currentHours}:${currentMinutes}`;
        document.getElementById('end_time').value = '';
        
        document.getElementById('payment_method').value = 'nakit';
        document.getElementById('discount').value = '0';
        document.getElementById('discountContainer').classList.add('hidden');
        document.getElementById('toggleDiscountBtn').classList.remove('hidden');
        
        updatePrice();
        document.getElementById('recordModal').classList.remove('hidden');
    }

    function openEditModal(id, roomNumber, staffId, staffId2, packageId, startTime, endTime, paymentMethod, discount) {
        document.getElementById('modalTitle').innerText = 'Kaydı Düzenle';
        document.getElementById('formMethod').value = 'PUT';
        document.getElementById('recordForm').action = '/reception/records/' + id;
        
        document.getElementById('room_number').value = roomNumber;
        document.getElementById('staff_id').value = staffId;
        document.getElementById('staff_id_2').value = staffId2 || '';
        document.getElementById('massage_package_id').value = packageId;
        document.getElementById('start_time').value = startTime;
        document.getElementById('end_time').value = endTime;
        document.getElementById('payment_method').value = paymentMethod;
        document.getElementById('discount').value = discount;
        
        if (discount > 0) {
            document.getElementById('discountContainer').classList.remove('hidden');
            document.getElementById('toggleDiscountBtn').classList.add('hidden');
        } else {
            document.getElementById('discountContainer').classList.add('hidden');
            document.getElementById('toggleDiscountBtn').classList.remove('hidden');
        }
        
        updatePrice();
        document.getElementById('recordModal').classList.remove('hidden');
    }

    function toggleDiscountField() {
        document.getElementById('discountContainer').classList.remove('hidden');
        document.getElementById('toggleDiscountBtn').classList.add('hidden');
    }

    function updatePrice() {
        const select = document.getElementById('massage_package_id');
        const selectedOption = select.options[select.selectedIndex];
        const basePrice = parseFloat(selectedOption.getAttribute('data-price')) || 0;
        
        document.getElementById('basePriceDisplay').innerText = basePrice.toFixed(2) + ' ₺';
        calculateFinalPrice();
    }

    function calculateFinalPrice() {
        const select = document.getElementById('massage_package_id');
        const selectedOption = select.options[select.selectedIndex];
        const basePrice = parseFloat(selectedOption.getAttribute('data-price')) || 0;
        
        const discount = parseFloat(document.getElementById('discount').value) || 0;
        let finalPrice = basePrice - discount;
        if(finalPrice < 0) finalPrice = 0;
        
        document.getElementById('finalPriceDisplay').innerText = finalPrice.toFixed(2) + ' ₺';
    }

    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const room = urlParams.get('room');
        const action = urlParams.get('action');
        
        if (room && action === 'add') {
            openAddModal();
            document.getElementById('room_number').value = room;
        }
    });

    function add60Minutes() {
        const startTimeInput = document.getElementById('start_time').value;
        if (!startTimeInput) return;
        const [hours, minutes] = startTimeInput.split(':').map(Number);
        let date = new Date();
        date.setHours(hours, minutes, 0, 0);
        date.setMinutes(date.getMinutes() + 60);
        
        const endHours = String(date.getHours()).padStart(2, '0');
        const endMinutes = String(date.getMinutes()).padStart(2, '0');
        document.getElementById('end_time').value = `${endHours}:${endMinutes}`;
    }
</script>
@endsection
