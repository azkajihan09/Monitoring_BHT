<body class="hold-transition sidebar-mini">
	<div class="wrapper">
		<div class="content-wrapper">
			<section class="content-header">
				<div class="container-fluid">
					<div class="row mb-2">
						<div class="col-sm-6">
							<h1 class="m-0 text-dark"><i class="fas fa-business-time mr-2"></i>One Day Publish</h1>
						</div>
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								<li class="breadcrumb-item"><a href="<?= site_url('Admin/Dashboard') ?>">Home</a></li>
								<li class="breadcrumb-item">One Day Service</li>
								<li class="breadcrumb-item active">One Day Publish</li>
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
							<form action="<?= base_url() ?>index.php/Odp" method="POST" class="form-horizontal">
								<div class="form-group row">
									<label class="col-sm-2 col-form-label">Jenis Laporan:</label>
									<div class="col-sm-10">
										<div class="custom-control custom-radio custom-control-inline">
											<input type="radio" id="laporan_bulanan" name="jenis_filter" value="bulanan" class="custom-control-input" <?= (!isset($jenis_filter) || (isset($jenis_filter) && $jenis_filter === 'bulanan')) ? 'checked' : '' ?>>
											<label class="custom-control-label" for="laporan_bulanan">Laporan Bulanan</label>
										</div>
										<div class="custom-control custom-radio custom-control-inline">
											<input type="radio" id="laporan_tahunan" name="jenis_filter" value="tahunan" class="custom-control-input" <?= (isset($jenis_filter) && $jenis_filter === 'tahunan') ? 'checked' : '' ?>>
											<label class="custom-control-label" for="laporan_tahunan">Laporan Tahunan</label>
										</div>
									</div>
								</div>
								<div class="form-group row" id="bulan_container">
									<label class="col-sm-2 col-form-label">Bulan:</label>
									<div class="col-sm-4">
										<select name="lap_bulan" class="form-control select2" id="lap_bulan">
											<option value="">-- Pilih Bulan --</option>
											<?php
											foreach ($nama_bulan as $value => $label) {
												$selected = (isset($lap_bulan) && $lap_bulan == $value) ? 'selected="selected"' : '';
												echo "<option value=\"$value\" $selected>$label</option>";
											}
											?>
										</select>
									</div>
									<label class="col-sm-2 col-form-label">Tahun:</label>
									<div class="col-sm-4">
										<select name="lap_tahun" class="form-control select2" required>
											<option value="">-- Pilih Tahun --</option>
											<?php
											$currentYear = date('Y');
											for ($year = 2016; $year <= $currentYear + 1; $year++) {
												$selected = (isset($lap_tahun) && $lap_tahun == $year) ? 'selected="selected"' : '';
												echo "<option value=\"$year\" $selected>$year</option>";
											}
											?>
										</select>
									</div>
								</div>
								<div class="form-group row">
									<div class="col-sm-4 offset-sm-8">
										<button type="submit" name="btn" value="Tampilkan" class="btn btn-primary btn-block">
											<i class="fas fa-search mr-2"></i> Tampilkan Data
										</button>
									</div>
								</div>
							</form>
						</div>
					</div>

					<?php if (!empty($datafilter)): ?>
						<!-- Export Buttons Row - Add directly below the filter card -->
						<div class="row mb-3">
							<div class="col-md-12">
								<a href="<?= site_url('Odp/export_excel/' . (isset($lap_bulan) ? $lap_bulan : 'all') . '/' . $lap_tahun) ?>" class="btn btn-success">
									<i class="fas fa-file-excel mr-2"></i> Export ke Excel
								</a>
								<span class="text-muted ml-2">
									<i class="fas fa-info-circle"></i>
									Klik tombol untuk mengunduh data dalam format Excel
								</span>
							</div>
						</div>

						<!-- Statistics Cards -->
						<div class="row">
							<div class="col-lg-3 col-6">
								<div class="small-box bg-info">
									<div class="inner">
										<h3><?= isset($stats->total_putus) ? $stats->total_putus : 0 ?></h3>
										<p>Total Perkara Putus</p>
									</div>
									<div class="icon">
										<i class="fas fa-gavel"></i>
									</div>
									<a href="#" class="small-box-footer">
										<?php if (!empty($lap_bulan)): ?>
											Periode: <?= $nama_bulan[$lap_bulan] ?> <?= $lap_tahun ?>
										<?php else: ?>
											Periode: Tahun <?= $lap_tahun ?>
										<?php endif; ?>
										<i class="fas fa-calendar-alt mx-1"></i>
									</a>
								</div>
							</div>

							<div class="col-lg-3 col-6">
								<div class="small-box bg-success">
									<div class="inner">
										<h3><?= isset($stats->total_publish) ? $stats->total_publish : 0 ?></h3>
										<p>Total Terpublikasi</p>
									</div>
									<div class="icon">
										<i class="fas fa-cloud-upload-alt"></i>
									</div>
									<a href="#" class="small-box-footer">
										<?= isset($stats->total_publish) && isset($stats->total_putus) ?
											round(($stats->total_publish / $stats->total_putus) * 100, 1) . '%' :
											'0%' ?> dari total perkara
										<i class="fas fa-info-circle mx-1"></i>
									</a>
								</div>
							</div>

							<div class="col-lg-3 col-6">
								<div class="small-box bg-warning">
									<div class="inner">
										<h3><?= isset($stats->total_odp_same_day) ? $stats->total_odp_same_day : 0 ?></h3>
										<p>One Day Publish</p>
									</div>
									<div class="icon">
										<i class="fas fa-bolt"></i>
									</div>
									<a href="#" class="small-box-footer">
										<?= isset($stats->pct_odp_same_day) ? $stats->pct_odp_same_day . '%' : '0%' ?> dari total perkara
										<i class="fas fa-info-circle mx-1"></i>
									</a>
								</div>
							</div>

							<div class="col-lg-3 col-6">
								<div class="small-box bg-danger">
									<div class="inner">
										<h3><?= isset($stats->avg_publish_days) ? round($stats->avg_publish_days, 1) : '-' ?></h3>
										<p>Rata-rata Hari Publikasi</p>
									</div>
									<div class="icon">
										<i class="fas fa-clock"></i>
									</div>
									<a href="#" class="small-box-footer">
										Hari sejak putusan
										<i class="fas fa-info-circle mx-1"></i>
									</a>
								</div>
							</div>
						</div>

						<!-- Chart Row -->
						<div class="row">
							<?php if (isset($jenis_filter) && $jenis_filter === 'tahunan' && !empty($monthly_performance)): ?>
								<!-- Monthly Performance -->
								<div class="col-md-8">
									<div class="card card-primary">
										<div class="card-header">
											<h3 class="card-title">
												<i class="fas fa-chart-line mr-1"></i>
												Performa ODP Bulanan Tahun <?= $lap_tahun ?>
											</h3>
											<div class="card-tools">
												<button type="button" class="btn btn-tool" data-card-widget="collapse">
													<i class="fas fa-minus"></i>
												</button>
											</div>
										</div>
										<div class="card-body">
											<div id="monthlyPerformanceChart" style="height: 300px;"></div>
										</div>
									</div>
								</div>
							<?php endif; ?>

							<!-- Case Type Distribution -->
							<div class="<?= (isset($jenis_filter) && $jenis_filter === 'tahunan') ? 'col-md-4' : 'col-md-6' ?>">
								<div class="card card-success">
									<div class="card-header">
										<h3 class="card-title">
											<i class="fas fa-chart-pie mr-1"></i>
											Distribusi Jenis Perkara
										</h3>
										<div class="card-tools">
											<button type="button" class="btn btn-tool" data-card-widget="collapse">
												<i class="fas fa-minus"></i>
											</button>
										</div>
									</div>
									<div class="card-body">
										<?php if (!empty($perkara_distribution)): ?>
											<div id="perkaraDistributionChart" style="height: 300px;"></div>
										<?php else: ?>
											<div class="alert alert-warning">
												<i class="fas fa-info-circle"></i> Tidak ada data untuk ditampilkan.
											</div>
										<?php endif; ?>
									</div>
								</div>
							</div>

							<?php if (!(isset($jenis_filter) && $jenis_filter === 'tahunan')): ?>
								<!-- ODP Status -->
								<div class="col-md-6">
									<div class="card card-info">
										<div class="card-header">
											<h3 class="card-title">
												<i class="fas fa-chart-bar mr-1"></i>
												Status One Day Publish
											</h3>
											<div class="card-tools">
												<button type="button" class="btn btn-tool" data-card-widget="collapse">
													<i class="fas fa-minus"></i>
												</button>
											</div>
										</div>
										<div class="card-body">
											<div id="odpStatusChart" style="height: 300px;"></div>
										</div>
									</div>
								</div>
							<?php endif; ?>
						</div>

						<!-- Additional Info Card -->
						<div class="card bg-gradient-info">
							<div class="card-header">
								<h3 class="card-title">
									<i class="fas fa-info-circle mr-1"></i>
									Informasi One Day Publish
								</h3>
								<div class="card-tools">
									<button type="button" class="btn btn-tool" data-card-widget="collapse">
										<i class="fas fa-minus"></i>
									</button>
								</div>
							</div>
							<div class="card-body">
								<p>One Day Publish (ODP) adalah layanan percepatan publikasi putusan/penetapan perkara pada hari yang sama dengan hari diputuskannya perkara. Layanan ini bertujuan untuk:</p>
								<ul>
									<li>Meningkatkan kecepatan layanan informasi kepada para pencari keadilan</li>
									<li>Mempercepat publikasi putusan untuk kepentingan transparansi peradilan</li>
									<li>Memastikan putusan pengadilan dapat diakses oleh publik secara cepat melalui Direktori Putusan</li>
								</ul>
								<div class="alert alert-light">
									<i class="fas fa-exclamation-circle mr-2"></i> Perkara yang tidak dipublikasi pada hari yang sama dengan hari putusan, tidak dianggap sebagai ODP dan memerlukan tindak lanjut lebih cepat.
								</div>
							</div>
						</div>

						<!-- Main Data Card -->
						<div class="card card-outline card-primary">
							<div class="card-header bg-light">
								<h3 class="card-title">
									<i class="fas fa-table mr-1"></i>
									Data One Day Publish
									<?php if (!empty($lap_bulan)): ?>
										- <?= $nama_bulan[$lap_bulan] ?> <?= $lap_tahun ?>
									<?php else: ?>
										- Tahun <?= $lap_tahun ?>
									<?php endif; ?>
								</h3>
								<div class="card-tools">
									<button type="button" class="btn btn-tool" data-card-widget="collapse">
										<i class="fas fa-minus"></i>
									</button>
									<button type="button" class="btn btn-tool" data-card-widget="maximize">
										<i class="fas fa-expand"></i>
									</button>
									<div class="btn-group ml-2">
										<button type="button" class="btn btn-sm btn-success dropdown-toggle" data-toggle="dropdown">
											<i class="fas fa-download"></i> Export
										</button>
										<div class="dropdown-menu dropdown-menu-right">
											<a href="#" class="dropdown-item export-excel">
												<i class="fas fa-file-excel mr-2"></i> Excel
											</a>
											<a href="#" class="dropdown-item export-pdf">
												<i class="fas fa-file-pdf mr-2"></i> PDF
											</a>
										</div>
									</div>
								</div>
							</div>
							<div class="card-body p-0">
								<div class="table-responsive">
									<table id="dataTable" class="table table-bordered table-striped table-hover">
										<thead>
											<tr>
												<th class="text-center" width="5%">No</th>
												<th width="15%">Nomor Perkara</th>
												<th width="15%">Jenis Perkara</th>
												<th width="12%">Tanggal Putus</th>
												<th width="12%">Tanggal Minutasi</th>
												<th width="12%">Tanggal Publish</th>
												<th width="8%">Selisih Hari</th>
												<th width="8%">Status ODP</th>
												<th width="13%">Aksi</th>
											</tr>
										</thead>
										<tbody>
											<?php
											$no = 1;
											foreach ($datafilter as $row):
												// Determine row class based on ODP status
												$rowClass = '';
												$badgeClass = 'badge-secondary';
												$badgeText = 'Tidak';

												if ($row->is_odp === 'Ya') {
													$rowClass = 'table-success';
													$badgeClass = 'badge-success';
													$badgeText = 'ODP';
												} elseif ($row->is_odp === 'Ya (1 Hari)') {
													$rowClass = 'table-info';
													$badgeClass = 'badge-info';
													$badgeText = 'ODP (1 Hari)';
												}
											?>
												<tr class="<?= $rowClass ?>">
													<td class="text-center"><?= $no++ ?></td>
													<td><?= $row->nomor_perkara ?></td>
													<td><?= $row->jenis_perkara_nama ?></td>
													<td><?= date('d-m-Y', strtotime($row->tanggal_putusan)) ?></td>
													<td><?= !empty($row->tanggal_minutasi) ? date('d-m-Y', strtotime($row->tanggal_minutasi)) : '<span class="text-danger">-</span>' ?></td>
													<td><?= date('d-m-Y', strtotime($row->tanggal_publish)) ?></td>
													<td class="text-center">
														<?php if ($row->selisih_hari <= 0): ?>
															<span class="badge badge-success">0</span>
														<?php elseif ($row->selisih_hari <= 1): ?>
															<span class="badge badge-info">1</span>
														<?php elseif ($row->selisih_hari <= 3): ?>
															<span class="badge badge-warning"><?= $row->selisih_hari ?></span>
														<?php else: ?>
															<span class="badge badge-danger"><?= $row->selisih_hari ?></span>
														<?php endif; ?>
													</td>
													<td class="text-center">
														<span class="badge <?= $badgeClass ?>"><?= $badgeText ?></span>
													</td>
													<td>
														<?php if (isset($row->perkara_id)): ?>
															<a href="<?= site_url('Odp/detail/' . $row->perkara_id) ?>" class="btn btn-xs btn-success" target="_blank" data-toggle="tooltip" title="Lihat Detail Lengkap">
																<i class="fas fa-eye"></i>
															</a>
														<?php endif; ?>

														<?php if (!empty($row->link_dirput)): ?>
															<a href="<?= $row->link_dirput ?>" class="btn btn-xs btn-info" target="_blank" data-toggle="tooltip" title="Lihat Putusan">
																<i class="fas fa-file-pdf"></i>
															</a>
														<?php else: ?>
															<a href="https://putusan3.mahkamahagung.go.id/search.html?q=<?= $row->nomor_perkara ?>" class="btn btn-xs btn-secondary" target="_blank" data-toggle="tooltip" title="Cari di Direktori Putusan">
																<i class="fas fa-search"></i>
															</a>
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
										<ul class="list-inline ml-4 mb-0">
											<li class="list-inline-item"><span class="badge badge-success">ODP</span> = Dipublikasi hari yang sama dengan putusan</li>
											<li class="list-inline-item"><span class="badge badge-info">ODP (1 Hari)</span> = Dipublikasi 1 hari setelah putusan</li>
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
						<div class="alert alert-warning alert-dismissible">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
							<h5><i class="icon fas fa-exclamation-triangle"></i> Tidak Ada Data</h5>
							<p>Tidak ditemukan data ODP pada periode yang dipilih. Silahkan pilih periode lainnya.</p>
						</div>
					<?php endif; ?>
				</div>
			</section>
		</div>
	</div>

	<!-- Detail Modal -->
	<div class="modal fade" id="detailModal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header bg-primary">
					<h4 class="modal-title">Detail Publikasi Putusan</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<table class="table table-bordered table-striped">
						<tr>
							<th style="width: 40%">Nomor Perkara</th>
							<td id="detail-nomor"></td>
						</tr>
						<tr>
							<th>Jenis Perkara</th>
							<td id="detail-jenis"></td>
						</tr>
						<tr>
							<th>Tanggal Putusan</th>
							<td id="detail-putus"></td>
						</tr>
						<tr>
							<th>Tanggal Minutasi</th>
							<td id="detail-minutasi"></td>
						</tr>
						<tr>
							<th>Tanggal Publikasi</th>
							<td id="detail-publish"></td>
						</tr>
						<tr>
							<th>Selisih Hari</th>
							<td id="detail-selisih"></td>
						</tr>
						<tr>
							<th>Status ODP</th>
							<td id="detail-status"></td>
						</tr>
						<tr>
							<th>Nama File</th>
							<td id="detail-filename"></td>
						</tr>
					</table>
				</div>
				<div class="modal-footer justify-content-between">
					<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
				</div>
			</div>
		</div>
	</div>

	<script src="<?= base_url() ?>assets/plugins/chart.js/Chart.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
	<script>
		$(function() {
			// Initialize Select2
			$('.select2').select2({
				theme: 'bootstrap4'
			});

			// Handle report type toggle
			function toggleBulanField() {
				if ($("#laporan_tahunan").is(":checked")) {
					$("#bulan_container").hide();
					$("#lap_bulan").prop("required", false);
					$("#lap_bulan").prop("disabled", true);
				} else {
					$("#bulan_container").show();
					$("#lap_bulan").prop("required", true);
					$("#lap_bulan").prop("disabled", false);
				}
			}

			// Initialize state and listen for changes
			toggleBulanField();
			$("input[name='jenis_filter']").change(function() {
				toggleBulanField();
			});

			// Initialize DataTables
			$("#dataTable").DataTable({
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
						title: 'Data One Day Publish <?= !empty($lap_bulan) ? $nama_bulan[$lap_bulan] . " " . $lap_tahun : "Tahun " . $lap_tahun ?>',
						exportOptions: {
							columns: [0, 1, 2, 3, 4, 5, 6, 7]
						},
						className: 'btn-success'
					},
					{
						extend: 'pdf',
						text: 'PDF',
						title: 'Data One Day Publish <?= !empty($lap_bulan) ? $nama_bulan[$lap_bulan] . " " . $lap_tahun : "Tahun " . $lap_tahun ?>',
						exportOptions: {
							columns: [0, 1, 2, 3, 4, 5, 6, 7]
						},
						className: 'btn-danger',
						orientation: 'landscape'
					}
				]
			}).buttons().container().appendTo('#dataTable_wrapper .col-md-6:eq(0)');

			// Export buttons binding
			$('.export-excel').click(function(e) {
				e.preventDefault();
				$('.buttons-excel').click();
			});

			$('.export-pdf').click(function(e) {
				e.preventDefault();
				$('.buttons-pdf').click();
			});

			// View detail handler
			$('.view-detail').click(function() {
				$('#detail-nomor').text($(this).data('nomor'));
				$('#detail-jenis').text($(this).data('jenis'));
				$('#detail-putus').text($(this).data('putus'));
				$('#detail-minutasi').text($(this).data('minutasi'));
				$('#detail-publish').text($(this).data('publish'));
				$('#detail-selisih').text($(this).data('selisih') + ' hari');
				$('#detail-status').text($(this).data('status'));
				$('#detail-filename').text($(this).data('filename'));
				$('#detailModal').modal('show');
			});

			// Initialize tooltips
			$('[data-toggle="tooltip"]').tooltip();

			<?php if (!empty($datafilter)): ?>
				// Initialize charts using ChartHelper
				setTimeout(function() {
					// Debug canvas elements
					ChartHelper.debugCanvas('perkaraDistributionChart');
					ChartHelper.debugCanvas('odpStatusChart');
					ChartHelper.debugCanvas('monthlyPerformanceChart');

					<?php if (isset($jenis_filter) && $jenis_filter === 'tahunan' && !empty($monthly_performance)): ?>
						// Monthly Performance Chart
						if (document.getElementById('monthlyPerformanceChart')) {
							var monthlyData = {
								labels: [
									'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
									'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'
								],
								datasets: [{
										label: 'Total Perkara',
										backgroundColor: 'rgba(60, 141, 188, 0.3)',
										borderColor: 'rgba(60, 141, 188, 1)',
										pointRadius: 3,
										pointBackgroundColor: 'rgba(60, 141, 188, 1)',
										pointBorderColor: '#fff',
										pointHoverRadius: 5,
										pointHoverBackgroundColor: '#fff',
										pointHoverBorderColor: 'rgba(60, 141, 188, 1)',
										data: [
											<?php
											$monthData = array_fill(1, 12, 0);
											if (!empty($monthly_performance)) {
												foreach ($monthly_performance as $item) {
													$monthData[(int)$item->month_num] = $item->total_putus;
												}
											}
											echo implode(', ', $monthData);
											?>
										],
										type: 'line',
										fill: false
									},
									{
										label: 'One Day Publish',
										backgroundColor: 'rgba(40, 167, 69, 0.7)',
										borderColor: 'rgba(40, 167, 69, 1)',
										borderWidth: 1,
										data: [
											<?php
											$monthData = array_fill(1, 12, 0);
											if (!empty($monthly_performance)) {
												foreach ($monthly_performance as $item) {
													$monthData[(int)$item->month_num] = $item->total_odp_same_day;
												}
											}
											echo implode(', ', $monthData);
											?>
										]
									}
								]
							};

							var options = {
								responsive: true,
								maintainAspectRatio: false,
								scales: {
									yAxes: [{
										ticks: {
											beginAtZero: true
										}
									}]
								}
							};

							ChartHelper.initChart('monthlyPerformanceChart', 'bar', monthlyData, options);
						}
					<?php endif; ?>

					<?php if (!empty($perkara_distribution)): ?>
						// Case Type Distribution Chart
						if (document.getElementById('perkaraDistributionChart')) {
							var perkaraData = {
								labels: [
									<?php
									$types = [];
									foreach ($perkara_distribution as $item) {
										$types[] = '"' . $item->jenis_perkara_nama . '"';
									}
									echo implode(', ', $types);
									?>
								],
								datasets: [{
									data: [
										<?php
										$counts = [];
										foreach ($perkara_distribution as $item) {
											$counts[] = $item->total_cases;
										}
										echo implode(', ', $counts);
										?>
									],
									backgroundColor: [
										'#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de',
										'#e83e8c', '#6610f2', '#6f42c1', '#fd7e14', '#20c997', '#17a2b8'
									]
								}]
							};

							var options = {
								responsive: true,
								maintainAspectRatio: false,
								legend: {
									position: '<?= (isset($jenis_filter) && $jenis_filter === 'tahunan') ? "right" : "bottom" ?>'
								}
							};

							ChartHelper.initChart('perkaraDistributionChart', 'doughnut', perkaraData, options);
						}
					<?php endif; ?>

					<?php if (!(isset($jenis_filter) && $jenis_filter === 'tahunan')): ?>
						// ODP Status Chart
						if (document.getElementById('odpStatusChart')) {
							var odpData = {
								labels: ['ODP (Hari Sama)', 'ODP (1 Hari)', 'Tidak ODP'],
								datasets: [{
									data: [
										<?= isset($stats->total_odp_same_day) ? $stats->total_odp_same_day : 0 ?>,
										<?= isset($stats->total_odp_one_day) && isset($stats->total_odp_same_day) ?
											$stats->total_odp_one_day - $stats->total_odp_same_day : 0 ?>,
										<?= isset($stats->total_putus) && isset($stats->total_odp_one_day) ?
											$stats->total_putus - $stats->total_odp_one_day : 0 ?>
									],
									backgroundColor: ['#28a745', '#17a2b8', '#dc3545']
								}]
							};

							var options = {
								responsive: true,
								maintainAspectRatio: false,
								legend: {
									position: 'bottom'
								}
							};

							ChartHelper.initChart('odpStatusChart', 'pie', odpData, options);
						}
					<?php endif; ?>
				}, 800); // Larger delay to ensure DOM is fully ready

				// Export to Excel
				$('#exportExcel').click(function() {
					exportTimelineToExcel();
				});

				// Function to export timeline to Excel
				function exportTimelineToExcel() {
					// Create workbook and worksheet
					const wb = XLSX.utils.book_new();
					wb.Props = {
						Title: "Timeline Perkara <?= isset($perkara->nomor_perkara) ? $perkara->nomor_perkara : '' ?>",
						Subject: "Timeline",
						Author: "SIPP",
						CreatedDate: new Date()
					};

					// Create worksheet
					const ws = XLSX.utils.aoa_to_sheet([]);

					// Add perkara information
					XLSX.utils.sheet_add_aoa(ws, [
						["TIMELINE PERKARA"],
						[""],
						["Informasi Perkara:"],
						["Nomor Perkara:", "<?= isset($perkara->nomor_perkara) ? $perkara->nomor_perkara : '-' ?>"],
						["Jenis Perkara:", "<?= isset($perkara->jenis_perkara_nama) ? $perkara->jenis_perkara_nama : '-' ?>"],
						["Tanggal Daftar:", "<?= isset($perkara->tanggal_pendaftaran) ? date('d-m-Y', strtotime($perkara->tanggal_pendaftaran)) : '-' ?>"],
						["Status Perkara:", "<?= isset($perkara->status_perkara) ? $perkara->status_perkara : 'Tidak diketahui' ?>"],
						["Penggugat/Pemohon:", "<?= isset($perkara->nama_p) ? $perkara->nama_p : '-' ?>"],
						["Tergugat/Termohon:", "<?= isset($perkara->nama_t) ? $perkara->nama_t : '-' ?>"],
						[""]
					], {
						origin: 0
					});

					// Add timeline headers
					XLSX.utils.sheet_add_aoa(ws, [
						["TIMELINE EVENTS"],
						[""],
						["Tanggal", "Waktu", "Jenis Event", "Judul", "Deskripsi", "Keterangan"]
					], {
						origin: {
							r: 11,
							c: 0
						}
					});

					// Extract timeline data
					const timelineData = [];
					<?php if (!empty($timeline)): ?>
						<?php foreach ($timeline as $index => $item): ?>
							timelineData.push([
								"<?= date('d-m-Y', strtotime($item->tanggal)) ?>",
								"<?= date('H:i', strtotime($item->tanggal)) ?>",
								"<?= $item->jenis_event ?>",
								"<?= addslashes($item->judul) ?>",
								"<?= addslashes($item->deskripsi) ?>",
								"<?= isset($item->keterangan) ? addslashes($item->keterangan) : '' ?>"
							]);
						<?php endforeach; ?>
					<?php endif; ?>

					// Add timeline data to worksheet
					if (timelineData.length > 0) {
						XLSX.utils.sheet_add_aoa(ws, timelineData, {
							origin: {
								r: 14,
								c: 0
							}
						});
					} else {
						XLSX.utils.sheet_add_aoa(ws, [
							["Tidak ada data timeline untuk perkara ini."]
						], {
							origin: {
								r: 14,
								c: 0
							}
						});
					}

					// Set column widths
					const cols = [{
							wch: 15
						}, // Tanggal
						{
							wch: 10
						}, // Waktu
						{
							wch: 15
						}, // Jenis Event
						{
							wch: 30
						}, // Judul
						{
							wch: 50
						}, // Deskripsi
						{
							wch: 30
						} // Keterangan
					];
					ws['!cols'] = cols;

					// Add worksheet to workbook
					XLSX.utils.book_append_sheet(wb, ws, "Timeline");

					// Generate Excel file and trigger download
					const wbout = XLSX.write(wb, {
						bookType: 'xlsx',
						type: 'binary'
					});

					function s2ab(s) {
						const buf = new ArrayBuffer(s.length);
						const view = new Uint8Array(buf);
						for (let i = 0; i < s.length; i++) {
							view[i] = s.charCodeAt(i) & 0xFF;
						}
						return buf;
					}

					// Create download link
					const filename = 'Timeline_<?= isset($perkara->nomor_perkara) ? str_replace("/", "_", $perkara->nomor_perkara) : "Perkara" ?>_<?= date("Ymd") ?>.xlsx';
					const blob = new Blob([s2ab(wbout)], {
						type: 'application/octet-stream'
					});

					// IE/Edge support
					if (window.navigator && window.navigator.msSaveOrOpenBlob) {
						window.navigator.msSaveOrOpenBlob(blob, filename);
					} else {
						const url = URL.createObjectURL(blob);
						const a = document.createElement('a');
						a.href = url;
						a.download = filename;
						document.body.appendChild(a);
						a.click();
						setTimeout(function() {
							document.body.removeChild(a);
							window.URL.revokeObjectURL(url);
						}, 0);
					}
				}
			<?php endif; ?>
		});
	</script>
</body>

</html>