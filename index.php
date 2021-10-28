<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="author" content="V2Solutions">
	<title>LT Report - Login</title>
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link
      rel="stylesheet"
      href="https://use.fontawesome.com/releases/v5.7.1/css/all.css"
    />
    <link rel="stylesheet" href="css/style.css?v=<?=time();?>">
    <link href="//www.v2solutions.com/images/fav.ico" type="image/x-icon" rel="icon">
</head>
<body>
<?php if (isset($_SESSION['success']) && !empty($_SESSION['success'])) { ?>
		<div class='alert alert-success'> <?php echo $_SESSION['success']; ?> <button onclick="this.parentNode.style.display = 'none';">X</button></div>
	<?php } ?>
	<?php if (isset($_SESSION['error']) && !empty($_SESSION['error'])) { ?>
		<div class='alert alert-danger'> <?php echo $_SESSION['error']; ?> <button onclick="this.parentNode.style.display = 'none';">X</button></div>
	<?php } ?>
	<script>
		$(document).ready(function() {
			$('form').attr('autocomplete', 'off');
			setTimeout(function() {
				<?php unset($_SESSION['error'], $_SESSION['success']); ?>;
			}, 5000);
		});
	</script>
	<!-- Main Content -->
	<div class="container-fluid">
		<div class="row main-content bg-success text-center">
			<div class="col-md-4 text-center company__info">
				<h4 class="company_title"><img src="images/v-2-logo.svg" alt="V2-logo"/></h4>
			</div>
			<div class="col-md-8 col-xs-12 col-sm-12 login_form ">
				<div class="container-fluid">
					<div class="row">
						<h2>Log In</h2>
					</div>
					<div class="row">
						<form action="authenticate.php" method="post" class="form-group">
							<div class="row">
								<input type="text" autocomplete="nope" name="username" id="username" class="form__input" placeholder="Username" required />
							</div>
							<div class="row">
								<input type="password" autocomplete="new-password" name="password" id="password" class="form__input" placeholder="Password" required />
							</div>
							<div class="row">
								<input type="submit" value="Submit" class="btn">
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- Footer -->
	<div class="container-fluid text-center footer">
		Coded with &hearts; by <a href="https://www.v2solutions.com/">V2Solutions.</a></p>
	</div>
</body>