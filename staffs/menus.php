<?php
require_once '../controllers/menusController.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menus | <?php require_once '../includes/title.php';?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../dist/output.css">
    <link rel="stylesheet" href="../css/app.css">
</head>
<body>

    <?php require_once '../components/user_header.php';?>

    <div class="max-w-7xl mx-auto px-4 py-12">
        <!-- Header Section -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold mb-4">Menu</h1>
        </div>

        <!-- Category Filter (Optional) -->
        <div class="flex justify-center gap-4 mb-8 flex-wrap">
            <button class="px-6 py-2 card text-white rounded-full font-semibold hover:bg-cyan-600 transition">All</button>
            <button class="px-6 py-2 bg-gray-200 text-gray-700 rounded-full font-semibold hover:bg-gray-300 transition">Appetizer</button>
            <button class="px-6 py-2 bg-gray-200 text-gray-700 rounded-full font-semibold hover:bg-gray-300 transition">Main Course</button>
            <button class="px-6 py-2 bg-gray-200 text-gray-700 rounded-full font-semibold hover:bg-gray-300 transition">Dessert</button>
            <button class="px-6 py-2 bg-gray-200 text-gray-700 rounded-full font-semibold hover:bg-gray-300 transition">Beverage</button>
        </div>

        <!-- Menu Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach($menus as $menu): ?>
                <div class="card rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-shadow duration-300">
                    <!-- Menu Image Placeholder -->
                    <div class="h-48 card card flex items-center justify-center">
                        <span class="text-6xl">🍽️</span>
                    </div>
                    
                    <!-- Menu Content -->
                    <div class="p-6">
                        <!-- Category Badge -->
                        <span class="inline-block px-3 py-1 bg-cyan-100 text-cyan-700 text-xs font-semibold rounded-full mb-3">
                            <?= ucfirst($menu['category']) ?>
                        </span>
                        
                        <!-- Menu Name -->
                        <h3 class="text-xl font-bold mb-2">
                            <?= htmlspecialchars($menu['menu_name']) ?>
                        </h3>
                        
                        <!-- Description -->
                        <p class="text-sm mb-4 line-clamp-2">
                            <?= htmlspecialchars($menu['description']) ?>
                        </p>
                        
                        <!-- Price and Status -->
                        <div class="flex items-center justify-between">
                            <span class="text-2xl font-bold text-cyan-600">
                                ₱<?= number_format($menu['price'], 2) ?>
                            </span>
                            
                            <?php if($menu['status'] === 'available'): ?>
                                <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-semibold rounded-full">
                                    Available
                                </span>
                            <?php else: ?>
                                <span class="px-3 py-1 bg-red-100 text-red-700 text-xs font-semibold rounded-full">
                                    Out of Stock
                                </span>
                            <?php endif; ?>
                        </div>
                        
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Empty State -->
        <?php if(empty($menus)): ?>
            <div class="text-center py-16">
                <div class="text-6xl mb-4">🍽️</div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">No menu items available</h3>
                <p class="text-gray-600">Check back later for our delicious offerings!</p>
            </div>
        <?php endif; ?>
    </div>

</body>
</html> 