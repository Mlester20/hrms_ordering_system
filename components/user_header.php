<header style="background-color: var(--bg-secondary); border-bottom: 1px solid var(--accent-cyan); box-shadow: 0 4px 6px rgba(0, 212, 255, 0.1);">
  <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center h-16">
      <!-- Logo -->
      <div class="flex items-center">
        <a href="/" class="flex items-center space-x-2">
          <span class="text-2xl font-bold" style="color: var(--accent-cyan);">🍽️ Restaurant Hub</span>
        </a>
      </div>

      <!-- Navigation Links -->
      <div class="hidden md:flex items-center space-x-8">
        <a href="home.php" style="color: var(--text-secondary); transition: color 0.2s;" onmouseover="this.style.color='var(--accent-blue)'" onmouseout="this.style.color='var(--text-secondary)'">Home</a>
        <a href="#" style="color: var(--text-secondary); transition: color 0.2s;" onmouseover="this.style.color='var(--accent-blue)'" onmouseout="this.style.color='var(--text-secondary)'">Menu</a>
        <a href="#" style="color: var(--text-secondary); transition: color 0.2s;" onmouseover="this.style.color='var(--accent-blue)'" onmouseout="this.style.color='var(--text-secondary)'">Orders</a>
      </div>

      <!-- Right Side: Dropdown Menus & Icons -->
      <div class="flex items-center space-x-6">
        
        <!-- Notifications Dropdown -->
        <div class="relative group">
          <button class="relative transition duration-200" style="color: var(--text-secondary);" onmouseover="this.style.color='var(--accent-cyan)'" onmouseout="this.style.color='var(--text-secondary)'">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 rounded-full" style="background-color: var(--danger);">3</span>
          </button>

          <!-- Notifications Dropdown Menu -->
          <div class="absolute right-0 mt-2 w-80 rounded-lg shadow-2xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50" style="background-color: var(--bg-card); border: 1px solid var(--accent-cyan);">
            <div class="px-4 py-3 border-b" style="border-color: var(--accent-cyan);">
              <h3 class="text-sm font-bold" style="color: var(--accent-cyan);">Notifications</h3>
            </div>
            <div class="max-h-96 overflow-y-auto">
              <!-- Notification Item -->
              <a href="#" class="block px-4 py-3 border-b transition duration-150" style="border-color: var(--accent-cyan); color: var(--text-primary);" onmouseover="this.style.backgroundColor='var(--bg-secondary)'" onmouseout="this.style.backgroundColor='transparent'">
                <div class="flex items-start">
                  <div class="flex-shrink-0">
                    <span class="inline-block w-2 h-2 rounded-full mt-2" style="background-color: var(--accent-cyan);"></span>
                  </div>
                  <div class="ml-3 flex-1">
                    <p class="text-sm font-medium" style="color: var(--text-primary);">Order #1234 ready for pickup</p>
                    <p class="mt-1 text-xs" style="color: var(--text-secondary);">5 minutes ago</p>
                  </div>
                </div>
              </a>
              <!-- Notification Item -->
              <a href="#" class="block px-4 py-3 border-b transition duration-150" style="border-color: var(--accent-cyan); color: var(--text-primary);" onmouseover="this.style.backgroundColor='var(--bg-secondary)'" onmouseout="this.style.backgroundColor='transparent'">
                <div class="flex items-start">
                  <div class="flex-shrink-0">
                    <span class="inline-block w-2 h-2 rounded-full mt-2" style="background-color: var(--accent-cyan);"></span>
                  </div>
                  <div class="ml-3 flex-1">
                    <p class="text-sm font-medium" style="color: var(--text-primary);">New restaurant near you</p>
                    <p class="mt-1 text-xs" style="color: var(--text-secondary);">2 hours ago</p>
                  </div>
                </div>
              </a>
              <!-- Notification Item -->
              <a href="#" class="block px-4 py-3 transition duration-150" style="color: var(--text-primary);" onmouseover="this.style.backgroundColor='var(--bg-secondary)'" onmouseout="this.style.backgroundColor='transparent'">
                <div class="flex items-start">
                  <div class="flex-shrink-0">
                    <span class="inline-block w-2 h-2 rounded-full mt-2" style="background-color: var(--text-secondary);"></span>
                  </div>
                  <div class="ml-3 flex-1">
                    <p class="text-sm font-medium" style="color: var(--text-primary);">Special offer: 20% off</p>
                    <p class="mt-1 text-xs" style="color: var(--text-secondary);">1 day ago</p>
                  </div>
                </div>
              </a>
            </div>
            <a href="#" class="block px-4 py-3 text-center text-sm font-medium border-t transition duration-150" style="color: var(--accent-cyan); border-color: var(--accent-cyan);" onmouseover="this.style.backgroundColor='var(--bg-secondary)'" onmouseout="this.style.backgroundColor='transparent'">
              View all notifications
            </a>
          </div>
        </div>

        <!-- Account Dropdown -->
        <div class="relative group">
          <button class="flex items-center space-x-2 px-4 py-2 rounded-lg transition duration-200" style="color: var(--text-secondary);" onmouseover="this.style.backgroundColor='var(--bg-card)'" onmouseout="this.style.backgroundColor='transparent'">
            <div class="w-8 h-8 rounded-full flex items-center justify-center text-white font-bold" style="background: linear-gradient(135deg, var(--accent-cyan) 0%, var(--accent-blue) 100%);">
              <?php echo strtoupper(substr($_SESSION['name'], 0, 1)); ?>
            </div>
            <span class="hidden sm:inline-block text-sm font-medium" style="color: var(--text-secondary);"> <?php echo htmlspecialchars($_SESSION['name']);?> </span>
            <svg class="w-4 h-4 transition duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--text-secondary);">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
            </svg>
          </button>

          <!-- Account Dropdown Menu -->
          <div class="absolute right-0 mt-2 w-48 rounded-lg shadow-2xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50" style="background-color: var(--bg-card); border: 1px solid var(--accent-cyan);">
            <div class="px-4 py-3 border-b" style="border-color: var(--accent-cyan);">
              <p class="text-sm font-bold" style="color: var(--accent-cyan);"> 
                <?php echo htmlspecialchars($_SESSION['name']);?> 
              </p>
              <p class="text-xs" style="color: var(--text-secondary);">
                <?php echo htmlspecialchars($_SESSION['role']);?>
              </p>
            </div>
            <div class="border-t my-2" style="border-color: var(--accent-cyan);"></div>
            <a href="settings.php" class="block px-4 py-2 text-sm transition duration-150 flex items-center space-x-2" style="color: var(--text-primary);" onmouseover="this.style.backgroundColor='var(--bg-secondary)'; this.style.color='var(--accent-cyan)'" onmouseout="this.style.backgroundColor='transparent'; this.style.color='var(--text-primary)'">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
              <span>Settings</span>
            </a>
            <a onclick="return confirm('Are you sure you want to logout?')" href="logout.php" class="block px-4 py-2 text-sm transition duration-150 flex items-center space-x-2 border-t" style="color: var(--danger); border-color: var(--accent-cyan);" onmouseover="this.style.backgroundColor='var(--bg-secondary)'" onmouseout="this.style.backgroundColor='transparent'">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
              </svg>
              <span>Logout</span>
            </a>
          </div>
        </div>

        <!-- Mobile Menu Button -->
        <button class="md:hidden transition duration-200" style="color: var(--text-secondary);" onmouseover="this.style.color='var(--accent-cyan)'" onmouseout="this.style.color='var(--text-secondary)'">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>
      </div>
    </div>
  </nav>
</header>