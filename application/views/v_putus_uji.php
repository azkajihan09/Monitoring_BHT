<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Laporan Perkara Putus per Majelis | SIPP</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url()?>assets/plugins/fontawesome-free/css/all.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="<?php echo base_url()?>assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="<?php echo base_url()?>assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="<?php echo base_url()?>assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <!-- Select2 -->
  <link rel="stylesheet" href="<?php echo base_url()?>assets/plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="<?php echo base_url()?>assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url()?>assets/dist/css/adminlte.min.css">
  
  <!-- Custom CSS -->
  <style>
    .small-box {
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      transition: transform 0.2s;
    }
    .small-box:hover {
      transform: translateY(-2px);
    }
    .card {
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      transition: box-shadow 0.3s;
    }
    .card:hover {
      box-shadow: 0 0 20px rgba(0,0,0,0.15);
    }
    .table th {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      border: none;
      font-weight: 600;
    }
    .progress {
      border-radius: 10px;
      box-shadow: inset 0 1px 2px rgba(0,0,0,0.1);
    }
    .badge-lg {
      font-size: 0.9em;
      padding: 0.5em 0.8em;
      border-radius: 8px;
    }
    .table-hover tbody tr:hover {
      background-color: rgba(0,123,255,0.1);
      transform: scale(1.01);
      transition: all 0.2s;
    }
    .user-panel .image i {
      margin-top: 5px;
    }
    .card-tools .btn {
      border: none;
    }
    .form-control:focus {
      border-color: #80bdff;
      box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
    }
    .btn {
      border-radius: 8px;
      transition: all 0.2s;
    }
    .btn:hover {
      transform: translateY(-1px);
      box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
    .input-group-text {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      border: none;
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
            <h1 class="m-0 text-dark"><i class="fas fa-gavel mr-2"></i> Laporan Perkara Putus per Majelis</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="<?= site_url('Admin/Dashboard') ?>">Home</a></li>
              <li class="breadcrumb-item">Perkara</li>
              <li class="breadcrumb-item active">Perkara Putus</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>  
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Filter Card -->
        <div class="card card-primary card-outline">
          <div class="card-header">
            <h3 class="card-title"><i class="fas fa-filter mr-1"></i> Filter Data</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
            </div>
          </div>
          <div class="card-body">
            <form action="<?php echo base_url()?>index.php/Putus_uji" method="POST" class="form-horizontal">
              <div class="form-group row">
                <label class="col-sm-2 col-form-label">Jenis Perkara:</label>
                <div class="col-sm-4">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-balance-scale"></i></span>
                    </div>
                    <select name="jenis_perkara" class="form-control select2" required>
                      <option value="Pdt.G" <?php echo ($jenis_perkara === 'Pdt.G') ? 'selected' : ''; ?>>Perkara Gugatan (Pdt.G)</option>
                      <option value="Pdt.P" <?php echo ($jenis_perkara === 'Pdt.P') ? 'selected' : ''; ?>>Perkara Permohonan (Pdt.P)</option>
                    </select>
                  </div>
                </div>
              </div>
              
              <div class="form-group row">
                <label class="col-sm-2 col-form-label">Periode:</label>
                <div class="col-sm-10">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                        </div>
                        <select name="lap_bulan" class="form-control select2" required>
                          <option value="">-- Pilih Bulan --</option>
                          <?php
                          $months = [
                            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
                            '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                            '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                          ];
                          $current_month = date('m');
                          foreach ($months as $value => $label) {
                            $selected = ($lap_bulan === $value) ? 'selected' : '';
                            echo "<option value=\"$value\" $selected>$label</option>";
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="far fa-calendar-check"></i></span>
                        </div>
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
              </div>
              
              <div class="form-group row">
                <div class="col-sm-10 offset-sm-2">
                  <button type="submit" name="btn" value="Tampilkan" class="btn btn-primary">
                    <i class="fas fa-search mr-2"></i> Tampilkan Data
                  </button>
                  <?php if (!empty($putus)): ?>
                    <button type="button" class="btn btn-success" onclick="exportToExcel()">
                      <i class="fas fa-file-excel mr-2"></i> Export Excel
                    </button>
                  <?php endif; ?>
                </div>
              </div>
            </form>
          </div>
        </div>
        
        <?php if (!empty($putus)): ?>
          <!-- Statistics Cards -->
          <div class="row">
            <div class="col-lg-3 col-6">
              <div class="small-box bg-success">
                <div class="inner">
                  <h3><?= $statistics['total_perkara'] ?></h3>
                  <p>Total Perkara Putus</p>
                </div>
                <div class="icon">
                  <i class="fas fa-gavel"></i>
                </div>
                <a href="#" class="small-box-footer">
                  Semua data perkara putus
                  <i class="fas fa-info-circle mx-1"></i>
                </a>
              </div>
            </div>
            
            <div class="col-lg-3 col-6">
               <div class="small-box bg-warning">
                 <div class="inner">
                   <h3><?= $statistics['beban_tertinggi'] ?></h3>
                   <p>Beban Tertinggi</p>
                 </div>
                 <div class="icon">
                   <i class="fas fa-chart-line"></i>
                 </div>
                 <a href="#" class="small-box-footer">
                   Dari data majelis hakim
                   <i class="fas fa-user-tie mx-1"></i>
                 </a>
               </div>
             </div>
            
            <div class="col-lg-3 col-6">
               <div class="small-box bg-info">
                 <div class="inner">
                   <h3><?= $statistics['beban_terendah'] ?></h3>
                   <p>Beban Terendah</p>
                 </div>
                 <div class="icon">
                   <i class="fas fa-chart-line"></i>
                 </div>
                 <a href="#" class="small-box-footer">
                   Dari data majelis hakim
                   <i class="fas fa-user-tie mx-1"></i>
                 </a>
               </div>
             </div>
            
            <div class="col-lg-3 col-6">
              <div class="small-box bg-danger">
                <div class="inner">
                  <h3><?= $jenis_perkara ?></h3>
                  <p>Jenis Perkara</p>
                </div>
                <div class="icon">
                  <i class="fas fa-balance-scale"></i>
                </div>
                <a href="#" class="small-box-footer">
                  <?= $months[$lap_bulan] . ' ' . $lap_tahun ?>
                  <i class="fas fa-calendar-alt mx-1"></i>
                </a>
              </div>
            </div>
          </div>
          
          <!-- Chart Section -->
          <div class="row">
            <div class="col-md-8">
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title"><i class="fas fa-chart-bar mr-1"></i> Distribusi Perkara Putus per Majelis</h3>
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-minus"></i>
                    </button>
                  </div>
                </div>
                <div class="card-body">
                  <div style="height: 300px;">
                    <canvas id="caseDistributionChart"></canvas>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card card-info">
                <div class="card-header">
                  <h3 class="card-title"><i class="fas fa-info-circle mr-1"></i> Analisis Beban Kerja</h3>
                  <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                      <i class="fas fa-minus"></i>
                    </button>
                  </div>
                </div>
                <div class="card-body p-0">
                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>Kategori Beban</th>
                        <th class="text-center">Jumlah</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $high_load = 0; $medium_load = 0; $low_load = 0;
                      foreach ($putus as $row) {
                        if ($row->putus > 10) $high_load++;
                        elseif ($row->putus > 5) $medium_load++;
                        else $low_load++;
                      }
                      ?>
                      <tr>
                        <td>Beban Tinggi (>10)</td>
                        <td class="text-center">
                          <span class="badge badge-danger"><?= $high_load ?></span>
                        </td>
                      </tr>
                      <tr>
                        <td>Beban Sedang (5-10)</td>
                        <td class="text-center">
                          <span class="badge badge-warning"><?= $medium_load ?></span>
                        </td>
                      </tr>
                      <tr>
                        <td>Beban Rendah (<5)</td>
                        <td class="text-center">
                          <span class="badge badge-success"><?= $low_load ?></span>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        <?php endif; ?>
        
        <!-- Main Data Table -->
        <div class="row">
          <div class="col-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title"><i class="fas fa-table mr-1"></i> Data Perkara Putus <?= $months[$lap_bulan] . ' ' . $lap_tahun ?></h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-tool" data-card-widget="maximize">
                    <i class="fas fa-expand"></i>
                  </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <?php if (!empty($putus)): ?>
                  <div class="table-responsive">
                    <table id="example1" class="table table-bordered table-striped table-hover">
                      <thead class="thead-dark">
                        <tr>
                          <th class="text-center" style="width: 5%;">No</th>
                          <th><i class="fas fa-user-tie mr-1"></i> Majelis Hakim</th>
                          <th class="text-center" style="width: 15%;"><i class="fas fa-gavel mr-1"></i> Putus</th>
                          <th class="text-center" style="width: 15%;"><i class="fas fa-chart-pie mr-1"></i> Persentase</th>
                          <th class="text-center" style="width: 15%;"><i class="fas fa-signal mr-1"></i> Kategori</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                         $no = 1;
                         // Calculate total putus correctly from object properties
                         $total_putus = 0;
                         if (!empty($putus)) {
                           foreach ($putus as $item) {
                             $total_putus += $item->putus;
                           }
                         }
                         
                         foreach ($putus as $row) {
                           $percentage = $total_putus > 0 ? round(($row->putus / $total_putus) * 100, 1) : 0;
                          
                          // Determine category and badge color
                          if ($row->putus > 10) {
                            $category = 'Tinggi';
                            $badge_class = 'badge-danger';
                            $row_class = 'table-danger';
                          } elseif ($row->putus > 5) {
                            $category = 'Sedang';
                            $badge_class = 'badge-warning';
                            $row_class = 'table-warning';
                          } else {
                            $category = 'Rendah';
                            $badge_class = 'badge-success';
                            $row_class = 'table-success';
                          }
                        ?>
                        <tr class="<?= $row_class ?>">
                          <td class="text-center font-weight-bold"><?php echo $no++; ?></td>
                          <td>
                            <div class="d-flex align-items-center">
                              <div class="user-panel d-flex">
                                <div class="image">
                                  <i class="fas fa-user-circle fa-2x text-primary"></i>
                                </div>
                                <div class="info ml-2">
                                  <span class="font-weight-bold"><?php echo $row->majelis_hakim_nama; ?></span>
                                  <?php if (isset($row->majelis_hakim_kode)): ?>
                                    <br><small class="text-muted">Kode: <?php echo $row->majelis_hakim_kode; ?></small>
                                  <?php endif; ?>
                                </div>
                              </div>
                            </div>
                          </td>
                          <td class="text-center">
                            <span class="badge badge-primary badge-lg"><?php echo $row->putus; ?></span>
                          </td>
                          <td class="text-center">
                            <div class="progress" style="height: 20px;">
                              <div class="progress-bar bg-info" role="progressbar" style="width: <?= $percentage ?>%" aria-valuenow="<?= $percentage ?>" aria-valuemin="0" aria-valuemax="100">
                                <?= $percentage ?>%
                              </div>
                            </div>
                          </td>
                          <td class="text-center">
                            <span class="badge <?= $badge_class ?> badge-lg"><?= $category ?></span>
                          </td>
                        </tr>
                        <?php } ?>
                      </tbody>
                      <tfoot class="thead-light">
                        <tr>
                          <th colspan="2" class="text-right font-weight-bold">Total:</th>
                          <th class="text-center">
                            <span class="badge badge-dark badge-lg"><?= $total_putus ?></span>
                          </th>
                          <th class="text-center">
                            <span class="badge badge-info badge-lg">100%</span>
                          </th>
                          <th class="text-center">
                            <span class="badge badge-secondary badge-lg"><?= count($putus) ?> Majelis</span>
                          </th>
                        </tr>
                      </tfoot>
                    </table>
                  </div>
                <?php else: ?>
                  <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle fa-2x mb-3"></i>
                    <h5>Belum Ada Data</h5>
                    <p>Silakan pilih filter periode dan jenis perkara untuk menampilkan data.</p>
                  </div>
                <?php endif; ?>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="<?php echo base_url()?>assets/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?php echo base_url()?>assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="<?php echo base_url()?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url()?>assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo base_url()?>assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?php echo base_url()?>assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?php echo base_url()?>assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?php echo base_url()?>assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="<?php echo base_url()?>assets/plugins/jszip/jszip.min.js"></script>
<script src="<?php echo base_url()?>assets/plugins/pdfmake/pdfmake.min.js"></script>
<script src="<?php echo base_url()?>assets/plugins/pdfmake/vfs_fonts.js"></script>
<script src="<?php echo base_url()?>assets/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="<?php echo base_url()?>assets/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="<?php echo base_url()?>assets/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- Select2 -->
<script src="<?php echo base_url()?>assets/plugins/select2/js/select2.full.min.js"></script>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url()?>assets/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url()?>assets/dist/js/demo.js"></script>

<!-- Page specific script -->
<script>
$(function () {
  // Initialize Select2
  $('.select2').select2({
    theme: 'bootstrap4'
  });
  
  // Initialize DataTable
  $("#example1").DataTable({
    "responsive": true,
    "lengthChange": true,
    "autoWidth": false,
    "pageLength": 25,
    "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
    "language": {
      "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
    },
    "buttons": [
      {
        extend: 'excel',
        text: '<i class="fas fa-file-excel"></i> Excel',
        className: 'btn btn-success btn-sm',
        title: 'Laporan Perkara Putus <?= $months[$lap_bulan] . " " . $lap_tahun ?>'
      },
      {
        extend: 'pdf',
        text: '<i class="fas fa-file-pdf"></i> PDF',
        className: 'btn btn-danger btn-sm',
        title: 'Laporan Perkara Putus <?= $months[$lap_bulan] . " " . $lap_tahun ?>'
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
  
  <?php if (!empty($putus)): ?>
  // Chart Data
  const chartData = {
    labels: [<?php foreach($putus as $row) echo '"' . addslashes($row->majelis_hakim_nama) . '",'; ?>],
    datasets: [{
      label: 'Jumlah Perkara Putus',
      data: [<?php foreach($putus as $row) echo $row->putus . ','; ?>],
      backgroundColor: [
        'rgba(255, 99, 132, 0.8)',
        'rgba(54, 162, 235, 0.8)',
        'rgba(255, 205, 86, 0.8)',
        'rgba(75, 192, 192, 0.8)',
        'rgba(153, 102, 255, 0.8)',
        'rgba(255, 159, 64, 0.8)',
        'rgba(199, 199, 199, 0.8)',
        'rgba(83, 102, 255, 0.8)',
        'rgba(255, 99, 255, 0.8)',
        'rgba(99, 255, 132, 0.8)'
      ],
      borderColor: [
        'rgba(255, 99, 132, 1)',
        'rgba(54, 162, 235, 1)',
        'rgba(255, 205, 86, 1)',
        'rgba(75, 192, 192, 1)',
        'rgba(153, 102, 255, 1)',
        'rgba(255, 159, 64, 1)',
        'rgba(199, 199, 199, 1)',
        'rgba(83, 102, 255, 1)',
        'rgba(255, 99, 255, 1)',
        'rgba(99, 255, 132, 1)'
      ],
      borderWidth: 2
    }]
  };
  
  // Initialize Chart
  const ctx = document.getElementById('caseDistributionChart').getContext('2d');
  const caseChart = new Chart(ctx, {
    type: 'bar',
    data: chartData,
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          display: false
        },
        title: {
          display: true,
          text: 'Distribusi Perkara Putus per Majelis Hakim'
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            stepSize: 1
          }
        },
        x: {
          ticks: {
            maxRotation: 45,
            minRotation: 45
          }
        }
      }
    }
  });
  <?php endif; ?>
});

// Export to Excel function
function exportToExcel() {
  $("#example1").DataTable().button('.buttons-excel').trigger();
}
</script>
</body>
</html>
