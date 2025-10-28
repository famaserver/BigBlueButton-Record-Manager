<?php
require_once __DIR__ . '/auth.php';

// KPIs
$numServers = 0;
$cronJobsCount = 0; // TODO: مقدار واقعی درصورت وجود لاگ/جدول

try {
	$pdo = getPDOConnection();
	// Ensure requests_log table exists
	$pdo->exec('CREATE TABLE IF NOT EXISTS requests_log (
		id INT AUTO_INCREMENT PRIMARY KEY,
		server_id INT NULL,
		action VARCHAR(50) NULL,
		created_at DATETIME NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4');
	$stmt = $pdo->query('SELECT COUNT(*) AS c FROM servers');
	$row = $stmt->fetch();
	$numServers = (int)($row && isset($row['c']) ? $row['c'] : 0);
	// Cron jobs = executed requests count
	$stmt = $pdo->query('SELECT COUNT(*) AS c FROM requests_log');
	$row = $stmt->fetch();
	$cronJobsCount = (int)($row && isset($row['c']) ? $row['c'] : 0);
	// Requests per server stats
	$perServer = [];
	$labels = [];
	$dataCounts = [];
	$q = $pdo->query('SELECT s.id, s.name, COUNT(r.id) AS cnt
		FROM servers s
		LEFT JOIN requests_log r ON r.server_id = s.id
		GROUP BY s.id, s.name
		ORDER BY s.id');
	foreach ($q->fetchAll() as $r) {
		$labels[] = $r['name'];
		$dataCounts[] = (int)$r['cnt'];
	}
	// Get servers list
	$serversQuery = $pdo->query('SELECT id, name, url FROM servers ORDER BY id DESC');
	$servers = $serversQuery->fetchAll();
} catch (Exception $e) {
	$numServers = 0;
	$servers = [];
}

// Dates (Jalali + Gregorian)
$gregorianDate = date('Y-m-d');
$jalaliDate = $gregorianDate;
if (class_exists('IntlDateFormatter')) {
	$fmt = @new IntlDateFormatter('fa_IR@calendar=persian', IntlDateFormatter::LONG, IntlDateFormatter::NONE, date_default_timezone_get());
	if ($fmt) {
		$jalaliDate = $fmt->format(time());
	}
}
?>
<!DOCTYPE html>
<html lang="en" dir="rtl" >
	
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>

		<meta charset="utf-8">
		<meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport">
		<meta name="description" content="Spruha -  Admin Panel laravel Dashboard Template">
		<meta name="author" content="Spruko Technologies Private Limited">
		<meta name="keywords" content="admin laravel template, template laravel admin, laravel css template, best admin template for laravel, laravel blade admin template, template admin laravel, laravel admin template bootstrap 4, laravel bootstrap 4 admin template, laravel admin bootstrap 4, admin template bootstrap 4 laravel, bootstrap 4 laravel admin template, bootstrap 4 admin template laravel, laravel bootstrap 4 template, bootstrap blade template, laravel bootstrap admin template">

        <!-- Favicon -->
		<link rel="icon" href="assets/img/brand/favicon.ico" type="image/x-icon"/>

		<!-- Title -->
		<title>famabutton</title>

		<!-- Bootstrap css-->
		<link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet"/>

		<!-- Icons css-->
		<link href="assets/plugins/web-fonts/icons.css" rel="stylesheet"/>
		<link href="assets/plugins/web-fonts/font-awesome/font-awesome.min.css" rel="stylesheet">
		<link href="assets/plugins/web-fonts/plugin.css" rel="stylesheet"/>

		<!-- Style css-->
		<link href="assets/css-rtl/style/style.css" rel="stylesheet">
		<link href="assets/css-rtl/skins.css" rel="stylesheet">
		<link href="assets/css-rtl/dark-style.css" rel="stylesheet">
		<link href="assets/css-rtl/colors/default.css" rel="stylesheet">

		<!-- Color css-->
		<link id="theme" rel="stylesheet" type="text/css" media="all" href="assets/css-rtl/colors/color.css">

		<!-- Select2 css -->
		<link href="assets/plugins/select2/css/select2.min.css" rel="stylesheet">

				<!-- Mutipleselect css-->
		<link rel="stylesheet" href="assets/plugins/multipleselect/multiple-select.css">

		<!-- Sidemenu css-->
		<link href="assets/css-rtl/sidemenu/sidemenu.css" rel="stylesheet">

		<!-- Switcher css-->
		<link href="assets/switcher/css/switcher-rtl.css" rel="stylesheet">
		<link href="assets/switcher/demo.css" rel="stylesheet">
		

		

	</head>

<body class="main-body leftmenu">



<!-- Loader -->
<div id="global-loader">
	<img src="assets/img/loader.svg" class="loader-img" alt="لودر">
</div>
<!-- End Loader -->

<!-- Page -->
<div class="page">

	<!-- Sidemenu -->
	<div class="main-sidebar main-sidebar-sticky side-menu">
		<div class="sidemenu-logo">
			<a class="main-logo" href="index.html">
				<img src="assets/img/brand/logo-famaserver.png" class="header-brand-img desktop-logo" alt="لوگو">
				<img src="assets/img/brand/logo-famaserver.png" class="header-brand-img icon-logo" alt="لوگو">
				<img src="assets/img/brand/logo-famaserver.png" class="header-brand-img desktop-logo theme-logo" alt="لوگو">
				<img src="assets/img/brand/logo-famaserver.png" class="header-brand-img icon-logo theme-logo" alt="لوگو">
			</a>
		</div>
		<div class="main-sidebar-body">
			<ul class="nav">
				<li class="nav-header"><span class="nav-label">داشبورد</span></li>
				<li class="nav-item">
					<a class="nav-link" href="index.php"><span class="shape1"></span><span class="shape2"></span><i class="ti-home sidemenu-icon"></i><span class="sidemenu-label">داشبورد</span></a>
				</li>
				
				<li class="nav-item">
					<a class="nav-link with-sub" href="#"><span class="shape1"></span><span class="shape2"></span><i class="ti-write sidemenu-icon"></i><span class="sidemenu-label">سرورها</span><i class="angle fe fe-chevron-left"></i></a>
					<ul class="nav-sub">
						<li class="nav-sub-item">
							<a class="nav-sub-link" href="servers.php">لیست سرور ها</a>
						</li>
						<li class="nav-sub-item">
							<a class="nav-sub-link" href="add_server.php">افزودن سرور</a>
						</li>
					</ul>
				</li>
				
				<li class="nav-item">
					<a class="nav-link" href="logout.php"><span class="shape1"></span><span class="shape2"></span><i class="fe fe-log-out sidemenu-icon"></i><span class="sidemenu-label">خروج</span></a>
				</li>
				
				
				
				
				
				</li>
			</ul>
		</div>
	</div>

	<!-- Main Content-->
	<div class="main-content side-content pt-0">
		<div class="container-fluid">
			<div class="inner-body">


				<!-- Page Header -->
				<div class="page-header">
					<div>
						<h2 class="main-content-title tx-24 mg-b-5">به داشبورد خوش آمدید</h2>
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="#">خانه</a></li>
							<li class="breadcrumb-item active" aria-current="page">داشبورد پروژه</li>
						</ol>
					</div>
					
				</div>
				<!-- End Page Header -->

				<!--Row-->
				<div class="row row-sm">
					<div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
						<div class="card custom-card">
									<div class="card-body">
										<div class="card-item">
											<div class="card-item-icon card-icon">
												<svg class="text-primary" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="24" viewBox="0 0 24 24" width="24"><g><rect height="14" opacity=".3" width="14" x="5" y="5"></rect><g><rect fill="none" height="24" width="24"></rect><g><path d="M19,3H5C3.9,3,3,3.9,3,5v14c0,1.1,0.9,2,2,2h14c1.1,0,2-0.9,2-2V5C21,3.9,20.1,3,19,3z M19,19H5V5h14V19z"></path><rect height="5" width="2" x="7" y="12"></rect><rect height="10" width="2" x="15" y="7"></rect><rect height="3" width="2" x="11" y="14"></rect><rect height="2" width="2" x="11" y="10"></rect></g></g></g></svg>
											</div>
											<div class="card-item-title mb-2">
                                                <label class="main-content-label tx-13 font-weight-bold mb-1">تعداد سرورها</label>
											</div>
											<div class="card-item-body">
												<div class="card-item-stat">
                                                    <h4 class="font-weight-bold"><?php echo $numServers; ?></h4>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
					<div class="col-sm-12 col-md-6 col-lg-6 col-xl-4">
						<div class="card custom-card">
									<div class="card-body">
										<div class="card-item">
											<div class="card-item-icon card-icon">
												<svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"></path><path d="M12 4c-4.41 0-8 3.59-8 8 0 1.82.62 3.49 1.64 4.83 1.43-1.74 4.9-2.33 6.36-2.33s4.93.59 6.36 2.33C19.38 15.49 20 13.82 20 12c0-4.41-3.59-8-8-8zm0 9c-1.94 0-3.5-1.56-3.5-3.5S10.06 6 12 6s3.5 1.56 3.5 3.5S13.94 13 12 13z" opacity=".3"></path><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zM7.07 18.28c.43-.9 3.05-1.78 4.93-1.78s4.51.88 4.93 1.78C15.57 19.36 13.86 20 12 20s-3.57-.64-4.93-1.72zm11.29-1.45c-1.43-1.74-4.9-2.33-6.36-2.33s-4.93.59-6.36 2.33C4.62 15.49 4 13.82 4 12c0-4.41 3.59-8 8-8s8 3.59 8 8c0 1.82-.62 3.49-1.64 4.83zM12 6c-1.94 0-3.5 1.56-3.5 3.5S10.06 13 12 13s3.5-1.56 3.5-3.5S13.94 6 12 6zm0 5c-.83 0-1.5-.67-1.5-1.5S11.17 8 12 8s1.5.67 1.5 1.5S12.83 11 12 11z"></path></svg>
											</div>
											<div class="card-item-title mb-2">
                                                <label class="main-content-label tx-13 font-weight-bold mb-1">کرون‌جاب</label>
											</div>
											<div class="card-item-body">
												<div class="card-item-stat">
                                                    <h4 class="font-weight-bold"><?php echo $cronJobsCount; ?></h4>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
					<div class="col-sm-12 col-md-12 col-lg-12 col-xl-4">
						<div class="card custom-card">
									<div class="card-body">
										<div class="card-item">
											<div class="card-item-icon card-icon">
												<svg class="text-primary" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"></path><path d="M12 4c-4.41 0-8 3.59-8 8s3.59 8 8 8 8-3.59 8-8-3.59-8-8-8zm1.23 13.33V19H10.9v-1.69c-1.5-.31-2.77-1.28-2.86-2.97h1.71c.09.92.72 1.64 2.32 1.64 1.71 0 2.1-.86 2.1-1.39 0-.73-.39-1.41-2.34-1.87-2.17-.53-3.66-1.42-3.66-3.21 0-1.51 1.22-2.48 2.72-2.81V5h2.34v1.71c1.63.39 2.44 1.63 2.49 2.97h-1.71c-.04-.97-.56-1.64-1.94-1.64-1.31 0-2.1.59-2.1 1.43 0 .73.57 1.22 2.34 1.67 1.77.46 3.66 1.22 3.66 3.42-.01 1.6-1.21 2.48-2.74 2.77z" opacity=".3"></path><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.31-8.86c-1.77-.45-2.34-.94-2.34-1.67 0-.84.79-1.43 2.1-1.43 1.38 0 1.9.66 1.94 1.64h1.71c-.05-1.34-.87-2.57-2.49-2.97V5H10.9v1.69c-1.51.32-2.72 1.3-2.72 2.81 0 1.79 1.49 2.69 3.66 3.21 1.95.46 2.34 1.15 2.34 1.87 0 .53-.39 1.39-2.1 1.39-1.6 0-2.23-.72-2.32-1.64H8.04c.1 1.7 1.36 2.66 2.86 2.97V19h2.34v-1.67c1.52-.29 2.72-1.16 2.73-2.77-.01-2.2-1.9-2.96-3.66-3.42z"></path></svg>
											</div>
											<div class="card-item-title  mb-2">
                                                <label class="main-content-label tx-13 font-weight-bold mb-1">تاریخ</label>
											</div>
											<div class="card-item-body">
												<div class="card-item-stat">
                                                    <h4 class="font-weight-bold"><?php echo htmlspecialchars($jalaliDate, ENT_QUOTES, 'UTF-8'); ?></h4>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
					</div>
				</div>
				<!--End row-->

				<!-- Row - لیست سرورها -->
				<div class="row row-sm">
					<div class="col-lg-12">
						<div class="card custom-card">
							<div class="card-body">
								<div>
									<h6 class="main-content-label mb-1">لیست سرورها</h6>
									<p class="text-muted card-sub-title">مشاهده و مدیریت سرورهای موجود</p>
								</div>
								<div class="table-responsive">
									<table class="table text-nowrap text-md-nowrap table-bordered mg-b-0">
										<thead>
										<tr>
											<th>نام سرور</th>
											<th>URL</th>
											<th class="text-center">عملیات</th>
										</tr>
										</thead>
										<tbody>
										<?php if (empty($servers)): ?>
											<tr><td colspan="3" class="text-center">سروری ثبت نشده است.</td></tr>
										<?php else: ?>
											<?php foreach ($servers as $server): ?>
												<tr>
													<td><?php echo htmlspecialchars($server['name'], ENT_QUOTES, 'UTF-8'); ?></td>
													<td class="text-left" dir="ltr" style="max-width:420px; white-space:normal; word-break:break-all;">
														<?php echo htmlspecialchars($server['url'], ENT_QUOTES, 'UTF-8'); ?>
													</td>
													<td class="text-center">
														<a class="btn btn-sm btn-info" href="view_record.php?server_id=<?php echo (int)$server['id']; ?>&status=published">پابلیش</a>
														<a class="btn btn-sm btn-warning" href="view_record.php?server_id=<?php echo (int)$server['id']; ?>&status=processing">پردازش</a>
														<a class="btn btn-sm btn-primary" href="servers.php">مدیریت کامل</a>
													</td>
												</tr>
											<?php endforeach; ?>
										<?php endif; ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- End Row -->


			</div>
		</div>
	</div>
	<!-- End Main Content-->



	<!-- Main Footer-->
	<div class="main-footer text-center">
		<div class="container">
			<div class="row row-sm">
				<div class="col-md-12">
					<span>کپی رایت © 1399  . طراحی شده توسط <a href="#">Themefix</a> کلیه حقوق محفوظ است.</span>
				</div>
			</div>
		</div>
	</div>
	<!--End Footer-->
</div>
<!-- End Page -->

<!-- Back-to-top -->
<a href="#top" id="back-to-top"><i class="fe fe-arrow-up"></i></a>

<!-- Jquery js-->
<script src="assets/plugins/jquery/jquery.min.js"></script>

<!-- Bootstrap js-->
<script src="assets/plugins/bootstrap/js/popper.min.js"></script>
<script src="assets/plugins/bootstrap/js/bootstrap-rtl.js"></script>

<!-- Perfect-scrollbar js -->
<script src="assets/plugins/perfect-scrollbar/perfect-scrollbar.min-rtl.js"></script>

<!-- Sidemenu js -->
<script src="assets/plugins/sidemenu/sidemenu-rtl.js"></script>

<!-- Select2 js-->
<script src="assets/plugins/select2/js/select2.min.js"></script>

<!-- Internal Chart.Bundle js-->
<script src="assets/plugins/chart.js/Chart.bundle.min.js"></script>

<!-- Peity js-->
<script src="assets/plugins/peity/jquery.peity.min.js"></script>

<!-- Internal Morris js -->
<script src="assets/plugins/raphael/raphael.min.js"></script>
<script src="assets/plugins/morris.js/morris.min.js"></script>

<!-- Circle Progress js-->
<script src="assets/js/circle-progress.min.js"></script>
<script src="assets/js/chart-circle.js"></script>

<!-- Internal Dashboard js-->
<script src="assets/js/index.js"></script>

<!-- Sticky js -->
<script src="assets/js/sticky.js"></script>

<!-- Custom js -->
<script src="assets/js/custom.js"></script>

<!-- Switcher js -->
<script src="assets/switcher/js/switcher-rtl.js"></script>


</body>
</html>