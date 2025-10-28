<body class="hold-transition sidebar-mini">
	<div class="wrapper">
		<div class="content-wrapper">
			<section class="content-header">
				<div class="container-fluid">
					<div class="row mb-2">
						<div class="col-sm-6">
							<h1 class="m-0 text-dark"><i class="fas fa-mosque mr-2"></i> Laporan Perceraian Untuk KUA</h1>
						</div>
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								<li class="breadcrumb-item"><a href="<?= site_url('Home') ?>">Home</a></li>
								<li class="breadcrumb-item">Laporan</li>
								<li class="breadcrumb-item active">Perceraian KUA</li>
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
							<form action="<?php echo base_url() ?>index.php/Cerai_kua" method="POST" class="form-horizontal">
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
												$months = [
													'01' => 'Januari',
													'02' => 'Februari',
													'03' => 'Maret',
													'04' => 'April',
													'05' => 'Mei',
													'06' => 'Juni',
													'07' => 'Juli',
													'08' => 'Agustus',
													'09' => 'September',
													'10' => 'Oktober',
													'11' => 'November',
													'12' => 'Desember'
												];

												foreach ($months as $value => $label) {
													$selected = (isset($lap_bulan) && $lap_bulan == $value) ? 'selected="selected"' : '';
													echo "<option value=\"$value\" $selected>$label</option>";
												}
												?>
											</select>
										</div>
									</div>
									<div class="col-sm-2">
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
									<div class="col-sm-2">
										<button type="submit" name="btn" value="search" class="btn btn-primary btn-block">
											<i class="fas fa-search mr-2"></i> Tampilkan
										</button>
									</div>
									<?php if (!empty($datafilter)): ?>
										<div class="col-sm-3">
											<div class="btn-group float-right">
												<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
													<i class="fas fa-download mr-1"></i> Export Data
												</button>
												<div class="dropdown-menu">
													<a class="dropdown-item export-excel" href="#">
														<i class="fas fa-file-excel mr-2 text-success"></i> Excel
													</a>
													<a class="dropdown-item export-pdf" href="#">
														<i class="fas fa-file-pdf mr-2 text-danger"></i> PDF
													</a>
													<a class="dropdown-item print-data" href="#">
														<i class="fas fa-print mr-2 text-primary"></i> Print
													</a>
												</div>
											</div>
										</div>
									<?php endif; ?>
								</div>
							</form>
						</div>
					</div>

					<?php if (isset($lap_bulan) && isset($lap_tahun)): ?>
						<div class="alert alert-info alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
							<h5><i class="icon fas fa-info"></i> Informasi</h5>
							Menampilkan data perceraian untuk periode <strong><?= $nama_bulan[$lap_bulan] ?> <?= $lap_tahun ?></strong>
							yang harus dilaporkan ke KUA sesuai dengan KMA No. 42 Tahun 2006 (Laporan F.16).
						</div>
					<?php endif; ?>

					<?php if (!empty($datafilter)): ?>
						<!-- Dashboard Summary -->
						<div class="row">
							<div class="col-lg-3 col-6">
								<div class="small-box bg-info">
									<div class="inner">
										<h3><?= count($datafilter) ?></h3>
										<p>Total Perceraian</p>
									</div>
									<div class="icon">
										<i class="fas fa-gavel"></i>
									</div>
									<a href="#" class="small-box-footer">
										Periode: <?= $nama_bulan[$lap_bulan] ?> <?= $lap_tahun ?>
										<i class="fas fa-calendar-alt mx-1"></i>
									</a>
								</div>
							</div>

							<div class="col-lg-3 col-6">
								<div class="small-box bg-success">
									<div class="inner">
										<h3><?= isset($stats->total_kua) ? $stats->total_kua : '0' ?></h3>
										<p>KUA Terdampak</p>
									</div>
									<div class="icon">
										<i class="fas fa-mosque"></i>
									</div>
									<a href="#" class="small-box-footer">
										Tercatat dalam database
										<i class="fas fa-info-circle mx-1"></i>
									</a>
								</div>
							</div>

							<div class="col-lg-3 col-6">
								<div class="small-box bg-warning">
									<div class="inner">
										<h3><?= isset($stats->avg_usia_pernikahan) ? round($stats->avg_usia_pernikahan, 1) : '-' ?></h3>
										<p>Rata-rata Usia Pernikahan</p>
									</div>
									<div class="icon">
										<i class="fas fa-clock"></i>
									</div>
									<a href="#" class="small-box-footer">
										Dalam tahun
										<i class="fas fa-info-circle mx-1"></i>
									</a>
								</div>
							</div>

							<div class="col-lg-3 col-6">
								<div class="small-box bg-danger">
									<div class="inner">
										<h3><?= isset($stats->blank_kua) ? $stats->blank_kua : '0' ?></h3>
										<p>KUA Tidak Tercatat</p>
									</div>
									<div class="icon">
										<i class="fas fa-exclamation-triangle"></i>
									</div>
									<a href="#" class="small-box-footer">
										Perlu verifikasi data
										<i class="fas fa-info-circle mx-1"></i>
									</a>
								</div>
							</div>
						</div>

						<!-- Charts Row -->
						<div class="row">
							<!-- KUA Distribution Chart -->
							<div class="col-md-6">
								<div class="card card-primary">
									<div class="card-header">
										<h3 class="card-title">
											<i class="fas fa-chart-pie mr-1"></i>
											Distribusi KUA
										</h3>
										<div class="card-tools">
											<button type="button" class="btn btn-tool" data-card-widget="collapse">
												<i class="fas fa-minus"></i>
											</button>
										</div>
									</div>
									<div class="card-body">
										<?php if (isset($stats->kua_distribution) && !empty($stats->kua_distribution)): ?>
											<div id="kuaDistributionChart" style="height: 300px;"></div>
										<?php else: ?>
											<div class="alert alert-warning">
												<i class="fas fa-info-circle"></i> Tidak cukup data untuk menampilkan distribusi KUA.
											</div>
										<?php endif; ?>
									</div>
								</div>
							</div>

							<!-- Marriage Duration Chart -->
							<div class="col-md-6">
								<div class="card card-success">
									<div class="card-header">
										<h3 class="card-title">
											<i class="fas fa-chart-bar mr-1"></i>
											Jumlah Perceraian per KUA
										</h3>
										<div class="card-tools">
											<button type="button" class="btn btn-tool" data-card-widget="collapse">
												<i class="fas fa-minus"></i>
											</button>
										</div>
									</div>
									<div class="card-body">
										<?php if (isset($stats->kua_distribution) && !empty($stats->kua_distribution)): ?>
											<div class="table-responsive">
												<table class="table table-striped table-hover">
													<thead>
														<tr>
															<th>Nama KUA</th>
															<th class="text-center">Jumlah Kasus</th>
															<th class="text-center">Persentase</th>
														</tr>
													</thead>
													<tbody>
														<?php if (isset($kua_counts) && !empty($kua_counts)):
															foreach ($kua_counts as $kua): ?>
																<tr>
																	<td><?= $kua->kua_tempat_nikah ?: 'Tidak Tercatat' ?></td>
																	<td class="text-center"><?= $kua->total ?></td>
																	<td class="text-center">
																		<?= round(($kua->total / count($datafilter)) * 100, 1) ?>%
																		<div class="progress progress-xs">
																			<div class="progress-bar bg-success" style="width: <?= round(($kua->total / count($datafilter)) * 100, 1) ?>%"></div>
																		</div>
																	</td>
																</tr>
															<?php endforeach;
														else: ?>
															<tr>
																<td colspan="3" class="text-center">Tidak ada data KUA</td>
															</tr>
														<?php endif; ?>
													</tbody>
												</table>
											</div>
										<?php else: ?>
											<div class="alert alert-warning">
												<i class="fas fa-info-circle"></i> Tidak cukup data untuk menampilkan statistik KUA.
											</div>
										<?php endif; ?>
									</div>
								</div>
							</div>
						</div>

						<!-- Additional Info Card -->
						<div class="card bg-gradient-info">
							<div class="card-header">
								<h3 class="card-title">
									<i class="fas fa-info-circle mr-1"></i>
									Informasi Laporan
								</h3>
								<div class="card-tools">
									<button type="button" class="btn btn-tool" data-card-widget="collapse">
										<i class="fas fa-minus"></i>
									</button>
								</div>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col-md-8">
										<p>Laporan perceraian untuk KUA ini berisi informasi yang harus dikirimkan ke setiap KUA tempat pernikahan pasangan yang bercerai dilaksanakan, sesuai dengan:</p>
										<ul>
											<li>Keputusan Menteri Agama No. 42 Tahun 2006</li>
											<li>Pasal 84 ayat (1), (2) dan (4) Undang-Undang No. 7 tahun 1989</li>
											<li>Pasal 147 ayat (2) KHI</li>
										</ul>
										<p><strong>Penting:</strong> Pemberitahuan ke KUA harus dilakukan dalam tenggang waktu 30 hari sejak putusan perceraian berkekuatan hukum tetap.</p>
									</div>
									<div class="col-md-4">
										<div class="info-box mb-3 bg-white">
											<span class="info-box-icon"><i class="far fa-clock"></i></span>
											<div class="info-box-content">
												<span class="info-box-text">Ketepatan Waktu Pelaporan</span>
												<span class="info-box-number">
													<?= isset($stats->on_time_percentage) ? $stats->on_time_percentage . '%' : 'Data tidak tersedia' ?>
												</span>
												<div class="progress">
													<div class="progress-bar" style="width: <?= isset($stats->on_time_percentage) ? $stats->on_time_percentage : 0 ?>%"></div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<!-- Main Data Card -->
						<div class="card card-outline card-primary">
							<div class="card-header bg-light">
								<h3 class="card-title">
									<i class="fas fa-table mr-1"></i>
									Data Perceraian Untuk KUA - <?= $nama_bulan[$lap_bulan] ?> <?= $lap_tahun ?>
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
									<table id="dataTable" class="table table-bordered table-striped table-hover">
										<thead>
											<tr class="bg-light">
												<th class="text-center" style="width: 3%">No</th>
												<th style="width: 11%">Nomor Perkara</th>
												<th style="width: 9%">Tgl Akta Cerai</th>
												<th style="width: 10%">Nomor Akta Cerai</th>
												<th style="width: 15%">Penggugat/Pemohon</th>
												<th style="width: 15%">Alamat Penggugat</th>
												<th style="width: 15%">Tergugat/Termohon</th>
												<th style="width: 15%">Alamat Tergugat</th>
												<th style="width: 10%">KUA Tempat Menikah</th>
											</tr>
										</thead>
										<tbody>
											<?php
											$no = 1;
											foreach ($datafilter as $row):
												// Menentukan class baris berdasarkan ada tidaknya data KUA
												$rowClass = empty($row->kua_tempat_nikah) ? 'table-warning' : '';
											?>
												<tr class="<?= $rowClass ?>">
													<td class="text-center"><?= $no++ ?></td>
													<td><?= $row->nomor_perkara ?></td>
													<td><?= date('d-m-Y', strtotime($row->tgl_akta_cerai)) ?></td>
													<td><?= $row->nomor_akta_cerai ?></td>
													<td>
														<strong><?= $row->nama_p ?></strong>
													</td>
													<td><?= $row->alamat_p ?></td>
													<td>
														<strong><?= $row->nama_t ?></strong>
													</td>
													<td><?= $row->alamat_t ?></td>
													<td>
														<?php if (!empty($row->kua_tempat_nikah)): ?>
															<span class="badge badge-success"><?= $row->kua_tempat_nikah ?></span>
														<?php else: ?>
															<span class="badge badge-warning">
																<i class="fas fa-exclamation-triangle mr-1"></i>
																Tidak Ada Data
															</span>
														<?php endif; ?>
													</td>
												</tr>
											<?php endforeach; ?>
										</tbody>
									</table>
								</div>
							</div>
							<div class="card-footer bg-light">
								<div class="row">
									<div class="col-md-6">
										<span class="text-muted"><i class="fas fa-info-circle mr-1"></i> Keterangan:</span>
										<ul class="list-unstyled ml-4 mb-0">
											<li><span class="badge badge-warning mr-1"><i class="fas fa-exclamation-triangle"></i></span> Baris kuning: Data KUA tidak tercatat</li>
										</ul>
									</div>
									<div class="col-md-6 text-right">
										<small class="text-muted">Total data: <?= count($datafilter) ?> | Diperbarui: <?= date('d-m-Y H:i:s') ?></small>
									</div>
								</div>
							</div>
						</div>
					<?php else: ?>
						<!-- No Data Message -->
						<?php if (isset($lap_bulan) && isset($lap_tahun)): ?>
							<div class="alert alert-warning alert-dismissible">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
								<h5><i class="icon fas fa-exclamation-triangle"></i> Tidak Ada Data</h5>
								<p>Tidak ada data perceraian pada periode yang dipilih. Silahkan pilih periode lainnya.</p>
								<div class="mt-3">
									<h6><i class="fas fa-lightbulb mr-1"></i> Beberapa kemungkinan penyebab:</h6>
									<ul>
										<li>Tidak ada perceraian yang dicatat pada periode tersebut</li>
										<li>Periode yang dipilih adalah periode di masa depan</li>
										<li>Data belum diinput ke dalam sistem</li>
									</ul>
								</div>
							</div>
						<?php else: ?>
							<div class="jumbotron bg-light">
								<h1 class="display-5"><i class="fas fa-mosque mr-2"></i> Laporan Perceraian Untuk KUA</h1>
								<p class="lead">Silahkan pilih periode untuk menampilkan data perceraian yang akan dikirimkan ke KUA.</p>
								<hr class="my-4">
								<p>Laporan ini berisi data pasangan yang bercerai untuk dilaporkan ke KUA tempat mereka menikah.</p>
								<p>
									<a class="btn btn-primary btn-lg" href="#" role="button">
										<i class="fas fa-question-circle mr-1"></i> Pelajari lebih lanjut
									</a>
								</p>
							</div>
						<?php endif; ?>
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
				"pageLength": 10,
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
						title: 'Data Perceraian KUA - <?= isset($nama_bulan[$lap_bulan]) ? $nama_bulan[$lap_bulan] : '' ?> <?= isset($lap_tahun) ? $lap_tahun : '' ?>',
						exportOptions: {
							columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
						},
						className: 'btn-success'
					},
					{
						extend: 'pdf',
						text: 'PDF',
						title: 'Data Perceraian KUA - <?= isset($nama_bulan[$lap_bulan]) ? $nama_bulan[$lap_bulan] : '' ?> <?= isset($lap_tahun) ? $lap_tahun : '' ?>',
						exportOptions: {
							columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
						},
						orientation: 'landscape',
						className: 'btn-danger',
						customize: function(doc) {
							// Styling PDF
							doc.styles.tableHeader.fontSize = 10;
							doc.defaultStyle.fontSize = 9;
							doc.defaultStyle.alignment = 'left';
							doc.styles.tableHeader.alignment = 'left';
						}
					},
					{
						extend: 'print',
						text: 'Print',
						title: 'Data Perceraian KUA - <?= isset($nama_bulan[$lap_bulan]) ? $nama_bulan[$lap_bulan] : '' ?> <?= isset($lap_tahun) ? $lap_tahun : '' ?>',
						className: 'btn-primary',
						exportOptions: {
							columns: [0, 1, 2, 3, 4, 5, 6, 7, 8]
						}
					}
				]
			}).buttons().container().appendTo('#dataTable_wrapper .col-md-6:eq(0)');

			// Export buttons binding
			$('.export-excel').click(function(e) {
				e.preventDefault();
				dataTable.button('.buttons-excel').trigger();
			});

			$('.export-pdf').click(function(e) {
				e.preventDefault();
				dataTable.button('.buttons-pdf').trigger();
			});

			$('.print-data').click(function(e) {
				e.preventDefault();
				dataTable.button('.buttons-print').trigger();
			});

			<?php if (isset($stats->kua_distribution) && !empty($stats->kua_distribution)): ?>
				// KUA Distribution Chart
				setTimeout(function() {
					ChartHelper.debugCanvas('kuaDistributionChart');

					if (document.getElementById('kuaDistributionChart')) {
						var kuaData = {
							labels: <?= json_encode(array_column($kua_counts, 'kua_tempat_nikah')) ?>,
							datasets: [{
								data: <?= json_encode(array_column($kua_counts, 'total')) ?>,
								backgroundColor: [
									'#36a2eb', '#ff6384', '#ffcd56', '#4bc0c0', '#9966ff',
									'#ff9f40', '#c9cbcf', '#7cb342', '#e91e63', '#3f51b5'
								]
							}]
						};

						var options = {
							responsive: true,
							maintainAspectRatio: false,
							legend: {
								position: 'right',
								labels: {
									boxWidth: 12
								}
							},
							tooltips: {
								callbacks: {
									label: function(tooltipItem, data) {
										var dataset = data.datasets[tooltipItem.datasetIndex];
										var total = dataset.data.reduce(function(previousValue, currentValue) {
											return previousValue + currentValue;
										});
										var currentValue = dataset.data[tooltipItem.index];
										var percentage = Math.floor(((currentValue / total) * 100) + 0.5);
										return data.labels[tooltipItem.index] + ': ' + currentValue + ' (' + percentage + '%)';
									}
								}
							}
						};

						ChartHelper.initChart('kuaDistributionChart', 'doughnut', kuaData, options);
					}
				}, 800);
			<?php endif; ?>
		});
	</script>
</body>

</html>