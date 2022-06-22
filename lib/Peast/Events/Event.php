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

class Event extends \System\Events\Event
{
    const PROPERTY_CHANGE = 'PropertyChangeEvent';

    public function __construct($sender, $extra = [])
    {
        $this->sender = $sender;
        $this->extra = $extra;
    }
}