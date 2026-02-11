  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 p-4">
    <?php 
    if ($orders->num_rows > 0):
      while($order = $orders->fetch_assoc()): 
        // Get initials from customer name
        $nameParts = explode(' ', $order['customer_name']);
        $initials = '';
        foreach($nameParts as $part) {
            $initials .= strtoupper(substr($part, 0, 1));
        }

        // Determine status color and label
        $statusColors = [
            'pending' => 'bg-yellow-600',
            'preparing' => 'bg-blue-600',
            'ready' => 'bg-green-600',
            'delivered' => 'bg-teal-600',
            'cancelled' => 'bg-red-600'
        ];
        
        $statusColor = $statusColors[$order['order_status']] ?? 'bg-gray-600';
        
        // Consistent avatar colors based on order ID
        $avatarColors = ['bg-blue-600', 'bg-green-500', 'bg-yellow-600', 'bg-purple-600', 'bg-pink-600', 'bg-indigo-600'];
        $avatarColor = $avatarColors[$order['order_id'] % count($avatarColors)];
        
        // Show initials only for delivered/cancelled, show avatar for active orders
        $showAvatar = in_array($order['order_status'], ['pending', 'preparing', 'ready']);
    ?>
    
    <!-- Order Card -->
    <div class="bg-gray-800 rounded-lg p-6 relative cursor-pointer hover:bg-gray-700 transition-colors" 
        onclick="openModal(<?php echo $order['order_id']; ?>)">
      <div class="flex items-center justify-between mb-4">
        <h3 class="text-white font-semibold text-lg">Order #<?php echo $order['order_id']; ?></h3>
        <span class="<?php echo $statusColor; ?> text-white text-xs px-3 py-1 rounded-full capitalize">
          <?php echo $order['order_status']; ?>
        </span>
      </div>
      
      <div class="flex items-center justify-center h-24 mb-4">
        <?php if ($showAvatar): ?>
          <div class="w-16 h-16 <?php echo $avatarColor; ?> rounded-full flex items-center justify-center">
            <span class="text-white font-bold text-lg"><?php echo $initials; ?></span>
          </div>
        <?php else: ?>
          <div class="text-gray-400 text-2xl font-bold"><?php echo $initials; ?></div>
        <?php endif; ?>
      </div>
      
      <div class="text-gray-500 text-sm">
        Room: <span class="text-white"><?php echo htmlspecialchars($order['room_number']); ?></span>
      </div>
    </div>
    
    <?php 
      endwhile;
    else:
    ?>
      <div class="col-span-full text-white text-center p-8">
        <p class="text-xl">No orders available</p>
      </div>
    <?php endif; ?>
  </div>

  <div id="orderModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-gray-800 rounded-lg max-w-md w-full relative flex flex-col">
      <!-- Close Button -->
      <button onclick="closeModal()" class="absolute top-4 right-4 text-gray-400 hover:text-white z-10">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
      </button>

      <!-- Modal Header - Fixed -->
      <div class="p-6 pb-4 border-b border-gray-700">
        <h2 class="text-2xl font-bold text-white mb-2" id="modalOrderNumber">Order #1</h2>
        <span id="modalStatus" class="bg-teal-600 text-white text-xs px-3 py-1 rounded-full">Pending</span>
      </div>

      <!-- Modal Content - Scrollable -->
      <div class="flex-1 overflow-y-auto p-6 space-y-4">
        <!-- Customer Info -->
        <div class="bg-gray-700 rounded-lg p-4">
          <h3 class="text-gray-400 text-sm mb-2">Customer</h3>
          <div class="flex items-center gap-3">
            <div id="modalAvatar" class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center">
              <span class="text-white font-bold" id="modalInitials">AM</span>
            </div>
            <div>
              <p class="text-white font-medium" id="modalCustomerName">Anna Martinez</p>
              <p class="text-gray-400 text-sm" id="modalCustomerEmail">anna.martinez@email.com</p>
            </div>
          </div>
        </div>

        <!-- Order Details -->
        <div class="bg-gray-700 rounded-lg p-4">
          <h3 class="text-gray-400 text-sm mb-3">Order Details</h3>
          <div class="space-y-2">
            <div class="flex justify-between">
              <span class="text-gray-400">Room Number</span>
              <span class="text-white" id="modalRoom">101</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-400">Total Amount</span>
              <span class="text-white" id="modalTotal">₱500.00</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-400">Payment Status</span>
              <span class="text-white capitalize" id="modalPaymentStatus">Pending</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-400">Ordered At</span>
              <span class="text-white" id="modalOrderedAt">Jan 23, 2026 7:00 PM</span>
            </div>
            <div class="flex justify-between" id="modalDeliveredAtContainer" style="display: none;">
              <span class="text-gray-400">Delivered At</span>
              <span class="text-white" id="modalDeliveredAt">-</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-400">Order ID</span>
              <span class="text-white" id="modalOrderId">#001</span>
            </div>
          </div>
        </div>

        <!-- Order Items -->
        <div class="bg-gray-700 rounded-lg p-4">
          <h3 class="text-gray-400 text-sm mb-3">Order Items</h3>
          <div id="modalOrderItems" class="space-y-2">
            <!-- Items will be loaded here -->
          </div>
        </div>

        <!-- Special Instructions -->
        <div class="bg-gray-700 rounded-lg p-4">
          <h3 class="text-gray-400 text-sm mb-2">Special Instructions</h3>
          <p class="text-white text-sm" id="modalInstructions">No special instructions</p>
        </div>

        <!-- Update Status Section -->
        <div class="bg-gray-700 rounded-lg p-4">
          <h3 class="text-gray-400 text-sm mb-3">Update Status</h3>
          <select id="statusSelect" class="w-full bg-gray-600 text-white rounded-lg px-4 py-2 mb-3 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="pending">Pending</option>
            <option value="preparing">Preparing</option>
            <option value="ready">Ready</option>
            <option value="delivered">Delivered</option>
            <option value="cancelled">Cancelled</option>
          </select>
        </div>
      </div>

      <!-- Modal Actions - Fixed -->
      <div class="p-6 pt-4 border-t border-gray-700 flex gap-3 bg-gray-800">
        <button onclick="updateOrderStatus()" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition-colors">
          Update Status
        </button>
        <button onclick="closeModal()" class="flex-1 bg-gray-600 hover:bg-gray-700 text-white py-2 px-4 rounded-lg transition-colors">
          Close
        </button>
      </div>
    </div>
  </div>

