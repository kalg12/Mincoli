<x-layouts.app :title="__('Detalle de Cotización')">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <div class="flex-1" x-data="quotationDetailManager()">
        <div class="border-b border-zinc-200 bg-white px-6 py-4 dark:border-zinc-700 dark:bg-zinc-900">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">Detalle de Cotización</h1>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">Folio: {{ $quotation->folio }}</p>
                </div>
                <a href="{{ route('dashboard.pos.quotations.index') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-zinc-600 hover:bg-zinc-700 text-white text-sm font-semibold transition-all">
                    <i class="fas fa-arrow-left"></i>
                    <span>Volver</span>
                </a>
            </div>
        </div>

        <div class="p-6">
            <div class="max-w-4xl mx-auto">
                <!-- Header Card -->
                <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-6 mb-6">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <h2 class="text-lg font-black text-zinc-900 dark:text-white">Cotización: {{ $quotation->folio }}</h2>
                            <p class="text-xs text-zinc-500 uppercase tracking-widest mt-1">{{ $quotation->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="flex gap-2">
                            <button @click="shareWhatsApp(@js($quotation))" 
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold transition-all">
                                <i class="fab fa-whatsapp"></i>
                                <span>WhatsApp</span>
                            </button>
                            <button @click="exportQuotation(@js($quotation), 'copy')" 
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-pink-600 hover:bg-pink-700 text-white text-sm font-semibold transition-all">
                                <i class="fas fa-copy"></i>
                                <span>Copiar</span>
                            </button>
                            <button @click="exportQuotation(@js($quotation), 'pdf')" 
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white text-sm font-semibold transition-all">
                                <i class="fas fa-file-pdf"></i>
                                <span>PDF</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Customer and General Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div class="bg-zinc-950 dark:bg-zinc-800 p-4 rounded-xl border border-zinc-800">
                        <h3 class="text-[10px] font-black uppercase text-zinc-500 tracking-widest mb-2">Cliente</h3>
                        <p class="text-sm font-bold text-white">{{ $quotation->customer_name }}</p>
                        <p class="text-xs text-zinc-500">{{ $quotation->customer_phone ?? 'Sin teléfono' }}</p>
                    </div>
                    <div class="bg-zinc-950 dark:bg-zinc-800 p-4 rounded-xl border border-zinc-800">
                        <h3 class="text-[10px] font-black uppercase text-zinc-500 tracking-widest mb-2">Información Operativa</h3>
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-[10px] text-zinc-500">Vendedor:</span>
                            <span class="text-xs font-bold text-zinc-300">{{ $quotation->user->name ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-[10px] text-zinc-500">Medio:</span>
                            <span class="text-xs font-bold text-zinc-300 uppercase">{{ $quotation->share_type }}</span>
                        </div>
                        <div class="flex justify-between items-center mt-1">
                            <span class="text-[10px] text-zinc-500">Estado:</span>
                            <span class="inline-flex rounded-full px-2 py-1 text-[10px] font-black uppercase tracking-wider
                                {{ $quotation->status === 'sent' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 
                                   ($quotation->status === 'accepted' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : 
                                   ($quotation->status === 'converted' ? 'bg-pink-100 text-pink-700 dark:bg-pink-900/30 dark:text-pink-400' : 
                                   ($quotation->status === 'expired' ? 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400' :
                                   'bg-zinc-100 text-zinc-700 dark:bg-zinc-800 dark:text-zinc-400'))) }}">
                                {{ $quotation->status_label }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Items Table -->
                <div class="bg-zinc-950 dark:bg-zinc-800 rounded-xl border border-zinc-800 overflow-hidden mb-6">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-zinc-900 text-zinc-500">
                            <tr>
                                <th class="px-4 py-2 uppercase font-black">Producto</th>
                                <th class="px-4 py-2 uppercase font-black text-center">Cant.</th>
                                <th class="px-4 py-2 uppercase font-black text-right">Precio</th>
                                <th class="px-4 py-2 uppercase font-black text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-900 text-zinc-300">
                            @foreach($quotation->items as $item)
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="font-bold text-zinc-200">{{ $item->product->name ?? 'Producto' }}</div>
                                    @if($item->variant)
                                    <div class="text-[9px] text-pink-500/70 font-black uppercase">{{ $item->variant->name }}</div>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center font-bold">{{ $item->quantity }}</td>
                                <td class="px-4 py-3 text-right">${{ number_format($item->unit_price, 2) }}</td>
                                <td class="px-4 py-3 text-right font-black text-white">${{ number_format($item->total, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Totals -->
                <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-6">
                    <div class="flex justify-end pt-4 border-t border-zinc-800">
                        <div class="w-48 space-y-2">
                            <div class="flex justify-between text-xs">
                                <span class="text-zinc-500 font-bold uppercase">Subtotal:</span>
                                <span class="text-zinc-300">${{ number_format($quotation->subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-xs">
                                <span class="text-zinc-500 font-bold uppercase">IVA (16%):</span>
                                <span class="text-zinc-300">${{ number_format($quotation->iva_total, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-lg pt-2 border-t border-zinc-900">
                                <span class="font-black text-white uppercase tracking-tighter">Total:</span>
                                <span class="font-black text-pink-500">${{ number_format($quotation->total, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function quotationDetailManager() {
            return {
                isExporting: false,
                paymentMethods: @json($paymentMethods),

                shareWhatsApp(q) {
                    let text = `*COTIZACIÓN MINCOLI*\n`;
                    text += `Folio: *${q.folio}*\n`;
                    text += `Cliente: ${q.customer_name}\n`;
                    text += `Fecha: ${new Date(q.created_at).toLocaleDateString()}\n`;
                    text += `--------------------------\n`;
                    
                    if (q.items && q.items.length > 0) {
                        q.items.forEach(item => {
                            const name = item.product ? item.product.name : 'Producto';
                            const variant = item.variant ? ` (${item.variant.name})` : '';
                            text += `• ${item.quantity}x ${name}${variant} - $${parseFloat(item.total).toLocaleString()}\n`;
                        });
                    }
                    
                    text += `--------------------------\n`;
                    text += `*TOTAL: $${parseFloat(q.total).toLocaleString()}*\n\n`;
                    text += `Lo atendió: ${q.user ? q.user.name : 'Vendedor'}\n`;
                    text += `¡Gracias por tu preferencia!`;
                    
                    const encodedText = encodeURIComponent(text);
                    const phone = q.customer_phone ? q.customer_phone.replace(/\D/g, '') : '';
                    if (phone && phone.length >= 10) {
                        window.open(`https://wa.me/52${phone}?text=${encodedText}`, '_blank');
                    } else {
                        window.open(`https://wa.me/?text=${encodedText}`, '_blank');
                    }
                },

                async exportQuotation(q, type) {
                    this.isExporting = true;
                    try {
                        const html = this.generateHTML(q);
                        const tempDiv = document.createElement('div');
                        tempDiv.innerHTML = html;
                        tempDiv.style.position = 'absolute';
                        tempDiv.style.left = '-9999px';
                        tempDiv.style.top = '-9999px';
                        tempDiv.style.width = '650px';
                        tempDiv.style.backgroundColor = '#ffffff';
                        document.body.appendChild(tempDiv);

                        const canvas = await html2canvas(tempDiv, {
                            scale: 2,
                            backgroundColor: '#ffffff',
                            logging: false,
                            useCORS: true,
                            width: 650,
                            windowWidth: 650
                        });
                        document.body.removeChild(tempDiv);

                        if (type === 'pdf') {
                            const imgData = canvas.toDataURL('image/jpeg', 0.95);
                            const { jsPDF } = window.jspdf;
                            const pdf = new jsPDF({ orientation: 'portrait', unit: 'mm', format: 'a4' });
                            const pdfWidth = pdf.internal.pageSize.getWidth();
                            const imgHeight = (canvas.height * pdfWidth) / canvas.width;
                            pdf.addImage(imgData, 'JPEG', 10, 10, pdfWidth - 20, imgHeight);
                            pdf.save(`Cotizacion_${q.folio}.pdf`);
                        } else if (type === 'copy') {
                            canvas.toBlob(async (blob) => {
                                if (navigator.clipboard && navigator.clipboard.write) {
                                    const data = [new ClipboardItem({ [blob.type]: blob })];
                                    await navigator.clipboard.write(data);
                                    alert('¡Imagen de Cotización copiada al portapapeles!');
                                } else {
                                    const link = document.createElement('a');
                                    link.download = `Cotizacion_${q.folio}.jpg`;
                                    link.href = canvas.toDataURL('image/jpeg', 0.95);
                                    link.click();
                                }
                            }, 'image/png');
                        }
                    } catch (e) {
                        console.error('Export error', e);
                        alert('Error al generar archivo');
                    } finally {
                        this.isExporting = false;
                    }
                },

                generateHTML(q) {
                    const dateStr = new Date(q.created_at).toLocaleString('es-MX', { dateStyle: 'long', timeStyle: 'short' });
                    const itemsHTML = q.items.map((item, index) => `
                        <tr style="background-color: ${index % 2 === 0 ? '#ffffff' : '#f9fafb'};">
                            <td style="padding: 16px; border: 1px solid #e5e7eb; font-weight: 700; color: #111827;">
                                ${item.product ? item.product.name : 'Producto'}
                                ${item.variant ? `<br><small style="color: #6b7280;">Variante: ${item.variant.name}</small>` : ''}
                            </td>
                            <td style="padding: 16px; border: 1px solid #e5e7eb; text-align: center; font-weight: 700; color: #111827;">${item.quantity}</td>
                            <td style="padding: 16px; border: 1px solid #e5e7eb; text-align: right; font-weight: 700; color: #111827;">$${parseFloat(item.unit_price).toFixed(2)}</td>
                            <td style="padding: 16px; border: 1px solid #e5e7eb; text-align: right; font-weight: 900; color: #db2777;">$${parseFloat(item.total).toFixed(2)}</td>
                        </tr>
                    `).join('');

                    return `
                        <div style="width: 650px; padding: 32px; background-color: #ffffff; font-family: 'Inter', Arial, sans-serif;">
                            <!-- Header -->
                            <div style="border-bottom: 3px solid #ec4899; background-color: #fef2f2; margin: -32px -32px 24px -32px; padding: 32px; display: flex; justify-content: space-between; align-items: center;">
                                <div style="display: flex; align-items: center; gap: 16px;">
                                    <div style="width: 80px; height: 80px; background-color: #ec4899; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                        <span style="color: #ffffff; font-weight: 900; font-size: 24px;">M</span>
                                    </div>
                                    <div>
                                        <h1 style="font-size: 24px; font-weight: 900; color: #111827; margin: 0 0 4px 0;">MINCOLI</h1>
                                        <p style="font-size: 14px; color: #4b5563; margin: 0;">Tienda Online • Moda y Accesorios</p>
                                    </div>
                                </div>
                                <div style="text-align: right;">
                                    <div style="background-color: #fdf2f8; border: 2px solid #f9a8d4; border-radius: 8px; padding: 8px 16px; margin-bottom: 8px;">
                                        <h2 style="font-size: 18px; font-weight: 900; color: #db2777; margin: 0;">COTIZACIÓN</h2>
                                    </div>
                                    <p style="font-size: 12px; color: #6b7280; margin: 0;">${dateStr}</p>
                                    <p style="font-size: 10px; color: #9ca3af; margin: 4px 0 0 0;">FOLIO: ${q.folio}</p>
                                </div>
                            </div>

                            <!-- Customer Info -->
                            <div style="display: flex; gap: 32px; margin-bottom: 24px;">
                                <div style="flex: 1; background-color: #f9fafb; border-radius: 8px; padding: 16px;">
                                    <h3 style="font-size: 14px; font-weight: 900; color: #374151; margin: 0 0 12px 0; text-transform: uppercase;">CLIENTE</h3>
                                    <p style="font-size: 16px; font-weight: 700; color: #111827; margin: 0 0 4px 0;">${q.customer_name}</p>
                                    <p style="font-size: 14px; color: #4b5563; margin: 0;">${q.customer_phone || 'Sin teléfono'}</p>
                                </div>
                                <div style="flex: 1; background-color: #f9fafb; border-radius: 8px; padding: 16px;">
                                    <h3 style="font-size: 14px; font-weight: 900; color: #374151; margin: 0 0 12px 0; text-transform: uppercase;">MÉTODOS DE PAGO</h3>
                                    ${this.paymentMethods.filter(m => !m.name.toLowerCase().includes('mercado')).map((method, index) => `
                                        <div style="margin-bottom: ${index === this.paymentMethods.filter(m => !m.name.toLowerCase().includes('mercado')).length - 1 ? '0' : '8px'}; background-color: #ffffff; border-radius: 4px; padding: 8px; border: 1px solid #e5e7eb;">
                                            <p style="font-size: 11px; font-weight: 900; color: #374151; margin: 0 0 2px 0;">${method.name}</p>
                                            <p style="font-size: 13px; font-weight: 700; color: #111827; margin: 0;">${method.supports_card_number && method.card_number ? method.card_number : (method.code || 'N/A')}</p>
                                        </div>
                                    `).join('')}
                                </div>
                            </div>

                            <!-- Products Table -->
                            <div style="margin-bottom: 24px;">
                                <div style="background-color: #111827; color: #ffffff; padding: 12px 16px; border-radius: 8px 8px 0 0;">
                                    <h3 style="font-size: 14px; font-weight: 900; margin: 0; text-transform: uppercase;">DETALLE DE PRODUCTOS</h3>
                                </div>
                                <table style="width: 100%; border: 2px solid #d1d5db; border-collapse: collapse; border-top: none;">
                                    <thead>
                                        <tr style="background-color: #f3f4f6;">
                                            <th style="padding: 12px 16px; border: 1px solid #d1d5db; text-align: left; font-size: 12px; font-weight: 900; color: #374151;">PRODUCTO</th>
                                            <th style="padding: 12px 16px; border: 1px solid #d1d5db; text-align: center; font-size: 12px; font-weight: 900; color: #374151;">CANT.</th>
                                            <th style="padding: 12px 16px; border: 1px solid #d1d5db; text-align: right; font-size: 12px; font-weight: 900; color: #374151;">PRECIO</th>
                                            <th style="padding: 12px 16px; border: 1px solid #d1d5db; text-align: right; font-size: 12px; font-weight: 900; color: #374151;">TOTAL</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${itemsHTML}
                                    </tbody>
                                </table>
                            </div>

                            <!-- Total -->
                            <div style="text-align: right; margin-bottom: 0; margin-top: 32px; padding-bottom: 60px;">
                                <div style="display: flex; justify-content: flex-end; align-items: baseline; gap: 16px;">
                                    <span style="font-size: 16px; font-weight: 900; color: #111827; text-transform: uppercase;">TOTAL A PAGAR:</span>
                                    <span style="font-size: 32px; font-weight: 900; color: #db2777;">$${parseFloat(q.total).toFixed(2)}</span>
                                </div>
                            </div>

                            <!-- Footer -->
                            <div style="text-align: center; padding-top: 50px; margin-top: 0; border-top: 2px solid #d1d5db;">
                                <div style="margin-bottom: 20px;">
                                    <p style="font-size: 18px; font-weight: 900; color: #111827; margin: 0 0 12px 0;">¡Gracias por tu preferencia!</p>
                                    <p style="font-size: 14px; color: #4b5563; margin: 0 0 12px 0;">Te esperamos pronto en</p>
                                    <p style="font-size: 20px; font-weight: 900; color: #db2777; margin: 0 0 16px 0;">mincoli.com</p>
                                </div>
                                <div style="font-size: 12px; color: #6b7280; padding-top: 8px;">
                                    <p style="margin: 0 0 6px 0;">📱 WhatsApp para pedidos: +52 56 1170 11660</p>
                                    <p style="margin: 0;">📍 Envíos a toda la República Mexicana</p>
                                </div>
                            </div>
                        </div>
                    `;
                }
            }
        }
    </script>
</x-layouts.app>
