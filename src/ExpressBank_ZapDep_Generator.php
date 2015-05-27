<?php
class ExpressBank_ZapDep_Generator {
	protected $employees = [];

	protected $export = '';

	public $company_name;

	public $company_iban;

	public $total = 0;
	public $encoding = EXPRESSBANK_EXPORT_ENCODING;

	function __construct($company_name, $company_iban) {
		$this->company_name = $company_name;
		$this->company_iban = $company_iban;
	}

	function add_employees($employees) {
		foreach ($employees as $emp) {
			$this->add_employee($emp);
		}
	}
	function add_employee(ExpressBank_ZapDep_Employee $employee) {
		$this->employees[] = $employee;
		$this->total += $employee->salary;
	}

	function generate() {
		$this->export = ExpressBank_Export::make();

		$this->generate_intro();
		$this->generate_summary();
		$this->generate_employees();

		return $this->export->generate();
	}

	function generate_intro() {
		$line = ExpressBank_Export_Line::make();

		$col = ExpressBank_Export_Line_Column::make($this->company_name, 50);
		$col->is_fixed_width = false;

		$line->append($col);

		$this->export->append($line);
	}

	function generate_summary() {
		$line = ExpressBank_Export_Line::make();

		$line->append([
			// Индентификатор на реда с фиксирана стойност "01"
			ExpressBank_Export_Line_Column::make('01', 2),

			// Обща сума на заплатите(трябва да е равна на сбора от сумите в детайлните редове)
			ExpressBank_Export_Line_Column::make($this->total, 20, "0", STR_PAD_LEFT),

			// IBAN от който се плащат заплатите (контрол)
			ExpressBank_Export_Line_Column::make($this->company_iban, 22),

			// Запълва се с три интервала
			ExpressBank_Export_Line_Column::make('   ', 3),

			// Брой детайлни редове от тип 02 (брой служители)
			ExpressBank_Export_Line_Column::make(count($this->employees), 5, '0', STR_PAD_LEFT),

			// Дата 
			ExpressBank_Export_Line_Column::make(date('Ymd'), 8),
		]);

		$this->export->append($line);
	}

	function generate_employees() {
		foreach ($this->employees as $emp) {
			$line = ExpressBank_Export_Line::make();

			$emp_name_column = ExpressBank_Export_Line_Column::make($emp->name, 26, ' ');
			// If the employee name is too long, it's OK to just clip it. EGN is the identifier rather than the name
			$emp_name_column->allow_clip = true;

			$salary = sprintf("%0.2f", $emp->salary);

			$line->append([
				// Индентификатор на реда с фиксирана стойност "02"
				ExpressBank_Export_Line_Column::make('02', 2),

				// ЕГН на служителя
				ExpressBank_Export_Line_Column::make($emp->egn, 10),

				// Име на служителя
				$emp_name_column,

				// Паспортни данни (при липса се запълва с интервали) 
				ExpressBank_Export_Line_Column::make(str_repeat(' ', 34), 34),

				// Дата 
				ExpressBank_Export_Line_Column::make(date('Ymd'), 8),

				// Сума на заплатата на служителя
				ExpressBank_Export_Line_Column::make($salary, 20, '0', STR_PAD_LEFT),

				// Код с фиксирана стойност "0"
				ExpressBank_Export_Line_Column::make('0', 1),

				// IBAN на служителя 
				ExpressBank_Export_Line_Column::make($emp->iban, 22),
			]);

			$this->export->append($line);
		}
	}
}
