// Global variable to store current order ID
let currentOrderId = null;

/**
 * Open modal and load order details
 */
function openModal(orderId) {
    currentOrderId = orderId;
    
    // Show loading state
    document.getElementById('orderModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Fetch order details via AJAX
    fetchOrderDetails(orderId);
}

/**
 * Close modal
 */
function closeModal() {
    document.getElementById('orderModal').classList.add('hidden');
    document.body.style.overflow = 'auto';
    currentOrderId = null;
}

/**
 * Fetch order details from server
 */
function fetchOrderDetails(orderId) {
    const formData = new FormData();
    formData.append('action', 'getOrderDetails');
    formData.append('order_id', orderId);
    
    fetch('../controllers/ordersController.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        // Check if response is JSON
        const contentType = response.headers.get('content-type');
        if (!contentType || !contentType.includes('application/json')) {
            throw new TypeError('Server did not return JSON. Check PHP errors in ordersController.php');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            populateModal(data.order, data.items);
        } else {
            showError(data.message || 'Failed to load order details');
            closeModal();
        }
    })
    .catch(error => {
        console.error('Error fetching order details:', error);
        showError('Error: ' + error.message + '. Check browser console for details.');
        closeModal();
    });
}

/**
 * Populate modal with order data
 */
function populateModal(order, items) {
    // Get initials from customer name
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
    
    const avatarColors = ['bg-blue-600', 'bg-green-500', 'bg-yellow-600', 'bg-purple-600', 'bg-pink-600', 'bg-indigo-600'];
    const avatarColor = avatarColors[Math.floor(Math.random() * avatarColors.length)];
    
    // Update modal header
    document.getElementById('modalOrderNumber').textContent = `Order #${order.order_id}`;
    document.getElementById('modalStatus').textContent = order.order_status.charAt(0).toUpperCase() + order.order_status.slice(1);
    document.getElementById('modalStatus').className = `${statusColors[order.order_status] || 'bg-gray-600'} text-white text-xs px-3 py-1 rounded-full capitalize`;
    
    // Update customer info
    document.getElementById('modalAvatar').className = `w-12 h-12 ${avatarColor} rounded-full flex items-center justify-center`;
    document.getElementById('modalInitials').textContent = initials;
    document.getElementById('modalCustomerName').textContent = order.customer_name;
    document.getElementById('modalCustomerEmail').textContent = order.email;
    
    // Update order details
    document.getElementById('modalOrderId').textContent = `#${order.order_id}`;
    document.getElementById('modalRoom').textContent = order.room_number;
    document.getElementById('modalTotal').textContent = `₱${parseFloat(order.total_amount).toFixed(2)}`;
    document.getElementById('modalPaymentStatus').textContent = order.payment_status.charAt(0).toUpperCase() + order.payment_status.slice(1);
    document.getElementById('modalOrderedAt').textContent = formatDateTime(order.ordered_at);
    
    // Show/hide delivered_at based on status
    if (order.delivered_at) {
        document.getElementById('modalDeliveredAtContainer').style.display = 'flex';
        document.getElementById('modalDeliveredAt').textContent = formatDateTime(order.delivered_at);
    } else {
        document.getElementById('modalDeliveredAtContainer').style.display = 'none';
    }
    
    // Update special instructions
    document.getElementById('modalInstructions').textContent = order.special_instructions || 'No special instructions';
    
    // Update order items
    populateOrderItems(items);
    
    // Set current status in select
    document.getElementById('statusSelect').value = order.order_status;
}

/**
 * Populate order items list
 */
function populateOrderItems(items) {
    const container = document.getElementById('modalOrderItems');
    container.innerHTML = '';
    
    if (items.length === 0) {
        container.innerHTML = '<p class="text-gray-400 text-sm">No items in this order</p>';
        return;
    }
    
    items.forEach(item => {
        const itemDiv = document.createElement('div');
        itemDiv.className = 'flex justify-between items-start py-2 border-b border-gray-600 last:border-0';
        itemDiv.innerHTML = `
            <div class="flex-1">
                <p class="text-white font-medium">${escapeHtml(item.menu_name)}</p>
                <p class="text-gray-400 text-xs">Qty: ${item.quantity} × ₱${parseFloat(item.price).toFixed(2)}</p>
                ${item.notes ? `<p class="text-gray-400 text-xs italic mt-1">${escapeHtml(item.notes)}</p>` : ''}
            </div>
            <div class="text-white font-medium">
                ₱${parseFloat(item.subtotal).toFixed(2)}
            </div>
        `;
        container.appendChild(itemDiv);
    });
}

/**
 * Update order status
 */
function updateOrderStatus() {
    if (!currentOrderId) {
        showError('No order selected');
        return;
    }
    
    const newStatus = document.getElementById('statusSelect').value;
    
    // Confirm update
    if (!confirm(`Are you sure you want to update the order status to "${newStatus}"?`)) {
        return;
    }
    
    const formData = new FormData();
    formData.append('action', 'updateStatus');
    formData.append('order_id', currentOrderId);
    formData.append('status', newStatus);
    
    fetch('../controllers/ordersController.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccess('Order status updated successfully');
            // Reload the page to reflect changes
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showError(data.message || 'Failed to update order status');
        }
    })
    .catch(error => {
        console.error('Error updating order status:', error);
        showError('An error occurred while updating order status');
    });
}

/**
 * Format datetime string
 */
function formatDateTime(datetime) {
    if (!datetime) return '-';
    
    const date = new Date(datetime);
    const options = {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    };
    return date.toLocaleDateString('en-US', options);
}

/**
 * Escape HTML to prevent XSS
 */
function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, m => map[m]);
}

/**
 * Show success message
 */
function showSuccess(message) {
    // Create toast notification
    const toast = document.createElement('div');
    toast.className = 'fixed top-4 right-4 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-fade-in';
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.classList.add('animate-fade-out');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

/**
 * Show error message
 */
function showError(message) {
    // Create toast notification
    const toast = document.createElement('div');
    toast.className = 'fixed top-4 right-4 bg-red-600 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-fade-in';
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.classList.add('animate-fade-out');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Event Listeners

// Close modal when clicking outside
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('orderModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    }
});

// Close modal with ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('orderModal');
        if (modal && !modal.classList.contains('hidden')) {
            closeModal();
        }
    }
});