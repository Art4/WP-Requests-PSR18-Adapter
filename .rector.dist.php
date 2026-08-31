<?php

declare(strict_types=1);

return \Rector\Config\RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/examples',
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ])
    ->withPhpSets()
    ->withSets([
        \Rector\Set\ValueObject\SetList::DEAD_CODE,
        \Rector\Set\ValueObject\SetList::CODE_QUALITY,
        \Rector\Set\ValueObject\SetList::CODING_STYLE,
        \Rector\Set\ValueObject\SetList::TYPE_DECLARATION,
        \Rector\Set\ValueObject\SetList::TYPE_DECLARATION_DOCBLOCKS,
        \Rector\Set\ValueObject\SetList::PRIVATIZATION,
        \Rector\Set\ValueObject\SetList::NAMING,
        \Rector\Set\ValueObject\SetList::INSTANCEOF,
        \Rector\Set\ValueObject\SetList::EARLY_RETURN,
        \Rector\Set\ValueObject\SetList::STRICT_BOOLEANS,
    ])
    ->withSkip([
        \Rector\CodingStyle\Rector\Catch_\CatchExceptionNameMatchingTypeRector::class,
        \Rector\CodingStyle\Rector\PostInc\PostIncDecToPreIncDecRector::class,
        \Rector\CodingStyle\Rector\String_\SymplifyQuoteEscapeRector::class,
        \Rector\PHPUnit\CodeQuality\Rector\Class_\YieldDataProviderRector::class,
    ])
;
