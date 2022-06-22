<?php
/**
 *
 *
 * @category Events
 * @package  peast
 * @author   Peter Georgiev
 * @license  http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link     none
 */

namespace Peast\Events;


class PropertyChangeEvent extends Event
{
    public $propertyName;

    public function __construct($propertyName, $sender, $extra = [])
    {
        $this->propertyName = $propertyName;
        parent::__construct($sender, $extra);
    }
}