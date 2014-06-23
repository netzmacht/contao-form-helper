<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 04.03.14
 * Time: 15:44
 */

namespace Netzmacht\FormHelper;


class FormLocator
{
	static protected $formTable = 'tl_form';

	/**
	 * @var \Database
	 */
	protected $database;

	/**
	 * @var array
	 */
	protected $forms = array();


	/**
	 * @param $database
	 */
	function __construct(\Database $database)
	{
		$this->database = $database;
	}


	/**
	 * @param $id
	 * @return \Database\Result|mixed
	 */
	public function getForm($id)
	{
		if(!isset($this->forms[$id])) {
			$this->forms[$id] = $this->database
				->prepare(sprintf('SELECT * FROM %s WHERE id=?', static::$formTable))
				->limit(1)
				->execute($id);
		}

		return $this->forms[$id];
	}

} 