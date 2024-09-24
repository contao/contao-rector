<?php

declare(strict_types=1);

namespace Rector\CustomRector;

use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Expr\Assign;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

final class LegacySessionToSymfonySessionRector extends AbstractRector
{
    private const SESSION_BAG_VAR = 'sessionBag';

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Rewrite Contao\Session calls to use the Symfony session bag',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
Session::getInstance()->get('foo');
Session::getInstance()->set('foo', 'bar');
Session::getInstance()->remove('foo');
Session::getInstance()->getData();
Session::getInstance()->setData($foo);
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
$sessionBag = \System::getContainer()->get('request_stack')->getSession()->getBag(
    \Contao\System::getContainer()->get('contao.routing.scope_matcher')->isBackendRequest(
        \Contao\System::getContainer()->get('request_stack')->getCurrentRequest()
    ) ? 'contao_backend' : (
        \Contao\System::getContainer()->get('contao.routing.scope_matcher')->isFrontendRequest(
            \Contao\System::getContainer()->get('request_stack')->getCurrentRequest()
        ) ? 'contao_frontend' : 'attributes'
    )
);
$sessionBag->get('foo');
$sessionBag->set('foo', 'bar');
$sessionBag->remove('foo');
$sessionBag->all();
$sessionBag->replace($foo);
CODE_SAMPLE
                )
            ]
        );
    }

    public function getNodeTypes(): array
    {
        return [StaticCall::class];
    }

    public function refactor(Node $node): ?Node
    {
        if (! $this->isName($node->class, 'Session') || ! $this->isName($node->name, 'getInstance')) {
            return null;
        }

        // Ensure this only works on Contao\Session
        $scope = $node->getAttribute(AttributeKey::SCOPE);
        if ($scope !== null && ! $scope->isInClass('Contao\Session')) {
            return null;
        }

        $methodCall = $this->getName($node->getAttribute(AttributeKey::PARENT_NODE));

        // Create the $sessionBag variable if not already assigned
        $this->ensureSessionBagVariableIsCreated($node);

        $methodMap = [
            'get' => 'get',
            'set' => 'set',
            'remove' => 'remove',
            'getData' => 'all',
            'setData' => 'replace',
        ];

        $methodName = $methodMap[$methodCall] ?? null;

        if ($methodName !== null) {
            // Replace with $sessionBag->methodName(...)
            return new MethodCall(new Variable(self::SESSION_BAG_VAR), $methodName, $node->args);
        }

        return null;
    }

    private function ensureSessionBagVariableIsCreated(Node $node): void
    {
        // Check if $sessionBag is already assigned
        $statements = $node->getAttribute(AttributeKey::STATEMENTS);

        foreach ($statements as $statement) {
            if ($statement instanceof Expression && $statement->expr instanceof Assign) {
                $assign = $statement->expr;
                if ($assign->var instanceof Variable && $this->isName($assign->var, self::SESSION_BAG_VAR)) {
                    return;
                }
            }
        }

        // If not assigned, create the $sessionBag variable assignment
        $sessionBagAssign = new Assign(
            new Variable(self::SESSION_BAG_VAR),
            $this->createSessionBagCall()
        );

        $this->addNodeBeforeNode(new Expression($sessionBagAssign), $node);
    }

    private function createSessionBagCall(): MethodCall
    {
        $containerCall = new StaticCall(new Name('System'), 'getContainer');
        $requestStackCall = new MethodCall($containerCall, 'get', [$this->nodeFactory->createArg('request_stack')]);
        $currentRequestCall = new MethodCall($requestStackCall, 'getCurrentRequest');
        $matcherCall = new MethodCall($containerCall, 'get', [$this->nodeFactory->createArg('contao.routing.scope_matcher')]);

        $isBackendRequestCall = new MethodCall($matcherCall, 'isBackendRequest', [$currentRequestCall]);
        $isFrontendRequestCall = new MethodCall($matcherCall, 'isFrontendRequest', [$currentRequestCall]);

        $sessionNameConditional = $this->nodeFactory->createTernary(
            $isBackendRequestCall,
            $this->nodeFactory->createArg('contao_backend'),
            $this->nodeFactory->createTernary(
                $isFrontendRequestCall,
                $this->nodeFactory->createArg('contao_frontend'),
                $this->nodeFactory->createArg('attributes')
            )
        );

        return new MethodCall(
            new MethodCall($requestStackCall, 'getSession'),
            'getBag',
            [$sessionNameConditional]
        );
    }
}
