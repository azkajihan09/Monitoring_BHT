<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark"><i class="fas fa-gavel mr-2"></i> Detail Perkara Putus Majelis Hakim</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="<?= site_url('Admin/Dashboard') ?>">Home</a></li>
                                <li class="breadcrumb-item"><a href="<?= site_url('Putus_total') ?>">Perkara Putus Total</a></li>
                                <li class="breadcrumb-item active">Detail Majelis</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="container-fluid">
                    <!-- Panel Info Card -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-users mr-1"></i> Informasi Majelis Hakim</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($majelis_hakim_nama)): ?>
                                <div class="row">
                                    <div class="col-md-8">
                                        <h4><?= str_replace('<br />', ' <span class="text-muted">|</span> ', $majelis_hakim_nama) ?></h4>
                                        <p class="text-muted">Jumlah perkara putus yang ditangani: <strong><?= count($cases) ?></strong></p>

                                        <a href="<?= site_url('Putus_total') ?>" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
                                        </a>

                                        <?php if (!empty($cases)): ?>
                                            <a href="<?= site_url('Putus_total/export_detail/' . $majelis_id) ?>" class="btn btn-success ml-2">
                                                <i class="fas fa-file-excel mr-2"></i> Export Excel
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="info-box bg-gradient-success">
                                            <span class="info-box-icon"><i class="fas fa-check-circle"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Status Perkara Putus</span>
                                                <span class="info-box-number">
                                                    <?php
                                                    $minutasi = 0;
                                                    $belum_minutasi = 0;

                                                    foreach ($cases as $case) {
                                                        if (!empty($case->tanggal_minutasi)) {
                                                            $minutasi++;
                                                        } else {
                                                            $belum_minutasi++;
                                                        }
                                                    }

                                                    echo "Minutasi: $minutasi | Belum: $belum_minutasi";
                                                    ?>
                                                </span>
                                                <div class="progress">
                                                    <?php $minutasiRate = count($cases) > 0 ? ($minutasi / count($cases)) * 100 : 0; ?>
                                                    <div class="progress-bar bg-success" style="width: <?= $minutasiRate ?>%"></div>
                                                </div>
                                                <span class="progress-description">
                                                    <?= round($minutasiRate, 1) ?>% telah minutasi
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle mr-2"></i> Data majelis hakim tidak ditemukan
                                </div>
                                <a href="<?= site_url('Putus_total') ?>" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if (!empty($cases)): ?>
                        <!-- Filter Card -->
                        <div class="card card-outline card-secondary collapsed-card">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-filter mr-1"></i> Filter Data</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body" style="display: none;">
                                <form action="<?= site_url('Putus_total/detail/' . $majelis_id) ?>" method="GET" class="form-horizontal">
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Jenis Perkara:</label>
                                        <div class="col-sm-4">
                                            <select name="jenis_perkara" class="form-control select2">
                                                <option value="">-- Semua Jenis --</option>
                                                <option value="Pdt.G" <?= (isset($_GET['jenis_perkara']) && $_GET['jenis_perkara'] === 'Pdt.G') ? 'selected' : ''; ?>>Gugatan (Pdt.G)</option>
                                                <option value="Pdt.P" <?= (isset($_GET['jenis_perkara']) && $_GET['jenis_perkara'] === 'Pdt.P') ? 'selected' : ''; ?>>Permohonan (Pdt.P)</option>
                                            </select>
                                        </div>
                                        <label class="col-sm-2 col-form-label">Status Minutasi:</label>
                                        <div class="col-sm-4">
                                            <select name="status_minutasi" class="form-control select2">
                                                <option value="">-- Semua Status --</option>
                                                <option value="sudah" <?= (isset($_GET['status_minutasi']) && $_GET['status_minutasi'] === 'sudah') ? 'selected' : ''; ?>>Sudah Minutasi</option>
                                                <option value="belum" <?= (isset($_GET['status_minutasi']) && $_GET['status_minutasi'] === 'belum') ? 'selected' : ''; ?>>Belum Minutasi</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Tahun:</label>
                                        <div class="col-sm-4">
                                            <select name="tahun" class="form-control select2">
                                                <option value="">-- Semua Tahun --</option>
                                                <?php
                                                $currentYear = date('Y');
                                                for ($year = $currentYear; $year >= $currentYear - 5; $year--) {
                                                    $selected = (isset($_GET['tahun']) && $_GET['tahun'] == $year) ? 'selected' : '';
                                                    echo "<option value=\"$year\" $selected>$year</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-sm-6">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-search mr-2"></i> Tampilkan
                                            </button>
                                            <a href="<?= site_url('Putus_total/detail/' . $majelis_id) ?>" class="btn btn-secondary ml-2">
                                                <i class="fas fa-sync-alt mr-2"></i> Reset
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Case List Card -->
                        <div class="card card-outline card-primary">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-list mr-1"></i> Daftar Perkara Putus</h3>
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
                                <table class="table table-bordered table-striped" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th class="text-center" width="5%">No</th>
                                            <th width="15%">Nomor Perkara</th>
                                            <th width="15%">Jenis Perkara</th>
                                            <th width="12%">Tanggal Daftar</th>
                                            <th width="12%">Penetapan Majelis</th>
                                            <th width="12%">Tanggal Putusan</th>
                                            <th width="12%">Tanggal Minutasi</th>
                                            <th width="10%">Durasi (hari)</th>
                                            <th width="7%">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        foreach ($cases as $case): ?>
                                            <tr>
                                                <td class="text-center"><?= $no++ ?></td>
                                                <td><?= $case->nomor_perkara ?></td>
                                                <td><?= $case->jenis_perkara_nama ?></td>
                                                <td><?= date('d-m-Y', strtotime($case->tanggal_pendaftaran)) ?></td>
                                                <td>
                                                    <?php if (!empty($case->penetapan_majelis_hakim)): ?>
                                                        <?= date('d-m-Y', strtotime($case->penetapan_majelis_hakim)) ?>
                                                    <?php else: ?>
                                                        <span class="badge badge-warning">Belum Ditetapkan</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if (!empty($case->tanggal_putusan)): ?>
                                                        <?= date('d-m-Y', strtotime($case->tanggal_putusan)) ?>
                                                    <?php else: ?>
                                                        <span class="badge badge-warning">Belum Diputus</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if (!empty($case->tanggal_minutasi)): ?>
                                                        <?= date('d-m-Y', strtotime($case->tanggal_minutasi)) ?>
                                                    <?php else: ?>
                                                        <span class="badge badge-info">Belum Minutasi</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php 
                                                    if (!empty($case->tanggal_putusan) && !empty($case->tanggal_pendaftaran)) {
                                                        $start = new DateTime($case->tanggal_pendaftaran);
                                                        $end = new DateTime($case->tanggal_putusan);
                                                        $duration = $start->diff($end)->days;
                                                        
                                                        $badgeClass = 'success';
                                                        if ($duration > 90) $badgeClass = 'danger';
                                                        elseif ($duration > 60) $badgeClass = 'warning';
                                                        
                                                        echo "<span class=\"badge badge-$badgeClass\">$duration</span>";
                                                    } else {
                                                        echo "<span class=\"badge badge-secondary\">-</span>";
                                                    }
                                                    ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php if (!empty($case->tanggal_minutasi)): ?>
                                                        <span class="badge badge-success">Minutasi</span>
                                                    <?php elseif (!empty($case->tanggal_putusan)): ?>
                                                        <span class="badge badge-warning">Putus</span>
                                                    <?php else: ?>
                                                        <span class="badge badge-secondary">Proses</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer">
                                <div class="float-right">
                                    <small class="text-muted">
                                        Total: <?= count($cases) ?> perkara putus |
                                        Diperbarui: <?= date('d-m-Y H:i:s') ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle mr-2"></i> Tidak ada data perkara putus untuk majelis hakim ini
                        </div>
                    <?php endif; ?>
                </div>
            </section>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Initialize select2
            $('.select2').select2({
                theme: 'bootstrap4'
            });

            // Initialize DataTable
            $('#dataTable').DataTable({
                "responsive": true,
                "lengthChange": true,
                "autoWidth": false,
                "pageLength": 25,
                "language": {
                    "lengthMenu": "Tampilkan _MENU_ data per halaman",
                    "zeroRecords": "Data tidak ditemukan",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                    "infoFiltered": "(difilter dari _MAX_ total data)",
                    "search": "Cari:",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Selanjutnya",
                        "previous": "Sebelumnya"
                    }
                },
                "buttons": [{
                        extend: 'excel',
                        text: 'Excel',
                        title: 'Daftar Perkara Putus Majelis Hakim',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                        }
                    },
                    {
                        extend: 'pdf',
                        text: 'PDF',
                        title: 'Daftar Perkara Putus Majelis Hakim',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
                        },
                        orientation: 'landscape'
                    }
                ]
            }).buttons().container().appendTo('#dataTable_wrapper .col-md-6:eq(0)');
        });
    </script>
</body>

</html>