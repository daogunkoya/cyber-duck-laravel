<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('New ☕️ Sales') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form id="coffee-sale-form" method="POST" action="{{ route('coffee-sales.store') }}">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Product Selection -->
                            <div>
                                <x-input-label for="coffee_product_id" :value="__('Coffee Product')" />
                                <select id="coffee_product_id" name="coffee_product_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">
                                            {{ $product->name }} ({{ $product->profit_margin_percentage }}% margin)
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('coffee_product_id')" class="mt-2" />
                            </div>

                            <!-- Quantity -->
                            <div>
                                <x-input-label for="quantity" :value="__('Quantity')" />
                                <x-text-input id="quantity" name="quantity" type="number" min="1" class="mt-1 block w-full" 
                                    value="{{ old('quantity', 1) }}" required />
                                <x-input-error :messages="$errors->get('quantity')" class="mt-2" />
                            </div>

                            <!-- Unit Cost -->
                            <div>
                                <x-input-label for="unit_cost" :value="__('Unit Cost (£)')" />
                                <x-text-input id="unit_cost" name="unit_cost" type="number" step="0.01" min="0.01" 
                                    class="mt-1 block w-full" value="{{ old('unit_cost') }}" required />
                                <x-input-error :messages="$errors->get('unit_cost')" class="mt-2" />
                            </div>

                            <!-- Results (will be populated via JavaScript) -->
                            <div class="md:col-span-2 bg-gray-50 p-4 rounded-lg">
                                <h3 class="text-lg font-medium text-gray-900">{{ __('Calculation Results') }}</h3>
                                <div class="mt-4 space-y-2">
                                    <p class="text-sm"><span class="font-medium">{{ __('Cost:') }}</span> <span id="cost-result">£0.00</span></p>
                                    <p class="text-sm"><span class="font-medium">{{ __('Selling Price:') }}</span> <span id="price-result">£0.00</span></p>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button type="button" id="calculate-btn">
                                {{ __('Calculate') }}
                            </x-primary-button>
                            <x-primary-button type="submit" class="ml-3" id="save-btn" disabled>
                                {{ __('Record Sale') }}
                            </x-primary-button>
                        </div>
                    </form>

                    <!-- Sales History -->
                    <div class="mt-12">
                        <h3 class="text-lg font-medium text-gray-900">{{ __('Recent Sales') }}</h3>
                        <div class="mt-4 overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Product') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Quantity') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Unit Cost') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Selling Price') }}</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Date') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($sales as $sale)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $sale->product->name }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $sale->quantity }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">£{{ number_format($sale->unit_cost, 2) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">£{{ number_format($sale->selling_price, 2) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">{{ __('No sales recorded yet.') }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('coffee-sale-form');
            const calculateBtn = document.getElementById('calculate-btn');
            const saveBtn = document.getElementById('save-btn');
            
            calculateBtn.addEventListener('click', async function() {
                const formData = new FormData(form);
                
                try {
                    const response = await fetch('{{ route("coffee-sales.calculate") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            coffee_product_id: formData.get('coffee_product_id'),
                            quantity: formData.get('quantity'),
                            unit_cost: formData.get('unit_cost')
                        })
                    });
                    
                    const data = await response.json();
                    
                    if (response.ok) {
                        document.getElementById('cost-result').textContent = '£' + data.data.cost.toFixed(2);
                        document.getElementById('price-result').textContent = '£' + data.data.selling_price.toFixed(2);
                        saveBtn.disabled = false;
                    } else {
                        alert(data.message || 'Calculation failed');
                    }
                } catch (error) {
                    alert('An error occurred during calculation');
                }
            });
        });
    </script>
    @endpush
</x-app-layout>