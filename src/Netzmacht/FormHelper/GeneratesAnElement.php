<?php

/**
 * @package    dev
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2014 netzmacht creative David Molineus
 * @license    LGPL 3.0
 * @filesource
 *
 */

namespace Netzmacht\FormHelper;

use Netzmacht\Html\Element;

/**
 * Interface GeneratesAnElement is used to be assigned to form widgets which already renders an element which can be
 * used of the form helper
 *
 * @package Netzmacht\FormHelper
 */
interface GeneratesAnElement
{

	/**
	 * @return Element
	 */
	public function generate();

}