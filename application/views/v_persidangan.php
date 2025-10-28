<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <div class="content-wrapper">
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0 text-dark"><i class="fas fa-gavel mr-2"></i> Catatan Persidangan Jurusita</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Persidangan</li>
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
              <form action="<?php echo base_url() ?>index.php/Persidangan" method="POST" class="form-horizontal">
                <?php $tanggal_sidang = isset($_POST['tanggal_sidang']) ? $_POST['tanggal_sidang'] : date('Y-m-d'); ?>

                <div class="form-group row">
                  <label class="col-sm-2 col-form-label">Jurusita:</label>
                  <div class="col-sm-4">
                    <select name="jurusita_nama" class="form-control" required="">
                      <option value="Khairullah" <?php echo (isset($_POST['jurusita_nama']) && $_POST['jurusita_nama'] === 'Khairullah') ? 'selected' : ''; ?>>Khairullah</option>
                      <option value="Rahmadi" <?php echo (isset($_POST['jurusita_nama']) && $_POST['jurusita_nama'] === 'Rahmadi') ? 'selected' : ''; ?>>Rahmadi, S.AP</option>
                      <option value="Lupi Ananda" <?php echo (isset($_POST['jurusita_nama']) && $_POST['jurusita_nama'] === 'Lupi Ananda') ? 'selected' : ''; ?>>Lupi Ananda, S.Kom</option>
                    </select>
                  </div>
                  <label class="col-sm-2 col-form-label">Tanggal Sidang:</label>
                  <div class="col-sm-4">
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                      </div>
                      <input type="date" name="tanggal_sidang" class="form-control" required value="<?php echo $tanggal_sidang; ?>">
                    </div>
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
                  <i class="fas fa-list-alt mr-1"></i>
                  Data Persidangan
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
                        <th width="15%">Nomor Perkara</th>
                        <th width="18%">Penggugat/Pemohon</th>
                        <th width="18%">Tergugat/Termohon</th>
                        <th width="15%">Panitera Pengganti</th>
                        <th width="5%" class="text-center">Sidang Ke-</th>
                        <th width="14%">Dihadiri oleh</th>
                        <th width="12%">Tanggal Putus</th>
                        <th width="5%" class="text-center">Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (isset($datafilter)) {
                        $no = 1;
                        foreach ($datafilter as $row): ?>
                          <tr>
                            <td class="text-center"><?php echo $no++; ?></td>
                            <td><?php echo $row->nomor_perkara; ?></td>
                            <td><?php echo $row->nama_p; ?></td>
                            <td><?php echo $row->nama_t; ?></td>
                            <td><?php echo $row->panitera_nama; ?></td>
                            <td class="text-center"><?php echo $row->sidang_ke; ?></td>
                            <td>
                              <?php
                              $hadirStatus = '';
                              $badgeClass = '';

                              if ($row->dihadiri_oleh == 1) {
                                $hadirStatus = 'Semua pihak';
                                $badgeClass = 'badge-success';
                              } else if ($row->dihadiri_oleh == 2) {
                                $hadirStatus = 'Penggugat saja';
                                $badgeClass = 'badge-warning';
                              } else if ($row->dihadiri_oleh == 3) {
                                $hadirStatus = 'Tergugat saja';
                                $badgeClass = 'badge-warning';
                              } else if ($row->dihadiri_oleh == 4) {
                                $hadirStatus = 'Para pihak tidak hadir';
                                $badgeClass = 'badge-danger';
                              } else {
                                $hadirStatus = 'Belum tercatat';
                                $badgeClass = 'badge-secondary';
                              }
                              ?>
                              <span class="badge <?php echo $badgeClass; ?>"><?php echo $hadirStatus; ?></span>
                            </td>
                            <td><?php echo !empty($row->tanggal_putusan) ? date('d-m-Y', strtotime($row->tanggal_putusan)) : '-'; ?></td>
                            <td class="text-center">
                              <a href="<?php echo base_url('index.php/Persidangan/detail/') . $row->idperkara; ?>" class="btn btn-info btn-sm" data-toggle="tooltip" title="Detail">
                                <i class="fa fa-search-plus"></i>
                              </a>
                            </td>
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
