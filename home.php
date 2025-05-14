<?php
include 'db_connect.php';


if (!isset($_SESSION['login_type'])) {
    header("Location: login.php");
    exit();
}

if (isset($_SESSION['invalid_attempts']) && $_SESSION['invalid_attempts'] >= 3) {
    $_SESSION['invalid_attempts'] = 0;
}

$total_visitors_today = $conn->query("SELECT COUNT(*) as total FROM person_tracks WHERE DATE(date_created) = CURDATE()")->fetch_assoc()['total'];
$total_establishments = $conn->query("SELECT COUNT(*) as total FROM establishments")->fetch_assoc()['total'];
$total_students = $conn->query("SELECT COUNT(*) as total FROM persons")->fetch_assoc()['total'];
$total_staff = $conn->query("SELECT COUNT(*) as total FROM users WHERE type = 2")->fetch_assoc()['total'];


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

$yearly = [];
$yearly_query = $conn->query("SELECT DATE_FORMAT(date_created, '%Y') as year, COUNT(*) as total
    FROM person_tracks
    GROUP BY year");

while ($row = $yearly_query->fetch_assoc()) {
    $yearly[] = $row;
}


$hourly = [];
$hourly_query = $conn->query("SELECT HOUR(date_created) as hour, COUNT(*) as total
    FROM person_tracks
    GROUP BY hour ORDER BY hour");
while ($row = $hourly_query->fetch_assoc()) {
    $hourly[] = $row;
}


$perPage = 5;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $perPage;
$totalQuery = $conn->query("SELECT COUNT(*) as total FROM failed_login_attempts");
$totalRow = $totalQuery->fetch_assoc();
$totalResults = $totalRow['total'];
$totalPages = ceil($totalResults / $perPage);


$failed_attempts = [];
if ($_SESSION['login_type'] == 1) {
    $user_id = $_SESSION['login_id'];
    $query = $conn->query("
        SELECT f.date_created, e.name as establishment, f.error_message
        FROM failed_login_attempts f
        JOIN establishments e ON f.establishment_id = e.id
        ORDER BY f.date_created DESC
        LIMIT $offset, $perPage
    ");

    while ($row = $query->fetch_assoc()) {
        $failed_attempts[] = $row;
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | PLMUN ACS</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">

    <!-- Custom CSS -->

    <style>
        body {
            background-image: url("assets/img/bg.png");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
    </style>

</head>

<body>


    <!-- Main Dashboard Content -->
    <main class="dashboard-container">
        <div class="dashboard-header">
            <h1>Welcome to Pamantasan ng Lungsod ng Muntinlupa</h1> <br>
            <h2>You are now Entering <?php echo htmlspecialchars($_SESSION['login_name']); ?> </h2>
        </div>
        <!-- eto yung admin side -->
        <?php if ($_SESSION['login_type'] == 1): ?>
            <!-- Statistics Cards -->
            <div class="card dashboard-card">
                <div class="card-header dashboard-card-header">
                    <b>Dashboard and Analytics</b>
                </div>
                <div class="card-body dashboard-card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="stat-card visitors">
                                <span class="float-right summary_icon"><i class="fa fa-users"></i></span>
                                <div class="stat-value"><?php echo $total_visitors_today; ?></div>
                                <div class="stat-label">Total Visitors Today</div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="stat-card establishments">
                                <span class="float-right summary_icon"><i class="fa fa-building"></i></span>
                                <div class="stat-value"><?php echo $total_establishments; ?></div>
                                <div class="stat-label">Total Establishments</div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="stat-card students">
                                <span class="float-right summary_icon"><i class="fa fa-graduation-cap"></i></span>
                                <div class="stat-value"><?php echo $total_students; ?></div>
                                <div class="stat-label">Total Students</div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="stat-card staff">
                                <span class="float-right summary_icon"><i class="fa fa-user"></i></span>
                                <div class="stat-value"><?php echo $total_staff; ?></div>
                                <div class="stat-label">Total Staff</div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card dashboard-card">
                                <div class="card-header dashboard-card-header">
                                    <b>Failed Login Attempts</b>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Establishment</th>
                                                    <th>Error</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($failed_attempts as $attempt): ?>
                                                    <tr>
                                                        <td><?php echo $attempt['date_created']; ?></td>
                                                        <td><?php echo $attempt['establishment']; ?></td>
                                                        <td><?php echo $attempt['error_message']; ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Pagination -->
                                    <nav>
                                        <ul class="pagination justify-content-center">
                                            <?php if ($page > 1): ?>
                                                <li class="page-item"><a class="page-link"
                                                        href="?page=<?php echo $page - 1; ?>">Previous</a></li>
                                            <?php endif; ?>

                                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                                <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                                    <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                                </li>
                                            <?php endfor; ?>

                                            <?php if ($page < $totalPages): ?>
                                                <li class="page-item"><a class="page-link"
                                                        href="?page=<?php echo $page + 1; ?>">Next</a></li>
                                            <?php endif; ?>
                                        </ul>
                                    </nav>

                                    <button class="btn btn-sm btn-outline-danger delete_person mb-2 mb-md-0" type="button"
                                        data-id="<?php echo $row['id']; ?>">Delete all logs</button>

                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts Section -->
                    <div class="row mt-5">
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-header bg-info text-white">Course Distribution</div>
                                <div class="card-body">
                                    <canvas id="courseChart"></canvas>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-header bg-success text-white">Establishments frequency</div>
                                <div class="card-body">
                                    <canvas id="estabChart"></canvas>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-header bg-success text-white">Daily Entries</div>
                                <div class="card-body">
                                    <canvas id="dailyChart"></canvas>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-header bg-warning text-white">Weekly Entries</div>
                                <div class="card-body">
                                    <canvas id="weeklyChart"></canvas>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-header bg-danger text-white">Monthly Entries</div>
                                <div class="card-body">
                                    <canvas id="monthlyChart"></canvas>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-header bg-info text-white">Yearly Entries</div>
                                <div class="card-body">
                                    <canvas id="yearlyChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif ?>

        <!-- end of admin side -->

        </div>
    </main>

    <!-- start ng staff dashboard -->
    <?php if ($_SESSION['login_type'] == 2): ?>


        <div id="splashOverlay">
            <video id="localVideo" muted loop playsinline>
                <source src="assets/img/vids/splash.mp4" type="video/mp4">
            </video>
        </div>

        <!-- <div id="splashOverlay">
            <video id="localVideo" loop playsinline autoplay>
                <source src="assets/img/vids/splash.mp4" type="video/mp4">
            </video>
        </div> -->


        <div class="container">
            <div class="row">
                <!-- Left side: Tracking Form -->
                <div class="col-md-12">
                    <div class="tracking-form">
                        <div class="card-body">
                            <div style="display: flex; justify-content: center;">
                                <img class="card-logo-plmun" src="assets/img/PLMUNLOGO.png">
                            </div>

                            <h1 class="card-body-title">PLASE SCAN YOUR QR CODE TO ENTER</h1>

                            <hr>
                            <form action="" id="manage-records">
                                <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
                                <div class="form-group mb-3">
                                    <div class="qr-scanner-container">
                                        <!-- Input Field (Behind) -->
                                        <input type="number" class="scanner-input" id="student_id" name="student_id"
                                            autocomplete="off" placeholder="Enter Student ID" oninput="checkIDAuto()">



                                        <!-- QR Code Image Design (Front) -->
                                        <div class="qr-overlay">
                                            <img src="assets/img/barcodelogo.png" alt="QR Code" class="qr-code-image">
                                            <div class="scan-line"></div>
                                            <div class="laser-dot"></div>
                                            <div class="scanner-frame"></div>
                                        </div>

                                    </div>


                                    <div class="text-center mt-4">
                                        <button class="btn btn-success btn-lg" type="button" id="manage_visitor">
                                            Visitor Entry Here
                                        </button>
                                    </div>
                                </div>

                                <div id="details" style="display:none">
                                    <input type="hidden" name="person_id" value="">
                                    <input type="hidden" name="establishment_id"
                                        value="<?php echo $_SESSION['login_establishment_id'] ?>">
                                </div>
                                <!-- ETO YUNG STUDENT INFO -->

                                <div class="modal fade" id="studentModal" tabindex="-1" aria-labelledby="studentModalLabel"
                                    aria-hidden="true" style="margin-top: 70px;">
                                    <div class="modal-dialog modal-lg" style="max-width: 900px;">
                                        <div class="modal-content border-0" style="background-color: #f8f9fa;">
                                            <!-- Header -->
                                            <div class="modal-header py-3" style="background-color: #1a5632; color: white;">
                                                <h5 class="modal-title fw-bold">STUDENT VERIFICATION</h5>
                                                <button type="button" class="btn-close btn-close-white"
                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>

                                            <div class="modal-body p-4">
                                                <div class="row g-4">
                                                    <!-- Photo Column with Fixed Aspect Ratio Container -->
                                                    <div class="col-md-5">
                                                        <div class="photo-container-wrapper"
                                                            style="height: 0; padding-bottom: 125%; position: relative; background-color: #e8f5e9; border-radius: 10px; overflow: hidden;">
                                                            <div class="d-flex justify-content-center align-items-center"
                                                                style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; padding: 15px;">
                                                                <img id="studentPhoto" alt="Student Photo" class="img-fluid"
                                                                    style="object-fit: contain; max-height: 100%; max-width: 100%; height: auto; width: auto;">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Info Column -->
                                                    <div class="col-md-7">
                                                        <div class="student-details ps-3">
                                                            <h3 class="student-name fw-bold mb-4" style="color: #1a5632;"
                                                                id="studentName">
                                                            </h3>

                                                            <div class="detail-item mb-3">
                                                                <div class="detail-label mb-1"
                                                                    style="color: #4a8c5e; font-size: 0.9rem; letter-spacing: 0.5px;">
                                                                    COLLEGE</div>
                                                                <div class="detail-value fs-5 fw-medium"
                                                                    style="color: #333;" id="studentCollege"></div>
                                                            </div>

                                                            <div class="detail-item mb-3">
                                                                <div class="detail-label mb-1"
                                                                    style="color: #4a8c5e; font-size: 0.9rem; letter-spacing: 0.5px;">
                                                                    COURSE
                                                                </div>
                                                                <div class="detail-value fs-5 fw-medium"
                                                                    style="color: #333;" id="studentCourse"></div>
                                                            </div>

                                                            <div class="detail-item mb-3">
                                                                <div class="detail-label mb-1"
                                                                    style="color: #4a8c5e; font-size: 0.9rem; letter-spacing: 0.5px;">
                                                                    YEAR
                                                                    LEVEL</div>
                                                                <div class="detail-value fs-5 fw-medium"
                                                                    style="color: #333;" id="studentYear">
                                                                </div>
                                                            </div>

                                                            <div class="detail-item mb-3">
                                                                <div class="detail-label mb-1"
                                                                    style="color: #4a8c5e; font-size: 0.9rem; letter-spacing: 0.5px;">
                                                                    STANDING</div>
                                                                <div class="detail-value fs-5 fw-medium"
                                                                    style="color: #333;" id="studentStanding"></div>
                                                            </div>

                                                            <div class="verification-status mt-4 pt-3"
                                                                style="border-top: 1px solid #e0e0e0;">
                                                                <div
                                                                    class="d-flex justify-content-between align-items-center">
                                                                    <div class="text-muted small">
                                                                        <i class="bi bi-clock me-1"></i> Verified: <span
                                                                            id="verificationTime"></span>
                                                                    </div>
                                                                    <span class="badge py-2 px-3"
                                                                        style="background-color: #1a5632; color: white;">
                                                                        <i class="bi bi-check-circle me-1"></i>VALID
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <script>
                                    // JavaScript to handle image loading and sizing
                                    document.addEventListener('DOMContentLoaded', function () {
                                        const studentPhoto = document.getElementById('studentPhoto');

                                        function adjustImage() {
                                            if (!studentPhoto.complete) return;

                                            const container = studentPhoto.closest('.photo-container-wrapper');
                                            const containerWidth = container.offsetWidth;
                                            const containerHeight = container.offsetHeight;

                                            if (studentPhoto.naturalWidth > studentPhoto.naturalHeight) {
                                                // Landscape image
                                                studentPhoto.style.width = '90%';
                                                studentPhoto.style.height = 'auto';
                                            } else {
                                                // Portrait or square image
                                                studentPhoto.style.height = '90%';
                                                studentPhoto.style.width = 'auto';
                                            }
                                        }

                                        // Run on load and if image loads later
                                        studentPhoto.onload = adjustImage;
                                        if (studentPhoto.complete) adjustImage();
                                    });
                                </script>
                        </div>
                    </div>
                <?php endif ?>

                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                <script>

                    $('#manage-records').submit(function (e) {
                        e.preventDefault();
                        $.ajax({
                            url: 'ajax.php?action=save_track',
                            data: new FormData($(this)[0]),
                            cache: false,
                            contentType: false,
                            processData: false,
                            method: 'POST',
                            success: function (resp) {
                                resp = JSON.parse(resp);
                                if (resp.status == 1) {
                                    // Save student ID to localStorage before reload
                                    const studentId = $('#student_id').val().trim();
                                    // sessionStorage.setItem('last_student_id', studentId);

                                    alert_toast("Data successfully saved", 'success');
                                    setTimeout(() => {
                                    }, 300);
                                    window.location.reload(); // Your existing reload logic
                                }
                            }
                        });
                    });


                    $('#manage_visitor').click(function () {
                        uni_modal("Visitor Entry", "manage_visitor.php", "mid-large")

                    })

                    $(document).ready(function () {

                        $('.delete_person').click(function () {
                            var id = $(this).data('id');
                            delete_logs(id);
                        });
                    });

                    let inactivityTimer;
                    const overlay = document.getElementById('splashOverlay');
                    const localVideo = document.getElementById('localVideo');

                    function resetTimer() {
                        clearTimeout(inactivityTimer);
                        hideSplash();
                        inactivityTimer = setTimeout(showSplash, 20000); // 30 sec
                    }

                    function showSplash() {
                        overlay.style.display = 'flex';
                        localVideo.currentTime = 0; // Reset to start
                        localVideo.play().catch(error => {
                            console.log('Video play prevented:', error);
                        });
                    }

                    function hideSplash() {
                        overlay.style.display = 'none';
                        localVideo.pause();
                    }

                    // Event listeners for user activity
                    const events = ['mousemove', 'keydown', 'click', 'scroll', 'touchstart'];
                    events.forEach(event => {
                        document.addEventListener(event, resetTimer);
                    });

                    // Initial setup
                    resetTimer();

                    let timeout = null;

                    function checkIDAuto() {
                        clearTimeout(timeout);
                        timeout = setTimeout(() => {
                            const studentId = $('#student_id').val().trim();
                            if (studentId !== "") {
                                $.ajax({
                                    url: 'ajax.php?action=get_pdetails',
                                    method: "POST",
                                    data: { student_id: studentId },
                                    success: function (resp) {
                                        try {
                                            const json = JSON.parse(resp);

                                            if (json.status === 1) {
                                                // Update student info
                                                $('#studentName').text(json.name);
                                                $('#studentCollege').text(json.college);
                                                $('#studentCourse').text(json.course);
                                                $('#studentYear').text(json.year_level);
                                                $('#studentStanding').text(json.standing);

                                                if (json.photo) {
                                                    $('#studentPhoto').attr('src', json.photo);
                                                }

                                                $('#studentModal').modal('show');

                                                $('[name="person_id"]').val(json.id);
                                                $('#details').show();

                                                setTimeout(() => {
                                                    $('#manage-records').submit();
                                                }, 5000);

                                            } else if (json.status === 3) {
                                                alert_toast("Maximum attempts reached. Redirecting...", 'danger');
                                                setTimeout(() => window.location.reload(), 2000);

                                            } else if (json.status === 2 && json.attempts !== undefined) {
                                                alert_toast(`Invalid! Attempts left: ${3 - json.attempts}`, 'danger');
                                                $('#details').hide();

                                            } else {
                                                alert_toast("Student not found!", 'danger');
                                                $('#details').hide();
                                            }
                                        } catch (e) {
                                            console.error("Parsing error:", e);
                                            console.error("Server returned:", resp);
                                            alert_toast("Unexpected server response. Check console for details.", 'danger');
                                            $('#details').hide();
                                        }
                                    },
                                    error: function (xhr, status, error) {
                                        console.error('AJAX error:', error);
                                        console.error('Full response:', xhr.responseText);
                                        $('#details').hide();
                                        alert_toast("Connection error. Please try again.", 'danger');
                                    }
                                });
                            } else {
                                reset_form();
                            }
                        }, 1500);
                    }

                </script>


                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const sidebar = document.getElementById('sidebar');
                        const overlay = document.querySelector('.overlay');

                        if (sidebar) {
                            sidebar.classList.remove('active');
                        }

                        if (overlay) {
                            overlay.style.display = 'none';
                        }
                    });

                    $('#saveVisitor').click(function (e) {
                        e.preventDefault();
                        var formData = new FormData($('#visitorForm')[0]);

                        $.ajax({
                            url: 'ajax.php?action=save_visitor',
                            method: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function (resp) {
                                resp = JSON.parse(resp);
                                if (resp.status == 1) {
                                    alert_toast("Visitor saved successfully", 'success');
                                    $('#visitorModal').modal('hide');
                                    $('#visitorForm')[0].reset();
                                } else {
                                    alert_toast("Error saving visitor", 'danger');
                                }
                            }
                        });
                    });





                    function delete_logs($id) {
                        start_load()
                        $.ajax({
                            url: 'ajax.php?action=delete_logs',
                            method: 'POST',
                            data: { id: $id },
                            success: function (resp) {
                                if (resp == 1) {
                                    alert_toast("Data successfully deleted", 'danger')
                                    setTimeout(function () {
                                        location.reload()
                                    }, 1500)

                                }
                            }
                        })
                    }


                    function get_person() {
                        start_load()
                        $.ajax({
                            url: 'ajax.php?action=get_pdetails',
                            method: "POST",
                            data: { student_id: $('#student_id').val() },
                            success: function (resp) {
                                if (resp) {
                                    resp = JSON.parse(resp)
                                    if (resp.status == 1) {
                                        // Update student card
                                        $('#studentName').text(resp.name)
                                        $('#studentCollege').text(resp.college)
                                        $('#studentCourse').text(resp.course)
                                        $('#studentYear').text(resp.year_level)
                                        $('#studentStanding').text(resp.standing)
                                        // Set photo if available
                                        if (resp.photo) {
                                            $('#studentPhoto').attr('src', resp.photo)
                                        } else {
                                            $('#studentPhoto').attr('src', 'path/to/default/photo.jpg') //dagdagan mo nalang to ng default photo para may fallback ka incase mag error path ng image
                                        }

                                        // Show the student card
                                        $('#studentCard').fadeIn()

                                        // Update form fields
                                        $('[name="person_id"]').val(resp.id)
                                        $('#details').show()
                                        end_load()


                                    }
                                }
                            }
                        })
                    }

                    function reset_form() {
                        $('#student_id').val('')
                        $('#details').hide()
                        $('#studentCard').hide()
                    }


                    //ito naman yung mga graph ng charts adjust mo nalang kung gusto mo sa chart.js

                    document.addEventListener('DOMContentLoaded', function () {
                        // College & Course Chart
                        if (document.getElementById('collegeCourseChart')) {
                            const rawData = <?= json_encode($college_course) ?>;
                            const colleges = [...new Set(rawData.map(item => item.college))];
                            const courses = [...new Set(rawData.map(item => item.course))];

                            const datasets = courses.map(course => {
                                return {
                                    label: course,
                                    data: colleges.map(col => {
                                        const record = rawData.find(row => row.college === col && row.course === course);
                                        return record ? parseInt(record.total) : 0;
                                    }),
                                    backgroundColor: `hsl(${Math.random() * 360}, 60%, 70%)`
                                };
                            });


                        }

                        // Course Donut Chart
                        if (document.getElementById('courseChart')) {
                            const courseChart = new Chart(document.getElementById('courseChart'), {
                                type: 'doughnut',
                                data: {
                                    labels: <?= json_encode(array_column($courses, 'course')) ?>,
                                    datasets: [{
                                        label: 'Students',
                                        data: <?= json_encode(array_column($courses, 'count')) ?>,
                                        backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4bc0c0', '#9966ff'],
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        legend: {
                                            display: true,
                                            position: 'left',
                                            align: 'center'
                                        },
                                        title: {
                                            display: true,
                                            text: 'Top Courses Today'
                                        }
                                    }
                                }
                            });
                        }


                        // Establishment Chart
                        if (document.getElementById('estabChart')) {
                            const estabChart = new Chart(document.getElementById('estabChart'), {
                                type: 'bar',
                                data: {
                                    labels: <?= json_encode(array_column($estabs, 'est_name')) ?>,
                                    datasets: [{
                                        label: 'Entries',
                                        data: <?= json_encode(array_column($estabs, 'total')) ?>,
                                        backgroundColor: '#28a745'
                                    }]
                                }
                            });
                        }

                        // Daily Chart
                        if (document.getElementById('dailyChart')) {
                            const dailyChart = new Chart(document.getElementById('dailyChart'), {
                                type: 'bar',
                                data: {
                                    labels: <?= json_encode(array_map(function ($h) {
                                        return date('g A', mktime($h['date']));
                                    }, $daily)) ?>,

                                    datasets: [{
                                        label: 'Entries',
                                        data: <?= json_encode(array_column($daily, 'total')) ?>,
                                        borderColor: '#17a2b8',
                                        backgroundColor: 'rgba(23, 162, 184, 0.1)',
                                        borderWidth: 2,
                                        fill: true
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    plugins: {
                                        title: {
                                            display: true,
                                            text: 'Today\'s Entries by Hour'
                                        }
                                    },
                                    scales: {
                                        y: {
                                            beginAtZero: true,
                                            ticks: {
                                                stepSize: 1
                                            }
                                        }
                                    }
                                }
                            });
                        }

                        // Weekly Chart
                        if (document.getElementById('weeklyChart')) {
                            const weeklyChart = new Chart(document.getElementById('weeklyChart'), {
                                type: 'line',
                                data: {
                                    labels: <?= json_encode(array_map(function ($d) {
                                        return date('D, M j', strtotime($d['date']));
                                    }, $weekly)) ?>,
                                    datasets: [{
                                        label: 'Entries',
                                        data: <?= json_encode(array_column($weekly, 'total')) ?>,
                                        borderColor: '#ffc107',
                                        backgroundColor: 'rgba(255, 193, 7, 0.1)',
                                        borderWidth: 2,
                                        fill: true
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    plugins: {
                                        title: {
                                            display: true,
                                            text: 'Weekly Entries'
                                        }
                                    },
                                    scales: {
                                        y: {
                                            beginAtZero: true,
                                            ticks: {
                                                stepSize: 1
                                            }
                                        }
                                    }
                                }
                            });
                        }

                        // Monthly Chart
                        if (document.getElementById('monthlyChart')) {
                            const monthlyChart = new Chart(document.getElementById('monthlyChart'), {
                                type: 'bar',
                                data: {
                                    labels: <?= json_encode(array_column($monthly, 'month')) ?>,
                                    datasets: [{
                                        label: 'Entries',
                                        data: <?= json_encode(array_column($monthly, 'total')) ?>,
                                        borderColor: '#dc3545',
                                        backgroundColor: 'rgba(220, 53, 69, 0.1)',
                                        borderWidth: 2,
                                        fill: true
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    plugins: {
                                        title: {
                                            display: true,
                                            text: 'Monthly Entries'
                                        }
                                    },
                                    scales: {
                                        y: {
                                            beginAtZero: true
                                        }
                                    }
                                }
                            });
                        }

                        // yearly Chart
                        if (document.getElementById('yearlyChart')) {
                            const yearlyChart = new Chart(document.getElementById('yearlyChart'), {
                                type: 'bar',
                                data: {
                                    labels: <?= json_encode(array_column($yearly, 'year')) ?>,
                                    datasets: [{
                                        label: 'Entries',
                                        data: <?= json_encode(array_column($yearly, 'total')) ?>,
                                        borderColor: '#17a2b8',
                                        backgroundColor: 'rgba(23, 162, 184, 0.1)',
                                        borderWidth: 2,
                                        fill: true
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    plugins: {
                                        title: {
                                            display: true,
                                            text: 'Yearly Entries'
                                        }
                                    },
                                    scales: {
                                        y: {
                                            beginAtZero: true
                                        }
                                    }
                                }
                            });
                        }

                    });


                    $(document).ready(function () {
                        // Get stored student ID
                        const lastStudentId = sessionStorage.getItem('last_student_id');

                        if (lastStudentId) {
                            $('#student_id')
                                .val(lastStudentId)
                                .focus()
                                .select();
                            // Clear the stored value
                            sessionStorage.removeItem('last_student_id');
                        } else {
                            // Default focus if no stored value
                            $('#student_id').focus();
                        }
                    });
                </script>
</body>

</html>