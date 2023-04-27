<?php

declare(strict_types=1);

namespace Contao\Rector\Set;

use Rector\Set\Contract\SetListInterface;

final class ContaoLevelSetList implements SetListInterface
{
    /**
     * @var string
     */
    final public const UP_TO_CONTAO_49 = __DIR__ . '/../../config/sets/contao/level/up-to-contao-49.php';

    /**
     * @var string
     */
    final public const UP_TO_CONTAO_413 = __DIR__ . '/../../config/sets/contao/level/up-to-contao-413.php';

    /**
     * @var string
     */
    final public const UP_TO_CONTAO_50 = __DIR__ . '/../../config/sets/contao/level/up-to-contao-50.php';

    /**
     * @var string
     */
    final public const UP_TO_CONTAO_51 = __DIR__ . '/../../config/sets/contao/level/up-to-contao-51.php';
}
