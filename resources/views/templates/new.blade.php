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
                        Wymiary <span class="hidden sm:inline-flex sm:ml-2">produktu</span>
                        <span class="flex-auto border-t border-gray-200 ml-2 hidden sm:inline-flex"></span>
                    </li>
                    <li class="flex md:w-full items-center" :class="{'text-custom-blue': step === 2, 'text-gray-500': step !== 2}">
                        <span class="flex items-center justify-center w-5 h-5 mr-2 text-xs border rounded-full shrink-0" :class="{'border-custom-blue': step === 2, 'border-gray-500': step !== 2}">
                            2
                        </span>
                        Szczegóły
                        <span class="flex-auto border-t border-gray-200 ml-2 hidden sm:inline-flex"></span>
                    </li>
                    <li class="flex md:w-full items-center" :class="{'text-custom-blue': step === 3, 'text-gray-500': step !== 3}">
                        <span class="flex items-center justify-center w-5 h-5 mr-2 text-xs border rounded-full shrink-0" :class="{'border-custom-blue': step === 3, 'border-gray-500': step !== 3}">
                            3
                        </span>
                        Koszyk
                        <span class="flex-auto border-t border-gray-200 ml-2 hidden sm:inline-flex"></span>
                    </li>
                    <li class="flex items-center" :class="{'text-custom-blue': step === 4, 'text-gray-500': step !== 4}">
                        <span class="flex items-center justify-center w-5 h-5 mr-2 text-xs border rounded-full shrink-0" :class="{'border-custom-blue': step === 4, 'border-gray-500': step !== 4}">
                            4
                        </span>
                        Podsumowanie
                    </li>
                </ol>
            </div>

            <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl">{{$product->display_name}}</h1>

            <div class="mt-6 sm:mt-8 lg:flex lg:gap-8">
                <!-- Formularz -->
                <div class="lg:w-2/3">
                    <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm hover:shadow-md transition-shadow duration-200">
                        <!-- Krok 1 - tylko atrybuty number -->
                        <div x-show="step === 1" class="space-y-4">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach ($attributes->sortBy('order') as $attribute)
                                @if ($attribute->type === 'number')
                                @if($attribute->name === 'window_width')
                                <!-- Specjalny input dla window_width z dynamicznym placeholderm -->
                                <div>
                                    <label for="window_width" class="mb-2 block text-sm font-medium text-gray-900">{{$attribute->display_name .' ('. $attribute->unit .')'}} <span class="text-red-500">*</span></label>
                                    <input type="number" id="window_width" step="0.1" min="{{$attribute->min}}" max="{{$attribute->max}}" x-model="product.window_width"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-custom-blue focus:ring-custom-blue"
                                        :placeholder="product.width ? `min: ${(parseFloat(product.width)-5).toFixed(1)} max: ${(parseFloat(product.width)-3.5).toFixed(1)}` : 'min: {{$attribute->min}} max: {{$attribute->max}}'"
                                        :class="{'border-red-500': validateField && (!product.window_width || product.window_width < {{$attribute->min}} || product.window_width > {{$attribute->max}} || (product.width && (product.width - product.window_width < 3.5 || product.width - product.window_width > 5)))}"
                                        required />

                                    <p class="mt-1 text-xs text-red-500" x-show="validateField && (!product.window_width || product.window_width === '')">
                                        To pole jest wymagane
                                    </p>
                                    <p class="mt-1 text-xs text-red-500" x-show="validateField && product.window_width && (product.window_width < {{$attribute->min}} || product.window_width > {{$attribute->max}})">
                                        Wartość musi być pomiędzy {{$attribute->min}} a {{$attribute->max}}
                                    </p>
                                    <p class="mt-1 text-xs text-red-500"
                                        x-show="validateField && product.width && product.window_width && (product.width - product.window_width < 3.5 || product.width - product.window_width > 5)">
                                        Wymagana szerokość szyby: <span x-text="(parseFloat(product.width)-5).toFixed(1)"></span>-<span x-text="(parseFloat(product.width)-3.5).toFixed(1)"></span> (obecnie: <span x-text="product.window_width"></span>)
                                    </p>
                                </div>
                                @else
                                <!-- Standardowy input dla innych atrybutów number -->
                                <div>
                                    <label for="{{$attribute->name}}" class="mb-2 block text-sm font-medium text-gray-900">{{$attribute->display_name .' ('. $attribute->unit .')'}} <span class="text-red-500">*</span></label>
                                    <input type="number" id="{{$attribute->name}}" step="0.1" min="{{$attribute->min}}" max="{{$attribute->max}}" x-model="product.{{$attribute->name}}"
                                        class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-custom-blue focus:ring-custom-blue"
                                        placeholder="{{'min: '. $attribute->min . ' max: '. $attribute->max}}"
                                        :class="{'border-red-500': validateField && (!product.{{$attribute->name}} || product.{{$attribute->name}} < {{$attribute->min}} || product.{{$attribute->name}} > {{$attribute->max}})}"
                                        required />

                                    <p class="mt-1 text-xs text-red-500" x-show="validateField && (!product.{{$attribute->name}} || product.{{$attribute->name}} === '')">
                                        To pole jest wymagane
                                    </p>
                                    <p class="mt-1 text-xs text-red-500" x-show="validateField && product.{{$attribute->name}} && (product.{{$attribute->name}} < {{$attribute->min}} || product.{{$attribute->name}} > {{$attribute->max}})">
                                        Wartość musi być pomiędzy {{$attribute->min}} a {{$attribute->max}}
                                    </p>
                                </div>
                                @endif
                                @endif
                                @endforeach
                            </div>

                            <div class="flex justify-between">
                                <div class="flex gap-2">
                                    <button type="button" @click="resetForm" class="px-5 py-2.5 text-sm font-medium text-gray-900 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-custom-blue">
                                        Resetuj
                                    </button>
                                    <button type="button" @click="goCart" class="flex items-center gap-1 px-3 py-2.5 text-sm font-medium text-gray-900 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-custom-blue">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        <span x-text="cart.length" class="text-xs bg-custom-blue text-white rounded-full h-5 w-5 flex items-center justify-center"></span>
                                    </button>
                                </div>
                                <button type="button" @click="nextStep" class="px-5 py-2.5 bg-gradient-to-r from-custom-blue to-custom-gran text-white font-medium rounded-lg text-sm shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                                    Dalej
                                </button>
                            </div>
                        </div>

                        <!-- Krok 2 - pozostałe atrybuty -->
                        <div x-show="step === 2" class="space-y-4">
                            @foreach ($attributes->sortBy('order') as $attribute)
                            @if ($attribute->type !== 'number')
                            @if ($attribute->type === 'text')
                            <div>
                                <label for="{{$attribute->name}}" class="mb-2 block text-sm font-medium text-gray-900">
                                    {{$attribute->display_name .' ('. $attribute->unit .')'}}
                                    @if($attribute->required !== false)<span class="text-red-500">*</span>@endif
                                </label>
                                <input type="text" id="{{$attribute->name}}" x-model="product.{{$attribute->name}}"
                                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-custom-blue focus:ring-custom-blue"
                                    :class="{'border-red-500': validateStep2 && {{$attribute->required !== false ? 'true' : 'false'}} && !product.{{$attribute->name}}}"
                                    placeholder="" {{$attribute->required !== false ? 'required' : ''}} />
                                <p class="mt-1 text-xs text-red-500" x-show="validateStep2 && {{$attribute->required !== false ? 'true' : 'false'}} && !product.{{$attribute->name}}">
                                    To pole jest wymagane
                                </p>
                            </div>
                            @endif

                            @if ($attribute->type === 'select')
                            <div>
                                <label for="{{$attribute->name}}" class="block mb-2 text-sm font-medium text-gray-900">
                                    {{$attribute->display_name }}
                                    @if($attribute->required !== false)<span class="text-red-500">*</span>@endif
                                </label>
                                <select id="{{$attribute->name}}" name="{{$attribute->name}}"
                                    {{$attribute->required !== false ? 'required' : ''}}
                                    x-model="product.{{$attribute->name}}"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-custom-blue focus:border-custom-blue block w-full p-2.5"
                                    :class="{'border-red-500': validateStep2 && {{$attribute->required !== false ? 'true' : 'false'}} && !product.{{$attribute->name}}}">
                                    <option value="">Wybierz opcję</option>
                                    @foreach($attribute->items as $item)
                                    <option value="{{$item->value}}">{{$item->value}}</option>
                                    @endforeach
                                </select>
                                <p class="mt-1 text-xs text-red-500" x-show="validateStep2 && {{$attribute->required !== false ? 'true' : 'false'}} && !product.{{$attribute->name}}">
                                    Proszę wybrać opcję
                                </p>
                            </div>
                            @endif
                            @if ($attribute->type === 'radio')
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-900">
                                    {{$attribute->display_name }}
                                    @if($attribute->required !== false)<span class="text-red-500">*</span>@endif
                                </label>
                                <div class="space-y-2">
                                    @foreach($attribute->items as $item)
                                    <div class="flex items-center">
                                        <input type="radio" id="{{$attribute->name}}_{{$item->value}}"
                                            {{$attribute->required !== false ? 'required' : ''}}
                                            name="{{$attribute->name}}"
                                            value="{{$item->value}}"
                                            x-model="product.{{$attribute->name}}"
                                            class="w-4 h-4 text-custom-blue bg-gray-100 border-gray-300 focus:ring-custom-blue">
                                        <label for="{{$attribute->name}}_{{$item->value}}" class="ml-2 text-sm font-medium text-gray-900">{{$item->value}}</label>
                                    </div>
                                    @endforeach
                                </div>
                                <p class="mt-1 text-xs text-red-500" x-show="validateStep2 && {{$attribute->required !== false ? 'true' : 'false'}} && !product.{{$attribute->name}}">
                                    Proszę wybrać opcję
                                </p>
                            </div>
                            @endif

                            @if ($attribute->type === 'color')
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-900">
                                    {{$attribute->display_name }}
                                    @if($attribute->required !== false)<span class="text-red-500">*</span>@endif
                                </label>
                                <div class="flex flex-wrap gap-4">
                                    @foreach($attribute->items as $item)
                                    <div class="flex flex-col items-center">
                                        <div class="relative">
                                            <input type="radio" id="{{$attribute->name}}_{{$item->value}}"
                                                {{$attribute->required !== false ? 'required' : ''}}
                                                name="{{$attribute->name}}"
                                                value="{{$item->value}}"
                                                x-model="product.{{$attribute->name}}"
                                                class="absolute opacity-0 w-full h-full cursor-pointer" />
                                            <div class="w-24 h-24 border rounded-md overflow-hidden"
                                                :class="{'ring-2 ring-custom-blue': product.{{$attribute->name}} === '{{$item->value}}', 'border-red-500': validateStep2 && {{$attribute->required !== false ? 'true' : 'false'}} && !product.{{$attribute->name}}}">
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
                                <p class="mt-1 text-xs text-red-500" x-show="validateStep2 && {{$attribute->required !== false ? 'true' : 'false'}} && !product.{{$attribute->name}}">
                                    Proszę wybrać kolor
                                </p>
                            </div>
                            @endif


                            <!-- Walidacja dla pola ilości -->
                            <div>
                                <label for="quantity" class="mb-2 block text-sm font-medium text-gray-900">Ilość <span class="text-red-500">*</span></label>
                                <input type="number" id="quantity" min="1" x-model="product.quantity"
                                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-custom-blue focus:ring-custom-blue"
                                    :class="{'border-red-500': validateStep2 && (!product.quantity || product.quantity < 1)}"
                                    placeholder="" required />
                                <p class="mt-1 text-xs text-red-500" x-show="validateStep2 && (!product.quantity || product.quantity < 1)">
                                    Proszę podać ilość (minimum 1)
                                </p>
                            </div>
                            @endif
                            @endforeach

                            <!-- <div>
                                <label for="quantity" class="mb-2 block text-sm font-medium text-gray-900">Ilość</label>
                                <input type="number" id="quantity" min="1" x-model="product.quantity"
                                    class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-custom-blue focus:ring-custom-blue"
                                    placeholder="" required />
                            </div> -->

                            <div class="flex justify-between">
                                <button type="button" @click="prevStep" class="px-5 py-2.5 text-sm font-medium text-gray-900 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-custom-blue">
                                    Wstecz
                                </button>
                                <button type="button" @click="addToCart" class="px-5 py-2.5 bg-gradient-to-r from-custom-blue to-custom-gran text-white font-medium rounded-lg text-sm shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                                    Dodaj do koszyka
                                </button>
                            </div>
                        </div>

                        <!-- Krok 3 - Koszyk -->
                        <div x-show="step === 3" class="space-y-4">
                            <template x-if="cart.length === 0">
                                <div class="bg-gray-50 rounded-xl p-6 text-center">
                                    <svg class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    <p class="mt-2 text-gray-600">Koszyk jest pusty</p>
                                    <button @click="step = 1" class="mt-4 px-4 py-2 bg-custom-blue text-white rounded-lg hover:bg-blue-700 transition-colors duration-200">
                                        Dodaj produkt
                                    </button>
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
                                                <button @click="removeFromCart(index)" class="text-red-500 hover:text-red-600 transition-colors duration-200">
                                                    <svg class="h-5 w-5" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </template>

                                    <div class="flex justify-between mt-6">
                                        <button @click="step = 1" class="px-5 py-2.5 text-sm font-medium text-gray-900 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-custom-blue">
                                            Dodaj kolejny
                                        </button>
                                        <button @click="step = 4" class="px-5 py-2.5 bg-gradient-to-r from-custom-blue to-custom-gran text-white font-medium rounded-lg text-sm shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200">
                                            Podsumowanie
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Krok 4 - Podsumowanie -->
                        <div x-show="step === 4" class="space-y-4">
                            <div class="flex items-center mb-4">
                                <div class="bg-gradient-to-r from-custom-blue to-custom-gran p-2 rounded-full">
                                    <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z" />
                                    </svg>
                                </div>
                                <h2 class="text-xl font-semibold text-gray-900 ml-3">Podsumowanie zamówienia</h2>
                            </div>

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

                            <dl class="flex items-center justify-between">
                                <dt class="text-base font-normal text-gray-500">Suma jednostek:</dt>
                                <dd class="text-base font-medium text-gray-900" x-text="`${totalRunningMeters.toFixed(0)}`"></dd>
                            </dl>

                            <dl class="flex items-center justify-between border-t border-gray-200 pt-2">
                                <dt class="text-base font-bold text-gray-900">Razem:</dt>
                                <dd class="text-base font-bold text-custom-blue" x-text="`${totalPrice.toFixed(2)} zł`"></dd>
                            </dl>

                            <form action="{{ route('order.store') }}" method="POST" @submit="onSubmitOrder" class="mt-6">
    @csrf
    <input type="hidden" name="cart" :value="JSON.stringify(cart)">
    <input type="hidden" name="totalRunningMeters" :value="totalRunningMeters.toFixed(2)">
    <input type="hidden" name="product_id" value="{{ $product->id }}">
    <input type="text" name="honeypot" style="display:none">

    <div class="flex justify-between gap-4">
        <button type="button" @click="step = 3" class="flex-1 px-5 py-2.5 text-sm font-medium text-gray-900 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-custom-blue">
            Wróć
        </button>
        <button type="submit" class="flex-1 px-5 py-2.5 bg-gradient-to-r from-custom-blue to-custom-gran text-white font-medium rounded-lg text-sm shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 flex items-center justify-center" :disabled="isSubmitting">
            <svg x-show="isSubmitting" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span x-text="isSubmitting ? 'Wysyłanie...' : 'Wyślij zamówienie'"></span>
        </button>
    </div>
</form>
                        </div>
                    </div>
                </div>
                <div class="lg:w-1/3">
                    <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm hover:shadow-md transition-shadow duration-200">
                        <div x-show="step === 1 || step ===2" class="space-y-4">
                            <template x-if="!hasDimensions()">
                                <div class="text-center">
                                    @if($product->image)
                                    <div x-data="{ zoomed: false }" class="relative">
                                        <!-- Miniaturka z hover effect -->
                                        <div class="relative overflow-hidden group">
                                            <img src="{{ asset('storage/' . $product->image) }}"
                                                alt="{{ $product->display_name }}"
                                                class="mx-auto max-h-64 rounded-lg cursor-pointer transition-transform duration-300 group-hover:scale-105"
                                                @click="zoomed = true">
                                        </div>

                                        <!-- Modal z powiększeniem -->
                                        <div x-show="zoomed"
                                            x-transition:enter="transition ease-out duration-300"
                                            x-transition:enter-start="opacity-0"
                                            x-transition:enter-end="opacity-100"
                                            x-transition:leave="transition ease-in duration-200"
                                            x-transition:leave-start="opacity-100"
                                            x-transition:leave-end="opacity-0"
                                            @click.away="zoomed = false"
                                            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-90 p-4">
                                            <div class="max-w-4xl max-h-screen">
                                                <img src="{{ asset('storage/' . $product->image) }}"
                                                    alt="{{ $product->display_name }}"
                                                    class="max-w-full max-h-screen object-contain">
                                                <button @click="zoomed = false"
                                                    class="absolute top-4 right-4 text-white hover:text-gray-300 transition-colors">
                                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @else
                                    <div class="bg-gray-100 rounded-lg p-8 text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <p class="mt-2">Brak zdjęcia produktu</p>
                                    </div>
                                    @endif
                                    <p class="mt-2 text-gray-500">Wprowadź wymiary aby zobaczyć podsumowanie</p>
                                </div>
                            </template>

                            <template x-if="hasDimensions()">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Wprowadzone wymiary</h3>
                                    <ul class="space-y-2">
                                        <template x-for="attribute in attributes">
                                            <li x-show="product[attribute.name]" class="flex justify-between">
                                                <span x-text="attribute.display_name" class="text-gray-600"></span>
                                                <span x-text="product[attribute.name] + (attribute.unit ? ' ' + attribute.unit : '')" class="font-medium"></span>
                                            </li>
                                        </template>
                                        <li x-show="product.quantity" class="flex justify-between border-t border-gray-200 pt-2">
                                            <span class="text-gray-600">Ilość</span>
                                            <span x-text="product.quantity + ' szt'" class="font-medium"></span>
                                        </li>
                                    </ul>
                                </div>
                            </template>
                        </div>
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
                validateField: false, // Flaga do walidacji pól w kroku 1
                validateStep2: false, // Flaga do walidacji pól w kroku 2
                 isSubmitting: false, // Flag for form submission state

                init() {
                    // Load cart for specific product
                    const allCarts = JSON.parse(localStorage.getItem('productCarts') || '{}');
                    this.cart = allCarts[this.product.productId] || [];
                },

                nextStep() {
                    this.validateField = true;

                    // Sprawdzamy podstawowe walidacje
                    const numberAttributes = this.attributes.filter(attr => attr.type === 'number');
                    let isValid = true;

                    // Najpierw sprawdzamy czy wszystkie wymagane pola są wypełnione
                    const allFieldsFilled = numberAttributes.every(attr => {
                        return this.product[attr.name] !== undefined &&
                            this.product[attr.name] !== '' &&
                            this.product[attr.name] !== null;
                    });

                    if (!allFieldsFilled) {
                        isValid = false;
                    }

                    // Następnie sprawdzamy zakresy wartości
                    numberAttributes.forEach(attr => {
                        const value = parseFloat(this.product[attr.name]);
                        if (value < attr.min || value > attr.max) {
                            isValid = false;
                        }
                    });

                    // Dodatkowa walidacja dla window_width
                    if (this.product.width && this.product.window_width) {
                        const difference = parseFloat(this.product.width) - parseFloat(this.product.window_width);
                        if (difference < 3.5 || difference > 5) {
                            isValid = false;
                            // Wymuszamy pokazanie komunikatu
                            this.$nextTick(() => {
                                this.validateField = true;
                            });
                        }
                    }

                    if (isValid) {
                        this.step++;
                        this.validateField = false;
                    }
                },
                goCart() {
                    this.step = 3;
                },
                hasDimensions() {
                    return this.attributes.some(attr =>
                        this.product[attr.name] && this.product[attr.name] !== ''
                    ) || this.product.quantity;
                },

                prevStep() {
                    this.step--;
                },

                clearCurrentCart() {
                    if (confirm('Czy na pewno chcesz wyczyścić koszyk dla tego produktu? Ta operacja jest nieodwracalna.')) {
                        const allCarts = JSON.parse(localStorage.getItem('productCarts') || '{}');
                        allCarts[this.product.productId] = [];
                        localStorage.setItem('productCarts', JSON.stringify(allCarts));
                        this.cart = [];
                        this.step = 1;
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
                    this.validateStep2 = true; // Włącz wyświetlanie komunikatów walidacyjnych w kroku 2

                    // Validate all fields
                    let isValid = true;

                    // Sprawdź wszystkie atrybuty, które nie są typu number (bo te zostały zwalidowane w kroku 1)
                    const nonNumberAttributes = this.attributes.filter(attr => attr.type !== 'number');
                    nonNumberAttributes.forEach(attr => {
                        const value = this.product[attr.name];
                        // Jeśli pole jest wymagane i nie jest wypełnione
                        if (attr.required !== false && (value === undefined || value === '' || value === null)) {
                            isValid = false;
                        }
                    });

                    // Sprawdzenie ilości
                    if (!this.product.quantity || parseInt(this.product.quantity) < 1) {
                        isValid = false;
                    }

                    if (!isValid) {
                        return; // Zatrzymaj dodawanie do koszyka
                    }

                    this.cart.push({
                        ...this.product
                    });
                    this.saveCart();
                    this.resetForm();
                    this.step = 3; // Go to cart step
                    this.validateStep2 = false; // Resetuj flagi walidacji
                },

                removeFromCart(index) {
                    this.cart.splice(index, 1);
                    this.saveCart();
                    if (this.cart.length === 0) {
                        this.step = 1; // Go back to first step if cart is empty
                    }
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
                    // Reset walidacji
                    this.validateField = false;
                    this.validateStep2 = false;

                    // Reset only non-number attributes to keep dimensions
                    //const numberAttributes = this.attributes.filter(attr => attr.type === 'number').map(attr => attr.name);

                    const newProduct = {
                        productId: this.product.productId
                    };
                    // numberAttributes.forEach(attr => {
                    //     newProduct[attr] = this.product[attr];
                    // });

                    this.product = newProduct;
                },

                onSubmitOrder(e) {
                    // Sprawdzenie czy koszyk nie jest pusty
                    if (this.cart.length === 0) {
                        e.preventDefault();
                        alert('Nie można złożyć zamówienia z pustym koszykiem. Proszę dodać przynajmniej jeden produkt.');
                        this.step = 1; // Powrót do pierwszego kroku
                        return false;
                    }

                    // Set loading state
                    this.isSubmitting = true;

                    this.$nextTick(() => {
                        // Clear cart after submission
                        const allCarts = JSON.parse(localStorage.getItem('productCarts') || '{}');
                        allCarts[this.product.productId] = [];
                        localStorage.setItem('productCarts', JSON.stringify(allCarts));
                        this.cart = [];
                        this.resetForm();
                    });
                }
            }
        }
    </script>
</x-layout>