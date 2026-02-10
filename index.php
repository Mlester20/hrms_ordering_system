<?php
require_once 'includes/flashMessages.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - POS System</title>
    <link rel="stylesheet" href="dist/output.css">
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <style>
        :root {
            --bg-primary: #0a0e27;
            --bg-secondary: #141937;
            --bg-card: #1a1f3a;
            --accent-blue: #00d4ff;
            --accent-cyan: #00bcd4;
            --accent-purple: #6b5ce7;
            --text-primary: #ffffff;
            --text-secondary: #8b92b8;
            --success: #00ff88;
            --warning: #ffb800;
            --danger: #ff4757;
        }
        
        body {
            background: linear-gradient(135deg, var(--bg-primary) 0%, var(--bg-secondary) 100%);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }
        
        .login-card {
            background: var(--bg-card);
            box-shadow: 0 8px 32px rgba(0, 212, 255, 0.15), 0 0 80px rgba(107, 92, 231, 0.1);
        }
        
        .input-field {
            background: var(--bg-secondary);
            border: 1px solid rgba(139, 146, 184, 0.2);
            color: var(--text-primary);
            transition: all 0.3s ease;
        }
        
        .input-field:focus {
            outline: none;
            border-color: var(--accent-blue);
            box-shadow: 0 0 0 3px rgba(0, 212, 255, 0.1);
        }
        
        .input-field::placeholder {
            color: var(--text-secondary);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--accent-blue) 0%, var(--accent-cyan) 100%);
            color: var(--bg-primary);
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 212, 255, 0.4);
        }
        
        .btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        
        .error-message {
            background: rgba(255, 71, 87, 0.1);
            border-left: 3px solid var(--danger);
            color: var(--danger);
        }
        
        .checkbox-custom {
            appearance: none;
            width: 18px;
            height: 18px;
            border: 2px solid rgba(139, 146, 184, 0.3);
            border-radius: 4px;
            background: var(--bg-secondary);
            cursor: pointer;
            position: relative;
            transition: all 0.3s ease;
        }
        
        .checkbox-custom:checked {
            background: var(--accent-blue);
            border-color: var(--accent-blue);
        }
        
        .checkbox-custom:checked::after {
            content: '✓';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: var(--bg-primary);
            font-size: 12px;
            font-weight: bold;
        }
        
        .password-toggle {
            color: var(--text-secondary);
            cursor: pointer;
            transition: color 0.2s ease;
        }
        
        .password-toggle:hover {
            color: var(--accent-blue);
        }
        
        .loading-spinner {
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top: 2px solid var(--text-primary);
            border-radius: 50%;
            width: 16px;
            height: 16px;
            animation: spin 0.8s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .glow-text {
            text-shadow: 0 0 20px rgba(0, 212, 255, 0.5);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="login-card w-full max-w-md rounded-2xl p-8 md:p-10">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl md:text-4xl font-bold mb-2 glow-text" style="color: var(--text-primary);">
                Welcome Back
            </h1>
            <p class="text-base" style="color: var(--text-secondary);">
                Sign in to start your session
            </p>
        </div>
        
        <!-- message -->
        <?php showFlash(); ?>
        
        <!-- Login Form -->
        <form method="POST" action="controllers/auth.php" class="space-y-6">
            <!-- Username/Email Field -->
            <div>
                <label for="username" class="block text-sm font-medium mb-2" style="color: var(--text-primary);">
                    Username or Email
                </label>
                <input 
                    type="email" 
                    id="username" 
                    name="email" 
                    class="input-field w-full px-4 py-3 rounded-lg text-base"
                    placeholder="Enter your username or email"
                    required
                    autocomplete="username"
                >
            </div>
            
            <!-- Password Field -->
            <div>
                <label for="password" class="block text-sm font-medium mb-2" style="color: var(--text-primary);">
                    Password
                </label>
                <div class="relative">
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="input-field w-full px-4 py-3 rounded-lg text-base pr-12"
                        placeholder="Enter your password"
                        required
                        autocomplete="current-password"
                    >
                    <button 
                        type="button" 
                        class="password-toggle absolute right-4 top-1/2 transform -translate-y-1/2"
                        onclick="togglePassword()"
                        aria-label="Toggle password visibility"
                    >
                    </button>
                </div>
            </div>
            
            
            <!-- Submit Button -->
            <button type="submit" class="btn-primary w-full py-3 px-6 rounded-lg text-base font-semibold flex items-center justify-center">
                Login
            </button>
        </form>
    </div>
    

</body>
</html>