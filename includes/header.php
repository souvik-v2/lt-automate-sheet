<?php
	$page = basename($_SERVER['PHP_SELF']);
	$page_arr = ['home', 'user', 'project', 'sprint', 'profile'];
	$home = $user = $project = $sprint = $profile = '';
	foreach($page_arr as $p) {
		if(strpos($page, $p) !== false){
			$$p = 'active';
		} 
	}
	switch ($page) {
		case 'home.php':
			$home = 'active';
			break;
		case 'user_management.php':
			$user = 'active';
			break;
		case 'project.php':
			$project = 'active';
			break;
		case 'sprint_view.php':
			$sprint = 'active';
			break;
		case 'profile.php':
			$profile = 'active';
			break;
	};
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8" />
	<title>LT Report</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap4.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	<link href="//www.v2solutions.com/images/fav.ico" type="image/x-icon" rel="icon">
	<link rel="stylesheet" href="css/style.css?v=<?= time(); ?>">
	<!-- jQuery first, then Popper.js, then Bootstrap JS -->
	<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

	<script src="js/jquery.dataTables.min.js"></script>
	<script src="js/dataTables.bootstrap4.min.js"></script>
	<script src="js/chart.min.js"></script>
	<script src="js/chartjs-plugin-datalabels@2.0.0"></script>
</head>

<body class="loggedin">
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
		<a class="navbar-brand" href="home.php">LT Report</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarNavDropdown">
			<ul class="navbar-nav ml-auto">
				<li class="nav-item <?=$home;?>">
					<a class="nav-link" href="home.php">Home</a>
				</li>
				<?php if ($_SESSION['loginuser']['role'] === 'admin') { ?>
					<li class="nav-item <?=$user;?>">
						<a class="nav-link" href="user_management.php">User</a>
					</li>
				<?php } ?>
				<li class="nav-item <?=$project;?>">
					<a class="nav-link" href="project.php">Project</a>
				</li>
				<li class="nav-item <?=$sprint;?>">
					<a class="nav-link" href="sprint_view.php">Sprint</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="logout.php">Logout</a>
				</li>
				<li class="nav-item <?=$profile;?>">
					<a class="nav-link" href="profile.php">Howdy, <?= ucfirst($_SESSION['loginuser']['name']); ?></a>
				</li>
			</ul>
		</div>
	</nav>

	<?php if (isset($_SESSION['success']) && !empty($_SESSION['success'])) { ?>
		<div class='alert alert-success'> <?php echo $_SESSION['success']; ?> <button onclick="this.parentNode.style.display = 'none';">X</button></div>
	<?php } ?>
	<?php if (isset($_SESSION['error']) && !empty($_SESSION['error'])) { ?>
		<div class='alert alert-danger'> <?php echo $_SESSION['error']; ?> <button onclick="this.parentNode.style.display = 'none';">X</button></div>
	<?php } ?>
	<script>
	$(document).ready(function() {
		setTimeout(function() {
			<?php unset($_SESSION['error'], $_SESSION['success']); ?>;
		}, 5000);
	});
	</script>