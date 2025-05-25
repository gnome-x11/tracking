<?php
include "db_connect.php";

if (!isset($_SESSION['login_type']) || $_SESSION['login_type'] != 1) {
    header('Location: login.php');
    exit();
}

$total_visitors_today = $conn->query("SELECT COUNT(*) as total FROM person_tracks WHERE DATE(date_created) = CURDATE()")->fetch_assoc()["total"];
$total_establishments = $conn->query("SELECT COUNT(*) as total FROM establishments")->fetch_assoc()["total"];
$total_visitors = $conn->query("SELECT COUNT(*) as total FROM visitors WHERE DATE(created_at) = CURDATE()")->fetch_assoc()["total"];
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
    <link rel="stylesheet" href="page-css/home.css?v=<?php echo time(); ?>">
</head>

<body>
    <!-- Main Dashboard Content -->
    <main class="dashboard-container">
        <?php if ($_SESSION["login_type"] == 1): ?>
            <!-- Statistics Cards -->
            <div class="card-header dashboard-card-header">
                <b style="color: white; font-size: 30px;">Dashboard and Analytics</b>
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
                    <a href="index.php?page=establishment" style="text-decoration: none; color: inherit;">
                        <div class="stat-card establishments">
                            <span class="float-right summary_icon"><i class="fa fa-building"></i></span>
                            <div class="stat-value"><?php echo $total_establishments; ?></div>
                            <div class="stat-label">Total Establishments</div>
                        </div>
                    </a>
                </div>

                <div class="col-md-3">
                    <a href="index.php?page=visitor_list" style="text-decoration: none; color: inherit;">
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
            <div class="row mt-6">
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-header bg-info text-white">Course Distribution</div>
                        <div class="card-body">
                            <canvas id="courseChart" width="200" height="340"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-6">
                    <div class="card">
                        <div class="card-header bg-success text-white">Establishments frequency</div>
                        <div class="card-body">
                            <canvas id="estabChart" width="400" height="315"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-6">
                    <div class="card">
                        <div class="card-header bg-success text-white">Daily Entries</div>
                        <div class="card-body">
                            <canvas id="dailyChart" height="240"></canvas>
                        </div>
                    </div>
                </div>
            </div>

             <div class="row mt-6">
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
                            <canvas id="timePeriodChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
                                        <b>Failed Attempts Logs</b>
                                        <span class="d-flex flex-wrap gap-2 mt-2 mt-md-0">
                                            <button class="btn btn-danger btn-sm mr-2" type="button" id="delete_records">
                                                <i class="fa fa-trash"></i> Delete All Logs
                                            </button>

                                        </span>
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

                                                    if (count($failed_logins) > 0):
                                                        foreach ($failed_logins as $login): ?>
                                                            <tr>
                                                                <td><?php echo $login['id']; ?></td>
                                                                <td><?php echo htmlspecialchars($login['establishment_name'] ?? 'Unknown'); ?>
                                                                </td>
                                                                <td><?php echo htmlspecialchars($login['error_message']); ?></td>
                                                                <td><?php echo date("M d, Y h:i A", strtotime($login['date_created'])); ?></td>
                                                            </tr>

                                                        <?php endforeach;
                                                    else:
                                                         ?>
                                                        <tr>
                                                            <td colspan="4" class="text-center">No failed login attempts found</td>
                                                        </tr>
                                                    <?php
                                                    endif;
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
            <!-- Add this code in the admin section where you want the table to appear -->

        <?php endif; ?>
    </main>

    <!-- //ito naman yung mga graph ng charts adjust mo nalang kung gusto mo sa chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        $(document).ready(function () {
            $('#failedLoginsTable').DataTable({
                "pageLength": 5,
                "lengthChange": false, // hide the 'show x entries' dropdown
            });
        });

        function _conf(msg, func, params = []) {
            $('#confirm_modal .modal-body').html(msg);
            $('#confirm_modal').modal('show');
            $('#confirm_modal #confirm').attr('onclick', func + "(" + params.map(JSON.stringify).join(',') + ")");
        }
        $('#delete_records').click(function () {
            _conf("Are you sure you want to delete all failed attempts logs?", "confirm_delete_records");
        });

        function confirm_delete_records() {
            delete_records();
        }

        function delete_records() {
            start_load()
            $.ajax({
                url: 'ajax.php?action=delete_records',
                method: "POST",
                success: function (resp) {
                    if (resp == 1) {
                        alert_toast("Failed Attempts logs cleared succesffuly", "success")
                        setTimeout(function () {
                            location.reload()
                        }, 1500)
                    }
                }
            })
        }



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
                        labels: <?= json_encode(array_column($estabs, "est_name")) ?>,
                        datasets: [{
                            label: 'Entries',
                            data: <?= json_encode(array_column($estabs, "total")) ?>,
                            backgroundColor: '#28a745'
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            title: {
                                display: true,
                                text: 'Entrance '
                            }
                        },
                        scales: {
                            y: {
                                // the data minimum used for determining the ticks is Math.min(dataMin, suggestedMin)
                                suggestedMin: 30,

                                // the data maximum used for determining the ticks is Math.max(dataMax, suggestedMax)
                                suggestedMax: 50,
                            }
                        }
                    },
                });
            }

            // Daily Chart
            if (document.getElementById('dailyChart')) {
                const dailyChart = new Chart(document.getElementById('dailyChart'), {
                    type: 'line',
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
                    type: 'bar',
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
                document.getElementById('timePeriodSelect').addEventListener('change', function () {
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

    </script>
</body>
</html>
