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
							Laporan Monitoring BHT
							<i class="fas fa-angle-left right"></i>
						</p>
					</a>
					<ul class="nav nav-treeview">
						
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
						
					</ul>
				</li>

				<!-- Data & Pengembangan -->			

				<li class="nav-header">DATA &amp; PENGEMBANGAN</li>

				
			</ul>
		</nav>
		<!-- /.sidebar-menu -->
	</div>
	<!-- /.sidebar -->
</aside>
