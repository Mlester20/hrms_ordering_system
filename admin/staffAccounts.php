<?php

require_once '../includes/config.php';
require_once '../controllers/staffsController.php';
require_once '../includes/flashMessages.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Account | <?php require '../includes/title.php'?></title>
    <link rel="stylesheet" href="../dist/output.css">
    <link rel="stylesheet" href="../css/app.css">
</head>
<body>

    <?php require '../components/admin_header.php';?>

    <div class="max-w-7xl mx-auto mt-6">
      <!-- Header -->
      <div class="flex justify-between items-center mb-8">
        <div>
          <h1 class="text-3xl font-bold mb-2">Users Management</h1>
          <p class="text-secondary">Manage your users</p>
        </div>
        <button onclick="openModal('add')" class="btn-cyan text-white px-6 py-3 rounded-lg font-semibold transition-all">
          + Add New User
        </button>
      </div>

      <!-- Table -->
      <div class="bg-card rounded-xl shadow-2xl overflow-hidden">
        <table class="w-full">
          <thead class="bg-secondary">
            <tr>
              <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">ID</th>
              <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Name</th>
              <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Email</th>
              <th class="px-6 py-4 text-left text-sm font-semibold uppercase tracking-wider">Role</th>
              <th class="px-6 py-4 text-center text-sm font-semibold uppercase tracking-wider">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-700">
            <?php
                foreach($user as $users):
            ?>
            <tr class="table-row transition-colors" data-menu-id="<?= $users['user_id'] ?>">
              <td class="px-6 py-4 text-secondary">#<?= $users['user_id'] ?></td>
              <td class="px-6 py-4 font-medium"><?= htmlspecialchars($users['name']) ?></td>
              <td class="px-6 py-4 text-secondary"><?= ucfirst($users['email']) ?></td>
              <td class="px-6 py-4 font-semibold"><?= htmlspecialchars($users['role']) ?></td>
              <td class="px-6 py-4 text-center">
                <button onclick="openModal('edit', <?= $users['user_id'] ?>)" class="accent-cyan text-white px-4 py-2 rounded-lg mr-2 hover:opacity-80 transition-opacity">Edit</button>
                <button onclick="deleteMenu(<?= $users['user_id'] ?>)" class="bg-danger text-white px-4 py-2 rounded-lg hover:opacity-80 transition-all">Delete</button>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
    
</body>
</html>