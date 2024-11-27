<?php

declare(strict_types=1);

namespace App\Rector\Rules;

use PhpParser\Node;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\AssignOp;
use PhpParser\Node\Expr\BinaryOp\Coalesce;
use PhpParser\Node\Expr\BinaryOp\Concat;
use PhpParser\Node\Expr\BinaryOp\Mul;
use PhpParser\Node\Expr\BinaryOp\Plus;
use PhpParser\Node\Expr\BinaryOp\Minus;
use PhpParser\Node\Expr\BinaryOp\Div;
use PhpParser\Node\Expr\Empty_;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\List_;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Isset_;
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Expr\BinaryOp\NotIdentical;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\PreInc;
use PhpParser\Node\Expr\PostInc;
use PhpParser\Node\Expr\PreDec;
use PhpParser\Node\Expr\PostDec;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\Foreach_;
use PhpParser\Node\Stmt\Return_;
use PhpParser\Node\Stmt\Unset_;
use PhpParser\Node\Arg;
use Rector\Core\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * Replaces undefined array key access and isset checks with nullish coalescing operator
 */
final class ArrayKeyNullishCoalescingRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition(
            'Replace undefined array key access and isset checks with nullish coalescing operator',
            [
                new CodeSample(
                    <<<'CODE_SAMPLE'
$array = [];
$value = isset($array['key']) ? $array['key'] : null;
CODE_SAMPLE
                    ,
                    <<<'CODE_SAMPLE'
$array = [];
$value = $array['key'] ?? null;
CODE_SAMPLE
                ),
            ]
        );
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [
            Isset_::class,
            ArrayDimFetch::class,
            Identical::class,
            NotIdentical::class,
        ];
    }

    /**
     * @param Isset_|ArrayDimFetch|Identical|NotIdentical $node
     */
    public function refactor(Node $node): ?Node
    {
        if ($node instanceof Isset_) {
            return $this->refactorIsset($node);
        }

        if ($node instanceof ArrayDimFetch) {
            // Skip if this array access is inside an isset
            if ($this->isInsideIsset($node)) {
                return null;
            }

            // Skip if this array access is part of a larger array access that's being assigned to
            if ($this->isPartOfAssignmentTarget($node)) {
                return null;
            }

            // Skip if this array access is part of an arithmetic operation
            if ($this->isPartOfArithmeticOperation($node)) {
                return null;
            }
            
            // Skip if this is part of an unset operation
            if ($this->isPartOfUnset($node)) {
                return null;
            }

            // Skip if this is part of a method call
            if ($this->isPartOfMethodCall($node)) {
                return null;
            }

            // Skip if this is part of string concatenation
            if ($this->isPartOfConcatenation($node)) {
                return null;
            }

            // Skip if this is part of a function call argument
            if ($this->isPartOfFunctionArgument($node)) {
                return null;
            }

            // Skip if this is an array key
            if ($this->isArrayKey($node)) {
                return null;
            }

            // Skip if this is part of a compound assignment (+=, -=, etc.)
            if ($this->isPartOfCompoundAssignment($node)) {
                return null;
            }

            // Skip if this is part of increment/decrement
            if ($this->isPartOfIncrementOrDecrement($node)) {
                return null;
            }

            // Skip if this is part of a foreach
            if ($this->isPartOfForeach($node)) {
                return null;
            }

            // Skip if this is part of a by-reference return
            if ($this->isPartOfReferenceReturn($node)) {
                return null;
            }

            // Skip if this is part of a property access
            if ($this->isPartOfPropertyAccess($node)) {
                return null;
            }

            // Skip if this is part of a list assignment
            if ($this->isPartOfListAssignment($node)) {
                return null;
            }

            // Skip if this is part of an empty() check
            if ($this->isPartOfEmptyCheck($node)) {
                return null;
            }

            // Skip if this is part of a type checking function
            if ($this->isPartOfTypeCheck($node)) {
                return null;
            }

            // Skip if this already has a null coalescing operator
            if ($this->hasNullCoalescingOperator($node)) {
                return null;
            }

            return $this->refactorArrayDimFetch($node);
        }

        if ($node instanceof Identical || $node instanceof NotIdentical) {
            return $this->refactorComparison($node);
        }

        return null;
    }

    private function refactorIsset(Isset_ $isset): ?Node
    {
        $vars = $isset->vars;
        if (count($vars) !== 1) {
            return null;
        }

        $var = $vars[0];
        if (!$var instanceof ArrayDimFetch) {
            return null;
        }

        // Skip if the array access already has a null coalescing operator
        if ($this->hasNullCoalescingOperator($var)) {
            return $var;
        }

        return new Coalesce($var, $this->createNull());
    }

    private function refactorArrayDimFetch(ArrayDimFetch $arrayDimFetch): ?Node
    {
        if (!$this->shouldRefactorArrayDimFetch($arrayDimFetch)) {
            return null;
        }

        return new Coalesce($arrayDimFetch, $this->createNull());
    }

    private function refactorComparison(Identical|NotIdentical $node): ?Node
    {
        if (!$this->isNullComparison($node)) {
            return null;
        }

        $arrayDimFetch = $this->extractArrayDimFetch($node);
        if ($arrayDimFetch === null) {
            return null;
        }

        if ($node instanceof NotIdentical) {
            return new Coalesce($arrayDimFetch, $this->createTrue());
        }

        return new Coalesce($arrayDimFetch, $this->createFalse());
    }

    private function shouldRefactorArrayDimFetch(ArrayDimFetch $arrayDimFetch): bool
    {
        $parent = $arrayDimFetch->getAttribute('parent');
        if (!$parent instanceof Node) {
            return false;
        }

        // Skip if this is part of an assignment target
        if ($parent instanceof Assign) {
            return false;
        }

        return true;
    }

    private function isNullComparison(Identical|NotIdentical $node): bool
    {
        return ($this->isNull($node->left) && $node->right instanceof ArrayDimFetch)
            || ($this->isNull($node->right) && $node->left instanceof ArrayDimFetch);
    }

    private function extractArrayDimFetch(Identical|NotIdentical $node): ?ArrayDimFetch
    {
        if ($node->left instanceof ArrayDimFetch) {
            return $node->left;
        }

        if ($node->right instanceof ArrayDimFetch) {
            return $node->right;
        }

        return null;
    }

    private function isNull(Node $node): bool
    {
        if (!$node instanceof ConstFetch) {
            return false;
        }

        return $node->name->toString() === 'null';
    }

    private function createNull(): ConstFetch
    {
        return new ConstFetch(new Node\Name('null'));
    }

    private function createTrue(): ConstFetch
    {
        return new ConstFetch(new Node\Name('true'));
    }

    private function createFalse(): ConstFetch
    {
        return new ConstFetch(new Node\Name('false'));
    }

    private function isPartOfAssignmentTarget(ArrayDimFetch $node): bool
    {
        $current = $node;
        while ($current) {
            $parent = $current->getAttribute('parent');
            if ($parent instanceof Assign && $parent->var === $current) {
                return true;
            }
            if (!$parent instanceof ArrayDimFetch) {
                break;
            }
            $current = $parent;
        }
        return false;
    }

    private function isPartOfUnset(ArrayDimFetch $node): bool
    {
        $current = $node;
        while ($current) {
            $parent = $current->getAttribute('parent');
            if ($parent instanceof Unset_) {
                return true;
            }
            if (!$parent instanceof ArrayDimFetch) {
                break;
            }
            $current = $parent;
        }
        return false;
    }

    private function isPartOfMethodCall(ArrayDimFetch $node): bool
    {
        $parent = $node->getAttribute('parent');
        return $parent instanceof MethodCall && $parent->var === $node;
    }

    private function isPartOfConcatenation(ArrayDimFetch $node): bool
    {
        $parent = $node->getAttribute('parent');
        return $parent instanceof Concat;
    }

    private function isPartOfFunctionArgument(ArrayDimFetch $node): bool
    {
        $parent = $node->getAttribute('parent');
        return $parent instanceof Arg;
    }

    private function isArrayKey(ArrayDimFetch $node): bool
    {
        $parent = $node->getAttribute('parent');
        return $parent instanceof ArrayItem && $parent->key === $node;
    }

    private function isPartOfCompoundAssignment(ArrayDimFetch $node): bool
    {
        $parent = $node->getAttribute('parent');
        return $parent instanceof AssignOp;
    }

    private function isPartOfIncrementOrDecrement(ArrayDimFetch $node): bool
    {
        $parent = $node->getAttribute('parent');
        return $parent instanceof PreInc 
            || $parent instanceof PostInc 
            || $parent instanceof PreDec 
            || $parent instanceof PostDec;
    }

    private function isPartOfForeach(ArrayDimFetch $node): bool
    {
        $parent = $node->getAttribute('parent');
        return $parent instanceof Foreach_ && $parent->expr === $node;
    }

    private function isPartOfReferenceReturn(ArrayDimFetch $node): bool
    {
        $current = $node;
        while ($current) {
            $parent = $current->getAttribute('parent');
            if ($parent instanceof Return_) {
                // Found a return statement, now check if it's in a by-ref function/method
                $context = $this->findParentFunctionOrMethod($parent);
                if ($context instanceof Function_) {
                    return $context->byRef;
                }
                if ($context instanceof ClassMethod) {
                    return $context->byRef;
                }
                return false;
            }
            if (!$parent instanceof Node) {
                break;
            }
            $current = $parent;
        }
        return false;
    }

    private function findParentFunctionOrMethod(Node $node): ?Node
    {
        $current = $node;
        while ($current) {
            if ($current instanceof Function_ || $current instanceof ClassMethod) {
                return $current;
            }
            $current = $current->getAttribute('parent');
            if (!$current instanceof Node) {
                break;
            }
        }
        return null;
    }

    private function isPartOfPropertyAccess(ArrayDimFetch $node): bool
    {
        $parent = $node->getAttribute('parent');
        return $parent instanceof Node\Expr\PropertyFetch 
            || $parent instanceof Node\Expr\StaticPropertyFetch;
    }

    private function isPartOfListAssignment(ArrayDimFetch $node): bool
    {
        $current = $node;
        while ($current) {
            $parent = $current->getAttribute('parent');
            if ($parent instanceof List_) {
                return true;
            }
            if (!$parent instanceof Node) {
                break;
            }
            $current = $parent;
        }
        return false;
    }

    private function isPartOfEmptyCheck(ArrayDimFetch $node): bool
    {
        $parent = $node->getAttribute('parent');
        return $parent instanceof Empty_;
    }

    private function isPartOfTypeCheck(ArrayDimFetch $node): bool
    {
        $parent = $node->getAttribute('parent');
        if (!$parent instanceof Arg) {
            return false;
        }

        $grandParent = $parent->getAttribute('parent');
        if (!$grandParent instanceof FuncCall) {
            return false;
        }

        $name = $grandParent->name;
        if (!$name instanceof Node\Name) {
            return false;
        }

        // List of type checking functions
        $typeCheckFunctions = [
            'is_array',
            'is_bool',
            'is_callable',
            'is_countable',
            'is_float',
            'is_int',
            'is_integer',
            'is_iterable',
            'is_null',
            'is_numeric',
            'is_object',
            'is_resource',
            'is_scalar',
            'is_string',
            'count',
            'sizeof',
        ];

        return in_array($name->toString(), $typeCheckFunctions, true);
    }

    private function hasNullCoalescingOperator(Node $node): bool
    {
        // Check if this node or any parent is already part of a coalesce operation
        $current = $node;
        while ($current) {
            $parent = $current->getAttribute('parent');
            if ($parent instanceof Coalesce) {
                return true;
            }
            if (!$parent instanceof Node) {
                break;
            }
            $current = $parent;
        }

        // Check if the array being accessed is already using ??
        if ($node instanceof ArrayDimFetch) {
            $var = $node->var;
            if ($var instanceof Coalesce || ($var instanceof ArrayDimFetch && $this->hasNullCoalescingOperator($var))) {
                return true;
            }
        }

        return false;
    }

    private function isInsideIsset(Node $node): bool
    {
        $current = $node;
        while ($current) {
            $parent = $current->getAttribute('parent');
            if ($parent instanceof Isset_) {
                return true;
            }
            if (!$parent instanceof Node) {
                break;
            }
            $current = $parent;
        }
        return false;
    }

    private function isPartOfArithmeticOperation(Node $node): bool
    {
        $parent = $node->getAttribute('parent');
        return $parent instanceof Mul 
            || $parent instanceof Plus 
            || $parent instanceof Minus 
            || $parent instanceof Div;
    }
}