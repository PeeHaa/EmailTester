<?php
/**
 * This bootstraps the application
 *
 * PHP version 5.4
 *
 * @category   EmailTester
 * @author     Pieter Hordijk <info@EmailTester.com>
 * @copyright  Copyright (c) 2013 Pieter Hordijk
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 * @version    1.0.0
 */
namespace EmailTester;

use EmailTester\Core\Autoloader,
    EmailTester\Http\Request,
    EmailTester\Storage\Pattern as PatternStorage;

/**
 * Redox prevention
 */
set_time_limit(30);

/**
 * Bootstrap the PitchBlade library
 */
require_once __DIR__ . '/src/EmailTester/bootstrap.php';

/**
 * Setup the environment specific settings
 */
require_once __DIR__ . '/init.deployment.php';

/**
 * Prevent rendering of templates when on CLI
 */
if(php_sapi_name() === 'cli') {
    return;
}

/**
 * Setup the request object
 */
$request = new Request($_SERVER, $_GET, $_POST, $_COOKIE);

/**
 * Get the template
 */
$patternOrUrl = $request->getPostVariable('patternOrUrl', null);
if ($request->getMethod() == 'POST' && $request->getPath() == '/test' && $patternOrUrl !== null && preg_match('#^http[s]?://stackoverflow.com/#', $patternOrUrl) === 1) {
    $url = $request->isSsl() ? 'https://' : 'http://';
    $url.= $request->getHost();
    $url.= '/test-url/' . rawurlencode($patternOrUrl);

    header('Location: ' . $url);
    exit;
} elseif ($request->getMethod() == 'POST' && $request->getPath() == '/test' && $patternOrUrl !== null && preg_match('#^http[s]?://stackoverflow.com/#', $patternOrUrl) !== 1) {
    $pattern = new PatternStorage($dbConnection);
    $id = $pattern->getId($patternOrUrl);

    $url = $request->isSsl() ? 'https://' : 'http://';
    $url.= $request->getHost();
    $url.= '/test-pattern/' . $id;

    header('Location: ' . $url);
    exit;
} elseif ($request->getMethod() == 'GET' && strpos($request->getPath(), '/test-pattern') === 0) {
    $template = __DIR__ . '/templates/pattern.phtml';
} else {
    $template = __DIR__ . '/templates/home.phtml';
}

/**
 * Render the page
 */
ob_start();
require $template;
$content = ob_get_contents();
ob_end_clean();

require __DIR__ . '/templates/page.phtml';
