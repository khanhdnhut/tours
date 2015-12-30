<html lang="en-US" xmlns="http://www.w3.org/1999/xhtml">
	<!--<![endif]-->
	<head>
		<meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
		<title><?php echo WEBSITE_NAME ?> › <?php echo LOG_IN ?></title>
		<link media="all" type="text/css" href="<?php echo PUBLIC_CSS ?>includes/buttons.css?ver=4.4" id="buttons-css" rel="stylesheet">
		<link media="all" type="text/css" href="https://fonts.googleapis.com/css?family=Open+Sans%3A300italic%2C400italic%2C600italic%2C300%2C400%2C600&amp;subset=latin%2Clatin-ext&amp;ver=4.4" id="open-sans-css" rel="stylesheet">
		<link media="all" type="text/css" href="<?php echo PUBLIC_CSS ?>includes/dashicons.css?ver=4.4" id="dashicons-css" rel="stylesheet">
		<link media="all" type="text/css" href="<?php echo PUBLIC_CSS ?>admin/login.css?ver=4.4" id="login-css" rel="stylesheet">
		<meta content="noindex,follow" name="robots">
	</head>
	<body class="login login-action-login wp-core-ui  locale-en-us">
		<div id="login">
			<h1><a tabindex="-1" title="<?php echo WEBSITE_NAME; ?>" href="<?php echo URL; ?>"><?php echo WEBSITE_NAME; ?></a></h1>
			<form method="post" action="<?php echo URL; ?>user/login" id="loginform" name="loginform">
				<p>
					<label for="user_login"><?php echo USER_NAME; ?><br>
					<input type="text" size="20" value="" class="input" id="user_login" name="log"></label>
				</p>
				<p>
					<label for="user_pass"><?php echo PASSWORD; ?><br>
					<input type="password" size="20" value="" class="input" id="user_pass" name="pwd"></label>
				</p>
                            
                                <!-- echo out the system feedback (error and success messages) -->
                                <?php $this->renderFeedbackMessages(); ?>
				<p class="forgetmenot"><label for="rememberme"><input type="checkbox" value="forever" id="rememberme" name="rememberme"> <?php echo REMEMBER_ME; ?></label></p>
				<p class="submit">
					<input type="submit" value="<?php echo LOG_IN; ?>" class="button button-primary button-large" id="wp-submit" name="wp-submit">
					<input type="hidden" value="<?php echo URL; ?>admin/" name="redirect_to">
					<input type="hidden" value="1" name="testcookie">
				</p>
			</form>
			<p id="nav">
				<a title="<?php echo LOST_PASS_TITLE; ?>" href="<?php echo URL; ?>user/lostpassword"><?php echo LOST_PASS; ?></a>
			</p>
			<script type="text/javascript">
				function wp_attempt_focus(){
				setTimeout( function(){ try{
				d = document.getElementById('user_login');
				d.focus();
				d.select();
				} catch(e){}
				}, 200);
				}
				
				wp_attempt_focus();
				if(typeof wpOnload=='function')wpOnload();
			</script>
			<p id="backtoblog"><a title="<?php echo BACK_TO_TITLE; ?>" href="<?php echo URL; ?>">← <?php echo BACK_TO; ?> <?php echo WEBSITE_NAME ?></a></p>
		</div>
		<div class="clear"></div>
	</body>
</html>