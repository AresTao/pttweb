<?php
	//$isLoggedIn = SessionManager::getInstance()->isAdmin();
        $isLoggedIn = false;
        /*if (SessionManager::getInstance()->isAdmin())
        {
                $isLoggedIn =true;
                echo '<script type="text/javascript">location.replace("?page=operator&sid=1")</script>';
        }else if (SessionManager::getInstance()->isOperator())
        {
                $isLoggedIn =true;
                echo '<script type="text/javascript">location.replace("?page=operator&sid=1")</script>';
        }else if (SessionManager::getInstance()->isEnterprise())
        {
                $isLoggedIn =true;
                echo '<script type="text/javascript">location.replace("?page=user&sid=1")</script>';
        }*/
	if ($isLoggedIn) {
		echo 'You are already logged in!';
		echo 'Were you looking for <a href="./?page=logout">logout</a>?';
	} else {
		if (isset($_GET['action']) && $_GET['action'] == 'dologin') {
			// parse and handle login form data
			try {
			
				SessionManager::getInstance()->loginAsOperator($_POST['username'], $_POST['password']); 
				$isLoggedIn = true;
				//echo '<script type="text/javascript">location.replace("?page=enterprise&sid=1")</script>';
				echo '<script type="text/javascript">location.replace("?page=operator&sid=1")</script>';


				//$isLoggedIn = true;
				//echo '<script type="text/javascript">location.replace("?page=user&sid=1")</script>';
				//echo '<script type="text/javascript">location.replace("?page=operator&sid=1")</script>';
				
				//echo 'Login successfull.<br/>
				//	Go on to the <a href="?page=meta">Meta Page</a>.';
			} catch(Exception $exc) {
                                
				echo '<div class="infobox infobox_error">Login failed.</div>';
			}
		}
		if (!$isLoggedIn) {
			// display login form
			/*if (!DBManager::getInstance()->doesAdminExist()) {
				echo '<div class="infobox infobox_info">';
				echo 'No admin Account exists yet.<br/>';
				echo 'To create an account, <b>just log in with your desired login-credentials</b>. The account will automatically created for you!<br/><br/>';
				echo 'If you experience problems and the account is not created for you, please check that your webserver has write permissions to the data folder.';
				echo '</div>';
			}*/
?>

<div id="login">
  <div class="dologo"></div>
    <form action="?page=login&action=dologin" method="post" onsubmit="
		if (jQuery('#mpi_login_username').attr('value').length == 0) {alert('You did not enter a username!'); return false;}
		if (jQuery('#mpi_login_password').attr('value').length == 0) {alert('You did not enter a password!'); return false;}">
   <ul>  
    <li class="inpLi"><b>用户名：</b><input name="username" type="text" class="inpLogin" id="mpi_login_username" ></li>
    <li class="inpLi"><b>密码：</b><input name="password" type="password" class="inpLogin" id="mpi_login_password" ></li>
    <li class="inpLi"><b>账号类型：</b><input name="type" type="hidden" class="inpLogin" id="type" value="2" ></li>
        <li class="sub"><input type="submit" name="submit" class="btn" value="login"></li> 

   </ul>
  </form>
</div> 

<?php
		}
	}
?>
