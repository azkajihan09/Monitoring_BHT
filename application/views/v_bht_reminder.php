<!-- 
    PEMBELAJARAN VIEW CODEIGNITER:
    
    View ini adalah file yang berisi HTML + PHP untuk menampilkan data
    Data dikirim dari Controller melalui variabel $data
    View ini akan di-load oleh template system (header, sidebar, footer)
    
    STRUKTUR VIEW INI:
    1. CSS khusus untuk styling
    2. Content Section dengan berbagai widget
    3. JavaScript untuk interaktivitas dan charts
-->

<!-- Custom CSS untuk halaman ini -->
<style>
    /* Styling untuk priority badges */
    .priority-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        font-weight: bold;
    }

    .priority-urgent {
        background-color: #dc3545;
        color: white;
    }

    .priority-warning {
        background-color: #ffc107;
        color: #212529;
    }

    .priority-terlambat {
        background-color: #6c757d;
        color: white;
    }

    .priority-normal {
        background-color: #28a745;
        color: white;
    }

    /* Styling untuk statistik cards */
    .stats-card {
        border-left: 4px solid #007bff;
        transition: transform 0.2s;
    }

    .stats-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .stats-number {
        font-size: 2rem;
        font-weight: bold;
        color: #495057;
    }

    /* Styling untuk charts container */
    .chart-container {
        position: relative;
        height: 300px;
        margin: 10px 0;
    }

    /* Responsive table */
    .table-responsive {
        border-radius: 0.5rem;
        overflow: hidden;
    }

    /* Loading overlay */
    .loading-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }
</style>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">
                    <i class="fas fa-bell text-warning"></i>
                    Dashboard BHT - Pengingat & Statistik
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
                    <li class="breadcrumb-item active">BHT Reminder</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">

        <!-- Error Message (jika ada) -->
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <i class="fas fa-exclamation-triangle"></i> <?= $error_message ?>
            </div>
        <?php endif; ?>

        <!-- BAGIAN 1: STATISTIK CARDS -->
        <div class="row">
            <!-- Total Perkara Putus -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info stats-card">
                    <div class="inner">
                        <h3 class="stats-number"><?= isset($monthly_report->total_perkara_putus) ? $monthly_report->total_perkara_putus : 0 ?></h3>
                        <p>Total Perkara Putus<br><small><?= isset($current_month_name) ? $current_month_name : 'Bulan Ini' ?> <?= isset($current_year) ? $current_year : date('Y') ?></small></p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-gavel"></i>
                    </div>
                </div>
            </div>

            <!-- BHT Selesai -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-success stats-card">
                    <div class="inner">
                        <h3 class="stats-number"><?= isset($monthly_report->bht_selesai) ? $monthly_report->bht_selesai : 0 ?></h3>
                        <p>BHT Selesai<br><small>
                                <?php if (isset($monthly_report->persentase_tepat_waktu)): ?>
                                    <?= $monthly_report->persentase_tepat_waktu ?>% Tepat Waktu
                                <?php else: ?>
                                    Perhitungan...
                                <?php endif; ?>
                            </small></p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>

            <!-- BHT Belum -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning stats-card">
                    <div class="inner">
                        <h3 class="stats-number"><?= isset($monthly_report->bht_belum) ? $monthly_report->bht_belum : 0 ?></h3>
                        <p>BHT Belum Dibuat<br><small>Perlu Perhatian</small></p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>

            <!-- Pengingat Aktif -->
            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger stats-card">
                    <div class="inner">
                        <h3 class="stats-number"><?= isset($reminder_count) ? $reminder_count : 0 ?></h3>
                        <p>Pengingat Aktif<br><small>Memerlukan Tindakan</small></p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-bell"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- BAGIAN 2: TABEL PENGINGAT OTOMATIS -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-bell text-warning"></i>
                            Pengingat Otomatis Batas Waktu BHT
                        </h3>
                        <div class="card-tools">
                            <!-- Filter Controls -->
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="filterReminders('ALL')">Semua</button>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="filterReminders('URGENT')">Urgent</button>
                                <button type="button" class="btn btn-sm btn-outline-warning" onclick="filterReminders('WARNING')">Warning</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="filterReminders('TERLAMBAT')">Terlambat</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="remindersTable">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Nomor Perkara</th>
                                        <th>Jenis Perkara</th>
                                        <th>Hakim</th>
                                        <th>Tanggal Putusan</th>
                                        <th>Batas BHT</th>
                                        <th>Sisa Hari</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="remindersTableBody">
                                    <?php if (isset($reminders) && !empty($reminders)): ?>
                                        <?php foreach ($reminders as $index => $reminder): ?>
                                            <tr data-priority="<?= $reminder->status_prioritas ?>">
                                                <td><?= $index + 1 ?></td>
                                                <td>
                                                    <strong><?= htmlspecialchars($reminder->nomor_perkara) ?></strong>
                                                </td>
                                                <td><?= htmlspecialchars($reminder->jenis_perkara_nama) ?></td>
                                                <td><?= htmlspecialchars($reminder->hakim ?: 'Tidak Diketahui') ?></td>
                                                <td>
                                                    <?= date('d/m/Y', strtotime($reminder->tanggal_putusan)) ?>
                                                </td>
                                                <td>
                                                    <strong><?= date('d/m/Y', strtotime($reminder->batas_bht)) ?></strong>
                                                </td>
                                                <td>
                                                    <span class="badge badge-<?= $reminder->sisa_hari < 0 ? 'danger' : ($reminder->sisa_hari <= 3 ? 'warning' : 'success') ?>">
                                                        <?= $reminder->sisa_hari ?> hari
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="priority-badge priority-<?= strtolower($reminder->status_prioritas) ?>">
                                                        <?= $reminder->status_prioritas ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm">
                                                        <button class="btn btn-info btn-sm" onclick="viewDetail(<?= $reminder->perkara_id ?>)" title="Lihat Detail">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                        <button class="btn btn-success btn-sm" onclick="markHandled(<?= $reminder->perkara_id ?>)" title="Tandai Selesai">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="9" class="text-center text-muted">
                                                <i class="fas fa-info-circle"></i> Tidak ada pengingat untuk ditampilkan
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- BAGIAN 3: CHARTS DAN GRAFIK -->
        <div class="row">
            <!-- Line Chart: Trend Bulanan -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-chart-line"></i>
                            Trend BHT Bulanan <?= isset($current_year) ? $current_year : date('Y') ?>
                        </h3>
                        <div class="card-tools">
                            <select id="chartYearSelector" class="form-control form-control-sm" style="width: 100px;" onchange="updateChartYear()">
                                <?php for ($year = date('Y'); $year >= 2020; $year--): ?>
                                    <option value="<?= $year ?>" <?= ($year == (isset($current_year) ? $current_year : date('Y'))) ? 'selected' : '' ?>><?= $year ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="monthlyTrendChart"></canvas>
                            <div id="chartLoading" class="loading-overlay" style="display: none;">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pie Chart: Distribusi Jenis Perkara -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-chart-pie"></i>
                            Distribusi Jenis Perkara
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="chart-container" style="height: 250px;">
                            <canvas id="caseDistributionChart"></canvas>
                        </div>
                        <!-- Legend -->
                        <div id="pieChartLegend" class="mt-3">
                            <!-- Legend akan diisi oleh JavaScript -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- BAGIAN 4: DETAIL STATISTIK -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-chart-bar"></i>
                            Performa BHT <?= isset($current_month_name) ? $current_month_name : 'Bulan Ini' ?>
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6 text-center">
                                <div class="description-block border-right">
                                    <span class="description-percentage text-success">
                                        <i class="fas fa-caret-up"></i> <?= isset($monthly_report->persentase_tepat_waktu) ? $monthly_report->persentase_tepat_waktu : 0 ?>%
                                    </span>
                                    <h5 class="description-header"><?= isset($monthly_report->bht_tepat_waktu) ? $monthly_report->bht_tepat_waktu : 0 ?></h5>
                                    <span class="description-text">BHT TEPAT WAKTU</span>
                                </div>
                            </div>
                            <div class="col-6 text-center">
                                <div class="description-block">
                                    <span class="description-percentage text-warning">
                                        <i class="fas fa-clock"></i> <?= isset($monthly_report->rata_rata_hari) ? $monthly_report->rata_rata_hari : 0 ?> hari
                                    </span>
                                    <h5 class="description-header"><?= isset($monthly_report->bht_terlambat) ? $monthly_report->bht_terlambat : 0 ?></h5>
                                    <span class="description-text">BHT TERLAMBAT</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-download"></i>
                            Export Laporan
                        </h3>
                    </div>
                    <div class="card-body">
                        <p>Download laporan BHT dalam format yang Anda inginkan:</p>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-success" onclick="exportReport('excel')">
                                <i class="fas fa-file-excel"></i> Excel
                            </button>
                            <button type="button" class="btn btn-danger" onclick="exportReport('pdf')">
                                <i class="fas fa-file-pdf"></i> PDF
                            </button>
                        </div>
                        <br><br>
                        <small class="text-muted">
                            <i class="fas fa-info-circle"></i>
                            Export akan menyertakan data pengingat dan statistik bulan ini.
                        </small>
                    </div>
                </div>
            </div>
        </div>

    </div><!-- /.container-fluid -->
</section><!-- /.content -->

<!-- JAVASCRIPT UNTUK INTERAKTIVITAS -->
<script>
    // ========== GLOBAL VARIABLES ==========
    let monthlyTrendChart = null;
    let caseDistributionChart = null;

    // Data dari PHP (dikonversi ke JavaScript)
    const chartData = <?= isset($chart_data) ? json_encode($chart_data) : '{}' ?>;
    const caseDistribution = <?= isset($case_distribution) ? json_encode($case_distribution) : '[]' ?>;

    // ========== DOCUMENT READY ==========
    $(document).ready(function() {
        console.log('Dashboard BHT Reminder loaded');
        console.log('Chart Data:', chartData);
        console.log('Case Distribution:', caseDistribution);

        // Initialize DataTables untuk tabel pengingat
        $('#remindersTable').DataTable({
            "responsive": true,
            "pageLength": 10,
            "order": [
                [6, "asc"]
            ], // Sort by Sisa Hari ascending
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
            }
        });

        // Initialize Charts
        initializeCharts();

        // Set auto-refresh untuk pengingat setiap 5 menit
        setInterval(refreshReminders, 5 * 60 * 1000);
    });

    // ========== CHART FUNCTIONS ==========
    function initializeCharts() {
        try {
            // Pastikan Chart.js sudah loaded
            if (typeof Chart === 'undefined') {
                console.error('Chart.js tidak ditemukan!');
                return;
            }

            // Initialize Monthly Trend Chart
            const monthlyCtx = document.getElementById('monthlyTrendChart');
            if (monthlyCtx && chartData.labels) {
                monthlyTrendChart = new Chart(monthlyCtx, {
                    type: 'line',
                    data: {
                        labels: chartData.labels,
                        datasets: [{
                                label: 'Total Perkara Putus',
                                data: chartData.total_putus || [],
                                borderColor: 'rgb(54, 162, 235)',
                                backgroundColor: 'rgba(54, 162, 235, 0.1)',
                                tension: 0.1
                            },
                            {
                                label: 'BHT Selesai',
                                data: chartData.bht_selesai || [],
                                borderColor: 'rgb(75, 192, 192)',
                                backgroundColor: 'rgba(75, 192, 192, 0.1)',
                                tension: 0.1
                            },
                            {
                                label: 'BHT Tepat Waktu',
                                data: chartData.bht_tepat_waktu || [],
                                borderColor: 'rgb(34, 197, 94)',
                                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                                tension: 0.1
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            title: {
                                display: true,
                                text: 'Trend BHT Bulanan'
                            }
                        }
                    }
                });
            }

            // Initialize Case Distribution Pie Chart
            const pieCtx = document.getElementById('caseDistributionChart');
            if (pieCtx && Array.isArray(caseDistribution) && caseDistribution.length > 0) {
                const pieLabels = caseDistribution.map(item => item.jenis_perkara_nama);
                const pieData = caseDistribution.map(item => item.jumlah);
                const pieColors = generateColors(pieData.length);

                caseDistributionChart = new Chart(pieCtx, {
                    type: 'doughnut',
                    data: {
                        labels: pieLabels,
                        datasets: [{
                            data: pieData,
                            backgroundColor: pieColors,
                            borderWidth: 2,
                            borderColor: '#fff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false // Kita buat legend custom
                            }
                        }
                    }
                });

                // Generate custom legend
                generatePieLegend(pieLabels, pieColors, caseDistribution);
            }

            console.log('Charts initialized successfully');

        } catch (error) {
            console.error('Error initializing charts:', error);
        }
    }

    function generateColors(count) {
        const colors = [
            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
            '#9966FF', '#FF9F40', '#FF6384', '#C9CBCF'
        ];
        return colors.slice(0, count);
    }

    function generatePieLegend(labels, colors, data) {
        const legendContainer = document.getElementById('pieChartLegend');
        if (!legendContainer) return;

        let legendHtml = '<div class="row">';

        labels.forEach((label, index) => {
            const item = data[index];
            legendHtml += `
            <div class="col-12 mb-2">
                <div class="d-flex align-items-center">
                    <div style="width: 20px; height: 20px; background-color: ${colors[index]}; margin-right: 8px; border-radius: 3px;"></div>
                    <div class="flex-grow-1">
                        <small class="text-muted">${label}</small><br>
                        <strong>${item.jumlah} perkara</strong>
                        <span class="badge badge-info ml-2">${item.persentase_selesai}% selesai</span>
                    </div>
                </div>
            </div>
        `;
        });

        legendHtml += '</div>';
        legendContainer.innerHTML = legendHtml;
    }

    // ========== REMINDER FUNCTIONS ==========
    function filterReminders(status) {
        const table = $('#remindersTable').DataTable();

        if (status === 'ALL') {
            table.search('').draw();
        } else {
            table.search(status).draw();
        }

        // Update button styles
        $('.btn-group .btn').removeClass('btn-primary').addClass('btn-outline-primary');
        event.target.classList.remove('btn-outline-primary');
        event.target.classList.add('btn-primary');
    }

    function refreshReminders() {
        console.log('Refreshing reminders...');

        $.ajax({
            url: '<?= base_url("bht_reminder/get_filtered_reminders") ?>',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    updateRemindersTable(response.data);
                    console.log('Reminders refreshed successfully');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error refreshing reminders:', error);
            }
        });
    }

    function updateRemindersTable(reminders) {
        const tbody = document.getElementById('remindersTableBody');
        if (!tbody) return;

        let html = '';

        if (reminders && reminders.length > 0) {
            reminders.forEach((reminder, index) => {
                const priorityClass = `priority-${reminder.status_prioritas.toLowerCase()}`;
                const badgeClass = reminder.sisa_hari < 0 ? 'danger' : (reminder.sisa_hari <= 3 ? 'warning' : 'success');

                html += `
                <tr data-priority="${reminder.status_prioritas}">
                    <td>${index + 1}</td>
                    <td><strong>${reminder.nomor_perkara}</strong></td>
                    <td>${reminder.jenis_perkara_nama}</td>
                    <td>${reminder.hakim || 'Tidak Diketahui'}</td>
                    <td>${formatDate(reminder.tanggal_putusan)}</td>
                    <td><strong>${formatDate(reminder.batas_bht)}</strong></td>
                    <td><span class="badge badge-${badgeClass}">${reminder.sisa_hari} hari</span></td>
                    <td><span class="priority-badge ${priorityClass}">${reminder.status_prioritas}</span></td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-info btn-sm" onclick="viewDetail(${reminder.perkara_id})" title="Lihat Detail">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-success btn-sm" onclick="markHandled(${reminder.perkara_id})" title="Tandai Selesai">
                                <i class="fas fa-check"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
            });
        } else {
            html = `
            <tr>
                <td colspan="9" class="text-center text-muted">
                    <i class="fas fa-info-circle"></i> Tidak ada pengingat untuk ditampilkan
                </td>
            </tr>
        `;
        }

        tbody.innerHTML = html;

        // Reinitialize DataTable
        $('#remindersTable').DataTable().destroy();
        $('#remindersTable').DataTable({
            "responsive": true,
            "pageLength": 10,
            "order": [
                [6, "asc"]
            ],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
            }
        });
    }

    // ========== ACTION FUNCTIONS ==========
    function viewDetail(perkaraId) {
        // Redirect ke halaman detail perkara
        window.open(`<?= base_url('perkara/detail/') ?>${perkaraId}`, '_blank');
    }

    function markHandled(perkaraId) {
        const note = prompt('Tambahkan catatan (opsional):');

        if (note !== null) { // User tidak cancel
            $.ajax({
                url: '<?= base_url("bht_reminder/mark_handled") ?>',
                method: 'POST',
                data: {
                    perkara_id: perkaraId,
                    note: note
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert('Pengingat berhasil ditandai sebagai sudah ditangani');
                        refreshReminders();
                    } else {
                        alert('Gagal menandai pengingat: ' + response.message);
                    }
                },
                error: function(xhr, status, error) {
                    alert('Terjadi kesalahan: ' + error);
                }
            });
        }
    }

    function updateChartYear() {
        const selectedYear = document.getElementById('chartYearSelector').value;
        const loading = document.getElementById('chartLoading');

        loading.style.display = 'flex';

        $.ajax({
            url: '<?= base_url("bht_reminder/get_chart_data") ?>',
            method: 'GET',
            data: {
                year: selectedYear
            },
            dataType: 'json',
            success: function(response) {
                if (response.success && monthlyTrendChart) {
                    // Update chart data
                    monthlyTrendChart.data.datasets[0].data = response.monthly_data.total_putus;
                    monthlyTrendChart.data.datasets[1].data = response.monthly_data.bht_selesai;
                    monthlyTrendChart.data.datasets[2].data = response.monthly_data.bht_tepat_waktu;
                    monthlyTrendChart.update();

                    console.log(`Chart updated for year ${selectedYear}`);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error updating chart:', error);
                alert('Gagal memuat data grafik');
            },
            complete: function() {
                loading.style.display = 'none';
            }
        });
    }

    function exportReport(format) {
        const year = document.getElementById('chartYearSelector').value;
        const month = new Date().getMonth() + 1;

        const url = `<?= base_url("bht_reminder/export_report") ?>?format=${format}&year=${year}&month=${month}`;
        window.open(url, '_blank');
    }

    // ========== UTILITY FUNCTIONS ==========
    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('id-ID');
    }

    // ========== AUTO-REFRESH NOTIFICATIONS ==========
    function checkForNewReminders() {
        // Implementasi untuk notifikasi real-time bisa ditambahkan kemudian
        // Misalnya menggunakan WebSocket atau polling
    }

    // Console log untuk debugging
    console.log('BHT Reminder Dashboard JavaScript loaded successfully');
</script>
