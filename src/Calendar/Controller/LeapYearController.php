<?php

declare(strict_types=1);

namespace Calendar\Controller;

use Calendar\Model\LeapYear;
use Calendar\Template\Twig;
use Doodle\DateTime;
use Doodle\Environment;
use Doodle\Versioning;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LeapYearController
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
    public function index(Request $request, string $title, string $year): Response
    {
        try {
            $appEnv = new Environment();
            $appName = $appEnv->get("APP_NAME");

            $versioning = new Versioning();
            $version = $versioning->getVersion();

            $leapYear = new LeapYear();
            $twig = new Twig();
            $date1 = new DateTime();
            $date = $date1->getDatetime("F d, Y H:i");

            $html = $twig->create("index.html.twig", [
                "year" => $year,
                "page_title" => $title,
                "app_name" => $appName,
                "version" => $version,
                "date" => $date,
            ]);

            $response = $leapYear->isLeapYear($year) ? new Response(htmlspecialchars($html)) : new Response("Nope, this is not a leap year.");

            // Sets the response's time-to-live for shared caches in seconds
            $response->setTtl(10);

            return $response;
        } catch (\Exception $e) {
            $response = new Response($e->getMessage());

            return $response;
        }
    }

    /**
     * @phpcsSuppress SlevomatCodingStandard.Files.TypeNameMatchesFileName.NoMatchBetweenTypeNameAndFileName
     *
     * @param string $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function subrequest(Request $request): Response
    {
        try {
            $client = HttpClient::create();
            $req = $client->request("GET", "https://api.github.com/repos/symfony/symfony-docs");

            $response = new Response(json_encode(json_decode($req->getContent())));

            $response->headers->set("Content-Type", "application/json");

            // Expiration Caching
            $response->setPrivate();
            $response->setMaxAge(0);
            $response->setSharedMaxAge(0);
            $response->headers->addCacheControlDirective('must-revalidate', true);

            return $response;
        } catch (\Exception $e) {
            $response = new Response($e->getMessage());

            return $response;
        }
    }
}
