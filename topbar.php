<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PLMUN Access Control System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Topbar Styles */
        .topbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 60px;
            background: linear-gradient(135deg, #00b050, rgb(0, 100, 17));
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            z-index: 1100;
        }

        .logo-container {
            display: flex;
            align-items: center;
            height: 100%;
            cursor: pointer;
            transition: transform 0.3s;
        }


        .logo-img {
            height: 40px;
            width: auto;
            margin-right: 15px;
        }

        .system-title {
            font-size: 1.2rem;
            font-weight: bold;
            line-height: 1.2;
            color: white;
        }

        .system-title small {
            font-size: 0.8rem;
            font-weight: normal;
            display: block;
            color: rgba(255, 255, 255, 0.8);
        }

        .hamburger-btn {
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 10px;
            margin-right: 15px;
            transition: transform 0.3s;
        }

        .hamburger-btn:hover {
            transform: scale(1.1);
        }

        .user-menu {
            display: flex;
            align-items: center;
            height: 100%;
        }

        .user-name {
            margin-right: 15px;
            font-size: 0.9rem;
        }

        .logout-btn {
            color: white;
            background: rgba(255, 255, 255, 0.2);
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            font-size: 0.9rem;
        }

        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .logout-btn i {
            margin-right: 5px;
        }

        /* Overlay for mobile */
        .overlay {
            position: fixed;
            top: 60px;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            display: none;
        }
        /* Hide short version by default */
.short-title {
    display: none;
}

/* Mobile view: show short title and hide full one */
@media (max-width: 768px) {
    .full-title {
        display: none;
    }

    .short-title {
        display: inline;
    }

    .system-title small {
        font-size: 0.7rem;
    }
}

    </style>
</head>

<body>
    <div class="topbar">
        <!-- Left Side: Navigation Controls -->
        <div style="display: flex; align-items: center;">
            <!-- Hamburger Menu Button -->
            <button id="sidebarToggle" class="btn btn-outline-light me-2">
                <i class="fas fa-bars"></i>
            </button>
            <div class="logo-container">
                <img src="assets/img/PLMUNLOGO.png" class="logo-img" alt="PLMUN Logo">
                <div class="system-title">
                    <span class="full-title">Pamantasan ng Lungsod ng Muntinlupa</span>
                    <span class="short-title">PLMUN</span>
                    <small>Access Control</small>
                </div>

            </div>

        </div>

        <!-- Right Side: User Menu -->
        <div class="user-menu">
            <span class="user-name"><?php echo htmlspecialchars($_SESSION['login_name'] ?? 'Guest'); ?></span>
            <a href="ajax.php?action=logout" class="logout-btn">
                <i class="fas fa-power-off"></i> Logout
            </a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.createElement('div');
            overlay.className = 'overlay';
            document.body.appendChild(overlay);

            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function (e) {
                    e.preventDefault();
                    sidebar.classList.toggle('active');
                    overlay.style.display = sidebar.classList.contains('active') ? 'block' : 'none';
                });
            }

            overlay.addEventListener('click', function () {
                sidebar.classList.remove('active');
                this.style.display = 'none';
            });
        });
    </script>
</body>

</html>