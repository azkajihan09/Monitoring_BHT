<body class="hold-transition sidebar-mini">
	<div class="wrapper">
		<div class="content-wrapper">
			<section class="content-header">
				<div class="container-fluid">
					<div class="row mb-2">
						<div class="col-sm-6">
							<h1 class="m-0 text-dark"><i class="fas fa-calendar-week mr-2"></i> Laporan Perkara Masuk Harian</h1>
						</div>
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								<li class="breadcrumb-item"><a href="<?= site_url('Admin/Dashboard') ?>">Home</a></li>
								<li class="breadcrumb-item">Perkara</li>
								<li class="breadcrumb-item active">Perkara Masuk Harian</li>
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
							<form action="<?php echo base_url() ?>index.php/Masuk_harian" method="POST" class="form-horizontal">
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

									<label class="col-sm-2 col-form-label">Periode:</label>
									<div class="col-sm-2">
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
											</div>
											<select name="lap_bulan" class="form-control select2" required>
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

									<div class="col-sm-2">
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text"><i class="fas fa-calendar"></i></span>
											</div>
											<select name="lap_tahun" class="form-control select2" required>
												<?php
												$current_year = date('Y');
												for ($year = $current_year; $year >= 2020; $year--) {
													$selected = (isset($_POST['lap_tahun']) && $_POST['lap_tahun'] == $year) ? 'selected' : '';
													echo "<option value='$year' $selected>$year</option>";
												}
												?>
											</select>
										</div>
									</div>
								</div>

								<div class="form-group row">
									<label class="col-sm-2 col-form-label">Cari Majelis:</label>
									<div class="col-sm-4">
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text"><i class="fas fa-search"></i></span>
											</div>
											<input type="text" name="search" class="form-control" placeholder="Nama Hakim..." value="<?php echo isset($_POST['search']) ? $_POST['search'] : ''; ?>">
										</div>
									</div>

									<div class="col-sm-4">
										<button type="submit" name="btn" class="btn btn-primary mr-2">
											<i class="fas fa-search mr-1"></i> Tampilkan Data
										</button>
										<?php if (!empty($datafilter)): ?>
											<a href="<?= base_url('index.php/Masuk_harian/export_excel/' . (isset($_POST['jenis_perkara']) ? $_POST['jenis_perkara'] : 'Pdt.G') . '/' . (isset($_POST['lap_bulan']) ? $_POST['lap_bulan'] : date('m')) . '/' . (isset($_POST['lap_tahun']) ? $_POST['lap_tahun'] : date('Y')) . (isset($_POST['search']) && !empty($_POST['search']) ? '/' . urlencode($_POST['search']) : '')) ?>" class="btn btn-success">
												<i class="fas fa-file-excel mr-1"></i> Export Excel
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
										<small>Hari kerja (Sen-Jum) <i class="fas fa-info-circle" title="Hanya menghitung hari Senin sampai Jumat"></i></small>
									</div>
									<div class="icon">
										<i class="fas fa-gavel"></i>
									</div>
								</div>
							</div>

							<div class="col-lg-3 col-6">
								<div class="small-box bg-success">
									<div class="inner">
										<h3><?= $stats->total_majelis ?></h3>
										<p>Jumlah Majelis</p>
										<small>Rata-rata: <?= number_format($stats->avg_per_majelis, 1) ?> perkara/majelis</small>
									</div>
									<div class="icon">
										<i class="fas fa-users"></i>
									</div>
								</div>
							</div>

							<div class="col-lg-3 col-6">
								<div class="small-box bg-warning">
									<div class="inner">
										<h3><?= number_format($stats->avg_daily, 1) ?></h3>
										<p>Rata-rata Harian</p>
										<small>Hari tersibuk: <?= $stats->busiest_day ?> (<?= $stats->busiest_day_count ?> perkara)</small>
									</div>
									<div class="icon">
										<i class="fas fa-calendar-day"></i>
									</div>
								</div>
							</div>

							<div class="col-lg-3 col-6">
								<div class="small-box bg-danger">
									<div class="inner">
										<h3><?= $stats->max_load ?></h3>
										<p>Beban Terbanyak</p>
										<small><?= $stats->max_load_majelis ?></small>
									</div>
									<div class="icon">
										<i class="fas fa-chart-line"></i>
									</div>
								</div>
							</div>
						</div>

						<!-- Daily Distribution Chart -->
						<div class="row">
							<div class="col-md-6">
								<div class="card card-primary">
									<div class="card-header">
										<h3 class="card-title"><i class="fas fa-chart-bar mr-1"></i> Distribusi Harian</h3>
									</div>
									<div class="card-body">
										<canvas id="dailyChart" style="height: 300px;"></canvas>
									</div>
								</div>
							</div>

							<div class="col-md-6">
								<div class="card card-info">
									<div class="card-header">
										<h3 class="card-title"><i class="fas fa-chart-pie mr-1"></i> Distribusi per Majelis</h3>
									</div>
									<div class="card-body">
										<canvas id="majelisChart" style="height: 300px;"></canvas>
									</div>
								</div>
							</div>
						</div>

						<!-- Data Table -->
						<div class="card card-primary card-outline">
							<div class="card-header">
								<h3 class="card-title"><i class="fas fa-table mr-1"></i> Data Perkara Masuk per Majelis per Hari</h3>
							</div>
							<div class="card-body">
								<div class="table-responsive">
									<table id="dataTable" class="table table-bordered table-striped table-hover">
										<thead class="thead-dark">
											<tr>
												<th style="width: 5%;">No</th>
												<th style="width: 35%;">Majelis Hakim</th>
												<th style="width: 10%;">Senin</th>
												<th style="width: 10%;">Selasa</th>
												<th style="width: 10%;">Rabu</th>
												<th style="width: 10%;">Kamis</th>
												<th style="width: 10%;">Jumat</th>
												<th style="width: 10%;">Total</th>
											</tr>
										</thead>
										<tbody>
											<?php
											$no = 1;
											foreach ($datafilter as $row) :
											?>
												<tr>
													<td class="text-center"><?= $no++ ?></td>
													<td>
														<strong><?= $row->majelis_hakim_nama ?></strong>
														<br><small class="text-muted">Kode: <?= $row->majelis_hakim_kode ?></small>
													</td>
													<td class="text-center">
														<?php if ($row->senin > 0): ?>
															<a href="<?= base_url('index.php/Masuk_harian/detail/' . $row->majelis_hakim_id . '/1?jenis_perkara=' . (isset($_POST['jenis_perkara']) ? $_POST['jenis_perkara'] : 'Pdt.G') . '&lap_bulan=' . (isset($_POST['lap_bulan']) ? $_POST['lap_bulan'] : date('m')) . '&lap_tahun=' . (isset($_POST['lap_tahun']) ? $_POST['lap_tahun'] : date('Y'))) ?>" class="btn btn-sm btn-info">
																<?= $row->senin ?>
															</a>
														<?php else: ?>
															<span class="text-muted">0</span>
														<?php endif; ?>
													</td>
													<td class="text-center">
														<?php if ($row->selasa > 0): ?>
															<a href="<?= base_url('index.php/Masuk_harian/detail/' . $row->majelis_hakim_id . '/2?jenis_perkara=' . (isset($_POST['jenis_perkara']) ? $_POST['jenis_perkara'] : 'Pdt.G') . '&lap_bulan=' . (isset($_POST['lap_bulan']) ? $_POST['lap_bulan'] : date('m')) . '&lap_tahun=' . (isset($_POST['lap_tahun']) ? $_POST['lap_tahun'] : date('Y'))) ?>" class="btn btn-sm btn-info">
																<?= $row->selasa ?>
															</a>
														<?php else: ?>
															<span class="text-muted">0</span>
														<?php endif; ?>
													</td>
													<td class="text-center">
														<?php if ($row->rabu > 0): ?>
															<a href="<?= base_url('index.php/Masuk_harian/detail/' . $row->majelis_hakim_id . '/3?jenis_perkara=' . (isset($_POST['jenis_perkara']) ? $_POST['jenis_perkara'] : 'Pdt.G') . '&lap_bulan=' . (isset($_POST['lap_bulan']) ? $_POST['lap_bulan'] : date('m')) . '&lap_tahun=' . (isset($_POST['lap_tahun']) ? $_POST['lap_tahun'] : date('Y'))) ?>" class="btn btn-sm btn-info">
																<?= $row->rabu ?>
															</a>
														<?php else: ?>
															<span class="text-muted">0</span>
														<?php endif; ?>
													</td>
													<td class="text-center">
														<?php if ($row->kamis > 0): ?>
															<a href="<?= base_url('index.php/Masuk_harian/detail/' . $row->majelis_hakim_id . '/4?jenis_perkara=' . (isset($_POST['jenis_perkara']) ? $_POST['jenis_perkara'] : 'Pdt.G') . '&lap_bulan=' . (isset($_POST['lap_bulan']) ? $_POST['lap_bulan'] : date('m')) . '&lap_tahun=' . (isset($_POST['lap_tahun']) ? $_POST['lap_tahun'] : date('Y'))) ?>" class="btn btn-sm btn-info">
																<?= $row->kamis ?>
															</a>
														<?php else: ?>
															<span class="text-muted">0</span>
														<?php endif; ?>
													</td>
													<td class="text-center">
														<?php if ($row->jumat > 0): ?>
															<a href="<?= base_url('index.php/Masuk_harian/detail/' . $row->majelis_hakim_id . '/5?jenis_perkara=' . (isset($_POST['jenis_perkara']) ? $_POST['jenis_perkara'] : 'Pdt.G') . '&lap_bulan=' . (isset($_POST['lap_bulan']) ? $_POST['lap_bulan'] : date('m')) . '&lap_tahun=' . (isset($_POST['lap_tahun']) ? $_POST['lap_tahun'] : date('Y'))) ?>" class="btn btn-sm btn-info">
																<?= $row->jumat ?>
															</a>
														<?php else: ?>
															<span class="text-muted">0</span>
														<?php endif; ?>
													</td>
													<td class="text-center">
														<strong class="text-primary"><?= $row->total_masuk ?></strong>
													</td>
												</tr>
											<?php endforeach; ?>
										</tbody>
										<tfoot>
											<tr class="bg-light font-weight-bold">
												<td colspan="2" class="text-center">TOTAL</td>
												<td class="text-center"><?= $stats->total_senin ?></td>
												<td class="text-center"><?= $stats->total_selasa ?></td>
												<td class="text-center"><?= $stats->total_rabu ?></td>
												<td class="text-center"><?= $stats->total_kamis ?></td>
												<td class="text-center"><?= $stats->total_jumat ?></td>
												<td class="text-center text-primary"><?= $stats->total_perkara ?></td>
											</tr>
										</tfoot>
									</table>
								</div>
							</div>
						</div>
					<?php elseif (isset($_POST['btn'])): ?>
						<div class="alert alert-info text-center">
							<i class="fas fa-info-circle fa-2x mb-3"></i>
							<h5>Tidak ada data yang ditemukan</h5>
							<p>Silakan coba dengan filter yang berbeda atau periode lain.</p>
						</div>
					<?php else: ?>
						<div class="alert alert-warning text-center">
							<i class="fas fa-search fa-2x mb-3"></i>
							<h5>Silakan pilih filter dan klik "Tampilkan Data"</h5>
							<p>Gunakan filter di atas untuk menampilkan data perkara masuk harian per majelis.</p>
						</div>
					<?php endif; ?>
				</div>
			</section>
		</div>
	</div>

	<!-- Chart.js -->
	<script src="<?= base_url('assets/plugins/chart.js/Chart.min.js') ?>"></script>
	<script>
		$(document).ready(function() {
			// Initialize select2
			$('.select2').select2({
				theme: 'bootstrap4'
			});

			<?php if (!empty($datafilter)): ?>
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

				// Daily Distribution Chart
				if (typeof Chart !== 'undefined' && document.getElementById('dailyChart')) {
					var dailyCtx = document.getElementById('dailyChart').getContext('2d');
					var dailyChart = new Chart(dailyCtx, {
						type: 'bar',
						data: {
							labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'],
							datasets: [{
								label: 'Jumlah Perkara',
								data: [
									<?= $stats->total_senin ?>,
									<?= $stats->total_selasa ?>,
									<?= $stats->total_rabu ?>,
									<?= $stats->total_kamis ?>,
									<?= $stats->total_jumat ?>
								],
								backgroundColor: [
									'rgba(54, 162, 235, 0.7)',
									'rgba(255, 99, 132, 0.7)',
									'rgba(255, 205, 86, 0.7)',
									'rgba(75, 192, 192, 0.7)',
									'rgba(153, 102, 255, 0.7)'
								],
								borderColor: [
									'rgba(54, 162, 235, 1)',
									'rgba(255, 99, 132, 1)',
									'rgba(255, 205, 86, 1)',
									'rgba(75, 192, 192, 1)',
									'rgba(153, 102, 255, 1)'
								],
								borderWidth: 1
							}]
						},
						options: {
							responsive: true,
							maintainAspectRatio: false,
							scales: {
								yAxes: [{
									ticks: {
										beginAtZero: true,
										stepSize: 1
									}
								}]
							},
							tooltips: {
								callbacks: {
									label: function(tooltipItem, data) {
										return data.datasets[tooltipItem.datasetIndex].label + ': ' + tooltipItem.yLabel + ' perkara';
									}
								}
							}
						}
					});
				}

				// Majelis Distribution Chart (Top 10)
				if (typeof Chart !== 'undefined' && document.getElementById('majelisChart')) {
					var majelisCtx = document.getElementById('majelisChart').getContext('2d');
					
					// Get top 10 majelis data
					var majelisLabels = [
						<?php
						if (!empty($datafilter)) {
							$top10 = array_slice($datafilter, 0, 10);
							$labels = [];
							foreach ($top10 as $row) {
								$name = strlen($row->hakim_ketua) > 20 ?
									substr($row->hakim_ketua, 0, 20) . '...' :
									$row->hakim_ketua;
								$labels[] = "'" . addslashes($name) . "'";
							}
							echo implode(', ', $labels);
						}
						?>
					];
					
					var majelisData = [
						<?php
						if (!empty($datafilter)) {
							$top10 = array_slice($datafilter, 0, 10);
							$data = [];
							foreach ($top10 as $row) {
								$data[] = $row->total_masuk;
							}
							echo implode(', ', $data);
						}
						?>
					];
					
					var majelisChart = new Chart(majelisCtx, {
						type: 'doughnut',
						data: {
							labels: majelisLabels,
							datasets: [{
								label: 'Jumlah Perkara',
								data: majelisData,
								backgroundColor: [
									'#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
									'#FF9F40', '#FF6384', '#C9CBCF', '#4BC0C0', '#FF6384'
								]
							}]
						},
						options: {
							responsive: true,
							maintainAspectRatio: false,
							legend: {
								position: 'bottom',
								labels: {
									fontSize: 10
								}
							},
							tooltips: {
								callbacks: {
									label: function(tooltipItem, data) {
										var label = data.labels[tooltipItem.index];
										var value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
										return label + ': ' + value + ' perkara';
									}
								}
							}
						}
					});
				}
			<?php endif; ?>
		});
	</script>
</body>