<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags always come first -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<meta name="author" content="">
	<link rel="icon" href="../../../../favicon.ico">

    <!-- Page title -->
    <title><?php echo isset($this->title) ? $this->title : '' ?></title>

    <!-- Bootstrap CSS -->
    <link href="udassets/css/bootstrap.min.css" rel="stylesheet">
	
	<!-- Custom styles for this template -->
	<link href="udassets/css/dashboard.css" rel="stylesheet">
	<link href="udassets/css/udstyle.css" rel="stylesheet">

    <?php echo isset($this->addToHead) ? $this->addToHead : '' ?>
  </head>
  <body>
	  <nav class="navbar navbar-dark fixed-top bg-dark flex-md-nowrap p-0 shadow navbar-expand-md">
		<?php 
		if (isset($this->menucontainer)) {
			if (is_array($this->menucontainer)) {
				foreach ($this->menucontainer as $bl) {
					echo $bl->show();
				}
			} else {
				echo $this->menucontainer->show();
			}
		} ?>
	  </nav>
	  
	  <div class="container-fluid">
	        <div class="row">
	          <nav class="col-md-2 d-none d-md-block bg-light sidebar">
	  			<?php
	  			if (isset($this->leftcontainer)) {
	  				if (is_array($this->leftcontainer)) {
	  					foreach ($this->leftcontainer as $bl) {
	  						echo $bl->show();
	  					}
	  				} else {
	  					echo $this->leftcontainer->show();
	  				}
	  			} ?>
	          </nav>

	          <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
	  			<?php 
	  			if (isset($this->messagescontainer)) {
	  				if (is_array($this->messagescontainer)) {
	  					foreach ($this->messagescontainer as $bl) {
	  						echo $bl->show();
	  					}
	  				} else {
	  					echo $this->messagescontainer->show();
	  				}
	  			} ?>
			
	  			<?PHP 
	  			if (isset($this->centralcontainer)) {
	  				if (is_array($this->centralcontainer)) {
	  					foreach ($this->centralcontainer as $bl) {
	  						echo $bl->show();
	  					}
	  				} else {
	  					echo $this->centralcontainer->show();
	  				}
	  			} ?>

	  			<?PHP 
	  			if (isset($this->secondcentralcontainer)) {
	  				if (is_array($this->secondcentralcontainer)) {
	  					foreach ($this->secondcentralcontainer as $bl) {
	  						echo $bl->show();
	  					}
	  				} else {
	  					echo $this->secondcentralcontainer->show();
	  				}
	  			} ?>

	  			<?PHP 
	  			if (isset($this->thirdcentralcontainer)) {
	  				if (is_array($this->thirdcentralcontainer)) {
	  					foreach ($this->thirdcentralcontainer as $bl) {
	  						echo $bl->show();
	  					}
	  				} else {
	  					echo $this->thirdcentralcontainer->show();
	  				}
	  			} ?>
	            
	          </main>
	        </div>
	      </div>

    <!-- jQuery first, then Tether, then Bootstrap JS. -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
	<script src="udassets/lib/popper.min.js">"></script>
    <script src="udassets/js/bootstrap.min.js"></script>
	
	<!-- Icons -->
    <script src="https://unpkg.com/feather-icons/dist/feather.min.js"></script>
    <script>
      feather.replace()
    </script>

    <?php echo isset($this->addToFoot) ? $this->addToFoot : '' ?>
  </body>
</html>
