<?php

include 'db_connect.php';

if (isset($_SESSION["invalid_attempts"]) && $_SESSION["invalid_attempts"] >= 3) {
    $_SESSION["invalid_attempts"] = 0;
}

include 'header.php';
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dashboard | PLMUN ACS</title>
        <link rel="stylesheet" href="page-css/home.css">
    </head>
    <style>
        body{
            background-image: url('assets/img/bg.png');
            background-repeat: no-repeat;
            background-size: cover;
        }
    </style>


<body>
    <main class="dashboard-container">
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
                                <h3 class="entering-text">You are now entering
                                    <?php echo htmlspecialchars($_SESSION["login_name"]); ?>
                                </h3>

                            </div>
                            <h1 class="card-body-title">Please scan your QR Code to enter.</h1>
                            <hr>
                            <form action="" id="manage-records">
                                <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
                                <div class="form-group mb-3">
                                    <div class="qr-scanner-container">
                                        <input type="text" class="scanner-input" id="student_id" name="student_id"
                                            autocomplete="off" placeholder="Scan QR Code" oninput="checkIDAuto()">
                                        <input type="hidden" id="visitor_token" name="token">
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
                    <div class="modal fade" id="visitorTimeInModal" tabindex="-1" aria-labelledby="visitorTimeInLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-success">
                                <div class="modal-header bg-success text-white">
                                    <h5 class="modal-title" id="visitorTimeInLabel">Visitor Entry</h5>
                                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body text-center">
                                    <h4><strong>Entered <span id="timeInEstablishmentName"></span></strong></h4>
                                    <p class="text-success fs-5 mt-3">Confirmed</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Time-out Modal -->
                    <div class="modal fade" id="visitorTimeOutModal" tabindex="-1" aria-labelledby="visitorTimeOutLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content border-danger">
                                <div class="modal-header bg-danger text-white">
                                    <h5 class="modal-title" id="visitorTimeOutLabel">Visitor Exit</h5>
                                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body text-center">
                                    <h4><strong>Exited <span id="timeOutEstablishmentName"></span></strong></h4>
                                    <p class="text-danger fs-5 mt-3">Confirmed</p>
                                </div>
                            </div>
                        </div>
                    </div>

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

                </div>
            </div>
        </div>
    </main>
</body>
</html>
<script src="page_js/staff.js"></script>

<script>
const establishmentId = '<?php echo $_SESSION['login_establishment_id'] ?? null; ?>';
const establishmentName = '<?php echo $_SESSION['login_establishment_name'] ?? null; ?>';
</script>
