<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-green elevation-4">
	<!-- Brand Logo -->
	<a href="<?php echo site_url('home') ?>" class="brand-link navbar-green">
		<img src="<?php echo base_url() ?>assets/dist/img/logo-mahkamah-agung.png" alt="Logo PA Amuntai" class="brand-image img-circle elevation-2" style="opacity: .8">
		<span class="brand-text font-weight-light">SIPP PA Amuntai</span>
	</a>

	<!-- Sidebar -->
	<div class="sidebar">
		<!-- Sidebar user panel (optional) -->
		<div class="user-panel mt-3 pb-3 mb-3 d-flex">
			<div class="image">
				<img src="<?php echo base_url() ?>assets/dist/img/Logo PA Amuntai - Trans.png" class="img-circle elevation-2" alt="User Image">
			</div>
			<div class="info">
				<a href="#" class="d-block">Administrator</a>
				<span class="badge badge-success">Online</span>
			</div>
		</div>

		<!-- Sidebar Menu -->
		<nav class="mt-2">
			<ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
				<!-- Dashboard -->
				<li class="nav-item">
					<a href="<?= site_url('home') ?>" class="nav-link <?= $this->uri->segment(1) == '' || $this->uri->segment(1) == 'home' ? 'active' : '' ?>">
						<i class="nav-icon fas fa-tachometer-alt"></i>
						<p>Dashboard</p>
					</a>
				</li>

				<li class="nav-header">MANAJEMEN PERKARA</li>

				<!-- Laporan Kegiatan Hakim -->
				<li class="nav-item">
					<a href="#" class="nav-link">
						<i class="nav-icon fas fa-gavel"></i>
						<p>
							Laporan Kegiatan Hakim
							<i class="fas fa-angle-left right"></i>
						</p>
					</a>
					<ul class="nav nav-treeview">
						<li class="nav-item">
							<a href="<?php echo site_url('Sisa_bulan_lalu') ?>" class="nav-link">
								<i class="far fa-calendar-minus nav-icon"></i>
								<p>Sisa Perkara Bulan Lalu</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="<?php echo site_url('Masuk') ?>" class="nav-link">
								<i class="fas fa-inbox nav-icon"></i>
								<p>Perkara Masuk</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="<?php echo site_url('Masuk_harian') ?>" class="nav-link">
								<i class="fas fa-calendar-week nav-icon"></i>
								<p>Perkara Masuk Harian</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="<?php echo site_url('Putus') ?>" class="nav-link">
								<i class="fas fa-check-circle nav-icon"></i>
								<p>Perkara Putus</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="<?php echo site_url('Putus_uji') ?>" class="nav-link">
								<i class="fas fa-check-circle nav-icon"></i>
								<p>Perkara Putus Uji</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="<?php echo site_url('Bht_putus') ?>" class="nav-link">
								<i class="fas fa-file-signature nav-icon"></i>
								<p>BHT Perkara Putus</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="<?php echo site_url('Bht_putus_2') ?>" class="nav-link">
								<i class="fas fa-file-contract nav-icon"></i>
								<p>BHT Perkara Putus 2</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="<?php echo site_url('Putus_total') ?>" class="nav-link">
								<i class="fas fa-chart-bar nav-icon"></i>
								<p>Perkara Putus Total</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="<?php echo site_url('Sisa_bulan_ini') ?>" class="nav-link">
								<i class="fas fa-calendar-week nav-icon"></i>
								<p>Sisa Perkara Bulan Ini</p>
							</a>
						</li>
					</ul>
				</li>

				<!-- Ket. Keadaan Perkara -->
				<li class="nav-item">
					<a href="#" class="nav-link">
						<i class="nav-icon fas fa-clipboard-list"></i>
						<p>
							Ket. Keadaan Perkara
							<i class="fas fa-angle-left right"></i>
						</p>
					</a>
					<ul class="nav nav-treeview">
						<li class="nav-item">
							<a href="<?php echo site_url('PNS') ?>" class="nav-link">
								<i class="fas fa-user-tie nav-icon"></i>
								<p>PNS</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="<?php echo site_url('Ghaib') ?>" class="nav-link">
								<i class="fas fa-user-slash nav-icon"></i>
								<p>Ghaib</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="<?php echo site_url('Prodeo') ?>" class="nav-link">
								<i class="fas fa-hand-holding-usd nav-icon"></i>
								<p>Prodeo</p>
							</a>
						</li>
					</ul>
				</li>

				<li class="nav-header">JENIS PERKARA</li>

				<!-- Permohonan -->
				<li class="nav-item">
					<a href="#" class="nav-link">
						<i class="nav-icon fas fa-file-signature"></i>
						<p>
							Permohonan
							<i class="fas fa-angle-left right"></i>
							<span class="badge badge-info right">2</span>
						</p>
					</a>
					<ul class="nav nav-treeview">
						<li class="nav-item">
							<a href="<?php echo site_url('Itsbat') ?>" class="nav-link">
								<i class="fas fa-certificate nav-icon"></i>
								<p>Itsbat Nikah</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="<?php echo site_url('Diska') ?>" class="nav-link">
								<i class="fas fa-people-arrows nav-icon"></i>
								<p>Dispensasi Kawin</p>
							</a>
						</li>
					</ul>
				</li>

				<!-- Gugatan -->
				<li class="nav-item">
					<a href="#" class="nav-link">
						<i class="nav-icon fas fa-balance-scale"></i>
						<p>
							Gugatan
							<i class="fas fa-angle-left right"></i>
							<span class="badge badge-warning right">4</span>
						</p>
					</a>
					<ul class="nav nav-treeview">
						<li class="nav-item">
							<a href="<?php echo site_url('Penyerahan_ac') ?>" class="nav-link">
								<i class="fas fa-file-alt nav-icon"></i>
								<p>Penyerahan Akta Cerai</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="<?php echo site_url('Usia_cerai') ?>" class="nav-link">
								<i class="fas fa-chart-pie nav-icon"></i>
								<p>Lap. Usia Perceraian</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="<?php echo site_url('Cerai_kua') ?>" class="nav-link">
								<i class="fas fa-mosque nav-icon"></i>
								<p>Lap. Perceraian KUA</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="<?php echo site_url('Reg_salput') ?>" class="nav-link">
								<i class="fas fa-file-export nav-icon"></i>
								<p>Register Salput ke KUA</p>
							</a>
						</li>
					</ul>
				</li>

				<li class="nav-header">LAYANAN ELEKTRONIK</li>

				<!-- E-Court -->
				<li class="nav-item">
					<a href="#" class="nav-link">
						<i class="nav-icon fas fa-laptop-code"></i>
						<p>
							E-Court
							<i class="fas fa-angle-left right"></i>
							<span class="badge badge-success right">2</span>
						</p>
					</a>
					<ul class="nav nav-treeview">
						<li class="nav-item">
							<a href="<?php echo site_url('Ecourt') ?>" class="nav-link">
								<i class="fas fa-list-alt nav-icon"></i>
								<p>Perkara E-Court</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="<?php echo site_url('Rekap_ecourt') ?>" class="nav-link">
								<i class="fas fa-chart-bar nav-icon"></i>
								<p>Rekap E-Court</p>
							</a>
						</li>
					</ul>
				</li>

				<!-- E-Court Menu -->
				<li class="nav-item has-treeview">
					<a href="#" class="nav-link">
						<i class="nav-icon fas fa-laptop-code"></i>
						<p>
							E-Court
							<i class="fas fa-angle-left right"></i>
						</p>
					</a>
					<ul class="nav nav-treeview">
						<li class="nav-item">
							<a href="<?= site_url('Ecourt') ?>" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>Laporan Perkara</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="<?= site_url('Ecourt_monitoring') ?>" class="nav-link">
								<i class="far fa-circle nav-icon"></i>
								<p>Monitoring E-Court</p>
							</a>
						</li>
					</ul>
				</li>

				<!-- One Day -->
				<li class="nav-item">
					<a href="#" class="nav-link">
						<i class="nav-icon fas fa-business-time"></i>
						<p>
							One Day Service
							<i class="fas fa-angle-left right"></i>
						</p>
					</a>
					<ul class="nav nav-treeview">
						<li class="nav-item">
							<a href="<?php echo site_url('Odm') ?>" class="nav-link">
								<i class="fas fa-file-signature nav-icon"></i>
								<p>One Day Minute</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="<?php echo site_url('Odp') ?>" class="nav-link">
								<i class="fas fa-share-alt nav-icon"></i>
								<p>One Day Publish</p>
							</a>
						</li>
					</ul>
				</li>

				<li class="nav-header">PERSIDANGAN</li>

				<!-- Catatan Persidangan -->
				<li class="nav-item">
					<a href="#" class="nav-link">
						<i class="nav-icon fas fa-gavel"></i>
						<p>
							Catatan Persidangan
							<i class="fas fa-angle-left right"></i>
						</p>
					</a>
					<ul class="nav nav-treeview">
						<li class="nav-item">
							<a href="<?php echo site_url('Persidangan') ?>" class="nav-link">
								<i class="fas fa-user-tag nav-icon"></i>
								<p>Jurusita</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="<?php echo site_url('Hadir_sidang') ?>" class="nav-link">
								<i class="fas fa-users nav-icon"></i>
								<p>Kehadiran Pihak</p>
							</a>
						</li>
					</ul>
				</li>

				<!-- Usia Pihak -->
				<li class="nav-item">
					<a href="#" class="nav-link">
						<i class="nav-icon fas fa-user-friends"></i>
						<p>
							Usia Pihak
							<i class="fas fa-angle-left right"></i>
						</p>
					</a>
					<ul class="nav nav-treeview">
						<li class="nav-item">
							<a href="<?php echo site_url('usia_pihak_p') ?>" class="nav-link">
								<i class="fas fa-user-alt nav-icon"></i>
								<p>Penggugat/Pemohon</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="<?php echo site_url('usia_pihak_t') ?>" class="nav-link">
								<i class="fas fa-user-alt nav-icon"></i>
								<p>Tergugat/Termohon</p>
							</a>
						</li>
					</ul>
				</li>

				<li class="nav-header">DATA &amp; PENGEMBANGAN</li>

				
			</ul>
		</nav>
		<!-- /.sidebar-menu -->
	</div>
	<!-- /.sidebar -->
</aside>
