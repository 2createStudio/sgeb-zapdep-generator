<?php

class ExpressBank_Export_Line_Column {
	public $is_fixed_width = true;

	public $width;

	/**
	 * Whether it's OK to clip the value if it's longer than 
	 * column width. 
	 */
	public $allow_clip = false;

	public $string;

	public $pad_string;

	/**
	 * The direction of the pad_string. See http://php.net/str_pad
	 */
	public $pad_type;

	/**
	 * @param int $width the width of the column
	 * @param string $pad_string
	 * @param $width the width of the column
	 */
	static function make($string, $width, $pad_string=" ", $pad_type=STR_PAD_RIGHT) {
		return new self($string, $width, $pad_string, $pad_type);
	}

	private function __construct($string, $width, $pad_string, $pad_type) {
		$this->string = $string;
		$this->width = $width;
		$this->pad_string = $pad_string;
		$this->pad_type = $pad_type;
	}

	public function format() {
		$str = $this->string;

		if (mb_strlen($str) > $this->width) {
			if ($this->allow_clip) {
				$str = mb_substr($str, 0, $this->width);
				return $this->convert($str);
			} else {
				throw new Exception("Value too big for column width. ");
			}
		}

		if (!$this->is_fixed_width) {
			return $this->convert($str);
		}

		$padded = $this->pad($str, $this->width, $this->pad_string, $this->pad_type);

		return $this->convert($padded);
	}

	function convert($str) {
		return mb_convert_encoding($str, EXPRESSBANK_EXPORT_ENCODING);
	}

	// See http://stackoverflow.com/a/14773775/514458
	function pad($str, $pad_len, $pad_str=' ', $dir=STR_PAD_RIGHT) {
		$str_len = mb_strlen($str);
		$pad_str_len = mb_strlen($pad_str);

		if (!$str_len && ($dir == STR_PAD_RIGHT || $dir == STR_PAD_LEFT)) {
			$str_len = 1;
		}

		if (!$pad_len || !$pad_str_len || $pad_len <= $str_len) {
			return $str;
		}

		$result = null;

		if ($dir == STR_PAD_BOTH) {
			$length = ($pad_len - $str_len) / 2;
			$repeat = ceil($length / $pad_str_len);
			$result = mb_substr(str_repeat($pad_str, $repeat), 0, floor($length))
					. $str
					. mb_substr(str_repeat($pad_str, $repeat), 0, ceil($length));
		} else {
			$repeat = ceil($str_len - $pad_str_len + $pad_len);
			if ($dir == STR_PAD_RIGHT) {
				$result = $str . str_repeat($pad_str, $repeat);
				$result = mb_substr($result, 0, $pad_len);
			} else if ($dir == STR_PAD_LEFT) {
				$result = str_repeat($pad_str, $repeat);
				$result = mb_substr($result, 0, 
							$pad_len - (($str_len - $pad_str_len) + $pad_str_len))
						. $str;
			}
		}

		return $result;
	}

}