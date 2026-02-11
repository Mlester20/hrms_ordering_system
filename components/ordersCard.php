<div class="bg-gray-900 border-b border-gray-800 sticky top-0 z-40">
    <div class="max-w-7xl mx-auto px-4 py-4">
        <div class="flex flex-wrap gap-4">
            <?php
            $filters = [
                'all' => ['label' => 'All Orders', 'color' => 'gray'],
                'pending' => ['label' => 'Pending', 'color' => 'yellow'],
                'preparing' => ['label' => 'Preparing', 'color' => 'blue'],
                'ready' => ['label' => 'Ready', 'color' => 'green'],
                'delivered' => ['label' => 'Delivered', 'color' => 'teal'],
                'cancelled' => ['label' => 'Cancelled', 'color' => 'red']
            ];

            foreach ($filters as $status => $info):
                $isActive = ($filterStatus === $status);
                $activeClass = $isActive 
                    ? "bg-{$info['color']}-600 text-white" 
                    : "bg-gray-800 text-gray-400 hover:bg-gray-700 hover:text-white";
                $count = $statusCounts[$status] ?? 0;
            ?>
                <a href="?status=<?php echo $status; ?>" 
                   class="<?php echo $activeClass; ?> px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                    <span><?php echo $info['label']; ?></span>
                    <span class="<?php echo $isActive ? 'bg-white/20' : 'bg-gray-700'; ?> text-xs px-2 py-0.5 rounded-full">
                        <?php echo $count; ?>
                    </span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>


<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 p-4">
    <?php 
    if ($orders && $orders->num_rows > 0):
        while ($order = $orders->fetch_assoc()): 

            // Get initials from customer name
            $nameParts = explode(' ', $order['customer_name']);
            $initials = '';
            foreach ($nameParts as $part) {
                $initials .= strtoupper(substr($part, 0, 1));
            }

            // Determine status color and label
            $statusColors = [
                'pending'   => 'bg-yellow-600',
                'preparing' => 'bg-blue-600',
                'ready'     => 'bg-green-600',
                'delivered' => 'bg-teal-600',
                'cancelled' => 'bg-red-600'
            ];

            $statusColor = $statusColors[$order['order_status']] ?? 'bg-gray-600';

            // Consistent avatar colors based on order ID
            $avatarColors = [
                'bg-blue-600',
                'bg-green-500',
                'bg-yellow-600',
                'bg-purple-600',
                'bg-pink-600',
                'bg-indigo-600'
            ];
            $avatarColor = $avatarColors[$order['order_id'] % count($avatarColors)];

            // Show initials only for delivered/cancelled
            $showAvatar = in_array($order['order_status'], ['pending', 'preparing', 'ready']);
    ?>

        <!-- Order Card -->
        <div class="bg-gray-800 rounded-lg p-6 relative cursor-pointer hover:bg-gray-700 transition-colors"
            onclick="openModal(<?php echo $order['order_id']; ?>)">

            <div class="flex items-center justify-between mb-4">
                <h3 class="text-white font-semibold text-lg">
                    Order #<?php echo $order['order_id']; ?>
                </h3>
                <span class="<?php echo $statusColor; ?> text-white text-xs px-3 py-1 rounded-full capitalize">
                    <?php echo $order['order_status']; ?>
                </span>
            </div>

            <div class="flex items-center justify-center h-24 mb-4">
                <?php if ($showAvatar): ?>
                    <div class="w-16 h-16 <?php echo $avatarColor; ?> rounded-full flex items-center justify-center">
                        <span class="text-white font-bold text-lg">
                            <?php echo $initials; ?>
                        </span>
                    </div>
                <?php else: ?>
                    <div class="text-gray-400 text-2xl font-bold">
                        <?php echo $initials; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="text-gray-500 text-sm">
                Room:
                <span class="text-white">
                    <?php echo htmlspecialchars($order['room_number']); ?>
                </span>
            </div>
        </div>

    <?php 
        endwhile;
    else:
    ?>
        <div class="col-span-full text-center p-12">
            <div class="text-gray-400 mb-2">
                <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
            </div>
            <p class="text-xl text-white mb-1">No <?php echo $filterStatus === 'all' ? '' : $filterStatus; ?> orders</p>
            <p class="text-gray-500">Orders will appear here when customers place them</p>
        </div>
    <?php endif; ?>
</div>


<div id="orderModal"
    class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">

    <div class="bg-gray-800 rounded-lg max-w-4xl w-full relative max-h-[90vh] flex flex-col">

        <!-- Close Button -->
        <button onclick="closeModal()"
                class="absolute top-4 right-4 text-gray-400 hover:text-white z-10">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M6 18L18 6M6 6l12 12">
                </path>
            </svg>
        </button>

        <!-- Modal Header -->
        <div class="p-6 pb-4 border-b border-gray-700 flex-shrink-0">
            <h2 class="text-2xl font-bold text-white mb-2" id="modalOrderNumber">
                Order #1
            </h2>
            <span id="modalStatus"
                class="bg-teal-600 text-white text-xs px-3 py-1 rounded-full">
                Pending
            </span>
        </div>

        <!-- Modal Content - Two Column Layout -->
        <div class="overflow-y-auto p-6 flex-shrink min-h-0">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                
                <!-- LEFT COLUMN -->
                <div class="space-y-4">
                    <!-- Customer Info -->
                    <div class="bg-gray-700 rounded-lg p-4">
                        <h3 class="text-gray-400 text-sm mb-3">Customer</h3>
                        <div class="flex items-center gap-3">
                            <div id="modalAvatar"
                                class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center flex-shrink-0">
                                <span class="text-white font-bold" id="modalInitials">AM</span>
                            </div>
                            <div class="min-w-0">
                                <p class="text-white font-medium" id="modalCustomerName">
                                    Anna Martinez
                                </p>
                                <p class="text-gray-400 text-sm truncate" id="modalCustomerEmail">
                                    anna.martinez@email.com
                                </p>
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
                                <span class="text-white font-medium" id="modalTotal">₱500.00</span>
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

                    <!-- Special Instructions -->
                    <div class="bg-gray-700 rounded-lg p-4">
                        <h3 class="text-gray-400 text-sm mb-2">Special Instructions</h3>
                        <p class="text-white text-sm" id="modalInstructions">
                            No special instructions
                        </p>
                    </div>
                </div>

                <!-- RIGHT COLUMN -->
                <div class="space-y-4">
                    <!-- Order Items -->
                    <div class="bg-gray-700 rounded-lg p-4">
                        <h3 class="text-gray-400 text-sm mb-3">Order Items</h3>
                        <div id="modalOrderItems" class="space-y-2">
                            <!-- Items will be loaded here -->
                        </div>
                    </div>

                    <!-- Update Status -->
                    <div class="bg-gray-700 rounded-lg p-4">
                        <h3 class="text-gray-400 text-sm mb-3">Update Status</h3>
                        <select id="statusSelect"
                                class="w-full bg-gray-600 text-white rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="pending">Pending</option>
                            <option value="preparing">Preparing</option>
                            <option value="ready">Ready</option>
                            <option value="delivered">Delivered</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                </div>

            </div>
        </div>

        <!-- Modal Actions -->
        <div class="p-6 pt-4 border-t border-gray-700 flex gap-3 bg-gray-800 flex-shrink-0">
            <button onclick="updateOrderStatus()"
                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg transition-colors">
                Update Status
            </button>
            <button onclick="closeModal()"
                    class="flex-1 bg-gray-600 hover:bg-gray-700 text-white py-2 px-4 rounded-lg transition-colors">
                Close
            </button>
        </div>
    </div>
</div>