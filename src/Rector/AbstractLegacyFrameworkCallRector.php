<?php

declare(strict_types=1);

namespace Contao\Rector\Rector;

use PhpParser\Node;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Type\ObjectType;
use Rector\Rector\AbstractRector;
use Rector\Reflection\ReflectionResolver;

abstract class AbstractLegacyFrameworkCallRector extends AbstractRector
{
    public function __construct(private readonly ReflectionResolver $reflectionResolver)
    {
    }

    public function getNodeTypes(): array
    {
        return [
            Node\Expr\StaticCall::class,
            Node\Expr\MethodCall::class,
        ];
    }

    protected function isParentStaticOrMethodClassCall(Node $node, string $oldClassName, string $oldMethodName, string|null $newClassName = null, string|null $newMethodName = null): bool
    {
        if ($this->isMethodCall($node, $oldClassName, $oldMethodName)) {
            return true;
        }

        if (!$node instanceof Node\Expr\StaticCall || !$this->isName($node->name, $oldMethodName)) {
            return false;
        }

        $classReflection = $this->reflectionResolver->resolveClassReflectionSourceObject($node);

        if (!$classReflection instanceof ClassReflection) {
            if (!$this->isNames($node->class, ['static', 'self'])) {
                if ($oldMethodName === $newMethodName && $this->getName($node->class) === $newClassName) {
                    return false;
                }

                return is_a($this->getName($node->class), $oldClassName, true);
            }

            $classReflection = $this->reflectionResolver->resolveClassReflection($node);
        }

        if (!$classReflection instanceof ClassReflection) {
            return false;
        }

        if ($oldMethodName === $newMethodName && $classReflection->getName() === $newClassName) {
            return false;
        }

        return $classReflection->is($oldClassName);
    }

    protected function isMethodCall(Node $node, string $className, string $methodName): bool
    {
        return $node instanceof Node\Expr\MethodCall
            && $this->isName($node->name, $methodName)
            && $this->isObjectType($node->var, new ObjectType($className))
        ;
    }
}
