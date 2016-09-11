<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 03/08/16
 * Time: 16.53
 */

namespace eschool;


class GradeConverter
{
	protected $conversionTables;
	
	/**
	 * @return mixed
	 */
	public function getConversionTables() {
		return $this->conversionTables;
	}
	
	/**
	 * @param mixed $conversionTables
	 */
	public function setConversionTables($conversionTable) {
		$this->conversionTables = $conversionTable;
	}
	
	public function __construct() {
	
	}
	
	public function convert() {
		
	}
}

