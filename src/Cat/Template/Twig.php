<?php

namespace Cat\Template;

use Symfony\Bridge\Twig\Extension\FormExtension;
use Symfony\Bridge\Twig\Extension\TranslationExtension;
use Symfony\Bridge\Twig\Form\TwigRendererEngine;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormRenderer;
use Symfony\Component\Translation\Loader\YamlFileLoader;
use Symfony\Component\Translation\Translator;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\RuntimeLoader\FactoryRuntimeLoader;

class Twig
{

    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingNativeTypeHint
     * @phpcsSuppress SlevomatCodingStandard.Files.TypeNameMatchesFileName.NoMatchBetweenTypeNameAndFileName
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingTraversableTypeHintSpecification
     *
     * @param string                                $template
     * @param string                                $title
     * @param \Symfony\Component\Form\FormInterface $form
     *
     *
     * @return string
     */
    public function create(string $template, string $title, FormInterface $form): string
    {
        // Create the Translator
        $translator = new Translator("en");
        $translator->addLoader("yaml", new YamlFileLoader());
        $translator->addResource("yaml", __DIR__ . "/Translations/messages.en.yaml", "en");

        $appVariableReflection = new \ReflectionClass("\Symfony\Bridge\Twig\AppVariable");
        $vendorTwigBridgeDirectory = dirname($appVariableReflection->getFileName());

        // Create the Twig environment
        $loader = new FilesystemLoader([__DIR__ . "/../Template", $vendorTwigBridgeDirectory . "/Resources/views/Form", ]);

        $twig = new Environment($loader, [
            "cache" => __DIR__ . "/../Cache",
            "debug" => true,
            "charset" => "UTF-8",
            "auto_reload" => true,
            "strict_variables" => true,
            "optimizations" => true,
        ]);

        // Add the FormExtension to Twig
        $twig->addExtension(new FormExtension());

        // Add the TranslationExtension to Twig
        $twig->addExtension(new TranslationExtension($translator));

        // Create the FormRenderer
        $rendererEngine = new TwigRendererEngine(["form_div_layout.html.twig"], $twig);
        $twig->addRuntimeLoader(new FactoryRuntimeLoader([
            FormRenderer::class => function () use ($rendererEngine, $twig) {
                return new FormRenderer($rendererEngine, null);
            },
        ]));

        return $twig->render($template, [
            "form" => $form->createView(),
            "title" => $title,
        ]);
    }
}
