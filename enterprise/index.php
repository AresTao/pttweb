<?php
define('MUMPHPI_MAINDIR', '..');
define('MUMPHPI_SECTION', 'enterprise');

	// Start timer for execution time of script first
	require_once(MUMPHPI_MAINDIR.'/classes/PHPStats.php');
	PHPStats::scriptExecTimeStart();

	require_once(MUMPHPI_MAINDIR.'/classes/SettingsManager.php');
	require_once(MUMPHPI_MAINDIR.'/classes/DBManager.php');
	require_once(MUMPHPI_MAINDIR.'/classes/Logger.php');
	require_once(MUMPHPI_MAINDIR.'/classes/SessionManager.php');
	SessionManager::startSession();

	require_once(MUMPHPI_MAINDIR.'/classes/TranslationManager.php');
	require_once(MUMPHPI_MAINDIR.'/classes/ServerInterface.php');
	require_once(MUMPHPI_MAINDIR.'/classes/HelperFunctions.php');
	require_once(MUMPHPI_MAINDIR.'/classes/TemplateManager.php');
	require_once(MUMPHPI_MAINDIR.'/classes/MessageManager.php');
	require_once(MUMPHPI_MAINDIR.'/classes/PermissionManager.php');
	require_once(MUMPHPI_MAINDIR.'/classes/MysqlInterface.php');
	require_once(MUMPHPI_MAINDIR.'/classes/PHPExcel/IOFactory.php');
	require_once(MUMPHPI_MAINDIR.'/classes/PHPExcel.php');

	if (SettingsManager::getInstance()->isDebugMode()) {
		error_reporting(E_ALL);
	}

    //MysqlInterface::addOperator(0, 1,"test", "test","test","test","test");
    //MysqlInterface::updateOperator(3,1, "t", "tt","tttt","tttttt","tetttt");
    //MysqlInterface::getOperators();
    //MysqlInterface::getOperatorById(5);
    //MysqlInterface::deleteOperatorById(1);
    //MysqlInterface::addOperationLog(1, "insert into ...");
    //MysqlInterface::getOperationLogByOperatorId(1);


	// Check for running Ice with Murmur
	try {
		ServerInterface::getInstance();
	} catch(Ice_UnknownLocalException $ex) {
		MessageManager::addError(tr('error_noIce'));
		MessageManager::echoAll();
		exit();
	}

	if (!SessionManager::getInstance()->isEnterprise() && HelperFunctions::getActivePage()!='login') {
		header('Location: ?page=login');
		exit();
	}  elseif (SessionManager::getInstance()->isEnterprise() && isset($_GET['ajax'])) {
		require_once(MUMPHPI_MAINDIR.'/ajax/enterprise.ajax.php');

		// TODO: this should probably have a check, whether the function exists
		if (is_callable('Ajax_Enterprise::' . $_GET['ajax'])) {
			eval('Ajax_Enterprise::' . $_GET['ajax'] . '();');
		}
		MessageManager::echoAll();
		exit();
	}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />

	<title><?php echo SettingsManager::getInstance()->getSiteTitle();?></title>
	<meta name="description" content="<?php echo SettingsManager::getInstance()->getSiteDescription(); ?>" />
	<meta name="keywords" content="<?php echo SettingsManager::getInstance()->getSiteKeywords(); ?>" />
	<meta name="generator" content="MumPI by KCode" />
	<meta name="author" content="KCode.de" />

	<?php TemplateManager::parseTemplate('HTMLHead');; ?>
</head>
<body>
	<?php
		$pageSection = 'server';
		if (isset($_GET['page']) && !empty($_GET['page'])) {
			$pageSection = $_GET['page'];
		}

		// Parse Template
		TemplateManager::parseTemplate('header');

		TemplateManager::parseTemplate($pageSection);
	
		TemplateManager::parseTemplate('footer');

	?>
</body>
</html>
