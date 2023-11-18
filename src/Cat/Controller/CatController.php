<?php

declare(strict_types=1);

namespace Cat\Controller;

use Cat\Template\Twig;
use Doodle\Container;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormFactoryBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CatController
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
            $container = new Container();
            // fetch service from the service container
            $serviceOne = $container->containerBuilder->get('helloworld.service');

            // Create the FormFactory
            $formFactoryBuilder = new FormFactoryBuilder();
            $formFactory = $formFactoryBuilder->getFormFactory();

            // Create the form
            $form = $formFactory->createBuilder()
                ->add("name", TextType::class)
                ->add("submit", SubmitType::class)
                ->getForm();

            // Handle the form submission
            $form->handleRequest();

            $twig = new Twig();

            $response = new Response(
                $twig->create("form.html.twig", $serviceOne() . " " . $title, $form)
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
