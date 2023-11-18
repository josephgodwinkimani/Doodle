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

namespace Doodle\Tests;

use PHPUnit\Framework\TestCase;
use Doodle\Framework;
use Doodle\Validator;
use Doodle\ResponseEvent;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\Routing;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Calendar\Controller\LeapYearController;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\RateLimiter\Storage\InMemoryStorage;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use InvalidArgumentException;

class FrameworkTest extends TestCase
{
    private EventDispatcher $dispatcher;

    private function testInit()
    {
        $matcher = $this->createMock('Symfony\Component\Routing\Matcher\UrlMatcherInterface');
        $matcher->expects($this->once())
            ->method('getContext')
            ->willReturn($this->createMock('Symfony\Component\Routing\RequestContext'));
        $matcher->expects($this->once())
            ->method('match')
            ->willReturn(['_controller' => 'TestController']);

        $controllerResolver = $this->createMock('Symfony\Component\HttpKernel\Controller\ControllerResolverInterface');
        $controllerResolver->expects($this->once())
            ->method('getController')
            ->willReturn(function () {
                return new Response('Hello World');
            });

        $argumentResolver = $this->createMock('Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface');
        $argumentResolver->expects($this->once())
            ->method('getArguments')
            ->willReturn([]);

        $dispatcher = $this->createMock(EventDispatcher::class);
        $dispatcher->expects($this->once())
            ->method('dispatch');

        $request = Request::create('/test');
        $request->attributes = new \Symfony\Component\HttpFoundation\ParameterBag();

        $router = new Framework($dispatcher, $matcher, $controllerResolver, $argumentResolver, );
        $response = $router->run($request);

        return $response;
    }

    public function testHandleRateLimitEnv()
    {
        $request = new Request();
        $dotenv = new Dotenv();
        $dotenv->loadEnv(__DIR__ . "/../../../.env.test");

        $dispatcher = $this->createMock(EventDispatcher::class);

        $dispatcher->expects($this->once())
            ->method('dispatch');

        $rateLimitPolicy = $_ENV["RATE_LIMIT_POLICY"];

        if ("no_limit" === $rateLimitPolicy) {
            $this->assertEquals("no_limit", $rateLimitPolicy);
        }

        $rateLimitId = $_ENV["RATE_LIMIT_ID"];

        $validator = new Validator();

        $rateLimitIdViolations = $validator->isDataType($rateLimitId, "string");

        $this->assertEquals(0, count($rateLimitIdViolations));

        $response = new Response("You enabled rate limiting with invalid values", 403);
        $dispatcher->dispatch(new ResponseEvent($response, $request), "response");

        $this->assertEquals(403, $response->getStatusCode());

    }

    public function testHandleRateLimitPolicy()
    {
        $request = new Request();
        $dotenv = new Dotenv();
        $dotenv->loadEnv(__DIR__ . "/../../../.env.dist");

        $rateLimitPolicy = "no_limit";

        $this->assertEquals("no_limit", $rateLimitPolicy);

    }

    public function testHandleRateLimit()
    {
        $request = new Request();
        $dotenv = new Dotenv();
        $dotenv->loadEnv(__DIR__ . "/../../../.env.dist");

        $dispatcher = $this->createMock(EventDispatcher::class);

        $dispatcher->expects($this->once())
            ->method('dispatch');

        $rateLimitPolicy = $_ENV["RATE_LIMIT_POLICY"];

        if ("no_limit" === $rateLimitPolicy) {
            $this->assertEquals("no_limit", $rateLimitPolicy);
        }

        $rateLimitId = $_ENV["RATE_LIMIT_ID"];
        $rateLimitInterval = $_ENV["RATE_LIMIT_INTERVAL"];
        $rateLimitString = $_ENV["RATE_LIMIT"];

        $rateLimit = (int) $rateLimitString;

        $factory = new RateLimiterFactory(
            [
                "id" => $rateLimitId,
                "policy" => $rateLimitPolicy,
                "limit" => $rateLimit,
                "interval" => $rateLimitInterval,
            ],
            new InMemoryStorage()
        );

        $limiter = $factory->create($request->getClientIp());

        $limiter->consume(1)->isAccepted() === false;

        $response = new Response("Too Many Requests", 500);

        $dispatcher->dispatch(new ResponseEvent($response, $request), "response");

        $this->assertEquals(500, $response->getStatusCode());

    }

    public function testHandleRateLimitSlidingWindow()
    {
        $request = new Request();
        $dotenv = new Dotenv();
        $dotenv->loadEnv(__DIR__ . "/../../../.env.dist");

        $rateLimitPolicy = $_ENV["RATE_LIMIT_POLICY"];

        if ("no_limit" === $rateLimitPolicy) {
            $this->assertEquals("no_limit", $rateLimitPolicy);
        }

        $rateLimitId = $_ENV["RATE_LIMIT_ID"];
        $rateLimitInterval = $_ENV["RATE_LIMIT_INTERVAL"];
        $rateLimitString = $_ENV["RATE_LIMIT"];

        $rateLimit = (int) $rateLimitString;

        $factory = new RateLimiterFactory(
            [
                "id" => $rateLimitId,
                "policy" => "sliding_window",
                "limit" => $rateLimit,
                "interval" => $rateLimitInterval,
            ],
            new InMemoryStorage()
        );

        $limiter = $factory->create($request->getClientIp());

        $limiter->consume(1)->isAccepted() === false;

        $response = new Response("Too Many Requests", 500);

        $dispatcher = $this->createMock(EventDispatcher::class);

        $dispatcher->expects($this->once())
            ->method('dispatch');

        $dispatcher->dispatch(new ResponseEvent($response, $request), "response");

        $this->assertEquals(500, $response->getStatusCode());
    }

    public function testHandleRateLimitFixedWindow()
    {
        $request = new Request();
        $dotenv = new Dotenv();
        $dotenv->loadEnv(__DIR__ . "/../../../.env.dist");

        $dispatcher = $this->createMock(EventDispatcher::class);

        $dispatcher->expects($this->once())
            ->method('dispatch');

        $rateLimitPolicy = $_ENV["RATE_LIMIT_POLICY"];

        if ("no_limit" === $rateLimitPolicy) {
            $this->assertEquals("no_limit", $rateLimitPolicy);
        }

        $rateLimitId = $_ENV["RATE_LIMIT_ID"];
        $rateLimitInterval = $_ENV["RATE_LIMIT_INTERVAL"];
        $rateLimitString = $_ENV["RATE_LIMIT"];

        $rateLimit = (int) $rateLimitString;

        $factory = new RateLimiterFactory(
            [
                "id" => $rateLimitId,
                "policy" => "fixed_window",
                "limit" => $rateLimit,
                "interval" => $rateLimitInterval,
            ],
            new InMemoryStorage()
        );

        $limiter = $factory->create($request->getClientIp());

        $limiter->consume(1)->isAccepted() === false;

        $response = new Response("Too Many Requests", 500);

        $dispatcher->dispatch(new ResponseEvent($response, $request), "response");

        $this->assertEquals(500, $response->getStatusCode());
    }

    public function testHandleRateLimitTokenBucket()
    {
        $request = new Request();
        $dotenv = new Dotenv();
        $dotenv->loadEnv(__DIR__ . "/../../../.env.dist");

        $dispatcher = $this->createMock(EventDispatcher::class);

        $dispatcher->expects($this->once())
            ->method('dispatch');

        $rateLimitPolicy = "token_bucket";

        if ("no_limit" === $rateLimitPolicy) {
            $this->assertEquals("no_limit", $rateLimitPolicy);
        }

        $rateLimitId = $_ENV["RATE_LIMIT_ID"];
        $rateLimitInterval = $_ENV["RATE_LIMIT_INTERVAL"];
        $rateLimitString = $_ENV["RATE_LIMIT"];

        $rateLimit = (int) $rateLimitString;

        $factory = new RateLimiterFactory(
            [
                "id" => $rateLimitId,
                "policy" => $rateLimitPolicy,
                "limit" => $rateLimit,
                "rate" => ["interval" => $rateLimitInterval]
            ],
            new InMemoryStorage()
        );

        $limiter = $factory->create($request->getClientIp());

        $limiter->consume(1)->isAccepted() === false;

        $response = new Response("Too Many Requests", 500);

        $dispatcher->dispatch(new ResponseEvent($response, $request), "response");

        $this->assertEquals(500, $response->getStatusCode());
    }

    public function testControllerResponse()
    {
        // Create a mock
        $dispatcher = $this->createMock(
            EventDispatcher::class
        );

        // Create a mock
        $matcher = $this->createMock(
            UrlMatcherInterface::class
        );

        $matcher->expects($this->once())
            ->method("match")
            ->will($this->returnValue([
                "_route" => "/is_leap_year/{year}",
                "title" => "Home",
                "year" => "2020",
                "_controller" => [new LeapYearController(), "index"]
            ]));

        $matcher->expects($this->once())
            ->method("getContext")
            ->will($this->returnValue($this->createMock(
                Routing\RequestContext::class
            )));

        $controllerResolver = new ControllerResolver();
        $argumentResolver = new ArgumentResolver();

        $framework = new Framework(
            $dispatcher,
            $matcher,
            $controllerResolver,
            $argumentResolver
        );

        $response = $framework->handle(new Request());

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testNotFoundHandling(): void
    {
        $framework = $this->getFrameworkForException(new ResourceNotFoundException());

        // Handle a HTTP request
        $response = $framework->handle(new Request());

        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testMethodNotAllowedExceptionHandling(): void
    {
        $framework = $this->getFrameworkForException(new MethodNotAllowedException(["GET"]));

        // Handle a HTTP request
        $response = $framework->handle(new Request());

        $this->assertEquals(405, $response->getStatusCode());
    }

    public function testExceptionHandling(): void
    {
        $framework = $this->getFrameworkForException(new InvalidArgumentException());

        // Handle a HTTP request
        $response = $framework->handle(new Request());

        $this->assertEquals(500, $response->getStatusCode());
    }

    private function getFrameworkForException($exception)
    {
        // Returns a mock object for the specified class
        $matcher = $this->createMock(Routing\Matcher\UrlMatcherInterface::class);
        // use getMock() on PHPUnit 5.3 or below
        // $matcher = $this->getMock(Routing\Matcher\UrlMatcherInterface::class);

        $matcher
            ->expects($this->once())
            ->method("match")
            ->will($this->throwException($exception));
        $matcher
            ->expects($this->once())
            ->method("getContext")
            ->will($this->returnValue($this->createMock(Routing\RequestContext::class)));
        $controllerResolver = $this->createMock(ControllerResolverInterface::class);
        $argumentResolver = $this->createMock(ArgumentResolverInterface::class);
        $this->dispatcher = new EventDispatcher();
        $dispatcher = $this->dispatcher;

        return new Framework($dispatcher, $matcher, $controllerResolver, $argumentResolver);
    }
}
