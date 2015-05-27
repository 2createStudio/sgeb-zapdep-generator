<?php
define('EXPRESSBANK_EXPORT_ENCODING', 'cp1251');
mb_internal_encoding("utf-8");


$src_dir = dirname(__FILE__) . '/src/';

require_once($src_dir . 'ExpressBank_ZapDep_Employee.php');
require_once($src_dir . 'ExpressBank_Export.php');
require_once($src_dir . 'ExpressBank_Export_Line.php');
require_once($src_dir . 'ExpressBank_Export_Line_Column.php');
require_once($src_dir . 'ExpressBank_ZapDep_Generator.php');
