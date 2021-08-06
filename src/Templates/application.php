<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../../../favicon.ico">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    
    <!-- Custom styles for this template -->
    <link href="udassets/css/dashboard.css" rel="stylesheet">
    <link href="udassets/css/udstyle.css" rel="stylesheet">
    
    <?php echo isset($this->addToHead) ? $this->addToHead : '' ?>

    <title><?php echo isset($this->title) ? $this->title : '' ?></title>
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
            <main role="main" class="col-md-12 ml-sm-auto col-lg-12 px-4">
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

    <!-- Separate Popper and Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js" integrity="sha384-eMNCOe7tC1doHpGoWe/6oMVemdAVTMs2xqW4mwXrXsW0L84Iytr2wi5v2QjrP/xp" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.min.js" integrity="sha384-cn7l7gDp0eyniUwwAZgrzD06kc/tftFf19TOAs2zVinnD/C7E91j9yyk5//jjpt/" crossorigin="anonymous"></script>
    
    <?php echo isset($this->addToFoot) ? $this->addToFoot : '' ?>
    
  </body>
</html>












<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags always come first -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    

    <!-- Page title -->
    <title></title>

    <!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    

    

    
</head>
<body>


<!-- jQuery and JS bundle w/ Popper.js -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>


</body>
</html>
