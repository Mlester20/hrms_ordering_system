<?php
session_start();
include '../includes/config.php';

// Check if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

// Fetch user details
$user_id = $_SESSION['user_id'];
$query = "SELECT user_id, name, email FROM restaurant_auth WHERE user_id = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Check if user exists
if (!$user) {
    session_destroy();
    header('Location: ../index.php');
    exit();
}

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Verify current password
    $passwordQuery = "SELECT password FROM restaurant_auth WHERE user_id = ?";
    $stmt = $con->prepare($passwordQuery);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($hashedPassword);
    $stmt->fetch();
    $stmt->close();

    // Use sha1 for verification instead of password_verify
    if (sha1($current_password) === $hashedPassword) {
        // Update user details
        $updateQuery = "UPDATE restaurant_auth SET name = ?, email = ? WHERE user_id = ?";
        $stmt = $con->prepare($updateQuery);
        $stmt->bind_param("ssi", $name, $email, $user_id);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Profile updated successfully!";
        } else {
            $_SESSION['error'] = "Failed to update profile.";
        }
        $stmt->close();

        // Handle password change if new password is provided
        if (!empty($new_password) && !empty($confirm_password)) {
            if ($new_password === $confirm_password) {
                // Use sha1 for new password hashing instead of password_hash
                $new_hashed_password = sha1($new_password);
                $passwordUpdateQuery = "UPDATE restaurant_auth SET password = ? WHERE user_id = ?";
                $stmt = $con->prepare($passwordUpdateQuery);
                $stmt->bind_param("si", $new_hashed_password, $user_id);

                if ($stmt->execute()) {
                    $_SESSION['success'] = "Password updated successfully!";
                } else {
                    $_SESSION['error'] = "Failed to update password.";
                }
                $stmt->close();
            } else {
                $_SESSION['error'] = "New password and confirm password do not match.";
            }
        }
        
        // Refresh user data
        $query = "SELECT user_id, name, email FROM restaurant_auth WHERE user_id = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
    } else {
        $_SESSION['error'] = "Incorrect current password. Please try again.";
    }

    header('Location: settings.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - <?php include '../includes/title.php'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="../css/app.css">
</head>
<body style="background-color: #0a0e27;">
    <!-- Header -->
    <?php include '../components/user_header.php'; ?>

    <!-- Main Container -->
    <div class="min-h-screen pt-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <!-- Notifications -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="mb-6 p-4 rounded-lg" style="background-color: #1a1f3a; border-left: 4px solid #00ff88;">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle" style="color: #00ff88; margin-right: 12px;"></i>
                        <span style="color: #ffffff;"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></span>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="mb-6 p-4 rounded-lg" style="background-color: #1a1f3a; border-left: 4px solid #ff4757;">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle" style="color: #ff4757; margin-right: 12px;"></i>
                        <span style="color: #ffffff;"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></span>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Settings Card -->
            <div class="rounded-lg shadow-lg overflow-hidden" style="background-color: #141937;">
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
                        <!-- Name & Email Row -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Name -->
                            <div>
                                <label class="block text-sm font-medium mb-2" style="color: #8b92b8;">
                                    <i class="fas fa-user mr-2" style="color: #00d4ff;"></i>Full Name
                                </label>
                                <input type="text" name="name" required value="<?php echo isset($user['name']) ? htmlspecialchars($user['name']) : ''; ?>" 
                                    class="w-full px-4 py-3 rounded-lg" style="background-color: #1a1f3a; color: #ffffff; border: 1px solid #00bcd4;" 
                                    placeholder="Enter your full name">
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block text-sm font-medium mb-2" style="color: #8b92b8;">
                                    <i class="fas fa-envelope mr-2" style="color: #00d4ff;"></i>Email Address
                                </label>
                                <input type="email" name="email" required value="<?php echo isset($user['email']) ? htmlspecialchars($user['email']) : ''; ?>" 
                                    class="w-full px-4 py-3 rounded-lg" style="background-color: #1a1f3a; color: #ffffff; border: 1px solid #00bcd4;" 
                                    placeholder="Enter your email address">
                            </div>
                        </div>

                        <!-- Divider -->
                        <div style="border-top: 1px solid #1a1f3a; margin: 8px 0;"></div>

                        <!-- Current Password (Full Width) -->
                        <div>
                            <label class="block text-sm font-medium mb-2" style="color: #8b92b8;">
                                <i class="fas fa-lock mr-2" style="color: #ff4757;"></i>Current Password
                            </label>
                            <input type="password" name="current_password" required 
                                class="w-full px-4 py-3 rounded-lg" style="background-color: #1a1f3a; color: #ffffff; border: 1px solid #ff4757;" 
                                placeholder="Enter your current password">
                            <p class="text-xs mt-2" style="color: #8b92b8;">Required to confirm changes</p>
                        </div>

                        <!-- New Password & Confirm Password Row -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- New Password -->
                            <div>
                                <label class="block text-sm font-medium mb-2" style="color: #8b92b8;">
                                    <i class="fas fa-key mr-2" style="color: #00ff88;"></i>New Password
                                </label>
                                <input type="password" name="new_password" 
                                    class="w-full px-4 py-3 rounded-lg" style="background-color: #1a1f3a; color: #ffffff; border: 1px solid #00ff88;" 
                                    placeholder="Leave blank to keep current">
                                <p class="text-xs mt-2" style="color: #8b92b8;">Optional - min 6 characters</p>
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label class="block text-sm font-medium mb-2" style="color: #8b92b8;">
                                    <i class="fas fa-check-double mr-2" style="color: #00ff88;"></i>Confirm New Password
                                </label>
                                <input type="password" name="confirm_password" 
                                    class="w-full px-4 py-3 rounded-lg" style="background-color: #1a1f3a; color: #ffffff; border: 1px solid #00ff88;" 
                                    placeholder="Confirm your new password">
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="flex gap-4 pt-6">
                            <button type="submit" class="flex-1 py-3 rounded-lg font-semibold transition-all duration-200" 
                                style="background: linear-gradient(135deg, #00d4ff 0%, #6b5ce7 100%); color: #ffffff;">
                                <i class="fas fa-save mr-2"></i>Save Changes
                            </button>
                            <a href="home.php" class="flex-1 py-3 rounded-lg font-semibold text-center transition-all duration-200" 
                                style="background-color: #1a1f3a; color: #8b92b8; border: 1px solid #00bcd4;">
                                <i class="fas fa-arrow-left mr-2"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>
</html>