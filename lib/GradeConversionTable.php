<?php
/**
 * Created by PhpStorm.
 * User: riccardo
 * Date: 04/08/16
 * Time: 12.43
 */

namespace eschool;

abstract class GradeConversionTable
{
	private $conversionTable;
	
	private $reverseConversionTable;
	
	public function __construct($table, $reverseTable) {
		$this->conversionTable = $table;
		$this->reverseConversionTable = $reverseTable;
	}
	
	/**
	 * @return mixed
	 */
	public function getConversionTable() {
		return $this->conversionTable;
	}
	
	/**
	 * @param mixed $conversionTable
	 * @return GradeConversionTable
	 */
	public function setConversionTable($conversionTable) {
		$this->conversionTable = $conversionTable;
		return $this;
	}
	
	/**
	 * @return mixed
	 */
	public function getReverseConversionTable() {
		return $this->reverseConversionTable;
	}
	
	/**
	 * @param mixed $reverseConversionTable
	 * @return GradeConversionTable
	 */
	public function setReverseConversionTable($reverseConversionTable) {
		$this->reverseConversionTable = $reverseConversionTable;
		return $this;
	}
	
	abstract public function convert();
	
	abstract public function reverseConversion();
}
