<x-layout>

    @php
    $product = $product;
    $attributes = $product->attributes;
    $price= $product->price;
    @endphp

    <section class="bg-white py-8 antialiased md:py-16" x-data="productPage({{ $product->id }})">
        <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">

            <!-- Breadcrumbs z gradientem -->
            <div class="mb-8">
                <ol class="flex items-center w-full text-sm font-medium text-center text-gray-500 sm:text-base">
                    <li class="flex md:w-full items-center" :class="{'text-custom-blue': step === 1, 'text-gray-500': step !== 1}">
                        <span class="flex items-center justify-center w-5 h-5 mr-2 text-xs border rounded-full shrink-0" :class="{'border-custom-blue': step === 1, 'border-gray-500': step !== 1}">
                            1
                        </span>
                        Wybór <span class="hidden sm:inline-flex sm:ml-2">produktu</span>
                        <span class="flex-auto border-t border-gray-200 ml-2 hidden sm:inline-flex"></span>
                    </li>
                    <li class="flex md:w-full items-center" :class="{'text-custom-blue': step === 2, 'text-gray-500': step !== 2}">
                        <span class="flex items-center justify-center w-5 h-5 mr-2 text-xs border rounded-full shrink-0" :class="{'border-custom-blue': step === 2, 'border-gray-500': step !== 2}">
                            2
                        </span>
                        Koszyk
                        <span class="flex-auto border-t border-gray-200 ml-2 hidden sm:inline-flex"></span>
                    </li>
                    <li class="flex items-center" :class="{'text-custom-blue': step === 3, 'text-gray-500': step !== 3}">
                        <span class="flex items-center justify-center w-5 h-5 mr-2 text-xs border rounded-full shrink-0" :class="{'border-custom-blue': step === 3, 'border-gray-500': step !== 3}">
                            3
                        </span>
                        Podsumowanie
                    </li>
                </ol>
            </div>
            
            <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl">{{$product->view}}</h1>

            <div class="mt-6 sm:mt-8 lg:flex lg:gap-8">
                <!-- Formularz -->
                <div class="lg:w-1/3">
                    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                        <form class="space-y-4" @submit.prevent="editingIndex === null ? addToCart() : updateCartItem()">
                            @foreach ($attributes->sortBy('order') as $attribute)
                            @if ($attribute->type === 'text')
                            <div>
                                <label for="{{$attribute->name}}" class="mb-2 block text-sm font-medium text-gray-900">{{$attribute->display_name .' ('. $attribute->unit .')'}}</label>
                                <input type="text" id="{{$attribute->name}}" x-model="product.{{$attribute->name}}" 
                                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-custom-blue focus:ring-custom-blue" 
                                    placeholder="" required />
                            </div>
                            @endif
                            
                            @if ($attribute->type === 'number')
                            <div>
                                <label for="{{$attribute->name}}" class="mb-2 block text-sm font-medium text-gray-900">{{$attribute->display_name .' ('. $attribute->unit .')'}} </label>
                                <input type="number" id="{{$attribute->name}}" step="0.1" min="{{$attribute->min}}" max="{{$attribute->max}}" x-model="product.{{$attribute->name}}" 
                                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-custom-blue focus:ring-custom-blue" 
                                    placeholder="{{'min: '. $attribute->min . ' max: '. $attribute->max}}" required />
                            </div>
                            @endif

                            @if ($attribute->type === 'select')
                            <div>
                                <label for="{{$attribute->name}}" class="block mb-2 text-sm font-medium text-gray-900">{{$attribute->display_name }}</label>
                                <select id="{{$attribute->name}}" name="{{$attribute->name}}" required x-model="product.{{$attribute->name}}" 
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-custom-blue focus:border-custom-blue block w-full p-2.5">
                                    <option value="">Wybierz kolor</option>
                                    @foreach($attribute->items as $item)
                                    <option value="{{$item->value}}">{{$item->value}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                            
                            @if ($attribute->type === 'radio')
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-900">{{$attribute->display_name }}</label>
                                <div class="space-y-2">
                                    @foreach($attribute->items as $item)
                                    <div class="flex items-center">
                                        <input type="radio" id="{{$attribute->name}}_{{$item->value}}" required name="{{$attribute->name}}" value="{{$item->value}}" x-model="product.{{$attribute->name}}" 
                                            class="w-4 h-4 text-custom-blue bg-gray-100 border-gray-300 focus:ring-custom-blue">
                                        <label for="{{$attribute->name}}_{{$item->value}}" class="ml-2 text-sm font-medium text-gray-900">{{$item->value}}</label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                            
                            @if ($attribute->type === 'color')
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-900">{{$attribute->display_name }}</label>
                                <div class="flex flex-wrap gap-4">
                                    @foreach($attribute->items as $item)
                                    <div class="flex flex-col items-center">
                                        <div class="relative">
                                            <input type="radio" id="{{$attribute->name}}_{{$item->value}}"
                                                required name="{{$attribute->name}}" value="{{$item->value}}"
                                                x-model="product.{{$attribute->name}}"
                                                class="absolute opacity-0 w-full h-full cursor-pointer" />
                                            <div class="w-16 h-16 border rounded-md overflow-hidden"
                                                :class="{'ring-2 ring-custom-blue': product.{{$attribute->name}} === '{{$item->value}}'}">
                                                <img src="{{ asset('storage/' . $item->image) }}" alt="{{$item->value}}" class="w-full h-full object-cover">
                                            </div>
                                        </div>
                                        <label for="{{$attribute->name}}_{{$item->value}}"
                                            class="mt-2 text-sm font-medium text-gray-900 text-center">
                                            {{$item->value}}
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                            @endforeach
                            
                            <div>
                                <label for="quantity" class="mb-2 block text-sm font-medium text-gray-900">Ilość</label>
                                <input type="number" id="quantity" min="1" x-model="product.quantity" 
                                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-custom-blue focus:ring-custom-blue" 
                                    placeholder="" required />
                            </div>
                            
                            <button type="submit" class="w-full px-5 py-3 bg-gradient-to-r from-custom-blue to-custom-gran text-white font-medium rounded-lg text-sm shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                                <span x-text="editingIndex === null ? 'Dodaj do koszyka' : 'Zaktualizuj'"></span>
                            </button>
                            <template x-if="editingIndex !== null">
                                <button type="button" @click="cancelEdit" class="w-full px-5 py-3 bg-gray-300 text-gray-700 font-medium rounded-lg text-sm shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                                    Anuluj edycję
                                </button>
                            </template>
                        </form>
                    </div>
                </div>

                <!-- Koszyk -->
                <div class="mt-8 lg:mt-0 lg:w-1/3">
                    <div class="space-y-4">
                        <template x-if="cart.length === 0">
                            <div class="bg-gray-50 rounded-xl p-6 text-center">
                                <svg class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <p class="mt-2 text-gray-600">Koszyk jest pusty</p>
                            </div>
                        </template>
                        
                        <template x-if="cart.length > 0">
                            <div class="space-y-4">
                                <div class="flex justify-end mb-4">
                                    <button @click="clearCurrentCart" class="text-red-600 hover:text-red-800 flex items-center gap-2 px-4 py-2 rounded-lg border border-red-600 hover:border-red-800 text-sm transition-colors duration-200">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Wyczyść koszyk
                                    </button>
                                </div>
                                
                                <template x-for="(item, index) in cart" :key="index">
                                    <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm hover:shadow-md transition-shadow duration-200">
                                        <p class="text-base font-medium text-gray-900" x-text="getProductName(item)"></p>
                                        <div class="mt-2 flex items-center justify-between">
                                            <div class="flex items-center">
                                                <button @click="decrementQuantity(index)" class="text-gray-500 hover:text-custom-blue focus:outline-none transition-colors duration-200">
                                                    <svg class="h-5 w-5" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                </button>
                                                <span class="text-gray-700 mx-2" x-text="item.quantity"></span>
                                                <button @click="incrementQuantity(index)" class="text-gray-500 hover:text-custom-blue focus:outline-none transition-colors duration-200">
                                                    <svg class="h-5 w-5" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                             <div class="flex items-center gap-2">
                                                <button @click="editItem(index)" class="text-gray-500 hover:text-custom-blue transition-colors duration-200">
                                                    <svg class="h-5 w-5" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </button>
                                                <button @click="removeFromCart(index)" class="text-red-500 hover:text-red-600 transition-colors duration-200">
                                                    <svg class="h-5 w-5" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Podsumowanie -->
                <div class="mt-8 lg:mt-0 lg:w-1/3">
                    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                        <div class="flex items-center mb-4">
                            <div class="bg-gradient-to-r from-custom-blue to-custom-gran p-2 rounded-full">
                                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" />
                                </svg>
                            </div>
                            <h2 class="text-xl font-semibold text-gray-900 ml-3">Podsumowanie</h2>
                        </div>
                        
                        <template x-if="cart.length > 0">
                            <div class="mt-4 space-y-4">
                                <div class="mt-4 space-y-4">
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium text-gray-700">Podsumowanie do skopiowania:</label>
                                        <div class="relative">
                                            <textarea x-ref="summaryText" x-text="getCartSummaryText()"
                                                class="w-full h-24 p-3 pr-10 text-sm border border-gray-300 rounded-lg bg-gray-50 resize-none focus:ring-custom-blue focus:border-custom-blue"
                                                readonly></textarea>
                                            <button @click="copySummary"
                                                class="absolute right-2 top-2 p-1.5 bg-white rounded-md text-gray-500 hover:text-custom-blue transition-colors duration-200"
                                                title="Kopiuj do schowka">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <dl class="flex items-center justify-between">
                                    <dt class="text-base font-normal text-gray-500">Suma jednostek:</dt>
                                    <dd class="text-base font-medium text-gray-900" x-text="`${totalRunningMeters.toFixed(0)}`"></dd>
                                </dl>
                                
                                <dl class="flex items-center justify-between border-t border-gray-200 pt-2">
                                    <dt class="text-base font-bold text-gray-900">Razem:</dt>
                                    <dd class="text-base font-bold text-custom-blue" x-text="`${totalPrice.toFixed(2)} zł`"></dd>
                                </dl>
                            </div>
                        </template>

                        <template x-if="cart.length > 0">
                            <form action="{{ route('order.store') }}" method="POST" @submit="onSubmitOrder" class="mt-6">
                                @csrf
                                <input type="hidden" name="cart" :value="JSON.stringify(cart)">
                                <input type="hidden" name="totalRunningMeters" :value="totalRunningMeters.toFixed(2)">
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="text" name="honeypot" style="display:none">
                                
                                <button type="submit" class=" w-full px-5 py-3 bg-gradient-to-r from-custom-blue to-custom-gran text-white font-medium rounded-lg text-sm shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                                    Wyślij formularz
                                </button>
                            </form>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        function productPage(productId) {
            return {
                product: {
                    productId: productId
                },
                attributes: @json($attributes),
                cart: [],
                formula: '{!! $product->formula !!}',
                price: '{!! $price !!}',
                step: 1,
                editingIndex: null,

                init() {
                    // Load cart for specific product
                    const allCarts = JSON.parse(localStorage.getItem('productCarts') || '{}');
                    this.cart = allCarts[this.product.productId] || [];
                    this.updateStep();
                },

                updateStep() {
                    this.step = this.cart.length > 0 ? 2 : 1;
                },

                clearCurrentCart() {
                    if (confirm('Czy na pewno chcesz wyczyścić koszyk dla tego produktu? Ta operacja jest nieodwracalna.')) {
                        const allCarts = JSON.parse(localStorage.getItem('productCarts') || '{}');
                        allCarts[this.product.productId] = [];
                        localStorage.setItem('productCarts', JSON.stringify(allCarts));
                        this.cart = [];
                        this.updateStep();
                    }
                },

                getCartSummaryText() {
                    return this.cart.map(item => {
                        const sortedAttributes = [...this.attributes].sort((a, b) => a.order - b.order);
                        const pairs = sortedAttributes
                            .sort((a, b) => a.order - b.order)
                            .map(attribute => {
                                const value = item[attribute.name];
                                if (value === '' || value == null) return null;

                                const shortName = attribute.short_name;
                                const unit = attribute.unit ? ` ${attribute.unit}` : '';
                                return `${shortName}: ${value}${unit}`;
                            })
                            .filter(pair => pair !== null);

                        return `${pairs.join(', ')} x ${item.quantity} szt`;
                    }).join('\n');
                },

                copySummary() {
                    const text = this.$refs.summaryText.value;

                    // Próba użycia nowoczesnego Clipboard API
                    if (navigator.clipboard && window.isSecureContext) {
                        navigator.clipboard.writeText(text).then(() => {
                            alert('Skopiowano do schowka!');
                        }).catch(err => {
                            fallbackCopyToClipboard(text);
                        });
                    } else {
                        // Fallback dla starszych przeglądarek
                        try {
                            // Zaznacz tekst
                            this.$refs.summaryText.select();
                            this.$refs.summaryText.setSelectionRange(0, 99999); // Dla urządzeń mobilnych

                            // Skopiuj do schowka
                            document.execCommand('copy');

                            // Odznacz tekst
                            window.getSelection().removeAllRanges();

                            alert('Skopiowano do schowka!');
                        } catch (err) {
                            console.error('Błąd podczas kopiowania:', err);
                            alert('Nie udało się skopiować tekstu. Spróbuj zaznaczyć i skopiować ręcznie.');
                        }
                    }
                },

                getProductName(item) {
                    // First, get all attributes and their values
                    const attributePairs = this.attributes
                        // Sort attributes by order
                        .sort((a, b) => a.order - b.order)
                        // Map through sorted attributes
                        .map(attribute => {
                            const value = item[attribute.name];
                            // Skip if the attribute has no value or is not in the item
                            if (value === '' || value == null) return null;

                            const shortName = attribute.short_name;
                            const unit = attribute.unit ? ` ${attribute.unit}` : '';
                            return `${shortName}: ${value}${unit}`;
                        })
                        // Remove null values
                        .filter(pair => pair !== null);

                    return attributePairs.join(', ');
                },

                get totalRunningMeters() {
                    const total = this.cart.reduce((total, item) => {
                        return total + this.calculateRunningMeters(item, false);
                    }, 0);
                    return Math.ceil(total);
                },

                get totalPrice() {
                    const price = this.totalRunningMeters * this.price;
                    return parseFloat(price.toFixed(2));
                },

                calculateRunningMeters(item, round = true) {
                    let width = parseFloat(item.width);
                    let height = parseFloat(item.height);
                    let depth = parseFloat(item.depth);
                    let quantity = parseInt(item.quantity);
                    const meters = eval(this.formula);
                    return round ? meters.toFixed(2) : meters;
                },

                addToCart() {
                    this.cart.push({
                        ...this.product
                    });
                    this.saveCart();
                    this.resetForm();
                    this.updateStep();
                },

                editItem(index) {
                    this.editingIndex = index;
                    const itemToEdit = this.cart[index];
                    
                    // Reset product object
                    this.product = {
                        productId: this.product.productId,
                        quantity: itemToEdit.quantity
                    };
                    
                    // Copy all attributes from the item to edit
                    this.attributes.forEach(attr => {
                        if (itemToEdit.hasOwnProperty(attr.name)) {
                            this.product[attr.name] = itemToEdit[attr.name];
                        }
                    });
                    
                    // Scroll to form
                    this.$nextTick(() => {
                        document.querySelector('.lg\\:w-1\\/3').scrollIntoView({ behavior: 'smooth' });
                    });
                },

                  updateCartItem() {
                    if (this.editingIndex !== null) {
                        this.cart[this.editingIndex] = {
                            ...this.product
                        };
                        this.saveCart();
                        this.resetForm();
                        this.editingIndex = null;
                    }
                },
                
                cancelEdit() {
                    this.editingIndex = null;
                    this.resetForm();
                },

                removeFromCart(index) {
                    if (this.editingIndex === index) {
                        this.cancelEdit();
                    }
                    this.cart.splice(index, 1);
                    this.saveCart();
                    this.updateStep();
                },

                incrementQuantity(index) {
                    this.cart[index].quantity++;
                    this.saveCart();
                },

                decrementQuantity(index) {
                    if (this.cart[index].quantity > 1) {
                        this.cart[index].quantity--;
                        this.saveCart();
                    }
                },

                saveCart() {
                    const allCarts = JSON.parse(localStorage.getItem('productCarts') || '{}');
                    allCarts[this.product.productId] = this.cart;
                    localStorage.setItem('productCarts', JSON.stringify(allCarts));
                },

                resetForm() {
                    this.product = {
                        productId: this.product.productId
                    };
                },

                clearCart() {
                    this.cart = [];
                    this.saveCart();
                    this.updateStep();
                },

                onSubmitOrder() {
                    this.step = 3;
                    this.$nextTick(() => {
                        this.clearCart();
                    });
                }
            }
        }
    </script>
</x-layout>