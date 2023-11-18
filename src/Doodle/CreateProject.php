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

use League\Flysystem\FilesystemException;
use League\Flysystem\UnableToCreateDirectory;

class CreateProject
{

    /**
     * @var string
     */
    protected string $directory;

    /**
     * Create a Doodle project
     *
     * @param mixed $directory Name of your project
     *
     *                         Example: `new Doodle\CreateProject("Cat");`
     *
     * @return void
     */
    public function __construct($directory)
    {
        try {
            $this->directory = $directory;
            $filesystemAdapter = new FileSystemAdapter();
            $filesystem = $filesystemAdapter->local(__DIR__ . "/../.");
            $filesystem->createDirectory($directory);
            $filesystem->createDirectory($directory . "/Cache");
            $filesystem->createDirectory($directory . "/Controller");
            $filesystem->createDirectory($directory . "/Model");
            $filesystem->createDirectory($directory . "/Service");
            $filesystem->createDirectory($directory . "/Template");
        } catch (FilesystemException | UnableToCreateDirectory $exception) {
            $appLogger = new Log();
            $appLogger->error($exception->getMessage());
        }
    }

    /**
     * Add twig support
     *
     * Example: `$I = new Doodle\CreateProject("Cat");  $I->twig();`
     *
     * @return void
     */
    public function twig()
    {
        try {
            $filesystemAdapter = new FileSystemAdapter();
            $filesystem = $filesystemAdapter->local(__DIR__ . "/../" . $this->directory . "/Template");
            $class = <<<'PHP'
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
            PHP;

            // Define the content of the PHP file with the specified namespace and class
            $content = "<?php\n\nnamespace " . $this->directory . "\\Template;\n\nuse Twig\Environment;\nuse Twig\Loader\FilesystemLoader;\n\nclass Twig { \n\n" . $class . "\n\n  }";

            // Write the file to the filesystem
            $filesystem->write('Twig.php', $content);
        } catch (FilesystemException | UnableToCreateDirectory $exception) {
            $appLogger = new Log();
            $appLogger->error($exception->getMessage());
        }
    }

    /**
     * Add pug support
     *
     * Example: `$I = new Doodle\CreateProject("Cat");  $I->pug();`
     *
     * @return void
     */
    public function pug()
    {
        try {
            $filesystemAdapter = new FileSystemAdapter();
            $filesystem = $filesystemAdapter->local(__DIR__ . "/../" . $this->directory . "/Template");
            $class = <<<'PHP'
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
            PHP;

            // Define the content of the PHP file with the specified namespace and class
            $content = "<?php\n\nnamespace " . $this->directory . "\\Template;\n\nclass Pug { \n\n" . $class . "\n\n  }";

            // Write the file to the filesystem
            $filesystem->write('Pug.php', $content);
        } catch (FilesystemException | UnableToCreateDirectory $exception) {
            $appLogger = new Log();
            $appLogger->error($exception->getMessage());
        }
    }
}
