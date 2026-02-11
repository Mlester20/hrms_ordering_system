// Store order data
let orderData = {};
let currentOrderId = null;

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

    // Auto refresh every 30 seconds
    setInterval(() => {
        location.reload();
    }, 30000);
});