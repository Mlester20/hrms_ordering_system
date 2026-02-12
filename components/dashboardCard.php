<div class="min-h-screen pt-8 px-4 sm:px-6 lg:px-8" style="background-color: var(--bg-primary);">
    <div class="max-w-7xl mx-auto">
        
        <!-- Page Title -->
        <div class="mb-8">
            <h1 class="text-3xl text-center font-bold" style="color: var(--text-primary);">
                <i class="fas fa-chart-line mr-3" style="color: var(--accent-blue);"></i>Dashboard Overview
            </h1>
            <p class="mt-2" style="color: var(--text-secondary);">Monitor your restaurant performance in real-time</p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            
            <!-- Total Revenue Card -->
            <div class="rounded-lg shadow-lg p-6" style="background-color: var(--bg-card); border: 1px solid var(--accent-blue);">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium mb-2" style="color: var(--text-secondary);">Total Revenue</p>
                        <h3 class="text-3xl font-bold" style="color: var(--accent-blue);">
                            ₱<?php echo number_format($totalRevenue, 2); ?>
                        </h3>
                    </div>
                    <div class="rounded-full p-4" style="background: linear-gradient(135deg, var(--accent-blue), var(--accent-cyan));">
                        <i class="fas fa-dollar-sign text-white text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Today Revenue Card -->
            <div class="rounded-lg shadow-lg p-6" style="background-color: var(--bg-card); border: 1px solid var(--accent-cyan);">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium mb-2" style="color: var(--text-secondary);">Today's Revenue</p>
                        <h3 class="text-3xl font-bold" style="color: var(--accent-cyan);">
                            ₱<?php echo number_format($todayRevenue, 2); ?>
                        </h3>
                    </div>
                    <div class="rounded-full p-4" style="background-color: var(--accent-cyan);">
                        <i class="fas fa-calendar-day text-white text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Today Orders Card -->
            <div class="rounded-lg shadow-lg p-6" style="background-color: var(--bg-card); border: 1px solid var(--danger);">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium mb-2" style="color: var(--text-secondary);">Today's Orders</p>
                        <h3 class="text-3xl font-bold" style="color: var(--danger);">
                            <?php echo number_format($todayOrders); ?>
                        </h3>
                    </div>
                    <div class="rounded-full p-4" style="background-color: var(--danger);">
                        <i class="fas fa-receipt text-white text-2xl"></i>
                    </div>
                </div>
            </div>

        </div>

        <!-- Two Column Layout: Revenue Chart + Top Items -->
        <div class="charts-container">
            
            <!-- Revenue Line Chart -->
            <div class="chart-card">
                <h3 class="text-xl text-center font-bold mb-4" style="color: var(--text-primary);">
                    <i class="fas fa-chart-line mr-2" style="color: var(--accent-blue);"></i>
                    Revenue Trend (Last 7 Days)
                </h3>
                <div class="chart-wrapper">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <!-- Top Selling Items -->
            <div class="chart-card">
                <h3 class="text-xl text-center font-bold mb-4" style="color: var(--text-primary);">
                    <i class="fas fa-star mr-2" style="color: var(--warning);"></i>
                    Top Selling Items
                </h3>
                <div class="top-items-container">
                    <div class="top-items-grid">
                        <?php 
                        $rank = 1;
                        $topItems->data_seek(0);
                        while($item = $topItems->fetch_assoc()): 
                        ?>
                        <div class="top-item-card">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-2xl font-bold" style="color: var(--accent-blue);">#<?php echo $rank++; ?></span>
                            </div>
                            <p class="font-semibold mb-2 truncate" style="color: var(--text-primary);">
                                <?php echo htmlspecialchars($item['menu_name']); ?>
                            </p>
                            <p class="text-sm mb-1" style="color: var(--text-secondary);">
                                <i class="fas fa-box mr-1"></i><?php echo number_format($item['total_sold']); ?> sold
                            </p>
                            <p class="font-bold" style="color: var(--success);">
                                ₱<?php echo number_format($item['total_revenue'], 2); ?>
                            </p>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
