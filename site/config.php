<?php namespace ProcessWire;

use Symfony\Component\Dotenv\Dotenv;

/**
 * ProcessWire Configuration File
 *
 * Site-specific configuration for ProcessWire
 *
 * Please see the file /wire/config.php which contains all configuration options you may
 * specify here. Simply copy any of the configuration options from that file and paste
 * them into this file in order to modify them.
 * 
 * SECURITY NOTICE
 * In non-dedicated environments, you should lock down the permissions of this file so
 * that it cannot be seen by other users on the system. For more information, please
 * see the config.php section at: https://processwire.com/docs/security/file-permissions/
 * 
 * This file is licensed under the MIT license
 * https://processwire.com/about/license/mit/
 *
 * ProcessWire 3.x, Copyright 2019 by Ryan Cramer
 * https://processwire.com
 *
 */

if(!defined("PROCESSWIRE")) die();

/*** SITE CONFIG *************************************************************************/

/** @var Config $config */

/**
 * Load dotenv
 */
$dotenv = new Dotenv();
$dotenv->load($config->paths->root . '.env');

/**
 * Allow core API variables to also be accessed as functions?
 *
 * Recommended. This enables API varibles like $pages to also be accessed as pages(),
 * as an example. And so on for most other core variables.
 *
 * Benefits are better type hinting, always in scope, and potentially shorter API calls.
 * See the file /wire/core/FunctionsAPI.php for details on these functions.
 *
 * @var bool
 *
 */
$config->useFunctionsAPI = true;


/*** INSTALLER CONFIG ********************************************************************/


/**
 * Installer: Database Configuration
 */
$config->dbHost = $_ENV['DB_HOST'];
$config->dbName = $_ENV['DB_NAME'];
$config->dbUser = $_ENV['DB_USER'];
$config->dbPass = $_ENV['DB_PASSWORD'];
$config->dbPort = $_ENV['DB_PORT'];
$config->dbEngine = $_ENV['DB_ENGINE'];

/**
 * PHPMailer
 */

$config->mailerFrom = $_ENV['MAILER_FROM'];
$config->mailerHost = $_ENV['MAILER_HOST'];
$config->mailerPort = $_ENV['MAILER_PORT'];
$config->mailerName = $_ENV['MAILER_NAME'];
$config->mailerUsername = $_ENV['MAILER_USERNAME'];
$config->mailerPassword = $_ENV['MAILER_PASSWORD'];
$config->mailerDebugMode = $_ENV['MAILER_DEBUG_MODE'];

/**
 * Environment
 */
$config->env = $_ENV['APP_ENV'];

function isDevEnvironment() {
    return $_ENV['APP_ENV'] === 'dev';
}
$config->isDevEnvironment = isDevEnvironment();

/**
 * Google recaptcha
 */
$config->recaptchaPublicKey = $_ENV['RECAPTCHA_PUBLIC_KEY'];
$config->recaptchaPrivateKey = $_ENV['RECAPTCHA_PRIVATE_KEY'];
$config->recaptchaUrlVerification = 'https://www.google.com/recaptcha/api/siteverify';

/**
 * Installer: User Authentication Salt 
 * 
 * This value was randomly generated for your system on 2021/03/14.
 * This should be kept as private as a password and never stored in the database.
 * Must be retained if you migrate your site from one server to another.
 * Do not change this value, or user passwords will no longer work.
 * 
 */
$config->userAuthSalt = $_ENV['USER_AUTH_SALT'];

/**
 * Installer: Table Salt (General Purpose) 
 * 
 * Use this rather than userAuthSalt when a hashing salt is needed for non user 
 * authentication purposes. Like with userAuthSalt, you should never change 
 * this value or it may break internal system comparisons that use it. 
 * 
 */
$config->tableSalt = $_ENV['TABLE_SALT'];

/**
 * Installer: File Permission Configuration
 * 
 */
$config->chmodDir = '0755'; // permission for directories created by ProcessWire
$config->chmodFile = '0644'; // permission for files created by ProcessWire 

/**
 * Installer: Time zone setting
 * 
 */
$config->timezone = 'Europe/Berlin';

/**
 * Installer: Admin theme
 * 
 */
$config->defaultAdminTheme = 'AdminThemeUikit';

/**
 * Installer: Unix timestamp of date/time installed
 * 
 * This is used to detect which when certain behaviors must be backwards compatible.
 * Please leave this value as-is.
 * 
 */
$config->installed = 1615745684;


/**
 * Installer: HTTP Hosts Whitelist
 * 
 */
$config->httpHosts = array('franz-atelier.local', 'www.franz-atelier.local');


/**
 * Installer: Debug mode?
 * 
 * When debug mode is true, errors and exceptions are visible. 
 * When false, they are not visible except to superuser and in logs. 
 * Should be true for development sites and false for live/production sites. 
 * 
 */
$config->debug = false;

$config->paths->vendor = $config->paths->root . 'vendor/';

$config->urls->set('css', 'site/templates/public/css/');
$config->urls->set('js', 'site/templates/public/js/');

/**
 * Twig config
 */
$config->twigTemplates = $config->paths->templates . 'views';
$config->twigDebug = $_ENV['APP_ENV'] === 'dev' ? true : false;
$config->twigTemplateNamespaces = [
    'content' => $config->twigTemplates . '/content',
    'blocks' => $config->twigTemplates . '/blocks',
    'partials' => $config->twigTemplates . '/partials',
    'email' => $config->twigTemplates . '/email'
];


setlocale(LC_ALL, 'de_DE.UTF-8');

