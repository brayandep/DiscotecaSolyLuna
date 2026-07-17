@extends('layouts.app')

@section('title', 'Registrar producto')
@section('subtitle', 'Agrega bebidas, snacks o insumos al inventario.')

@section('actions')
    <a href="{{ route('productos.index') }}" class="btn btn-light">▦ Ver productos</a>
@endsection

@section('content')

<style>
    .product-card {
        max-width: 1280px;
        margin: 0 auto;
        padding: 0;
        overflow: hidden;
    }

    .product-header {
        display: flex;
        align-items: center;
        gap: 18px;
        padding: 30px 34px 24px;
    }

    .product-icon {
        width: 66px;
        height: 66px;
        border-radius: 18px;
        background: linear-gradient(135deg, #061527, #0e2d4d);
        color: #f6b23c;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 29px;
        border: 1px solid rgba(246,178,60,.6);
        box-shadow: 0 10px 25px rgba(6,21,39,.16);
    }

    .product-header h2 {
        margin: 0;
        font-size: 27px;
        color: #07182d;
    }

    .product-header p {
        margin: 8px 0 0;
        color: #6f7f91;
    }

    .gold-line {
        height: 2px;
        background: linear-gradient(90deg, #f6b23c, rgba(246,178,60,.15));
        margin: 0 34px;
    }

    .product-form {
        padding: 30px 34px 34px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px 34px;
    }

    .form-full {
        grid-column: 1 / -1;
    }

    .field-label {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .field-label span.icon {
        color: #d99522;
        font-size: 17px;
    }

    .textarea-wrap {
        position: relative;
    }

    .textarea-wrap textarea {
        min-height: 135px;
        resize: vertical;
        padding-bottom: 35px;
    }

    .counter {
        position: absolute;
        right: 14px;
        bottom: 13px;
        color: #6f7f91;
        font-size: 12px;
    }

    .bottom-grid {
        display: grid;
        grid-template-columns: 1fr 1.3fr;
        gap: 34px;
        padding-top: 24px;
        margin-top: 24px;
        border-top: 1px solid #dde6ef;
    }

    .status-box {
        border: 1px solid rgba(246,178,60,.45);
        background: linear-gradient(135deg, rgba(246,178,60,.09), rgba(255,255,255,.9));
        border-radius: 16px;
        padding: 22px;
        display: flex;
        align-items: center;
        gap: 16px;
        min-height: 110px;
    }

    .custom-check {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: #061527;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #f6b23c;
        font-size: 22px;
        box-shadow: 0 6px 18px rgba(6,21,39,.22);
    }

    .status-title {
        font-size: 17px;
        font-weight: 800;
        color: #07182d;
    }

    .status-text {
        color: #6f7f91;
        font-size: 13px;
        margin-top: 5px;
    }

    .checkbox-hidden {
        display: none;
    }

    .photo-area {
        display: grid;
        grid-template-columns: 240px 1fr;
        gap: 22px;
        align-items: center;
    }

    .preview-box {
        border: 2px dashed rgba(246,178,60,.55);
        background: #fffaf0;
        border-radius: 16px;
        height: 150px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #8a6a36;
        overflow: hidden;
        text-align: center;
    }

    .preview-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: none;
    }

    .preview-icon {
        font-size: 38px;
        margin-bottom: 8px;
    }

    .file-input {
        border: 1px solid #dce5ee;
        border-radius: 13px;
        padding: 11px;
        width: 100%;
        background: white;
    }

    .actions-row {
        display: flex;
        justify-content: flex-end;
        gap: 14px;
        padding-top: 26px;
        margin-top: 26px;
        border-top: 1px solid #dde6ef;
    }

    @media (max-width: 900px) {
        .form-grid,
        .bottom-grid,
        .photo-area {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="card product-card">
    <div class="product-header">
        <div class="product-icon">▣</div>
        <div>
            <h2>Nuevo producto</h2>
            <p>Registra la información, existencia y fotografía del producto.</p>
        </div>
    </div>

    <div class="gold-line"></div>

    <form class="product-form" action="{{ route('productos.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-grid">
            <div>
                <label class="field-label">
                    <span class="icon">◇</span>
                    Nombre del producto <span class="required">*</span>
                </label>
                <input
                    class="input"
                    type="text"
                    name="nombre"
                    value="{{ old('nombre') }}"
                    placeholder="Ejemplo: Vodka Absolut"
                    required
                >
            </div>

            <div>
                <label class="field-label">
                    <span class="icon">☷</span>
                    Tipo de producto <span class="required">*</span>
                </label>
                <select name="categoria" required>
                    <option value="">Selecciona una opción</option>
                    <option value="Cerveza" {{ old('categoria') == 'Cerveza' ? 'selected' : '' }}>Cerveza</option>
                    <option value="Licor" {{ old('categoria') == 'Licor' ? 'selected' : '' }}>Licor</option>
                    <option value="Energizante" {{ old('categoria') == 'Energizante' ? 'selected' : '' }}>Energizante</option>
                    <option value="Gaseosa" {{ old('categoria') == 'Gaseosa' ? 'selected' : '' }}>Gaseosa</option>
                    <option value="Agua" {{ old('categoria') == 'Agua' ? 'selected' : '' }}>Agua</option>
                    <option value="Snack" {{ old('categoria') == 'Snack' ? 'selected' : '' }}>Snack</option>
                    <option value="Promoción" {{ old('categoria') == 'Promoción' ? 'selected' : '' }}>Promoción</option>
                    <option value="Insumo" {{ old('categoria') == 'Insumo' ? 'selected' : '' }}>Insumo</option>
                    <option value="Otro" {{ old('categoria') == 'Otro' ? 'selected' : '' }}>Otro</option>
                </select>
            </div>

            <div>
                <label class="field-label">
                    <span class="icon">▢</span>
                    Cantidad disponible <span class="required">*</span>
                </label>
                <input
                    class="input"
                    type="number"
                    name="stock_actual"
                    value="{{ old('stock_actual', 0) }}"
                    min="0"
                    required
                >
            </div>

            <div>
                <label class="field-label">
                    <span class="icon">Bs</span>
                    Precio de venta <span class="required">*</span>
                </label>
                <input
                    class="input"
                    type="number"
                    step="0.01"
                    name="precio_venta"
                    value="{{ old('precio_venta') }}"
                    placeholder="0.00"
                    min="0"
                    required
                >
                <div class="help">Ingresa el precio en bolivianos.</div>
            </div>
            <div>
    <label class="field-label">
        <span class="icon">＋</span>
        Acompañamiento
    </label>
    <input
        class="input"
        type="text"
        name="nombre_acompanamiento"
        value="{{ old('nombre_acompanamiento') }}"
        placeholder="Ejemplo: Hielo, vaso especial, energizante"
    >
    <div class="help">Opcional. Si el producto no tiene acompañamiento, deja este campo vacío.</div>
</div>

<div>
    <label class="field-label">
        <span class="icon">Bs</span>
        Precio de la Pieza
    </label>
    <input
        class="input"
        type="number"
        step="0.01"
        name="precio_acompanamiento"
        value="{{ old('precio_acompanamiento', 0) }}"
        placeholder="0.00"
        min="0"
    >
    <div class="help">
        Ejemplo: si el producto cuesta Bs 20 y con hielo cuesta Bs 40, coloca Bs 20 aquí.
    </div>
</div>

            <div>
                <label class="field-label">
                    <span class="icon">▦</span>
                    Cantidad mínima <span class="required">*</span>
                </label>
                <input
                    class="input"
                    type="number"
                    name="stock_minimo"
                    value="{{ old('stock_minimo', 0) }}"
                    min="0"
                    required
                >
            </div>

            <div>
                <label class="field-label">
                    <span class="icon">⌁</span>
                    Unidades <span class="required">*</span>
                </label>
                <input
                    class="input"
                    type="text"
                    name="unidad"
                    value="{{ old('unidad', 'Unidad') }}"
                    placeholder="Ejemplo: Botella, lata, unidad"
                    required
                >
            </div>

            <div class="form-full">
                <label class="field-label">
                    <span class="icon">☰</span>
                    Descripción
                </label>

                <div class="textarea-wrap">
                    <textarea
                        name="descripcion"
                        maxlength="500"
                        placeholder="Describe brevemente el producto..."
                        oninput="document.getElementById('contadorDescripcion').innerText = this.value.length + '/500'"
                    >{{ old('descripcion') }}</textarea>

                    <span class="counter" id="contadorDescripcion">0/500</span>
                </div>
            </div>
        </div>

        <div class="bottom-grid">
            <div>
                <label class="field-label">
                    <span class="icon">✓</span>
                    Estado del producto
                </label>

                <label class="status-box" for="estado">
                    <input
                        class="checkbox-hidden"
                        type="checkbox"
                        name="estado"
                        id="estado"
                        checked
                    >

                    <div class="custom-check">✓</div>

                    <div>
                        <div class="status-title">Producto disponible</div>
                        <div class="status-text">
                            El producto estará visible y podrá venderse desde el sistema.
                        </div>
                    </div>
                </label>
            </div>

            <div>
                <label class="field-label">
                    <span class="icon">▧</span>
                    Fotografía del producto
                </label>

                <div class="photo-area">
                    <div class="preview-box" id="previewBox">
                        <img id="previewImage" src="" alt="Vista previa">

                        <div id="previewText">
                            <div class="preview-icon">▧</div>
                            <strong>Sin fotografía</strong>
                            <div style="font-size:13px; margin-top:5px;">Se mostrará sin imagen</div>
                        </div>
                    </div>

                    <div>
                        <input
                            class="file-input"
                            type="file"
                            name="imagen"
                            id="imagen"
                            accept="image/jpeg,image/jpg,image/png,image/webp"
                            onchange="previewFile(event)"
                        >

                        <div class="help">
                            Formatos permitidos: JPG, JPEG, PNG, WEBP.<br>
                            Tamaño máximo: 2 MB.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="actions-row">
            <a href="{{ route('productos.index') }}" class="btn btn-light">Cancelar</a>
            <button type="submit" class="btn btn-primary">▣ Guardar producto</button>
        </div>
    </form>
</div>

<script>
    function previewFile(event) {
        const input = event.target;
        const previewImage = document.getElementById('previewImage');
        const previewText = document.getElementById('previewText');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewImage.style.display = 'block';
                previewText.style.display = 'none';
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const textarea = document.querySelector('textarea[name="descripcion"]');
        const contador = document.getElementById('contadorDescripcion');

        if (textarea && contador) {
            contador.innerText = textarea.value.length + '/500';
        }
    });
</script>

@endsection