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
        <h2 class="font-semibold text-lg sm:text-xl text-white leading-tight">Product Types</h2>
    </x-slot>

    <!-- Breadcrumb Navigation -->
    <x-breadcrumb :items="[
        ['url' => route('admin.dashboard'), 'label' => 'Admin'],
        ['url' => route('admin.product-types'), 'label' => 'Product Types']
    ]" />

    <div class="py-2 sm:py-6" style="background-color: #242f6d;">
        <div class="max-w-full sm:max-w-4xl mx-auto px-2 sm:px-4 lg:px-8">
            @if(session('success'))
                <div class="mb-2 sm:mb-4 p-2 sm:p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg text-xs sm:text-sm">{{ session('success') }}</div>
            @endif
            
            @if(session('error'))
                <div class="mb-2 sm:mb-4 p-2 sm:p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg text-xs sm:text-sm">{{ session('error') }}</div>
            @endif

            {{-- Add Product Type Form --}}
            <div class="bg-white rounded-lg sm:rounded-xl shadow-lg p-3 sm:p-6 mb-3 sm:mb-6">
                <h3 class="text-sm sm:text-lg font-semibold text-gray-900 mb-2 sm:mb-4">Add New Product Type</h3>
                <form method="POST" action="{{ route('admin.product-types.store') }}" class="flex gap-2">
                    @csrf
                    <div class="flex-1">
                        <input type="text" name="name" placeholder="e.g., cone, cup, bowl" required class="w-full px-2 py-1 sm:px-3 sm:py-2 border border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-xs sm:text-base">
                        @error('name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 sm:px-4 py-1 sm:py-2 rounded-lg text-xs sm:text-sm font-medium transition-colors whitespace-nowrap">Add</button>
                </form>
            </div>

            {{-- Product Types List --}}
            <div class="bg-white rounded-lg sm:rounded-xl shadow-lg overflow-hidden">
                {{-- Mobile Card View --}}
                <div class="md:hidden space-y-2 p-2 sm:p-4">
                    @forelse($productTypes as $type)
                        <div class="p-2 bg-gray-50 rounded-lg border border-gray-200 space-y-2">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="font-semibold text-xs sm:text-sm text-gray-900">{{ $type->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $type->products_count }} products</p>
                                </div>
                            </div>
                            <div class="pt-1 border-t border-gray-200 flex gap-2">
                                <button onclick="editProductType('{{ $type->id }}', '{{ $type->name }}')" class="text-xs text-blue-600 hover:text-blue-800 font-medium">Edit</button>
                                @if($type->products_count == 0)
                                    <form method="POST" action="{{ route('admin.product-types.delete', $type) }}" class="inline" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs text-red-600 hover:text-red-800 font-medium">Delete</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-xs text-gray-500 py-4">No product types found</p>
                    @endforelse
                </div>

                {{-- Desktop Table View --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Products Count</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($productTypes as $type)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $type->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $type->products_count }}</td>
                                    <td class="px-6 py-4 text-sm space-x-3">
                                        <button onclick="editProductType('{{ $type->id }}', '{{ $type->name }}')" class="text-blue-600 hover:text-blue-800 font-medium">Edit</button>
                                        @if($type->products_count == 0)
                                            <form method="POST" action="{{ route('admin.product-types.delete', $type) }}" class="inline" onsubmit="return confirm('Are you sure?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Delete</button>
                                            </form>
                                        @else
                                            <span class="text-gray-400 cursor-not-allowed">Delete</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">No product types found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-sm w-full mx-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Edit Product Type</h3>
            <form method="POST" id="editForm" class="space-y-4">
                @csrf
                @method('PATCH')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                    <input type="text" name="name" id="editName" required class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">Update</button>
                    <button type="button" onclick="closeEditModal()" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-900 px-4 py-2 rounded-lg font-medium transition-colors">Cancel</button>
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
