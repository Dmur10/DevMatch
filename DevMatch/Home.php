<?php
  require("navBar.php");
?>
<html>
    <head>
      
    <link rel = "stylesheet"
			href = "https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css"
			integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl"
			crossorigin="anonymous">

      <link rel = "stylesheet" href = "CSS/home.css">
    
</head>

<body>

<div class="lateral">

  <a href="connections.php" target="page">Connections</a>
  <a href="teams.php"  target="page">Teams</a>
  <a href="vacancies.php"  target="page">Vacancies</a>
  <a href="suggestions.php" target="page">Suggested vacancies</a>
</div>

<iframe class="page" name="page" src="connections.php"></iframe>

</body>
</html>