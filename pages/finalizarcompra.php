<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalizar Compra</title>
    <link rel="stylesheet" href="../assets/css/finalizarcompra.css">
</head>
<body>
    <div class="checkout-container">
        <h1>FINALIZAR COMPRA</h1>

        <div class="section">
            <h2>Resumen del Pedido</h2>
            <div class="item">
                <span>Perfume Floral (100ml)</span>
                <span>$120.000</span>
            </div>
            <div class="item">
                <span>Perfume Vainilla (50ml)</span>
                <span>$80.000</span>
            </div>
            <div class="total">
                <strong>Total:</strong>
                <strong>$200.000</strong>
            </div>
        </div>

        <div class="section">
            <h2>Datos de Envío</h2>
            <input type="text" placeholder="Nombre completo">
            <input type="text" placeholder="Dirección">
            <input type="text" placeholder="Teléfono">
            <input type="email" placeholder="Correo electrónico">
        </div>

        <div class="section">
            <h2>Método de Pago</h2>
            <select>
                <option>Tarjeta de crédito</option>
                <option>Transferencia bancaria</option>
                <option>Pago contra entrega</option>
            </select>
        </div>

        <button class="btn-finish">Finalizar Compra</button>
    </div>
</body>
</html>
