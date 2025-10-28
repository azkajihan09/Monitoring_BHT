<body class="hold-transition sidebar-mini">
	<div class="wrapper">
		<div class="content-wrapper">
			<section class="content-header">
				<div class="container-fluid">
					<div class="row mb-2">
						<div class="col-sm-6">
							<h1 class="m-0 text-dark"><i class="fas fa-balance-scale mr-2"></i> Laporan Sisa Perkara Bulan Ini</h1>
						</div>
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								<li class="breadcrumb-item"><a href="<?= site_url('Home') ?>">Home</a></li>
								<li class="breadcrumb-item">Perkara</li>
								<li class="breadcrumb-item active">Sisa Bulan Ini</li>
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
							<form action="<?php echo base_url() ?>index.php/sisa_bulan_ini" method="GET" class="form-horizontal">
								<div class="form-group row">
									<label class="col-sm-2 col-form-label">Jenis Perkara:</label>
									<div class="col-sm-4">
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text"><i class="fas fa-gavel"></i></span>
											</div>
											<select name="jenis_perkara" class="form-control select2">
												<option value="Pdt.G" <?= ($jenis_perkara === 'Pdt.G') ? 'selected' : ''; ?>>Gugatan (Pdt.G)</option>
												<option value="Pdt.P" <?= ($jenis_perkara === 'Pdt.P') ? 'selected' : ''; ?>>Permohonan (Pdt.P)</option>
												<option value="all" <?= ($jenis_perkara === 'all') ? 'selected' : ''; ?>>Semua Jenis</option>
											</select>
										</div>
									</div>

									<label class="col-sm-2 col-form-label">Periode:</label>
									<div class="col-sm-4">
										<div class="row">
											<div class="col-sm-6">
												<div class="input-group">
													<div class="input-group-prepend">
														<span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
													</div>
													<select name="lap_bulan" class="form-control select2">
														<?php foreach ($months as $value => $label): ?>
															<option value="<?= $value ?>" <?= ($lap_bulan === $value) ? 'selected' : ''; ?>><?= $label ?></option>
														<?php endforeach; ?>
													</select>
												</div>
											</div>
											<div class="col-sm-6">
												<div class="input-group">
													<div class="input-group-prepend">
														<span class="input-group-text"><i class="far fa-calendar-check"></i></span>
													</div>
													<select name="lap_tahun" class="form-control select2">
														<?php
														$currentYear = date('Y');
														for ($year = 2016; $year <= $currentYear; $year++): ?>
															<option value="<?= $year ?>" <?= ($lap_tahun == $year) ? 'selected' : ''; ?>><?= $year ?></option>
														<?php endfor; ?>
													</select>
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="form-group row">
									<label class="col-sm-2 col-form-label">Pencarian:</label>
									<div class="col-sm-6">
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text"><i class="fas fa-search"></i></span>
											</div>
											<input type="text" name="search" class="form-control" placeholder="Cari nama hakim, nomor perkara..." value="<?= $search ?>">
										</div>
									</div>
									<div class="col-sm-4">
										<button type="submit" class="btn btn-primary">
											<i class="fas fa-search mr-2"></i> Tampilkan Data
										</button>
										<a href="<?= site_url('Sisa_bulan_ini/export_excel') ?>" class="btn btn-success">
											<i class="fas fa-file-excel mr-2"></i> Export Excel
										</a>
									</div>
								</div>
							</form>
						</div>
					</div>

					<?php if (!empty($datafilter)): ?>
						<!-- Statistics Cards -->
						<div class="row">
							<div class="col-lg-3 col-6">
								<div class="small-box bg-info">
									<div class="inner">
										<h3><?= $total_cases ?></h3>
										<p>Total Sisa Perkara</p>
									</div>
									<div class="icon">
										<i class="fas fa-file-alt"></i>
									</div>
									<a href="#" class="small-box-footer">
										<?= $months[$lap_bulan] ?> <?= $lap_tahun ?>
										<i class="fas fa-calendar-alt mx-1"></i>
									</a>
								</div>
							</div>

							<div class="col-lg-3 col-6">
								<div class="small-box bg-success">
									<div class="inner">
										<h3><?= count($datafilter) ?></h3>
										<p>Majelis Hakim</p>
									</div>
									<div class="icon">
										<i class="fas fa-users"></i>
									</div>
									<a href="#" class="small-box-footer">
										Rata-rata: <?= round($total_cases / count($datafilter), 1) ?> perkara/majelis
										<i class="fas fa-info-circle mx-1"></i>
									</a>
								</div>
							</div>

							<div class="col-lg-3 col-6">
								<div class="small-box bg-warning">
									<div class="inner">
										<h3><?= isset($stats->avg_case_age) ? round($stats->avg_case_age) : '-' ?></h3>
										<p>Rata-rata Umur Perkara</p>
									</div>
									<div class="icon">
										<i class="fas fa-clock"></i>
									</div>
									<a href="#" class="small-box-footer">
										Dalam hari
										<i class="fas fa-info-circle mx-1"></i>
									</a>
								</div>
							</div>

							<div class="col-lg-3 col-6">
								<div class="small-box bg-danger">
									<div class="inner">
										<h3><?= isset($stats->oldest_case) ? round($stats->oldest_case) : '-' ?></h3>
										<p>Umur Perkara Tertua</p>
									</div>
									<div class="icon">
										<i class="fas fa-hourglass-end"></i>
									</div>
									<a href="#" class="small-box-footer">
										Dalam hari
										<i class="fas fa-info-circle mx-1"></i>
									</a>
								</div>
							</div>
						</div>

						<!-- Charts Row -->
						<div class="row">
							<!-- Case Age Distribution Chart -->
							<?php if (isset($stats->age_distribution)): ?>
								<div class="col-md-6">
									<div class="card">
										<div class="card-header bg-gradient-info">
											<h3 class="card-title">
												<i class="fas fa-chart-pie mr-1"></i>
												Distribusi Umur Perkara
											</h3>
											<div class="card-tools">
												<button type="button" class="btn btn-tool" data-card-widget="collapse">
													<i class="fas fa-minus"></i>
												</button>
											</div>
										</div>
										<div class="card-body">
											<canvas id="ageDistributionChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
										</div>
									</div>
								</div>
							<?php endif; ?>

							<!-- Case Type Distribution Chart -->
							<?php if (isset($stats->case_types) && !empty($stats->case_types)): ?>
								<div class="col-md-6">
									<div class="card">
										<div class="card-header bg-gradient-success">
											<h3 class="card-title">
												<i class="fas fa-chart-bar mr-1"></i>
												Jenis Perkara
											</h3>
											<div class="card-tools">
												<button type="button" class="btn btn-tool" data-card-widget="collapse">
													<i class="fas fa-minus"></i>
												</button>
											</div>
										</div>
										<div class="card-body">
											<canvas id="caseTypesChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
										</div>
									</div>
								</div>
							<?php endif; ?>
						</div>

						<!-- Main Data Table -->
						<div class="card">
							<div class="card-header">
								<h3 class="card-title">
									<i class="fas fa-table mr-1"></i>
									Data Sisa Perkara Bulan <?= $months[$lap_bulan] ?> <?= $lap_tahun ?>
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
											<th class="text-center" width="5%">No</th>
											<th width="60%">Majelis Hakim</th>
											<th class="text-center" width="15%">Jumlah Perkara</th>
											<th class="text-center" width="20%">Aksi</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$no = 1;
										foreach ($datafilter as $row): ?>
											<tr>
												<td class="text-center"><?= $no++ ?></td>
												<td><?= $row->majelis_hakim_nama ?></td>
												<td class="text-center">
													<span class="badge badge-<?= $row->sisa_bulan_ini > 10 ? 'danger' : ($row->sisa_bulan_ini > 5 ? 'warning' : 'success') ?>"><?= $row->sisa_bulan_ini ?></span>
												</td>
												<td class="text-center">
													<a href="<?= site_url('Sisa_bulan_ini/detail/' . $row->majelis_hakim_id . '?jenis_perkara=' . $jenis_perkara . '&lap_bulan=' . $lap_bulan . '&lap_tahun=' . $lap_tahun) ?>" class="btn btn-sm btn-info">
														<i class="fas fa-eye mr-1"></i> Lihat Detail
													</a>
													<a href="<?= site_url('Sisa_bulan_ini/export_detail/' . $row->majelis_hakim_id . '?jenis_perkara=' . $jenis_perkara . '&lap_bulan=' . $lap_bulan . '&lap_tahun=' . $lap_tahun) ?>" class="btn btn-sm btn-success">
														<i class="fas fa-file-excel mr-1"></i> Export
													</a>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>
						</div>
					<?php else: ?>
						<!-- No Data Message -->
						<div class="alert alert-info">
							<h5><i class="icon fas fa-info"></i> Informasi</h5>
							<p>Tidak ada data sisa perkara untuk periode yang dipilih.</p>
							<?php if (isset($_GET) && !empty($_GET)): ?>
								<p>Silahkan ubah filter pencarian atau coba untuk bulan/tahun lainnya.</p>
							<?php else: ?>
								<p>Silahkan pilih periode dan klik "Tampilkan Data" untuk melihat data sisa perkara.</p>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>
			</section>
		</div>
	</div>

	<?php if (!empty($datafilter) && isset($stats)): ?>
		<!-- ChartJS -->
		<script src="<?= base_url() ?>assets/plugins/chart.js/Chart.min.js"></script>
		<script>
			$(function() {
				// Initialize Select2
				$('.select2').select2({
					theme: 'bootstrap4'
				});

				// Initialize DataTable
				$("#dataTable").DataTable({
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
				});

				<?php if (isset($stats->age_distribution)): ?>
					// Age Distribution Chart
					var ageDistributionChartCanvas = document.getElementById('ageDistributionChart');
					if (ageDistributionChartCanvas) {
						var ageData = {
							labels: ['< 30 hari', '30-90 hari', '91-180 hari', '> 180 hari'],
							datasets: [{
								data: [
									<?= $stats->age_distribution->under_30_days ?>,
									<?= $stats->age_distribution->under_3_months ?>,
									<?= $stats->age_distribution->under_6_months ?>,
									<?= $stats->age_distribution->over_6_months ?>
								],
								backgroundColor: ['#28a745', '#17a2b8', '#ffc107', '#dc3545']
							}]
						};

						var ageDistributionChart = new Chart(ageDistributionChartCanvas, {
							type: 'pie',
							data: ageData,
							options: {
								responsive: true,
								maintainAspectRatio: false,
								legend: {
									position: 'right'
								}
							}
						});
					}
				<?php endif; ?>

				<?php if (isset($stats->case_types) && !empty($stats->case_types)): ?>
					// Case Types Chart
					var caseTypesChartCanvas = document.getElementById('caseTypesChart');
					if (caseTypesChartCanvas) {
						var caseTypeData = {
							labels: [
								<?php foreach ($stats->case_types as $type): ?> '<?= $type->jenis_perkara_nama ?>',
								<?php endforeach; ?>
							],
							datasets: [{
								label: 'Jumlah Perkara',
								data: [
									<?php foreach ($stats->case_types as $type): ?>
										<?= $type->count ?>,
									<?php endforeach; ?>
								],
								backgroundColor: '#17a2b8'
							}]
						};

						var caseTypesChart = new Chart(caseTypesChartCanvas, {
							type: 'horizontalBar',
							data: caseTypeData,
							options: {
								responsive: true,
								maintainAspectRatio: false,
								scales: {
									xAxes: [{
										ticks: {
											beginAtZero: true
										}
									}]
								},
								legend: {
									display: false
								}
							}
						});
					}
				<?php endif; ?>
			});
		</script>
	<?php endif; ?>
</body>

</html>