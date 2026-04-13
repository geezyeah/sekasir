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
        <h2 class="font-semibold text-lg sm:text-xl text-white leading-tight">{{ __('admin.products_title') }}</h2>
    </x-slot>

    <!-- Breadcrumb Navigation -->
    <x-breadcrumb :items="[
        ['url' => route('admin.dashboard'), 'label' => 'Admin'],
        ['url' => route('admin.products'), 'label' => 'Products']
    ]" />

    <div class="py-2 sm:py-6" style="background-color: #242f6d;">
        <div class="max-w-full sm:max-w-7xl mx-auto px-2 sm:px-4 lg:px-8">
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-800 rounded-lg text-sm flex items-center gap-3 shadow-sm">
                    <i class="fas fa-check-circle text-lg"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            {{-- Add Product Form --}}
            <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="fas fa-plus-circle text-indigo-600"></i>
                    {{ __('admin.add_new_product') }}
                </h3>
                <form id="addProductForm" method="POST" action="{{ route('admin.products.store') }}" class="space-y-4">
                    @csrf
                    <!-- Hidden field for idempotency key -->
                    <input type="hidden" name="idempotency_key" id="idempotency_key" value="">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-800 mb-2">Shop</label>
                            <select name="shop_id" required class="w-full px-3 py-2.5 border-2 border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-sm transition-colors">
                                @foreach($shops as $shop)
                                    <option value="{{ $shop->id }}">{{ $shop->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-800 mb-2">{{ __('admin.product_name') }}</label>
                            <input type="text" name="name" required placeholder="e.g., Chocolate Ice Cream" class="w-full px-3 py-2.5 border-2 border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-sm transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-800 mb-2">{{ __('admin.price_idr') }}</label>
                            <input type="number" name="price" required min="0" placeholder="e.g., 15000" class="w-full px-3 py-2.5 border-2 border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-sm transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-800 mb-2">{{ __('admin.product_type') }}</label>
                            <select name="product_type_id" class="w-full px-3 py-2.5 border-2 border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-sm transition-colors">
                                <option value="">-- Select Type --</option>
                                @foreach($productTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-center pt-6">
                            <input type="checkbox" name="is_seasonal" value="1" id="is_seasonal" class="rounded border-2 border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 w-5 h-5">
                            <label for="is_seasonal" class="text-sm text-gray-800 ml-3 font-medium cursor-pointer">{{ __('admin.mark_as_seasonal') }}</label>
                        </div>
                        <div class="pt-6">
                            <button type="submit" id="addProductBtn" class="w-full bg-indigo-600 hover:bg-indigo-700 disabled:bg-gray-400 disabled:cursor-not-allowed text-white px-4 py-2.5 rounded-lg text-sm font-semibold shadow-md transition-all hover:shadow-lg transform hover:scale-105">
                                <i class="fas fa-plus mr-2"></i><span id="addProductBtnText">{{ __('admin.add_product') }}</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Product List --}}
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                {{-- Mobile Card View --}}
                <div class="md:hidden">
                    @forelse($products as $product)
                        <div class="border-b border-gray-200 last:border-b-0 p-4 hover:bg-gray-50 transition-colors {{ !$product->is_active ? 'opacity-60 bg-gray-50' : '' }}">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <h4 class="font-bold text-base text-gray-900 mb-1">{{ $product->name }}</h4>
                                    <p class="text-xs text-gray-500 flex items-center gap-1.5">
                                        <i class="fas {{ $product->shop->name === 'Ice Lepen' ? 'fa-ice-cream' : 'fa-bowl-food' }}" style="color: {{ $product->shop->name === 'Ice Lepen' ? '#c41e3a' : '#f39c12' }};"></i>
                                        {{ $product->shop->name }}
                                    </p>
                                </div>
                                <span class="px-3 py-1 text-xs rounded-full font-semibold {{ $product->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $product->is_active ? '✓ Active' : '✕ Inactive' }}
                                </span>
                            </div>
                            
                            <div class="space-y-2 bg-gray-50 rounded-lg p-3 mb-3">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600 font-medium">Price:</span>
                                    <span class="text-gray-900 font-semibold">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                </div>
                                @if($product->productType?->name)
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600 font-medium">Type:</span>
                                    <span class="text-gray-900 font-semibold">{{ $product->productType->name }}</span>
                                </div>
                                @endif
                                @if($product->is_seasonal)
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600 font-medium">Seasonal:</span>
                                    <i class="fas fa-check text-green-600"></i>
                                </div>
                                @endif
                            </div>
                            
                            <div class="flex gap-2">
                                <a href="{{ route('admin.products.edit', $product) }}" class="flex-1 text-center text-sm font-semibold text-blue-600 hover:text-blue-700 py-2 px-3 rounded-lg hover:bg-blue-50 transition-colors">
                                    <i class="fas fa-edit mr-1"></i>Edit
                                </a>
                                <form method="POST" action="{{ route('admin.products.toggle', $product) }}" class="flex-1">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="w-full text-sm font-semibold py-2 px-3 rounded-lg transition-colors {{ $product->is_active ? 'text-red-600 hover:bg-red-50 hover:text-red-700' : 'text-green-600 hover:bg-green-50 hover:text-green-700' }}">
                                        <i class="fas {{ $product->is_active ? 'fa-times' : 'fa-check' }} mr-1"></i>{{ $product->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.products.delete', $product) }}" class="flex-1" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full text-sm font-semibold text-red-600 hover:text-red-700 py-2 px-3 rounded-lg hover:bg-red-50 transition-colors">
                                        <i class="fas fa-trash mr-1"></i>Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center">
                            <p class="text-gray-500 text-sm"><i class="fas fa-box-open mb-2 block text-3xl opacity-30"></i>{{ __('admin.no_products_found') }}</p>
                        </div>
                    @endforelse
                </div>

                {{-- Desktop Table View --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50 border-b-2 border-gray-300">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wide">Shop</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wide">Product Name</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wide">Price</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wide">Type</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wide">Seasonal</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wide">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wide">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($products as $product)
                                <tr class="hover:bg-indigo-50 transition-colors {{ !$product->is_active ? 'opacity-60 bg-gray-50' : '' }}">
                                    <td class="px-4 py-3">
                                        <span class="flex items-center gap-2 text-sm font-semibold text-gray-900">
                                            <i class="fas {{ $product->shop->name === 'Ice Lepen' ? 'fa-ice-cream' : 'fa-bowl-food' }}" style="color: {{ $product->shop->name === 'Ice Lepen' ? '#c41e3a' : '#f39c12' }};"></i>
                                            {{ $product->shop->name }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm font-semibold text-gray-900">{{ $product->name }}</td>
                                    <td class="px-4 py-3 text-sm font-bold text-indigo-600">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $product->productType?->name ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        @if($product->is_seasonal)
                                            <span class="px-2.5 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold flex items-center gap-1 w-fit">
                                                <i class="fas fa-snowflake"></i> Yes
                                            </span>
                                        @else
                                            <span class="text-gray-500">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="px-3 py-1 text-xs rounded-full font-semibold {{ $product->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                            {{ $product->is_active ? '✓ Active' : '✕ Inactive' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex gap-2">
                                            <a href="{{ route('admin.products.edit', $product) }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700 px-2 py-1 rounded hover:bg-blue-50 transition-colors">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <form method="POST" action="{{ route('admin.products.toggle', $product) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-sm font-semibold px-2 py-1 rounded transition-colors {{ $product->is_active ? 'text-red-600 hover:text-red-700 hover:bg-red-50' : 'text-green-600 hover:text-green-700 hover:bg-green-50' }}">
                                                    <i class="fas {{ $product->is_active ? 'fa-times' : 'fa-check' }}"></i>
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.products.delete', $product) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-sm font-semibold text-red-600 hover:text-red-700 px-2 py-1 rounded hover:bg-red-50 transition-colors">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                        <i class="fas fa-box-open text-3xl mb-2 block opacity-30"></i>
                                        <span class="text-sm">No products found</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        let isSubmitting = false;

        function preventDuplicateSubmit(e) {
            e.preventDefault();
            
            // Prevent duplicate submission
            if (isSubmitting) {
                return false;
            }

            // Generate idempotency key
            const idempotencyKey = `product_${Date.now()}_${Math.random().toString(36).substr(2, 9)}`;
            document.getElementById('idempotency_key').value = idempotencyKey;

            // Disable button and show loading state
            const btn = document.getElementById('addProductBtn');
            const btnText = document.getElementById('addProductBtnText');
            
            isSubmitting = true;
            btn.disabled = true;
            btnText.textContent = 'Adding...';

            // Submit form after setting token
            setTimeout(() => {
                document.getElementById('addProductForm').submit();
            }, 100);

            // Re-enable after 3 seconds (in case of error)
            setTimeout(() => {
                if (isSubmitting) {
                    isSubmitting = false;
                    btn.disabled = false;
                    btnText.textContent = 'Add';
                }
            }, 3000);
        }

        // Bind form submission
        document.getElementById('addProductForm').addEventListener('submit', preventDuplicateSubmit);
    </script>
    @endpush
</x-app-layout>
