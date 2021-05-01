<?php
  require("navBar.php");
?>
<html>
    <head>
      <link rel = "stylesheet"
			href = "https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css"
			integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl"
			crossorigin="anonymous">
      <link href="http://netdna.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet">
      <link rel="stylesheet" href="CSS/profile.css">

    </head>
    <body>

  

    <?php
        require("database.php");

        $user = $_SESSION['userID'];
        $userType = intval($_SESSION['userType']);
    
        if(isset($_REQUEST['update'])) {
          $team = $_REQUEST['updateTeam'];
          $updateTeamName = $_REQUEST['updateTeamName'];
          $updateTeamDescription = $_REQUEST['updateTeamDescription'];

          $updateQuery = $connection->prepare('UPDATE teams SET Name=?, Description=? WHERE TeamID=?');
          $updateQuery->bind_param('ssi',$updateTeamName,$updateTeamDescription,$team);
          $updateQuery->execute();
        }
        else if(isset($_REQUEST['teamProfileSelected'])) {
          $team = $_REQUEST['teamProfileSelected'];
        }

        $memTypeQuery = $connection->prepare('SELECT MTypeID FROM members WHERE TeamID=? AND UserID=?');
        $memTypeQuery->bind_param('ii',$team, $user);
        $memTypeQuery->execute();

        $memTypeResult = $memTypeQuery->get_result();
        if(!mysqli_num_rows($memTypeResult)) {
          $memberType = 0;
        } else {
          $memTypeResult = mysqli_fetch_assoc($memTypeResult);
          $memberType = intval($memTypeResult['MTypeID']);
        }
  
        $searchTerm =  $team;

        //mail and password
        $teamSearch = $connection->prepare('SELECT Name, Description, CreatorID FROM teams WHERE TeamID = ?');
        $teamSearch->bind_param('s',$searchTerm);
        $teamSearch->execute();
        $result = $teamSearch->get_result();
        $row = mysqli_fetch_assoc($result);
        
        $name = $row['Name'];
        $description = $row['Description'];
        $creatorID = $row['CreatorID'];

        $creatorSearch = $connection->prepare('SELECT FirstName, LastName FROM profiles WHERE UserID = ?');
        $creatorSearch->bind_param('s', $creatorID);
        $creatorSearch->execute();
        $result = $creatorSearch->get_result();
        $row = mysqli_fetch_assoc($result);

        $creatorName = $row['FirstName'] . " " . $row['LastName'];
      
      if(isset($_POST['update'])) {
        //update databese validate and reload the page
      }
      if(isset($_POST['cancel'])) {
         // it will refresh the page automatically
      }

echo '
<div class="container">
<div class="row gutters">
<div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12">
<div class="card h-100">
	<div class="card-body">
		<div class="account-settings">
			<div class="user-profile">
				<div class="user-avatar">
					<img src=" ' . '" alt="Maxwell Admin">
				</div>
				<h5 class="user-name">' . $name  .'</h5>
				<h6 class="user-email">' . $creatorName . '</h6>
			</div>
			<div class="about">
				<h5>About</h5>';

   if($description==null)
   echo'
        <textarea type="textarea" class="form-control" id="description" placeholder="Description" rows="3"></textarea>';
    else
     echo ' <textarea type="textarea" class="form-control" id="description" value="' . $description .'" rows="3"></textarea>';
     echo '
            <form action="teamMembers.php"target="_parent" method="post">
              <input type="hidden" name="memberType" value="'.$memberType.'">
              <input type="hidden" name="teamID" value="' .$team.'">
              <input type="submit" name="viewMembers" value="View Members" />
            </form>
            <form action="teamVacancies.php"target="_parent" method="post">
              <input type="hidden" name="memberType" value="'.$memberType.'">
              <input type="hidden" name="teamID" value="' .$team.'">
              <input type="submit" name="viewVacancies" value="View Vacancies" />
            </form>
        </div>
		</div>
	</div>
</div>
</div>
<div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 col-12">
<div class="card h-100">
	<div class="card-body">';
  if($userType===2 || $memberType===2) {
  echo'
    <form action="" method="POST">';
  }
      echo'<div class="row gutters">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
          <h6 class="mb-2 text-primary">Team Details</h6>
        </div>
        <div class="col-xl-8 col-lg-8 col-md-8 col-sm-8 col-12">
          <div class="form-group">
            <label for="fullName">Team Name</label>
            <input type="text" class="form-control" name="updateTeamName" id="teamName" value="' . $name . '">
          </div>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12">
          <div class="form-group">
            <label for="eMail">Team Description</label>
            <input type="text" class="form-control" name="updateTeamDescription" id="teamDescription" value="' . $description . '">
          </div>
        </div>
      </div>';
      if($userType === 2 || $memberType === 2) {
        echo '
      <div class="row gutters">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
          <div class="text-right">
            <input type="hidden" name="updateTeam" value="'.$team.'">
            <input type="submit" id="updateBtn" name="update" class="btn btn-primary" value="Update" />
    </form>
            <form action="" method="POST">
              <input type="hidden" name="teamProfileSelected" value="'.$team.'">
              <input type="submit" id="cancelBtn" name="cancel" class="btn btn-secondary" value="Cancel"/>
            </form>
          </div>
        </div>
      </div>';
      }
      echo '
	</div>
</div>
</div>
</div>
</div>
';?>
<script src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="http://netdna.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script type="text/javascript">
	
</script>


</body>
</html>