<style>
    /* Sidebar Styles */
    #sidebar {
        position: fixed;
        top: 60px;
        left: -250px;
        width: 250px;
        height: calc(100% - 60px);
        background: #343a40;
        transition: left 0.3s ease;
        z-index: 1000;
        overflow-y: auto;
    }

    #sidebar.active {
        left: 0;
    }

    .sidebar-list {
        padding: 20px 0;
    }

    .sidebar-list a {
        display: block;
        color: #adb5bd;
        padding: 12px 20px;
        text-decoration: none;
        transition: all 0.3s;
        border-left: 3px solid transparent;
    }

    .sidebar-list a:hover,
    .sidebar-list a.active {
        color: #ffffff;
        background-color: rgba(255, 255, 255, 0.1);
        border-left: 3px solid #007bff;
    }

    .icon-field {
        margin-right: 10px;
        width: 20px;
        display: inline-block;
        text-align: center;
    }

    .sidebar-header {
        padding: 15px 20px;
        background: #2c3e50;
        color: white;
        font-weight: bold;
        border-bottom: 1px solid #1a252f;
    }
</style>

<nav id="sidebar" class='bg-dark'>
    <div class="sidebar-list">
        <a href="index.php" class="nav-item nav-home">
            <span class='icon-field'><i class="fa fa-home"></i></span> Home
        </a>
        <a href="index.php?page=records" class="nav-item nav-records">
            <span class='icon-field'><i class="fa fa-th-list"></i></span> Records
        </a>
        <a href="index.php?page=visitor_logs" class="nav-item nav-visitors">
            <span class='icon-field'><i class="fa fa-th-list"></i></span> Visitors Logs
        </a>
        <a href="index.php?page=visitor_list" class="nav-item nav-visitor_list">
            <span class='icon-field'><i class="fa fa-th-list"></i></span> Visitors List
        </a>
        <?php if ($_SESSION['login_type'] == 1): ?>
            <a href="index.php?page=establishment" class="nav-item nav-establishment">
                <span class='icon-field'><i class="fa fa-building"></i></span> Establishments
            </a>
            <a href="index.php?page=persons" class="nav-item nav-persons">
                <span class='icon-field'><i class="fa fa-user-friends"></i></span> Students
            </a>
            <a href="index.php?page=users" class="nav-item nav-users">
                <span class='icon-field'><i class="fa fa-users"></i></span> Users
            </a>
        <?php endif; ?>
    </div>
</nav>

<script>
    $(document).ready(function () {
        // Highlight active menu item
        var currentPage = '<?php echo isset($_GET['page']) ? $_GET['page'] : 'dashboard'; ?>';
        $('.nav-item').removeClass('active');
        $('.nav-' + currentPage).addClass('active');

        // Close sidebar when clicking a link (for mobile)
        $('.sidebar-list a').click(function () {
            if (window.innerWidth <= 768) {
                $('#sidebar').removeClass('active');
                $('.overlay').hide();
            }
        });
    });
</script>
