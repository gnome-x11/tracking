<?php
include "db_connect.php";

if (!isset($_SESSION["login_type"])) {
      header("Location: login.php");
      exit();
}

if (isset($_SESSION["invalid_attempts"]) && $_SESSION["invalid_attempts"] >= 3) {
      $_SESSION["invalid_attempts"] = 0;
}

$total_visitors_today = $conn->query("SELECT COUNT(*) as total FROM person_tracks WHERE DATE(date_created) = CURDATE()")->fetch_assoc()["total"];
$total_establishments = $conn->query("SELECT COUNT(*) as total FROM establishments")->fetch_assoc()["total"];
$total_visitors = $conn->query("SELECT COUNT(*) as total FROM visitors")->fetch_assoc()["total"];
$total_staff = $conn->query("SELECT COUNT(*) as total FROM users WHERE type = 2")->fetch_assoc()["total"];

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
      $daily[] = ["date" => $row["hour"], "total" => $row["total"]];
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

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | PLMUN ACS</title>

    <link rel="stylesheet" href="home.css?v=<?php echo time(); ?>">
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

            background-color: white;
        }
    </style>

</head>

<body>


    <!-- Main Dashboard Content -->
    <main class="dashboard-container">
        <!-- <div class="dashboard-header">
            <h1>Welcome to Pamantasan ng Lungsod ng Muntinlupa</h1> <br>
            <h2>You are now Entering <?php echo htmlspecialchars($_SESSION["login_name"]); ?> </h2>
        </div> -->
        <!-- eto yung admin side -->
        <?php if ($_SESSION["login_type"] == 1): ?>
            <!-- Statistics Cards -->
                <div class="card-header dashboard-card-header">
                    <b style="color: white; font-size: 30px;">Dashboard and Analytics</b><br><br><br>
                </div>

                    <div class="row">
                        <div class="col-md-3">
                            <a href="index.php?page=records" style="text-decoration: none; color: inherit;">
                                <div class="stat-card visitors" style="cursor: pointer;">
                                    <span class="float-right summary_icon"><i class="fa fa-users"></i></span>
                                    <div class="stat-value"><?php echo $total_visitors_today; ?></div>
                                    <div class="stat-label">Total Student Entered Today</div>
                                </div>
                            </a>
                        </div>

                        <div class="col-md-3">
                            <a href="index.php?page=establishment" style="text-decoration: none; color: inherit;"><div class="stat-card establishments">
                                <span class="float-right summary_icon"><i class="fa fa-building"></i></span>
                                <div class="stat-value"><?php echo $total_establishments; ?></div>
                                <div class="stat-label">Total Establishments</div>
                            </div>
                            </a>
                        </div>

                        <div class="col-md-3">
                            <a href="index.php?page=visitor" style="text-decoration: none; color: inherit;">
                            <div class="stat-card students">
                                <span class="float-right summary_icon"><i class="fa fa-graduation-cap"></i></span>
                                <div class="stat-value"><?php echo $total_visitors; ?></div>
                                <div class="stat-label">Total Visitors Today</div>
                            </div>
                            </a>
                        </div>

                        <div class="col-md-3">
                            <a href="index.php?page=users" style="text-decoration: none; color: inherit;">
                            <div class="stat-card staff">
                                <span class="float-right summary_icon"><i class="fa fa-user"></i></span>
                                <div class="stat-value"><?php echo $total_staff; ?></div>
                                <div class="stat-label">Total Staff</div>
                            </div>
                            </a>
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
                                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                    <span>Time Period Analytics</span>
                                    <select class="form-select form-select-sm" id="timePeriodSelect" style="width: 150px;">
                                        <option value="weekly">Weekly</option>
                                        <option value="monthly">Monthly</option>
                                        <option value="yearly">Yearly</option>
                                    </select>
                                </div>
                                <div class="card-body">
                                    <canvas id="timePeriodChart" ></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Add this code in the admin section where you want the table to appear -->
                    <div class="row mt-5">

                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                                    <span>Failed Login Attempts</span>
                                    <button class="btn btn-light btn-sm text-danger delete_records" data-id="all">Clear Logs</button>
                                </div>


                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="failedLoginsTable" class="table table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Establishment</th>
                                                    <th>Error Message</th>
                                                    <th>Date/Time</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $failed_logins = [];
                                                $failed_query = $conn->query("
                                                    SELECT f.id, f.establishment_id, e.name AS establishment_name, f.error_message, f.date_created
                                                    FROM failed_login_attempts f
                                                    LEFT JOIN establishments e ON f.establishment_id = e.id
                                                    ORDER BY f.date_created DESC
                                                ");
                                                while ($row = $failed_query->fetch_assoc()) {
                                                    $failed_logins[] = $row;
                                                }


                                                if(count($failed_logins) > 0):
                                                    foreach($failed_logins as $login): ?>
                                                    <tr>
                                                        <td><?php echo $login['id'] ?></td>
                                                        <td><?php echo htmlspecialchars($login['establishment_name'] ?? 'Unknown') ?></td>
                                                        <td><?php echo htmlspecialchars($login['error_message']) ?></td>
                                                        <td><?php echo date("M d, Y h:i A", strtotime($login['date_created'])) ?></td>
                                                    </tr>

                                                <?php endforeach;
                                                else: ?>
                                                    <tr>
                                                        <td colspan="4" class="text-center">No failed login attempts found</td>
                                                    </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <!-- DataTables Initialization -->
                    <script>
                        $(document).ready(function () {
                            $('#failedLoginsTable').DataTable({
                                "pageLength": 5,
                                "lengthChange": false, // hide the 'show x entries' dropdown
                            });
                        });
                    </script>
                    </div>

        <?php endif; ?>


        <!-- end of admin side -->
    </main>

    <!-- start ng staff dashboard -->
    <?php if ($_SESSION["login_type"] == 2): ?>


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
                <div class="col-md-12">
                    <div class="tracking-form">
                        <div class="card-body">
                            <div style="display: flex; justify-content: center;">
                                <img class="card-logo-plmun" src="assets/img/PLMUNLOGO.png">
                            </div>
                            <div class="welcome-message">
                              <h3 class="welcome-heading">Welcome to Pamantasan ng Lungsod ng Muntinlupa</h3><br>
                              <h3 class="entering-text">You are now entering <?php echo htmlspecialchars($_SESSION["login_name"]); ?></h3>

                            </div>
                            <h1 class="card-body-title">Please scan your QR Code to enter.</h1>
                            <hr>
                            <form action="" id="manage-records">
                                <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
                                <div class="form-group mb-3">
                                    <div class="qr-scanner-container">
                                        <!-- Student ID Input -->
                                        <input type="text" class="scanner-input" id="student_id" name="student_id"
                                               autocomplete="off" placeholder="Scan QR Code" oninput="checkIDAuto()">
                                        <input type="hidden" id="visitor_token" name="token" >
                                        <!-- Visitor Token Input (Hidden) -->
                                        <!-- QR Code Visual Elements -->
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
                                           value="<?php echo $_SESSION['login_establishment_id']; ?>">
                                </div>
                            </form>
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
                        </div>
                    </div>

                    <!-- Time-in Modal -->
                    <div class="modal fade" id="visitorTimeInModal" tabindex="-1" aria-labelledby="visitorTimeInLabel" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-success">
                          <div class="modal-header bg-success text-white">
                            <h5 class="modal-title" id="visitorTimeInLabel">Visitor Entry</h5>
                            <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body text-center">
                            <h4><strong>Entered <span id="timeInEstablishmentName"></span></strong></h4>
                            <p class="text-success fs-5 mt-3">✔ Confirmed</p>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- Time-out Modal -->
                    <div class="modal fade" id="visitorTimeOutModal" tabindex="-1" aria-labelledby="visitorTimeOutLabel" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-danger">
                          <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title" id="visitorTimeOutLabel">Visitor Exit</h5>
                            <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body text-center">
                            <h4><strong>Exited <span id="timeOutEstablishmentName"></span></strong></h4>
                            <p class="text-danger fs-5 mt-3">✔ Confirmed</p>
                          </div>
                        </div>
                      </div>
                    </div>


                <?php endif; ?>

                <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                                const studentId = $('#student_id').val().trim();

                                alert_toast("Data successfully saved", 'success');
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1500); // 1.5 seconds delay to see the toast
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
                        inactivityTimer = setTimeout(showSplash, 60000); // 30 sec
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
                            const scannedValue = $('#student_id').val().trim();
                            if (scannedValue !== "") {
                                // First: Try matching student ID
                                $.ajax({
                                    url: 'ajax.php?action=get_pdetails',
                                    method: "POST",
                                    data: { student_id: scannedValue },
                                    success: function (resp) {
                                        try {
                                            const json = JSON.parse(resp);

                                            if (json.status === 1) {
                                                // Valid student, show modal and submit form
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

                                            } else {
                                                // If not found as student, fallback to visitor scan
                                                scanVisitorToken(scannedValue);
                                            }
                                        } catch (e) {
                                            console.error("Student response parse error:", e);
                                            scanVisitorToken(scannedValue);
                                        }
                                    },
                                    error: function () {
                                        console.error("Student AJAX error. Trying visitor...");
                                        scanVisitorToken(scannedValue);
                                    }
                                });
                            } else {
                                reset_form();
                            }
                        }, 1500);
                    }

                    function scanVisitorToken(token) {

                        const establishmentId = '<?php echo $_SESSION['login_establishment_id']; ?>';
                        sessionStorage.setItem('last_visitor_token', token);

                        $.ajax({
                            url: 'ajax.php?action=scan_visitor',
                            method: "POST",
                            data: {
                                token: token,
                                establishment_id: establishmentId
                            },
                            success: function (resp) {
                                try {
                                    const result = JSON.parse(resp);
                                    if (result.status === "success") {
                                        const establishmentName = '<?php echo $_SESSION['login_establishment_name']; ?>';
                                        let actionText = "";
                                        let modal;

                                        if (result.action === 'time_in') {
                                            actionText = "Visitor Time-in Recorded";
                                            $('#timeInEstablishmentName').text(establishmentName);
                                            modal = new bootstrap.Modal(document.getElementById('visitorTimeInModal'));
                                        } else if (result.action === 'time_out') {
                                            actionText = "Visitor Time-out Recorded";
                                            $('#timeOutEstablishmentName').text(establishmentName);
                                            modal = new bootstrap.Modal(document.getElementById('visitorTimeOutModal'));
                                        }

                                        modal.show();

                                        // Auto close modal after 3 seconds, then show toast
                                        setTimeout(() => {
                                            modal.hide();
                                            alert_toast(actionText, 'success');
                                        }, 3000);

                                    } else {
                                        alert_toast(result.message || "Invalid visitor token", 'danger');
                                    }
                                } catch (e) {
                                    console.error("Visitor response parse error:", e);
                                    alert_toast("Unexpected error while scanning visitor QR", 'danger');
                                }
                            },
                            error: function () {
                                alert_toast("Failed to connect. Please try again.", 'danger');
                            }
                        });

                        // Reset scanner input after visitor attempt
                        // Reset scanner input and refocus after visitor attempt
                        setTimeout(() => {
                            const input = $('#student_id');
                            input.val('');
                            input.focus();
                        }, 2000);

                    }

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

                    </script>


                    <!-- //ito naman yung mga graph ng charts adjust mo nalang kung gusto mo sa chart.js -->
<script>


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
                                    labels: <?= json_encode(array_column($courses, "course")) ?>,
                                    datasets: [{
                                        label: 'Students',
                                        data: <?= json_encode(array_column($courses, "count")) ?>,
                                        backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4bc0c0', '#9966ff'],
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        legend: {
                                            display: true,
                                            position: 'bottom',
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
                                    labels: <?= json_encode(array_column($estabs, "est_name")) ?>,
                                    datasets: [{
                                        label: 'Entries',
                                        data: <?= json_encode(array_column($estabs, "total")) ?>,
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
                                    labels: <?= json_encode(
                                          array_map(function ($h) {
                                                return date("g A", mktime($h["date"]));
                                          }, $daily)
                                    ) ?>,

                                    datasets: [{
                                        label: 'Entries',
                                        data: <?= json_encode(array_column($daily, "total")) ?>,
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
                        // Combined Time Period Chart
                        if (document.getElementById('timePeriodChart')) {
                            const timePeriodChartCtx = document.getElementById('timePeriodChart').getContext('2d');

                            // Prepare data for each time period
                            const timePeriodData = {
                                weekly: {
                                    labels: <?= json_encode(
                                          array_map(function ($d) {
                                                return date("D, M j", strtotime($d["date"]));
                                          }, $weekly)
                                    ) ?>,
                                    data: <?= json_encode(array_column($weekly, "total")) ?>,
                                    label: 'Weekly Entries',
                                    borderColor: '#ffc107',
                                    backgroundColor: 'rgba(255, 193, 7, 0.1)'
                                },
                                monthly: {
                                    labels: <?= json_encode(array_column($monthly, "month")) ?>,
                                    data: <?= json_encode(array_column($monthly, "total")) ?>,
                                    label: 'Monthly Entries',
                                    borderColor: '#dc3545',
                                    backgroundColor: 'rgba(220, 53, 69, 0.1)'
                                },
                                yearly: {
                                    labels: <?= json_encode(array_column($yearly, "year")) ?>,
                                    data: <?= json_encode(array_column($yearly, "total")) ?>,
                                    label: 'Yearly Entries',
                                    borderColor: '#17a2b8',
                                    backgroundColor: 'rgba(23, 162, 184, 0.1)'
                                }
                            };

                            // Create the initial chart (weekly by default)
                            const timePeriodChart = new Chart(timePeriodChartCtx, {
                                type: 'line',
                                data: {
                                    labels: timePeriodData.weekly.labels,
                                    datasets: [{
                                        label: timePeriodData.weekly.label,
                                        data: timePeriodData.weekly.data,
                                        borderColor: timePeriodData.weekly.borderColor,
                                        backgroundColor: timePeriodData.weekly.backgroundColor,
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

                            // Handle time period selection change
                            document.getElementById('timePeriodSelect').addEventListener('change', function() {
                                const selectedPeriod = this.value;
                                const periodData = timePeriodData[selectedPeriod];

                                // Update chart data
                                timePeriodChart.data.labels = periodData.labels;
                                timePeriodChart.data.datasets[0].data = periodData.data;
                                timePeriodChart.data.datasets[0].label = periodData.label;
                                timePeriodChart.data.datasets[0].borderColor = periodData.borderColor;
                                timePeriodChart.data.datasets[0].backgroundColor = periodData.backgroundColor;

                                // Update chart title
                                timePeriodChart.options.plugins.title.text = periodData.label;

                                // Update chart type (bar for monthly/yearly, line for weekly)
                                timePeriodChart.config.type = selectedPeriod === 'weekly' ? 'line' : 'bar';

                                timePeriodChart.update();
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

                    $(document).ready(function () {
                        // Get stored visitor token
                        const lastVisitorToken = sessionStorage.getItem('last_visitor_token');

                        if (lastVisitorToken) {
                            $('#visitor_token')
                                .val(lastVisitorToken)
                                .focus()
                                .select();
                            // Clear the stored value after restoring
                            sessionStorage.removeItem('last_visitor_token');
                        } else {
                            // Default focus if no stored value
                            $('#visitor_token').focus();
                        }
                    });


                    // Confirmation dialog function
                      function _conf(message, callback, args = []) {
                          if (confirm(message)) {
                              if (typeof window[callback] === "function") {
                                  window[callback](...args);
                              }
                          }
                      }

                      // Handle button click and call _conf
                      $('.delete_records').click(function () {
                          _conf("Are you sure you want to clear all logs?", "delete_records", [$(this).attr('data-id')]);
                      });

                      // Actual deletion logic via AJAX
                      function delete_records(id) {
                          if (id === "all") {
                              $.ajax({
                                  url: "ajax/clear_failed_logs.php",
                                  method: "POST",
                                  data: { action: "clear_all" },
                                  success: function (res) {
                                      alert("All logs cleared successfully!");
                                      location.reload();
                                  },
                                  error: function () {
                                      alert("Failed to clear logs. Please try again.");
                                  }
                              });
                          }
                      }
                </script>
</body>

</html>
