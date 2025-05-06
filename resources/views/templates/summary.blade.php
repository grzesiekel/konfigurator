<x-layout>
    @php
    $order = $order;
    $cart = $order->cart;
    $product = $order->product;
    $attributes = $order->product->attributes;
    @endphp



    <div class="max-w-4xl mx-auto p-6 bg-white rounded-2xl shadow-xl my-8 transform transition-all duration-300 hover:shadow-2xl" x-data="summaryData()">
        <!-- Nagłówek z ikoną -->
        <div class="flex items-center mb-6">
            <div class="bg-gradient-to-r from-custom-blue to-custom-gran p-3 rounded-full shadow-md">
                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-800 ml-3">Zamówienie nr {{$order->number}}</h2>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Lewa kolumna - szczegóły zamówienia -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Produkty -->
                <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4 flex items-center">
                        <svg class="h-5 w-5 text-custom-blue mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        Produkty
                    </h3>
                    <div class="space-y-3">
                        <template x-for="(item,index) in cart" :key="index">
                            <div class="flex justify-between items-center">

                                <span class="text-gray-600" x-text="getProductName(item)"></span>
                                <span class="font-medium" x-text="item.quantity + ' szt'"></span>
                            </div>
                        </template>

                    </div>
                </div>

                <!-- Podsumowanie płatności -->
                <div class="bg-gradient-to-br from-custom-blue/10 to-custom-gran/10 rounded-xl p-5 border border-custom-blue/20">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4 flex items-center">
                        <svg class="h-5 w-5 text-custom-gran mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Podsumowanie płatności
                    </h3>
                    <div class="space-y-3">

                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Ilość jednostek, które należy zakupić:</span>
                            <span class="font-medium" x-text="`${totalRunningMeters.toFixed(0)}`"></span>
                        </div>
                        <div class="flex justify-between items-center pt-3 border-t border-gray-200">
                            <span class="text-gray-600">Dostawa:</span>
                            <span class="font-medium text-green-600">GRATIS</span>
                        </div>
                        <div class="flex justify-between items-center pt-4 border-t border-gray-300">
                            <span class="text-lg font-bold text-gray-800">Do zapłaty:</span>
                            <span class="text-xl font-bold text-custom-blue" x-text="`${totalPrice.toFixed(2)} zł`">307.49 zł</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Prawa kolumna - kopiowanie zamówienia -->
            <div class="space-y-6">
                <!-- Metoda płatności -->
                <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4 flex items-center">
                    <svg class="h-5 w-5 text-custom-blue mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
            </svg>
                        Do skopiowania
                    </h3>
                    <div class="flex items-center space-x-3">
                        <!-- <div class="bg-custom-blue/10 p-2 rounded-lg">
                        <svg class="h-6 w-6 text-custom-blue" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-medium">Karta kredytowa</p>
                        <p class="text-sm text-gray-500">Zabezpieczona płatność online</p>
                    </div> -->
                        <div class="relative">
                            <textarea
                                x-ref="summaryText"
                                x-text="getCartSummaryText()"
                                class="w-full h-24 p-2 text-sm border rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                readonly></textarea>
                            <button
                                @click="copySummary"
                                class="absolute right-2 top-2 p-1 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300"
                                title="Kopiuj do schowka">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Jak dokonać zakupu -->
                <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
    <h3 class="text-lg font-semibold text-gray-700 mb-4 flex items-center">
        <svg class="h-5 w-5 text-custom-gran mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
        </svg>
        Jak dokonać zakupu
    </h3>
    <ul class="space-y-3">
        <li class="flex items-start">
            <svg class="h-5 w-5 text-custom-blue mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span class="text-gray-600">Przejdź na stronę produktu</span>
        </li>
        <li class="flex items-start">
            <svg class="h-5 w-5 text-custom-blue mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span class="text-gray-600" x-text="`Dodaj do koszyka ${totalRunningMeters.toFixed(0)} sztuk`"></span>
        </li>
        <li class="flex items-start">
            <svg class="h-5 w-5 text-custom-blue mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span class="text-gray-600">Przejdź do formularza dostawy</span>
        </li>
        <li class="flex items-start">
            <svg class="h-5 w-5 text-custom-blue mr-2 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span class="text-gray-600" x-text="`W uwagach do zamówienia wpisz kod: ${number}`"></span>
        </li>
    </ul>
</div>

                <!-- Przyciski akcji -->
                <div class="space-y-3">
                <a href="{{$order->product->site_url}}" target="_blank" 
   class="w-full px-6 py-3 bg-gradient-to-r from-custom-blue to-custom-gran text-white font-medium rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 flex items-center justify-center group">
    <svg class="h-5 w-5 mr-2 text-white group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
    </svg>
    <span class="group-hover:scale-105 transition-transform">Strona produktu</span>
</a>
                   
                    <a href="{{ url('/produkt/' . $order->product->slug) }}" class="w-full px-6 py-3 bg-white border border-custom-blue/30 text-custom-blue font-medium rounded-xl hover:bg-custom-blue/5 transition duration-150 flex items-center justify-center">
                        
                        <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Wróć do konfiguratora
                        
</a>
                  
                </div>
            </div>
        </div>
    </div>

    <script>
        function summaryData() {
            return {
                attributes: @json($attributes),
                cart: JSON.parse('{!! $cart !!}'),
                formula: '{!! $product->formula !!}',
                price: '{!! $product->price !!}',
                number:'{!! $order->number !!}',

                getProductName(item) {
                    // Get sorted attributes by order
                    const sortedAttributes = [...this.attributes].sort((a, b) => a.order - b.order);

                    const attributePairs = sortedAttributes.map(attribute => {
                        const value = item[attribute.name];
                        // Skip if the attribute has no value or is not in the item
                        if (value === '' || value == null) return null;

                        const shortName = attribute.short_name;
                        const unit = attribute.unit ? ` ${attribute.unit}` : '';
                        return `${shortName}: ${value}${unit}`;
                    }).filter(pair => pair !== null);

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
                    // const meters = ((parseFloat(item.width) + parseFloat(item.height)) * 2 / 100) * item.quantity;
                    console.log(this.formula);
                    const meters = eval(this.formula);

                    return round ? meters.toFixed(2) : meters;
                },
                getCartSummaryText() {
                    return this.cart.map(item => {
                        // Get sorted attributes by order
                        const sortedAttributes = [...this.attributes].sort((a, b) => a.order - b.order);

                        const pairs = sortedAttributes.map(attribute => {
                            const value = item[attribute.name];
                            if (value === '' || value == null || attribute.name === 'quantity' || attribute.name === 'productId') return null;

                            const shortName = attribute.short_name;
                            const unit = attribute.unit ? ` ${attribute.unit}` : '';
                            return `${shortName}: ${value}${unit}`;
                        }).filter(pair => pair !== null);

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
                }
            }
        }
    </script>
</x-layout>