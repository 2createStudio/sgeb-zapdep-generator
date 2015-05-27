<?php
class ExpressBank_Export {
	public $lines = []; 
	public $eol = "\r\n";

	static function make() {
		return new self();
	}

	function append(ExpressBank_Export_Line $line) {
		$this->lines[] = $line;
	}

	function generate() {
		$result = [];

		foreach ($this->lines as $line) {
			$result[] = $line->get_text();
		}

		return implode($this->eol, $result);
	}
}
