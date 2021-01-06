<?php
/**
 * This file is part of the Peast package
 *
 * (c) Marco Marchiò <marco.mm89@gmail.com>
 *
 * For the full copyright and license information refer to the LICENSE file
 * distributed with this source code
 */
namespace Peast\Selector\Node;

use Peast\Selector\Matches;
use Peast\Syntax\Utils;
use Peast\Traverser;

/**
 * Selector combinator class
 * 
 * @author Marco Marchiò <marco.mm89@gmail.com>
 */
class Combinator
{
    /**
     * Operator
     *
     * @var string|null
     */
    protected $operator;

    /**
     * Selector parts
     *
     * @var Part\Part[]
     */
    protected $parts = array();

    /**
     * Sets the operator
     *
     * @param string $operator Operator
     * @return $this
     */
    public function setOperator($operator)
    {
        $this->operator = $operator;
        return $this;
    }

    /**
     * Returns the operator
     *
     * @return string|null
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * Adds a new selector part
     *
     * @param Part\Part $part Part
     * @return $this
     */
    public function addPart(Part\Part $part)
    {
        $this->parts[] = $part;
        return $this;
    }

    /**
     * Returns the selector parts
     *
     * @return Part\Part[]
     */
    public function getParts()
    {
        return $this->parts;
    }

    /**
     * Executes the current group on the given matches
     *
     * @param Matches $matches Matches
     */
    public function exec(Matches $matches)
    {
        $parts = $this->parts;
        //Sort the parts by priority to execute faster checks first
        usort($parts, function ($p1, $p2) {
            $pr1 = $p1->getPriority();
            $pr2 = $p2->getPriority();
            if ($pr1 === $pr2) {
                return 0;
            } elseif ($pr1 < $pr2) {
                return -1;
            } else {
                return 1;
            }
        });
        $filter = function ($node, $parent) use ($parts) {
            foreach ($parts as $part) {
                if (!$part->check($node, $parent)) {
                    return false;
                }
            }
            return true;
        };
        switch ($this->operator) {
            case " ":
            case ">":
                $children = $this->operator === ">";
                $matches->map(function ($curNode) use ($filter, $children) {
                    $ret = array();
                    $traverser = new Traverser(array(
                        "skipStartingNode" => true,
                        "passParentNode" => true
                    ));
                    $traverser->addFunction(
                        function ($node, $parent) use ($filter, $children, &$ret) {
                            if ($filter($node, $parent)) {
                                $ret[] = array($node, $parent);
                            }
                            if ($children) {
                                return Traverser::DONT_TRAVERSE_CHILD_NODES;
                            }
                        }
                    );
                    $traverser->traverse($curNode);
                    return $ret;
                });
            break;
            case "~":
            case "+":
                $adjacent = $this->operator === "+";
                $matches->map(function ($node, $parent) use ($filter, $adjacent) {
                    if (!$parent) {
                        return null;
                    }
                    $ret = array();
                    $evaluate = false;
                    $props = Utils::getNodeProperties($parent, true);
                    foreach ($props as $prop) {
                        $propNode = $parent->{$prop["getter"]}();
                        if ($evaluate) {
                            if ($filter($node, $parent)) {
                                $ret[] = array($propNode, $parent);
                            }
                            if ($adjacent) {
                                break;
                            }
                        } elseif ($propNode === $node) {
                            $evaluate = true;
                        }
                    }
                    return $ret;
                });
            break;
            default:
                $matches->filter($filter);
            break;
        }
    }
}