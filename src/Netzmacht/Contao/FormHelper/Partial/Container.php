<?php

namespace Netzmacht\Contao\FormHelper\Partial;

use Netzmacht\FormHelper\HasElement;
use Netzmacht\Html\CastsToString;
use Netzmacht\Html\Element;


class Container extends TemplateComponent
{

	const POSITION_BEFORE = 'before';
	const POSITION_AFTER = 'after';

	/**
	 * @var Element
	 */
	protected $element;

	/**
	 * @var array
	 */
	protected $children = array();

	/**
	 * @var array
	 */
	protected $position = array();

	/**
	 * @var HasElement
	 */
	protected $wrapper;

	/**
	 * @var
	 */
	protected $elementTemplate;

	/**
	 * @var bool
	 */
	protected $renderContainer;


	/**
	 * @param array $attributes
	 */
	function __construct(array $attributes=array())
	{
		parent::__construct($attributes);

		$this->renderContainer = false;
	}


	/**
	 * @param boolean $renderContainer
	 */
	public function setRenderContainer($renderContainer)
	{
		$this->renderContainer = (bool) $renderContainer;
	}


	/**
	 * @return boolean
	 */
	public function getRenderContainer()
	{
		return $this->renderContainer;
	}


	/**
	 * @param HasElement $wrapper
	 * @return $this
	 */
	public function setWrapper(HasElement $wrapper)
	{
		$this->wrapper = $wrapper;

		return $this;
	}


	/**
	 * @return mixed
	 */
	public function getWrapper()
	{
		return $this->wrapper;
	}


	/**
	 * @return array
	 */
	public function getChildren()
	{
		return $this->children;
	}


	/**
	 * @param CastsToString|string $element
	 * @return $this
	 */
	public function setElement($element)
	{
		$this->element = $element;

		return $this;
	}


	/**
	 * @return CastsToString|string
	 */
	public function getElement()
	{
		return $this->element;
	}


	/**
	 * @param mixed $elementTemplate
	 * @return $this
	 */
	public function setElementTemplateName($elementTemplate)
	{
		$this->elementTemplate = $elementTemplate;
		return $this;
	}


	/**
	 * @return mixed
	 */
	public function getElementTemplateName()
	{
		return $this->elementTemplate;
	}


	/**
	 * @param $name
	 * @param $child
	 * @param string $position
	 * @return $this
	 */
	public function addChild($name, $child, $position=Container::POSITION_AFTER)
	{
		$this->children[$name] = $child;
		$this->position[$name] = $position;

		return $this;
	}


	/**
	 * @param $name
	 * @return CastsToString|string
	 * @throws
	 */
	public function getChild($name)
	{
		if($this->hasChild($name)) {
			return $this->children[$name];
		}

		throw new \Exception(sprintf('Unkown child with name "%s"', $name));
	}


	/**
	 * @param $name
	 * @return mixed
	 * @throws \Exception
	 */
	public function getChildPosition($name)
	{
		if($this->hasChild($name)) {
			return $this->position[$name];
		}

		throw new \Exception(sprintf('Unkown child with name "%s"', $name));
	}


	/**
	 * @param string
	 * @return array
	 */
	public function getChildByPosition($destination)
	{
		$before = array();

		foreach($this->position as $name => $position) {
			if($position == $destination) {
				$before[$name] = $this->children[$name];
			}
		}

		return $before;
	}


	/**
	 * @param $name
	 * @return CastsToString|string
	 */
	public function removeChild($name)
	{
		if($this->hasChild($name)) {
			$child = $this->getChild($name);

			unset($this->children[$name]);
			unset($this->position[$name]);

			return $child;
		}

		return null;
	}


	/**
	 * Rearrange order of assigned elements
	 * @param array $order Can be a list of element names or an reset of position as well
	 * @return $this
	 */
	public function rearrangeChildren(array $order)
	{
		$position = $this->position;
		$this->position = array();

		// rearrange order
		foreach($order as $item => $pos) {
			if(!is_string($item)) {
				$item = $pos;
				$pos = $position[$item];
			}

			if(isset($position[$item])) {
				$this->position[$item] = $pos;
				unset($position[$item]);
			}
		}

		// apply old orders of not mentioned elements
		foreach($position as $item => $pos) {
			$this->position[$item] = $pos;
		}

		return $this;
	}


	/**
	 * @param $name
	 * @return bool
	 */
	public function hasChild($name)
	{
		return isset($this->children[$name]);
	}


	/**
	 * @return string
	 */
	public function generate()
	{
		if($this->template) {
			$template = new \FrontendTemplate($this->template);
			$template->before  = $this->getChildByPosition(static::POSITION_BEFORE);
			$template->after   = $this->getChildByPosition(static::POSITION_AFTER);
			$template->container = $this;

			if($this->wrapper) {
				$template->element = $this->wrapper;
			}
			else {
				$template->element = $this->element;
			}

			return $template->parse();
		}

		$buffer  = $this->generateChildren(static::POSITION_BEFORE);
		$buffer .= $this->generateElement();
		$buffer .= $this->generateChildren(static::POSITION_AFTER);

		if($this->renderContainer) {
			return sprintf('<div %s>%s%s%s</div>%s', $this->generateAttributes(), PHP_EOL, $buffer, PHP_EOL, PHP_EOL);
		}

		return $buffer;
	}


	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->generate();
	}


	/**
	 * @param $position
	 * @return string
	 */
	public function generateChildren($position)
	{
		$buffer = '';

		foreach($this->getChildByPosition($position) as $child) {
			$buffer .= (string) $child;
		}

		return $buffer;
	}


	/**
	 * @return string
	 */
	public function generateElement()
	{
		if($this->elementTemplate) {
			$template = new \FrontendTemplate($this->elementTemplate);
			$template->element = $this->element;

			$element = $template->parse();
		}
		else {
			$element = (string) $this->element;
		}

		if($this->wrapper) {
			$this->wrapper->setElement($element);
			$element = (string) $this->wrapper;
		}

		return $element;
	}

}