<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Denied</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="max-w-md w-full">
            <!-- Error Container -->
            <div class="bg-white rounded-lg shadow-2xl overflow-hidden">
                <!-- Red Top Bar -->
                <div class="h-1 bg-gradient-to-r from-red-500 to-pink-600"></div>

                <!-- Content -->
                <div class="p-8 text-center">
                    <!-- Icon -->
                    <div class="mb-6">
                        <div class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-red-100">
                            <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Heading -->
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Access Denied</h1>
                    <p class="text-gray-600 mb-6">403 Forbidden</p>

                    <!-- Message -->
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-8">
                        <p class="text-red-700 text-sm">
                            You do not have permission to access this page. Admin privileges are required.
                        </p>
                    </div>

                    <!-- Additional Info -->
                    <div class="mb-8 p-4 bg-gray-50 rounded-lg">
                        <p class="text-gray-600 text-sm">
                            If you believe this is an error, please contact the system administrator.
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="space-y-3">
                        <a href="/" class="block w-full bg-gradient-to-r from-purple-600 to-purple-700 text-white font-semibold py-3 px-4 rounded-lg hover:from-purple-700 hover:to-purple-800 transition duration-200">
                            Go to Home
                        </a>
                        <a href="javascript:history.back()" class="block w-full bg-gray-200 text-gray-800 font-semibold py-3 px-4 rounded-lg hover:bg-gray-300 transition duration-200">
                            Go Back
                        </a>
                    </div>

                    <!-- Footer -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <p class="text-gray-500 text-xs">
                            Support ID: <span class="font-mono"><?php echo bin2hex(random_bytes(4)); ?></span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Extra Security Info (Optional) -->
            <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <p class="text-blue-700 text-xs leading-relaxed">
                    <strong>Security Note:</strong> This access attempt has been logged for security purposes. 
                    If you need admin access, please contact your administrator.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
