<body class="hold-transition sidebar-mini">
	<div class="wrapper">
		<div class="content-wrapper">
			<section class="content-header">
				<div class="container-fluid">
					<div class="row mb-2">
						<div class="col-sm-6">
							<h1 class="m-0 text-dark"><i class="fas fa-file-import mr-2"></i> Laporan Perkara Masuk</h1>
						</div>
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								<li class="breadcrumb-item"><a href="<?= site_url('Admin/Dashboard') ?>">Home</a></li>
								<li class="breadcrumb-item">Perkara</li>
								<li class="breadcrumb-item active">Perkara Masuk</li>
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
							<form action="<?php echo base_url() ?>index.php/Masuk" method="POST" class="form-horizontal">
								<div class="form-group row">
									<label class="col-sm-2 col-form-label">Jenis Perkara:</label>
									<div class="col-sm-4">
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text"><i class="fas fa-balance-scale"></i></span>
											</div>
											<select name="jenis_perkara" class="form-control select2" required>
												<option value="Pdt.G" <?php echo (isset($_POST['jenis_perkara']) && $_POST['jenis_perkara'] === 'Pdt.G') ? 'selected' : ''; ?>>Perkara Gugatan (Pdt.G)</option>
												<option value="Pdt.P" <?php echo (isset($_POST['jenis_perkara']) && $_POST['jenis_perkara'] === 'Pdt.P') ? 'selected' : ''; ?>>Perkara Permohonan (Pdt.P)</option>
												<option value="all" <?php echo (isset($_POST['jenis_perkara']) && $_POST['jenis_perkara'] === 'all') ? 'selected' : ''; ?>>Semua Jenis</option>
											</select>
										</div>
									</div>
								</div>

								<div class="form-group row">
									<label class="col-sm-2 col-form-label">Periode:</label>
									<div class="col-sm-10">
										<div class="row">
											<div class="col-md-4">
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

														$current_month = date('m');
														foreach ($months as $value => $label) {
															$selected = (isset($_POST['lap_bulan']) && $_POST['lap_bulan'] === $value) ? 'selected' : (!isset($_POST['lap_bulan']) && $value == $current_month ? 'selected' : '');
															echo "<option value=\"$value\" $selected>$label</option>";
														}
														?>
													</select>
												</div>
											</div>
											<div class="col-md-4">
												<div class="input-group">
													<div class="input-group-prepend">
														<span class="input-group-text"><i class="far fa-calendar-check"></i></span>
													</div>
													<select name="lap_tahun" class="form-control select2" required>
														<option value="">-- Pilih Tahun --</option>
														<?php
														$currentYear = date('Y');
														for ($year = 2016; $year <= $currentYear + 1; $year++) {
															$selected = (isset($_POST['lap_tahun']) && $_POST['lap_tahun'] == $year) ? 'selected' : (!isset($_POST['lap_tahun']) && $year == $currentYear ? 'selected' : '');
															echo "<option value=\"$year\" $selected>$year</option>";
														}
														?>
													</select>
												</div>
											</div>
											<div class="col-md-4">
												<input type="text" name="search" class="form-control" placeholder="Cari nama hakim..." value="<?= isset($_POST['search']) ? $_POST['search'] : '' ?>">
											</div>
										</div>
									</div>
								</div>

								<div class="form-group row">
									<div class="col-sm-10 offset-sm-2">
										<button type="submit" name="btn" value="search" class="btn btn-primary">
											<i class="fas fa-search mr-2"></i> Tampilkan Data
										</button>
										<?php if (!empty($datafilter)): ?>
											<a href="<?= base_url('index.php/Masuk/export_excel/' .
																	(isset($_POST['jenis_perkara']) ? $_POST['jenis_perkara'] : 'Pdt.G') . '/' .
																	(isset($_POST['lap_bulan']) ? $_POST['lap_bulan'] : date('m')) . '/' .
																	(isset($_POST['lap_tahun']) ? $_POST['lap_tahun'] : date('Y')) . '/' .
																	(isset($_POST['search']) ? urlencode($_POST['search']) : '')) ?>"
												class="btn btn-success">
												<i class="fas fa-file-excel mr-2"></i> Export Excel
											</a>
										<?php endif; ?>
									</div>
								</div>
							</form>
						</div>
					</div>

					<?php if (isset($stats) && !empty($datafilter)): ?>
						<!-- Statistics Cards -->
						<div class="row">
							<div class="col-lg-3 col-6">
								<div class="small-box bg-info">
									<div class="inner">
										<h3><?= $stats->total_perkara ?></h3>
										<p>Total Perkara Masuk</p>
									</div>
									<div class="icon">
										<i class="fas fa-file-alt"></i>
									</div>
									<a href="#" class="small-box-footer">
										<?= isset($_POST['lap_bulan']) && isset($_POST['lap_tahun']) ? $months[$_POST['lap_bulan']] . ' ' . $_POST['lap_tahun'] : '' ?>
										<i class="fas fa-calendar-alt mx-1"></i>
									</a>
								</div>
							</div>

							<div class="col-lg-3 col-6">
								<div class="small-box bg-success">
									<div class="inner">
										<h3><?= $stats->total_majelis ?></h3>
										<p>Jumlah Majelis</p>
									</div>
									<div class="icon">
										<i class="fas fa-user-friends"></i>
									</div>
									<a href="#" class="small-box-footer">
										Rata-rata: <?= round($stats->avg_per_majelis, 1) ?> perkara/majelis
										<i class="fas fa-info-circle mx-1"></i>
									</a>
								</div>
							</div>

							<div class="col-lg-3 col-6">
								<div class="small-box bg-warning">
									<div class="inner">
										<h3><?= $stats->max_load ?></h3>
										<p>Beban Terbanyak</p>
									</div>
									<div class="icon">
										<i class="fas fa-weight-hanging"></i>
									</div>
									<a href="#" class="small-box-footer">
										<?= $stats->max_load_majelis ?>
										<i class="fas fa-info-circle mx-1"></i>
									</a>
								</div>
							</div>

							<div class="col-lg-3 col-6">
								<div class="small-box bg-danger">
									<div class="inner">
										<h3><?= $stats->min_load ?></h3>
										<p>Beban Tersedikit</p>
									</div>
									<div class="icon">
										<i class="fas fa-balance-scale"></i>
									</div>
									<a href="#" class="small-box-footer">
										<?= $stats->min_load_majelis ?>
										<i class="fas fa-info-circle mx-1"></i>
									</a>
								</div>
							</div>
						</div>

						<!-- Distribution Chart -->
						<div class="row">
							<div class="col-md-8">
								<div class="card card-primary">
									<div class="card-header">
										<h3 class="card-title"><i class="fas fa-chart-bar mr-1"></i> Distribusi Perkara per Majelis</h3>
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
										<h3 class="card-title"><i class="fas fa-info-circle mr-1"></i> Distribusi Beban</h3>
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
													<th>Kategori</th>
													<th class="text-center">Jumlah Majelis</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>Beban Tinggi (>10)</td>
													<td class="text-center">
														<span class="badge badge-danger"><?= $stats->high_load_count ?></span>
													</td>
												</tr>
												<tr>
													<td>Beban Sedang (5-10)</td>
													<td class="text-center">
														<span class="badge badge-warning"><?= $stats->medium_load_count ?></span>
													</td>
												</tr>
												<tr>
													<td>Beban Rendah (<5)< /td>
													<td class="text-center">
														<span class="badge badge-success"><?= $stats->low_load_count ?></span>
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
					<?php if (!empty($datafilter)): ?>
						<div class="card card-outline card-primary">
							<div class="card-header">
								<h3 class="card-title"><i class="fas fa-table mr-1"></i> Data Perkara Masuk <?php echo (isset($_POST['lap_bulan']) && isset($months[$_POST['lap_bulan']])) ? $months[$_POST['lap_bulan']] . ' ' . $_POST['lap_tahun'] : ''; ?></h3>
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
								<table class="table table-bordered table-striped" id="dataTable">
									<thead>
										<tr>
											<th class="text-center" width="5%">No</th>
											<th width="50%">Majelis Hakim</th>
											<th class="text-center" width="15%">Jumlah Perkara</th>
											<th class="text-center" width="15%">Persentase</th>
											<th class="text-center" width="15%">Aksi</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$no = 1;
										foreach ($datafilter as $row): ?>
											<tr>
												<td class="text-center"><?= $no++ ?></td>
												<td>
													<strong><?= $row->majelis_hakim_nama ?></strong>
													<?php if (!empty($row->hakim_ketua)): ?>
														<div class="small text-muted">Ketua: <?= $row->hakim_ketua ?></div>
													<?php endif; ?>
												</td>
												<td class="text-center">
													<span class="badge badge-<?= $row->masuk > 10 ? 'danger' : ($row->masuk > 5 ? 'warning' : 'success') ?> badge-pill"><?= $row->masuk ?></span>
												</td>
												<td class="text-center">
													<?php
													// Fix division by zero error
													if (isset($stats->total_perkara) && $stats->total_perkara > 0) {
														$percentage = ($row->masuk / $stats->total_perkara) * 100;
														echo round($percentage, 1) . '%';
													} else {
														echo "0%";
														$percentage = 0;
													}
													?>
													<div class="progress progress-xs">
														<div class="progress-bar bg-primary" style="width: <?= $percentage ?>%"></div>
													</div>
												</td>
												<td>
													<a href="<?= site_url('Masuk/detail/' . str_replace(',', '-', $row->majelis_hakim_id)) ?>" class="btn btn-sm btn-info" data-toggle="tooltip" title="Lihat Detail Perkara">
														<i class="fas fa-eye"></i> Detail
													</a>
													<div class="btn-group">
														<button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown">
															<i class="fas fa-cog"></i>
														</button>
														<div class="dropdown-menu">
															<a class="dropdown-item" href="<?= site_url('Masuk/export_detail/' . str_replace(',', '-', $row->majelis_hakim_id)) ?>">
																<i class="fas fa-file-excel mr-2 text-success"></i> Export Excel
															</a>
															<a class="dropdown-item print-preview" href="#" data-id="<?= str_replace(',', '-', $row->majelis_hakim_id) ?>">
																<i class="fas fa-print mr-2 text-primary"></i> Print Preview
															</a>
														</div>
													</div>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>
							<div class="card-footer">
								<div class="float-right">
									<small class="text-muted">
										Jumlah data: <?= count($datafilter) ?> majelis hakim |
										Diperbarui: <?= date('d-m-Y H:i:s') ?>
									</small>
								</div>
							</div>
						</div>
					<?php else: ?>
						<!-- No data message -->
						<div class="alert alert-info">
							<h5><i class="icon fas fa-info"></i> Informasi</h5>
							<p>Silakan pilih periode dan jenis perkara untuk melihat data perkara masuk.</p>
							<?php if (isset($_POST['btn'])): ?>
								<p><strong>Data tidak ditemukan</strong> untuk periode dan filter yang dipilih.</p>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				</div>
			</section>
		</div>
	</div>

	<?php if (isset($stats) && !empty($datafilter)): ?>
		<script src="<?= base_url('assets/plugins/chart.js/Chart.min.js') ?>"></script>
		<script>
			$(document).ready(function() {
				// Initialize select2
				$('.select2').select2({
					theme: 'bootstrap4'
				});

				// Initialize DataTable
				$('#dataTable').DataTable({
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
					}
				});

				// Initialize Chart
				var ctx = document.getElementById('caseDistributionChart').getContext('2d');
				var caseDistributionChart = new Chart(ctx, {
					type: 'horizontalBar',
					data: {
						labels: [
							<?php
							$labels = [];
							foreach ($datafilter as $row) {
								// Truncate long names
								$name = strlen($row->majelis_hakim_nama) > 30 ?
									substr($row->majelis_hakim_nama, 0, 30) . '...' :
									$row->majelis_hakim_nama;
								$labels[] = "'" . addslashes($name) . "'";
							}
							echo implode(', ', $labels);
							?>
						],
						datasets: [{
							label: 'Jumlah Perkara',
							data: [
								<?php
								$data = [];
								foreach ($datafilter as $row) {
									$data[] = $row->masuk;
								}
								echo implode(', ', $data);
								?>
							],
							backgroundColor: [
								<?php
								$colors = [];
								foreach ($datafilter as $row) {
									if ($row->masuk > 10) {
										$colors[] = "'rgba(220, 53, 69, 0.7)'"; // Danger (red)
									} elseif ($row->masuk > 5) {
										$colors[] = "'rgba(255, 193, 7, 0.7)'"; // Warning (yellow)
									} else {
										$colors[] = "'rgba(40, 167, 69, 0.7)'"; // Success (green)
									}
								}
								echo implode(', ', $colors);
								?>
							],
							borderColor: [
								<?php
								$borderColors = [];
								foreach ($datafilter as $row) {
									if ($row->masuk > 10) {
										$borderColors[] = "'rgba(220, 53, 69, 1)'"; // Danger (red)
									} elseif ($row->masuk > 5) {
										$borderColors[] = "'rgba(255, 193, 7, 1)'"; // Warning (yellow)
									} else {
										$borderColors[] = "'rgba(40, 167, 69, 1)'"; // Success (green)
									}
								}
								echo implode(', ', $borderColors);
								?>
							],
							borderWidth: 1
						}]
					},
					options: {
						responsive: true,
						maintainAspectRatio: false,
						scales: {
							xAxes: [{
								ticks: {
									beginAtZero: true,
									stepSize: 1
								}
							}]
						}
					}
				});
			});
		</script>
	<?php endif; ?>
</body>

</html>