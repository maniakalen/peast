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
 * A node that represents an object literal.
 * For example: {a: 1, b: 2, c: 3}
 * 
 * @author Marco Marchiò <marco.mm89@gmail.com>
 */
class ObjectExpression extends Node implements Expression
{
    /**
     * Map of node properties
     * 
     * @var array 
     */
    protected $propertiesMap = array(
        "properties" => true
    );
    
    /**
     * Object properties
     * 
     * @var Property[] 
     */
    protected $properties = array();
    
    /**
     * Returns object properties
     * 
     * @return Property[] 
     */
    public function getProperties()
    {
        return $this->properties;
    }
    
    /**
     * Sets object properties
     * 
     * @param Property[] $properties Object properties
     * 
     * @return $this
     */
    public function setProperties($properties)
    {
        $this->assertArrayOf($properties, array("Property", "SpreadElement"));
        $this->properties = $properties;
        foreach ($this->properties as &$prop) {
            $this->addChild($prop);
        }
        return $this;
    }
}