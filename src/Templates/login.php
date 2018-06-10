<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags always come first -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <!-- Page title -->
    <title><?php echo isset($this->title) ? $this->title : '' ?></title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">

    <?php echo isset($this->addToHead) ? $this->addToHead : '' ?>
  </head>
  <body>
			
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

    <?php 
	if (isset($this->centralcontainer)) {
		if (is_array($this->centralcontainer)) {
			foreach ($this->centralcontainer as $bl) {
				echo $bl->show();
			}
		} else {
			echo $this->centralcontainer->show();
		}
	} ?>

    <!-- jQuery first, then Tether, then Bootstrap JS. -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.0.0/jquery.min.js" integrity="sha384-THPy051/pYDQGanwU6poAc/hOdQxjnOEXzbT+OuUAFqNqFjL+4IGLBgCJC3ZOShY" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.2.0/js/tether.min.js" integrity="sha384-Plbmg8JY28KFelvJVai01l8WyZzrYWG825m+cZ0eDDS1f7d/js6ikvy1+X+guPIB" crossorigin="anonymous"></script>
    <script src="assets/js/bootstrap.min.js"></script>

    <?php echo isset($this->addToFoot) ? $this->addToFoot : '' ?>
  </body>
</html>
