<?php

include 'db_connect.php';


$total_visitors_today = $conn->query("SELECT COUNT(*) as total FROM person_tracks WHERE DATE(date_created) = CURDATE()")->fetch_assoc()['total'];
$total_establishments = $conn->query("SELECT COUNT(*) as total FROM establishments")->fetch_assoc()['total'];
$total_students = $conn->query("SELECT COUNT(*) as total FROM persons")->fetch_assoc()['total'];

// Fetch data for charts
$college_course = [];
$college_course_query = $conn->query("
    SELECT college, course, COUNT(*) as total 
    FROM persons 
    GROUP BY college, course
");
while ($row = $college_course_query->fetch_assoc()) {
    $college_course[] = $row;
}

$courses = [];
$course_query = $conn->query("SELECT course, COUNT(*) as count FROM persons 
    JOIN person_tracks ON persons.id = person_tracks.person_id 
    GROUP BY course");
while ($row = $course_query->fetch_assoc()) {
    $courses[] = $row;
}

$estabs = [];
$estab_query = $conn->query("SELECT e.name as est_name, COUNT(*) as total 
    FROM person_tracks pt 
    JOIN establishments e ON pt.establishment_id = e.id 
    GROUP BY e.name");
while ($row = $estab_query->fetch_assoc()) {
    $estabs[] = $row;
}

$daily = [];
$daily_query = $conn->query("SELECT HOUR(date_created) as hour, COUNT(*) as total
FROM person_tracks
WHERE DATE(date_created) = CURDATE()
GROUP BY hour
ORDER BY hour");

while ($row = $daily_query->fetch_assoc()) {
    $daily[] = ['date' => $row['hour'], 'total' => $row['total']];
}

$weekly = [];
$weekly_query = $conn->query("SELECT DATE(date_created) as date, COUNT(*) as total 
    FROM person_tracks 
    WHERE date_created >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) 
    GROUP BY DATE(date_created)");
while ($row = $weekly_query->fetch_assoc()) {
    $weekly[] = $row;
}

$monthly = [];
$monthly_query = $conn->query("SELECT DATE_FORMAT(date_created, '%Y-%m') as month, COUNT(*) as total 
    FROM person_tracks 
    GROUP BY month");
while ($row = $monthly_query->fetch_assoc()) {
    $monthly[] = $row;
}

$hourly = [];
$hourly_query = $conn->query("SELECT HOUR(date_created) as hour, COUNT(*) as total 
    FROM person_tracks 
    GROUP BY hour ORDER BY hour");
while ($row = $hourly_query->fetch_assoc()) {
    $hourly[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | PLMUN Access Control Systeem</title>

    <!-- Bootstrap CSS -->
    <?php
    session_start();
    if (!isset($_SESSION['login_id']))
        header('location:login.php');
    // include('./auth.php'); 
    ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom CSS -->
    <style>
        body {
            padding-top: 60px;
            /* Match topbar height */
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .dashboard-container {
            padding: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .dashboard-header {
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 1px solid #dee2e6;
        }

        .stat-card {
            text-align: center;
            padding: 25px 15px;
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-value {
            font-size: 2.5rem;
            font-weight: bold;
            color: #2c3e50;
        }

        .stat-label {
            color: #6c757d;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .recent-activity {
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <?php include 'topbar.php' ?>
    <?php include 'navbar.php' ?>
    <div class="toast" id="alert_toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-body text-white">
        </div>
    </div>
    <main id="view-panel">
        <?php $page = isset($_GET['page']) ? $_GET['page'] : 'home'; ?>
        <?php include $page . '.php' ?>


    </main>

    <div id="preloader"></div>
    <a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>

    <div class="modal fade" id="confirm_modal" role='dialog'>
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmation</h5>
                </div>
                <div class="modal-body">
                    <div id="delete_content"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id='confirm' onclick="">Continue</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="uni_modal" role='dialog'>
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id='submit'
                        onclick="$('#uni_modal form').submit()">Save</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="viewer_modal" role='dialog'>
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <button type="button" class="btn-close" data-dismiss="modal"><span class="fa fa-times"></span></button>
                <img src="" alt="">
            </div>
        </div>
    </div>
</body>

</html>

<script>
    window.start_load = function () {
        $('body').prepend('<di id="preloader2"></di>')
    }
    window.end_load = function () {
        $('#preloader2').fadeOut('fast', function () {
            $(this).remove();
        })
    }
    window.viewer_modal = function ($src = '') {
        start_load()
        var t = $src.split('.')
        t = t[1]
        if (t == 'mp4') {
            var view = $("<video src='assets/uploads/" + $src + "' controls autoplay></video>")
        } else {
            var view = $("<img src='assets/uploads/" + $src + "' />")
        }
        $('#viewer_modal .modal-content video,#viewer_modal .modal-content img').remove()
        $('#viewer_modal .modal-content').append(view)
        $('#viewer_modal').modal({
            show: true,
            backdrop: 'static',
            keyboard: false,
            focus: true
        })
        end_load()

    }
    window.uni_modal = function ($title = '', $url = '', $size = "") {
        start_load()
        $.ajax({
            url: $url,
            error: err => {
                console.log()
                alert("An error occured")
            },
            success: function (resp) {
                if (resp) {
                    $('#uni_modal .modal-title').html($title)
                    $('#uni_modal .modal-body').html(resp)
                    if ($size != '') {
                        $('#uni_modal .modal-dialog').addClass($size)
                    } else {
                        $('#uni_modal .modal-dialog').removeAttr("class").addClass("modal-dialog modal-md")
                    }
                    $('#uni_modal').modal({
                        show: true,
                        backdrop: 'static',
                        keyboard: false,
                        focus: true
                    })
                    end_load()
                }
            }
        })
    }
    window._conf = function ($msg = '', $func = '', $params = []) {
        $('#confirm_modal #confirm').attr('onclick', $func + "(" + $params.join(',') + ")")
        $('#confirm_modal .modal-body').html($msg)
        $('#confirm_modal').modal('show')
    }
    window.alert_toast = function ($msg = 'TEST', $bg = 'success') {
        $('#alert_toast').removeClass('bg-success')
        $('#alert_toast').removeClass('bg-danger')
        $('#alert_toast').removeClass('bg-info')
        $('#alert_toast').removeClass('bg-warning')

        if ($bg == 'success')
            $('#alert_toast').addClass('bg-success')
        if ($bg == 'danger')
            $('#alert_toast').addClass('bg-danger')
        if ($bg == 'info')
            $('#alert_toast').addClass('bg-info')
        if ($bg == 'warning')
            $('#alert_toast').addClass('bg-warning')
        $('#alert_toast .toast-body').html($msg)
        $('#alert_toast').toast({ delay: 3000 }).toast('show');
    }
    $(document).ready(function () {
        $('#preloader').fadeOut('fast', function () {
            $(this).remove();
        })
    })
    $('.datetimepicker').datetimepicker({
        format: 'Y/m/d H:i',
        startDate: '+3d'
    })
    $('.select2').select2({
        placeholder: "Please select here",
        width: "100%"
    })
</script>
<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Custom JavaScript -->
<script>
    // Ensure sidebar is closed when dashboard loads
    document.addEventListener('DOMContentLoaded', function () {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.querySelector('.overlay');

        if (sidebar) {
            sidebar.classList.remove('active');
        }

        if (overlay) {
            overlay.style.display = 'none';
        }

        // Add any dashboard-specific JavaScript here
    });
</script>