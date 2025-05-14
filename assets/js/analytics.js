document.addEventListener('DOMContentLoaded', function () {
    fetch('data/analytics.php')
        .then(res => res.json())
        .then(data => {


    // Card values
    document.getElementById('totalStudents').textContent = data.totalStudents;
    document.getElementById('totalEstablishments').textContent = data.totalEstablishments;
    document.getElementById('totalStaff').textContent = data.totalStaff;

    // Monthly College Trend Line
    const collegeLabels = Array.from({ length: 12 }, (_, i) => new Date(0, i).toLocaleString('en', { month: 'short' }));
    const collegeDatasets = Object.entries(data.collegeTrend).map(([college, values]) => ({
        label: college,
        data: collegeLabels.map((_, i) => values[i + 1] || 0),
        borderWidth: 2,
        fill: false
    }));

    new Chart(document.getElementById('collegeTrendLine'), {
        type: 'line',
        data: { labels: collegeLabels, datasets: collegeDatasets },
        options: { responsive: true }
    });

    // Monthly Course Trend Line
    const courseDatasets = Object.entries(data.courseTrend).map(([course, values]) => ({
        label: course,
        data: collegeLabels.map((_, i) => values[i + 1] || 0),
        borderWidth: 2,
        fill: false
    }));

    new Chart(document.getElementById('courseTrendLine'), {
        type: 'line',
        data: { labels: collegeLabels, datasets: courseDatasets },
        options: { responsive: true }
    });

    // Hourly Entries Chart
    new Chart(document.getElementById('hourlyEntriesChart'), {
        type: 'bar',
        data: {
            labels: Array.from({ length: 24 }, (_, i) => `${i}:00`),
            datasets: [{
                label: 'Entries',
                data: data.hourlyEntries,
                backgroundColor: 'rgba(255, 99, 132, 0.6)'
            }]
        },
        options: { responsive: true }
    });

    // Weekly Entries Chart
    const weekLabels = Object.keys(data.weeklyEntries);
    const weekData = Object.values(data.weeklyEntries);

    new Chart(document.getElementById('weeklyEntriesChart'), {
        type: 'line',
        data: {
            labels: weekLabels,
            datasets: [{
                label: 'Weekly Entries',
                data: weekData,
                borderColor: '#17a2b8',
                borderWidth: 2,
                fill: false
            }]
        },
        options: { responsive: true }
    });

            // Set Visit Count
            document.getElementById('todaysVisitCount').textContent = data.todaysVisit;

            // Bar Chart: Establishment Volume
            new Chart(document.getElementById('highestEstablishmentToday'), {
                type: 'bar',
                data: {
                    labels: data.establishments.map(e => e.name),
                    datasets: [{
                        label: 'Number of Visitors',
                        data: data.establishments.map(e => e.count),
                        backgroundColor: 'rgba(54, 162, 235, 0.6)'
                    }]
                },
                options: { responsive: true }
            });

            // Pie Chart: Colleges
            new Chart(document.getElementById('highestCollegeToday'), {
                type: 'pie',
                data: {
                    labels: data.colleges.map(c => c.college),
                    datasets: [{
                        data: data.colleges.map(c => c.count),
                        backgroundColor: ['#007bff', '#ffc107', '#28a745', '#17a2b8', '#dc3545']
                    }]
                },
                options: { responsive: true }
            });

            // Pie Chart: Courses
            new Chart(document.getElementById('highestCourseToday'), {
                type: 'pie',
                data: {
                    labels: data.courses.map(c => c.course),
                    datasets: [{
                        data: data.courses.map(c => c.count),
                        backgroundColor: ['#6f42c1', '#e83e8c', '#20c997', '#fd7e14', '#343a40']
                    }]
                },
                options: { responsive: true }
            });
        });
});
