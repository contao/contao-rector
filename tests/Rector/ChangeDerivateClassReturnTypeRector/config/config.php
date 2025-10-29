<?php

declare(strict_types=1);

use Contao\CoreBundle\Controller\ContentElement\AbstractContentElementController;
use Contao\CoreBundle\Controller\FrontendModule\AbstractFrontendModuleController;
use Contao\Rector\Rector\ChangeDerivateClassReturnTypeRector;
use Contao\Rector\ValueObject\ChangeDerivateClassReturnType;
use PhpParser\Node\Name\FullyQualified;
use Rector\Config\RectorConfig;
use Symfony\Component\HttpFoundation\Response;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->ruleWithConfiguration(ChangeDerivateClassReturnTypeRector::class, [
        new ChangeDerivateClassReturnType(
            AbstractContentElementController::class,
            'getResponse',
            'UnionType',
            new FullyQualified(Response::class),
        ),
        new ChangeDerivateClassReturnType(
            AbstractFrontendModuleController::class,
            'getResponse',
            'UnionType',
            new FullyQualified(Response::class),
        ),
    ]);
};
