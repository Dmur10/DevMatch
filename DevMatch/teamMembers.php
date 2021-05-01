<?php
		require("navBar.php");
        require("database.php");

		if(isset($_REQUEST['viewMembers'])) {
			$teamID = $_REQUEST['teamID'];
			$memberType = intval($_REQUEST['memberType']);
		}

		if(isset($_REQUEST['memberToRemove'])) {
			$deleteQuery = $connection->prepare('DELETE FROM members WHERE UserID=? AND TeamID=?');
            $deleteQuery->bind_param('ii', $_REQUEST['memberToRemove'], $teamID);
            $deleteQuery->execute();
		}

		//retrieves connections already validated
        $members = $connection->prepare('SELECT UserID FROM members WHERE TeamID = ?');
        $members->bind_param('s', $teamID);
        $members->execute();

		$membersRes = $members->get_result();

		if (mysqli_num_rows($membersRes)==0 ) {
			$membersRes = "";
		}
		displaySearchResultProfile($membersRes,$teamID, $memberType);
		

		
	// Display search result for the different search
	// Not ideal, relevant field are hard coded and each search has a different display function.
	function displaySearchResultProfile($members, $teamID, $memberType) {	 

		echo ('
		<div class="container">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h2 class="panel-title">Team Members</h2>
				</div>
			<table class="table table-bordered table-condensed table-hover">
			<thead class="thead-dark">
				<tr>');

					echo('
					<th>Name</th>
					<th>Gender</th>
					<th>Country</th>
					<th>Employment</th>');
			echo ('
				</tr>
			</thead>');

		if($members !== "") {

			while($row = mysqli_fetch_assoc($members)) {
				include("database.php");

				$searchTerm = $row['UserID'];

				$searchUser = $connection->prepare('SELECT profiles.* FROM profiles INNER JOIN users ON profiles.UserID = users.UserID WHERE profiles.UserID = ? AND Banned=0');
				$searchUser->bind_param('s',$searchTerm);
				$searchUser->execute();

				$resultUser = $searchUser->get_result();

				while(	$user_row = mysqli_fetch_assoc($resultUser)){
					echo '<tr class="clickableRow" data-href="#">
						<td>
							'.$user_row['FirstName'].' '.$user_row['LastName'].'
						</td>
						<td>
							'.$user_row['Gender'].'
						</td>
						<td>
							'.$user_row['Country'].'
						</td>
						<td>
							'.$user_row['Employment'].'
						</td>
						<td>
							<form action="profile.php"target="_parent" method="post">
								<input type="hidden" name="profileSelected" value=' .$user_row['UserID'].'>
								<input type="submit" name="View" value="View" />
							</form>
						</td>';
						if($memberType === 2) {
						echo '<td>
							<form action="" method="POST">
								<input type="hidden" name="teamID" value='. $teamID .'>
								<input type="hidden" name="memberToRemove" value='.$user_row['UserID'].'>
								<input type="submit" name="Remove" value="Remove"/>
							</form>
						</td>';
						}
					echo'</tr>';
				}
			}
		}
		
		echo (' </table>
				</div>
				</div>');
	}

	?>
    
    <html>
    <head>
      <link rel = "stylesheet"
			href = "https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css"
			integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl"
			crossorigin="anonymous">
    </head>

    <body>
      
    </body>
</html>