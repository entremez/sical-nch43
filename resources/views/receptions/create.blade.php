@extends('layouts.default')

@section('title', 'Recepción de productos')

@section('content')
    <div class="max-w-6xl mx-auto p-6 bg-white rounded shadow">
        <h1 class="text-2xl font-bold mb-6">Recepción de productos de guía de despacho #{{ $movement->dispatch_guide }}</h1>
        <form method="POST" action="{{ route('receptions.store', $movement) }}" enctype="multipart/form-data" onsubmit="return validateReceptionForm()">
            @csrf

            @foreach ($products as $product)
                <div class="border p-4 rounded mb-6">
                    <h2 class="text-lg font-semibold mb-2">{{ $product->model->designation->name }}
                        {{ $product->model->brand->name }} modelo {{ $product->model->name }}</h2>

                    <label class="block mb-1 font-medium">Informe de recepción</label>
                    <textarea name="products[{{ $product->id }}][report]" class="w-full border rounded p-2 mb-3"></textarea>

                    <div class="mb-6">
                        <label class="block mb-1 font-semibold">Imágenes del producto por categoría</label>

                        @foreach ($categories as $category)
                            <div class="mb-4 border border-gray-200 rounded p-3">
                                <h3 class="font-semibold text-sm mb-2">
                                    {{ $category->name }}
                                    @if ($category->min_images > 0)
                                        <span class="text-red-600 text-xs">(mínimo {{ $category->min_images }})</span>
                                    @else
                                        <span class="text-gray-500 text-xs">(opcional)</span>
                                    @endif
                                </h3>

                                <div class="flex flex-col gap-2 category-image-group" data-category="{{ $category->id }}"
                                    data-product="{{ $product->id }}" data-min="{{ $category->min_images }}">
                                    <div class="flex gap-2">
                                        <input type="file"
                                            name="products[{{ $product->id }}][images][{{ $category->id }}][]"
                                            accept="image/*" class="flex-1 border rounded p-1" />
                                    </div>
                                </div>

                                <button type="button" class="mt-2 text-sm text-blue-600 hover:underline add-image-btn"
                                    data-category="{{ $category->id }}" data-product="{{ $product->id }}">
                                    + Agregar otra imagen
                                </button>
                            </div>
                        @endforeach
                    </div>


                </div>
            @endforeach

            <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow">
                Guardar recepción
            </button>
        </form>
    </div>


    <script>
        document.querySelectorAll('.add-image-btn').forEach(button => {
            button.addEventListener('click', function() {
                const catId = this.dataset.category;
                const productId = this.dataset.product;
                const group = this.previousElementSibling;

                // Crear contenedor para input + botón
                const wrapper = document.createElement('div');
                wrapper.className = 'flex items-center gap-2 mt-1';

                const newInput = document.createElement('input');
                newInput.type = 'file';
                newInput.name = `products[${productId}][images][${catId}][]`;
                newInput.accept = 'image/*';
                newInput.className = 'flex-1 border rounded p-1';

                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.innerText = '🗑️';
                removeBtn.className = 'text-red-600 hover:text-red-800 font-bold text-xl';
                removeBtn.addEventListener('click', function() {
                    wrapper.remove();
                });

                wrapper.appendChild(newInput);
                wrapper.appendChild(removeBtn);
                group.appendChild(wrapper);
            });
        });

function validateReceptionForm() {
    let isValid = true;
    let errorMessages = [];

    const productContainers = document.querySelectorAll('.border.p-4.rounded.mb-6');

    productContainers.forEach(productDiv => {
        const productIdMatch = productDiv.innerHTML.match(/products\[(\d+)]/);
        if (!productIdMatch) return;

        const productId = productIdMatch[1];

        const reportField = productDiv.querySelector(`textarea[name="products[${productId}][report]"]`);
        if (!reportField || reportField.value.trim() === '') {
            isValid = false;
            errorMessages.push(`Falta el informe de recepción para el producto ID ${productId}`);
        }

        const groups = productDiv.querySelectorAll(`.category-image-group[data-product="${productId}"]`);
        groups.forEach(group => {
            const catId = group.dataset.category;
            const min = parseInt(group.dataset.min || '0');
            const inputCount = group.querySelectorAll('input[type="file"]').length;

            if (min > 0 && inputCount < min) {
                isValid = false;
                errorMessages.push(`El producto ID ${productId} requiere al menos ${min} imagen(es) para la categoría ${catId}`);
            }
        });
    });

    if (!isValid) {
        alert("Errores encontrados:\n\n" + errorMessages.join("\n"));
        return false; // Cancela el envío
    }

    return true; // Permite enviar el formulario
}
    </script>


@endsection
