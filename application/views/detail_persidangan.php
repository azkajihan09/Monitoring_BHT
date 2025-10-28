<div class="content-wrapper">
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1 class="m-0 text-dark"><i class="fas fa-gavel mr-2"></i> Detail Persidangan</h1>
				</div>
				<div class="col-sm-6">
					<ol class="breadcrumb float-sm-right">
						<li class="breadcrumb-item"><a href="#">Home</a></li>
						<li class="breadcrumb-item"><a href="<?php echo base_url('index.php/persidangan'); ?>">Persidangan</a></li>
						<li class="breadcrumb-item active">Detail</li>
					</ol>
				</div>
			</div>
		</div>
	</section>

	<section class="content">
		<div class="container-fluid">
			<div class="card card-info">
				<div class="card-header">
					<h3 class="card-title">
						<i class="fas fa-file-alt mr-1"></i>
						Data Persidangan
					</h3>
					<div class="card-tools">
						<a href="<?php echo base_url('index.php/persidangan'); ?>" class="btn btn-sm btn-warning"><i class="fas fa-arrow-left mr-1"></i> Kembali</a>
					</div>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-8">
							<div class="table-responsive">
								<table class="table table-bordered">
									<tr>
										<th width="30%" class="bg-light">Nomor Perkara</th>
										<td><?php echo $detail->nomor_perkara; ?></td>
									</tr>
									<tr>
										<th class="bg-light">Nama Penggugat/Pemohon</th>
										<td><?php echo $detail->nama_p; ?></td>
									</tr>
									<tr>
										<th class="bg-light">Alamat Penggugat/Pemohon</th>
										<td><?php echo $detail->alamat_p; ?></td>
									</tr>
									<tr>
										<th class="bg-light">Nama Tergugat/Termohon</th>
										<td><?php echo $detail->nama_t; ?></td>
									</tr>
									<tr>
										<th class="bg-light">Alamat Tergugat/Termohon</th>
										<td><?php echo $detail->alamat_t; ?></td>
									</tr>
									<tr>
										<th class="bg-light">Tanggal Sidang</th>
										<td><?php echo !empty($detail->tanggal_sidang) ? date('d-m-Y', strtotime($detail->tanggal_sidang)) : '-'; ?></td>
									</tr>
									<tr>
										<th class="bg-light">Sidang Ke-</th>
										<td><?php echo $detail->sidang_ke; ?></td>
									</tr>
									<tr>
										<th class="bg-light">Panitera Pengganti</th>
										<td><?php echo $detail->panitera_nama; ?></td>
									</tr>
								</table>
							</div>
						</div>
						<div class="col-md-4">
							<div class="callout callout-info">
								<h5><i class="fas fa-info-circle mr-2"></i> Informasi Persidangan</h5>
								<p class="mb-1">Nomor Perkara: <strong><?php echo $detail->nomor_perkara; ?></strong></p>
								<p class="mb-1">Sidang ke-<?php echo $detail->sidang_ke; ?></p>
								<hr>
								<p class="mb-0 text-center"><a href="#" class="btn btn-sm btn-outline-primary">Cetak Detail Persidangan</a></p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
