<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Etiquetas Premium - Mincoli</title>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 30px;
            min-height: 100vh;
        }

        .toolbar {
            background: white;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .settings-panel {
            background: white;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 30px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }

        .settings-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 24px;
            margin-top: 20px;
        }

        .settings-section {
            border-left: 3px solid #ec4899;
            padding-left: 16px;
        }

        .settings-section h3 {
            font-size: 14px;
            font-weight: 700;
            color: #1f2937;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 12px;
        }

        .checkbox-group {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .checkbox-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .checkbox-item input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: #ec4899;
        }

        .checkbox-item label {
            font-size: 14px;
            color: #4b5563;
            cursor: pointer;
            user-select: none;
        }

        .template-selector {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 12px;
        }

        .template-option {
            background: #f9fafb;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 16px;
            cursor: pointer;
            transition: all 0.2s;
            text-align: center;
        }

        .template-option:hover {
            border-color: #ec4899;
            background: #fdf2f8;
        }

        .template-option.active {
            border-color: #ec4899;
            background: linear-gradient(135deg, #fdf2f8 0%, #fae8ff 100%);
            box-shadow: 0 4px 12px rgba(236, 72, 153, 0.2);
        }

        .template-icon {
            font-size: 32px;
            margin-bottom: 8px;
        }

        .template-name {
            font-size: 13px;
            font-weight: 600;
            color: #1f2937;
        }

        .toggle-settings {
            background: #f3f4f6;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .toggle-settings:hover {
            background: #e5e7eb;
        }

        .hidden {
            display: none;
        }

        .table-template {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }

        .table-template table {
            width: 100%;
            border-collapse: collapse;
        }

        .table-template th {
            background: linear-gradient(135deg, #ec4899 0%, #8b5cf6 100%);
            color: white;
            padding: 12px;
            text-align: left;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .table-template td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 13px;
            color: #1f2937;
        }

        .table-template tr:last-child td {
            border-bottom: none;
        }

        .table-template tr:hover {
            background: #f9fafb;
        }

        .list-template {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }

        .list-item {
            padding: 16px;
            border-bottom: 2px solid #f3f4f6;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .list-item:last-child {
            border-bottom: none;
        }

        .toolbar-title {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .toolbar-title h1 {
            font-size: 24px;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .store-logo {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #ec4899 0%, #8b5cf6 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 20px;
        }

        .toolbar-actions {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .format-selector {
            display: flex;
            background: #f3f4f6;
            border-radius: 10px;
            padding: 4px;
        }

        .format-btn {
            padding: 8px 16px;
            border: none;
            background: transparent;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 13px;
            color: #6b7280;
            transition: all 0.2s;
        }

        .format-btn.active {
            background: white;
            color: #ec4899;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-print {
            background: linear-gradient(135deg, #ec4899 0%, #8b5cf6 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(236, 72, 153, 0.4);
        }

        .btn-print:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(236, 72, 153, 0.5);
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .labels-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .labels-grid.format-small {
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        }

        .labels-grid.format-large {
            grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
        }

        /* Formatos térmicos */
        .labels-grid.format-thermal-58 {
            grid-template-columns: 1fr;
            width: 58mm;
            margin: 0 auto 30px;
            gap: 4mm;
        }

        .labels-grid.format-thermal-80 {
            grid-template-columns: 1fr;
            width: 80mm;
            margin: 0 auto 30px;
            gap: 5mm;
        }

        .format-thermal-58 .label,
        .format-thermal-80 .label {
            padding: 12px;
            box-shadow: none;
            border: 1px dashed #e5e7eb;
        }

        .format-thermal-58 .product-name,
        .format-thermal-80 .product-name {
            font-size: 14px;
        }

        .format-thermal-58 .price-value,
        .format-thermal-80 .price-value {
            font-size: 18px;
        }

        .format-thermal-58 .barcode-container svg,
        .format-thermal-80 .barcode-container svg {
            max-height: 40px;
        }

        .label {
            background: white;
            border-radius: 16px;
            padding: 24px;
            page-break-inside: avoid;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            position: relative;
            overflow: hidden;
            transition: all 0.3s;
        }

        .label:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        }

        .label::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #ec4899, #8b5cf6, #3b82f6);
        }

        .label-header {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f3f4f6;
        }

        .product-name {
            font-size: 18px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 6px;
            line-height: 1.3;
        }

        .product-category {
            display: inline-block;
            padding: 4px 12px;
            background: linear-gradient(135deg, #ec4899 0%, #8b5cf6 100%);
            color: white;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .featured-badge {
            position: absolute;
            top: 12px;
            right: 12px;
            background: #fbbf24;
            color: #78350f;
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .sale-badge {
            position: absolute;
            top: 5px;
            right: 5px;
            background: #ef4444;
            color: white;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .sku-display {
            background: #f3f4f6;
            padding: 12px;
            border-radius: 8px;
            margin: 15px 0;
            text-align: center;
        }

        .sku-label {
            font-size: 10px;
            color: #6b7280;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 1px;
        }

        .sku-value {
            font-family: 'Courier New', monospace;
            font-size: 16px;
            font-weight: 700;
            color: #1f2937;
            margin-top: 4px;
        }

        .barcode-container {
            background: #f9fafb;
            border-radius: 12px;
            padding: 16px;
            margin: 20px 0;
            text-align: center;
        }

        .barcode-container svg {
            max-width: 100%;
            height: auto;
        }

        .barcode-label {
            font-size: 10px;
            color: #6b7280;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }

        .qr-section {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 15px 0;
        }

        .qr-code {
            background: white;
            padding: 10px;
            border-radius: 8px;
            border: 2px solid #f3f4f6;
        }

        .price-showcase {
            background: linear-gradient(135deg, #ec4899 0%, #8b5cf6 100%);
            color: white;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
            margin-top: 20px;
            position: relative;
            overflow: hidden;
        }

        .price-showcase::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: shimmer 3s infinite;
        }

        @keyframes shimmer {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(-20px, -20px); }
        }

        .price-value {
            font-size: 36px;
            font-weight: 800;
            margin-bottom: 5px;
            text-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        .price-label {
            font-size: 12px;
            opacity: 0.9;
            font-weight: 600;
        }

        .stats-row {
            display: flex;
            justify-content: space-around;
            padding: 15px 0;
            border-top: 2px solid #f3f4f6;
            margin-top: 15px;
        }

        .stat-item {
            text-align: center;
        }

        .stat-label {
            font-size: 10px;
            color: #6b7280;
            text-transform: uppercase;
            font-weight: 600;
        }

        .stat-value {
            font-size: 16px;
            font-weight: 700;
            color: #1f2937;
            margin-top: 4px;
        }

        .format-small .product-name { font-size: 14px; }
        .format-small .price-value { font-size: 24px; }
        .format-small .label { padding: 16px; }

        .format-large .product-name { font-size: 22px; }
        .format-large .price-value { font-size: 42px; }
        .format-large .label { padding: 30px; }

        .copy-btn {
            padding: 8px 16px;
            border: 2px solid #e5e7eb;
            background: white;
            border-radius: 8px;
            font-weight: 700;
            font-size: 13px;
            color: #6b7280;
            cursor: pointer;
            transition: all 0.2s;
            min-width: 50px;
        }

        .copy-btn:hover {
            border-color: #ec4899;
            color: #ec4899;
            background: #fdf2f8;
        }

        .copy-btn.active {
            background: linear-gradient(135deg, #ec4899 0%, #8b5cf6 100%);
            color: white;
            border-color: #ec4899;
            box-shadow: 0 2px 8px rgba(236, 72, 153, 0.3);
        }

        @media print {
            body {
                background: white;
                padding: 0;
                min-height: auto;
            }

            .container {
                max-width: 100%;
                margin: 0;
            }

            .toolbar, .settings-panel {
                display: none !important;
            }

            .labels-grid {
                display: grid !important;
                grid-template-columns: repeat(3, 1fr) !important;
                gap: 8mm !important;
                padding: 10mm;
            }

            .labels-grid.format-small {
                grid-template-columns: repeat(4, 1fr) !important;
                gap: 5mm !important;
            }

            .labels-grid.format-large {
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 10mm !important;
            }

            .label {
                box-shadow: none !important;
                border: 1px solid #e5e7eb !important;
                padding: 12px !important;
                page-break-inside: avoid;
                break-inside: avoid;
                transform: none !important;
                margin: 0 !important;
            }

            .label::before {
                height: 3px;
            }

            .product-name {
                font-size: 14px !important;
            }

            .price-value {
                font-size: 24px !important;
            }

            .barcode-container {
                padding: 10px !important;
                margin: 10px 0 !important;
            }

            .barcode-container svg {
                max-height: 50px !important;
            }

            .qr-code canvas,
            .qr-code img {
                width: 60px !important;
                height: 60px !important;
            }

            .price-showcase {
                padding: 12px !important;
                margin-top: 10px !important;
            }

            .format-small .label {
                padding: 8px !important;
            }

            .format-small .product-name {
                font-size: 11px !important;
            }

            .format-small .price-value {
                font-size: 18px !important;
            }

            .format-small .barcode-container svg {
                max-height: 40px !important;
            }

            .format-small .qr-code canvas,
            .format-small .qr-code img {
                width: 50px !important;
                height: 50px !important;
            }

            .format-large .label {
                padding: 16px !important;
            }

            .format-large .product-name {
                font-size: 16px !important;
            }

            .format-large .price-value {
                font-size: 28px !important;
            }

            .table-template,
            .list-template {
                page-break-inside: avoid;
            }

            @page {
                size: A4 portrait;
                margin: 10mm;
            }

            /* Forzar ancho térmico en impresión */
            .labels-grid.format-thermal-58 {
                width: 58mm !important;
                grid-template-columns: 1fr !important;
                gap: 4mm !important;
                padding: 0 !important;
            }

            .labels-grid.format-thermal-80 {
                width: 80mm !important;
                grid-template-columns: 1fr !important;
                gap: 5mm !important;
                padding: 0 !important;
            }

            .format-thermal-58 .label,
            .format-thermal-80 .label {
                width: auto;
                margin: 0;
                border: 1px solid #e5e7eb !important;
                padding: 10px !important;
                box-shadow: none !important;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="toolbar">
            <div class="toolbar-title">
                <div class="store-logo">M</div>
                <div>
                    <h1>Etiquetas Premium</h1>
                    <p style="color: #6b7280; font-size: 13px; margin-top: 2px;">
                        {{ count($products) }} producto{{ count($products) != 1 ? 's' : '' }} • <span id="totalLabels">{{ count($products) }}</span> etiqueta{{ count($products) != 1 ? 's' : '' }} • {{ now()->format('d/m/Y H:i') }}
                    </p>
                </div>
            </div>
            <div class="toolbar-actions">
                <button class="toggle-settings" onclick="toggleSettings()">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                    </svg>
                    Configurar
                </button>
                <div class="format-selector" style="flex-wrap: wrap; gap: 6px;">
                    <button class="format-btn" onclick="changeFormat('small')">Pequeña</button>
                    <button class="format-btn active" onclick="changeFormat('medium')">Mediana</button>
                    <button class="format-btn" onclick="changeFormat('large')">Grande</button>
                    <button class="format-btn" onclick="changeFormat('thermal58')">Térmica 58mm</button>
                    <button class="format-btn" onclick="changeFormat('thermal80')">Térmica 80mm</button>
                </div>
                <button class="btn btn-print" onclick="window.print()">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Imprimir
                </button>
            </div>
        </div>

        <div class="settings-panel hidden" id="settingsPanel">
            <div style="display: flex; justify-content: space-between; align-items: center; gap: 12px; margin-bottom: 8px;">
                <div>
                    <h2 style="font-size: 18px; font-weight: 700; color: #1f2937;">Personalizar Impresion</h2>
                    <p style="color: #6b7280; font-size: 14px;">Selecciona el formato y los datos que deseas incluir en la impresion</p>
                </div>
                <button type="button" onclick="clearAllToggles()" style="padding: 8px 12px; border: 1px solid #e5e7eb; border-radius: 8px; background: #fff; color: #374151; font-size: 13px; font-weight: 600; cursor: pointer;">
                    Desmarcar todo
                </button>
            </div>

            <div class="settings-grid">
                <div class="settings-section">
                    <h3>📄 Formato de Impresión</h3>
                    <div class="template-selector">
                        <div class="template-option active" onclick="selectTemplate('labels')" id="template-labels">
                            <div class="template-icon">🏷️</div>
                            <div class="template-name">Etiquetas</div>
                        </div>
                        <div class="template-option" onclick="selectTemplate('table')" id="template-table">
                            <div class="template-icon">📊</div>
                            <div class="template-name">Planilla</div>
                        </div>
                        <div class="template-option" onclick="selectTemplate('list')" id="template-list">
                            <div class="template-icon">📋</div>
                            <div class="template-name">Lista</div>
                        </div>
                    </div>
                </div>

                <div class="settings-section">
                    <h3>✨ Datos a Mostrar</h3>
                    <div class="checkbox-group">
                        <div class="checkbox-item">
                            <input class="setting-toggle" type="checkbox" id="show-name" checked onchange="updatePreview()">
                            <label for="show-name">Nombre del producto</label>
                        </div>
                        <div class="checkbox-item">
                            <input class="setting-toggle" type="checkbox" id="show-category" checked onchange="updatePreview()">
                            <label for="show-category">Categoría</label>
                        </div>
                        <div class="checkbox-item">
                            <input class="setting-toggle" type="checkbox" id="show-sku" checked onchange="updatePreview()">
                            <label for="show-sku">SKU</label>
                        </div>
                        <div class="checkbox-item">
                            <input class="setting-toggle" type="checkbox" id="show-barcode" checked onchange="updatePreview()">
                            <label for="show-barcode">Código de barras</label>
                        </div>
                    </div>
                </div>

                <div class="settings-section">
                    <h3>💰 Información Comercial</h3>
                    <div class="checkbox-group">
                        <div class="checkbox-item">
                            <input class="setting-toggle" type="checkbox" id="show-price" checked onchange="updatePreview()">
                            <label for="show-price">Precio</label>
                        </div>
                        <div class="checkbox-item">
                            <input class="setting-toggle" type="checkbox" id="show-stock" checked onchange="updatePreview()">
                            <label for="show-stock">Stock</label>
                        </div>
                        <div class="checkbox-item">
                            <input class="setting-toggle" type="checkbox" id="show-status" checked onchange="updatePreview()">
                            <label for="show-status">Estado</label>
                        </div>
                        <div class="checkbox-item">
                            <input class="setting-toggle" type="checkbox" id="show-qr" checked onchange="updatePreview()">
                            <label for="show-qr">Código QR</label>
                        </div>
                    </div>
                </div>

                <div class="settings-section">
                    <h3>🏅 Badges y Extras</h3>
                    <div class="checkbox-group">
                        <div class="checkbox-item">
                            <input class="setting-toggle" type="checkbox" id="show-featured" checked onchange="updatePreview()">
                            <label for="show-featured">Badge destacado</label>
                        </div>
                        <div class="checkbox-item">
                            <input class="setting-toggle" type="checkbox" id="show-sale" checked onchange="updatePreview()">
                            <label for="show-sale">Badge oferta</label>
                        </div>
                    </div>
                </div>

                <div class="settings-section">
                    <h3>🔢 Copias por Producto</h3>
                    <p style="font-size: 12px; color: #6b7280; margin-bottom: 12px;">Cantidad de etiquetas a imprimir por cada producto</p>
                    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                        <button class="copy-btn active" onclick="setCopies(1)" data-copies="1">1x</button>
                        <button class="copy-btn" onclick="setCopies(2)" data-copies="2">2x</button>
                        <button class="copy-btn" onclick="setCopies(3)" data-copies="3">3x</button>
                        <button class="copy-btn" onclick="setCopies(5)" data-copies="5">5x</button>
                        <button class="copy-btn" onclick="setCopies(10)" data-copies="10">10x</button>
                        <div style="display: flex; align-items: center; gap: 8px; margin-left: 8px;">
                            <input type="number" id="custom-copies" min="1" max="100" placeholder="Personalizado"
                                style="width: 100px; padding: 8px 12px; border: 2px solid #e5e7eb; border-radius: 8px; font-size: 13px; font-weight: 600;"
                                onchange="setCopies(this.value)">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="labels-grid" id="labelsGrid"></div>
    </div>

    <script>
        window.productsData = {!! json_encode($products) !!};
    </script>

    <script>
        const products = window.productsData;
        let currentTemplate = 'labels';
        let copiesPerProduct = 1;

        document.addEventListener('DOMContentLoaded', function() {
            renderTemplate();
        });

        function toggleSettings() {
            const panel = document.getElementById('settingsPanel');
            panel.classList.toggle('hidden');
        }

        function selectTemplate(template) {
            currentTemplate = template;
            document.querySelectorAll('.template-option').forEach(opt => opt.classList.remove('active'));
            document.getElementById('template-' + template).classList.add('active');
            renderTemplate();
        }

        function clearAllToggles() {
            document.querySelectorAll('.setting-toggle').forEach(cb => cb.checked = false);
            updatePreview();
        }

        function renderTemplate() {
            const container = document.getElementById('labelsGrid');

            if (currentTemplate === 'labels') {
                renderLabels(container);
            } else if (currentTemplate === 'table') {
                renderTable(container);
            } else if (currentTemplate === 'list') {
                renderList(container);
            }

            setTimeout(() => {
                initializeBarcodes();
                initializeQRCodes();
            }, 100);
        }

        function renderLabels(container) {
            const showName = document.getElementById('show-name')?.checked ?? true;
            const showCategory = document.getElementById('show-category')?.checked ?? true;
            const showSku = document.getElementById('show-sku')?.checked ?? true;
            const showBarcode = document.getElementById('show-barcode')?.checked ?? true;
            const showPrice = document.getElementById('show-price')?.checked ?? true;
            const showStock = document.getElementById('show-stock')?.checked ?? true;
            const showStatus = document.getElementById('show-status')?.checked ?? true;
            const showQr = document.getElementById('show-qr')?.checked ?? true;
            const showFeatured = document.getElementById('show-featured')?.checked ?? true;
            const showSale = document.getElementById('show-sale')?.checked ?? true;

            let html = '';
            products.forEach(product => {
                // Repetir cada producto según copiesPerProduct
                for (let copy = 0; copy < copiesPerProduct; copy++) {
                    html += `<div class="label">`;

                    if (showFeatured && product.is_featured) {
                        html += `<div class="featured-badge">
                            <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            Destacado
                        </div>`;
                    }

                    if (showSale && product.sale_price && product.sale_price < product.price) {
                        html += `<div class="sale-badge">¡Oferta!</div>`;
                    }

                    if (showName || showCategory) {
                        html += `<div class="label-header">`;
                        if (showName) html += `<div class="product-name">${product.name}</div>`;
                        if (showCategory) html += `<span class="product-category">${product.category?.name ?? 'Sin categoría'}</span>`;
                        html += `</div>`;
                    }

                    if (showSku) {
                        html += `<div class="sku-display">
                            <div class="sku-label">SKU</div>
                            <div class="sku-value">${product.sku}</div>
                        </div>`;
                    }

                    if (showBarcode && product.barcode) {
                        html += `<div class="barcode-container">
                            <div class="barcode-label">Código de Barras</div>
                            <svg class="barcode" data-barcode="${product.barcode}"></svg>
                        </div>`;
                    }

                    if (showQr) {
                        html += `<div class="qr-section">
                            <div class="qr-code" id="qr-${product.id}-copy-${copy}"></div>
                        </div>`;
                    }

                    if (showPrice) {
                        html += `<div class="price-showcase">
                            <div class="price-value">$${parseFloat(product.price).toFixed(2)}</div>
                            <div class="price-label">PRECIO REGULAR</div>`;
                        if (showSale && product.sale_price && product.sale_price < product.price) {
                            html += `<div style="margin-top: 8px; font-size: 14px; text-decoration: line-through; opacity: 0.8;">
                                Antes: $${parseFloat(product.sale_price).toFixed(2)}
                            </div>`;
                        }
                        html += `</div>`;
                    }

                    if (showStock || showStatus) {
                        html += `<div class="stats-row">`;
                        if (showStock) {
                            html += `<div class="stat-item">
                                <div class="stat-label">Stock</div>
                                <div class="stat-value">${product.stock}</div>
                            </div>`;
                        }
                        if (showStatus) {
                            html += `<div class="stat-item">
                                <div class="stat-label">Estado</div>
                                <div class="stat-value" style="font-size: 12px;">${product.status.charAt(0).toUpperCase() + product.status.slice(1)}</div>
                            </div>`;
                        }
                        html += `</div>`;
                    }

                    html += `</div>`;
                }
            });

            container.innerHTML = html;
            container.className = 'labels-grid';
        }

        function renderTable(container) {
            const showName = document.getElementById('show-name')?.checked ?? true;
            const showCategory = document.getElementById('show-category')?.checked ?? true;
            const showSku = document.getElementById('show-sku')?.checked ?? true;
            const showBarcode = document.getElementById('show-barcode')?.checked ?? true;
            const showPrice = document.getElementById('show-price')?.checked ?? true;
            const showStock = document.getElementById('show-stock')?.checked ?? true;
            const showStatus = document.getElementById('show-status')?.checked ?? true;

            let html = '<div class="table-template"><table><thead><tr>';

            if (showName) html += '<th>Producto</th>';
            if (showCategory) html += '<th>Categoría</th>';
            if (showSku) html += '<th>SKU</th>';
            if (showBarcode) html += '<th>Código de Barras</th>';
            if (showPrice) html += '<th>Precio</th>';
            if (showStock) html += '<th>Stock</th>';
            if (showStatus) html += '<th>Estado</th>';

            html += '</tr></thead><tbody>';

            products.forEach(product => {
                html += '<tr>';
                if (showName) html += `<td><strong>${product.name}</strong></td>`;
                if (showCategory) html += `<td>${product.category?.name ?? 'Sin categoría'}</td>`;
                if (showSku) html += `<td><code>${product.sku}</code></td>`;
                if (showBarcode) html += `<td>${product.barcode ?? '-'}</td>`;
                if (showPrice) html += `<td><strong>$${parseFloat(product.price).toFixed(2)}</strong></td>`;
                if (showStock) html += `<td>${product.stock}</td>`;
                if (showStatus) html += `<td><span style="text-transform: capitalize;">${product.status}</span></td>`;
                html += '</tr>';
            });

            html += '</tbody></table></div>';
            container.innerHTML = html;
            container.className = '';
        }

        function renderList(container) {
            const showCategory = document.getElementById('show-category')?.checked ?? true;
            const showSku = document.getElementById('show-sku')?.checked ?? true;
            const showBarcode = document.getElementById('show-barcode')?.checked ?? true;
            const showPrice = document.getElementById('show-price')?.checked ?? true;
            const showStock = document.getElementById('show-stock')?.checked ?? true;

            let html = '<div class="list-template">';

            products.forEach(product => {
                html += `<div class="list-item">
                    <div>
                        <div style="font-weight: 700; font-size: 16px; color: #1f2937; margin-bottom: 8px;">${product.name}</div>
                        <div style="display: flex; gap: 16px; flex-wrap: wrap;">`;

                if (showCategory) html += `<span style="font-size: 13px; color: #6b7280;">📁 ${product.category?.name ?? 'Sin categoría'}</span>`;
                if (showSku) html += `<span style="font-size: 13px; color: #6b7280;">🏷️ SKU: <strong>${product.sku}</strong></span>`;
                if (showBarcode && product.barcode) html += `<span style="font-size: 13px; color: #6b7280;">📊 ${product.barcode}</span>`;
                if (showStock) html += `<span style="font-size: 13px; color: #6b7280;">📦 Stock: <strong>${product.stock}</strong></span>`;

                html += `</div></div>`;

                if (showPrice) {
                    html += `<div style="text-align: right;">
                        <div style="font-size: 24px; font-weight: 800; color: #ec4899;">$${parseFloat(product.price).toFixed(2)}</div>
                    </div>`;
                }

                html += `</div>`;
            });

            html += '</div>';
            container.innerHTML = html;
            container.className = '';
        }

        function updatePreview() {
            renderTemplate();
        }

        function initializeBarcodes() {
            document.querySelectorAll('.barcode').forEach(function(svg) {
                const barcodeValue = svg.getAttribute('data-barcode');
                if (barcodeValue && !svg.querySelector('rect')) {
                    try {
                        JsBarcode(svg, barcodeValue, {
                            format: "CODE128",
                            width: 2,
                            height: 60,
                            displayValue: true,
                            fontSize: 14,
                            margin: 10,
                            background: "#f9fafb"
                        });
                    } catch (e) {
                        console.error('Error generando código de barras:', e);
                    }
                }
            });
        }

        function initializeQRCodes() {
            products.forEach(product => {
                for (let copy = 0; copy < copiesPerProduct; copy++) {
                    const qrElement = document.getElementById('qr-' + product.id + '-copy-' + copy);
                    if (qrElement && !qrElement.hasChildNodes()) {
                        new QRCode(qrElement, {
                            text: "{{ url('/productos') }}/" + product.slug,
                            width: 80,
                            height: 80,
                            colorDark: "#1f2937",
                            colorLight: "#ffffff",
                            correctLevel: QRCode.CorrectLevel.H
                        });
                    }
                }
            });
        }

        function changeFormat(format) {
            if (currentTemplate !== 'labels') return;

            const grid = document.getElementById('labelsGrid');
            const buttons = document.querySelectorAll('.format-btn');

            grid.classList.remove('format-small', 'format-medium', 'format-large', 'format-thermal-58', 'format-thermal-80');

            if (format === 'thermal58') {
                grid.classList.add('format-thermal-58');
            } else if (format === 'thermal80') {
                grid.classList.add('format-thermal-80');
            } else if (format !== 'medium') {
                grid.classList.add('format-' + format);
            }

            buttons.forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
        }

        function setCopies(copies) {
            copiesPerProduct = parseInt(copies);

            // Actualizar botones activos
            document.querySelectorAll('.copy-btn').forEach(btn => btn.classList.remove('active'));
            const matchingBtn = document.querySelector(`.copy-btn[data-copies="${copies}"]`);
            if (matchingBtn) {
                matchingBtn.classList.add('active');
                document.getElementById('custom-copies').value = '';
            } else {
                document.getElementById('custom-copies').value = copies;
            }

            // Actualizar contador de etiquetas totales
            const totalLabels = products.length * copiesPerProduct;
            document.getElementById('totalLabels').textContent = totalLabels;

            // Actualizar vista previa
            updatePreview();
        }
    </script>
</body>
</html>
