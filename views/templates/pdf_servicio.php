<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Comprobante de Servicio Tecnico</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #000;
            margin: 0;
            padding: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-section {
            width: 100%;
            margin-bottom: 20px;
        }
        .logo-cell {
            width: 50%;
            vertical-align: top;
        }
        .logo-cell img {
            max-width: 250px;
            max-height: 80px;
        }
        .logo-cell h1 {
            margin: 5px 0 0 0;
            font-size: 20px;
            color: #101498ff;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .company-cell {
            width: 50%;
            vertical-align: top;
            text-align: right;
        }
        .company-name {
            font-size: 16px;
            font-weight: bold;
            margin: 0 0 5px 0;
        }
        .company-ruc {
            font-size: 12px;
            margin: 0 0 5px 0;
        }
        .company-address {
            font-size: 11px;
            margin: 0 0 10px 0;
            line-height: 1.2;
        }
        .invoice-box {
            border: 2px solid #000;
            text-align: center;
            padding: 5px;
            margin-left: 20%;
            width: 80%;
            float: right;
        }
        .invoice-title {
            font-size: 14px;
            font-weight: bold;
            margin: 0;
        }
        .invoice-serie {
            font-size: 12px;
            margin: 5px 0;
        }
        .invoice-number {
            font-size: 18px;
            font-weight: bold;
            color: #d00;
            margin: 5px 0;
        }
        .invoice-date {
            font-size: 12px;
            font-weight: bold;
            text-align: left;
            margin-top: 5px;
            border-top: 1px solid #000;
            padding-top: 5px;
        }
        .client-box {
            border: 2px solid #000;
            margin-bottom: 10px;
            clear: both;
        }
        .client-table td {
            padding: 5px;
            vertical-align: top;
            border-bottom: 1px solid #000;
        }
        .client-table td:last-child {
            border-right: none;
        }
        .label {
            font-weight: bold;
            font-size: 11px;
            display: block;
            margin-bottom: 3px;
        }
        .value {
            font-size: 12px;
        }
        .details-box {
            border: 2px solid #000;
            margin-bottom: 0;
        }
        .details-box th {
            border: 1px solid #000;
            border-top: none;
            padding: 8px;
            text-align: center;
            font-weight: bold;
            font-size: 12px;
        }
        .details-box td {
            border: 1px solid #000;
            padding: 8px;
            vertical-align: top;
        }
        .col-qty { width: 15%; text-align: center; border-left: none !important; }
        .col-detail { width: 65%; }
        .col-price { width: 10%; text-align: right; }
        .col-total { width: 10%; text-align: right; border-right: none !important; }
        
        .totals-section {
            width: 100%;
            border-left: 2px solid #000;
            border-right: 2px solid #000;
            border-bottom: 2px solid #000;
        }
        .totals-left {
            width: 70%;
            vertical-align: top;
            padding: 5px;
            font-size: 10px;
            border-right: 1px solid #000;
        }
        .totals-right {
            width: 30%;
            vertical-align: top;
            padding: 0;
        }
        .totals-table td {
            border-bottom: 1px solid #000;
            padding: 4px 5px;
        }
        .totals-table tr:last-child td {
            border-bottom: none;
        }
        .t-label {
            font-weight: bold;
            text-align: left;
        }
        .t-value {
            text-align: right;
        }
        .disclaimer {
            text-align: center;
            font-size: 12px;
            font-weight: bold;
            margin-top: 15px;
        }
    </style>
</head>
<body>

    <table class="header-section">
        <tr>
            <td class="logo-cell">
                <img src="<?php echo rtrim(URL_BASE, '/') . '/public/img/Logo.webp'; ?>" alt="WorldCell">
                <h1>WorldCell</h1>
                <p style="margin: 0; font-size: 12px;">Tu tienda de tecnología</p>
            </td>
            <td class="company-cell">
                <p class="company-name">WORLDCELL S.A.</p>
                <p class="company-ruc">RUC: 0504278078001</p>
                <p class="company-address">Dirección Matriz<br>Ecuador - Cotopaxi - Latacunga</p>
                
                <div class="invoice-box">
                    <p class="invoice-title">COMPROBANTE DE SERVICIO TECNICO</p>
                    <p class="invoice-serie">SERIE:001-001</p>
                    <p class="invoice-number"><?php echo str_pad($row['id'], 9, "0", STR_PAD_LEFT); ?></p>
                    <div class="invoice-date">FECHA: <?php echo date('d-m-Y', strtotime($row['fecha_solicitud'])); ?></div>
                </div>
            </td>
        </tr>
    </table>

    <div class="client-box">
        <table class="client-table">
            <tr>
                <td style="width: 70%; border-right: 1px solid #000;">
                    <span class="label">CLIENTE:</span>
                    <span class="value"><?php echo htmlspecialchars($row['nombre'].' '.$row['apellido']); ?></span>
                </td>
                <td style="width: 30%;">
                    <span class="label">RUC / CEDULA:</span>
                    <span class="value"><?php echo htmlspecialchars($row['cedula'] ?? '9999999999'); ?></span>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="border-bottom: none;">
                    <span class="label">DIRECCION / CONTACTO:</span>
                    <span class="value"><?php echo htmlspecialchars($row['correo']); ?> | Telf: <?php echo htmlspecialchars($row['telefono'] ?? 'N/A'); ?></span>
                </td>
            </tr>
        </table>
    </div>

    <table class="details-box">
        <tr>
            <th class="col-detail">DETALLE</th>
            <th class="col-price">PRECIO<br>UNITARIO</th>
            <th class="col-total" style="border-right: none;">TOTAL</th>
        </tr>
        <tr>
            <td class="col-detail">
                Mantenimiento / Servicio Técnico:<br>
                <strong>Equipo:</strong> <?php echo htmlspecialchars($row['dispositivo']); ?><br>
                <strong>Problema Reportado:</strong> <?php echo strip_tags(nl2br(htmlspecialchars($row['descripcion_problema']))); ?><br><br>
                <strong>Estado:</strong> <?php echo htmlspecialchars($row['estado']); ?> | <strong>Prioridad:</strong> <?php echo htmlspecialchars($row['prioridad']); ?><br>
                <br>
                <strong>Entrega:</strong> <?php echo htmlspecialchars($row['tipo_entrega']); ?><br>
                <?php if ($row['tipo_entrega'] === 'Recepcion a domicilio'): ?>
                    Ubicación: <?php echo htmlspecialchars($row['ubicacion_domicilio']); ?><br>
                    Para: <?php echo htmlspecialchars($row['fecha_domicilio']); ?> a las <?php echo htmlspecialchars($row['hora_domicilio']); ?>
                <?php elseif ($row['tipo_entrega'] === 'Envio al local'): ?>
                    Sucursal: <?php echo htmlspecialchars($row['sucursal_local']); ?> (<?php echo htmlspecialchars($row['metodo_envio']); ?>)<br>
                    Llegada est.: <?php echo htmlspecialchars($row['fecha_local']); ?> a las <?php echo htmlspecialchars($row['hora_local']); ?>
                <?php endif; ?>
            </td>
            <td class="col-price"><?php echo number_format(!empty($row['precio_base']) ? $row['precio_base'] : $row['precio_estimado'], 2); ?></td>
            <td class="col-total"><?php echo number_format(!empty($row['precio_base']) ? $row['precio_base'] : $row['precio_estimado'], 2); ?></td>
        </tr>
    </table>

    <table class="totals-section">
        <tr>
            <td class="totals-right">
                <table class="totals-table">
                    <?php
                        $tiene_descuento = !empty($row['descuento_porcentaje']) && (float)$row['descuento_porcentaje'] > 0;
                        $subtotal = $tiene_descuento ? (float)$row['precio_base'] : (float)$row['precio_estimado'];
                        $total_final = (float)$row['precio_estimado'];
                        $monto_descuento = $subtotal - $total_final;
                    ?>
                    <tr>
                        <td class="t-label">SUBTOTAL</td>
                        <td class="t-value"><?php echo number_format($subtotal, 2); ?></td>
                    </tr>
                    <?php if ($tiene_descuento): ?>
                    <tr style="color: #cc0000; background: #fff5f5;">
                        <td class="t-label" style="font-size: 12px;">
                            DESCUENTO <?php echo round($row['descuento_porcentaje']); ?>%
                            <?php if (!empty($row['nombre_oferta'])): ?>
                                — <?php echo htmlspecialchars($row['nombre_oferta']); ?>
                            <?php endif; ?>
                        </td>
                        <td class="t-value" style="font-size: 12px; font-weight: bold;">-<?php echo number_format($monto_descuento, 2); ?></td>
                    </tr>
                    <?php else: ?>
                    <tr>
                        <td class="t-label">IVA 0%</td>
                        <td class="t-value">0.00</td>
                    </tr>
                    <tr>
                        <td class="t-label">IVA 12%</td>
                        <td class="t-value">0.00</td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <td class="t-label" style="font-size: 13px;"><strong>TOTAL US$</strong></td>
                        <td class="t-value" style="font-size: 13px;"><strong><?php echo number_format($total_final, 2); ?></strong></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table style="width: 100%; margin-top: 35px; text-align: center;">
        <tr>
            <td style="width: 50%;">
                <hr style="width: 60%; border: 0; border-top: 1px solid #000;">
                <strong>FIRMA CLIENTE</strong>
            </td>
            <td style="width: 50%;">
                <hr style="width: 60%; border: 0; border-top: 1px solid #000;">
                <strong>FIRMA / SELLO EMPRESA</strong>
            </td>
        </tr>
    </table>

    <div class="disclaimer">
        DOCUMENTO SIN VALIDEZ TRIBUTARIA
    </div>

</body>
</html>
