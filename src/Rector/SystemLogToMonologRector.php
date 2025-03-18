<?php

declare(strict_types=1);

namespace Contao\Rector\Rector;

use Contao\CoreBundle\Monolog\ContaoContext;
use Contao\System;
use PhpParser\Node;
use Psr\Log\LogLevel;
use Symplify\RuleDocGenerator\Contract\DocumentedRuleInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class SystemLogToMonologRector extends AbstractLegacyFrameworkCallRector implements DocumentedRuleInterface
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Rewrites deprecated System::log() calls to Monolog', [
            new CodeSample(
                <<<'CODE_BEFORE'
\Contao\System::log('generic log message', __METHOD__, TL_ACCESS);
\Contao\System::log('error message', __METHOD__, TL_ERROR);
CODE_BEFORE
                ,
                <<<'CODE_AFTER'
\Contao\System::getContainer()->get('logger')->log(\Psr\Log\LogLevel::INFO, 'generic log message', ['contao' => new \Contao\CoreBundle\Monolog\ContaoContext(__METHOD__, \Contao\CoreBundle\Monolog\ContaoContext::ACCESS)]);
\Contao\System::getContainer()->get('logger')->log(\Psr\Log\LogLevel::ERROR, 'error message', ['contao' => new \Contao\CoreBundle\Monolog\ContaoContext(__METHOD__, \Contao\CoreBundle\Monolog\ContaoContext::ERROR)]);
CODE_AFTER
            ),
        ]);
    }

    public function refactor(Node $node): ?Node
    {
        assert($node instanceof Node\Expr\StaticCall || $node instanceof Node\Expr\MethodCall);

        if (!$this->isParentStaticOrMethodClassCall($node, System::class, 'log')) {
            return null;
        }

        $args = $node->getArgs();
        $message = $args[0];
        $method = $args[1];
        $level = $args[2]->value;

        if ($level instanceof Node\Expr\ConstFetch) {
            $name = $this->getName($level->name);

            if (\in_array($name, [
                'TL_ERROR',
                'TL_ACCESS',
                'TL_GENERAL',
                'TL_FILES',
                'TL_CRON',
                'TL_FORMS',
                'TL_EMAIL',
                'TL_CONFIGURATION',
                'TL_NEWSLETTER',
                'TL_REPOSITORY',
            ])) {
                $name = substr($name, 3);
                $level = new Node\Expr\ClassConstFetch(new Node\Name\FullyQualified(ContaoContext::class), $name);
            }
        }

        $logLevel = 'INFO';
        if ('Contao\CoreBundle\Monolog\ContaoContext::ERROR' === $this->getName($level) || 'ERROR' === $level->value) {
            $logLevel = 'ERROR';
        }

        $context = new Node\Expr\New_(new Node\Name\FullyQualified(ContaoContext::class), $this->nodeFactory->createArgs([$method, $level]));
        $levelConst = new Node\Expr\ClassConstFetch(new Node\Name\FullyQualified(LogLevel::class), $logLevel);

        $container = new Node\Expr\StaticCall(new Node\Name\FullyQualified(System::class), 'getContainer');
        $service = new Node\Expr\MethodCall($container, 'get', [new Node\Arg(new Node\Scalar\String_('monolog.logger.contao'))]);
        $node = new Node\Expr\MethodCall($service, 'log', $this->nodeFactory->createArgs([$levelConst, $message, ['contao' => $context]]));

        return $node;
    }
}
