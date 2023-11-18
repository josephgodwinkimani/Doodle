<?php

declare(strict_types=1);

namespace Dog\Controller;

use Dog\Template\Pug;
use Doodle\Environment;
use Doodle\Versioning;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DogController
{
    /**
     * @phpcsSuppress SlevomatCodingStandard.Files.TypeNameMatchesFileName.NoMatchBetweenTypeNameAndFileName
     *
     * @param string $request
     *
     * @param string $title
     *
     * @param string $year
     *
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request, string $title): Response
    {
        try {
            $appEnv = new Environment();
            $appName = $appEnv->get("APP_NAME");

            $versioning = new Versioning();
            $version = $versioning->getVersion();

            $pug = new Pug();

            $response = new Response(
                $pug->create("index.pug", [
                    "items" => [
                        ["id" => 1, "name" => "Bichon Frise"],
                        ["id" => 2, "name" => "American Bulldog"],
                        ["id" => 3, "name" => "Boerboel"],
                    ],
                    "page_title" => $title,
                    "version" => $version,
                    "app_name" => $appName,
                ])
            );

            // Sets the response's time-to-live for shared caches in seconds
            $response->setTtl(10);

            return $response;
        } catch (\Exception $e) {
            $response = new Response($e->getMessage());

            return $response;
        }
    }
}
