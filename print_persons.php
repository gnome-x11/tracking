<?php
include 'db_connect.php';

$ids = $_POST['ids'] ?? [];
$id_list = implode(",", array_map('intval', $ids));
$persons = $conn->query("SELECT *, concat(lastname,', ',firstname,' ',middlename) as name, 
        concat(address,', ',street,', ',baranggay,', ',city,', ',state,', ',zip_code) as caddress 
        FROM persons WHERE id IN ($id_list)");
?>
<!DOCTYPE html>
<html>

<head>
    <title>PLMUN_STUDENT_ID</title>
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
            padding: 20px;
        }

        .id-card {
            top: 100px;

            align-items: center;
            width: 100%;
            max-width: 380px;
            border: 4px solid var(--plmun-dark-green);
            border-radius: 12px;
            padding: 25px;
            background: white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            margin: 20px auto;
            page-break-after: always;
            position: relative;
            bottom: 100px;
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
            /* Fixed height to prevent overlap */
        }

        .qr-code {
            width: 150px;
            height: 150px;
            margin: 0 auto;
            border: 0 none;
            padding: 5px;
            background: white;
            display: block;
            /* Ensures proper rendering */
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

        @media print {
            body {
                background: white !important;
                padding: 0 !important;
            }

            .id-card {
                box-shadow: none;
                margin: 0 auto;
                border-width: 3px;
            }

            .id-qr-container {
                page-break-inside: avoid;
            }
        }
    </style>
</head>

<body>
    <?php while ($person = $persons->fetch_assoc()): ?>
        <div class="id-card" id="card-<?php echo $person['id'] ?>">
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
                <div id="qr-<?php echo $person['id'] ?>" class="qr-code"></div>
                <div style="font-size: 15px; margin-top: 15px; color: #006400;">
                    <i class="fas fa-qrcode"></i> SCAN TO VERIFY
                </div>
            </div>

            <div class="id-footer">
                <div>Valid until: <?php echo date('M d, Y', strtotime('+1 year')) ?></div>
                <div style="margin-top: 5px;">© <?php echo date('Y') ?> PLMun. All rights reserved.</div>
            </div>
        </div>
    <?php endwhile; ?>

  
    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/qr-code-styling@1.5.0/lib/qr-code-styling.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcode-generator@1.4.4/qrcode.min.js"></script>


    <script>
    // Helper: Wait for all images to load
    function waitForImagesToLoad(callback) {
        const images = document.querySelectorAll('img');
        let loaded = 0;
        if (images.length === 0) return callback();

        images.forEach(img => {
            if (img.complete) {
                loaded++;
                if (loaded === images.length) callback();
            } else {
                img.onload = img.onerror = () => {
                    loaded++;
                    if (loaded === images.length) callback();
                };
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        // Generate QR codes for each student
        <?php
        $persons->data_seek(0);
        while ($person = $persons->fetch_assoc()):
        ?>
        try {
            const qrCode_<?php echo $person['id'] ?> = new QRCodeStyling({
                width: 150,
                height: 150,
                data: "<?php echo $person['student_id'] ?>",
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
            qrCode_<?php echo $person['id'] ?>.append(document.getElementById('qr-<?php echo $person['id'] ?>'));
        } catch (e) {
            const qr = qrcode(0, 'L');
            qr.addData("<?php echo $person['student_id'] ?>");
            qr.make();
            document.getElementById('qr-<?php echo $person['id'] ?>').innerHTML = qr.createImgTag(4, 0);
        }
        <?php endwhile; ?>

        // Wait for all images + QR to load before printing
        waitForImagesToLoad(() => {
            setTimeout(() => {
                window.print();
                setTimeout(() => window.close(), 500);
            }, 500); // QR needs time to render
        });
    });
</script>


</body>

</html>