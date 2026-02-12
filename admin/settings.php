<?php

require_once '../controllers/settingsController.php';
require_once '../includes/flashMessages.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setting | <?php require_once '../includes/title.php'?></title>
    <link rel="stylesheet" href="../dist/output.css">
    <link rel="stylesheet" href="../css/app.css">
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
</head>
<body>
    
    <?php require_once '../components/admin_header.php';?>

    <!-- Main Container -->
    <div class="min-h-screen pt-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">

            <?php showFlash(); ?>

            <?php foreach ($users as $user): ?>
            <!-- Settings Card -->
            <div class="rounded-lg shadow-lg overflow-hidden mb-8" style="background-color: #141937;">
                
                <!-- Card Header -->
                <div class="px-6 py-6">
                    <h1 class="text-2xl font-bold text-white text-center">
                        <i class="fas fa-user-cog mr-3"></i>Update Profile
                        <hr class="mt-4">
                    </h1>
                </div>

                <!-- Card Body -->
                <div class="px-6 py-8">
                    <form method="POST" class="space-y-6">
                        
                        <!-- Hidden ID (important for update) -->
                        <input type="hidden" name="user_id" value="<?php echo $user['user_id'];?>">

                        <!-- Name & Email Row -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            
                            <!-- Name -->
                            <div>
                                <label class="block text-sm font-medium mb-2" style="color: #8b92b8;">
                                    <i class="fas fa-user mr-2" style="color: #00d4ff;"></i>Full Name
                                </label>
                                <input type="text" name="name" required 
                                    value="<?php echo htmlspecialchars($user['name']); ?>" 
                                    class="w-full px-4 py-3 rounded-lg"
                                    style="background-color: #1a1f3a; color: #ffffff; border: 1px solid #00bcd4;"
                                    placeholder="Enter your full name">
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block text-sm font-medium mb-2" style="color: #8b92b8;">
                                    <i class="fas fa-envelope mr-2" style="color: #00d4ff;"></i>Email Address
                                </label>
                                <input type="email" name="email" required 
                                    value="<?php echo htmlspecialchars($user['email']); ?>" 
                                    class="w-full px-4 py-3 rounded-lg"
                                    style="background-color: #1a1f3a; color: #ffffff; border: 1px solid #00bcd4;"
                                    placeholder="Enter your email address">
                            </div>
                        </div>

                        <!-- Divider -->
                        <div style="border-top: 1px solid #1a1f3a; margin: 8px 0;"></div>

                        <!-- Current Password -->
                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: #8b92b8;">
                                <i class="fas fa-lock mr-2" style="color: #ff4757;"></i>Current Password
                            </label>
                            <input type="password" name="current_password" required
                                class="w-full px-4 py-3 rounded-lg"
                                style="background-color: #1a1f3a; color: #ffffff; border: 1px solid #ff4757;"
                                placeholder="Enter your current password">
                            <p class="text-xs mt-2" style="color: #8b92b8;">
                                Required to confirm changes
                            </p>
                        </div>

                        <!-- New Password & Confirm -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            
                            <div>
                                <label class="block text-sm font-medium mb-2" style="color: #8b92b8;">
                                    <i class="fas fa-key mr-2" style="color: #00ff88;"></i>New Password
                                </label>
                                <input type="password" name="new_password"
                                    class="w-full px-4 py-3 rounded-lg"
                                    style="background-color: #1a1f3a; color: #ffffff; border: 1px solid #00ff88;"
                                    placeholder="Leave blank to keep current">
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-2" style="color: #8b92b8;">
                                    <i class="fas fa-check-double mr-2" style="color: #00ff88;"></i>Confirm New Password
                                </label>
                                <input type="password" name="confirm_password"
                                    class="w-full px-4 py-3 rounded-lg"
                                    style="background-color: #1a1f3a; color: #ffffff; border: 1px solid #00ff88;"
                                    placeholder="Confirm your new password">
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="flex gap-4 pt-6">
                            <button type="submit"
                                class="flex-1 py-3 rounded-lg font-semibold transition-all duration-200"
                                style="background: linear-gradient(135deg, #00d4ff 0%, #6b5ce7 100%); color: #ffffff;">
                                <i class="fas fa-save mr-2"></i>Save Changes
                            </button>

                            <a href="home.php"
                                class="flex-1 py-3 rounded-lg font-semibold text-center transition-all duration-200"
                                style="background-color: #1a1f3a; color: #8b92b8; border: 1px solid #00bcd4;">
                                <i class="fas fa-arrow-left mr-2"></i>Cancel
                            </a>
                        </div>

                    </form>
                </div>
            </div>
            <?php endforeach; ?>

        </div>
    </div>


</body>
</html>