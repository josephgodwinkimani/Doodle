<?php

declare(strict_types=1);

namespace Market\Controller;

use Market\Model\Product;
use Market\Template\Twig;
use Doodle\Environment;
use Doodle\Versioning;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductsController
{

    /**
     * @phpcsSuppress SlevomatCodingStandard.Files.TypeNameMatchesFileName.NoMatchBetweenTypeNameAndFileName
     *
     * @param string $request
     *
     * @param string $title
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

            $products = new Product();

            $twig = new Twig();

            $response = $products ? new Response(
                $twig->create("index.html.twig", [
                    "products" => $products->getAll(),
                    "page_title" => $title,
                    "version" => $version,
                    "app_name" => $appName,
                ])
            ) : new Response("There are No Products in your database yet!");

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
     * @param string $title
     *
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create(Request $request, string $title): Response
    {

        try {
            // For Form Data
            //$name = $request->request->get('name');

            // For JSON Data
            $parameters = \json_decode($request->getContent(), true);
            $name = $parameters["name"]; // will print 'product name'
            $createdBy = $parameters["createdBy"];

            $appEnv = new Environment();
            $appName = $appEnv->get("APP_NAME");

            $versioning = new Versioning();
            $version = $versioning->getVersion();

            $products = new Product();

            $twig = new Twig();

            $html = $twig->create("create.html.twig", [
                "product" => $products->createOne($name, $createdBy),
                "page_title" => $title,
                "app_name" => $appName,
                "version" => $version,
            ]);

            $response = $products ? new Response(htmlspecialchars($html)) : new Response("Product creation failed !");

            // Sets the response's time-to-live for shared caches in seconds
            $response->setTtl(10);

            return $response;
        } catch (\Exception $e) {
            $response = new Response($e->getMessage());

            return $response;
        }
    }
}
