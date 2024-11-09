<?php
session_start();
include('include/config.php');
if (strlen($_SESSION['alogin']) == 0) {
	header('location:index.php');
} else {
	date_default_timezone_set('Asia/Kolkata');
	$currentTime = date('d-m-Y h:i:s A', time());

	if (isset($_GET['del'])) {
		mysqli_query($con, "delete from products where id = '" . $_GET['id'] . "'");
		$_SESSION['delmsg'] = "Product deleted !!";
	}


?>
	<!DOCTYPE html>
	<html lang="en">

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Admin| Manage Users</title>
		<link type="text/css" href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<link type="text/css" href="bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
		<link type="text/css" href="css/theme.css" rel="stylesheet">
		<link type="text/css" href="images/icons/css/font-awesome.css" rel="stylesheet">
		<link type="text/css" href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,600italic,400,600' rel='stylesheet'>
	</head>

	<body>
		<?php include('include/header.php'); ?>

		<div class="wrapper">
			<div class="container">
				<div class="row">
					<?php include('include/sidebar.php'); ?>
					<div class="span9">
						<div class="content">

							<div class="module">
								<div class="module-head">
									<h3>Manage Users</h3>
								</div>

								<div class="module-body table">
									<?php if (isset($_GET['del'])) { ?>
										<div class="alert alert-error">
											<button type="button" class="close" data-dismiss="alert">×</button>
											<strong>Oh snap!</strong> <?php echo htmlentities($_SESSION['delmsg']); ?><?php echo htmlentities($_SESSION['delmsg'] = ""); ?>
										</div>
									<?php } ?>

									<br />


									<table cellpadding="0" cellspacing="0" border="0" class="datatable-1 table table-bordered table-striped	 display" width="100%">
										<thead>
											<tr>
												<th>Name</th>
												<th>Email </th>
											</tr>
										</thead>
										<tbody>
											<?php
											function getConversation($user_id, $conn)
											{

												$sql = "SELECT * FROM conversations
														WHERE user_1=? OR user_2=?
														ORDER BY conversation_id  DESC";

												$stmt = mysqli_prepare($conn, $sql);
												mysqli_stmt_bind_param($stmt, "ii", $user_id, $user_id);
												mysqli_stmt_execute($stmt);
												$result = mysqli_stmt_get_result($stmt);

												if (mysqli_num_rows($result) > 0) {
													$conversations = mysqli_fetch_all($result, MYSQLI_ASSOC);


													$user_data = [];
													# Looping through the conversations
													foreach ($conversations as $conversation) {
														# Determine the user to query
														$other_user_id = ($conversation['user_1'] == $user_id) ? $conversation['user_2'] : $conversation['user_1'];

														$sql2 = "SELECT * FROM users WHERE id=?";
														$stmt2 = mysqli_prepare($conn, $sql2);
														mysqli_stmt_bind_param($stmt2, "i", $other_user_id);
														mysqli_stmt_execute($stmt2);
														$result2 = mysqli_stmt_get_result($stmt2);
														$allConversations = mysqli_fetch_all($result2, MYSQLI_ASSOC);

														# Pushing the data into the array 
														array_push($user_data, $allConversations[0]);
													}

													return $user_data;
												} else {
													return [];
												}
											}

											function getUser($id, $conn)
											{
												$sql = "SELECT * FROM users WHERE id=?";
												$stmt = mysqli_prepare($conn, $sql);

												if ($stmt) {
													mysqli_stmt_bind_param($stmt, "i", $id);
													mysqli_stmt_execute($stmt);
													$result = mysqli_stmt_get_result($stmt);

													if (mysqli_num_rows($result) === 1) {
														$user = mysqli_fetch_assoc($result);
														return $user;
													} else {
														echo "No user found with id: " . htmlspecialchars($id);
														return [];
													}
												} else {
													echo "Failed to prepare statement.";
													return [];
												}
											}


											$user = getUser($_SESSION['id'], $con);

											$conversations = getConversation($user['id'], $con);
											?>

											<?php if (!empty($conversations)) { ?>
												<?php foreach ($conversations as $conversation) { ?>
													<tr onclick="window.location.href='conversation.php?id=<?= $conversation['id'] ?>'">
														<td><?= htmlspecialchars($conversation['name']) ?></td>
														<td><?= htmlspecialchars($conversation['email']) ?></td>
													</tr>
												<?php } ?>
											<?php } ?>

										</tbody>
									</table>
								</div>
							</div>
						</div><!--/.content-->
					</div><!--/.span9-->
				</div>
			</div><!--/.container-->
		</div><!--/.wrapper-->

		<?php include('include/footer.php'); ?>

		<script src="scripts/jquery-1.9.1.min.js" type="text/javascript"></script>
		<script src="scripts/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>
		<script src="bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
		<script src="scripts/flot/jquery.flot.js" type="text/javascript"></script>
		<script src="scripts/datatables/jquery.dataTables.js"></script>
		<script>
			$(document).ready(function() {
				$('.datatable-1').dataTable();
				$('.dataTables_paginate').addClass("btn-group datatable-pagination");
				$('.dataTables_paginate > a').wrapInner('<span />');
				$('.dataTables_paginate > a:first-child').append('<i class="icon-chevron-left shaded"></i>');
				$('.dataTables_paginate > a:last-child').append('<i class="icon-chevron-right shaded"></i>');
			});
		</script>
	</body>
<?php } ?>