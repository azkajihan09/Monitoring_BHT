<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1><i class="fas fa-gavel mr-2"></i> Jadwal Persidangan</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="<?= site_url('Home') ?>">Home</a></li>
                                <li class="breadcrumb-item active">Jadwal Persidangan</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="container-fluid">
                    <!-- Quick Stats Cards -->
                    <div class="row">
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3><?= $stats->total_sidang ?? 0 ?></h3>
                                    <p>Jadwal Hari Ini</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <a href="<?= site_url('Persidangan_New/dashboard') ?>" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>

                        <?php if (isset($stats->kehadiran)): ?>
                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <h3><?= $stats->kehadiran->hadir_keduanya ?? 0 ?></h3>
                                        <p>Kehadiran Lengkap</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <a href="<?= site_url('Persidangan_New/dashboard') ?>" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>

                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-warning">
                                    <div class="inner">
                                        <h3><?= $stats->kehadiran->hadir_penggugat + $stats->kehadiran->hadir_tergugat ?? 0 ?></h3>
                                        <p>Kehadiran Sepihak</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <a href="<?= site_url('Persidangan_New/dashboard') ?>" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>

                            <div class="col-lg-3 col-6">
                                <div class="small-box bg-danger">
                                    <div class="inner">
                                        <h3><?= $stats->kehadiran->tidak_hadir ?? 0 ?></h3>
                                        <p>Tidak Hadir</p>
                                    </div>
                                    <div class="icon">
                                        <i class="fas fa-user-slash"></i>
                                    </div>
                                    <a href="<?= site_url('Persidangan_New/dashboard') ?>" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Filter & Action Buttons Card -->
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-filter mr-1"></i> Filter Jadwal Sidang
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="<?= site_url('Persidangan_New') ?>" method="get" class="mb-4">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Pilih Tanggal:</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="far fa-calendar-alt"></i>
                                                    </span>
                                                </div>
                                                <input type="date" class="form-control" name="tanggal_sidang"
                                                    value="<?= isset($filters['tanggal_sidang']) ? $filters['tanggal_sidang'] : date('Y-m-d') ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Jurusita:</label>
                                            <select class="form-control select2" name="jurusita" data-placeholder="Pilih Jurusita">
                                                <option value="">-- Semua Jurusita --</option>
                                                <?php foreach ($jurusita_list as $js): ?>
                                                    <option value="<?= $js->jurusita_id ?>" 
                                                        <?= (isset($filters['jurusita']) && $filters['jurusita'] == $js->jurusita_id) ? 'selected' : '' ?>>
                                                        <?= $js->nama ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <small class="form-text text-muted">
                                                <i class="fas fa-info-circle"></i> Data diambil langsung dari tabel perkara_jurusita
                                            </small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Ruang Sidang:</label>
                                            <select class="form-control select2" name="ruangan_id">
                                                <option value="">-- Semua Ruangan --</option>
                                                <?php foreach ($ruangan_list as $r): ?>
                                                    <option value="<?= $r->id ?>"
                                                        <?= (isset($filters['ruangan_id']) && $filters['ruangan_id'] == $r->id) ? 'selected' : '' ?>>
                                                        <?= $r->nama ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Status:</label>
                                            <select class="form-control" name="status">
                                                <option value="">-- Semua Status --</option>
                                                <option value="pending" <?= (isset($filters['status']) && $filters['status'] == 'pending') ? 'selected' : '' ?>>
                                                    Belum Putus
                                                </option>
                                                <option value="decided" <?= (isset($filters['status']) && $filters['status'] == 'decided') ? 'selected' : '' ?>>
                                                    Sudah Putus
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label>Cari:</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" placeholder="Nomor perkara, nama pihak..."
                                                    name="search" value="<?= $filters['search'] ?? '' ?>">
                                                <div class="input-group-append">
                                                    <button class="btn btn-primary" type="submit">
                                                        <i class="fas fa-search"></i>
                                                    </button>
                                                    <a href="<?= site_url('Persidangan_New') ?>" class="btn btn-default">
                                                        <i class="fas fa-sync-alt"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="btn-group mb-3">
                                        <a href="<?= site_url('Persidangan_New?view=list' . (isset($_GET) ? '&' . http_build_query(array_diff_key($_GET, ['view' => ''])) : '')) ?>"
                                            class="btn btn-default <?= $view_mode == 'list' ? 'active' : '' ?>">
                                            <i class="fas fa-list"></i> List
                                        </a>
                                        <a href="<?= site_url('Persidangan_New?view=card' . (isset($_GET) ? '&' . http_build_query(array_diff_key($_GET, ['view' => ''])) : '')) ?>"
                                            class="btn btn-default <?= $view_mode == 'card' ? 'active' : '' ?>">
                                            <i class="fas fa-th-large"></i> Card
                                        </a>
                                        <a href="<?= site_url('Persidangan_New/calendar') ?>"
                                            class="btn btn-default">
                                            <i class="fas fa-calendar"></i> Calendar
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-6 text-right">
                                    <a href="<?= site_url('Persidangan_New/export_excel?' . http_build_query($_GET)) ?>"
                                        class="btn btn-success mb-3">
                                        <i class="fas fa-file-excel mr-1"></i> Export Excel
                                    </a>
                                    <a href="<?= site_url('Persidangan_New/dashboard') ?>"
                                        class="btn btn-info mb-3">
                                        <i class="fas fa-tachometer-alt mr-1"></i> Dashboard
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if (isset($filters['tanggal_sidang'])): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle mr-1"></i>
                            Menampilkan jadwal sidang untuk tanggal <strong><?= date('d F Y', strtotime($filters['tanggal_sidang'])) ?></strong>
                            <?= isset($filters['jurusita']) ? ' dengan Jurusita <strong>' . $filters['jurusita'] . '</strong>' : '' ?>
                            <?php if (isset($filters['search']) && !empty($filters['search'])): ?>
                                dengan pencarian: <strong><?= $filters['search'] ?></strong>
                            <?php endif; ?>
                        </div>
                    <?php elseif (isset($filters['tanggal_mulai']) && isset($filters['tanggal_akhir'])): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle mr-1"></i>
                            Menampilkan jadwal sidang dari tanggal <strong><?= date('d F Y', strtotime($filters['tanggal_mulai'])) ?></strong>
                            sampai <strong><?= date('d F Y', strtotime($filters['tanggal_akhir'])) ?></strong>
                            <?= isset($filters['jurusita']) ? ' dengan Jurusita <strong>' . $filters['jurusita'] . '</strong>' : '' ?>
                            <?php if (isset($filters['search']) && !empty($filters['search'])): ?>
                                dengan pencarian: <strong><?= $filters['search'] ?></strong>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <!-- List View -->
                    <?php if ($view_mode == 'list'): ?>
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-list mr-1"></i> Daftar Jadwal Sidang
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
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th class="text-center" style="width: 50px">No.</th>
                                                <th style="width: 150px">Nomor Perkara</th>
                                                <th style="width: 100px">Jam Sidang</th>
                                                <th style="width: 300px">Agenda</th>
                                                <th>Majelis Hakim</th>
                                                <th>Ruangan</th>
                                                <th style="width: 200px">Pihak</th>
                                                <th style="width: 100px">Status</th>
                                                <th style="width: 80px">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (empty($sidang_list)): ?>
                                                <tr>
                                                    <td colspan="9" class="text-center">Tidak ada data sidang yang ditemukan</td>
                                                </tr>
                                            <?php else: ?>
                                                <?php
                                                $no = $this->input->get('page') ? $this->input->get('page') + 1 : 1;
                                                foreach ($sidang_list as $sidang):
                                                    // Determine row class based on status
                                                    $rowClass = '';
                                                    if (!empty($sidang->tanggal_putusan)) {
                                                        $rowClass = 'table-success';
                                                    } else if ($sidang->ditunda == 'Y') {
                                                        $rowClass = 'table-warning';
                                                    }
                                                ?>
                                                    <tr class="<?= $rowClass ?>">
                                                        <td class="text-center"><?= $no++ ?></td>
                                                        <td>
                                                            <strong><?= $sidang->nomor_perkara ?></strong><br>
                                                            <small class="text-muted"><?= $sidang->jenis_perkara_nama ?></small>
                                                        </td>
                                                        <td class="text-center">
                                                            <span class="badge badge-primary"><?= date('H:i', strtotime($sidang->jam_sidang)) ?></span>
                                                            <?php if (!empty($sidang->sampai_jam)): ?>
                                                                <br><small>s/d <?= date('H:i', strtotime($sidang->sampai_jam)) ?></small>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <?= !empty($sidang->agenda) ? $sidang->agenda : '<em class="text-muted">Tidak ada agenda</em>' ?>

                                                            <?php if ($sidang->ditunda == 'Y'): ?>
                                                                <br><span class="badge badge-warning">DITUNDA</span>
                                                                <?php if (!empty($sidang->alasan_ditunda)): ?>
                                                                    <br><small class="text-muted"><?= $sidang->alasan_ditunda ?></small>
                                                                <?php endif; ?>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td><?= $sidang->majelis_hakim ?></td>
                                                        <td><?= $sidang->ruangan_nama ?? $sidang->ruangan ?></td>
                                                        <td>
                                                            <div class="small mb-1">
                                                                <i class="fas fa-user-tie mr-1 text-primary"></i> <?= $sidang->nama_p ?>
                                                            </div>
                                                            <div class="small">
                                                                <i class="fas fa-user mr-1 text-danger"></i> <?= $sidang->nama_t ?>
                                                            </div>
                                                        </td>
                                                        <td class="text-center">
                                                            <?php if (!empty($sidang->tanggal_putusan)): ?>
                                                                <span class="badge badge-success">PUTUS</span>
                                                                <br><small><?= date('d-m-Y', strtotime($sidang->tanggal_putusan)) ?></small>
                                                            <?php else: ?>
                                                                <span class="badge badge-info">AKTIF</span>
                                                                <?php
                                                                // Show attendance badge
                                                                $hadir = '';
                                                                $hadir_class = 'badge-secondary';

                                                                switch ($sidang->dihadiri_oleh) {
                                                                    case 0:
                                                                        $hadir = 'Tidak hadir';
                                                                        $hadir_class = 'badge-danger';
                                                                        break;
                                                                    case 1:
                                                                        $hadir = 'Penggugat';
                                                                        $hadir_class = 'badge-primary';
                                                                        break;
                                                                    case 2:
                                                                        $hadir = 'Tergugat';
                                                                        $hadir_class = 'badge-warning';
                                                                        break;
                                                                    case 3:
                                                                        $hadir = 'Keduanya';
                                                                        $hadir_class = 'badge-success';
                                                                        break;
                                                                }

                                                                if (!empty($hadir)): ?>
                                                                    <br><span class="badge <?= $hadir_class ?>"><?= $hadir ?></span>
                                                                <?php endif; ?>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <a href="<?= site_url('Persidangan_New/detail/' . $sidang->perkara_id) ?>" class="btn btn-sm btn-info" title="Lihat Detail">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer clearfix">
                                <?php if ($pagination): ?>
                                    <div class="float-right">
                                        <?= $pagination ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Card View -->
                    <?php elseif ($view_mode == 'card'): ?>
                        <div class="row">
                            <?php if (empty($sidang_list)): ?>
                                <div class="col-12">
                                    <div class="alert alert-warning">
                                        Tidak ada data sidang yang ditemukan
                                    </div>
                                </div>
                            <?php else: ?>
                                <?php foreach ($sidang_list as $sidang):
                                    // Determine card class based on status
                                    $cardClass = 'card-primary';
                                    if (!empty($sidang->tanggal_putusan)) {
                                        $cardClass = 'card-success';
                                    } else if ($sidang->ditunda == 'Y') {
                                        $cardClass = 'card-warning';
                                    }
                                ?>
                                    <div class="col-md-4">
                                        <div class="card <?= $cardClass ?>">
                                            <div class="card-header">
                                                <h3 class="card-title">
                                                    <i class="fas fa-gavel mr-1"></i> <?= date('H:i', strtotime($sidang->jam_sidang)) ?>
                                                </h3>
                                                <div class="card-tools">
                                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <h5 class="mb-3"><?= $sidang->nomor_perkara ?></h5>
                                                <p class="mb-2">
                                                    <strong>Agenda:</strong><br>
                                                    <?= !empty($sidang->agenda) ? $sidang->agenda : '<em class="text-muted">Tidak ada agenda</em>' ?>
                                                    <?php if ($sidang->ditunda == 'Y'): ?>
                                                        <span class="badge badge-warning ml-2">DITUNDA</span>
                                                    <?php endif; ?>
                                                </p>
                                                <p class="mb-2"><strong>Ruangan:</strong> <?= $sidang->ruangan_nama ?? $sidang->ruangan ?></p>
                                                <p class="mb-2"><strong>Majelis Hakim:</strong><br><?= $sidang->majelis_hakim ?></p>
                                                <p class="mb-0">
                                                    <strong>Para Pihak:</strong><br>
                                                    <span class="text-primary"><i class="fas fa-user-tie mr-1"></i> <?= $sidang->nama_p ?></span><br>
                                                    <span class="text-danger"><i class="fas fa-user mr-1"></i> <?= $sidang->nama_t ?></span>
                                                </p>
                                            </div>
                                            <div class="card-footer">
                                                <a href="<?= site_url('Persidangan_New/detail/' . $sidang->perkara_id) ?>" class="btn btn-sm btn-info btn-block">
                                                    <i class="fas fa-eye mr-1"></i> Detail
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="float-right">
                                    <?= $pagination ?>
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
            // Initialize select2 elements with enhanced configuration
            $('.select2').select2({
                theme: 'bootstrap4',
                width: '100%',
                allowClear: true
            });

            // Toggle date filter type
            $('#date_range_toggle').change(function() {
                if ($(this).is(':checked')) {
                    $('#single_date_container').hide();
                    $('#date_range_container').show();
                } else {
                    $('#single_date_container').show();
                    $('#date_range_container').hide();
                }
            });
        });
    </script>
</body>

</html>