<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\SetList;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/rector-standalone',
        __DIR__ . '/src',
    ])
    ->withSets([
        SetList::PHP_53,
        SetList::PHP_54,
        // SetList::PHP_55,
        // SetList::PHP_56,
        // SetList::PHP_70,
        // SetList::PHP_71,
        // SetList::PHP_72,
        // SetList::PHP_73,
        // SetList::PHP_74,
        // SetList::PHP_80,
        // SetList::PHP_81,
        // SetList::PHP_82,
        // SetList::PHP_83,
    ])
    ->withSkip([
        __DIR__ . '/src/adodb',
        __DIR__ . '/src/phpmailer',
    ])
    ->withTypeCoverageLevel(0);
