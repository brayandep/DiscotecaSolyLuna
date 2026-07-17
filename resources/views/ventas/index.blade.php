@extends('layouts.app')

@section('title', 'Ventas')
@section('subtitle', 'Carga productos a la cuenta de un mesero y registra si pagó o quedó fiado.')

@section('content')

<style>
    .sales-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 18px;
        margin-bottom: 22px;
    }

    .sale-stat {
        background: white;
        border-radius: 18px;
        padding: 20px;
        border: 1px solid #dde6ef;
        box-shadow: 0 12px 30px rgba(5,18,32,.06);
    }

    .sale-stat span {
        color: #6f7f91;
        font-size: 13px;
        font-weight: 800;
        text-transform: uppercase;
    }

    .sale-stat h2 {
        margin: 10px 0 0;
        font-size: 28px;
        color: #07182d;
    }

    .ventas-layout {
        display: grid;
        grid-template-columns: 1.35fr .9fr;
        gap: 24px;
        align-items: start;
    }

    .panel-card {
        background: white;
        border-radius: 20px;
        border: 1px solid #dde6ef;
        box-shadow: 0 18px 45px rgba(5,18,32,.08);
        overflow: hidden;
    }

    .panel-header {
        padding: 24px 26px;
        border-bottom: 1px solid #dde6ef;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .panel-header h2 {
        margin: 0;
        font-size: 22px;
    }

    .panel-header p {
        margin: 6px 0 0;
        color: #6f7f91;
    }

    .panel-body {
        padding: 24px 26px;
    }

    .products-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 14px;
        margin-top: 18px;
        max-height: 620px;
        overflow-y: auto;
        padding-right: 6px;
    }

    .product-sale-card {
        border: 1px solid #dde6ef;
        border-radius: 16px;
        background: white;
        padding: 14px;
        display: grid;
        grid-template-columns: 70px 1fr;
        gap: 12px;
        align-items: center;
        cursor: pointer;
        transition: .2s;
        text-align: left;
    }

    .product-sale-card:hover {
        border-color: #f6b23c;
        box-shadow: 0 10px 25px rgba(246,178,60,.15);
        transform: translateY(-2px);
    }

    .product-sale-card img,
    .product-no-img {
        width: 70px;
        height: 70px;
        object-fit: cover;
        border-radius: 14px;
        border: 1px solid #e5edf5;
        background: #eef4fa;
    }

    .product-no-img {
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6f7f91;
        font-size: 12px;
    }

    .product-title {
        font-weight: 900;
        color: #07182d;
        margin-bottom: 5px;
    }

    .product-price {
        color: #07834f;
        font-weight: 900;
        font-size: 16px;
    }

    .product-stock {
        color: #6f7f91;
        font-size: 12px;
        margin-top: 3px;
    }

    .detail-table {
        border: 1px solid #dde6ef;
        border-radius: 14px;
        overflow: hidden;
        margin-bottom: 18px;
    }

    .detail-table table {
        width: 100%;
    }

    .detail-table th {
        font-size: 12px;
        padding: 12px;
    }

    .detail-table td {
        padding: 12px;
        font-size: 14px;
    }

    .qty-control {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .qty-btn {
        width: 28px;
        height: 28px;
        border: none;
        border-radius: 8px;
        background: #eef4fa;
        cursor: pointer;
        font-weight: 900;
    }

    .remove-btn {
        border: none;
        background: #fdecea;
        color: #b02a37;
        border-radius: 8px;
        padding: 7px 9px;
        cursor: pointer;
        font-weight: 800;
    }

    .total-box {
        border: 1px solid rgba(246,178,60,.55);
        background: #fff7e8;
        border-radius: 17px;
        padding: 20px;
        margin: 20px 0;
    }

    .total-box span {
        color: #9a6500;
        font-size: 13px;
        font-weight: 900;
        text-transform: uppercase;
    }

    .total-box h1 {
        margin: 8px 0 0;
        color: #9a5500;
        font-size: 34px;
    }

    .payment-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }

    .recent-sales {
        margin-top: 24px;
    }

    .empty-detail {
        padding: 18px;
        text-align: center;
        color: #6f7f91;
    }

    @media (max-width: 1100px) {
        .ventas-layout,
        .sales-stats {
            grid-template-columns: 1fr;
        }

        .products-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>

<div class="sales-stats">
    <div class="sale-stat">
        <span>Total vendido hoy</span>
        <h2>Bs {{ number_format($totalHoy, 2) }}</h2>
    </div>

    <div class="sale-stat">
        <span>Efectivo</span>
        <h2>Bs {{ number_format($efectivoHoy, 2) }}</h2>
    </div>

    <div class="sale-stat">
        <span>QR</span>
        <h2>Bs {{ number_format($qrHoy, 2) }}</h2>
    </div>

    <div class="sale-stat">
        <span>Fiado</span>
        <h2>Bs {{ number_format($fiadoHoy, 2) }}</h2>
    </div>
</div>

<form action="{{ route('ventas.store') }}" method="POST" id="ventaForm">
    @csrf

    <div class="ventas-layout">
        <div class="panel-card">
            <div class="panel-header">
                <div>
                    <h2>Productos disponibles</h2>
                    <p>Selecciona los productos que se cargarán al mesero.</p>
                </div>
            </div>

            <div class="panel-body">
                <input
                    class="input"
                    type="text"
                    id="buscarProductoVenta"
                    placeholder="Buscar producto..."
                    onkeyup="filtrarProductos()"
                >

                <div class="products-grid" id="productosGrid">
                    @foreach($productos as $producto)
                        <button
                            type="button"
                            class="product-sale-card"
                            data-name="{{ strtolower($producto->nombre) }}"
                            data-id="{{ $producto->id }}"
                            data-producto="{{ $producto->nombre }}"
                            data-precio="{{ $producto->precio_venta }}"
                            data-stock="{{ $producto->stock_actual }}"
                            data-nombre-acompanamiento="{{ $producto->nombre_acompanamiento ?? 'Acompañamiento' }}"
    data-precio-acompanamiento="{{ $producto->precio_acompanamiento ?? 0 }}"
                            onclick="agregarProducto(this)"
                        >
                            @if($producto->imagen)
                                <img src="{{ asset('storage/' . $producto->imagen) }}" alt="{{ $producto->nombre }}">
                            @else
                                <div class="product-no-img">Sin foto</div>
                            @endif

                            <div>
                                <div class="product-title">{{ $producto->nombre }}</div>
                                <div class="product-price">Bs {{ number_format($producto->precio_venta, 2) }}</div>
                                <div class="product-stock">Stock: {{ $producto->stock_actual }}</div>
                            </div>
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="panel-card">
            <div class="panel-header">
                <div>
                    <h2>Detalle de venta</h2>
                    <p>Revisa cantidades, mesero y forma de pago.</p>
                </div>
            </div>

            <div class="panel-body">
                <label>Mesero / trabajador <span class="required">*</span></label>
                <select name="trabajador_id" required>
                    <option value="">Selecciona un mesero</option>
                    @foreach($trabajadores as $trabajador)
                        <option value="{{ $trabajador->id }}">{{ $trabajador->nombre }}</option>
                    @endforeach
                </select>

                <br><br>

                <div class="detail-table">
                    <table>
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Cant.</th>
                                <th>Acomp.</th>
                                <th>Total</th>
                                <th></th>
                            </tr>
                        </thead>

                        <tbody id="detalleVentaBody">
                            <tr id="emptyRow">
<td colspan="5">
                                        <div class="empty-detail">No hay productos agregados.</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div id="hiddenInputs"></div>

                <div class="payment-grid">
                    <div>
                        <label>Estado de pago <span class="required">*</span></label>
                        <select name="estado_pago" id="estado_pago" onchange="cambiarEstadoPago()" required>
                            <option value="Pagado">Pagado</option>
                            <option value="Fiado">Fiado</option>
                        </select>
                    </div>

                    <div id="metodoPagoBox">
                        <label>Método de pago <span class="required">*</span></label>
                        <select name="metodo_pago" id="metodo_pago">
                            <option value="Efectivo">Efectivo</option>
                            <option value="QR">QR</option>
                        </select>
                    </div>
                </div>

                <br>

                <label>Observación</label>
                <textarea name="observacion" rows="3" placeholder="Ejemplo: mesa 4, cliente frecuente, pago pendiente, etc."></textarea>

                <div class="total-box">
                    <span>Total a cargar</span>
                    <h1 id="totalTexto">Bs 0.00</h1>
                </div>

                <div style="display:flex; justify-content:flex-end; gap:12px;">
                    <button type="button" class="btn btn-light" onclick="vaciarVenta()">Vaciar venta</button>
                    <button type="submit" class="btn btn-primary">Registrar venta</button>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="panel-card recent-sales">
    <div class="panel-header">
        <div>
            <h2>Últimas ventas de hoy</h2>
            <p>Resumen rápido de ventas cargadas a meseros.</p>
        </div>
    </div>

    <div class="panel-body">
        <table>
            <thead>
                <tr>
                    <th>Nro</th>
                    <th>Mesero</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Método</th>
                    <th>Hora</th>
                </tr>
            </thead>

            <tbody>
                @forelse($ventasHoy as $venta)
                    <tr>
                        <td>#{{ $venta->id }}</td>
                        <td>{{ $venta->trabajador->nombre ?? 'Sin mesero' }}</td>
                        <td>Bs {{ number_format($venta->total, 2) }}</td>
                        <td>
                            @if($venta->estado_pago == 'Pagado')
                                <span class="badge ok">Pagado</span>
                            @else
                                <span class="badge warning">Fiado</span>
                            @endif
                        </td>
                        <td>{{ $venta->metodo_pago ?? '-' }}</td>
                        <td>{{ $venta->created_at->format('H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center; color:#6f7f91;">
                            Todavía no hay ventas registradas hoy.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    let carrito = {};

    function agregarProducto(elemento) {
        const id = elemento.dataset.id;
        const nombre = elemento.dataset.producto;
        const precio = parseFloat(elemento.dataset.precio);
        const stock = parseInt(elemento.dataset.stock);
        const nombreAcomp = elemento.dataset.nombreAcompanamiento || 'Acompañamiento';
        const precioAcomp = parseFloat(elemento.dataset.precioAcompanamiento || 0);

        if (!carrito[id]) {
            carrito[id] = {
                id: id,
                nombre: nombre,
                precio: precio,
                stock: stock,
                cantidad: 1,
                nombreAcomp: nombreAcomp,
                precioAcomp: precioAcomp,
                conAcomp: 0
            };
        } else {
            if (carrito[id].cantidad >= stock) {
                alert('No hay más stock disponible para ' + nombre);
                return;
            }

            carrito[id].cantidad++;
        }

        renderDetalle();
    }

    function cambiarAcompanamiento(id, valor) {
        carrito[id].conAcomp = parseInt(valor);
        renderDetalle();
    }

    function aumentarCantidad(id) {
        if (carrito[id].cantidad >= carrito[id].stock) {
            alert('No hay más stock disponible para ' + carrito[id].nombre);
            return;
        }

        carrito[id].cantidad++;
        renderDetalle();
    }

    function disminuirCantidad(id) {
        carrito[id].cantidad--;

        if (carrito[id].cantidad <= 0) {
            delete carrito[id];
        }

        renderDetalle();
    }

    function eliminarProducto(id) {
        delete carrito[id];
        renderDetalle();
    }

    function renderDetalle() {
        const body = document.getElementById('detalleVentaBody');
        const hiddenInputs = document.getElementById('hiddenInputs');

        body.innerHTML = '';
        hiddenInputs.innerHTML = '';

        let total = 0;
        const ids = Object.keys(carrito);

        if (ids.length === 0) {
            body.innerHTML = `
                <tr id="emptyRow">
                    <td colspan="5">
                        <div class="empty-detail">No hay productos agregados.</div>
                    </td>
                </tr>
            `;

            document.getElementById('totalTexto').innerText = 'Bs 0.00';
            return;
        }

        ids.forEach(id => {
            const item = carrito[id];

            const extra = item.conAcomp === 1 ? item.precioAcomp : 0;
            const precioFinal = item.precio + extra;
            const subtotal = precioFinal * item.cantidad;

            total += subtotal;

            let acompanamientoHtml = `<span style="color:#6f7f91;">No aplica</span>`;

            if (item.precioAcomp > 0) {
                acompanamientoHtml = `
                    <select onchange="cambiarAcompanamiento('${id}', this.value)" style="padding:8px; border-radius:8px; border:1px solid #dde6ef;">
                        <option value="0" ${item.conAcomp === 0 ? 'selected' : ''}>No</option>
                        <option value="1" ${item.conAcomp === 1 ? 'selected' : ''}>
                            Sí - ${item.nombreAcomp} (+Bs ${item.precioAcomp.toFixed(2)})
                        </option>
                    </select>
                `;
            }

            body.innerHTML += `
                <tr>
                    <td>
                        <strong>${item.nombre}</strong>
                        <br>
                        <small style="color:#6f7f91;">
                            Base: Bs ${item.precio.toFixed(2)}
                        </small>
                    </td>

                    <td>
                        <div class="qty-control">
                            <button type="button" class="qty-btn" onclick="disminuirCantidad('${id}')">-</button>
                            <strong>${item.cantidad}</strong>
                            <button type="button" class="qty-btn" onclick="aumentarCantidad('${id}')">+</button>
                        </div>
                    </td>

                    <td>${acompanamientoHtml}</td>

                    <td>
                        <strong>Bs ${subtotal.toFixed(2)}</strong>
                        <br>
                        <small style="color:#6f7f91;">
                            Unitario: Bs ${precioFinal.toFixed(2)}
                        </small>
                    </td>

                    <td>
                        <button type="button" class="remove-btn" onclick="eliminarProducto('${id}')">x</button>
                    </td>
                </tr>
            `;

            hiddenInputs.innerHTML += `
                <input type="hidden" name="producto_ids[]" value="${item.id}">
                <input type="hidden" name="cantidades[]" value="${item.cantidad}">
                <input type="hidden" name="con_acompanamiento[]" value="${item.conAcomp}">
            `;
        });

        document.getElementById('totalTexto').innerText = 'Bs ' + total.toFixed(2);
    }

    function vaciarVenta() {
        carrito = {};
        renderDetalle();
    }

    function cambiarEstadoPago() {
        const estado = document.getElementById('estado_pago').value;
        const metodoBox = document.getElementById('metodoPagoBox');
        const metodoPago = document.getElementById('metodo_pago');

        if (estado === 'Fiado') {
            metodoBox.style.display = 'none';
            metodoPago.value = '';
        } else {
            metodoBox.style.display = 'block';
            metodoPago.value = 'Efectivo';
        }
    }

    function filtrarProductos() {
        const texto = document.getElementById('buscarProductoVenta').value.toLowerCase();
        const productos = document.querySelectorAll('.product-sale-card');

        productos.forEach(producto => {
            const nombre = producto.dataset.name;

            if (nombre.includes(texto)) {
                producto.style.display = 'grid';
            } else {
                producto.style.display = 'none';
            }
        });
    }

    document.getElementById('ventaForm').addEventListener('submit', function(e) {
        if (Object.keys(carrito).length === 0) {
            e.preventDefault();
            alert('Debes agregar al menos un producto.');
        }
    });
</script>

@endsection