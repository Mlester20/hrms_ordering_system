let orderData = {};
let currentOrderId = null;
let currentOrderDetails = null;

// Initialize order data
function initializeOrderData(data) {
    orderData = data;
}

function openModal(orderId) {
    const data = orderData[orderId];
    if (!data) {
        console.error('Order not found:', orderId);
        return;
    }

    currentOrderId = data.orderId;

    // Update modal content
    document.getElementById('modalRoomNumber').textContent = 'Room ' + data.roomNumber;
    document.getElementById('modalStatus').textContent = data.status;
    document.getElementById('modalStatus').className = `${data.statusColor} text-white text-xs px-3 py-1 rounded-full capitalize`;
    
    document.getElementById('modalAvatar').className = `w-12 h-12 ${data.avatarColor} rounded-full flex items-center justify-center`;
    document.getElementById('modalInitials').textContent = data.initials;
    document.getElementById('modalCustomerName').textContent = data.customerName;
    document.getElementById('modalCustomerEmail').textContent = data.customerEmail;
    
    document.getElementById('modalOrderId').textContent = '#' + data.orderId;
    document.getElementById('modalRoom').textContent = data.roomNumber;
    document.getElementById('modalDate').textContent = data.orderedAt;
    document.getElementById('modalPayment').textContent = data.paymentStatus;
    document.getElementById('modalTotal').textContent = data.totalAmount;
    document.getElementById('modalInstructions').textContent = data.specialInstructions;

    // Load order items via AJAX
    loadOrderItems(data.orderId);

    // Show modal
    document.getElementById('orderModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    document.getElementById('orderModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    currentOrderId = null;
}

function loadOrderItems(orderId) {
    fetch(`../controllers/getOrderItems.php?order_id=${orderId}`)
        .then(response => response.json())
        .then(data => {
            // Store the complete order details for printing
            currentOrderDetails = {
                order: orderData[orderId],
                items: data.items || []
            };
            
            const itemsContainer = document.getElementById('modalItems');
            if(data.success && data.items.length > 0) {
                itemsContainer.innerHTML = data.items.map(item => `
                    <div class="flex justify-between items-start border-b border-gray-600 pb-2">
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
        })
        .catch(error => {
            console.error('Error loading items:', error);
            document.getElementById('modalItems').innerHTML = '<p class="text-red-400 text-sm">Error loading items</p>';
        });
}

function updateOrderStatus(newStatus) {
    if(!currentOrderId) return;

    if(confirm(`Are you sure you want to mark this order as "${newStatus}"?`)) {
        fetch('../controllers/updateOrderStatus.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `order_id=${currentOrderId}&status=${newStatus}`
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
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
