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
    <link rel="stylesheet" href="<?php echo BASEPATH; ?>assets/css/bootstrap.min.css">

    <?php echo isset($this->addToHead) ? $this->addToHead : '' ?>
  </head>
  <body>
    <header class="navbar navbar-default navbar-fixed-top">
		<div class="container">
			<br/><br/><br/>
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
			
		</div>
    </header>

    <div id="container">
  		<div id="leftcolumn">
			<?PHP 
			if (isset($this->leftcontainer)) {
				if (is_array($this->leftcontainer)) {
					foreach ($this->leftcontainer as $bl) {
						echo $bl->show();
					}
				} else {
					echo $this->leftcontainer->show();
				}
			} ?>
  		</div>
  		<div id="centralcolumn">
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
  		</div>
  		<div id="rightcolumn">
			<?PHP 
			if (isset($this->rightcontainer)) {
				if (is_array($this->rightcontainer)) {
					foreach ($this->rightcontainer as $bl) {
						echo $bl->show();
					}
				} else {
					echo $this->rightcontainer->show();
				}
			} ?>
  		</div>
	  </div>
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

    <!-- jQuery first, then Tether, then Bootstrap JS. -->
    <script src="<?php echo BASEPATH; ?>assets/lib/jquery/jquery-3.3.1.min.js">"></script>
	<script src="<?php echo BASEPATH; ?>assets/lib/popper.min.js">"></script>
    <script src="<?php echo BASEPATH; ?>assets/js/bootstrap.min.js"></script>

    <?php echo isset($this->addToFoot) ? $this->addToFoot : '' ?>
  </body>
</html>
