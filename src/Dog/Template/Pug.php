<?php

declare(strict_types=1);

namespace Dog\Template;

class Pug
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
        $path = __DIR__ . "/../Template/";
        $pug = new \Pug\Pug([
            'pretty' => true,
            'cache' => __DIR__ . "/../Cache",
        ]);

        return $pug->render($path . $template, $variableArray);
    }
}
