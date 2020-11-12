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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="<?php echo BASEPATH ?>udassets/css/style.css">

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

    <div id="container">

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

	  </div> <!-- #container -->

	  <footer>
			<?PHP 
			if (isset($this->bottomcontainer)) {
				if (is_array($this->bottomcontainer)) {
					foreach ($this->bottomcontainer as $bl) {
						echo $bl->show();
					}
				} else {
					echo $this->bottomcontainer->show();
				}
			} ?>
	  </footer>

	<!-- jQuery and JS bundle w/ Popper.js -->
	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>

    <?php echo isset($this->addToFoot) ? $this->addToFoot : '' ?>
  </body>
</html>
