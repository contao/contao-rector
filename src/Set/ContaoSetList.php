<?php

declare(strict_types=1);

namespace Contao\Rector\Set;

use Rector\Set\Contract\SetListInterface;

final class ContaoSetList implements SetListInterface
{
    /**
     * @var string
     */
    final public const CONTAO_49 = __DIR__ . '/../../config/sets/contao/contao-49.php';

    /**
     * @var string
     */
    final public const CONTAO_413 = __DIR__ . '/../../config/sets/contao/contao-413.php';

    /**
     * @var string
     */
    final public const CONTAO_50 = __DIR__ . '/../../config/sets/contao/contao-50.php';

    /**
     * @var string
     */
    final public const ANNOTATIONS_TO_ATTRIBUTES = __DIR__ . '/../../config/sets/contao/annotations-to-attributes.php';

    /**
     * @var string
     */
    final public const FQCN = __DIR__ . '/../../config/sets/contao/contao-namespace.php';
}
