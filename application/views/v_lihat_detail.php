<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark"><i class="fas fa-file-alt mr-2"></i> Detail Perkara</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="<?= site_url('Admin/Dashboard') ?>">Home</a></li>
                                <li class="breadcrumb-item"><a href="javascript:history.back()">Kembali</a></li>
                                <li class="breadcrumb-item active">Detail Perkara</li>
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
                            <a href="javascript:history.back()" class="btn btn-secondary">
                                <i class="fas fa-arrow-left mr-1"></i> Kembali
                            </a>
                            <?php if (isset($perkara->perkara_id)): ?>
                                <a href="<?= site_url('Perkara/timeline/' . $perkara->perkara_id) ?>" class="btn btn-primary ml-2">
                                    <i class="fas fa-history mr-1"></i> Timeline
                                </a>
                            <?php endif; ?>
                            <div class="btn-group ml-2">
                                <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                                    <i class="fas fa-print mr-1"></i> Cetak
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#" id="printDetail">
                                        <i class="fas fa-file-alt mr-2"></i> Detail Perkara
                                    </a>
                                    <?php if (isset($perkara->nomor_akta_cerai)): ?>
                                        <a class="dropdown-item" href="#" id="printAktaCerai">
                                            <i class="fas fa-file-contract mr-2"></i> Akta Cerai
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Case Overview Card -->
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-info-circle mr-1"></i>
                                Informasi Umum Perkara
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
                                            <td><?= isset($perkara->nomor_perkara) ? $perkara->nomor_perkara : '-' ?></td>
                                        </tr>
                                        <tr>
                                            <th>Tanggal Daftar</th>
                                            <td><?= isset($perkara->tanggal_pendaftaran) ? date('d-m-Y', strtotime($perkara->tanggal_pendaftaran)) : '-' ?></td>
                                        </tr>
                                        <tr>
                                            <th>Jenis Perkara</th>
                                            <td><?= isset($perkara->jenis_perkara_nama) ? $perkara->jenis_perkara_nama : '-' ?></td>
                                        </tr>
                                        <tr>
                                            <th>Status Perkara</th>
                                            <td>
                                                <?php if (isset($perkara->status_perkara)): ?>
                                                    <?php if ($perkara->status_perkara == 'Putus'): ?>
                                                        <span class="badge badge-success">Putus</span>
                                                    <?php elseif ($perkara->status_perkara == 'Proses'): ?>
                                                        <span class="badge badge-info">Proses</span>
                                                    <?php else: ?>
                                                        <span class="badge badge-secondary"><?= $perkara->status_perkara ?></span>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    -
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-striped">
                                        <tr>
                                            <th style="width: 30%">Tanggal Putus</th>
                                            <td><?= isset($perkara->tanggal_putusan) ? date('d-m-Y', strtotime($perkara->tanggal_putusan)) : '-' ?></td>
                                        </tr>
                                        <tr>
                                            <th>Tanggal Minutasi</th>
                                            <td><?= isset($perkara->tanggal_minutasi) ? date('d-m-Y', strtotime($perkara->tanggal_minutasi)) : '-' ?></td>
                                        </tr>
                                        <tr>
                                            <th>Majelis Hakim</th>
                                            <td><?= isset($perkara->majelis_hakim_nama) ? $perkara->majelis_hakim_nama : '-' ?></td>
                                        </tr>
                                        <tr>
                                            <th>Panitera Pengganti</th>
                                            <td><?= isset($perkara->panitera_pengganti_text) ? $perkara->panitera_pengganti_text : '-' ?></td>
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
                                        Para Pihak
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
                                                <h5><?= isset($perkara->nama_p) ? $perkara->nama_p : 'Tidak ada data' ?></h5>
                                                <?php if (isset($perkara->alamat_p)): ?>
                                                    <p class="mb-0"><?= $perkara->alamat_p ?></p>
                                                <?php endif; ?>
                                                <?php if (isset($perkara->telepon_p)): ?>
                                                    <p class="mb-0"><i class="fas fa-phone mr-1"></i> <?= $perkara->telepon_p ?></p>
                                                <?php endif; ?>
                                            </div>

                                            <h5 class="text-primary mt-4">Tergugat/Termohon</h5>
                                            <div class="callout callout-warning">
                                                <h5><?= isset($perkara->nama_t) ? $perkara->nama_t : 'Tidak ada data' ?></h5>
                                                <?php if (isset($perkara->alamat_t)): ?>
                                                    <p class="mb-0"><?= $perkara->alamat_t ?></p>
                                                <?php endif; ?>
                                                <?php if (isset($perkara->telepon_t)): ?>
                                                    <p class="mb-0"><i class="fas fa-phone mr-1"></i> <?= $perkara->telepon_t ?></p>
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
                                                    <th>Tanggal</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if (isset($dokumen) && !empty($dokumen)): ?>
                                                    <?php $no = 1;
                                                    foreach ($dokumen as $dok): ?>
                                                        <tr>
                                                            <td><?= $no++ ?></td>
                                                            <td><?= $dok->nama_dokumen ?></td>
                                                            <td><?= date('d-m-Y', strtotime($dok->tanggal)) ?></td>
                                                            <td>
                                                                <?php if ($dok->status == 'Tersedia'): ?>
                                                                    <span class="badge badge-success">Tersedia</span>
                                                                <?php else: ?>
                                                                    <span class="badge badge-secondary">Belum Tersedia</span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <?php if ($dok->status == 'Tersedia'): ?>
                                                                    <a href="<?= $dok->link_dokumen ?>" class="btn btn-sm btn-info" target="_blank">
                                                                        <i class="fas fa-download"></i>
                                                                    </a>
                                                                <?php else: ?>
                                                                    <button disabled class="btn btn-sm btn-secondary">
                                                                        <i class="fas fa-download"></i>
                                                                    </button>
                                                                <?php endif; ?>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="5" class="text-center">
                                                            <i class="fas fa-info-circle mr-1"></i> Tidak ada dokumen yang tersedia
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
                                Proses Perkara
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if (isset($jadwal_sidang) && !empty($jadwal_sidang)): ?>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr class="bg-light">
                                                <th>No</th>
                                                <th>Tanggal</th>
                                                <th>Jam</th>
                                                <th>Agenda</th>
                                                <th>Ruangan</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1;
                                            foreach ($jadwal_sidang as $jadwal): ?>
                                                <tr>
                                                    <td><?= $no++ ?></td>
                                                    <td><?= date('d-m-Y', strtotime($jadwal->tanggal_sidang)) ?></td>
                                                    <td><?= $jadwal->jam_sidang ?></td>
                                                    <td><?= $jadwal->agenda ?></td>
                                                    <td><?= $jadwal->ruangan ?></td>
                                                    <td>
                                                        <?php if ($jadwal->status_sidang == 'Selesai'): ?>
                                                            <span class="badge badge-success">Selesai</span>
                                                        <?php elseif ($jadwal->status_sidang == 'Tunda'): ?>
                                                            <span class="badge badge-warning">Ditunda</span>
                                                        <?php elseif ($jadwal->status_sidang == 'Jadwal'): ?>
                                                            <span class="badge badge-primary">Dijadwalkan</span>
                                                        <?php else: ?>
                                                            <span class="badge badge-secondary"><?= $jadwal->status_sidang ?></span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle mr-1"></i> Belum ada jadwal sidang yang tersedia
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Additional Data Sections -->
                    <?php if (isset($perkara->amar_putusan) && !empty($perkara->amar_putusan)): ?>
                        <div class="card card-outline card-secondary">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-gavel mr-1"></i>
                                    Amar Putusan
                                </h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="callout callout-light">
                                    <?= $perkara->amar_putusan ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Handle print detail
            $("#printDetail").click(function(e) {
                e.preventDefault();
                window.print();
            });

            <?php if (isset($perkara->nomor_akta_cerai)): ?>
                // Handle print akta cerai
                $("#printAktaCerai").click(function(e) {
                    e.preventDefault();
                    var url = "<?= site_url('Perkara/print_akta_cerai/' . (isset($perkara->perkara_id) ? $perkara->perkara_id : '')) ?>";
                    window.open(url, '_blank');
                });
            <?php endif; ?>
        });
    </script>
</body>