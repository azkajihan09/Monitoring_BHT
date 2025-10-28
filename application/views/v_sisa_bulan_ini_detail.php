<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark"><i class="fas fa-file-alt mr-2"></i> Detail Sisa Perkara</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="<?= site_url('Admin/Dashboard') ?>">Home</a></li>
                                <li class="breadcrumb-item"><a href="<?= site_url('Sisa_bulan_ini') ?>">Sisa Perkara</a></li>
                                <li class="breadcrumb-item active">Detail</li>
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
                            <h3 class="card-title"><i class="fas fa-user-tie mr-1"></i> Informasi Majelis Hakim</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <?php if (count($majelis_list) > 1): ?>
                                        <h4>Multiple Majelis Hakim (<?= count($majelis_list) ?>)</h4>
                                        <ul class="list-group mb-3">
                                            <?php foreach ($majelis_list as $majelis): ?>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <?= str_replace('<br />', ' <span class="text-muted">|</span> ', $majelis) ?>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php else: ?>
                                        <h4><?= str_replace('<br />', ' <span class="text-muted">|</span> ', $majelis_hakim_nama) ?></h4>
                                    <?php endif; ?>

                                    <p class="text-muted">Jumlah sisa perkara: <strong><?= count($detail_cases) ?></strong></p>

                                    <a href="<?= site_url('Sisa_bulan_ini?jenis_perkara=' . $jenis_perkara . '&lap_bulan=' . $lap_bulan . '&lap_tahun=' . $lap_tahun) ?>" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                                    </a>

                                    <a href="<?= site_url('Sisa_bulan_ini/export_detail/' . $majelis_hakim_id . '?jenis_perkara=' . $jenis_perkara . '&lap_bulan=' . $lap_bulan . '&lap_tahun=' . $lap_tahun) ?>" class="btn btn-success ml-2">
                                        <i class="fas fa-file-excel mr-2"></i> Export Excel
                                    </a>
                                </div>
                                <div class="col-md-4 text-right">
                                    <div class="info-box bg-gradient-info">
                                        <span class="info-box-icon"><i class="far fa-calendar"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Periode</span>
                                            <span class="info-box-number"><?= $months[$lap_bulan] ?> <?= $lap_tahun ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Case Filter Card -->
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
                            <form action="<?= site_url('Sisa_bulan_ini/detail/' . $majelis_hakim_id) ?>" method="GET" class="form-horizontal">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Jenis Perkara:</label>
                                    <div class="col-sm-4">
                                        <select name="jenis_perkara" class="form-control select2">
                                            <option value="Pdt.G" <?= ($jenis_perkara === 'Pdt.G') ? 'selected' : ''; ?>>Gugatan (Pdt.G)</option>
                                            <option value="Pdt.P" <?= ($jenis_perkara === 'Pdt.P') ? 'selected' : ''; ?>>Permohonan (Pdt.P)</option>
                                            <option value="all" <?= ($jenis_perkara === 'all') ? 'selected' : ''; ?>>Semua Jenis</option>
                                        </select>
                                    </div>
                                    <label class="col-sm-2 col-form-label">Periode:</label>
                                    <div class="col-sm-4">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <select name="lap_bulan" class="form-control select2">
                                                    <?php foreach ($months as $value => $label): ?>
                                                        <option value="<?= $value ?>" <?= ($lap_bulan === $value) ? 'selected' : ''; ?>><?= $label ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-sm-6">
                                                <select name="lap_tahun" class="form-control select2">
                                                    <?php
                                                    $currentYear = date('Y');
                                                    for ($year = 2016; $year <= $currentYear; $year++): ?>
                                                        <option value="<?= $year ?>" <?= ($lap_tahun == $year) ? 'selected' : ''; ?>><?= $year ?></option>
                                                    <?php endfor; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Cari Perkara:</label>
                                    <div class="col-sm-6">
                                        <input type="text" name="search" class="form-control" placeholder="Nomor perkara, nama pihak..." value="<?= isset($search) ? $search : '' ?>">
                                    </div>
                                    <div class="col-sm-4">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search mr-2"></i> Tampilkan
                                        </button>
                                        <a href="<?= site_url('Sisa_bulan_ini/detail/' . $majelis_hakim_id) ?>" class="btn btn-secondary">
                                            <i class="fas fa-sync-alt mr-2"></i> Reset
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <?php if (!empty($detail_cases)): ?>
                        <!-- Case List Card -->
                        <div class="card card-outline card-primary">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-list-alt mr-1"></i> Daftar Sisa Perkara</h3>
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
                                <table id="dataTable" class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th class="text-center" width="5%">No</th>
                                            <th width="15%">Nomor Perkara</th>
                                            <th width="15%">Jenis Perkara</th>
                                            <th width="10%">Tanggal Daftar</th>
                                            <th width="10%">Umur Perkara</th>
                                            <th width="20%">Penggugat/Pemohon</th>
                                            <th width="20%">Tergugat/Termohon</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        foreach ($detail_cases as $case): ?>
                                            <tr>
                                                <td class="text-center"><?= $no++ ?></td>
                                                <td><?= $case->nomor_perkara ?></td>
                                                <td><?= $case->jenis_perkara_nama ?></td>
                                                <td><?= date('d-m-Y', strtotime($case->tanggal_pendaftaran)) ?></td>
                                                <td>
                                                    <?php
                                                    $days = $case->usia_perkara;
                                                    $class = 'success';
                                                    if ($days > 180) $class = 'danger';
                                                    elseif ($days > 90) $class = 'warning';
                                                    elseif ($days > 30) $class = 'info';
                                                    ?>
                                                    <span class="badge badge-<?= $class ?>"><?= $days ?> hari</span>
                                                </td>
                                                <td><?= isset($case->nama_penggugat) && !empty($case->nama_penggugat) ? $case->nama_penggugat : '-' ?></td>
                                                <td><?= isset($case->nama_tergugat) && !empty($case->nama_tergugat) ? $case->nama_tergugat : '-' ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer text-right">
                                <small class="text-muted">
                                    Total: <?= count($detail_cases) ?> perkara |
                                    Tanggal cetak: <?= date('d-m-Y H:i:s') ?>
                                </small>
                            </div>
                        </div>

                        <!-- Statistics Section -->
                        <div class="row">
                            <div class="col-md-6">
                                <!-- Case Age Card -->
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title"><i class="fas fa-hourglass-half mr-1"></i> Distribusi Umur Perkara</h3>
                                    </div>
                                    <div class="card-body p-0">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Kategori</th>
                                                    <th class="text-center">Jumlah</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $under30 = 0;
                                                $under90 = 0;
                                                $under180 = 0;
                                                $over180 = 0;

                                                foreach ($detail_cases as $case) {
                                                    $days = $case->usia_perkara;
                                                    if ($days <= 30) $under30++;
                                                    elseif ($days <= 90) $under90++;
                                                    elseif ($days <= 180) $under180++;
                                                    else $over180++;
                                                }
                                                ?>
                                                <tr>
                                                    <td><span class="badge badge-success">Kurang dari 30 hari</span></td>
                                                    <td class="text-center"><?= $under30 ?></td>
                                                </tr>
                                                <tr>
                                                    <td><span class="badge badge-info">30 - 90 hari</span></td>
                                                    <td class="text-center"><?= $under90 ?></td>
                                                </tr>
                                                <tr>
                                                    <td><span class="badge badge-warning">91 - 180 hari</span></td>
                                                    <td class="text-center"><?= $under180 ?></td>
                                                </tr>
                                                <tr>
                                                    <td><span class="badge badge-danger">Lebih dari 180 hari</span></td>
                                                    <td class="text-center"><?= $over180 ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- Case Type Card -->
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title"><i class="fas fa-chart-pie mr-1"></i> Jenis Perkara</h3>
                                    </div>
                                    <div class="card-body p-0">
                                        <?php
                                        $caseTypes = [];
                                        foreach ($detail_cases as $case) {
                                            if (!isset($caseTypes[$case->jenis_perkara_nama])) {
                                                $caseTypes[$case->jenis_perkara_nama] = 0;
                                            }
                                            $caseTypes[$case->jenis_perkara_nama]++;
                                        }
                                        ?>
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Jenis Perkara</th>
                                                    <th class="text-center">Jumlah</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($caseTypes as $type => $count): ?>
                                                    <tr>
                                                        <td><?= $type ?></td>
                                                        <td class="text-center"><?= $count ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- No Data Alert -->
                        <div class="alert alert-info">
                            <h5><i class="icon fas fa-info"></i> Informasi</h5>
                            <p>Tidak ditemukan sisa perkara untuk majelis hakim ini pada periode yang dipilih.</p>
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
            $("#dataTable").DataTable({
                "responsive": true,
                "lengthChange": true,
                "autoWidth": false,
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
                    title: 'Detail Sisa Perkara - Majelis Hakim',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6]
                    }
                }]
            }).buttons().container().appendTo('#dataTable_wrapper .col-md-6:eq(0)');
        });
    </script>
</body>

</html>