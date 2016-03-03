<!DOCTYPE HTML>
<?PHP
	require 'functions.php';
	check_logon();
	connect();
			
	//Make array for exporting data
	$rep_year = date("Y",time());
	$rep_month = date("m",time());
	$_SESSION['rep_export'] = array();
	$_SESSION['rep_exp_title'] = $rep_year.'-'.$rep_month.'_empl-current';
	
	$query_emplcurr = get_emplcurr();
?>
	
<html>
	<?PHP include_Head('Current Employees',1) ?>	
	
	<body>
		<!-- MENU -->
		<?PHP include_Menu(7); ?>
		<div id="menu_main">
			<!-- <a href="empl_search.php">Search</a> -->
			<a href="empl_new.php">New Employee</a>
			<a href="empl_curr.php" id="item_selected">Current Employees</a>
			<a href="empl_past.php">Former Employees</a>
		</div>
	
		<!-- TABLE: Active Customers -->
		<table id="tb_table">				
			<colgroup>
				<col width="8%" />	
				<col width="17%" />
				<col width="8%" />
				<col width="8%" />
				<col width="17%" />
				<col width="17%" />
				<col width="17%" />
				<col width="8%" />
			</colgroup>
			<tr>
				<form class="export" action="rep_export.php" method="post">
					<th class="title" colspan="8">Current Employees
					<!-- Export Button -->
					<input type="submit" name="export_rep" value="Export" />
					</th>
				</form>
			</tr>
			<tr>
				<th>Empl. No.</th>
				<th>Name</th>					
				<th>Gender</th>
				<th>DoB</th> 
				<th>Address</th> 
				<th>Phone No.</th>
				<th>Email</th>
				<th>In</th>
			</tr>
			<?PHP		
			$color = 0;
			while ($row_emplcurr = mysql_fetch_assoc($query_emplcurr)){					
			
				tr_colored($color);	//Alternating row colors
				echo '<td>
								<a href="employee.php?empl='.$row_emplcurr['empl_id'].'">'.$row_emplcurr['empl_no'].'</a>
							</td>
							<td>'.$row_emplcurr['empl_name'].'</td>
							<td>'.$row_emplcurr['emplsex_name'].'</td>
							<td>'.date("d.m.Y",$row_emplcurr['empl_dob']).'</td>
							<td>'.$row_emplcurr['empl_address'].'</td>
							<td>'.$row_emplcurr['empl_phone'].'</td>
							<td>'.$row_emplcurr['empl_email'].'</td>
							<td>'.date("d.m.Y",$row_emplcurr['empl_in']).'</td>
						</tr>';
				
				array_push($_SESSION['rep_export'], array("Empl. No." => $row_emplcurr['empl_no'], "Employee Name" => $row_emplcurr['empl_name'], "DoB" => date("d.m.Y",$row_emplcurr['empl_dob']), "Gender" => $row_emplcurr['emplsex_name'], "Address" => $row_emplcurr['empl_address'], "Phone No." => $row_emplcurr['empl_phone'], "Email" => $row_emplcurr['empl_email'], "Empl. In" => date("d.m.Y",$row_emplcurr['empl_in'])));
			}
			?>
		</table>
	</body>
</html>