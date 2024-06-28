<?php

namespace Jane\Component\JsonSchema\Tests;

use Composer\InstalledVersions;
use PhpCsFixer\Cache\NullCacheManager;
use PhpCsFixer\Differ\NullDiffer;
use PhpCsFixer\Error\ErrorsManager;
use PhpCsFixer\Finder;
use PhpCsFixer\FixerFactory;
use PhpCsFixer\Linter\Linter;
use PhpCsFixer\RuleSet\RuleSet;
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

        $fixers = (new FixerFactory())
            ->registerBuiltInFixers()
            ->useRuleSet(new RuleSet([
                '@Symfony' => true,
                'return_type_declaration' => ['space_before' => 'none'],
                'string_implicit_backslashes' => true,
            ]))
            ->getFixers();

        $runner = new Runner(
            Finder::create()->in($path),
            $fixers,
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
