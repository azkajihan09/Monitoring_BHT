<body class="hold-transition sidebar-mini">
	<div class="wrapper">
		<div class="content-wrapper">
			<!-- Content Header -->
			<section class="content-header">
				<div class="container-fluid">
					<div class="row mb-2">
						<div class="col-sm-6">
							<h1 class="m-0 text-dark"><i class="fas fa-calendar-check mr-2"></i> Laporan One Day Minute</h1>
						</div>
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								<li class="breadcrumb-item"><a href="<?= site_url('Admin/Dashboard') ?>">Home</a></li>
								<li class="breadcrumb-item">Laporan</li>
								<li class="breadcrumb-item active">One Day Minute</li>
							</ol>
						</div>
					</div>
				</div>
			</section>

			<!-- Main content -->
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
							<form action="<?php echo base_url() ?>index.php/Odm" method="POST" class="form-horizontal">
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
													$selected = (isset($_POST['lap_bulan']) && $_POST['lap_bulan'] === $value) ? 'selected="selected"' : '';
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
													$selected = (isset($_POST['lap_tahun']) && $_POST['lap_tahun'] == $year) ? 'selected="selected"' : '';
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

					<?php if (!empty($datafilter)): ?>
						<!-- Prominent Export Buttons - Add after filter card but before statistics/data table -->
						<div class="row mb-4">
							<div class="col-md-12">
								<div class="bg-light p-3" style="border-radius: 5px; border: 1px solid #ddd;">
									<h5><i class="fas fa-file-export mr-2"></i> Export Data One Day Minute</h5>
									<div class="mt-3">
										<a href="<?= site_url('Odm/export_excel/' . (isset($lap_bulan) ? $lap_bulan : date('m')) . '/' . (isset($lap_tahun) ? $lap_tahun : date('Y'))) ?>" class="btn btn-success btn-lg">
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

						<!-- Statistics Cards -->
						<div class="row">
							<div class="col-lg-3 col-6">
								<div class="small-box bg-info">
									<div class="inner">
										<h3><?= count($datafilter) ?></h3>
										<p>Total Perkara</p>
									</div>
									<div class="icon">
										<i class="fas fa-gavel"></i>
									</div>
									<a href="#" class="small-box-footer">
										<?php
										if (isset($_POST['lap_bulan']) && isset($months[$_POST['lap_bulan']])) {
											echo $months[$_POST['lap_bulan']] . ' ' . $_POST['lap_tahun'];
										} else {
											echo isset($_POST['lap_tahun']) ? $_POST['lap_tahun'] : date('Y');
										}
										?>
										<i class="fas fa-calendar-alt mx-1"></i>
									</a>
								</div>
							</div>

							<div class="col-lg-3 col-6">
								<div class="small-box bg-success">
									<div class="inner">
										<h3><?= isset($stats->odm_count) ? $stats->odm_count : 0 ?></h3>
										<p>One Day Minute</p>
									</div>
									<div class="icon">
										<i class="fas fa-check-circle"></i>
									</div>
									<a href="#" class="small-box-footer">
										<?= isset($datafilter) && count($datafilter) > 0 ? round((isset($stats->odm_count) ? $stats->odm_count : 0) / count($datafilter) * 100, 1) : 0 ?>% dari total perkara
										<i class="fas fa-info-circle mx-1"></i>
									</a>
								</div>
							</div>

							<div class="col-lg-3 col-6">
								<div class="small-box bg-warning">
									<div class="inner">
										<?php
										// Calculate average duration
										$total_days = 0;
										$delay_count = 0;
										foreach ($datafilter as $row) {
											$putus_date = new DateTime($row->tanggal_putusan);
											$minutasi_date = new DateTime($row->tanggal_minutasi);
											$interval = $putus_date->diff($minutasi_date);
											$days = $interval->days;
											$total_days += $days;

											if ($days > 0) {
												$delay_count++;
											}
										}
										$avg_days = count($datafilter) > 0 ? round($total_days / count($datafilter), 1) : 0;
										?>
										<h3><?= $avg_days ?></h3>
										<p>Rata-rata Hari Minutasi</p>
									</div>
									<div class="icon">
										<i class="fas fa-clock"></i>
									</div>
									<a href="#" class="small-box-footer">
										Dari tanggal putus ke minutasi
										<i class="fas fa-info-circle mx-1"></i>
									</a>
								</div>
							</div>

							<div class="col-lg-3 col-6">
								<div class="small-box bg-danger">
									<div class="inner">
										<h3><?= $delay_count ?></h3>
										<p>Minutasi Terlambat</p>
									</div>
									<div class="icon">
										<i class="fas fa-exclamation-triangle"></i>
									</div>
									<a href="#" class="small-box-footer">
										<?= round(($delay_count / count($datafilter)) * 100, 1) ?>% dari total perkara
										<i class="fas fa-info-circle mx-1"></i>
									</a>
								</div>
							</div>
						</div>

						<!-- Chart Row -->
						<div class="row">
							<div class="col-md-6">
								<div class="card card-info">
									<div class="card-header">
										<h3 class="card-title">
											<i class="fas fa-chart-pie mr-1"></i>
											Perbandingan One Day Minute
										</h3>
										<div class="card-tools">
											<button type="button" class="btn btn-tool" data-card-widget="collapse">
												<i class="fas fa-minus"></i>
											</button>
										</div>
									</div>
									<div class="card-body">
										<canvas id="odmPieChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
									</div>
								</div>
							</div>

							<div class="col-md-6">
								<div class="card card-success">
									<div class="card-header">
										<h3 class="card-title">
											<i class="fas fa-chart-bar mr-1"></i>
											Distribusi Jenis Perkara
										</h3>
										<div class="card-tools">
											<button type="button" class="btn btn-tool" data-card-widget="collapse">
												<i class="fas fa-minus"></i>
											</button>
										</div>
									</div>
									<div class="card-body">
										<?php
										// Count case types
										$case_types = [];
										foreach ($datafilter as $row) {
											if (!isset($case_types[$row->jenis_perkara_nama])) {
												$case_types[$row->jenis_perkara_nama] = 0;
											}
											$case_types[$row->jenis_perkara_nama]++;
										}
										arsort($case_types);
										?>

										<div class="table-responsive">
											<table class="table table-striped">
												<thead>
													<tr>
														<th>Jenis Perkara</th>
														<th class="text-center">Jumlah Perkara</th>
														<th class="text-center">One Day Minute</th>
														<th class="text-center">Persentase</th>
													</tr>
												</thead>
												<tbody>
													<?php
													// Count ODM by case type
													$odm_by_type = [];
													foreach ($datafilter as $row) {
														if (!isset($odm_by_type[$row->jenis_perkara_nama])) {
															$odm_by_type[$row->jenis_perkara_nama] = 0;
														}

														if (date('Y-m-d', strtotime($row->tanggal_putusan)) === date('Y-m-d', strtotime($row->tanggal_minutasi))) {
															$odm_by_type[$row->jenis_perkara_nama]++;
														}
													}

													foreach ($case_types as $type => $count) {
														$odm_count = isset($odm_by_type[$type]) ? $odm_by_type[$type] : 0;
														$percentage = round(($odm_count / $count) * 100, 1);
														echo "<tr>
                                                                <td>{$type}</td>
                                                                <td class='text-center'>{$count}</td>
                                                                <td class='text-center'>{$odm_count}</td>
                                                                <td class='text-center'>
                                                                    {$percentage}%
                                                                    <div class='progress progress-xs'>
                                                                        <div class='progress-bar bg-success' style='width: {$percentage}%'></div>
                                                                    </div>
                                                                </td>
                                                            </tr>";
													}
													?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>

						<!-- Export Buttons Row -->
						<div class="row mb-3">
							<div class="col-md-12">
								<div class="bg-info p-2" style="border-radius: 5px;">
									<a href="#" class="btn btn-success btn-sm export-excel">
										<i class="fas fa-file-excel mr-1"></i> Export ke Excel
									</a>
									<a href="#" class="btn btn-danger btn-sm export-pdf">
										<i class="fas fa-file-pdf mr-1"></i> Export ke PDF
									</a>
									<a href="#" class="btn btn-primary btn-sm print-data">
										<i class="fas fa-print mr-1"></i> Cetak
									</a>
								</div>
							</div>
						</div>

						<!-- Main Data Card -->
						<div class="card card-outline card-primary">
							<div class="card-header">
								<h3 class="card-title">
									<i class="fas fa-table mr-1"></i>
									Data One Day Minute
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
											<th class="text-center" style="width: 5%">No</th>
											<th style="width: 15%">Nomor Perkara</th>
											<th style="width: 15%">Jenis Perkara</th>
											<th style="width: 15%">Tanggal Putus</th>
											<th style="width: 15%">Tanggal Minutasi</th>
											<th style="width: 10%">Selisih (hari)</th>
											<th style="width: 15%">Status</th>
											<th style="width: 10%">Aksi</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$no = 1;
										foreach ($datafilter as $row):
											$putus_date = new DateTime($row->tanggal_putusan);
											$minutasi_date = new DateTime($row->tanggal_minutasi);
											$interval = $putus_date->diff($minutasi_date);
											$days = $interval->days;
											$is_odm = date('Y-m-d', strtotime($row->tanggal_putusan)) === date('Y-m-d', strtotime($row->tanggal_minutasi));
										?>
											<tr>
												<td class="text-center"><?= $no++ ?></td>
												<td><?= $row->nomor_perkara ?></td>
												<td><?= $row->jenis_perkara_nama ?></td>
												<td><?= date('d-m-Y', strtotime($row->tanggal_putusan)) ?></td>
												<td><?= date('d-m-Y', strtotime($row->tanggal_minutasi)) ?></td>
												<td class="text-center">
													<?php if ($days == 0): ?>
														<span class="badge badge-success">0</span>
													<?php elseif ($days <= 7): ?>
														<span class="badge badge-warning"><?= $days ?></span>
													<?php else: ?>
														<span class="badge badge-danger"><?= $days ?></span>
													<?php endif; ?>
												</td>
												<td>
													<?php if ($is_odm): ?>
														<span class="badge badge-success">One Day Minute</span>
													<?php elseif ($days <= 7): ?>
														<span class="badge badge-info">Dalam Batas Waktu</span>
													<?php else: ?>
														<span class="badge badge-danger">Terlambat</span>
													<?php endif; ?>
												</td>
												<td class="text-center">
													<?php if (!empty($row->perkara_id)): ?>
														<a href="<?= site_url('Odp/detail/' . $row->perkara_id) ?>" class="btn btn-xs btn-info" target="_blank" data-toggle="tooltip" title="Detail Lengkap">
															<i class="fas fa-eye"></i>
														</a>
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
										One Day Minute: <?= isset($odm_count) ? $odm_count : 0 ?> (<?= round((isset($odm_count) ? $odm_count : 0) / count($datafilter) * 100, 1) ?>%) |
										Diperbarui: <?= date('d-m-Y H:i:s') ?>
									</small>
								</div>
							</div>
						</div>
					<?php else: ?>
						<!-- No Data Message -->
						<?php if (isset($_POST['btn'])): ?>
							<div class="alert alert-warning">
								<h5><i class="icon fas fa-exclamation-triangle"></i> Tidak Ada Data</h5>
								<p>Tidak ditemukan data perkara pada periode yang dipilih. Silahkan pilih periode lainnya.</p>
							</div>
						<?php else: ?>
							<div class="jumbotron bg-light">
								<h1 class="display-5"><i class="fas fa-calendar-check mr-2"></i> Laporan One Day Minute</h1>
								<p class="lead">Silahkan pilih periode untuk menampilkan data laporan.</p>
								<hr class="my-4">
								<p>One Day Minute adalah laporan perkara yang diminutasi pada hari yang sama dengan tanggal putusan.</p>
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

			// Initialize DataTables with export buttons
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
				"dom": 'Bfrtip',
				"buttons": [{
						extend: 'excel',
						text: 'Excel',
						title: 'Data One Day Minute - ' + (typeof lap_bulan !== 'undefined' && typeof nama_bulan[lap_bulan] !== 'undefined' ? nama_bulan[lap_bulan] + " " : "") + (typeof lap_tahun !== 'undefined' ? lap_tahun : ""),
						exportOptions: {
							columns: [0, 1, 2, 3, 4, 5]
						},
						className: 'btn-success',
						action: function(e, dt, button, config) {
							// Add visual feedback
							$('.export-excel').addClass('disabled').html('<i class="fas fa-spinner fa-spin mr-1"></i> Exporting...');
							$.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, button, config);
							setTimeout(function() {
								$('.export-excel').removeClass('disabled').html('<i class="fas fa-file-excel mr-1"></i> Export ke Excel');
							}, 3000);
						}
					},
					{
						extend: 'pdfHtml5',
						text: 'PDF',
						title: 'Data One Day Minute - ' + (typeof lap_bulan !== 'undefined' && typeof nama_bulan[lap_bulan] !== 'undefined' ? nama_bulan[lap_bulan] + " " : "") + (typeof lap_tahun !== 'undefined' ? lap_tahun : ""),
						exportOptions: {
							columns: [0, 1, 2, 3, 4, 5]
						},
						orientation: 'landscape',
						className: 'btn-danger',
						action: function(e, dt, button, config) {
							// Add visual feedback
							$('.export-pdf').addClass('disabled').html('<i class="fas fa-spinner fa-spin mr-1"></i> Creating PDF...');
							$.fn.dataTable.ext.buttons.pdfHtml5.action.call(this, e, dt, button, config);
							setTimeout(function() {
								$('.export-pdf').removeClass('disabled').html('<i class="fas fa-file-pdf mr-1"></i> Export ke PDF');
							}, 3000);
						}
					},
					{
						extend: 'print',
						text: 'Print',
						title: 'Data One Day Minute - ' + (typeof lap_bulan !== 'undefined' && typeof nama_bulan[lap_bulan] !== 'undefined' ? nama_bulan[lap_bulan] + " " : "") + (typeof lap_tahun !== 'undefined' ? lap_tahun : ""),
						exportOptions: {
							columns: [0, 1, 2, 3, 4, 5]
						},
						className: 'btn-primary',
						action: function(e, dt, button, config) {
							// Add visual feedback
							$('.print-data').addClass('disabled').html('<i class="fas fa-spinner fa-spin mr-1"></i> Preparing...');
							$.fn.dataTable.ext.buttons.print.action.call(this, e, dt, button, config);
							setTimeout(function() {
								$('.print-data').removeClass('disabled').html('<i class="fas fa-print mr-1"></i> Cetak');
							}, 1000);
						}
					}
				]
			});

			// Bind export buttons to DataTables buttons
			$('.export-excel').click(function(e) {
				e.preventDefault();
				dataTable.button('.buttons-excel').trigger();
			});
			$('.export-csv').click(function(e) {
				e.preventDefault();
				dataTable.button('.buttons-csv').trigger();
			});
			$('.export-pdf').click(function(e) {
				e.preventDefault();
				dataTable.button('.buttons-pdf').trigger();
			});
			$('.print-data').click(function(e) {
				e.preventDefault();
				dataTable.button('.buttons-print').trigger();
			});

			<?php if (!empty($datafilter)): ?>
				// Initialize pie chart
				var pieChartCanvas = $('#odmPieChart').get(0).getContext('2d');
				var pieData = {
					labels: ['One Day Minute', 'Lebih dari 1 hari'],
					datasets: [{
						data: [<?= isset($odm_count) ? $odm_count : 0 ?>, <?= count($datafilter) - (isset($odm_count) ? $odm_count : 0) ?>],
						backgroundColor: ['#28a745', '#dc3545'],
					}]
				};
				var pieOptions = {
					maintainAspectRatio: false,
					responsive: true,
					legend: {
						position: 'right',
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
				var pieChart = new Chart(pieChartCanvas, {
					type: 'pie',
					data: pieData,
					options: pieOptions
				});
			<?php endif; ?>

			// Initialize tooltips
			$('[data-toggle="tooltip"]').tooltip();
		});
	</script>
</body>

</html>