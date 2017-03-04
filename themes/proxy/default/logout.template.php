<?php
        if ( SessionManager::getInstance()->isOperator())
                SessionManager::getInstance()->operatorLogOut();
        else if (SessionManager::getInstance()->isEnterprise())
                SessionManager::getInstance()->enterpriseLogOut();
        else
	        SessionManager::getInstance()->adminLogOut();
?>
<script type="text/javascript">location.replace("?page=login")</script>
<p>Logout successful</p>
