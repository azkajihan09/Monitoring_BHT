<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">📊 BHT Perkara Putus 4 - Dengan Pengurutan Tanggal</h1>
                    <p class="text-muted">Sistem monitoring BHT dengan pengurutan tanggal yang lebih baik</p>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Home</a></li>
                        <li class="breadcrumb-item active">BHT Putus 4</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            <!-- Filter dan Sorting Card -->
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-filter"></i> Filter & Pengurutan Data
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <?= form_open('bht_putus_4', array('method' => 'post', 'id' => 'filterForm')) ?>

                    <div class="row">
                        <!-- Jenis Perkara -->
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="jenis_perkara">Jenis Perkara</label>
                                <select name="jenis_perkara" id="jenis_perkara" class="form-control">
                                    <option value="Pdt.G" <?= ($jenis_perkara == 'Pdt.G') ? 'selected' : '' ?>>Pdt.G</option>
                                    <option value="Pdt.P" <?= ($jenis_perkara == 'Pdt.P') ? 'selected' : '' ?>>Pdt.P</option>
                                    <option value="Pdt.Sus" <?= ($jenis_perkara == 'Pdt.Sus') ? 'selected' : '' ?>>Pdt.Sus</option>
                                </select>
                            </div>
                        </div>

                        <!-- Bulan -->
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="lap_bulan">Bulan</label>
                                <select name="lap_bulan" id="lap_bulan" class="form-control">
                                    <?php foreach ($months as $key => $month): ?>
                                        <option value="<?= $key ?>" <?= ($lap_bulan == $key) ? 'selected' : '' ?>>
                                            <?= $month ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Tahun -->
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="lap_tahun">Tahun</label>
                                <select name="lap_tahun" id="lap_tahun" class="form-control">
                                    <?php for ($year = date('Y'); $year >= 2020; $year--): ?>
                                        <option value="<?= $year ?>" <?= ($lap_tahun == $year) ? 'selected' : '' ?>>
                                            <?= $year ?>
                                        </option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Tanggal Range -->
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="tanggal_awal">Tanggal Awal</label>
                                <input type="date" name="tanggal_awal" id="tanggal_awal" class="form-control"
                                    value="<?= $tanggal_awal ?>">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="tanggal_akhir">Tanggal Akhir</label>
                                <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control"
                                    value="<?= $tanggal_akhir ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Nomor Perkara -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="nomor_perkara">Nomor Perkara</label>
                                <input type="text" name="nomor_perkara" id="nomor_perkara" class="form-control"
                                    placeholder="Cari nomor perkara..." value="<?= $nomor_perkara ?>">
                            </div>
                        </div>

                        <!-- Urutkan Berdasarkan -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="order_by">
                                    <i class="fas fa-sort"></i> Urutkan Berdasarkan
                                </label>
                                <select name="order_by" id="order_by" class="form-control">
                                    <?php foreach ($sort_options as $key => $option): ?>
                                        <option value="<?= $key ?>" <?= ($order_by == $key) ? 'selected' : '' ?>>
                                            <?= $option ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Arah Urutan -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="order_dir">
                                    <i class="fas fa-sort-amount-down"></i> Arah Urutan
                                </label>
                                <select name="order_dir" id="order_dir" class="form-control">
                                    <?php foreach ($sort_directions as $key => $direction): ?>
                                        <option value="<?= $key ?>" <?= ($order_dir == $key) ? 'selected' : '' ?>>
                                            <?= $direction ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <div class="d-block">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fas fa-search"></i> Filter
                                    </button>
                                    <button type="button" class="btn btn-secondary btn-sm" id="resetBtn">
                                        <i class="fas fa-undo"></i> Reset
                                    </button>
                                    <button type="button" class="btn btn-success btn-sm" id="exportBtn">
                                        <i class="fas fa-file-excel"></i> Export
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?= form_close() ?>
                </div>
            </div>

            <!-- Statistik Cards -->
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3><?= isset($statistik['total_perkara']) ? $statistik['total_perkara'] : 0 ?></h3>
                            <p>Total Perkara</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-folder"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3><?= isset($statistik['bht_selesai']) ? $statistik['bht_selesai'] : 0 ?></h3>
                            <p>BHT Selesai</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3><?= isset($statistik['bht_proses']) ? $statistik['bht_proses'] : 0 ?></h3>
                            <p>BHT Proses</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3><?= isset($statistik['bht_terlambat']) ? $statistik['bht_terlambat'] : 0 ?></h3>
                            <p>BHT Terlambat</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info Periode dan Total Records -->
            <div class="row">
                <div class="col-12">
                    <div class="card card-secondary">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <h5>
                                        <i class="fas fa-calendar"></i> <?= isset($periode_text) ? $periode_text : '' ?>
                                    </h5>
                                    <p class="text-muted mb-0">
                                        Diurutkan berdasarkan: <strong><?= $sort_options[$order_by] ?></strong>
                                        (<strong><?= $sort_directions[$order_dir] ?></strong>)
                                    </p>
                                </div>
                                <div class="col-md-4 text-right">
                                    <h5>
                                        <i class="fas fa-list"></i> Total Records:
                                        <span class="badge badge-primary"><?= $total_records ?></span>
                                    </h5>
                                    <p class="text-muted mb-0">Data yang ditampilkan</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel Data BHT -->
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-table"></i> Data BHT Perkara Putus
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="maximize">
                            <i class="fas fa-expand"></i>
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="bhtTable" class="table table-bordered table-striped table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th style="width: 50px;">#</th>
                                    <th>Nomor Perkara</th>
                                    <th>
                                        <i class="fas fa-calendar"></i> Tanggal Putus
                                        <?php if ($order_by == 'tanggal_putus'): ?>
                                            <i class="fas fa-sort-<?= ($order_dir == 'DESC') ? 'down' : 'up' ?> text-warning"></i>
                                        <?php endif; ?>
                                    </th>
                                    <th>Jenis Perkara</th>
                                    <th>Panitera Pengganti</th>
                                    <th>Jurusita</th>
                                    <th>PBT</th>
                                    <th>
                                        BHT
                                        <?php if ($order_by == 'bht'): ?>
                                            <i class="fas fa-sort-<?= ($order_dir == 'DESC') ? 'down' : 'up' ?> text-warning"></i>
                                        <?php endif; ?>
                                    </th>
                                    <th>Ikrar</th>
                                    <th>Status BHT</th>
                                    <th>Kategori</th>
                                    <th>Selisih Hari</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($bht_putus) && is_array($bht_putus)): ?>
                                    <?php $no = 1; ?>
                                    <?php foreach ($bht_putus as $row): ?>
                                        <tr>
                                            <td class="text-center"><?= $no++ ?></td>
                                            <td>
                                                <strong><?= $row->nomor_perkara ?></strong>
                                            </td>
                                            <td>
                                                <span class="badge badge-info">
                                                    <?= $row->tanggal_putus_formatted ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-secondary">
                                                    <?= $row->jenis_perkara ?>
                                                </span>
                                            </td>
                                            <td><?= $row->panitera_pengganti_nama ?></td>
                                            <td><?= $row->jurusita_pengganti_nama ?></td>
                                            <td>
                                                <?php if (!empty($row->pbt_formatted)): ?>
                                                    <span class="badge badge-success">
                                                        <?= $row->pbt_formatted ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if (!empty($row->bht_formatted)): ?>
                                                    <span class="badge badge-primary">
                                                        <?= $row->bht_formatted ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-muted">Belum</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if (!empty($row->ikrar_formatted)): ?>
                                                    <span class="badge badge-warning">
                                                        <?= $row->ikrar_formatted ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php
                                                $status_class = '';
                                                switch ($row->status_bht) {
                                                    case 'Selesai':
                                                        $status_class = 'badge-success';
                                                        break;
                                                    case 'Proses':
                                                        $status_class = 'badge-warning';
                                                        break;
                                                    case 'Terlambat':
                                                        $status_class = 'badge-danger';
                                                        break;
                                                    default:
                                                        $status_class = 'badge-secondary';
                                                }
                                                ?>
                                                <span class="badge <?= $status_class ?>">
                                                    <?= $row->status_bht ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php
                                                $kategori_class = '';
                                                switch ($row->kategori_bht) {
                                                    case 'Tepat Waktu':
                                                        $kategori_class = 'badge-success';
                                                        break;
                                                    case 'Terlambat':
                                                        $kategori_class = 'badge-danger';
                                                        break;
                                                    default:
                                                        $kategori_class = 'badge-info';
                                                }
                                                ?>
                                                <span class="badge <?= $kategori_class ?>">
                                                    <?= $row->kategori_bht ?>
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <?php if (!empty($row->selisih_hari_bht)): ?>
                                                    <?php
                                                    $selisih_class = '';
                                                    if ($row->selisih_hari_bht > 0) {
                                                        $selisih_class = 'text-danger';
                                                    } elseif ($row->selisih_hari_bht < 0) {
                                                        $selisih_class = 'text-success';
                                                    } else {
                                                        $selisih_class = 'text-info';
                                                    }
                                                    ?>
                                                    <span class="<?= $selisih_class ?>">
                                                        <strong><?= $row->selisih_hari_bht ?></strong> hari
                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="12" class="text-center">
                                            <div class="alert alert-info">
                                                <i class="fas fa-info-circle"></i>
                                                Tidak ada data yang ditemukan untuk kriteria pencarian ini.
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

<!-- Loading Modal -->
<div class="modal fade" id="loadingModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <p class="mt-2">Memproses data...</p>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Initialize DataTable with better options
        var table = $('#bhtTable').DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "searching": true,
            "ordering": false, // Disable DataTable ordering since we use server-side sorting
            "info": true,
            "paging": true,
            "pageLength": 25,
            "lengthMenu": [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "Semua"]
            ],
            "language": {
                "search": "Cari dalam tabel:",
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                "infoFiltered": "(difilter dari _MAX_ total data)",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Selanjutnya",
                    "previous": "Sebelumnya"
                },
                "emptyTable": "Tidak ada data dalam tabel"
            },
            "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                '<"row"<"col-sm-12"tr>>' +
                '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>'
        });

        // Reset button functionality
        $('#resetBtn').on('click', function() {
            $('#filterForm')[0].reset();

            // Set default values
            $('#jenis_perkara').val('Pdt.G');
            $('#lap_bulan').val('<?= date('m') ?>');
            $('#lap_tahun').val('<?= date('Y') ?>');
            $('#order_by').val('tanggal_putus');
            $('#order_dir').val('DESC');

            // Submit form
            $('#filterForm').submit();
        });

        // Export button functionality
        $('#exportBtn').on('click', function() {
            var params = new URLSearchParams();

            // Get all form values
            params.append('jenis_perkara', $('#jenis_perkara').val());
            params.append('lap_bulan', $('#lap_bulan').val());
            params.append('lap_tahun', $('#lap_tahun').val());
            params.append('tanggal_awal', $('#tanggal_awal').val());
            params.append('tanggal_akhir', $('#tanggal_akhir').val());
            params.append('nomor_perkara', $('#nomor_perkara').val());
            params.append('order_by', $('#order_by').val());
            params.append('order_dir', $('#order_dir').val());

            // Open export URL
            var exportUrl = '<?= base_url('bht_putus_4/export_excel') ?>?' + params.toString();

            $('#loadingModal').modal('show');

            // Create temporary iframe for download
            var iframe = document.createElement('iframe');
            iframe.style.display = 'none';
            iframe.src = exportUrl;
            document.body.appendChild(iframe);

            // Hide loading modal after a delay
            setTimeout(function() {
                $('#loadingModal').modal('hide');
                document.body.removeChild(iframe);
            }, 3000);
        });

        // Auto-submit when sorting options change
        $('#order_by, #order_dir').on('change', function() {
            $('#loadingModal').modal('show');
            setTimeout(function() {
                $('#filterForm').submit();
            }, 500);
        });

        // Toggle date range vs month/year selection
        $('#tanggal_awal, #tanggal_akhir').on('change', function() {
            var tanggal_awal = $('#tanggal_awal').val();
            var tanggal_akhir = $('#tanggal_akhir').val();

            if (tanggal_awal && tanggal_akhir) {
                // Disable month/year selection when using date range
                $('#lap_bulan, #lap_tahun').prop('disabled', true).addClass('bg-light');
            } else {
                // Enable month/year selection when not using date range
                $('#lap_bulan, #lap_tahun').prop('disabled', false).removeClass('bg-light');
            }
        });

        // Initialize date range toggle on page load
        $('#tanggal_awal').trigger('change');

        // Add tooltips for better UX
        $('[data-toggle="tooltip"]').tooltip();

        // Quick sort buttons (additional feature)
        function addQuickSortButtons() {
            var quickSortHtml = `
            <div class="btn-group btn-group-sm mb-3" role="group" aria-label="Quick Sort">
                <button type="button" class="btn btn-outline-primary quick-sort" data-sort="tanggal_putus" data-dir="DESC">
                    <i class="fas fa-calendar-alt"></i> Terbaru
                </button>
                <button type="button" class="btn btn-outline-primary quick-sort" data-sort="tanggal_putus" data-dir="ASC">
                    <i class="fas fa-calendar-alt"></i> Terlama
                </button>
                <button type="button" class="btn btn-outline-primary quick-sort" data-sort="status_bht" data-dir="ASC">
                    <i class="fas fa-exclamation-triangle"></i> Status
                </button>
                <button type="button" class="btn btn-outline-primary quick-sort" data-sort="nomor_perkara" data-dir="ASC">
                    <i class="fas fa-sort-numeric-up"></i> Nomor
                </button>
            </div>
        `;

            $('.table-responsive').before(quickSortHtml);

            // Quick sort functionality
            $('.quick-sort').on('click', function() {
                $('#order_by').val($(this).data('sort'));
                $('#order_dir').val($(this).data('dir'));

                // Visual feedback
                $('.quick-sort').removeClass('btn-primary').addClass('btn-outline-primary');
                $(this).removeClass('btn-outline-primary').addClass('btn-primary');

                $('#loadingModal').modal('show');
                setTimeout(function() {
                    $('#filterForm').submit();
                }, 500);
            });

            // Highlight active quick sort button
            var currentSort = $('#order_by').val();
            var currentDir = $('#order_dir').val();
            $('.quick-sort').each(function() {
                if ($(this).data('sort') === currentSort && $(this).data('dir') === currentDir) {
                    $(this).removeClass('btn-outline-primary').addClass('btn-primary');
                }
            });
        }

        // Add quick sort buttons
        addQuickSortButtons();

        // Show success message if any
        <?php if ($this->session->flashdata('success')): ?>
            toastr.success('<?= $this->session->flashdata('success') ?>');
        <?php endif; ?>

        <?php if ($this->session->flashdata('error')): ?>
            toastr.error('<?= $this->session->flashdata('error') ?>');
        <?php endif; ?>
    });
</script>

<style>
    .small-box .inner h3 {
        font-size: 2.2rem;
        font-weight: bold;
    }

    .badge {
        font-size: 0.75rem;
    }

    .table th {
        background-color: #343a40;
        color: white;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, .075);
    }

    .card-header {
        background: linear-gradient(90deg, #007bff 0%, #0056b3 100%);
        color: white;
    }

    .quick-sort {
        margin-right: 5px;
    }

    .bg-light {
        background-color: #f8f9fa !important;
    }

    /* Loading animation */
    .spinner-border {
        width: 3rem;
        height: 3rem;
    }

    /* Responsive improvements */
    @media (max-width: 768px) {
        .small-box .inner h3 {
            font-size: 1.8rem;
        }

        .btn-group-sm>.btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.775rem;
        }
    }

    /* Print styles */
    @media print {

        .btn,
        .card-tools,
        .quick-sort {
            display: none !important;
        }

        .table {
            font-size: 0.8rem;
        }
    }
</style>
