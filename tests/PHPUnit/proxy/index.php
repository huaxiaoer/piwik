<?php
/**
 * Proxy to index.php, but will use the Test DB
 * Used by tests/PHPUnit/Integration/ImportLogsTest.php and tests/PHPUnit/Integration/UITest.php
 */

$GLOBALS['PIWIK_CONFIG_TEST_ENVIRONMENT'] = true;
$GLOBALS['PIWIK_ACCESS_IS_SUPERUSER'] = true;
$GLOBALS['PIWIK_ACCESS_SUPERUSER_LOGIN'] = 'superUserLogin';

// Wrapping the request inside ob_start() calls to ensure that the Test
// calling us waits for the full request to process before unblocking
ob_start();

define('PIWIK_INCLUDE_PATH', '../../..');
define('PIWIK_USER_PATH', PIWIK_INCLUDE_PATH);

require_once PIWIK_INCLUDE_PATH . '/libs/upgradephp/upgrade.php';
require_once PIWIK_INCLUDE_PATH . '/core/Loader.php';
require_once PIWIK_INCLUDE_PATH . '/core/EventDispatcher.php';

Piwik_Visualization_Cloud::$debugDisableShuffle = true;

Piwik_Tracker::setTestEnvironment();
Piwik_Tracker_Cache::deleteTrackerCache();

// Disable index.php dispatch since we do it manually below
define('PIWIK_ENABLE_DISPATCH', false);
include PIWIK_INCLUDE_PATH . '/index.php';

$controller = new Piwik_FrontController;
$controller->init();
$controller->dispatch();

ob_flush();
