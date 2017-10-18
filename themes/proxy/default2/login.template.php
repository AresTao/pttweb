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
				require_once(MUMPHPI_MAINDIR.'/classes/Captcha.php');
		                $cap = $_POST['cap'];
                                if ($cap == "")
                                       throw new Exception("Please input captcha");
                                if (!Captcha::cap_isCorrect($cap))	
                                       throw new Exception("Captcha error");
				SessionManager::getInstance()->loginAsOperator($_POST['username'], $_POST['password']); 
				$isLoggedIn = true;
                                if (SessionManager::getInstance()->getLevel() == 2)
                                
				        echo '<script type="text/javascript">location.replace("?page=enterprise2&sid=1")</script>';
				else if (SessionManager::getInstance()->getLevel() == 1)
                                        echo '<script type="text/javascript">location.replace("?page=operator&sid=1")</script>';


				//$isLoggedIn = true;
				//echo '<script type="text/javascript">location.replace("?page=user&sid=1")</script>';
				//echo '<script type="text/javascript">location.replace("?page=operator&sid=1")</script>';
				
				//echo 'Login successfull.<br/>
				//	Go on to the <a href="?page=meta">Meta Page</a>.';
			} catch(Exception $exc) {
                                echo '<script type="text/javascript">window.wxc.xcConfirm("'.$exc->getMessage().'", window.wxc.xcConfirm.typeEnum.error);</script>';
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
  <div class="dologo"><div style = "position:absolute;right:0px;bottom:0px;"><span style="font-size:18px;">Agency Manage</span></div></div>
    <form action="?page=login&action=dologin" method="post" onsubmit="
		if (jQuery('#mpi_login_username').attr('value').length == 0) {window.wxc.xcConfirm('Please input account', window.wxc.xcConfirm.typeEnum.error); return false;}
		if (jQuery('#mpi_login_password').attr('value').length == 0) {window.wxc.xcConfirm('Please input password', window.wxc.xcConfirm.typeEnum.error); return false;}">
   <ul>  
    <li class="inpLi"><b>Account</b><input name="username" type="text" class="inpLogin" id="mpi_login_username" ></li>
    <li class="inpLi"><b>Password</b><input name="password" type="password" class="inpLogin" id="mpi_login_password" ></li>
    <li class="inpLi"><b>Input The Result</b><input name="cap" type="text" class="inpLogin" id="cap" value="" ></li>
    <li class="inpLi"><?php 
                              require_once(MUMPHPI_MAINDIR.'/classes/Captcha.php');
                              Captcha::cap_show();
                      ?></li>
        <li class="sub"><input type="submit" name="submit" class="btn" value="Sign In"></li> 

   </ul>
  </form>
</div> 

<?php
		}
	}
?>
