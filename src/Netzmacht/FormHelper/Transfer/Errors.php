<?php

namespace Netzmacht\FormHelper\Transfer;

use Netzmacht\FormHelper\GenerateInterface;
use Netzmacht\FormHelper\Html\Attributes;
use Netzmacht\FormHelper\Html\AttributesTrait;
use Netzmacht\FormHelper\TemplateInterface;

class Errors implements GenerateInterface, TemplateInterface
{
	use AttributesTrait;
	use TemplateTrait;

	/**
	 * @var array
	 */
	protected $errors = array();


	/**
	 * @param array $errors
	 * @param Attributes $attributes
	 */
	function __construct(array $errors, Attributes $attributes)
	{
		$this->errors     = $errors;
		$this->attributes = $attributes;
		$this->template   = 'formhelper_error_last';
	}


	/**
	 * @return array
	 */
	public function getErrors()
	{
		return $this->errors;
	}


	/**
	 * @param $index
	 * @return string
	 */
	public function getError($index=0)
	{
		if(isset($this->errors[0])) {
			return $this->errors[0];
		}

		return '';
	}


	/**
	 * @return bool
	 */
	public function hasErrors()
	{
		return !empty($this->errors);
	}


	/**
	 * @return string
	 */
	public function generate()
	{
		$template = new \FrontendTemplate($this->template);
		$template->errors = $this->errors;
		$template->attributes = $this->attributes;

		return $template->parse();
	}


	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->generate();
	}

} 