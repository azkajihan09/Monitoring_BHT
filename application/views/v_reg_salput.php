<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <div class="content-wrapper">
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-8">
              <h1 class="m-0 text-dark"><i class="fas fa-file-signature mr-2"></i> Register Petikan Salinan Putusan ke KUA</h1>
            </div>
            <div class="col-sm-4">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Register</li>
              </ol>
            </div>
          </div>
        </div>
      </section>

      <section class="content">
        <div class="container-fluid">
          <div class="card card-primary card-outline">
            <div class="card-header">
              <h3 class="card-title">Filter Data</h3>
            </div>
            <div class="card-body">
              <form action="<?php echo base_url() ?>index.php/Reg_salput" method="POST" class="form-horizontal">
                <div class="form-group row">
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
                </div>
                <div class="form-group row">
                  <div class="col-sm-4 offset-sm-8">
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
                  <i class="fas fa-file-alt mr-1"></i>
                  Data Register Petikan Salinan Putusan
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
                <div class="table-responsive">
                  <table id="example1" class="table table-bordered table-striped table-hover">
                    <thead class="bg-info">
                      <tr>
                        <th width="3%" class="text-center">No</th>
                        <th width="13%">Nomor Perkara</th>
                        <th width="10%">Tanggal Putusan</th>
                        <th width="16%">Penggugat/Pemohon</th>
                        <th width="16%">Tergugat/Termohon</th>
                        <th width="10%">Nomor Akta Nikah</th>
                        <th width="10%">Tanggal Akta Nikah</th>
                        <th width="10%">Tanggal Ikrar Talak</th>
                        <th width="12%">Tanggal Perceraian</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (isset($datafilter)) {
                        $no = 1;
                        foreach ($datafilter as $row): ?>
                          <tr>
                            <td class="text-center"><?php echo $no++; ?></td>
                            <td><?php echo $row->nomor_perkara; ?></td>
                            <td><?php echo date('d-m-Y', strtotime($row->tanggal_putusan)); ?></td>
                            <td>
                              <strong><?php echo $row->pihak1_text; ?></strong>
                              <p class="text-muted small"><?php echo $row->alamat_p; ?></p>
                            </td>
                            <td>
                              <strong><?php echo $row->pihak2_text; ?></strong>
                              <p class="text-muted small"><?php echo $row->alamat_t; ?></p>
                            </td>
                            <td><?php echo $row->no_kutipan_akta_nikah; ?></td>
                            <td><?php echo !empty($row->tgl_kutipan_akta_nikah) ? date('d-m-Y', strtotime($row->tgl_kutipan_akta_nikah)) : '-'; ?></td>
                            <td><?php echo !empty($row->tgl_ikrar_talak) ? date('d-m-Y', strtotime($row->tgl_ikrar_talak)) : '-'; ?></td>
                            <td><?php echo !empty($row->tgl_akta_cerai) ? date('d-m-Y', strtotime($row->tgl_akta_cerai)) : '-'; ?></td>
                          </tr>
                      <?php endforeach;
                      } ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          <?php } ?>
        </div>
      </section>
    </div>
  </div>
</body>

</html>
