<body class="hold-transition sidebar-mini">
	<div class="wrapper">
		<div class="content-wrapper">
			<section class="content-header">
				<div class="container-fluid">
					<div class="row mb-2">
						<div class="col-sm-6">
							<h1 class="m-0 text-dark"><i class="fas fa-hand-holding-usd mr-2"></i> Laporan Perkara Prodeo</h1>
						</div>
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								<li class="breadcrumb-item"><a href="<?= site_url('Admin/Dashboard') ?>">Home</a></li>
								<li class="breadcrumb-item">Perkara</li>
								<li class="breadcrumb-item active">Perkara Prodeo</li>
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
							<form action="<?php echo base_url() ?>index.php/Prodeo" method="POST" class="form-horizontal">
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

					<!-- Information Alert -->
					<?php if (isset($lap_bulan) && isset($lap_tahun)): ?>
						<div class="alert alert-info">
							<i class="icon fas fa-info-circle"></i>
							<strong>Info Pencarian:</strong>
							Menampilkan data perkara prodeo (cuma-cuma) jenis <strong><?= $jenis_perkara ?></strong> pada periode <strong><?= $months[$lap_bulan] ?> <?= $lap_tahun ?></strong>
						</div>
					<?php endif; ?>

					<?php if (!empty($datafilter)): ?>
						<!-- Biaya Perkara Analysis Card -->
						<div class="card card-danger">
							<div class="card-header">
								<h3 class="card-title">
									<i class="fas fa-money-bill-wave mr-1"></i>
									Analisis Biaya Perkara
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
										<div class="info-box bg-gradient-danger mb-3">
											<span class="info-box-icon"><i class="fas fa-hand-holding-usd"></i></span>
											<div class="info-box-content">
												<span class="info-box-text">Total Biaya yang Dihemat</span>
												<span class="info-box-number">
													Rp. <?= number_format(isset($biaya_detail['total_savings']) ? $biaya_detail['total_savings'] : (count($datafilter) * 850000), 0, ',', '.') ?>
												</span>
												<?php if (isset($biaya_detail['is_estimated']) && $biaya_detail['is_estimated']): ?>
													<span class="progress-description">
														<i class="fas fa-info-circle"></i> Estimasi berdasarkan rata-rata
													</span>
												<?php endif; ?>
											</div>
										</div>

										<div class="table-responsive">
											<table class="table table-bordered">
												<tr>
													<th colspan="2" class="bg-light">Detail Biaya Perkara</th>
												</tr>
												<tr>
													<td width="60%">Biaya rata-rata per perkara</td>
													<td>
														<strong>Rp. <?= number_format(isset($biaya_detail['regular_fees']->avg_biaya) ? $biaya_detail['regular_fees']->avg_biaya : 850000, 0, ',', '.') ?></strong>
													</td>
												</tr>
												<tr>
													<td>Biaya terendah</td>
													<td>
														Rp. <?= number_format(isset($biaya_detail['regular_fees']->min_biaya) ? $biaya_detail['regular_fees']->min_biaya : 600000, 0, ',', '.') ?>
													</td>
												</tr>
												<tr>
													<td>Biaya tertinggi</td>
													<td>
														Rp. <?= number_format(isset($biaya_detail['regular_fees']->max_biaya) ? $biaya_detail['regular_fees']->max_biaya : 1100000, 0, ',', '.') ?>
													</td>
												</tr>
												<tr>
													<td>Jumlah perkara prodeo</td>
													<td>
														<strong><?= count($datafilter) ?> perkara</strong>
													</td>
												</tr>
											</table>
										</div>

										<a href="<?= site_url('Prodeo/export_biaya_detail?jenis_perkara=' . $jenis_perkara . '&lap_bulan=' . $lap_bulan . '&lap_tahun=' . $lap_tahun) ?>" class="btn btn-danger mt-3">
											<i class="fas fa-file-excel mr-1"></i> Export Analisis Biaya
										</a>
									</div>

									<div class="col-md-6">
										<h5>Rincian Komponen Biaya</h5>
										<?php if (!empty($biaya_detail['components'])): ?>
											<div style="height: 250px;">
												<canvas id="feePieChart"></canvas>
											</div>
											<div class="table-responsive mt-3">
												<table class="table table-sm table-striped">
													<thead>
														<tr>
															<th>Jenis Biaya</th>
															<th class="text-right">Rata-rata</th>
														</tr>
													</thead>
													<tbody>
														<?php foreach ($biaya_detail['components'] as $comp): ?>
															<tr>
																<td><?= $comp->nama_komponen ?></td>
																<td class="text-right">Rp. <?= number_format($comp->rata_rata, 0, ',', '.') ?></td>
															</tr>
														<?php endforeach; ?>
													</tbody>
												</table>
											</div>
										<?php else: ?>
											<div class="alert alert-warning">
												<i class="fas fa-info-circle mr-1"></i> Tidak ada data rincian biaya untuk periode ini.
												<?php if (isset($biaya_detail['is_estimated']) && $biaya_detail['is_estimated']): ?>
													Menggunakan estimasi biaya standar.
												<?php endif; ?>
											</div>

											<table class="table table-sm">
												<tr>
													<th colspan="2">Komponen Biaya Standar (Estimasi)</th>
												</tr>
												<tr>
													<td>Biaya Pendaftaran</td>
													<td class="text-right">Rp. 30.000</td>
												</tr>
												<tr>
													<td>Biaya Proses</td>
													<td class="text-right">Rp. 50.000</td>
												</tr>
												<tr>
													<td>Biaya Panggilan</td>
													<td class="text-right">Rp. 600.000</td>
												</tr>
												<tr>
													<td>Biaya Materai</td>
													<td class="text-right">Rp. 20.000</td>
												</tr>
												<tr>
													<td>Redaksi</td>
													<td class="text-right">Rp. 10.000</td>
												</tr>
												<tr>
													<td>PNBP Panggilan</td>
													<td class="text-right">Rp. 40.000</td>
												</tr>
												<tr>
													<td>Biaya Lainnya</td>
													<td class="text-right">Rp. 100.000</td>
												</tr>
												<tr class="bg-light">
													<th>Total (estimasi)</th>
													<th class="text-right">Rp. 850.000</th>
												</tr>
											</table>
										<?php endif; ?>
									</div>
								</div>
							</div>
						</div>

						<!-- Statistics Cards -->
						<div class="row">
							<div class="col-lg-3 col-6">
								<div class="small-box bg-info">
									<div class="inner">
										<h3><?= count($datafilter) ?></h3>
										<p>Total Perkara Prodeo</p>
									</div>
									<div class="icon">
										<i class="fas fa-hand-holding-usd"></i>
									</div>
									<a href="#" class="small-box-footer">
										<?= isset($stats->percent_of_total) ? 'Sekitar ' . round($stats->percent_of_total, 1) . '% dari total perkara' : '' ?>
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

						<!-- Case Type Distribution Stats -->
						<div class="row">
							<div class="col-md-6">
								<div class="card card-primary">
									<div class="card-header">
										<h3 class="card-title"><i class="fas fa-chart-pie mr-1"></i> Distribusi Jenis Perkara</h3>
										<div class="card-tools">
											<button type="button" class="btn btn-tool" data-card-widget="collapse">
												<i class="fas fa-minus"></i>
											</button>
										</div>
									</div>
									<div class="card-body">
										<div class="row">
											<div class="col-md-8">
												<div class="chart-container" style="position: relative; height:240px;">
													<canvas id="caseTypeChart"></canvas>
												</div>
											</div>
											<div class="col-md-4">
												<ul class="list-group">
													<li class="list-group-item d-flex justify-content-between align-items-center">
														Cerai Gugat
														<span class="badge badge-primary badge-pill">
															<?= isset($stats->cerai_gugat_count) ? $stats->cerai_gugat_count : 0 ?>
														</span>
													</li>
													<li class="list-group-item d-flex justify-content-between align-items-center">
														Cerai Talak
														<span class="badge badge-info badge-pill">
															<?= isset($stats->cerai_talak_count) ? $stats->cerai_talak_count : 0 ?>
														</span>
													</li>
													<li class="list-group-item d-flex justify-content-between align-items-center">
														Lainnya
														<span class="badge badge-success badge-pill">
															<?= isset($stats->other_case_count) ? $stats->other_case_count : 0 ?>
														</span>
													</li>
												</ul>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="col-md-6">
								<div class="card card-info">
									<div class="card-header">
										<h3 class="card-title"><i class="fas fa-info-circle mr-1"></i> Informasi Perkara Prodeo</h3>
										<div class="card-tools">
											<button type="button" class="btn btn-tool" data-card-widget="collapse">
												<i class="fas fa-minus"></i>
											</button>
										</div>
									</div>
									<div class="card-body">
										<div class="callout callout-info">
											<h5><i class="fas fa-info-circle"></i> Apa itu Perkara Prodeo?</h5>
											<p>Perkara prodeo adalah perkara yang dibebaskan dari biaya perkara untuk orang yang tidak mampu secara ekonomi. Hal ini diatur dalam:</p>
											<ul>
												<li>Pasal 237-245 HIR/273-281 RBg</li>
												<li>Pasal 237 ayat (3) HIR/273 RBg</li>
												<li>SEMA Nomor 10 Tahun 2010 tentang Pedoman Bantuan Hukum</li>
											</ul>
										</div>

										<div class="info-box bg-gradient-success">
											<span class="info-box-icon"><i class="far fa-calendar-check"></i></span>
											<div class="info-box-content">
												<span class="info-box-text">Estimasi Biaya yang Dibebaskan</span>
												<span class="info-box-number">Rp. <?= number_format(count($datafilter) * 750000, 0, ',', '.') ?>,-</span>
												<div class="progress">
													<div class="progress-bar" style="width: 100%"></div>
												</div>
												<span class="progress-description">
													Dengan estimasi rata-rata Rp. 750,000 per perkara
												</span>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<!-- Export Buttons Row -->
						<div class="row mb-4">
							<div class="col-md-12">
								<div class="bg-light p-3" style="border-radius: 5px; border: 1px solid #ddd;">
									<h5><i class="fas fa-file-export mr-2"></i> Export Data Perkara Prodeo</h5>
									<div class="mt-3">
										<a href="<?= site_url('Prodeo/export_excel?jenis_perkara=' . $jenis_perkara . '&lap_bulan=' . $lap_bulan . '&lap_tahun=' . $lap_tahun) ?>" class="btn btn-success btn-lg">
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
									Data Perkara Prodeo <?= $jenis_perkara ?> - <?= $months[$lap_bulan] ?> <?= $lap_tahun ?>
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
											<th width="15%">Para Pihak</th>
											<th width="8%">Tgl Pendaftaran</th>
											<th width="8%">Tgl PMH</th>
											<th width="8%">Tgl PHS</th>
											<th width="8%">Sidang I</th>
											<th width="8%">Tgl Putusan</th>
											<th width="8%">Durasi</th>
											<th width="10%">Status Putusan</th>
											<th width="12%">Majelis Hakim</th>
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
												<td>
													<span class="badge badge-secondary"><?= $row->jenis_perkara_nama ?></span><br>
													<strong><?= $row->nomor_perkara ?></strong>
												</td>
												<td>
													<strong>P:</strong> <?= $row->nama_penggugat ?><br>
													<strong>T:</strong> <?= $row->nama_tergugat ?>
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
												<td><?= $row->majelis_hakim_nama ?></td>
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
							<p>Tidak ditemukan data perkara prodeo untuk jenis <strong><?= $jenis_perkara ?></strong> pada periode <strong><?= $months[$lap_bulan] ?> <?= $lap_tahun ?></strong>.</p>
							<p>Silakan coba periode lain atau jenis perkara yang berbeda.</p>
						</div>
					<?php else: ?>
						<!-- Welcome Message -->
						<div class="jumbotron bg-light">
							<h1 class="display-5"><i class="fas fa-hand-holding-usd mr-2"></i> Laporan Perkara Prodeo</h1>
							<p class="lead">Silakan tentukan parameter pencarian untuk menampilkan data perkara yang dibebaskan dari biaya perkara (prodeo).</p>
							<hr class="my-4">
							<p>Perkara prodeo adalah perkara yang dibebaskan dari biaya perkara untuk orang yang tidak mampu secara ekonomi sesuai dengan Pasal 237-245 HIR/273-281 RBg.</p>
						</div>
					<?php endif; ?>
				</div>
			</section>
		</div>
	</div>

	<script src="<?= base_url() ?>assets/plugins/chart.js/Chart.min.js"></script>
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
						title: 'Data Perkara Prodeo - <?= isset($jenis_perkara) ? $jenis_perkara : "" ?> <?= isset($lap_bulan) ? $months[$lap_bulan] : "" ?> <?= isset($lap_tahun) ? $lap_tahun : "" ?>',
						exportOptions: {
							columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
						},
						className: 'btn-success'
					},
					{
						extend: 'pdf',
						text: 'PDF',
						title: 'Data Perkara Prodeo - <?= isset($jenis_perkara) ? $jenis_perkara : "" ?> <?= isset($lap_bulan) ? $months[$lap_bulan] : "" ?> <?= isset($lap_tahun) ? $lap_tahun : "" ?>',
						exportOptions: {
							columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
						},
						orientation: 'landscape',
						className: 'btn-danger'
					},
					{
						extend: 'print',
						text: 'Print',
						title: 'Data Perkara Prodeo - <?= isset($jenis_perkara) ? $jenis_perkara : "" ?> <?= isset($lap_bulan) ? $months[$lap_bulan] : "" ?> <?= isset($lap_tahun) ? $lap_tahun : "" ?>',
						exportOptions: {
							columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
						},
						className: 'btn-primary'
					}
				]
			}).buttons().container().appendTo('#dataTable_wrapper .col-md-6:eq(0)');

			// Export buttons binding
			$('.export-pdf').click(function() {
				dataTable.button('.buttons-pdf').trigger();
			});

			$('.print-data').click(function() {
				dataTable.button('.buttons-print').trigger();
			});

			<?php if (!empty($datafilter) && isset($stats)): ?>
				// Initialize chart
				if (document.getElementById('caseTypeChart')) {
					var ctx = document.getElementById('caseTypeChart').getContext('2d');
					var caseTypeChart = new Chart(ctx, {
						type: 'pie',
						data: {
							labels: ['Cerai Gugat', 'Cerai Talak', 'Lainnya'],
							datasets: [{
								data: [
									<?= isset($stats->cerai_gugat_count) ? $stats->cerai_gugat_count : 0 ?>,
									<?= isset($stats->cerai_talak_count) ? $stats->cerai_talak_count : 0 ?>,
									<?= isset($stats->other_case_count) ? $stats->other_case_count : 0 ?>
								],
								backgroundColor: [
									'rgba(54, 162, 235, 0.8)',
									'rgba(255, 206, 86, 0.8)',
									'rgba(75, 192, 192, 0.8)'
								],
								borderColor: [
									'rgba(54, 162, 235, 1)',
									'rgba(255, 206, 86, 1)',
									'rgba(75, 192, 192, 1)'
								],
								borderWidth: 1
							}]
						},
						options: {
							responsive: true,
							maintainAspectRatio: false,
							legend: {
								position: 'right',
								labels: {
									boxWidth: 12
								}
							}
						}
					});
				}
			<?php endif; ?>
		});
	</script>
</body>

</html>