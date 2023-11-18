<?php

declare(strict_types=1);

namespace Market\Template;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Twig
{

    /**
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ReturnTypeHint.MissingNativeTypeHint
     * @phpcsSuppress SlevomatCodingStandard.Files.TypeNameMatchesFileName.NoMatchBetweenTypeNameAndFileName
     * @phpcsSuppress SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingTraversableTypeHintSpecification
     *
     * @param string $template
     *
     * @param array  $variableArray
     *
     *
     * @return string
     */
    public function create(string $template, array $variableArray): string
    {
        $loader = new FilesystemLoader(__DIR__ . "/../Template");
        $twig = new Environment($loader, [
            "cache" => __DIR__ . "/../Cache",
            "debug" => true,
            "charset" => "UTF-8",
            "auto_reload" => true,
            "strict_variables" => true,
            "optimizations" => true,
        ]);

        return $twig->render($template, $variableArray);
    }
}
