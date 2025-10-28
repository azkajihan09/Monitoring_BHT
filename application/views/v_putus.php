<body class="hold-transition sidebar-mini">
	<div class="wrapper">
		<div class="content-wrapper">
			<section class="content-header">
				<div class="container-fluid">
					<div class="row mb-2">
						<div class="col-sm-6">
							<h1 class="m-0 text-dark"><i class="fas fa-gavel mr-2"></i> Laporan Perkara Putus</h1>
						</div>
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								<li class="breadcrumb-item"><a href="<?= site_url('Admin/Dashboard') ?>">Home</a></li>
								<li class="breadcrumb-item">Perkara</li>
								<li class="breadcrumb-item active">Perkara Putus</li>
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
							<form action="<?php echo base_url() ?>index.php/Putus" method="GET" class="form-horizontal">
								<div class="form-group row">
									<label class="col-sm-2 col-form-label">Jenis Perkara:</label>
									<div class="col-sm-4">
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text"><i class="fas fa-balance-scale"></i></span>
											</div>
											<select name="jenis_perkara" class="form-control select2">
												<option value="all" <?= ($jenis_perkara === 'all') ? 'selected' : ''; ?>>Semua Jenis</option>
												<option value="Pdt.G" <?= ($jenis_perkara === 'Pdt.G') ? 'selected' : ''; ?>>Gugatan (Pdt.G)</option>
												<option value="Pdt.P" <?= ($jenis_perkara === 'Pdt.P') ? 'selected' : ''; ?>>Permohonan (Pdt.P)</option>
												<option value="Pdt.Eks" <?= ($jenis_perkara === 'Pdt.Eks') ? 'selected' : ''; ?>>Eksekusi (Pdt.Eks)</option>
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
														<option value="">-- Semua Bulan --</option>
														<?php
														foreach ($months as $value => $label) {
															$selected = ($lap_bulan === $value) ? 'selected' : '';
															echo "<option value=\"$value\" $selected>$label</option>";
														}
														?>
													</select>
												</div>
											</div>
											<div class="col-sm-6">
												<div class="input-group">
													<div class="input-group-prepend">
														<span class="input-group-text"><i class="far fa-calendar-check"></i></span>
													</div>
													<select name="lap_tahun" class="form-control select2" required>
														<option value="">-- Pilih Tahun --</option>
														<?php
														$currentYear = date('Y');
														for ($year = 2016; $year <= $currentYear; $year++) {
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
									<label class="col-sm-2 col-form-label">Pencarian:</label>
									<div class="col-sm-6">
										<input type="text" name="search" class="form-control" placeholder="Cari nomor perkara, majelis hakim, status putusan..." value="<?= $search ?>">
									</div>
									<div class="col-sm-4">
										<button type="submit" class="btn btn-primary">
											<i class="fas fa-search mr-2"></i> Tampilkan Data
										</button>
										<?php if (!empty($datafilter)): ?>
											<a href="<?= site_url('Putus/export_excel') ?>" class="btn btn-success">
												<i class="fas fa-file-excel mr-2"></i> Export Excel
											</a>
										<?php endif; ?>
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
										<h3><?= $stats->total_perkara ?></h3>
										<p>Total Perkara Putus</p>
									</div>
									<div class="icon">
										<i class="fas fa-gavel"></i>
									</div>
									<a href="#" class="small-box-footer">
										<?= $lap_bulan ? $months[$lap_bulan] . ' ' : '' ?><?= $lap_tahun ?>
										<i class="fas fa-calendar-alt mx-1"></i>
									</a>
								</div>
							</div>

							<div class="col-lg-3 col-6">
								<div class="small-box bg-success">
									<div class="inner">
										<h3><?= round($stats->avg_duration) ?></h3>
										<p>Rata-rata Durasi Perkara (hari)</p>
									</div>
									<div class="icon">
										<i class="fas fa-clock"></i>
									</div>
									<a href="#" class="small-box-footer">
										Min: <?= $stats->min_duration ?> | Max: <?= $stats->max_duration ?>
										<i class="fas fa-info-circle mx-1"></i>
									</a>
								</div>
							</div>

							<div class="col-lg-3 col-6">
								<div class="small-box bg-warning">
									<div class="inner">
										<h3><?= $stats->total_minutasi ?></h3>
										<p>Perkara Minutasi</p>
									</div>
									<div class="icon">
										<i class="fas fa-check-circle"></i>
									</div>
									<a href="#" class="small-box-footer">
										<?= $stats->minutasi_percentage ?>% dari total perkara
										<i class="fas fa-info-circle mx-1"></i>
									</a>
								</div>
							</div>

							<div class="col-lg-3 col-6">
								<div class="small-box bg-danger">
									<div class="inner">
										<h3><?= $stats->total_majelis ?></h3>
										<p>Majelis Hakim Terlibat</p>
									</div>
									<div class="icon">
										<i class="fas fa-users"></i>
									</div>
									<a href="#" class="small-box-footer">
										Jumlah majelis hakim yang menangani
										<i class="fas fa-info-circle mx-1"></i>
									</a>
								</div>
							</div>
						</div>

						<!-- Chart Section -->
						<div class="row">
							<!-- Case Status Distribution -->
							<div class="col-md-6">
								<div class="card">
									<div class="card-header bg-gradient-primary">
										<h3 class="card-title">
											<i class="fas fa-chart-pie mr-1"></i>
											Distribusi Status Putusan
										</h3>
										<div class="card-tools">
											<button type="button" class="btn btn-tool" data-card-widget="collapse">
												<i class="fas fa-minus"></i>
											</button>
										</div>
									</div>
									<div class="card-body">
										<div class="chart-container" style="position: relative; height:300px;">
											<canvas id="statusChart"></canvas>
										</div>
									</div>
								</div>
							</div>

							<!-- Case Types Distribution -->
							<div class="col-md-6">
								<div class="card">
									<div class="card-header bg-gradient-success">
										<h3 class="card-title">
											<i class="fas fa-chart-bar mr-1"></i>
											Jenis Perkara Putus
										</h3>
										<div class="card-tools">
											<button type="button" class="btn btn-tool" data-card-widget="collapse">
												<i class="fas fa-minus"></i>
											</button>
										</div>
									</div>
									<div class="card-body">
										<div class="chart-container" style="position: relative; height:300px;">
											<canvas id="caseTypeChart"></canvas>
										</div>
									</div>
								</div>
							</div>
						</div>

						<?php if (isset($stats->monthly_distribution) && !$lap_bulan): ?>
							<!-- Monthly Trend -->
							<div class="card">
								<div class="card-header bg-gradient-info">
									<h3 class="card-title">
										<i class="fas fa-chart-line mr-1"></i>
										Tren Bulanan Perkara Putus Tahun <?= $lap_tahun ?>
									</h3>
									<div class="card-tools">
										<button type="button" class="btn btn-tool" data-card-widget="collapse">
											<i class="fas fa-minus"></i>
										</button>
									</div>
								</div>
								<div class="card-body">
									<div class="chart-container" style="position: relative; height:250px;">
										<canvas id="monthlyTrendChart"></canvas>
									</div>
								</div>
							</div>
						<?php endif; ?>

						<!-- Main Data Table -->
						<div class="card">
							<div class="card-header bg-light">
								<h3 class="card-title">
									<i class="fas fa-list mr-1"></i>
									Daftar Perkara Putus <?= $lap_bulan ? $months[$lap_bulan] . ' ' : '' ?><?= $lap_tahun ?>
								</h3>
								<div class="card-tools">
									<button type="button" class="btn btn-tool" data-card-widget="collapse">
										<i class="fas fa-minus"></i>
									</button>
								</div>
							</div>
							<div class="card-body p-0">
								<div class="table-responsive">
									<table class="table table-bordered table-striped table-hover">
										<thead>
											<tr>
												<th class="text-center" width="3%">No</th>
												<th width="15%">Nomor Perkara</th>
												<th width="10%">Jenis Perkara</th>
												<th width="10%">Tanggal Daftar</th>
												<th width="10%">Tanggal Putus</th>
												<th width="5%">Durasi</th>
												<th width="15%">Status Putusan</th>
												<th width="10%">Minutasi</th>
												<th width="22%">Majelis Hakim</th>
											</tr>
										</thead>
										<tbody>
											<?php
											$no = $offset + 1;
											foreach ($datafilter as $row):
											?>
												<tr>
													<td class="text-center"><?= $no++ ?></td>
													<td><?= $row->nomor_perkara ?></td>
													<td><?= $row->jenis_perkara_nama ?></td>
													<td><?= date('d-m-Y', strtotime($row->tanggal_pendaftaran)) ?></td>
													<td><?= date('d-m-Y', strtotime($row->tanggal_putusan)) ?></td>
													<td class="text-center">
														<?php
														$days = $row->durasi_perkara;
														if ($days <= 60) {
															echo '<span class="badge badge-success">' . $days . '</span>';
														} else if ($days <= 120) {
															echo '<span class="badge badge-warning">' . $days . '</span>';
														} else {
															echo '<span class="badge badge-danger">' . $days . '</span>';
														}
														?>
													</td>
													<td><?= $row->status_putusan_nama ?></td>
													<td>
														<?php if ($row->tanggal_minutasi): ?>
															<span class="badge badge-success">
																<i class="fas fa-check-circle mr-1"></i>
																<?= date('d-m-Y', strtotime($row->tanggal_minutasi)) ?>
															</span>
														<?php else: ?>
															<span class="badge badge-secondary">
																<i class="fas fa-clock mr-1"></i> Belum
															</span>
														<?php endif; ?>
													</td>
													<td><?= $row->majelis_hakim_nama ?></td>
												</tr>
											<?php endforeach; ?>
										</tbody>
									</table>
								</div>
							</div>
							<div class="card-footer bg-light">
								<div class="row">
									<div class="col-md-6">
										<?= $pagination ?>
									</div>
									<div class="col-md-6 text-right">
										<span class="text-muted">
											Total: <?= $total_rows ?> perkara |
											Halaman: <?= $offset + 1 ?> - <?= min($offset + count($datafilter), $total_rows) ?> dari <?= $total_rows ?>
										</span>
									</div>
								</div>
							</div>
						</div>
					<?php else: ?>
						<!-- No Data Alert -->
						<div class="alert alert-info alert-dismissible">
							<h5><i class="icon fas fa-info"></i> Informasi</h5>
							<p>
								<?php if (isset($_GET['lap_tahun'])): ?>
									Tidak ditemukan data perkara putus untuk periode yang dipilih.
								<?php else: ?>
									Silahkan pilih periode dan klik tombol "Tampilkan Data" untuk menampilkan data perkara putus.
								<?php endif; ?>
							</p>
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

			<?php if (!empty($datafilter)): ?>
				// Status Distribution Chart
				var statusCtx = document.getElementById('statusChart').getContext('2d');
				var statusData = {
					labels: [
						<?php
						$statusLabels = [];
						$statusValues = [];
						$statusColors = [];

						foreach ($stats->status_distribution as $index => $status) {
							$statusLabels[] = "'" . addslashes($status->status_putusan_nama) . "'";
							$statusValues[] = $status->total;

							// Generate different colors
							$colors = ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de', '#6c757d'];
							$statusColors[] = "'" . $colors[$index % count($colors)] . "'";
						}

						echo implode(', ', $statusLabels);
						?>
					],
					datasets: [{
						data: [<?= implode(', ', $statusValues) ?>],
						backgroundColor: [<?= implode(', ', $statusColors) ?>]
					}]
				};

				var statusChart = new Chart(statusCtx, {
					type: 'doughnut',
					data: statusData,
					options: {
						responsive: true,
						maintainAspectRatio: false,
						legend: {
							position: 'right'
						},
						title: {
							display: false
						}
					}
				});

				// Case Types Chart
				var typeCtx = document.getElementById('caseTypeChart').getContext('2d');
				var typeData = {
					labels: [
						<?php
						$typeLabels = [];
						$typeValues = [];

						foreach ($stats->case_type_distribution as $type) {
							$typeLabels[] = "'" . addslashes($type->jenis_perkara_nama) . "'";
							$typeValues[] = $type->total;
						}

						echo implode(', ', $typeLabels);
						?>
					],
					datasets: [{
						label: 'Jumlah Perkara',
						data: [<?= implode(', ', $typeValues) ?>],
						backgroundColor: '#36a2eb'
					}]
				};

				var typeChart = new Chart(typeCtx, {
					type: 'bar',
					data: typeData,
					options: {
						responsive: true,
						maintainAspectRatio: false,
						scales: {
							yAxes: [{
								ticks: {
									beginAtZero: true,
									precision: 0
								}
							}]
						},
						legend: {
							display: false
						}
					}
				});

				<?php if (isset($stats->monthly_distribution) && !$lap_bulan): ?>
					// Monthly Trend Chart
					var monthlyCtx = document.getElementById('monthlyTrendChart').getContext('2d');

					// Prepare data for all 12 months
					var monthlyData = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

					<?php foreach ($stats->monthly_distribution as $month): ?>
						monthlyData[<?= $month->month - 1 ?>] = <?= $month->total ?>;
					<?php endforeach; ?>

					var trendData = {
						labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'],
						datasets: [{
							label: 'Jumlah Perkara Putus',
							data: monthlyData,
							backgroundColor: 'rgba(60, 141, 188, 0.3)',
							borderColor: '#3c8dbc',
							pointBackgroundColor: '#3c8dbc',
							pointRadius: 5,
							pointHoverRadius: 7,
							borderWidth: 2,
							fill: true
						}]
					};

					var trendChart = new Chart(monthlyCtx, {
						type: 'line',
						data: trendData,
						options: {
							responsive: true,
							maintainAspectRatio: false,
							scales: {
								yAxes: [{
									ticks: {
										beginAtZero: true,
										precision: 0
									}
								}]
							}
						}
					});
				<?php endif; ?>
			<?php endif; ?>
		});
	</script>
</body>

</html>