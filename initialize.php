<?php
// Define core paths
// Define as absolute paths to make sure that require_once works as expected

// DIRECTORY_SEPARATOR is a PHP pre-defined constant
// (\ for Windows, / for Unix)
defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);

defined('SITE_ROOT') ? null : define('SITE_ROOT', $_SERVER['DOCUMENT_ROOT'].DS.'school');

defined('LIB_PATH') ? null : define('LIB_PATH', SITE_ROOT.DS.'includes');

// load config file first
require_once(LIB_PATH.DS.'config.php');

// load basic functions so that all have access and can use it
require_once(LIB_PATH.DS.'functions.php');

//load core objects
//session
require_once(LIB_PATH.DS.'session.php');
//database
require_once(LIB_PATH.DS.'database.php');
//database objects
require_once(LIB_PATH.DS.'database_object.php');
//pagination
//phpmailer

//load database related classes
//student
require_once(LIB_PATH.DS.'student.php');
//user
require_once(LIB_PATH.DS.'user.php');
//enrollment
require_once(LIB_PATH.DS.'enrollment.php');
//account
?>