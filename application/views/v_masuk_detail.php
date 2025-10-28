<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark"><i class="fas fa-gavel mr-2"></i> Detail Perkara Majelis Hakim</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="<?= site_url('Admin/Dashboard') ?>">Home</a></li>
                                <li class="breadcrumb-item"><a href="<?= site_url('Masuk') ?>">Perkara Masuk</a></li>
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
                                        <p class="text-muted">Jumlah perkara yang ditangani: <strong><?= count($cases) ?></strong></p>

                                        <a href="<?= site_url('Masuk') ?>" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar
                                        </a>

                                        <?php if (!empty($cases)): ?>
                                            <a href="<?= site_url('Masuk/export_detail/' . $majelis_id) ?>" class="btn btn-success ml-2">
                                                <i class="fas fa-file-excel mr-2"></i> Export Excel
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="info-box bg-gradient-info">
                                            <span class="info-box-icon"><i class="fas fa-balance-scale"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Status Perkara</span>
                                                <span class="info-box-number">
                                                    <?php
                                                    $active = 0;
                                                    $completed = 0;

                                                    foreach ($cases as $case) {
                                                        if (!empty($case->tanggal_putusan)) {
                                                            $completed++;
                                                        } else {
                                                            $active++;
                                                        }
                                                    }

                                                    echo "Aktif: $active | Selesai: $completed";
                                                    ?>
                                                </span>
                                                <div class="progress">
                                                    <?php $completionRate = count($cases) > 0 ? ($completed / count($cases)) * 100 : 0; ?>
                                                    <div class="progress-bar" style="width: <?= $completionRate ?>%"></div>
                                                </div>
                                                <span class="progress-description">
                                                    <?= round($completionRate, 1) ?>% perkara telah selesai
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle mr-2"></i> Data majelis hakim tidak ditemukan
                                </div>
                                <a href="<?= site_url('Masuk') ?>" class="btn btn-secondary">
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
                                <form action="<?= site_url('Masuk/detail/' . $majelis_id) ?>" method="GET" class="form-horizontal">
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Jenis Perkara:</label>
                                        <div class="col-sm-4">
                                            <select name="jenis_perkara" class="form-control select2">
                                                <option value="">-- Semua Jenis --</option>
                                                <option value="Pdt.G" <?= (isset($_GET['jenis_perkara']) && $_GET['jenis_perkara'] === 'Pdt.G') ? 'selected' : ''; ?>>Gugatan (Pdt.G)</option>
                                                <option value="Pdt.P" <?= (isset($_GET['jenis_perkara']) && $_GET['jenis_perkara'] === 'Pdt.P') ? 'selected' : ''; ?>>Permohonan (Pdt.P)</option>
                                            </select>
                                        </div>
                                        <label class="col-sm-2 col-form-label">Status:</label>
                                        <div class="col-sm-4">
                                            <select name="status" class="form-control select2">
                                                <option value="">-- Semua Status --</option>
                                                <option value="aktif" <?= (isset($_GET['status']) && $_GET['status'] === 'aktif') ? 'selected' : ''; ?>>Aktif</option>
                                                <option value="selesai" <?= (isset($_GET['status']) && $_GET['status'] === 'selesai') ? 'selected' : ''; ?>>Selesai</option>
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
                                            <a href="<?= site_url('Masuk/detail/' . $majelis_id) ?>" class="btn btn-secondary ml-2">
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
                                <h3 class="card-title"><i class="fas fa-list mr-1"></i> Daftar Perkara</h3>
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
                                            <th width="15%">Tanggal Daftar</th>
                                            <th width="15%">Penetapan Majelis</th>
                                            <th width="15%">Sidang Pertama</th>
                                            <th width="15%">Tanggal Putusan</th>
                                            <th width="5%">Status</th>
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
                                                    <?php if (!empty($case->sidang_pertama)): ?>
                                                        <?= date('d-m-Y', strtotime($case->sidang_pertama)) ?>
                                                    <?php else: ?>
                                                        <span class="badge badge-warning">Belum Dijadwalkan</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if (!empty($case->tanggal_putusan)): ?>
                                                        <?= date('d-m-Y', strtotime($case->tanggal_putusan)) ?>
                                                    <?php else: ?>
                                                        <span class="badge badge-info">Proses</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php if (!empty($case->tanggal_putusan)): ?>
                                                        <span class="badge badge-success">Selesai</span>
                                                    <?php else: ?>
                                                        <span class="badge badge-primary">Aktif</span>
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
                                        Total: <?= count($cases) ?> perkara |
                                        Diperbarui: <?= date('d-m-Y H:i:s') ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle mr-2"></i> Tidak ada data perkara untuk majelis hakim ini
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
                        title: 'Daftar Perkara Majelis Hakim',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7]
                        }
                    },
                    {
                        extend: 'pdf',
                        text: 'PDF',
                        title: 'Daftar Perkara Majelis Hakim',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7]
                        },
                        orientation: 'landscape'
                    }
                ]
            }).buttons().container().appendTo('#dataTable_wrapper .col-md-6:eq(0)');
        });
    </script>
</body>

</html>