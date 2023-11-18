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

namespace Doodle;

use Doodle\Validator;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\RateLimiter\Storage\InMemoryStorage;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;

class Framework
{
    /** @phpstan-ignore-next-line */
    private EventDispatcher $dispatcher;
    /** @phpstan-ignore-next-line */
    private UrlMatcherInterface $matcher;
    /** @phpstan-ignore-next-line */
    private ControllerResolverInterface $controllerResolver;
    /** @phpstan-ignore-next-line */
    private ArgumentResolverInterface $argumentResolver;

    /**
     * Instantiate the framework
     *
     * @param \Symfony\Component\EventDispatcher\EventDispatcher                   $dispatcher
     * @param \Symfony\Component\Routing\Matcher\UrlMatcherInterface               $matcher
     * @param \Symfony\Component\HttpKernel\Controller\ControllerResolverInterface $controllerResolver
     * @param \Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface   $argumentResolver
     */
    public function __construct(
        EventDispatcher $dispatcher,
        UrlMatcherInterface $matcher,
        ControllerResolverInterface $controllerResolver,
        ArgumentResolverInterface $argumentResolver
    ) {
        $this->dispatcher = $dispatcher;
        $this->matcher = $matcher;
        $this->controllerResolver = $controllerResolver;
        $this->argumentResolver = $argumentResolver;
    }

    /**
     * Handle a HTTP request
     *
     * @param Request $request
     *
     *
     * @return mixed
     *
     * @phpcsSuppress SlevomatCodingStandard.Files.TypeNameMatchesFileName.NoMatchBetweenTypeNameAndFileName
     */
    public function handle(Request $request): mixed
    {
        $dotenv = new Dotenv();
        $dotenv->loadEnv(__DIR__ . "/../../.env.dist");

        $rateLimitPolicy = $_ENV["RATE_LIMIT_POLICY"];

        if ("no_limit" === $rateLimitPolicy) {
            return $this->run($request);
        }

        $rateLimitId = $_ENV["RATE_LIMIT_ID"];
        $rateLimitInterval = $_ENV["RATE_LIMIT_INTERVAL"];
        $rateLimitString = $_ENV["RATE_LIMIT"];

        $validator = new Validator();

        $rateLimitIdViolations = $validator->isDataType($rateLimitId, "string");

        $rateLimitPolicyViolations = $validator->isDataType($rateLimitPolicy);

        $rateLimitStringViolations = $validator->isDataType($rateLimitString);

        if (($rateLimitIdViolations && 0 !== count($rateLimitIdViolations)) || /** @phpstan-ignore-line */
            ($rateLimitPolicyViolations && 0 !== count($rateLimitPolicyViolations)) /** @phpstan-ignore-line */
        ) {
            $response = new Response("You enabled rate limiting with invalid values", 403);
            $this->dispatcher->dispatch(new ResponseEvent($response, $request), "response");

            return $response;
        }

        $rateLimit = (int) $rateLimitString;

        if ("token_bucket" === $rateLimitPolicy) {
            $factory = new RateLimiterFactory(
                [
                    "id" => $rateLimitId,
                    "policy" => $rateLimitPolicy,
                    "limit" => $rateLimit,
                    "rate" => ["interval" => $rateLimitInterval],
                ],
                new InMemoryStorage()
            );
        }

        if ("sliding_window" === $rateLimitPolicy || "fixed_window" === $rateLimitPolicy) {
            $factory = new RateLimiterFactory(
                [
                    "id" => $rateLimitId,
                    "policy" => $rateLimitPolicy,
                    "limit" => $rateLimit,
                    "interval" => $rateLimitInterval,
                ],
                new InMemoryStorage()
            );
        }

        $limiter = $factory->create($request->getClientIp()); /** @phpstan-ignore-line */

        if (false === $limiter->consume(1)->isAccepted()) {
            $response = new Response("Too Many Requests", 500);
            $this->dispatcher->dispatch(new ResponseEvent($response, $request), "response");

            return $response;
        }

        return $this->run($request);
    }

    /**
     * Handle a HTTP response
     *
     * @param Request $request
     *
     *
     * @return mixed
     *
     * @phpcsSuppress SlevomatCodingStandard.Files.TypeNameMatchesFileName.NoMatchBetweenTypeNameAndFileName
     */
    public function run(Request $request): mixed
    {
        $this->matcher->getContext()->fromRequest($request);

        try {
            $request->attributes->add($this->matcher->match($request->getPathInfo()));

            $controller = $this->controllerResolver->getController($request);
            $arguments = $this->argumentResolver->getArguments($request, $controller);

            $response = call_user_func_array($controller, $arguments);
        } catch (ResourceNotFoundException $exception) {
            $response = new Response($exception->getMessage(), 404);
        } catch (MethodNotAllowedException $exception) {
            $response = new Response($exception->getMessage(), 405);
        } catch (\Exception $exception) {
            $response = new Response($exception->getMessage(), 500);
        }

        $this->dispatcher->dispatch(new ResponseEvent($response, $request), "response");

        return $response;
    }
}
