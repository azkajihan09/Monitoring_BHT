<body class="hold-transition sidebar-mini">
	<div class="wrapper">
		<div class="content-wrapper">
			<section class="content-header">
				<div class="container-fluid">
					<div class="row mb-2">
						<div class="col-sm-6">
							<h1 class="m-0 text-dark"><i class="fas fa-user-tie mr-2"></i> Laporan Perkara PNS</h1>
						</div>
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								<li class="breadcrumb-item"><a href="<?= site_url('Admin/Dashboard') ?>">Home</a></li>
								<li class="breadcrumb-item">Perkara</li>
								<li class="breadcrumb-item active">Perkara PNS</li>
							</ol>
						</div>
					</div>
				</div>
			</section>

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
							<form action="<?php echo base_url() ?>index.php/PNS" method="POST" class="form-horizontal">
								<div class="form-group row">
									<label class="col-sm-2 col-form-label">Jenis Perkara:</label>
									<div class="col-sm-4">
										<select name="jenis_perkara" class="form-control select2" required>
											<option value="Pdt.G" <?php echo (isset($jenis_perkara) && $jenis_perkara === 'Pdt.G') ? 'selected' : ''; ?>>Perkara Gugatan (Pdt.G)</option>
											<option value="Pdt.P" <?php echo (isset($jenis_perkara) && $jenis_perkara === 'Pdt.P') ? 'selected' : ''; ?>>Perkara Permohonan (Pdt.P)</option>
										</select>
									</div>
								</div>

								<div class="form-group row">
									<label class="col-sm-2 col-form-label">Periode Laporan:</label>
									<div class="col-sm-3">
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
											</div>
											<select name="lap_bulan" class="form-control select2" required>
												<option value="">-- Pilih Bulan --</option>
												<?php
												$currentMonth = date('m');
												foreach ($months as $value => $label) {
													$selected = (isset($lap_bulan) && $lap_bulan == $value) ? 'selected="selected"' : '';
													echo "<option value=\"$value\" $selected>$label</option>";
												}
												?>
											</select>
										</div>
									</div>
									<div class="col-sm-3">
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text"><i class="far fa-calendar-check"></i></span>
											</div>
											<select name="lap_tahun" class="form-control select2" required>
												<option value="">-- Pilih Tahun --</option>
												<?php
												$currentYear = date('Y');
												for ($year = 2016; $year <= $currentYear + 5; $year++) {
													$selected = (isset($lap_tahun) && $lap_tahun == $year) ? 'selected="selected"' : '';
													echo "<option value=\"$year\" $selected>$year</option>";
												}
												?>
											</select>
										</div>
									</div>
									<div class="col-sm-4">
										<button type="submit" name="btn" value="Tampilkan" class="btn btn-primary">
											<i class="fas fa-search mr-1"></i> Tampilkan Data
										</button>
									</div>
								</div>
							</form>
						</div>
					</div>

					<?php if (isset($lap_bulan) && isset($lap_tahun)): ?>
						<div class="alert alert-info">
							<i class="fas fa-info-circle mr-2"></i>
							<strong>Info Pencarian:</strong>
							Menampilkan data perkara PNS jenis <strong><?= $jenis_perkara ?></strong> pada periode <strong><?= $months[$lap_bulan] ?> <?= $lap_tahun ?></strong>
						</div>
					<?php endif; ?>

					<?php if (!empty($datafilter)): ?>
						<!-- Statistics Cards -->
						<div class="row">
							<div class="col-lg-3 col-6">
								<div class="small-box bg-info">
									<div class="inner">
										<h3><?= count($datafilter) ?></h3>
										<p>Total Perkara PNS</p>
									</div>
									<div class="icon">
										<i class="fas fa-user-tie"></i>
									</div>
									<a href="#" class="small-box-footer">
										Periode: <?= $months[$lap_bulan] ?> <?= $lap_tahun ?>
										<i class="fas fa-info-circle mx-1"></i>
									</a>
								</div>
							</div>

							<div class="col-lg-3 col-6">
								<div class="small-box bg-success">
									<div class="inner">
										<h3><?= isset($stats->completed_cases) ? $stats->completed_cases : '0' ?></h3>
										<p>Perkara Selesai</p>
									</div>
									<div class="icon">
										<i class="fas fa-check-circle"></i>
									</div>
									<a href="#" class="small-box-footer">
										<?= isset($stats->completed_cases) && count($datafilter) > 0 ? round(($stats->completed_cases / count($datafilter)) * 100) . '% dari total' : '' ?>
										<i class="fas fa-info-circle mx-1"></i>
									</a>
								</div>
							</div>

							<div class="col-lg-3 col-6">
								<div class="small-box bg-warning">
									<div class="inner">
										<h3><?= isset($stats->ongoing_cases) ? $stats->ongoing_cases : '0' ?></h3>
										<p>Perkara Dalam Proses</p>
									</div>
									<div class="icon">
										<i class="fas fa-spinner"></i>
									</div>
									<a href="#" class="small-box-footer">
										<?= isset($stats->ongoing_cases) && count($datafilter) > 0 ? round(($stats->ongoing_cases / count($datafilter)) * 100) . '% dari total' : '' ?>
										<i class="fas fa-info-circle mx-1"></i>
									</a>
								</div>
							</div>

							<div class="col-lg-3 col-6">
								<div class="small-box bg-danger">
									<div class="inner">
										<h3><?= isset($stats->avg_duration) ? round($stats->avg_duration) : '0' ?></h3>
										<p>Rata-rata Durasi (hari)</p>
									</div>
									<div class="icon">
										<i class="fas fa-clock"></i>
									</div>
									<a href="#" class="small-box-footer">
										Min: <?= isset($stats->min_duration) ? $stats->min_duration : '0' ?> |
										Max: <?= isset($stats->max_duration) ? $stats->max_duration : '0' ?>
										<i class="fas fa-info-circle mx-1"></i>
									</a>
								</div>
							</div>
						</div>

						<!-- Export Buttons Row -->
						<div class="row mb-4">
							<div class="col-md-12">
								<div class="bg-light p-3" style="border-radius: 5px; border: 1px solid #ddd;">
									<h5><i class="fas fa-file-export mr-2"></i> Export Data Perkara PNS</h5>
									<div class="mt-3">
										<a href="<?= site_url('PNS/export_excel?jenis_perkara=' . $jenis_perkara . '&lap_bulan=' . $lap_bulan . '&lap_tahun=' . $lap_tahun) ?>" class="btn btn-success btn-lg">
											<i class="fas fa-file-excel mr-2"></i> Export ke Excel
										</a>
										<button type="button" class="btn btn-danger btn-lg ml-2 export-pdf">
											<i class="fas fa-file-pdf mr-2"></i> Export ke PDF
										</button>
										<button type="button" class="btn btn-primary btn-lg ml-2 print-data">
											<i class="fas fa-print mr-2"></i> Cetak
										</button>
										<span class="text-muted ml-3">
											<i class="fas fa-info-circle mr-1"></i> Klik tombol untuk mengunduh data dalam format yang diinginkan
										</span>
									</div>
								</div>
							</div>
						</div>

						<!-- Main Data Table -->
						<div class="card card-outline card-primary">
							<div class="card-header">
								<h3 class="card-title">
									<i class="fas fa-table mr-1"></i>
									Data Perkara PNS <?= $jenis_perkara ?> - <?= $months[$lap_bulan] ?> <?= $lap_tahun ?>
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
								<table id="dataTable" class="table table-bordered table-striped table-hover">
									<thead>
										<tr>
											<th class="text-center" width="3%">No</th>
											<th width="12%">Nomor Perkara</th>
											<th width="15%">Nama Pihak</th>
											<th width="12%">Pekerjaan</th>
											<th width="8%">Tgl Pendaftaran</th>
											<th width="8%">Tgl PMH</th>
											<th width="8%">Tgl PHS</th>
											<th width="8%">Sidang I</th>
											<th width="8%">Tgl Putusan</th>
											<th width="8%">Durasi</th>
											<th width="10%">Status Putusan</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$no = 1;
										foreach ($datafilter as $row):
											// Determine row class based on status
											$rowClass = '';
											if (empty($row->tanggal_putusan)) {
												$rowClass = 'table-warning'; // Ongoing case
											} elseif (strpos(strtolower($row->status_putusan_nama), 'cabut') !== false) {
												$rowClass = 'table-secondary'; // Withdrawn case
											} elseif (strpos(strtolower($row->status_putusan_nama), 'tolak') !== false) {
												$rowClass = 'table-danger'; // Rejected case
											} elseif (
												strpos(strtolower($row->status_putusan_nama), 'terima') !== false ||
												strpos(strtolower($row->status_putusan_nama), 'kabulkan') !== false
											) {
												$rowClass = 'table-success'; // Accepted case
											}
										?>
											<tr class="<?= $rowClass ?>">
												<td class="text-center"><?= $no++ ?></td>
												<td><?= $row->nomor_perkara ?></td>
												<td>
													<strong><?= $row->nama_pihak ?></strong>
													<?php if (!empty($row->tanggal_lahir)): ?>
														<div class="small text-muted">
															<i class="far fa-calendar-alt"></i> <?= date('d-m-Y', strtotime($row->tanggal_lahir)) ?>
														</div>
													<?php endif; ?>
												</td>
												<td>
													<span class="badge badge-info"><?= $row->pekerjaan ?></span>
												</td>
												<td><?= date('d-m-Y', strtotime($row->tanggal_pendaftaran)) ?></td>
												<td><?= !empty($row->penetapan_majelis_hakim) ? date('d-m-Y', strtotime($row->penetapan_majelis_hakim)) : '-' ?></td>
												<td><?= !empty($row->penetapan_hari_sidang) ? date('d-m-Y', strtotime($row->penetapan_hari_sidang)) : '-' ?></td>
												<td><?= !empty($row->sidang_pertama) ? date('d-m-Y', strtotime($row->sidang_pertama)) : '-' ?></td>
												<td>
													<?php if (!empty($row->tanggal_putusan)): ?>
														<?= date('d-m-Y', strtotime($row->tanggal_putusan)) ?>
													<?php else: ?>
														<span class="badge badge-warning">Dalam Proses</span>
													<?php endif; ?>
												</td>
												<td class="text-center">
													<span class="badge <?= $row->durasi_perkara > 90 ? 'badge-danger' : 'badge-info' ?>">
														<?= $row->durasi_perkara ?> hari
													</span>
												</td>
												<td>
													<?php if (!empty($row->status_putusan_nama)): ?>
														<span class="badge badge-secondary"><?= $row->status_putusan_nama ?></span>
													<?php else: ?>
														<span class="badge badge-warning">Dalam Proses</span>
													<?php endif; ?>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>
							<div class="card-footer">
								<div class="text-right">
									<small class="text-muted">
										Total data: <?= count($datafilter) ?> |
										Diperbarui: <?= date('d-m-Y H:i:s') ?>
									</small>
								</div>
							</div>
						</div>
					<?php elseif (isset($lap_bulan) && isset($lap_tahun)): ?>
						<!-- No Data Alert -->
						<div class="alert alert-warning">
							<h5><i class="icon fas fa-exclamation-triangle"></i> Tidak Ada Data</h5>
							<p>Tidak ditemukan data perkara PNS untuk jenis <strong><?= $jenis_perkara ?></strong> pada periode <strong><?= $months[$lap_bulan] ?> <?= $lap_tahun ?></strong>.</p>
							<p>Silakan coba periode lain atau jenis perkara yang berbeda.</p>
						</div>
					<?php else: ?>
						<!-- Welcome Message -->
						<div class="jumbotron bg-light">
							<h1 class="display-5"><i class="fas fa-user-tie mr-2"></i> Laporan Perkara PNS</h1>
							<p class="lead">Silakan tentukan parameter pencarian untuk menampilkan data perkara yang melibatkan Pegawai Negeri Sipil.</p>
							<hr class="my-4">
							<p>Laporan ini menampilkan informasi perkara yang salah satu pihaknya bekerja sebagai PNS atau Pegawai Negeri Sipil.</p>
						</div>
					<?php endif; ?>
				</div>
			</section>
		</div>
	</div>

	<script>
		$(document).ready(function() {
			// Initialize Select2
			$('.select2').select2({
				theme: 'bootstrap4'
			});

			// Initialize DataTables
			var dataTable = $("#dataTable").DataTable({
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
						title: 'Data Perkara PNS - <?= isset($jenis_perkara) ? $jenis_perkara : "" ?> <?= isset($lap_bulan) ? $months[$lap_bulan] : "" ?> <?= isset($lap_tahun) ? $lap_tahun : "" ?>',
						exportOptions: {
							columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
						},
						className: 'btn-success'
					},
					{
						extend: 'pdf',
						text: 'PDF',
						title: 'Data Perkara PNS - <?= isset($jenis_perkara) ? $jenis_perkara : "" ?> <?= isset($lap_bulan) ? $months[$lap_bulan] : "" ?> <?= isset($lap_tahun) ? $lap_tahun : "" ?>',
						exportOptions: {
							columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
						},
						orientation: 'landscape',
						className: 'btn-danger'
					},
					{
						extend: 'print',
						text: 'Print',
						title: 'Data Perkara PNS - <?= isset($jenis_perkara) ? $jenis_perkara : "" ?> <?= isset($lap_bulan) ? $months[$lap_bulan] : "" ?> <?= isset($lap_tahun) ? $lap_tahun : "" ?>',
						exportOptions: {
							columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
						},
						className: 'btn-primary'
					}
				]
			}).buttons().container().appendTo('#dataTable_wrapper .col-md-6:eq(0)');

			// Export buttons event handlers
			$('.export-pdf').click(function() {
				dataTable.button('.buttons-pdf').trigger();
			});

			$('.print-data').click(function() {
				dataTable.button('.buttons-print').trigger();
			});
		});
	</script>
</body>

</html>