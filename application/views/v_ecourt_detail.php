<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark"><i class="fas fa-file-alt mr-2"></i> Detail Perkara E-Court</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="<?= site_url('Admin/Dashboard') ?>">Home</a></li>
                                <li class="breadcrumb-item"><a href="<?= site_url('Ecourt') ?>">E-Court</a></li>
                                <li class="breadcrumb-item active">Detail</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="container-fluid">
                    <!-- Action buttons -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <a href="<?= site_url('Ecourt') ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left mr-1"></i> Kembali
                            </a>
                            <a href="<?= site_url('Ecourt_monitoring/timeline/' . $case->perkara_id) ?>" class="btn btn-primary ml-2">
                                <i class="fas fa-history mr-1"></i> Lihat Timeline
                            </a>
                            <div class="btn-group ml-2">
                                <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                                    <i class="fas fa-print mr-1"></i> Cetak
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#" id="printDetail">
                                        <i class="fas fa-file-alt mr-2"></i> Detail Perkara
                                    </a>
                                    <a class="dropdown-item" href="#" id="printSKUM">
                                        <i class="fas fa-file-invoice-dollar mr-2"></i> SKUM
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Case Overview Card -->
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-info-circle mr-1"></i>
                                Informasi Umum
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-striped">
                                        <tr>
                                            <th style="width: 30%">Nomor Perkara</th>
                                            <td><?= !empty($case->nomor_perkara) ? $case->nomor_perkara : '<span class="badge badge-warning">Belum Terdaftar</span>' ?></td>
                                        </tr>
                                        <tr>
                                            <th>ID E-Court</th>
                                            <td><?= !empty($case->efiling_id) ? $case->efiling_id : '-' ?></td>
                                        </tr>
                                        <tr>
                                            <th>Tanggal Daftar</th>
                                            <td><?= !empty($case->ecourt_reg_date) ? date('d-m-Y', strtotime($case->ecourt_reg_date)) : '-' ?></td>
                                        </tr>
                                        <tr>
                                            <th>Jenis Perkara</th>
                                            <td><?= !empty($case->jenis_perkara_nama) ? $case->jenis_perkara_nama : '-' ?></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-striped">
                                        <tr>
                                            <th style="width: 30%">Status Pembayaran</th>
                                            <td>
                                                <?php if (!empty($case->status_pembayaran) && $case->status_pembayaran == '1'): ?>
                                                    <span class="badge badge-success">Lunas</span>
                                                    <span class="ml-2">Rp <?= number_format($case->jumlah_skum, 0, ',', '.') ?></span>
                                                <?php else: ?>
                                                    <span class="badge badge-warning">Belum Dibayar</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Tanggal Bayar</th>
                                            <td><?= !empty($case->tanggal_bayar) ? date('d-m-Y', strtotime($case->tanggal_bayar)) : '-' ?></td>
                                        </tr>
                                        <tr>
                                            <th>Proses Terakhir</th>
                                            <td>
                                                <?php
                                                if (!empty($case->proses_terakhir_text)) {
                                                    echo '<span class="badge badge-info">' . $case->proses_terakhir_text . '</span>';
                                                } else if (!empty($case->ecourt_reg_date)) {
                                                    echo '<span class="badge badge-secondary">Pendaftaran</span>';
                                                } else {
                                                    echo '-';
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Tahapan Terakhir</th>
                                            <td>
                                                <?php
                                                if (!empty($case->tahapan_terakhir_text)) {
                                                    echo '<span class="badge badge-primary">' . $case->tahapan_terakhir_text . '</span>';
                                                } else {
                                                    echo '-';
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Parties Info Card -->
                        <div class="col-md-6">
                            <div class="card card-outline card-info">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-user mr-1"></i>
                                        Pihak Berperkara
                                    </h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <h5 class="text-primary">Penggugat/Pemohon</h5>
                                            <div class="callout callout-info">
                                                <h5><?= !empty($case->nama_penggugat) ? $case->nama_penggugat : 'Belum terdaftar' ?></h5>
                                                <?php if (!empty($case->pihak1_text)): ?>
                                                    <p class="mb-0"><?= $case->pihak1_text ?></p>
                                                <?php endif; ?>
                                            </div>

                                            <h5 class="text-primary mt-4">Tergugat/Termohon</h5>
                                            <div class="callout callout-warning">
                                                <h5><?= !empty($case->nama_tergugat) ? $case->nama_tergugat : 'Belum terdaftar' ?></h5>
                                                <?php if (!empty($case->pihak2_text)): ?>
                                                    <p class="mb-0"><?= $case->pihak2_text ?></p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Case Documents Card -->
                        <div class="col-md-6">
                            <div class="card card-outline card-success">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-file-pdf mr-1"></i>
                                        Dokumen Perkara
                                    </h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Jenis Dokumen</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (!empty($case->nomor_perkara)): ?>
                                                    <tr>
                                                        <td>1</td>
                                                        <td>Surat Gugatan/Permohonan</td>
                                                        <td>
                                                            <?php if (!empty($case->surat_dok)): ?>
                                                                <span class="badge badge-success">Ada</span>
                                                            <?php else: ?>
                                                                <span class="badge badge-secondary">Belum Upload</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <?php if (!empty($case->surat_dok)): ?>
                                                                <a href="#" class="btn btn-sm btn-info">
                                                                    <i class="fas fa-download"></i>
                                                                </a>
                                                            <?php else: ?>
                                                                <button disabled class="btn btn-sm btn-secondary">
                                                                    <i class="fas fa-download"></i>
                                                                </button>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>2</td>
                                                        <td>SKUM</td>
                                                        <td>
                                                            <span class="badge badge-success">Ada</span>
                                                        </td>
                                                        <td>
                                                            <a href="#" class="btn btn-sm btn-info print-skum">
                                                                <i class="fas fa-print"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>3</td>
                                                        <td>Bukti Pembayaran</td>
                                                        <td>
                                                            <?php if (!empty($case->tanggal_bayar)): ?>
                                                                <span class="badge badge-success">Ada</span>
                                                            <?php else: ?>
                                                                <span class="badge badge-secondary">Belum Upload</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <?php if (!empty($case->tanggal_bayar)): ?>
                                                                <a href="#" class="btn btn-sm btn-info">
                                                                    <i class="fas fa-download"></i>
                                                                </a>
                                                            <?php else: ?>
                                                                <button disabled class="btn btn-sm btn-secondary">
                                                                    <i class="fas fa-download"></i>
                                                                </button>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="4" class="text-center">
                                                            <i class="fas fa-info-circle mr-1"></i> Dokumen akan tersedia setelah perkara teregistrasi
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

                    <!-- Case Progress Card -->
                    <div class="card card-outline card-warning">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-tasks mr-1"></i>
                                Progress Perkara
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="progress-group">
                                <span class="progress-text">Pendaftaran</span>
                                <span class="float-right"><b>100%</b></span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-success" style="width: 100%"></div>
                                </div>
                            </div>

                            <div class="progress-group">
                                <span class="progress-text">Pembayaran</span>
                                <span class="float-right"><b><?= !empty($case->tanggal_bayar) ? '100%' : '0%' ?></b></span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-success" style="width: <?= !empty($case->tanggal_bayar) ? '100%' : '0%' ?>"></div>
                                </div>
                            </div>

                            <div class="progress-group">
                                <span class="progress-text">Registrasi</span>
                                <span class="float-right"><b><?= !empty($case->nomor_perkara) ? '100%' : '0%' ?></b></span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-success" style="width: <?= !empty($case->nomor_perkara) ? '100%' : '0%' ?>"></div>
                                </div>
                            </div>

                            <div class="progress-group">
                                <span class="progress-text">Penetapan Majelis Hakim</span>
                                <span class="float-right">
                                    <b><?= !empty($case->penetapan_majelis_hakim) ? '100%' : '0%' ?></b>
                                </span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-success" style="width: <?= !empty($case->penetapan_majelis_hakim) ? '100%' : '0%' ?>"></div>
                                </div>
                            </div>

                            <div class="progress-group">
                                <span class="progress-text">Persidangan</span>
                                <span class="float-right">
                                    <b><?= (!empty($case->penetapan_hari_sidang) && empty($case->tanggal_putusan)) ? '50%' : (!empty($case->tanggal_putusan) ? '100%' : '0%') ?></b>
                                </span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-success" style="width: <?=
                                                                                        (!empty($case->penetapan_hari_sidang) && empty($case->tanggal_putusan)) ? '50%' : (!empty($case->tanggal_putusan) ? '100%' : '0%') ?>"></div>
                                </div>
                            </div>

                            <div class="progress-group">
                                <span class="progress-text">Putusan</span>
                                <span class="float-right">
                                    <b><?= !empty($case->tanggal_putusan) ? '100%' : '0%' ?></b>
                                </span>
                                <div class="progress progress-sm">
                                    <div class="progress-bar bg-success" style="width: <?= !empty($case->tanggal_putusan) ? '100%' : '0%' ?>"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notes Card (Optional) -->
                    <?php if (!empty($case->posita) || !empty($case->petitum) || !empty($case->catatan_pendaftaran)): ?>
                        <div class="card card-outline card-secondary">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-sticky-note mr-1"></i>
                                    Catatan Perkara
                                </h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($case->posita)): ?>
                                    <div class="form-group">
                                        <label for="posita"><strong>Posita:</strong></label>
                                        <div class="callout callout-info">
                                            <?= $case->posita ?>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($case->petitum)): ?>
                                    <div class="form-group">
                                        <label for="petitum"><strong>Petitum:</strong></label>
                                        <div class="callout callout-info">
                                            <?= $case->petitum ?>
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <?php if (!empty($case->catatan_pendaftaran)): ?>
                                    <div class="form-group">
                                        <label for="catatan"><strong>Catatan Pendaftaran:</strong></label>
                                        <div class="callout callout-warning">
                                            <?= $case->catatan_pendaftaran ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Print detail
            $("#printDetail").click(function(e) {
                e.preventDefault();
                window.print();
            });

            // Print SKUM
            $("#printSKUM, .print-skum").click(function(e) {
                e.preventDefault();
                var url = "<?= site_url('Ecourt/print_skum/' . $case->perkara_id) ?>";
                window.open(url, '_blank');
            });
        });
    </script>
</body>

</html>