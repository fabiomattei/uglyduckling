<?php

namespace Firststep\Templates\Blocks\Login;

use Firststep\Common\Blocks\BaseBlock;

class LoginForm extends BaseBlock {
	
	function __construct() {
	}
	
    function show(): string {
        return '<br /><br /><br /><br /><br />
        <div class="container">
    	<div class="row">
        <div class="col-sm-6 col-md-4 offset-md-4">
            <h1 class="text-center login-title">Sign in to continue to ' . APPNAMEFORPAGETITLE . '</h1>
            <div class="account-wall">
                <img class="profile-img" src="https://lh5.googleusercontent.com/-b0-k99FZlyE/AAAAAAAAAAI/AAAAAAAAAAA/eu7opA4byxI/photo.jpg?sz=120"
                    alt="">
                <form action="' . make_url( 'main', 'login' ) . '" class="form-signin" method="POST">
                <input type="text" name="email" class="form-control" placeholder="Email" required autofocus>
                <input type="password" name="password" class="form-control" placeholder="Password" required>
                <button class="btn btn-lg btn-primary btn-block" type="submit">
                    Sign in</button>
                <label class="checkbox pull-left">
                    <input type="checkbox" value="remember-me">
                    Remember me
                </label>
                <a href="' . BASEPATH . 'public/help.html" class="pull-right need-help">Need help? </a><span class="clearfix"></span>
                </form>
            </div>
            <a href="' . BASEPATH . 'public/signup.html" class="text-center new-account">Create an account </a>
        </div> <!-- col -->
    	</div> <!-- row -->
		</div> <!-- container -->'; 
    }
	
	function addToHead(): string {
        return '<link rel="stylesheet" href="' . BASEPATH . 'assets/css/login.css">';
    }

}
