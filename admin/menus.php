<?php

require_once '../includes/config.php';
require_once '../controllers/menusController.php';
require_once '../includes/flashMessages.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Menu Management | <?php require '../includes/title.php';?></title>
  <link rel="stylesheet" href="../dist/output.css">
  <link rel="stylesheet" href="../css/app.css">
  <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
</head>
<body>

  <?php require '../components/admin_header.php'; ?>

  <div class="max-w-7xl mx-auto mt-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
      <div>
        <h1 class="text-3xl font-bold mb-2">Menu Management</h1>
        <p class="text-secondary">Manage your restaurant menu items</p>
      </div>
      <button onclick="openModal('add')" class="btn-cyan text-white px-6 py-3 rounded-lg font-semibold transition-all">
        + Add New Menu
      </button>
    </div>

    <?php showFlash(); ?>

    <!-- Table -->
    <div class="bg-card rounded-xl shadow-2xl overflow-hidden">
      <table class="w-full">
        <thead class="bg-secondary">
          <tr>
            <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">ID</th>
            <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Image</th>
            <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Menu Name</th>
            <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Category</th>
            <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Price</th>
            <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Status</th>
            <th class="px-6 py-4 text-center text-sm font-semibold uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-700">
          <?php
              foreach($menus as $menu):
          ?>
          <tr class="table-row transition-colors" data-menu-id="<?= $menu['menu_id'] ?>">
            <td class="px-6 py-4 text-secondary">#<?= str_pad($menu['menu_id'], 3, '0', STR_PAD_LEFT) ?></td>
            <td class="px-6 py-4">
              <?php if (!empty($menu['product_image'])): ?>
                <img src="../uploads/<?= htmlspecialchars($menu['product_image']) ?>" alt="<?= htmlspecialchars($menu['menu_name']) ?>" class="image-preview">
              <?php else: ?>
                <div class="image-preview bg-secondary flex items-center justify-center text-xs text-gray-500">No Image</div>
              <?php endif; ?>
            </td>
            <td class="px-6 py-4 font-medium"><?= htmlspecialchars($menu['menu_name']) ?></td>
            <td class="px-6 py-4 text-secondary"><?= ucfirst($menu['category']) ?></td>
            <td class="px-6 py-4 font-semibold" style="color: var(--success)">₱<?= number_format($menu['price'], 2) ?></td>
            <td class="px-6 py-4">
              <?php if ($menu['status'] === 'available'): ?>
                <span class="px-3 py-1 rounded-full text-xs font-semibold" style="background-color: rgba(0, 255, 136, 0.2); color: var(--success)">Available</span>
              <?php else: ?>
                <span class="px-3 py-1 rounded-full text-xs font-semibold" style="background-color: rgba(255, 184, 0, 0.2); color: var(--warning)">Out of Stock</span>
              <?php endif; ?>
            </td>
            <td class="px-6 py-4 text-center">
              <button onclick="openModal('edit', <?= $menu['menu_id'] ?>)" class="accent-cyan text-white px-4 py-2 rounded-lg mr-2 hover:opacity-80 transition-opacity">Edit</button>
              <button onclick="deleteMenu(<?= $menu['menu_id'] ?>)" class="bg-danger text-white px-4 py-2 rounded-lg hover:opacity-80 transition-all">Delete</button>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Modal -->
  <div id="menuModal" class="modal items-center justify-center">
    <div class="bg-card rounded-xl shadow-2xl w-full max-w-2xl mx-4 overflow-hidden">
      <!-- Modal Header -->
      <div class="bg-secondary px-6 py-4 flex justify-between items-center">
        <h2 id="modalTitle" class="text-2xl font-bold">Add New Menu</h2>
        <button onclick="closeModal()" class="text-secondary hover:text-white transition-colors text-2xl">&times;</button>
      </div>

      <!-- Modal Body -->
      <form id="menuForm" class="p-6" enctype="multipart/form-data">
        <div class="space-y-4">
          <input type="hidden" name="menu_id" id="menuId">
          <div>
            <label class="block text-sm font-semibold mb-2">Menu Name</label>
            <input name="menu_name" type="text" id="menuName" class="w-full px-4 py-3 rounded-lg bg-secondary border border-gray-700 text-white transition-all" placeholder="Enter menu name" required>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-semibold mb-2">Category</label>
              <select id="category" name="category" class="w-full px-4 py-3 rounded-lg bg-secondary border border-gray-700 text-white transition-all" required>
                <option value="">Select category</option>
                <option value="appetizer">Appetizer</option>
                <option value="main">Main Course</option>
                <option value="dessert">Dessert</option>
                <option value="beverage">Beverage</option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-semibold mb-2">Price</label>
              <input type="number" name="price" id="price" step="0.01" class="w-full px-4 py-3 rounded-lg bg-secondary border border-gray-700 text-white transition-all" placeholder="0.00" required>
            </div>
          </div>

          <div>
            <label class="block text-sm font-semibold mb-2">Product Image</label>
            <input type="file" name="product_image" id="product_image" accept="image/*" class="w-full px-4 py-3 rounded-lg bg-secondary border border-gray-700 text-white transition-all">
            <div id="imagePreviewContainer" class="mt-2" style="display: none;">
              <img id="imagePreview" src="" alt="Preview" class="modal-image-preview">
            </div>
            <input type="hidden" id="currentImage" name="current_image">
          </div>

          <div>
            <label class="block text-sm font-semibold mb-2">Description</label>
            <textarea name="description" id="description" rows="3" class="w-full px-4 py-3 rounded-lg bg-secondary border border-gray-700 text-white transition-all" placeholder="Enter menu description"></textarea>
          </div>

          <div>
            <label class="block text-sm font-semibold mb-2">Status</label>
            <select id="status" name="status" class="w-full px-4 py-3 rounded-lg bg-secondary border border-gray-700 text-white transition-all" required>
              <option value="available">Available</option>
              <option value="unavailable">Out of Stock</option>
            </select>
          </div>
        </div>

        <!-- Modal Footer -->
        <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-gray-700">
          <button type="button" onclick="closeModal()" class="px-6 py-3 rounded-lg bg-secondary hover:bg-opacity-80 transition-all font-semibold">Cancel</button>
          <button type="submit" name="save_menu" class="btn-cyan px-6 py-3 rounded-lg font-semibold text-white">Save Menu</button>
        </div>
      </form>
    </div>
  </div>

  <!-- External JS -->
  <script src="../js/menus.js"></script>

</body>
</html>