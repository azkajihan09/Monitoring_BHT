<body class="hold-transition sidebar-mini">
	<div class="wrapper">
		<div class="content-wrapper">
			<section class="content-header">
				<div class="container-fluid">
					<div class="row mb-2">
						<div class="col-sm-6">
							<h1 class="m-0 text-dark"><i class="fas fa-list-alt mr-2"></i> Detail Perkara Masuk Harian</h1>
						</div>
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								<li class="breadcrumb-item"><a href="<?= site_url('Admin/Dashboard') ?>">Home</a></li>
								<li class="breadcrumb-item"><a href="<?= site_url('Masuk_harian') ?>">Perkara Masuk Harian</a></li>
								<li class="breadcrumb-item active">Detail</li>
							</ol>
						</div>
					</div>
				</div>
			</section>

			<section class="content">
				<div class="container-fluid">
					<!-- Info Card -->
					<div class="card card-info card-outline">
						<div class="card-header">
							<h3 class="card-title"><i class="fas fa-info-circle mr-1"></i> Informasi Detail</h3>
							<div class="card-tools">
								<a href="<?= site_url('Masuk_harian') ?>" class="btn btn-sm btn-secondary">
									<i class="fas fa-arrow-left mr-1"></i> Kembali
								</a>
							</div>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-md-6">
									<table class="table table-borderless">
										<tr>
											<td width="30%"><strong>Majelis Hakim:</strong></td>
											<td><?= isset($majelis_info) ? $majelis_info->majelis_hakim_nama : '-' ?></td>
										</tr>
										<tr>
											<td><strong>Kode Majelis:</strong></td>
											<td><?= isset($majelis_info) ? $majelis_info->majelis_hakim_kode : '-' ?></td>
										</tr>
										<tr>
											<td><strong>Hakim Ketua:</strong></td>
											<td><?= isset($majelis_info) ? $majelis_info->hakim_ketua : '-' ?></td>
										</tr>
									</table>
								</div>
								<div class="col-md-6">
									<table class="table table-borderless">
										<tr>
											<td width="30%"><strong>Hari:</strong></td>
											<td>
												<?php
												$hari_names = ['', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
												echo isset($day_of_week) ? $hari_names[$day_of_week] : '-';
												?>
											</td>
										</tr>
										<tr>
											<td><strong>Periode:</strong></td>
											<td>
												<?php
												$bulan_names = [
													'', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
													'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
												];
												echo isset($lap_bulan, $lap_tahun) ? $bulan_names[(int)$lap_bulan] . ' ' . $lap_tahun : '-';
												?>
											</td>
										</tr>
										<tr>
											<td><strong>Jenis Perkara:</strong></td>
											<td>
												<?php
												if (isset($jenis_perkara)) {
													switch ($jenis_perkara) {
														case 'Pdt.G':
															echo 'Perkara Gugatan (Pdt.G)';
															break;
														case 'Pdt.P':
															echo 'Perkara Permohonan (Pdt.P)';
															break;
														case 'all':
															echo 'Semua Jenis Perkara';
															break;
														default:
															echo $jenis_perkara;
													}
												} else {
													echo '-';
												}
												?>
											</td>
										</tr>
									</table>
								</div>
							</div>
						</div>
					</div>

					<!-- Statistics Cards -->
					<div class="row">
						<div class="col-lg-3 col-6">
							<div class="small-box bg-primary">
								<div class="inner">
									<h3><?= isset($detail_cases) ? count($detail_cases) : 0 ?></h3>
									<p>Total Perkara</p>
									<small>Pada hari ini</small>
								</div>
								<div class="icon">
									<i class="fas fa-file-alt"></i>
								</div>
							</div>
						</div>

						<div class="col-lg-3 col-6">
							<div class="small-box bg-success">
								<div class="inner">
									<h3>
										<?php
										if (isset($detail_cases)) {
											$pdt_g_count = 0;
											foreach ($detail_cases as $case) {
												if (strpos($case->nomor_perkara, 'Pdt.G') !== false) {
													$pdt_g_count++;
												}
											}
											echo $pdt_g_count;
										} else {
											echo '0';
										}
										?>
									</h3>
									<p>Perkara Gugatan</p>
									<small>Pdt.G</small>
								</div>
								<div class="icon">
									<i class="fas fa-balance-scale"></i>
								</div>
							</div>
						</div>

						<div class="col-lg-3 col-6">
							<div class="small-box bg-warning">
								<div class="inner">
									<h3>
										<?php
										if (isset($detail_cases)) {
											$pdt_p_count = 0;
											foreach ($detail_cases as $case) {
												if (strpos($case->nomor_perkara, 'Pdt.P') !== false) {
													$pdt_p_count++;
												}
											}
											echo $pdt_p_count;
										} else {
											echo '0';
										}
										?>
									</h3>
									<p>Perkara Permohonan</p>
									<small>Pdt.P</small>
								</div>
								<div class="icon">
									<i class="fas fa-file-signature"></i>
								</div>
							</div>
						</div>

						<div class="col-lg-3 col-6">
							<div class="small-box bg-info">
								<div class="inner">
									<h3>
										<?php
										if (isset($detail_cases)) {
											$total_panjar = 0;
											foreach ($detail_cases as $case) {
												if (isset($case->panjar_perkara)) {
													$total_panjar += (float)$case->panjar_perkara;
												}
											}
											echo 'Rp ' . number_format($total_panjar, 0, ',', '.');
										} else {
											echo 'Rp 0';
										}
										?>
									</h3>
									<p>Total Panjar</p>
									<small>Hari ini</small>
								</div>
								<div class="icon">
									<i class="fas fa-money-bill-wave"></i>
								</div>
							</div>
						</div>
					</div>

					<!-- Data Table -->
					<div class="card card-primary card-outline">
						<div class="card-header">
							<h3 class="card-title"><i class="fas fa-list mr-1"></i> Daftar Perkara Masuk</h3>
							<div class="card-tools">
								<?php if (isset($detail_cases) && !empty($detail_cases)): ?>
									<button type="button" class="btn btn-sm btn-success" onclick="exportToExcel()">
										<i class="fas fa-file-excel mr-1"></i> Export Excel
									</button>
								<?php endif; ?>
							</div>
						</div>
						<div class="card-body">
							<?php if (isset($detail_cases) && !empty($detail_cases)): ?>
								<div class="table-responsive">
									<table id="detailTable" class="table table-bordered table-striped table-hover">
										<thead class="thead-dark">
											<tr>
												<th style="width: 5%;">No</th>
												<th style="width: 15%;">Nomor Perkara</th>
												<th style="width: 10%;">Jenis</th>
												<th style="width: 25%;">Pihak Berperkara</th>
												<th style="width: 12%;">Tanggal Daftar</th>
												<th style="width: 15%;">Panjar</th>
												<th style="width: 18%;">Keterangan</th>
											</tr>
										</thead>
										<tbody>
											<?php
											$no = 1;
											foreach ($detail_cases as $case) :
											?>
												<tr>
													<td class="text-center"><?= $no++ ?></td>
													<td>
														<strong><?= $case->nomor_perkara ?></strong>
														<?php if (isset($case->nomor_urut_perkara)): ?>
															<br><small class="text-muted">Urut: <?= $case->nomor_urut_perkara ?></small>
														<?php endif; ?>
													</td>
													<td class="text-center">
														<?php if (strpos($case->nomor_perkara, 'Pdt.G') !== false): ?>
															<span class="badge badge-success">Gugatan</span>
														<?php elseif (strpos($case->nomor_perkara, 'Pdt.P') !== false): ?>
															<span class="badge badge-warning">Permohonan</span>
														<?php else: ?>
															<span class="badge badge-secondary">Lainnya</span>
														<?php endif; ?>
													</td>
													<td>
														<?php if (isset($case->pihak_penggugat) && !empty($case->pihak_penggugat)): ?>
															<strong>Penggugat:</strong> <?= $case->pihak_penggugat ?><br>
														<?php endif; ?>
														<?php if (isset($case->pihak_tergugat) && !empty($case->pihak_tergugat)): ?>
															<strong>Tergugat:</strong> <?= $case->pihak_tergugat ?>
														<?php endif; ?>
														<?php if (isset($case->pihak_pemohon) && !empty($case->pihak_pemohon)): ?>
															<strong>Pemohon:</strong> <?= $case->pihak_pemohon ?>
														<?php endif; ?>
													</td>
													<td class="text-center">
														<?php if (isset($case->tanggal_pendaftaran)): ?>
															<?= date('d/m/Y', strtotime($case->tanggal_pendaftaran)) ?>
															<br><small class="text-muted"><?= date('H:i', strtotime($case->tanggal_pendaftaran)) ?></small>
														<?php else: ?>
															-
														<?php endif; ?>
													</td>
													<td class="text-right">
														<?php if (isset($case->panjar_perkara) && $case->panjar_perkara > 0): ?>
															Rp <?= number_format($case->panjar_perkara, 0, ',', '.') ?>
														<?php else: ?>
															<span class="text-muted">-</span>
														<?php endif; ?>
													</td>
													<td>
														<?php if (isset($case->jenis_perkara_nama)): ?>
															<small class="text-info"><?= $case->jenis_perkara_nama ?></small><br>
														<?php endif; ?>
														<?php if (isset($case->status_perkara)): ?>
															<span class="badge badge-info badge-sm"><?= $case->status_perkara ?></span>
														<?php endif; ?>
													</td>
												</tr>
											<?php endforeach; ?>
										</tbody>
									</table>
								</div>
							<?php else: ?>
								<div class="alert alert-info text-center">
									<i class="fas fa-info-circle fa-2x mb-3"></i>
									<h5>Tidak ada perkara masuk</h5>
									<p>Tidak ada perkara yang masuk pada hari dan majelis yang dipilih.</p>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</section>
		</div>
	</div>

	<script>
		$(document).ready(function() {
			<?php if (isset($detail_cases) && !empty($detail_cases)): ?>
				// Initialize DataTable
				$('#detailTable').DataTable({
					"responsive": true,
					"lengthChange": true,
					"autoWidth": false,
					"pageLength": 25,
					"order": [[4, "asc"]], // Sort by tanggal daftar
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
			<?php endif; ?>
		});

		// Export to Excel function
		function exportToExcel() {
			var table = document.getElementById('detailTable');
			var wb = XLSX.utils.table_to_book(table, {sheet: "Detail Perkara"});
			
			// Generate filename
			var filename = 'Detail_Perkara_Masuk_' + 
				'<?= isset($majelis_info) ? str_replace(' ', '_', $majelis_info->majelis_hakim_kode) : 'Majelis' ?>' + '_' +
				'<?php
					$hari_names = ['', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
					echo isset($day_of_week) ? $hari_names[$day_of_week] : 'Hari';
				?>' + '_' +
				'<?= isset($lap_bulan, $lap_tahun) ? $lap_bulan . '_' . $lap_tahun : date('m_Y') ?>' + 
				'.xlsx';
			
			XLSX.writeFile(wb, filename);
		}
	</script>

	<!-- Include XLSX library for Excel export -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
</body>