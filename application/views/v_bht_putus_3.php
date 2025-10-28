<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BHT Perkara Putus 3 | SIPP</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/fontawesome-free/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
    <!-- DateRangePicker -->
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/daterangepicker/daterangepicker.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url() ?>assets/dist/css/adminlte.min.css">

    <style>
        .table th {
            background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
            color: white;
            border: none;
            font-weight: 600;
            font-size: 12px;
        }

        .table td {
            font-size: 11px;
            vertical-align: middle;
        }

        .badge-status {
            font-size: 10px;
            padding: 4px 8px;
        }

        .stat-card {
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .multiple-dates {
            line-height: 1.4;
            font-size: 11px;
        }

        .multiple-dates .date-item {
            display: block;
            margin-bottom: 2px;
            padding: 1px 4px;
            background-color: rgba(40, 167, 69, 0.1);
            border-radius: 3px;
            border-left: 3px solid #28a745;
        }

        .search-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark"><i class="fas fa-file-invoice mr-2"></i> BHT Perkara Putus 3</h1>
                            <small class="text-muted">Pencarian Berdasarkan Tanggal & Nomor Perkara</small>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="<?= site_url('home') ?>">Home</a></li>
                                <li class="breadcrumb-item active">BHT Perkara Putus 3</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <!-- Filter Card -->
                    <div class="card card-danger card-outline">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-search mr-1"></i> Pencarian & Filter Data</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body search-section">
                            <form action="<?php echo base_url() ?>index.php/Bht_putus_3" method="POST" class="form-horizontal">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><i class="fas fa-balance-scale mr-1"></i> Jenis Perkara:</label>
                                            <select name="jenis_perkara" class="form-control select2" required>
                                                <option value="Pdt.G" <?php echo ($jenis_perkara === 'Pdt.G') ? 'selected' : ''; ?>>Perkara Gugatan (Pdt.G)</option>
                                                <option value="Pdt.P" <?php echo ($jenis_perkara === 'Pdt.P') ? 'selected' : ''; ?>>Perkara Permohonan (Pdt.P)</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><i class="fas fa-search mr-1"></i> Nomor Perkara:</label>
                                            <input type="text" name="nomor_perkara" class="form-control"
                                                placeholder="Cari berdasarkan nomor perkara..."
                                                value="<?= htmlspecialchars($nomor_perkara) ?>">
                                            <small class="text-muted">Kosongkan jika ingin menampilkan semua perkara</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tab untuk memilih jenis pencarian -->
                                <div class="row">
                                    <div class="col-12">
                                        <ul class="nav nav-tabs" id="searchTabs" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link <?= empty($tanggal_awal) && empty($tanggal_akhir) ? 'active' : '' ?>"
                                                    id="monthly-tab" data-toggle="tab" href="#monthly" role="tab">
                                                    <i class="fas fa-calendar-alt mr-1"></i> Berdasarkan Bulan
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link <?= !empty($tanggal_awal) && !empty($tanggal_akhir) ? 'active' : '' ?>"
                                                    id="daterange-tab" data-toggle="tab" href="#daterange" role="tab">
                                                    <i class="fas fa-calendar-week mr-1"></i> Berdasarkan Range Tanggal
                                                </a>
                                            </li>
                                        </ul>
                                        <div class="tab-content mt-3" id="searchTabsContent">
                                            <!-- Tab Pencarian Bulanan -->
                                            <div class="tab-pane fade <?= empty($tanggal_awal) && empty($tanggal_akhir) ? 'show active' : '' ?>"
                                                id="monthly" role="tabpanel">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label><i class="far fa-calendar-alt mr-1"></i> Bulan:</label>
                                                            <select name="lap_bulan" class="form-control select2" required>
                                                                <option value="">-- Pilih Bulan --</option>
                                                                <?php
                                                                foreach ($months as $value => $label) {
                                                                    $selected = ($lap_bulan === $value) ? 'selected' : '';
                                                                    echo "<option value=\"$value\" $selected>$label</option>";
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label><i class="far fa-calendar-check mr-1"></i> Tahun:</label>
                                                            <select name="lap_tahun" class="form-control select2" required>
                                                                <option value="">-- Pilih Tahun --</option>
                                                                <?php
                                                                $currentYear = date('Y');
                                                                for ($year = 2016; $year <= $currentYear + 1; $year++) {
                                                                    $selected = ($lap_tahun == $year) ? 'selected' : '';
                                                                    echo "<option value=\"$year\" $selected>$year</option>";
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Tab Pencarian Range Tanggal -->
                                            <div class="tab-pane fade <?= !empty($tanggal_awal) && !empty($tanggal_akhir) ? 'show active' : '' ?>"
                                                id="daterange" role="tabpanel">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label><i class="fas fa-calendar-day mr-1"></i> Tanggal Awal:</label>
                                                            <input type="date" name="tanggal_awal" class="form-control"
                                                                value="<?= $tanggal_awal ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label><i class="fas fa-calendar-day mr-1"></i> Tanggal Akhir:</label>
                                                            <input type="date" name="tanggal_akhir" class="form-control"
                                                                value="<?= $tanggal_akhir ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-12">
                                        <button type="submit" name="btn" value="Tampilkan" class="btn btn-danger">
                                            <i class="fas fa-search mr-2"></i> Cari Data
                                        </button>
                                        <button type="reset" class="btn btn-secondary" onclick="resetForm()">
                                            <i class="fas fa-undo mr-2"></i> Reset
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Statistik Cards -->
                    <?php if (isset($statistik)): ?>
                        <div class="row">
                            <div class="col-lg-3 col-md-6">
                                <div class="info-box stat-card">
                                    <span class="info-box-icon bg-info"><i class="fas fa-gavel"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Total Perkara Putus</span>
                                        <span class="info-box-number"><?= number_format($statistik['total_putus']) ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="info-box stat-card">
                                    <span class="info-box-icon bg-success"><i class="fas fa-check-circle"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Sudah BHT</span>
                                        <span class="info-box-number"><?= number_format($statistik['sudah_bht']) ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="info-box stat-card">
                                    <span class="info-box-icon bg-warning"><i class="fas fa-clock"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Belum BHT</span>
                                        <span class="info-box-number"><?= number_format($statistik['belum_bht']) ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="info-box stat-card">
                                    <span class="info-box-icon bg-primary"><i class="fas fa-percentage"></i></span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Persentase BHT</span>
                                        <span class="info-box-number"><?= $statistik['persentase_bht'] ?>%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Main Data Table -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card card-outline card-danger">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-table mr-1"></i> Detail BHT Perkara Putus 3
                                        <?php if (!empty($tanggal_awal) && !empty($tanggal_akhir)): ?>
                                            (<?= date('d/m/Y', strtotime($tanggal_awal)) ?> - <?= date('d/m/Y', strtotime($tanggal_akhir)) ?>)
                                        <?php else: ?>
                                            <?= isset($months) ? $months[$lap_bulan] : '' ?> <?= $lap_tahun ?>
                                        <?php endif; ?>
                                        <?php if (!empty($nomor_perkara)): ?>
                                            <span class="badge badge-secondary">Filter: <?= htmlspecialchars($nomor_perkara) ?></span>
                                        <?php endif; ?>
                                    </h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                        <button type="button" class="btn btn-tool" data-card-widget="maximize">
                                            <i class="fas fa-expand"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <?php if (!empty($bht_putus)): ?>
                                        <div class="table-responsive">
                                            <table id="bhtTable3" class="table table-bordered table-striped table-hover">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center" style="width: 3%;">No</th>
                                                        <th style="width: 10%;">Tanggal Putus</th>
                                                        <th style="width: 12%;">Nomor Perkara</th>
                                                        <th style="width: 10%;">Jenis Perkara</th>
                                                        <th style="width: 15%;">Panitera Pengganti</th>
                                                        <th style="width: 15%;">Juru Sita Pengganti</th>
                                                        <th style="width: 8%;">PBT</th>
                                                        <th style="width: 8%;">BHT</th>
                                                        <th style="width: 8%;">Ikrar</th>
                                                        <th style="width: 8%;">Status BHT</th>
                                                        <th style="width: 8%;">Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $no = 1;
                                                    foreach ($bht_putus as $row) {
                                                        // Format tanggal Indonesia
                                                        $tanggal_putus = $row->tanggal_putus ? date('d/m/Y', strtotime($row->tanggal_putus)) : '-';
                                                        $bht = $row->bht ? date('d/m/Y', strtotime($row->bht)) : '-';
                                                        $ikrar = $row->ikrar ? date('d/m/Y', strtotime($row->ikrar)) : '-';

                                                        // Pemrosesan PBT yang berisi multiple dates
                                                        $pbt_display = '-';
                                                        if ($row->pbt) {
                                                            $pbt_dates = explode('<br>', $row->pbt);
                                                            if (count($pbt_dates) > 1) {
                                                                $pbt_display = '<div class="multiple-dates">';
                                                                foreach ($pbt_dates as $date) {
                                                                    if (!empty($date)) {
                                                                        $pbt_display .= '<span class="date-item">' . date('d/m/Y', strtotime($date)) . '</span>';
                                                                    }
                                                                }
                                                                $pbt_display .= '</div>';
                                                            } else {
                                                                $pbt_display = date('d/m/Y', strtotime($row->pbt));
                                                            }
                                                        }

                                                        // Status badge
                                                        $status_class = $row->status == 'SELESAI' ? 'badge-success' : 'badge-warning';
                                                        $bht_status_class = $row->status_bht == 'SUDAH BHT' ? 'badge-success' : ($row->status_bht == 'BELUM BHT' ? 'badge-warning' : 'badge-secondary');
                                                    ?>
                                                        <tr>
                                                            <td class="text-center"><?= $no++ ?></td>
                                                            <td><?= $tanggal_putus ?></td>
                                                            <td class="font-weight-bold text-primary"><?= $row->nomor_perkara ?></td>
                                                            <td>
                                                                <span class="badge badge-info badge-status"><?= $row->jenis_perkara ?></span>
                                                            </td>
                                                            <td><?= $row->panitera_pengganti_nama ?: '-' ?></td>
                                                            <td><?= $row->jurusita_pengganti_nama ?: '-' ?></td>
                                                            <td class="text-center">
                                                                <?= $pbt_display ?>
                                                            </td>
                                                            <td class="text-center">
                                                                <?php if ($row->bht): ?>
                                                                    <span class="text-success font-weight-bold"><?= $bht ?></span>
                                                                <?php else: ?>
                                                                    <span class="text-muted">-</span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td class="text-center"><?= $ikrar ?></td>
                                                            <td class="text-center">
                                                                <span class="badge <?= $bht_status_class ?> badge-status"><?= $row->status_bht ?></span>
                                                            </td>
                                                            <td class="text-center">
                                                                <span class="badge <?= $status_class ?> badge-status"><?= $row->status ?></span>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php else: ?>
                                        <div class="alert alert-info text-center">
                                            <i class="fas fa-info-circle fa-2x mb-3"></i>
                                            <h5>Belum Ada Data</h5>
                                            <p>Silakan pilih filter periode dan jenis perkara untuk menampilkan data BHT perkara putus.</p>
                                            <?php if (!empty($nomor_perkara)): ?>
                                                <p class="text-muted">Filter pencarian: <strong>"<?= htmlspecialchars($nomor_perkara) ?>"</strong></p>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <!-- jQuery -->
    <script src="<?php echo base_url() ?>assets/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="<?php echo base_url() ?>assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables  & Plugins -->
    <script src="<?php echo base_url() ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="<?php echo base_url() ?>assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="<?php echo base_url() ?>assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="<?php echo base_url() ?>assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="<?php echo base_url() ?>assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="<?php echo base_url() ?>assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="<?php echo base_url() ?>assets/plugins/jszip/jszip.min.js"></script>
    <script src="<?php echo base_url() ?>assets/plugins/pdfmake/pdfmake.min.js"></script>
    <script src="<?php echo base_url() ?>assets/plugins/pdfmake/vfs_fonts.js"></script>
    <script src="<?php echo base_url() ?>assets/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="<?php echo base_url() ?>assets/plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="<?php echo base_url() ?>assets/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
    <!-- Select2 -->
    <script src="<?php echo base_url() ?>assets/plugins/select2/js/select2.full.min.js"></script>
    <!-- DateRangePicker -->
    <script src="<?php echo base_url() ?>assets/plugins/moment/moment.min.js"></script>
    <script src="<?php echo base_url() ?>assets/plugins/daterangepicker/daterangepicker.js"></script>
    <!-- AdminLTE App -->
    <script src="<?php echo base_url() ?>assets/dist/js/adminlte.min.js"></script>

    <script>
        $(function() {
            // Initialize Select2
            $('.select2').select2({
                theme: 'bootstrap4'
            });

            // Initialize DataTable
            $("#bhtTable3").DataTable({
                "responsive": true,
                "lengthChange": true,
                "autoWidth": false,
                "pageLength": 25,
                "lengthMenu": [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "Semua"]
                ],
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
                },
                "buttons": [{
                        extend: 'excel',
                        text: '<i class="fas fa-file-excel"></i> Excel',
                        className: 'btn btn-success btn-sm',
                        title: 'BHT Perkara Putus 3 <?php if (!empty($tanggal_awal) && !empty($tanggal_akhir)): ?>(<?= date("d-m-Y", strtotime($tanggal_awal)) ?> - <?= date("d-m-Y", strtotime($tanggal_akhir)) ?>)<?php else: ?><?= isset($months) ? $months[$lap_bulan] : "" ?> <?= $lap_tahun ?><?php endif; ?>'
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fas fa-file-pdf"></i> PDF',
                        className: 'btn btn-danger btn-sm',
                        title: 'BHT Perkara Putus 3 <?php if (!empty($tanggal_awal) && !empty($tanggal_akhir)): ?>(<?= date("d-m-Y", strtotime($tanggal_awal)) ?> - <?= date("d-m-Y", strtotime($tanggal_akhir)) ?>)<?php else: ?><?= isset($months) ? $months[$lap_bulan] : "" ?> <?= $lap_tahun ?><?php endif; ?>',
                        orientation: 'landscape',
                        pageSize: 'A4'
                    },
                    {
                        extend: 'print',
                        text: '<i class="fas fa-print"></i> Print',
                        className: 'btn btn-info btn-sm'
                    }
                ],
                "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                    '<"row"<"col-sm-12"B>>' +
                    '<"row"<"col-sm-12"tr>>' +
                    '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>'
            });

            // Tab switching logic
            $('#searchTabs a').on('click', function(e) {
                e.preventDefault();
                $(this).tab('show');

                // Clear inputs when switching tabs
                if ($(this).attr('href') === '#monthly') {
                    $('input[name="tanggal_awal"]').val('');
                    $('input[name="tanggal_akhir"]').val('');
                } else {
                    $('select[name="lap_bulan"]').val('').trigger('change');
                    $('select[name="lap_tahun"]').val('').trigger('change');
                }
            });
        });

        function resetForm() {
            $('input[name="nomor_perkara"]').val('');
            $('input[name="tanggal_awal"]').val('');
            $('input[name="tanggal_akhir"]').val('');
            $('select[name="jenis_perkara"]').val('Pdt.G').trigger('change');
            $('select[name="lap_bulan"]').val('').trigger('change');
            $('select[name="lap_tahun"]').val('').trigger('change');

            // Reset to monthly tab
            $('#monthly-tab').tab('show');
        }
    </script>
</body>

</html>
