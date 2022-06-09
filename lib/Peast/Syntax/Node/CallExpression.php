<?php
/**
 * This file is part of the Peast package
 *
 * (c) Marco Marchiò <marco.mm89@gmail.com>
 *
 * For the full copyright and license information refer to the LICENSE file
 * distributed with this source code
 */
namespace Peast\Syntax\Node;

/**
 * A node that represents a call expression.
 * For example: test()
 * 
 * @author Marco Marchiò <marco.mm89@gmail.com>
 */
class CallExpression extends ChainElement
{
    /**
     * Map of node properties
     * 
     * @var array 
     */
    protected $propertiesMap = array(
        "callee" => true,
        "arguments" => true,
        'await' => false
    );
    
    /**
     * The callee expression
     * 
     * @var Expression|Super 
     */
    protected $callee;
    
    /**
     * The arguments array
     * 
     * @var Expression[]|SpreadElement[]
     */
    protected $arguments = array();

    /**
     * Defines whether this call should be with await
     *
     * @var boolean $await
     */
    protected $await;

    /**
     * Returns the callee expression
     * 
     * @return Expression|Super
     */
    public function getCallee()
    {
        return $this->callee;
    }

    /**
     * @return bool
     */
    public function getAwait()
    {
        return $this->await;
    }

    /**
     * @param bool $await
     */
    public function setAwait($await)
    {
        $this->await = $await;
    }
    
    /**
     * Sets the callee expression
     * 
     * @param Expression|Super $callee Callee expression
     * 
     * @return $this
     */
    public function setCallee($callee)
    {
        $this->assertType($callee, array("Expression", "Super"));
        $this->callee = $callee;
        return $this;
    }
    
    /**
     * Returns the arguments array
     * 
     * @return Expression[]|SpreadElement[]
     */
    public function getArguments()
    {
        return $this->arguments;
    }
    
    /**
     * Sets the arguments array
     * 
     * @param Expression[]|SpreadElement[] $arguments Arguments array
     * 
     * @return $this
     */
    public function setArguments($arguments)
    {
        $this->assertArrayOf($arguments, array("Expression", "SpreadElement"));
        $this->arguments = $arguments;
        return $this;
    }
}