<!doctype html>
<head>
    <meta charset="utf-8">

    <title>MultiBank Group</title>

   <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

	   <style>
        body {
            padding-top: 5rem;
        }
   
    </style>
</head>

<body>
<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <h1 class="text-white">MultiBank Group</h1>
            </li>
		</ul>
		
		<?php if(isset($_SESSION['userEmail'])) { ?>
		<ul class="nav navbar-nav navbar-right">
			<li class="nav-item">
            <a href="/msgraph/Auth/signout" class="nav-link"><b>Sign Out</b></a>
			</li>
		</ul>
		<?php } ?>				
        
    </div>
	
</nav>

<main role="main" class="container">

    <div class="starter-template">
		<?php
		 if(!empty($_SESSION['success'])) {
					echo "<div class='alert alert-success d-print-none' role='alert'>";
					echo $_SESSION['success'];
					unset($_SESSION['success']);
					echo "</div>";
				}elseif(!empty($_SESSION['errors'])) {
					echo "<div class='alert alert-danger d-print-none' role='alert'>";
					echo $_SESSION['errors'];
					unset($_SESSION['errors']);
					echo "</div>";
				}
				
          echo $content_for_layout;
        ?>

    </div>

</main>
<script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>
</html>
