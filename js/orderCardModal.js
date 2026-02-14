// Store order data
let orderData = {};
let currentOrderId = null;
let currentOrderDetails = null; // For storing complete order details for printing

function initializeOrderData(data) {
    orderData = data;
}

function openModal(orderId) {
    fetch(`../controllers/ordersController.php`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=getOrderDetails&order_id=${orderId}`
    })
    .then(response => response.json())
    .then(data => {
        if (!data.success) {
            alert('Error loading order details');
            return;
        }

        const order = data.order;
        currentOrderId = order.order_id;

        // Store complete order details for printing
        currentOrderDetails = {
            order: order,
            items: data.items || []
        };

        // Get initials
        const nameParts = order.customer_name.split(' ');
        const initials = nameParts.map(part => part.charAt(0).toUpperCase()).join('');

        // Status colors
        const statusColors = {
            'pending': 'bg-yellow-600',
            'preparing': 'bg-blue-600',
            'ready': 'bg-green-600',
            'delivered': 'bg-teal-600',
            'cancelled': 'bg-red-600'
        };

        // Avatar colors
        const avatarColors = ['bg-blue-600', 'bg-green-500', 'bg-yellow-600', 'bg-purple-600', 'bg-pink-600', 'bg-indigo-600'];
        const avatarColor = avatarColors[order.order_id % avatarColors.length];

        // Update modal content
        document.getElementById('modalOrderNumber').textContent = `Order #${order.order_id}`;
        document.getElementById('modalStatus').textContent = order.order_status;
        document.getElementById('modalStatus').className = `${statusColors[order.order_status]} text-white text-xs px-3 py-1 rounded-full capitalize`;
        
        document.getElementById('modalAvatar').className = `w-12 h-12 ${avatarColor} rounded-full flex items-center justify-center flex-shrink-0`;
        document.getElementById('modalInitials').textContent = initials;
        document.getElementById('modalCustomerName').textContent = order.customer_name;
        document.getElementById('modalCustomerEmail').textContent = order.email;
        
        document.getElementById('modalOrderId').textContent = `#${order.order_id}`;
        document.getElementById('modalRoom').textContent = order.room_number;
        document.getElementById('modalTotal').textContent = `₱${parseFloat(order.total_amount).toFixed(2)}`;
        document.getElementById('modalPaymentStatus').textContent = order.payment_status;
        document.getElementById('modalOrderedAt').textContent = new Date(order.ordered_at).toLocaleString();
        document.getElementById('modalInstructions').textContent = order.special_instructions || 'No special instructions';

        // Handle delivered_at
        if (order.delivered_at) {
            document.getElementById('modalDeliveredAtContainer').style.display = 'flex';
            document.getElementById('modalDeliveredAt').textContent = new Date(order.delivered_at).toLocaleString();
        } else {
            document.getElementById('modalDeliveredAtContainer').style.display = 'none';
        }

        // Set current status in select
        document.getElementById('statusSelect').value = order.order_status;

        // Load order items
        const itemsContainer = document.getElementById('modalOrderItems');
        if (data.items && data.items.length > 0) {
            itemsContainer.innerHTML = data.items.map(item => `
                <div class="flex justify-between items-start border-b border-gray-600 pb-2 last:border-0">
                    <div class="flex-1">
                        <p class="text-white font-medium">${item.menu_name}</p>
                        <p class="text-gray-400 text-xs">Qty: ${item.quantity} × ₱${parseFloat(item.price).toFixed(2)}</p>
                        ${item.notes ? `<p class="text-gray-500 text-xs italic">${item.notes}</p>` : ''}
                    </div>
                    <span class="text-white font-semibold">₱${parseFloat(item.subtotal).toFixed(2)}</span>
                </div>
            `).join('');
        } else {
            itemsContainer.innerHTML = '<p class="text-gray-400 text-sm">No items found</p>';
        }

        // Show modal
        document.getElementById('orderModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error loading order details');
    });
}

function closeModal() {
    document.getElementById('orderModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    currentOrderId = null;
}

function updateOrderStatus() {
    if (!currentOrderId) return;

    const newStatus = document.getElementById('statusSelect').value;

    if (confirm(`Are you sure you want to mark this order as "${newStatus}"?`)) {
        fetch('../controllers/ordersController.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=updateStatus&order_id=${currentOrderId}&status=${newStatus}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Order status updated successfully!');
                location.reload();
            } else {
                alert('Error updating order status: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error updating order status');
        });
    }
}

function printReceipt() {
    if (!currentOrderDetails || !currentOrderDetails.order) {
        alert("Please select an order first");
        return;
    }
    
    const order = currentOrderDetails.order;
    const items = currentOrderDetails.items;
    
    // Calculate tax (12% VAT)
    const taxRate = 0.12;
    const subtotal = parseFloat(order.total_amount);
    const taxAmount = subtotal * taxRate;
    const totalWithTax = subtotal + taxAmount;
    
    // Capitalize status
    const capitalizeStatus = (status) => {
        return status.charAt(0).toUpperCase() + status.slice(1);
    };
    
    // Generate items HTML
    const generateItemsHTML = (items) => {
        if (!items || items.length === 0) {
            return '<tr><td colspan="4" style="text-align: center;">No items</td></tr>';
        }
        
        return items.map(item => `
            <tr>
                <td>${item.menu_name}${item.notes ? `<br><small style="font-size: 10px; font-style: italic;">${item.notes}</small>` : ''}</td>
                <td class="qty">${item.quantity}</td>
                <td class="price">₱${parseFloat(item.price).toFixed(2)}</td>
                <td class="total">₱${parseFloat(item.subtotal).toFixed(2)}</td>
            </tr>
        `).join('');
    };
    
    // Build receipt HTML
    const receiptHTML = `
        <!DOCTYPE html>
        <html>
        <head>
            <title>Receipt - Order #${order.order_id}</title>
            <meta charset="UTF-8">
            <style>
                * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }
                
                body {
                    font-family: 'Courier New', monospace;
                    padding: 20px;
                    max-width: 400px;
                    margin: 0 auto;
                    background: #f5f5f5;
                }
                
                .receipt {
                    background: white;
                    border: 2px solid #000;
                    padding: 20px;
                    box-shadow: 0 0 10px rgba(0,0,0,0.1);
                }
                
                .header {
                    text-align: center;
                    border-bottom: 2px dashed #000;
                    padding-bottom: 15px;
                    margin-bottom: 15px;
                }
                
                .header h1 {
                    font-size: 24px;
                    margin-bottom: 5px;
                    letter-spacing: 2px;
                }
                
                .header p {
                    font-size: 12px;
                    margin: 2px 0;
                }
                
                .section {
                    margin-bottom: 15px;
                    padding-bottom: 15px;
                    border-bottom: 1px dashed #000;
                }
                
                .section:last-child {
                    border-bottom: none;
                }
                
                .info-row {
                    display: flex;
                    margin-bottom: 5px;
                }
                
                .label {
                    font-weight: bold;
                    width: 120px;
                    flex-shrink: 0;
                }
                
                .value {
                    flex: 1;
                    word-wrap: break-word;
                }
                
                .items-table {
                    width: 100%;
                    margin: 10px 0;
                    border-collapse: collapse;
                }
                
                .items-table th {
                    text-align: left;
                    border-bottom: 1px solid #000;
                    padding: 5px 2px;
                    font-size: 12px;
                }
                
                .items-table td {
                    padding: 8px 2px;
                    vertical-align: top;
                    font-size: 11px;
                }
                
                .items-table .qty {
                    text-align: center;
                    width: 40px;
                }
                
                .items-table .price,
                .items-table .total {
                    text-align: right;
                    width: 70px;
                }
                
                .totals {
                    margin-top: 15px;
                }
                
                .totals-row {
                    display: flex;
                    justify-content: space-between;
                    padding: 3px 0;
                    font-size: 14px;
                }
                
                .totals-row.grand-total {
                    font-size: 18px;
                    font-weight: bold;
                    border-top: 2px solid #000;
                    padding-top: 8px;
                    margin-top: 8px;
                }
                
                .payment-status {
                    text-align: center;
                    padding: 10px;
                    background: #f0f0f0;
                    border-radius: 5px;
                    font-weight: bold;
                }
                
                .payment-status.paid {
                    background: #d4edda;
                    color: #155724;
                }
                
                .payment-status.pending {
                    background: #fff3cd;
                    color: #856404;
                }
                
                .footer {
                    text-align: center;
                    margin-top: 20px;
                    font-size: 11px;
                    color: #666;
                }
                
                .footer p {
                    margin: 3px 0;
                }
                
                .action-buttons {
                    text-align: center;
                    margin-top: 20px;
                    padding: 20px;
                }
                
                .btn {
                    padding: 12px 30px;
                    font-size: 16px;
                    cursor: pointer;
                    border: none;
                    border-radius: 5px;
                    margin: 0 5px;
                    font-family: Arial, sans-serif;
                }
                
                .btn-print {
                    background: #007bff;
                    color: white;
                }
                
                .btn-print:hover {
                    background: #0056b3;
                }
                
                .btn-close {
                    background: #6c757d;
                    color: white;
                }
                
                .btn-close:hover {
                    background: #545b62;
                }
                
                @media print {
                    body {
                        padding: 0;
                        background: white;
                    }
                    
                    .receipt {
                        border: none;
                        box-shadow: none;
                    }
                    
                    .action-buttons {
                        display: none;
                    }
                }
            </style>
        </head>
        <body>
            <div class="receipt">
                <!-- Header -->
                <div class="header">
                    <h1>FOOD ORDER</h1>
                    <p>═══ OFFICIAL RECEIPT ═══</p>
                    <p>Thank you for your order!</p>
                </div>
                
                <!-- Order Info -->
                <div class="section">
                    <h3 style="margin-bottom: 8px; font-size: 14px;">ORDER INFORMATION</h3>
                    <div class="info-row">
                        <span class="label">Receipt No:</span>
                        <span class="value">#${String(order.order_id).padStart(6, '0')}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Date & Time:</span>
                        <span class="value">${new Date(order.ordered_at).toLocaleString()}</span>
                    </div>
                    <div class="info-row">
                        <span class="label">Order Status:</span>
                        <span class="value">${capitalizeStatus(order.order_status)}</span>
                    </div>
                    ${order.delivered_at ? `
                    <div class="info-row">
                        <span class="label">Delivered:</span>
                        <span class="value">${new Date(order.delivered_at).toLocaleString()}</span>
                    </div>
                    ` : ''}
                </div>
                
                <!-- Customer Info -->
                <div class="section">
                    <h3 style="margin-bottom: 8px; font-size: 14px;">CUSTOMER DETAILS</h3>
                    <div class="info-row">
                        <span class="label">Name:</span>
                        <span class="value">${order.customer_name}</span>
                    </div>
                    
                    <div class="info-row">
                        <span class="label">Room Number:</span>
                        <span class="value">${order.room_number || 'N/A'}</span>
                    </div>
                </div>
                
                <!-- Order Items -->
                <div class="section">
                    <h3 style="margin-bottom: 10px; font-size: 14px;">ORDER ITEMS</h3>
                    <table class="items-table">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th class="qty">Qty</th>
                                <th class="price">Price</th>
                                <th class="total">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${generateItemsHTML(items)}
                        </tbody>
                    </table>
                </div>
                
                <!-- Special Instructions -->
                ${order.special_instructions ? `
                <div class="section">
                    <h3 style="margin-bottom: 5px; font-size: 14px;">SPECIAL INSTRUCTIONS</h3>
                    <div style="margin-top: 5px; font-style: italic; font-size: 12px; padding: 8px; background: #f9f9f9; border-left: 3px solid #333;">
                        ${order.special_instructions}
                    </div>
                </div>
                ` : ''}
                
                <!-- Totals -->
                <div class="section">
                    <div class="totals">
                        <div class="totals-row">
                            <span>Subtotal:</span>
                            <span>₱${subtotal.toFixed(2)}</span>
                        </div>
                        <div class="totals-row">
                            <span>Tax (12% VAT):</span>
                            <span>₱${taxAmount.toFixed(2)}</span>
                        </div>
                        <div class="totals-row grand-total">
                            <span>TOTAL AMOUNT:</span>
                            <span>₱${totalWithTax.toFixed(2)}</span>
                        </div>
                    </div>
                </div>
                
                <!-- Payment Status -->
                <div class="section">
                    <div class="payment-status ${order.payment_status.toLowerCase()}">
                        Payment Status: ${capitalizeStatus(order.payment_status)}
                    </div>
                </div>
                
                <!-- Footer -->
                <div class="footer">
                    <p>━━━━━━━━━━━━━━━━━━━━━━━━━━━━</p>
                    <p>This is a computer-generated receipt.</p>
                    <p>No signature required.</p>
                    <p style="margin-top: 8px;">For inquiries, please contact support.</p>
                    <p style="margin-top: 8px; font-size: 10px;">Printed: ${new Date().toLocaleString()}</p>
                </div>
            </div>
            
            <div class="action-buttons">
                <button class="btn btn-print" onclick="window.print()">🖨️ Print Receipt</button>
                <button class="btn btn-close" onclick="window.close()">✕ Close</button>
            </div>
        </body>
        </html>
    `;
    
    // Open print window
    const printWindow = window.open('', '_blank', 'width=800,height=900');
    
    if (!printWindow) {
        alert('Please allow popups to print the receipt');
        return;
    }
    
    printWindow.document.write(receiptHTML);
    printWindow.document.close();
    
    // Auto print after window loads
    printWindow.onload = function() {
        setTimeout(() => {
            printWindow.focus();
            printWindow.print();
        }, 250);
    };
}

// Initialize event listeners when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Close modal when clicking outside
    document.getElementById('orderModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });

    // Close modal with ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });

    // Print Receipt Button Event Listener
    const printBtn = document.getElementById('printReceipt');
    if (printBtn) {
        printBtn.addEventListener('click', printReceipt);
    }

    // Auto refresh every 30 seconds
    setInterval(() => {
        location.reload();
    }, 30000);
});