<!-- sidebar.php -->
<div class="d-flex flex-column p-4 text-white" id="sidebar-wrapper" style="width: 280px; min-width: 280px; height: 100vh; position: sticky; top: 0; z-index: 1020; background-color: #111827; border-right: 1px solid rgba(255, 255, 255, 0.08);">
    
    <!-- Custom Hover & Transition Styles -->
    <style>
        #sidebar-wrapper .nav-link {
            color: #9ca3af;
            font-weight: 500;
            padding: 10px 16px;
            border-radius: 8px;
            transition: all 0.2s ease-in-out;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        #sidebar-wrapper .nav-link:hover {
            color: #ffffff;
            background-color: rgba(255, 255, 255, 0.05);
        }
        #sidebar-wrapper .nav-link.active {
            color: #ffffff;
            background-color: #3b82f6 !important; /* Premium Royal Blue Accent */
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25);
        }
        #sidebar-wrapper .nav-link i {
            font-size: 1.1rem;
        }
        #sidebar-wrapper .logout-btn {
            background-color: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.2);
            padding: 12px;
            border-radius: 8px;
            text-align: center;
            transition: all 0.2s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-weight: 500;
        }
        #sidebar-wrapper .logout-btn:hover {
            background-color: #ef4444;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);
        }
    </style>

    <!-- Sidebar Header / Branding -->
    <div class="d-flex align-items-center mb-4 pb-2">
        <div class="bg-primary d-flex align-items-center justify-content-center rounded-3 me-3" style="width: 40px; height: 40px; background: linear-gradient(135deg, #3b82f6, #1d4ed8);">
            <i class="bi bi-compass text-white fs-5"></i>
        </div>
        <span class="fs-5 fw-bold tracking-tight" style="letter-spacing: -0.5px;">Travarsa</span>
    </div>
    
    <div style="border-top: 1px solid rgba(255, 255, 255, 0.08); margin-bottom: 24px;"></div>
    
    <!-- Navigation Links -->
    <ul class="nav nav-pills flex-column mb-auto gap-1">
        <li class="nav-item">
            <!-- Tip: Add the 'active' class to whichever page is currently loaded -->
            <a href="#" class="nav-link">
                <i class="bi bi-grid-1x2-fill"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li>
            <a href="/travarsa/pages/events.php" class="nav-link">
                <i class="bi bi-calendar4-event"></i>
                <span>Events</span>
            </a>
        </li>
        <li>
            <a href="/travarsa/pages/bookings.php" class="nav-link">
                <i class="bi bi-ticket-perforated"></i>
                <span>Bookings</span>
            </a>
        </li>
        <li>
            <a href="/travarsa/pages/users.php" class="nav-link">
                <i class="bi bi-people"></i>
                <span>Users</span>
            </a>
        </li>
    </ul>
    
    <div style="border-top: 1px solid rgba(255, 255, 255, 0.08); margin-top: 24px; margin-bottom: 24px;"></div>
    
    <!-- Footer User Profile / Logout Structure -->
    <div class="d-flex flex-column gap-3">
        <!-- Minimalist Admin Badge -->
        <div class="d-flex align-items-center px-2">
            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white fw-bold me-3" style="width: 36px; height: 36px; font-size: 0.9rem; background-color: #374151 !important;">
                A
            </div>
            <div class="lh-sm">
                <div class="fw-semibold text-white fs-6">Admin Panel</div>
                <div style="font-size: 0.75rem; color: #9ca3af;">system@travarsa.com</div>
            </div>
        </div>

        <!-- Classy, Sophisticated Logout -->
        <a href="logout.php" class="logout-btn">
            <i class="bi bi-box-arrow-left"></i>
            <span>Log Out</span>
        </a>
    </div>
</div>