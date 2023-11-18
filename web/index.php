<?php

declare(strict_types=1);

/*
 * This file is part of the Doodle package.
 *
 * (c) Godwin Kimani <josephgodwinke@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__ . "/../vendor/autoload.php";

/*
 * Provides tools that allow your application components to communicate
 * with each other by dispatching events and listening to them
 *
 * The dispatcher, the central object of the event dispatcher system,
 * notifies listeners of an event dispatched to it.
 * Put another way: your code dispatches an event to the dispatcher,
 * the dispatcher notifies all registered listeners for the event, and
 * each listener does whatever it wants with the event.
 */
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/*
 * Provides a structured process for converting a Request into a Response
 */
use Symfony\Component\HttpKernel;

/*
 * Maps an HTTP request to a set of configuration variables
 */
use Symfony\Component\Routing;

/*
 * Parses the _controller in the request attributes into a callable array containing
 * a controller object and its method
 */
use Symfony\Component\HttpKernel\Controller\ControllerResolver;

/*
 * Assigns a suitable value to the controller action arguments
 */
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;

$request = Request::createFromGlobals();
$routes = include __DIR__ . "/../src/route.php";

$context = new Routing\RequestContext();

/*
 * The matcher will find the matched controller and return the result
 */
$matcher = new Routing\Matcher\UrlMatcher($routes, $context);

$dispatcher = new EventDispatcher();
$dispatcher->addListener("response", [new Doodle\ContentTypeListener(), "onResponse"], -255);
$dispatcher->addListener("response", [new Doodle\LogListener(), "onResponse"]);

$controllerResolver = new ControllerResolver();
$argumentResolver = new ArgumentResolver();

$framework = new Doodle\Framework($dispatcher, $matcher, $controllerResolver, $argumentResolver);
$response = $framework->handle($request);

$response->send();
