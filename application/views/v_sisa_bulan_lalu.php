<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0 text-dark"><i class="fas fa-calendar-minus mr-2"></i> Sisa Perkara Bulan Lalu</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Sisa Perkara</li>
              </ol>
            </div>
          </div>
        </div>
      </section>

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <div class="card card-primary card-outline">
            <div class="card-header">
              <h3 class="card-title">Filter Data</h3>
            </div>
            <div class="card-body">
              <form action="<?php echo base_url() ?>index.php/sisa_bulan_lalu" method="POST" class="form-horizontal">
                <div class="form-group row">
                  <label class="col-sm-2 col-form-label">Jenis Perkara:</label>
                  <div class="col-sm-4">
                    <select name="jenis_perkara" class="form-control" required="">
                      <option value="Pdt.G" <?php echo (isset($_POST['jenis_perkara']) && $_POST['jenis_perkara'] === 'Pdt.G') ? 'selected' : ''; ?>>Pdt.G</option>
                      <option value="Pdt.P" <?php echo (isset($_POST['jenis_perkara']) && $_POST['jenis_perkara'] === 'Pdt.P') ? 'selected' : ''; ?>>Pdt.P</option>
                    </select>
                  </div>
                  <label class="col-sm-2 col-form-label">Laporan Bulan:</label>
                  <div class="col-sm-4">
                    <select name="lap_bulan" class="form-control" required="">
                      <option value="01" <?php echo (isset($_POST['lap_bulan']) && $_POST['lap_bulan'] === '01') ? 'selected' : ''; ?>>Januari</option>
                      <option value="02" <?php echo (isset($_POST['lap_bulan']) && $_POST['lap_bulan'] === '02') ? 'selected' : ''; ?>>Februari</option>
                      <option value="03" <?php echo (isset($_POST['lap_bulan']) && $_POST['lap_bulan'] === '03') ? 'selected' : ''; ?>>Maret</option>
                      <option value="04" <?php echo (isset($_POST['lap_bulan']) && $_POST['lap_bulan'] === '04') ? 'selected' : ''; ?>>April</option>
                      <option value="05" <?php echo (isset($_POST['lap_bulan']) && $_POST['lap_bulan'] === '05') ? 'selected' : ''; ?>>Mei</option>
                      <option value="06" <?php echo (isset($_POST['lap_bulan']) && $_POST['lap_bulan'] === '06') ? 'selected' : ''; ?>>Juni</option>
                      <option value="07" <?php echo (isset($_POST['lap_bulan']) && $_POST['lap_bulan'] === '07') ? 'selected' : ''; ?>>Juli</option>
                      <option value="08" <?php echo (isset($_POST['lap_bulan']) && $_POST['lap_bulan'] === '08') ? 'selected' : ''; ?>>Agustus</option>
                      <option value="09" <?php echo (isset($_POST['lap_bulan']) && $_POST['lap_bulan'] === '09') ? 'selected' : ''; ?>>September</option>
                      <option value="10" <?php echo (isset($_POST['lap_bulan']) && $_POST['lap_bulan'] === '10') ? 'selected' : ''; ?>>Oktober</option>
                      <option value="11" <?php echo (isset($_POST['lap_bulan']) && $_POST['lap_bulan'] === '11') ? 'selected' : ''; ?>>November</option>
                      <option value="12" <?php echo (isset($_POST['lap_bulan']) && $_POST['lap_bulan'] === '12') ? 'selected' : ''; ?>>Desember</option>
                    </select>
                  </div>
                </div>
                <div class="form-group row">
                  <label class="col-sm-2 col-form-label">Tahun:</label>
                  <div class="col-sm-4">
                    <select name="lap_tahun" class="form-control" required="">
                      <?php
                      $currentYear = date('Y');
                      for ($year = 2016; $year <= $currentYear + 1; $year++) {
                      ?>
                        <option value="<?php echo $year; ?>" <?php echo (isset($_POST['lap_tahun']) && $_POST['lap_tahun'] == $year) ? 'selected' : ($year == $currentYear && !isset($_POST['lap_tahun']) ? 'selected' : ''); ?>><?php echo $year; ?></option>
                      <?php } ?>
                    </select>
                  </div>
                  <div class="col-sm-4 offset-sm-2">
                    <button type="submit" name="btn" class="btn btn-primary btn-block"><i class="fas fa-search mr-2"></i>Tampilkan</button>
                  </div>
                </div>
              </form>
            </div>
          </div>

          <?php if (isset($_POST['btn'])) { ?>
            <div class="card">
              <div class="card-header bg-success">
                <h3 class="card-title">
                  <i class="fas fa-table mr-1"></i>
                  Data Sisa Perkara Bulan Lalu
                </h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped table-hover">
                  <thead class="bg-info">
                    <tr>
                      <th width="5%" class="text-center">No</th>
                      <th>Majelis Hakim</th>
                      <th>Jumlah Sisa Perkara</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if (isset($datafilter)) {
                      $no = 1;
                      $total = 0;
                      foreach ($datafilter as $row): $total += $row->sisa_bulan_lalu; ?>
                        <tr>
                          <td class="text-center"><?php echo $no++; ?></td>
                          <td><?php echo $row->majelis_hakim_nama; ?></td>
                          <td class="text-center"><?php echo $row->sisa_bulan_lalu; ?></td>
                        </tr>
                      <?php endforeach; ?>
                      <tr class="bg-light font-weight-bold">
                        <td colspan="2" class="text-right">Total</td>
                        <td class="text-center"><?php echo $total; ?></td>
                      </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
          <?php } ?>
        </div>
      </section>
    </div>
  </div>
  <!-- ./wrapper -->




  <!-- Page specific script -->
  <!-- <script>
  $(function () {
    $("#DataTable").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('#DataTable').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });
</script> 
 -->
</body>

</html>
