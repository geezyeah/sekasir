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
        <h2 class="font-semibold text-lg sm:text-xl text-white leading-tight">{{ __('admin.product_types_title') }}</h2>
    </x-slot>

    <!-- Breadcrumb Navigation -->
    <x-breadcrumb :items="[
        ['url' => route('admin.dashboard'), 'label' => 'Admin'],
        ['url' => route('admin.product-types'), 'label' => 'Product Types']
    ]" />

    <div class="py-2 sm:py-6" style="background-color: #242f6d;">
        <div class="max-w-full lg:max-w-7xl mx-auto px-2 sm:px-4 lg:px-8">
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-800 rounded-lg text-sm flex items-center gap-3 shadow-sm">
                    <i class="fas fa-check-circle text-lg"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            
            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-800 rounded-lg text-sm flex items-center gap-3 shadow-sm">
                    <i class="fas fa-exclamation-circle text-lg"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            {{-- Add Product Type Form --}}
            <div class="bg-white rounded-xl shadow-md p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                    <i class="fas fa-plus-circle text-indigo-600"></i>
                    {{ __('admin.add_new_type') }}
                </h3>
                <form method="POST" action="{{ route('admin.product-types.store') }}" class="space-y-4">
                    @csrf
                    <div class="flex gap-3">
                        <div class="flex-1">
                            <input type="text" name="name" placeholder="e.g., cone, cup, bowl" required class="w-full px-3 py-2.5 border-2 border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-sm transition-colors">
                            @error('name')
                                <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                                    <i class="fas fa-info-circle"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg text-sm font-semibold shadow-md transition-all hover:shadow-lg transform hover:scale-105 whitespace-nowrap">
                            <i class="fas fa-plus mr-2"></i>{{ __('admin.add_new_type') }}
                        </button>
                    </div>
                </form>
            </div>

            {{-- Product Types List --}}
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                {{-- Mobile Card View --}}
                <div class="md:hidden">
                    @forelse($productTypes as $type)
                        <div class="border-b border-gray-200 last:border-b-0 p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h4 class="font-bold text-base text-gray-900">{{ $type->name }}</h4>
                                    <p class="text-xs text-gray-500 mt-1">
                                        <i class="fas fa-boxes mr-1"></i>{{ $type->products_count }} {{ $type->products_count === 1 ? 'product' : 'products' }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <button onclick="editProductType('{{ $type->id }}', '{{ $type->name }}')" class="flex-1 text-center text-sm font-semibold text-blue-600 hover:text-blue-700 py-2 px-3 rounded-lg hover:bg-blue-50 transition-colors">
                                    <i class="fas fa-edit mr-1"></i>Edit
                                </button>
                                @if($type->products_count == 0)
                                    <form method="POST" action="{{ route('admin.product-types.delete', $type) }}" class="flex-1" onsubmit="return confirm('Are you sure you want to delete this product type?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full text-sm font-semibold text-red-600 hover:text-red-700 py-2 px-3 rounded-lg hover:bg-red-50 transition-colors">
                                            <i class="fas fa-trash mr-1"></i>Delete
                                        </button>
                                    </form>
                                @else
                                    <button disabled class="flex-1 text-sm font-semibold text-gray-400 py-2 px-3 rounded-lg cursor-not-allowed">
                                        <i class="fas fa-lock mr-1"></i>Delete
                                    </button>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center">
                            <p class="text-gray-500 text-sm"><i class="fas fa-tags mb-2 block text-3xl opacity-30"></i>{{ __('admin.no_types_found') }}</p>
                        </div>
                    @endforelse
                </div>

                {{-- Desktop Table View --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50 border-b-2 border-gray-300">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wide">Type Name</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wide">Products</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wide">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($productTypes as $type)
                                <tr class="hover:bg-indigo-50 transition-colors">
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $type->name }}</td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold">
                                            <i class="fas fa-box mr-1"></i>{{ $type->products_count }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex gap-2">
                                            <button onclick="editProductType('{{ $type->id }}', '{{ $type->name }}')" class="text-sm font-semibold text-blue-600 hover:text-blue-700 px-3 py-1.5 rounded hover:bg-blue-50 transition-colors">
                                                <i class="fas fa-edit mr-1"></i>Edit
                                            </button>
                                            @if($type->products_count == 0)
                                                <form method="POST" action="{{ route('admin.product-types.delete', $type) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this product type?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-sm font-semibold text-red-600 hover:text-red-700 px-3 py-1.5 rounded hover:bg-red-50 transition-colors">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <button disabled class="text-sm font-semibold text-gray-400 px-3 py-1.5 rounded cursor-not-allowed" title="Cannot delete - products exist">
                                                    <i class="fas fa-lock"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-8 text-center text-gray-500">
                                        <i class="fas fa-tags text-3xl mb-2 block opacity-30"></i>
                                        <span class="text-sm">No product types found</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl p-6 max-w-sm w-full shadow-2xl">
            <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                <i class="fas fa-edit text-indigo-600"></i>
                Edit Product Type
            </h3>
            <form method="POST" id="editForm" class="space-y-4">
                @csrf
                @method('PATCH')
                <div>
                    <label class="block text-sm font-semibold text-gray-800 mb-2">Type Name</label>
                    <input type="text" name="name" id="editName" required class="w-full px-3 py-2.5 border-2 border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 text-sm transition-colors">
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-lg font-semibold shadow-md transition-all hover:shadow-lg">
                        <i class="fas fa-check mr-2"></i>Update
                    </button>
                    <button type="button" onclick="closeEditModal()" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-900 px-4 py-2.5 rounded-lg font-semibold transition-colors">
                        <i class="fas fa-times mr-2"></i>Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function editProductType(id, name) {
            document.getElementById('editName').value = name;
            document.getElementById('editForm').action = `/admin/product-types/${id}`;
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        document.getElementById('editModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditModal();
            }
        });
    </script>
</x-app-layout>
