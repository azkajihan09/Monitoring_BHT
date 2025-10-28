<body class="hold-transition sidebar-mini">
	<div class="wrapper">
		<div class="content-wrapper">
			<!-- Content Header -->
			<section class="content-header">
				<div class="container-fluid">
					<div class="row mb-2">
						<div class="col-sm-6">
							<h1 class="m-0 text-dark"><i class="fas fa-search-location mr-2"></i> Penelusuran Perkara Ghaib</h1>
						</div>
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								<li class="breadcrumb-item"><a href="<?= site_url('Admin/Dashboard') ?>">Home</a></li>
								<li class="breadcrumb-item">Perkara</li>
								<li class="breadcrumb-item active">Perkara Ghaib</li>
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
							<h3 class="card-title"><i class="fas fa-filter mr-1"></i> Filter Pencarian</h3>
							<div class="card-tools">
								<button type="button" class="btn btn-tool" data-card-widget="collapse">
									<i class="fas fa-minus"></i>
								</button>
							</div>
						</div>
						<div class="card-body">
							<form action="<?php echo base_url() ?>index.php/Ghaib" method="POST" class="form-horizontal">
								<div class="form-group row">
									<label class="col-sm-2 col-form-label">Jenis Perkara:</label>
									<div class="col-sm-4">
										<select name="jenis_perkara" class="form-control select2" required>
											<option value="">-- Pilih Jenis Perkara --</option>
											<option value="Pdt.G" <?php echo (isset($_POST['jenis_perkara']) && $_POST['jenis_perkara'] === 'Pdt.G') ? 'selected' : ''; ?>>Perkara Gugatan (Pdt.G)</option>
											<option value="Pdt.P" <?php echo (isset($_POST['jenis_perkara']) && $_POST['jenis_perkara'] === 'Pdt.P') ? 'selected' : ''; ?>>Perkara Permohonan (Pdt.P)</option>
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
												for ($year = 2016; $year <= $currentYear + 1; $year++) {
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

					<?php if (isset($_POST['btn'])): ?>
						<?php if (!empty($datafilter)): ?>
							<!-- Information Alert -->
							<div class="alert alert-info">
								<h5><i class="icon fas fa-info"></i> Informasi Pencarian</h5>
								<p>Menampilkan data perkara ghaib untuk jenis <strong><?= $_POST['jenis_perkara'] ?></strong> pada periode <strong><?= $months[$_POST['lap_bulan']] ?> <?= $_POST['lap_tahun'] ?></strong>. Total ditemukan: <strong><?= count($datafilter) ?> perkara</strong>.</p>
							</div>

							<!-- Statistics Cards -->
							<div class="row">
								<div class="col-lg-3 col-6">
									<div class="small-box bg-info">
										<div class="inner">
											<h3><?= count($datafilter) ?></h3>
											<p>Total Perkara Ghaib</p>
										</div>
										<div class="icon">
											<i class="fas fa-search-location"></i>
										</div>
										<a href="#" class="small-box-footer">
											<?= isset($stats->percent_of_total) ? round($stats->percent_of_total, 1) . '% dari total perkara' : '' ?>
											<i class="fas fa-info-circle mx-1"></i>
										</a>
									</div>
								</div>

								<div class="col-lg-3 col-6">
									<div class="small-box bg-success">
										<div class="inner">
											<h3><?= isset($stats->putus_count) ? $stats->putus_count : 0 ?></h3>
											<p>Sudah Diputus</p>
										</div>
										<div class="icon">
											<i class="fas fa-gavel"></i>
										</div>
										<a href="#" class="small-box-footer">
											<?= isset($stats->putus_count) && count($datafilter) > 0 ? round(($stats->putus_count / count($datafilter)) * 100, 1) . '% dari total ghaib' : '0%' ?>
											<i class="fas fa-info-circle mx-1"></i>
										</a>
									</div>
								</div>

								<div class="col-lg-3 col-6">
									<div class="small-box bg-warning">
										<div class="inner">
											<h3><?= isset($stats->avg_days) ? round($stats->avg_days) : 0 ?></h3>
											<p>Rata-rata Durasi (hari)</p>
										</div>
										<div class="icon">
											<i class="fas fa-clock"></i>
										</div>
										<a href="#" class="small-box-footer">
											Dari pendaftaran hingga putusan
											<i class="fas fa-info-circle mx-1"></i>
										</a>
									</div>
								</div>

								<div class="col-lg-3 col-6">
									<div class="small-box bg-danger">
										<div class="inner">
											<h3><?= isset($stats->in_process) ? $stats->in_process : 0 ?></h3>
											<p>Dalam Proses</p>
										</div>
										<div class="icon">
											<i class="fas fa-hourglass-half"></i>
										</div>
										<a href="#" class="small-box-footer">
											<?= isset($stats->in_process) && count($datafilter) > 0 ? round(($stats->in_process / count($datafilter)) * 100, 1) . '% dari total ghaib' : '0%' ?>
											<i class="fas fa-info-circle mx-1"></i>
										</a>
									</div>
								</div>
							</div>

							<!-- Export Buttons Row -->
							<div class="row mb-4">
								<div class="col-md-12">
									<div class="bg-light p-3" style="border-radius: 5px; border: 1px solid #ddd;">
										<h5><i class="fas fa-file-export mr-2"></i> Export Data Perkara Ghaib</h5>
										<div class="mt-3">
											<a href="<?= site_url('Ghaib/export_excel/' . $_POST['jenis_perkara'] . '/' . $_POST['lap_bulan'] . '/' . $_POST['lap_tahun']) ?>" class="btn btn-success btn-lg">
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

							<!-- Main Data Card -->
							<div class="card card-outline card-primary">
								<div class="card-header">
									<h3 class="card-title">
										<i class="fas fa-table mr-1"></i>
										Data Perkara Ghaib <?= $_POST['jenis_perkara'] ?> - <?= $months[$_POST['lap_bulan']] ?> <?= $_POST['lap_tahun'] ?>
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
												<th width="16%">Majelis Hakim</th>
												<th width="10%">Panitera Pengganti</th>
												<th width="8%">Tgl. Pendaftaran</th>
												<th width="8%">Tgl. PMH</th>
												<th width="8%">Tgl. PHS</th>
												<th width="8%">Sidang Pertama</th>
												<th width="8%">Tgl. Putusan</th>
												<th width="10%">Jenis Putusan</th>
												<th width="10%">Keterangan Ghaib</th>
											</tr>
										</thead>
										<tbody>
											<?php
											$no = 1;
											foreach ($datafilter as $row):
												// Calculate days
												$days_to_verdict = !empty($row->tanggal_putusan) ?
													(strtotime($row->tanggal_putusan) - strtotime($row->tanggal_pendaftaran)) / (60 * 60 * 24) :
													null;

												// Row class based on status
												$row_class = '';
												if (empty($row->tanggal_putusan)) {
													$row_class = 'table-warning'; // Still in process
												} elseif (stripos($row->status_putusan_nama, 'dikabulkan') !== false) {
													$row_class = 'table-success'; // Accepted
												} elseif (stripos($row->status_putusan_nama, 'ditolak') !== false) {
													$row_class = 'table-danger'; // Rejected
												} elseif (
													stripos($row->status_putusan_nama, 'dicabut') !== false ||
													stripos($row->status_putusan_nama, 'gugur') !== false
												) {
													$row_class = 'table-secondary'; // Withdrawn or failed
												}
											?>
												<tr class="<?= $row_class ?>">
													<td class="text-center"><?= $no++ ?></td>
													<td><?= $row->nomor_perkara ?></td>
													<td><?= $row->majelis_hakim_nama ?></td>
													<td><?= $row->panitera_pengganti_text ?></td>
													<td><?= date('d-m-Y', strtotime($row->tanggal_pendaftaran)) ?></td>
													<td><?= !empty($row->penetapan_majelis_hakim) ? date('d-m-Y', strtotime($row->penetapan_majelis_hakim)) : '-' ?></td>
													<td><?= !empty($row->penetapan_hari_sidang) ? date('d-m-Y', strtotime($row->penetapan_hari_sidang)) : '-' ?></td>
													<td><?= !empty($row->sidang_pertama) ? date('d-m-Y', strtotime($row->sidang_pertama)) : '-' ?></td>
													<td>
														<?php if (!empty($row->tanggal_putusan)): ?>
															<?= date('d-m-Y', strtotime($row->tanggal_putusan)) ?>
															<div class="small text-muted">
																<?= $days_to_verdict ?> hari
															</div>
														<?php else: ?>
															<span class="badge badge-warning">Dalam Proses</span>
														<?php endif; ?>
													</td>
													<td><?= !empty($row->status_putusan_nama) ? $row->status_putusan_nama : '-' ?></td>
													<td>
														<div class="small text-danger">
															<?= $row->alamat_t ?>
														</div>
														<?php if ($row->ghaib == 1): ?>
															<span class="badge badge-info">Ghaib</span>
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
						<?php else: ?>
							<!-- No Data Alert -->
							<div class="alert alert-warning">
								<h5><i class="icon fas fa-exclamation-triangle"></i> Tidak Ada Data</h5>
								<p>Tidak ditemukan data perkara ghaib untuk kriteria pencarian yang dipilih.</p>
							</div>
						<?php endif; ?>
					<?php else: ?>
						<!-- Welcome Message -->
						<div class="jumbotron bg-light">
							<h1 class="display-5"><i class="fas fa-search-location mr-2"></i> Penelusuran Perkara Ghaib</h1>
							<p class="lead">Gunakan form di atas untuk mencari perkara dengan status ghaib (pihak tidak diketahui alamatnya) pada periode tertentu.</p>
							<hr class="my-4">
							<p>Perkara ghaib memerlukan penanganan khusus dalam proses pemanggilan dan persidangan sesuai dengan ketentuan hukum acara perdata.</p>
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
						title: 'Data Perkara Ghaib - <?= isset($_POST["jenis_perkara"]) ? $_POST["jenis_perkara"] : "" ?> <?= isset($_POST["lap_bulan"]) ? $months[$_POST["lap_bulan"]] : "" ?> <?= isset($_POST["lap_tahun"]) ? $_POST["lap_tahun"] : "" ?>',
						exportOptions: {
							columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
						},
						className: 'btn-success'
					},
					{
						extend: 'pdf',
						text: 'PDF',
						title: 'Data Perkara Ghaib - <?= isset($_POST["jenis_perkara"]) ? $_POST["jenis_perkara"] : "" ?> <?= isset($_POST["lap_bulan"]) ? $months[$_POST["lap_bulan"]] : "" ?> <?= isset($_POST["lap_tahun"]) ? $_POST["lap_tahun"] : "" ?>',
						exportOptions: {
							columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
						},
						orientation: 'landscape',
						className: 'btn-danger'
					},
					{
						extend: 'print',
						text: 'Print',
						title: 'Data Perkara Ghaib - <?= isset($_POST["jenis_perkara"]) ? $_POST["jenis_perkara"] : "" ?> <?= isset($_POST["lap_bulan"]) ? $months[$_POST["lap_bulan"]] : "" ?> <?= isset($_POST["lap_tahun"]) ? $_POST["lap_tahun"] : "" ?>',
						exportOptions: {
							columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
						},
						className: 'btn-primary'
					}
				]
			});

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