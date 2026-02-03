    <div class="flex items-center justify-between mb-4 lg:hidden">
        <h3 class="text-lg font-bold text-gray-900">Filtros</h3>
        <button type="button" class="close-filters-btn text-gray-500 hover:text-gray-700">
            <i class="fas fa-times text-xl"></i>
        </button>
    </div>
    <h3 class="text-lg font-bold text-gray-900 mb-4 hidden lg:block">Filtros</h3>

    <!-- Categories -->
    <div class="mb-6">
        <h4 class="font-semibold text-gray-700 mb-3">Categorías</h4>
        <div class="space-y-2">
            <a href="{{ route('shop') }}" class="block px-3 py-2 rounded {{ !request('category') ? 'bg-pink-50 text-pink-600' : 'text-gray-700 hover:bg-gray-50' }}">
                Todas las categorías
            </a>
            @foreach($categories as $cat)
            <a href="{{ route('shop.category', $cat->slug) }}"
               class="block px-3 py-2 rounded {{ request('category') == $cat->slug ? 'bg-pink-50 text-pink-600' : 'text-gray-700 hover:bg-gray-50' }}">
                {{ $cat->name }}
                <span class="text-xs text-gray-500">({{ $cat->products_count }})</span>
            </a>
            @endforeach
        </div>
    </div>

    <!-- Price Range -->
    <div class="mb-6">
        <h4 class="font-semibold text-gray-700 mb-3">Rango de Precio</h4>
        <form method="GET" action="{{ route('shop') }}" class="space-y-3">
            @if(request('category'))
            <input type="hidden" name="category" value="{{ request('category') }}">
            @endif
            @if(request('subcategory'))
            <input type="hidden" name="subcategory" value="{{ request('subcategory') }}">
            @endif
            <div>
                <label class="text-sm text-gray-600">Mínimo</label>
                <input type="number" name="min_price" value="{{ request('min_price') }}"
                       class="w-full border-gray-300 rounded-lg mt-1" placeholder="$0">
            </div>
            <div>
                <label class="text-sm text-gray-600">Máximo</label>
                <input type="number" name="max_price" value="{{ request('max_price') }}"
                       class="w-full border-gray-300 rounded-lg mt-1" placeholder="$999">
            </div>
            <button type="submit" class="w-full bg-pink-600 hover:bg-pink-700 text-white font-semibold py-2 rounded-lg transition">
                Aplicar
            </button>
        </form>
    </div>

    <!-- Availability -->
    <div>
        <h4 class="font-semibold text-gray-700 mb-3">Disponibilidad</h4>
        <form method="GET" action="{{ route('shop') }}"> 
             @if(request('category'))
            <input type="hidden" name="category" value="{{ request('category') }}">
            @endif
             @if(request('subcategory'))
            <input type="hidden" name="subcategory" value="{{ request('subcategory') }}">
            @endif
            @if(request('min_price'))
            <input type="hidden" name="min_price" value="{{ request('min_price') }}">
            @endif
             @if(request('max_price'))
            <input type="hidden" name="max_price" value="{{ request('max_price') }}">
            @endif
            
            <label class="flex items-center space-x-2 cursor-pointer">
                <input type="checkbox" name="in_stock" value="1" {{ request('in_stock') ? 'checked' : '' }} onchange="this.form.submit()" class="rounded text-pink-600 focus:ring-pink-500">
                <span class="text-sm text-gray-700">Solo productos en stock</span>
            </label>
        </form>
    </div>
</div>
