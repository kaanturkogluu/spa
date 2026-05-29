@extends('layouts.app')

@section('content')
<div class="mb-8 flex justify-between items-center">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">Masaj Paketleri</h2>
        <p class="text-gray-500 mt-1">Sistemdeki masaj paketlerini ve primleri yönetin.</p>
    </div>
    <button onclick="document.getElementById('addModal').classList.remove('hidden')" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium shadow transition-colors">
        <i class="fa-solid fa-plus mr-2"></i> Yeni Paket
    </button>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($packages as $package)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow relative">
        <div class="p-6">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center text-2xl">
                    <i class="fa-solid fa-spa"></i>
                </div>
                <div class="flex space-x-1">
                    <button onclick="openEditModal({{ $package->id }}, '{{ $package->name }}', {{ $package->price }}, {{ $package->staff_premium }}, {{ $package->reception_premium }})" class="text-blue-500 hover:bg-blue-50 p-2 rounded-lg transition-colors">
                        <i class="fa-solid fa-pen"></i>
                    </button>
                    <form action="{{ route('admin.packages.destroy', $package) }}" method="POST" onsubmit="return confirm('Silmek istediğinize emin misiniz?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:bg-red-50 p-2 rounded-lg transition-colors">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
            
            <h3 class="text-xl font-bold text-gray-800 mb-1">{{ $package->name }}</h3>
            <p class="text-3xl font-black text-gray-900 mb-6">{{ number_format($package->price, 2) }} ₺</p>
            
            <div class="space-y-3">
                <div class="flex justify-between items-center py-2 border-t border-gray-100">
                    <span class="text-gray-500 text-sm"><i class="fa-solid fa-user-nurse mr-2"></i>Personel Primi</span>
                    <span class="font-bold text-gray-800">{{ number_format($package->staff_premium, 2) }} ₺</span>
                </div>
                <div class="flex justify-between items-center py-2 border-t border-gray-100">
                    <span class="text-gray-500 text-sm"><i class="fa-solid fa-desktop mr-2"></i>Resepsiyon Primi</span>
                    <span class="font-bold text-gray-800">{{ number_format($package->reception_premium, 2) }} ₺</span>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    @if($packages->isEmpty())
    <div class="col-span-full bg-white p-8 rounded-2xl shadow-sm border border-gray-100 text-center">
        <div class="text-gray-400 text-5xl mb-4"><i class="fa-solid fa-box-open"></i></div>
        <p class="text-gray-500 text-lg">Henüz hiç masaj paketi eklenmemiş.</p>
    </div>
    @endif
</div>

<!-- Add Modal -->
<div id="addModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl w-full max-w-md p-6 m-4 shadow-xl">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-800">Yeni Masaj Paketi</h3>
            <button onclick="document.getElementById('addModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-times text-xl"></i></button>
        </div>
        <form action="{{ route('admin.packages.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Paket Adı</label>
                <input type="text" name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fiyat (₺)</label>
                <input type="number" step="0.01" name="price" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Personel Primi (₺)</label>
                    <input type="number" step="0.01" name="staff_premium" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Resepsiyon Primi (₺)</label>
                    <input type="number" step="0.01" name="reception_premium" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">
                </div>
            </div>
            <div class="pt-4 flex justify-end space-x-2">
                <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" class="px-4 py-2 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg">İptal</button>
                <button type="submit" class="px-4 py-2 text-white bg-green-600 hover:bg-green-700 rounded-lg font-medium">Kaydet</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl w-full max-w-md p-6 m-4 shadow-xl">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-800">Paket Düzenle</h3>
            <button onclick="document.getElementById('editModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-times text-xl"></i></button>
        </div>
        <form id="editForm" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Paket Adı</label>
                <input type="text" name="name" id="edit_name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fiyat (₺)</label>
                <input type="number" step="0.01" name="price" id="edit_price" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Personel Primi (₺)</label>
                    <input type="number" step="0.01" name="staff_premium" id="edit_staff_premium" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Resepsiyon Primi (₺)</label>
                    <input type="number" step="0.01" name="reception_premium" id="edit_reception_premium" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">
                </div>
            </div>
            <div class="pt-4 flex justify-end space-x-2">
                <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')" class="px-4 py-2 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg">İptal</button>
                <button type="submit" class="px-4 py-2 text-white bg-blue-600 hover:bg-blue-700 rounded-lg font-medium">Güncelle</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openEditModal(id, name, price, staffPremium, receptionPremium) {
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_price').value = price;
        document.getElementById('edit_staff_premium').value = staffPremium;
        document.getElementById('edit_reception_premium').value = receptionPremium;
        document.getElementById('editForm').action = '/admin/packages/' + id;
        document.getElementById('editModal').classList.remove('hidden');
    }
</script>
@endsection
