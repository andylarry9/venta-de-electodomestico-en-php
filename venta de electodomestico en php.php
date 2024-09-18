<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Venta de productos electrodomésticos</title>
    <style>
        h1 {
            margin: 10px 0;
            text-align: center;
        }
        .section{
            width: 100%;
            display:flex;
            justify-content: center;
        }
        .form{
            width: 800px;
            box-shadow: 0 0 10px rgba(0,0,0,0.5);
            padding: 10px 0;
        }
        .form-table {
            width: 50%;
            margin: 0 auto;
            border-collapse: collapse;
        }

        .form-table td {
            padding: 10px;
        }

        .image-row img {
            display: block;
            margin: 0 auto;
            width: 700px;
            height: 300px;
        }

        .error-message {
            color: red;
            font-size: 14px;
        }

        .submit-row {
            text-align: center;
        }

        .submit-row button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .submit-row button:hover {
            background-color: #0056b3;
        }

        .nombre,
        .promedio {
            width: 100%;
            padding: 8px;
            margin: 5px 0;
            box-sizing: border-box;
        }

        .seleccion {
            width: 100%;
            padding: 8px;
            margin: 5px 0;
            box-sizing: border-box;
        }

        .resultado {
            font-size: 16px;
            font-weight: bold;
        }

    </style>
</head>

<body>

    <?php
        error_reporting(0);
     /*
        PRODUCTOS / PRECIO
         * COCINA           $1.200,00
         * REFRIGERADOR     $2.500,00
         * TELEVISION       $3.200,00
         * LAVADORA         $1.000,00
         * RADIOGRABADORA   $  700,00
         * 
         * 
         * SI SUBTOTAL SOBREPASA LOS $10.000,00 => 10%
         * CASO CONTRARIO                     => 5%
     */
        $nombre_error = $categoria_error = $cantidad_error = '';
        $precio_producto = $subtotal_pagar = $monto_descuento = $monto_pagar = 0;
        $nombre = $categoria = $cantidad = '';
        $productos = [
            "cocina" => 1200.00,
            "refrigeradora" => 2500.00,
            "televisión" => 3200.00,
            "lavadora" => 1000.00,
            "radiograbadora" => 700.00
        ];

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
        $nombre = $_POST['nombre'] ?? '';
        $categoria = $_POST['categoria'] ?? '';
        $cantidad = $_POST['cantidad'] ?? '';

        if (empty($nombre)) {$nombre_error = "Debe ingresar el nombre del cliente.";}
        if (empty($categoria)) {$categoria_error = "Debe seleccionar un producto.";}
        if (empty($cantidad) || !is_numeric($cantidad) || $cantidad < 1) {
            $cantidad_error = "La cantidad debe ser un número entero mayor que cero.";
        }

        // Procesar si no hay errores
        if (empty($nombre_error) && empty($categoria_error) && empty($cantidad_error)) {
            // Obtener el precio del producto seleccionado
            $precio_producto = $productos[$categoria];

            // Calcular subtotal
            $subtotal_pagar = $precio_producto * $cantidad;

            // Calcular descuento
            $porcentaje_descuento = ($subtotal_pagar > 10000) ? 0.10 : 0.05;
            $monto_descuento = $subtotal_pagar * $porcentaje_descuento;

            // Calcular monto a pagar
            $monto_pagar = $subtotal_pagar - $monto_descuento;
        }
    }
    ?>


    <main>
        <section class="section">
            <form class="form" method="POST" name="frmElectrodomestico" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?> ">
                <table class="form-table">
                    <tr>
                        <td colspan="3" class="header">
                            <h1>Venta de productos electrodomésticos</h1>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" class="image-row">
                            <img src="https://3.bp.blogspot.com/-Ygs-zmkaGHc/V1DDyf5n_CI/AAAAAAAAG34/jJhrWoNdtScEhj4r3PZBVViIWRTDUxAUACLcB/s1600/Electrodom%25C3%25A9sticos%2Bde%2B%25C3%25BAltima%2Bgeneraci%25C3%25B3n.jpg" alt="Electrodomesticos" class="form-image">
                        </td>
                    </tr>
                    <tr>
                        <td>Cliente:</td>
                        <td>
                            <input type="text" name="nombre" class="nombre" required value="<?php echo htmlspecialchars($nombre) ?>"/>
                        </td>
                        <td class="error-message">
                            <?php echo $nombre_error; ?>
                        </td>
                    </tr>
                    <tr>
                        <td>Producto:</td>
                        <td>
                            <select name="categoria" class="seleccion">
                                <option value="">Seleccione una categoría</option>
                                <option value="cocina" <?php echo ($categoria == 'cocina') ? 'selected' : ''; ?>>Cocina de 6 hornillas</option>
                                <option value="refrigeradora" <?php echo ($categoria == 'refrigeradora') ? 'selected' : ''; ?>>Refrigeradora</option>
                                <option value="television" <?php echo ($categoria == 'television') ? 'selected' : ''; ?>>Televisión</option>
                                <option value="lavadora" <?php echo ($categoria == 'lavadora') ? 'selected' : ''; ?>>Lavadora</option>
                                <option value="radiograbadora" <?php echo ($categoria == 'radiograbadora') ? 'selected' : ''; ?>>Radiograbadora</option>
                            </select>
                        </td>
                        <td class="error-message"><?php echo $categoria_error; ?></td>
                    </tr>
                    <tr>
                        <td>Cantidad:</td>
                        <td><input type="number" name="cantidad" step="1" class="cantidad" value="<?php echo htmlspecialchars($cantidad) ?>"></td>
                        <td class="error-message"><?php echo $cantidad_error; ?></td>
                    </tr>

                    <tr>
                        <td colspan="3" class="submit-row"><button type="submit" name="submit">Procesar</button></td>
                    </tr>
                    <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit']) && empty($nombre_error) && empty($categoria_error) && empty($promedio_error)) : ?>
                        <tr>
                            <td>Precio del producto:</td>
                            <td colspan="2">$<?php echo number_format($precio_producto, 2); ?></td>
                        </tr>
                        <tr>
                            <td>Subtotal a pagar:</td>
                            <td colspan="2">$<?php echo number_format($subtotal_pagar, 2); ?></td>
                        </tr>
                        <tr>
                            <td>Monto de descuento:</td>
                            <td colspan="2">$<?php echo number_format($monto_descuento, 2); ?></td>
                        </tr>
                        <tr>
                            <td>Monto a pagar:</td>
                            <td colspan="2">$<?php echo number_format($monto_pagar, 2); ?></td>
                        </tr>
                    <?php endif; ?>
                </table>
            </form>
        </section>
    </main>

</body>

</html>
