<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8" />
	<title>LT Automate Sheet</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

	<link href="css/style.css?v=<?php echo time();?>" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</head>

<body class="loggedin">
	<nav class="navtop">
		<div>
			<h1>LT Automate Sheet</h1>
			<a href="home.php"><i class="fas fa-home"></i>Home</a>
			<?php if($_SESSION['loginuser']['role'] === 'admin') { ?>
			<a href="user_management.php"><i class="fas fa-home"></i>Users</a>
			<?php } ?>
			<a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
			<a href="profile.php"><i class="fas fa-user-circle"></i>Howdy, <?= ucfirst($_SESSION['loginuser']['name']); ?></a>
		</div>
	</nav>
	<?php if(isset($_SESSION['success']) && !empty($_SESSION['success'])) { ?>
		<div class='alert alert-success'> <?php echo $_SESSION['success']; ?> <button onclick="this.parentNode.style.display = 'none';">X</button></div>
	<?php } ?>
	<?php if(isset($_SESSION['error']) && !empty($_SESSION['error'])) { ?>
		<div class='alert alert-danger'> <?php echo $_SESSION['error']; ?> <button onclick="this.parentNode.style.display = 'none';">X</button></div>
	<?php } ?>
	<script>
	$(document).ready(function() {
		setTimeout(function(){ 
			<?php unset($_SESSION['error'], $_SESSION['success']);?>; 
		}, 5000);
	});
	</script>
