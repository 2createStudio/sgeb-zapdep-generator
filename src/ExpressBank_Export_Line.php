<?php

class ExpressBank_Export_Line {
	public $columns = [];

	static function make() {
		return new self();
	}

	function append($cols) {
		if (!is_array($cols)) {
			$cols = array($cols);
		}
		foreach ($cols as $col) {
			$this->append_col($col);
		}
	}
	function append_col(ExpressBank_Export_Line_Column $col) {
		$this->columns[] = $col;
	}

	function get_text() {
		$result = [];

		foreach ($this->columns as $col) {
			$result[] = $col->format();
		}

		return implode('', $result);
	}
}
