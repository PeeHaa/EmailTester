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
    EmailTester\Http\Request;

/**
 * Bootstrap the PitchBlade library
 */
require_once __DIR__ . '/src/EmailTester/bootstrap.php';

/**
 * Setup the environment specific settings
 */
require_once __DIR__ . '/init.deployment.php';

/**
 * Setup the request object
 */
$request = new Request($_SERVER, $_GET, $_POST, $_COOKIE);

/**
 * Get the template
 */
if ($request->getMethod() == 'GET' && $request->getPath() == '/test') {
    $template = __DIR__ . '/templates/home.phtml';
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
