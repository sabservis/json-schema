<?php

namespace Jane\Component\JsonSchema\Tests;

use Composer\InstalledVersions;
use PhpCsFixer\Cache\NullCacheManager;
use PhpCsFixer\Differ\NullDiffer;
use PhpCsFixer\Error\ErrorsManager;
use PhpCsFixer\Finder;
use PhpCsFixer\Fixer;
use PhpCsFixer\Linter\Linter;
use PhpCsFixer\Runner\Runner;

trait CodeStyleFixerTrait
{
    private function fixCodeStyle(string $path): array
    {
        $parser = InstalledVersions::getVersion('nikic/php-parser');
        if (version_compare($parser, '5.0', '>=')) {
            // don't run CS fixer latest PHP parser
            return [];
        }

        $runner = new Runner(
            Finder::create()->in($path),
            [
                new Fixer\ClassNotation\VisibilityRequiredFixer(),
                new Fixer\Import\NoUnusedImportsFixer(),
            ],
            new NullDiffer(),
            null,
            new ErrorsManager(),
            new Linter(),
            false,
            new NullCacheManager(),
        );

        return $runner->fix();
    }
}
