<?php
class ExpressBank_ZapDep_Employee {
	public $name;
	public $salary;
	public $egn;
	public $iban;


	function __construct($name, $salary, $egn, $iban) {
		if (empty($egn)) {
			throw new Exception("Missing EGN for $name");
		}
		if (empty($iban)) {
			throw new Exception("Missing IBAN for $name");
		}

		$this->name = $name;
		$this->salary = $salary;
		$this->egn = $egn;

		$this->iban = $iban;
	}
}
