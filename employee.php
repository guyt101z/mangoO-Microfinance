<!DOCTYPE HTML>
<?PHP
	require 'functions.php';
	check_logon();
	connect();
	
	//Generate timestamp
	$timestamp = time();
	
	// Get EMPL_ID
	if(isset($_GET['empl'])) $_SESSION['empl_id'] = sanitize($_GET['empl']);
	else header('Location:empl_curr.php');
	
	//UPDATE-Button
	if (isset($_POST['update'])){
				
		//Sanitize user input
		$empl_no = sanitize($_POST['empl_no']);
		$empl_name = sanitize($_POST['empl_name']);
		$empl_dob = strtotime(sanitize($_POST['empl_dob']));
		$emplsex_id = sanitize($_POST['emplsex_id']);
		$empl_address = sanitize($_POST['empl_address']);
		$empl_phone = sanitize($_POST['empl_phone']);
		$empl_email = sanitize($_POST['empl_email']);
		$empl_active = sanitize($_POST['empl_active']);
		
		//Update EMPLOYEE
		$sql_update = "UPDATE employee SET empl_no = '$empl_no', empl_name = '$empl_name', empl_dob = $empl_dob, emplsex_id = $emplsex_id, empl_address = '$empl_address', empl_phone = '$empl_phone', empl_email = '$empl_email', empl_active = '$empl_active', empl_lastupd = $timestamp, user_id = $_SESSION[log_id] WHERE empl_id = $_SESSION[empl_id]";
		$query_update = mysql_query($sql_update);
		check_sql($query_update);
		
		// Forward to this page
		header('Location: employee.php?empl='.$_SESSION['empl_id']);
	}
	
	//Select Sexes from EMPLSEX for dropdown-menu
	$sql_sex = "SELECT * FROM emplsex";
	$query_sex = mysql_query($sql_sex);
	check_sql($query_sex);
	
	//Select employee from EMPLOYEE
	$sql_empl = "SELECT * FROM employee, user WHERE employee.user_id = user.user_id AND empl_id = '$_SESSION[empl_id]'";
	$query_empl = mysql_query($sql_empl);
	check_sql($query_empl);
	$result_empl = mysql_fetch_assoc($query_empl);
?>

<html>
	<?PHP include_Head('Employee',0) ?>		
		<script>
			function validate(form){
				fail = validateName(form.empl_name.value)
				fail += validateDob(form.empl_dob.value)
				fail += validateAddress(form.empl_address.value)
				fail += validatePhone(form.empl_phone.value)
				fail += validateEmail(form.empl_email.value)
				if (fail == "") return true
				else { alert(fail); return false }
			}
		</script>
		<script src="functions_validate.js"></script>
	</head>
	
	<body>
		<!-- MENU -->
		<?PHP include_Menu(7); ?>
		<div id="menu_main">
			<!-- <a href="empl_search.php">Search</a> -->
			<a href="empl_new.php">New Employee</a>
			<a href="empl_curr.php">Current Employees</a>
			<a href="empl_past.php">Former Employees</a>
		</div>
		
		<div class="content_center">
			<!-- HEADING -->
			<p class="heading" style="margin-bottom:.3em;">
				<?PHP echo $result_empl['empl_name'].' ('.$result_empl['empl_no'].')'; ?>
			</p>
			
			<form action="employee.php" method="post" onSubmit="return validate(this)">
				
				<table id ="tb_fields" style="border-spacing:0.1em 1.25em;">
					<colgroup>
						<col width="9%"/>
						<col width="25%"/>
						<col width="8%"/>
						<col width="25%"/>
						<col width="8%"/>
						<col width="25%"/>
					</colgroup>					
					<?PHP					
						echo '<tr>
										<td rowspan="4" colspan="2" style="text-align:center; vertical-align:top;">
										<a href="empl_new_pic.php?from=employee">';
						if (isset($result_empl['empl_pic'])) 
							echo '<img src="'.$result_empl['empl_pic'].'" title="Employee\'s picture">';
						else {
								if ($result_empl['emplsex_id'] == 2) echo '<img src="ico/custpic_f.png" title="Upload new picture" />';
								else echo '<img src="ico/custpic_m.png" title="Upload new picture" />';
						}
						echo '	</a>
										</td>
										<td>Cust No:</td>
										<td><input type="text" name="empl_no" value="'.$result_empl['empl_no'].'" tabindex="1" /></td>
										<td>Phone No:</td>
										<td><input type="text" name="empl_phone" value="'.$result_empl['empl_phone'].'" tabindex="6" /></td>
									</tr>';
						echo '<tr>
										<td>Name:</td>
										<td><input type="text" name="empl_name" value="'.$result_empl['empl_name'].'" tabindex="2" /></td>
										<td>E-Mail:</td>
										<td><input type="text" name="empl_email" value="'.$result_empl['empl_email'].'" placeholder="abc@xyz.com" tabindex="7" /></td>
									</tr>
									<tr>
										<td>Gender:</td>
										<td>
											<select name="emplsex_id" size="1" tabindex="3">';
								while ($row_sex = mysql_fetch_assoc($query_sex)){
									if($row_sex ['emplsex_id'] == $result_empl['emplsex_id']){
										echo '<option selected value="'.$row_sex['emplsex_id'].'">'.$row_sex['emplsex_name'].'</option>';
									}
									else echo '<option value="'.$row_sex['emplsex_id'].'">'.$row_sex['emplsex_name'].'</option>';
								}
								echo '</select>
										</td>
										<td>Employed since:</td>
										<td><input type="text" name="empl_in" value="'.date("d.m.Y", $result_empl['empl_in']).'" disabled="disabled" /></td>
									</tr>
									<tr>
										<td>DoB:</td>
										<td><input type="text" id="datepicker" name="empl_dob" value="'.date("d.m.Y",$result_empl['empl_dob']).'" placeholder="DD.MM.YYYY" tabindex="4" /></td>
										<td>Employed until:</td>
										<td><input type="text" id="datepicker2" name="empl_out" placeholder="DD.MM.YYYY" tabindex="4" /></td>
									</tr>
									<tr>
										<td>Last updated:</td>
										<td><input type="text" disabled="diabled" value="'.date("d.m.Y", $result_empl['empl_lastupd']).'" /></td>
										<td>Address:</td>
										<td><input type="text" name="empl_address" value="'.$result_empl['empl_address'].'" placeholder="Place of Residence" tabindex="5" /></td>
										<td>Active:</td>
										<td><input type="checkbox" name="empl_active" value="1" tabindex="13"'; 
										if ($result_empl['empl_active']==1) echo ' checked="checked"';
										echo ' />
										</td>
									</tr>
									<tr>
										<td colspan="6" class="center">
											<input type="submit" name="update" value="Save Changes" tabindex="14" />
										</td>
									</tr>';
					?>
				</table>
			</form>
		</div>
	</body>
</html>