<?php

declare(strict_types=1);

use App\Rector\Rules\ArrayKeyNullishCoalescingRector;
use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/src',
    ]);
    
    $rectorConfig->parallel(120, 1, 1);
    
    $rectorConfig->rules([
        ArrayKeyNullishCoalescingRector::class,
    ]);
};
