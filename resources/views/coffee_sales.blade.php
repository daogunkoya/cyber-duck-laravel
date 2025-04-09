<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Coffee Sales
        </h2>
    </x-slot>

    <div class="py-12" x-data="coffeeSales()" x-init="init()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Form with all elements in one row -->
                    <div class="flex items-end gap-4 mb-8 ">
                        <!-- Product Select -->
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700">Product</label>
                            <select x-model="form.product_id" 
                                    @change="calculateSellingPrice()"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                    required>
                                <template x-for="product in coffeeProducts" :key="product.coffeeProductId">
                                    <option :value="product.coffeeProductId" :data-profit-margin="product.profitMargin" x-text="product.name"></option>
                                </template>
                            </select>
                        </div>
                        
                        <!-- Quantity Input -->
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700">Quantity</label>
                            <input type="number" 
                                   x-model="form.quantity"
                                   @input="calculateSellingPrice()"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                   required min="1" value="1">
                        </div>
                        
                        <!-- Unit Cost Input -->
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700">Unit Cost (£)</label>
                            <input type="number" step="0.01" 
                                   x-model="form.unit_cost"
                                   @input="calculateSellingPrice()"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                   required min="0.01" value="0.00">
                        </div>
                        
                        <!-- Selling Price Display -->
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-gray-700">Selling Price (£)</label>
                            <div class="mt-1 block w-full p-2 rounded-md bg-gray-100">
                                <span x-text="sellingPrice.toFixed(2)">0.00</span>
                            </div>
                        </div>
                        
                        <!-- Record Sale Button -->
                        <div class="flex-1 pb-[20px]">
                            <button @click="recordSale()"
                                    :disabled="isSubmitting"
                                    class="w-full h-[42px] inline-flex justify-center items-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-gray-800 bg-indigo-200 hover:bg-indigo-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                    x-text="isSubmitting ? 'Processing...' : 'Record Sale'">
                            </button>
                        </div>
                    </div>

                    <!-- Sales Table -->
                    <div>
                        <h3 class=" mt-9 text-lg font-medium text-gray-900 mb-4">Previous Sales</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price (£)</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Selling Price (£)</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sold At</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <template x-for="sale in sales" :key="sale.id">
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="sale.product_name"></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="sale.quantity"></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="sale.unit_cost"></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="sale.selling_price"></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="new Date(sale.created_at).toLocaleString()"></td>
                                        </tr>
                                    </template>
                                    <tr x-show="sales.length === 0">
                                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No sales recorded yet.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function coffeeSales() {
            return {
                coffeeProducts: @json($coffeeProducts),
                sales: @json($sales),
                form: {
                    product_id: '',
                    quantity: 1,
                    unit_cost: 0.00
                },
                sellingPrice: 0.00,
                isSubmitting: false,
                
                init() {
                    if (this.coffeeProducts.length > 0) {
                        this.form.product_id = this.coffeeProducts[0].coffeeProductId;
                    }
                    this.calculateSellingPrice();
                },
                
                calculateSellingPrice() {
                   
                    const profitMargin = {{ config('coffee.profit_margin') }};
                    const quantity = parseFloat(this.form.quantity) || 0;
                    const unitCost = parseFloat(this.form.unit_cost) || 0;
                    const shippingCost = {{ config('coffee.shipping_cost') }};
                    
                    const cost = quantity * unitCost;
                    this.sellingPrice = (cost / (1 - profitMargin)) + shippingCost;
                },
                
                async recordSale() {
                    this.isSubmitting = true;
                    
                    try {
                        const response = await fetch("{{ route('sales.store') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({
                                coffee_product_id: this.form.product_id,
                                quantity: this.form.quantity,
                                unit_cost: this.form.unit_cost,
                                selling_price: parseFloat(this.sellingPrice.toFixed(2))
                            })
                        });
                        
                        const data = await response.json();
                        
                        if (data.success) {
                            this.sales.unshift({
                                id: data.sale.id,
                                coffee_product: {
                                    name: data.product_name
                                },
                                quantity: data.sale.quantity,
                                unit_cost: data.sale.unit_cost,
                                selling_price: data.sale.selling_price,
                                created_at: data.sale.created_at
                            });
                            
                            this.form.quantity = 1;
                            this.form.unit_cost = 0.00;
                            this.calculateSellingPrice();
                        }
                    } catch (error) {
                        console.error('Error:', error);
                    } finally {
                        this.isSubmitting = false;
                    }
                }
            };
        }
    </script>
</x-app-layout>