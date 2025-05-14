<?php
include 'db_connect.php';

$id = $_GET['id'] ?? 0;
$person = $conn->query("SELECT *, concat(lastname,', ',firstname,' ',middlename) as name, 
        concat(address,', ',street,', ',baranggay,', ',city,', ',state,', ',zip_code) as caddress 
        FROM persons WHERE id = $id")->fetch_assoc();
?>
<!DOCTYPE html>
<html>

<head>
    <title>PLMun Student ID</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style type="text/css">
        :root {
            --plmun-dark-green: #006400;
            --plmun-light-green: #228B22;
            --plmun-gold: #D4AF37;
        }

        body {
            background-color: #f0f0f0;
            padding: 30px;
        }

        .student-id-card {
            width: 100%;
            max-width: 380px;
            border: 4px solid var(--plmun-dark-green);
            border-radius: 12px;
            padding: 25px;
            background: white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            margin: 0 auto;
            position: relative;
        }

        .id-header {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--plmun-gold);
        }

        .school-logo {
            width: 50px;
            height: 50px;
            margin-bottom: 15px;
        }

        .school-name {
            font-size: 15px;
            font-weight: 700;
            color: var(--plmun-dark-green);
            margin-bottom: 5px;
            text-align: center;
        }

        .student-photo {
            width: 150px;
            height: 150px;
            margin: 15px auto;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .student-name {
            font-size: 20px;
            font-weight: 600;
            text-align: center;
            margin: 10px 0 5px;
        }

        .student-id {
            font-size: 16px;
            text-align: center;
            color: var(--plmun-dark-green);
            font-weight: 500;
            margin-bottom: 30px;
        }

        .id-qr-container {
            text-align: center;
            margin: 20px 0;
            min-height: 180px;
        }

        #qrCodeContainer {
            width: 150px;
            height: 150px;
            margin: 0 auto;
            border: 0 none;
            padding: 5px;
            background: white;
            display: block;
        }

        .student-details {
            font-size: 12px;
            line-height: 1.4;
            margin-top: 10px;
            color: #222;
        }

        .student-details .detail-line {
            margin-bottom: 2px;
        }

        .student-details .label {
            font-weight: 600;
            color: var(--plmun-dark-green);
        }

        .student-photo {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f0f0f0;
            /* Optional: fallback bg */
        }

        .student-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .student-photo .default-icon {
            font-size: 48px;
            color: #006400;
        }


        .id-footer {
            text-align: center;
            font-size: 11px;
            color: #666;
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px solid #eee;
        }

        .access-history {
            margin-top: 30px;
        }

        .history-table th {
            background-color: var(--plmun-dark-green);
            color: white;
        }

        .btn-print {
            background-color: var(--plmun-dark-green);
            border-color: var(--plmun-dark-green);
        }

        .btn-print:hover {
            background-color: var(--plmun-light-green);
            border-color: var(--plmun-light-green);
        }

        .modal-dialog {
            margin-top: 10vh;
        }

        .modal-content {
            max-height: 90vh;
            overflow-y: auto;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-4">
                <div class="student-id-card" id="toPrint">
                    <div class="id-header">
                        <img class="school-logo" src="assets/img/PLMUNLOGO.png">
                        <div class="school-name">Pamantasan ng Lungsod ng Muntinlupa</div>
                        <div style="font-size: 14px;">OFFICIAL STUDENT ID</div>
                    </div>

                    <div class="student-photo">
                        <?php if (!empty($person['photo']) && file_exists('uploads/' . $person['photo'])): ?>
                            <img src="uploads/<?php echo htmlspecialchars($person['photo']) ?>" alt="Student Photo">
                        <?php else: ?>
                            <i class="fas fa-user-graduate default-icon"></i>
                        <?php endif; ?>
                    </div>


                    <div class="student-name"><?php echo htmlspecialchars($person['name']) ?></div>
                    <div class="student-id">ID: <?php echo htmlspecialchars($person['student_id']) ?></div>

                    <div class="student-details">
                        <div class="detail-line"><span class="label">College:</span>
                            <?php echo htmlspecialchars($person['college']) ?></div>
                        <div class="detail-line"><span class="label">Course:</span>
                            <?php echo htmlspecialchars($person['course']) ?></div>
                        <div class="detail-line"><span class="label">Year:</span>
                            <?php echo htmlspecialchars($person['year_level']) ?></div>
                        <div class="detail-line"><span class="label">Standing:</span>
                            <?php echo htmlspecialchars($person['standing']) ?></div>
                        <div class="detail-line"><span class="label">Address:</span>
                            <?php echo htmlspecialchars($person['caddress']) ?></div>
                    </div>

                    <div class="id-qr-container">
                        <div id="qrCodeContainer"></div>
                        <div style="font-size: 15px; margin-top: 15px; color: #006400;">
                            <i class="fas fa-qrcode"></i> SCAN TO VERIFY
                        </div>
                    </div>

                    <div class="id-footer">
                        <div>Valid until: <?php echo date('M d, Y', strtotime('+1 year')) ?></div>
                        <div style="margin-top: 5px;">© <?php echo date('Y') ?> PLMun. All rights reserved.</div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="access-history card shadow">
                    <div class="card-header bg-dark text-white">
                        <h5 class="mb-0"><i class="fas fa-history"></i> Campus Access History</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table history-table">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th>Date & Time</th>
                                        <th>Building</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    $tracks = $conn->query(
                                        "SELECT t.*, e.address, e.name as ename 
                                            FROM person_tracks t 
                                            INNER JOIN establishments e ON e.id = t.establishment_id 
                                            WHERE t.person_id = '$id' 
                                            ORDER BY t.date_created DESC"
                                    );
                                    while ($row = $tracks->fetch_assoc()):
                                        ?>
                                        <tr>
                                            <td class="text-center"><?php echo $i++ ?></td>
                                            <td><?php echo date("M d, Y h:i A", strtotime($row['date_created'])) ?></td>
                                            <td><?php echo ucwords($row['ename']) ?></td>
                                            <td><?php echo $row['address'] ?></td>

                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-footer display mt-4">
        <div class="row justify-content-center">
            <div class="col-auto">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">
                    <i class="fas fa-times"></i> Close
                </button>
                <form action="index.php?page=print_persons" method="POST" style="display: inline;">
                    <input type="hidden" name="ids[]" value="<?php echo $id ?>">
                    <button type="submit" class="btn btn-success ml-2">
                        <i class="fas fa-id-card"></i> Print Full ID
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/qr-code-styling@1.5.0/lib/qr-code-styling.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcode-generator@1.4.4/qrcode.min.js"></script>

    <script>
        // Generate QR code with fallback
        function generateQRCode() {
            const qrData =

                "<?php echo $person['student_id'] ?>"
                ;

            try {
                const qrCode = new QRCodeStyling({
                    width: 150,
                    height: 150,
                    data: qrData,
                    image: "",
                    dotsOptions: {
                        color: "#000000",
                        type: "square"
                    },
                    backgroundOptions: {
                        color: "#ffffff"
                    },
                    qrOptions: {
                        errorCorrectionLevel: "L"
                    }
                });
                document.getElementById('qrCodeContainer').innerHTML = '';
                qrCode.append(document.getElementById('qrCodeContainer'));
            } catch (e) {
                console.log("Using fallback QR generator");
                const qr = qrcode(0, 'L');
                qr.addData(qrData);
                qr.make();
                document.getElementById('qrCodeContainer').innerHTML = qr.createImgTag(4, 0);
            }

            return qrData;
        }

        $(document).ready(function () {
            generateQRCode();
        });

        window.addEventListener('load', function () {
            generateQRCode();
        });
    </script>
</body>

</html>