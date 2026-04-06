<x-app-layout>
    <style>
        body {
            background-color: #242f6d;
        }
        header {
            background-color: #242f6d !important;
        }
        nav {
            background-color: #242f6d !important;
        }
    </style>

    <x-slot name="header">
        <h2 class="font-semibold text-lg sm:text-xl text-white leading-tight">Products</h2>
    </x-slot>

    <!-- Breadcrumb Navigation -->
    <x-breadcrumb :items="[
        ['url' => route('admin.dashboard'), 'label' => 'Admin'],
        ['url' => route('admin.products'), 'label' => 'Products']
    ]" />

    <div class="py-2 sm:py-6" style="background-color: #242f6d;">
        <div class="max-w-full sm:max-w-7xl mx-auto px-2 sm:px-4 lg:px-8">
            @if(session('success'))
                <div class="mb-2 sm:mb-4 p-2 sm:p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-xs sm:text-sm">{{ session('success') }}</div>
            @endif

            {{-- Add Product Form --}}
            <div class="bg-white rounded-lg sm:rounded-xl shadow-lg p-3 sm:p-6 mb-3 sm:mb-6">
                <h3 class="text-sm sm:text-lg font-semibold text-gray-900 mb-2 sm:mb-4">Add New Product</h3>
                <form method="POST" action="{{ route('admin.products.store') }}" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-1.5 sm:gap-3 lg:gap-4 items-end">
                    @csrf
                    <div class="col-span-1">
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Shop</label>
                        <select name="shop_id" required class="w-full px-2 py-1 sm:px-3 sm:py-2 border border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-xs sm:text-base">
                            @foreach($shops as $shop)
                                <option value="{{ $shop->id }}">{{ $shop->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-1">
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input type="text" name="name" required class="w-full px-2 py-1 sm:px-3 sm:py-2 border border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-xs sm:text-base">
                    </div>
                    <div class="col-span-1">
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 hidden sm:block">Price (IDR)</label>
                        <label class="block text-xs font-medium text-gray-700 mb-1 block sm:hidden">Price</label>
                        <input type="number" name="price" required min="0" class="w-full px-2 py-1 sm:px-3 sm:py-2 border border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-xs sm:text-base">
                    </div>
                    <div class="col-span-1">
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Type</label>
                        <select name="product_type_id" class="w-full px-2 py-1 sm:px-3 sm:py-2 border border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-xs sm:text-base">
                            <option value="">Select Type</option>
                            @foreach($productTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-1 flex items-center">
                        <input type="checkbox" name="is_seasonal" value="1" id="is_seasonal" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 w-3 h-3 sm:w-4 sm:h-4">
                        <label for="is_seasonal" class="text-xs sm:text-sm text-gray-700 ml-1 sm:ml-2 whitespace-nowrap">Seasonal</label>
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-2 sm:px-4 py-1 sm:py-2 rounded-lg text-xs sm:text-sm font-medium transition-colors">Add</button>
                    </div>
                </form>
            </div>

            {{-- Product List --}}
            <div class="bg-white rounded-lg sm:rounded-xl shadow-lg overflow-hidden">
                {{-- Mobile Card View --}}
                <div class="md:hidden space-y-2 p-2 sm:p-4">
                    @forelse($products as $product)
                        <div class="p-2 bg-gray-50 rounded-lg border border-gray-200 space-y-1">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-semibold text-xs sm:text-sm text-gray-900">{{ $product->name }}</p>
                                    <p class="text-xs text-gray-500"><i class="fas {{ $product->shop->name === 'Ice Lepen' ? 'fa-ice-cream' : 'fa-bowl-food' }}" style="color: {{ $product->shop->name === 'Ice Lepen' ? '#c41e3a' : '#f39c12' }}; margin-right: 4px;"></i>{{ $product->shop->name }}</p>
                                </div>
                                <span class="px-1.5 py-0.5 text-xs rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} font-semibold">
                                    {{ $product->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            <div class="text-xs text-gray-600">
                                <p>Price: <span class="font-semibold">Rp {{ number_format($product->price, 0, ',', '.') }}</span></p>
                                @if($product->productType?->name)
                                    <p>Type: <span class="font-semibold">{{ $product->productType->name }}</span></p>
                                @endif
                            </div>
                            <div class="pt-1 border-t border-gray-200 flex gap-2">
                                <a href="{{ route('admin.products.edit', $product) }}" class="text-xs text-blue-600 hover:text-blue-800 font-medium">Edit</a>
                                <form method="POST" action="{{ route('admin.products.toggle', $product) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-xs {{ $product->is_active ? 'text-red-600' : 'text-green-600' }}">
                                        {{ $product->is_active ? '❌ Deactivate' : '✓ Activate' }}
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.products.delete', $product) }}" class="inline" onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-red-600 hover:text-red-800 font-medium">Delete</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-xs text-gray-500 py-4">No products found</p>
                    @endforelse
                </div>

                {{-- Desktop Table View --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase">Shop</th>
                                <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                                <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Type</th>
                                <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase hidden lg:table-cell">Seasonal</th>
                                <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($products as $product)
                                <tr class="{{ !$product->is_active ? 'bg-gray-50 opacity-60' : 'hover:bg-gray-50' }}">
                                    <td class="px-3 sm:px-6 py-2 sm:py-4 text-xs sm:text-sm text-gray-900 font-medium">{{ $product->shop->name }}</td>
                                    <td class="px-3 sm:px-6 py-2 sm:py-4 text-xs sm:text-sm font-medium text-gray-900">{{ Str::limit($product->name, 12) }}</td>
                                    <td class="px-3 sm:px-6 py-2 sm:py-4 text-xs sm:text-sm text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                    <td class="px-3 sm:px-6 py-2 sm:py-4 text-xs sm:text-sm text-gray-500 hidden sm:table-cell">{{ $product->productType?->name ?? '-' }}</td>
                                    <td class="px-3 sm:px-6 py-2 sm:py-4 text-xs sm:text-sm text-gray-500 hidden lg:table-cell">{{ $product->is_seasonal ? '✓' : '-' }}</td>
                                    <td class="px-3 sm:px-6 py-2 sm:py-4">
                                        <span class="px-1.5 py-0.5 sm:px-2 sm:py-1 text-xs rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="px-3 sm:px-6 py-2 sm:py-4 space-x-1">
                                        <a href="{{ route('admin.products.edit', $product) }}" class="text-xs sm:text-sm text-blue-600 hover:text-blue-800 font-medium">Edit</a>
                                        <form method="POST" action="{{ route('admin.products.toggle', $product) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-xs sm:text-sm {{ $product->is_active ? 'text-red-600 hover:text-red-800' : 'text-green-600 hover:text-green-800' }}">
                                                {{ $product->is_active ? 'Deact' : 'Act' }}
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.products.delete', $product) }}" class="inline" onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs sm:text-sm text-red-600 hover:text-red-800 font-medium">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
