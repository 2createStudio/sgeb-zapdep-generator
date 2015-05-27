<?php
require_once(dirname(__FILE__) . '/load.php');

$company_name = "Parts Unlimited";
$company_iban = "BG80BNBG96611020345678";

$payroll_data = [
	[
		'full_name' => 'Николай Николаев Николаев',
		'salary'    => '500.00',
		'egn'       => '0123456789',
		'iban'      => 'BG80BNBG96611020345678',
	],
	[
		'full_name' => 'Иван Иванов Иванов',
		'salary'    => '600.00',
		'egn'       => '9876543210',
		'iban'      => 'BG80BNBG96611020345678',
	],
	[
		'full_name' => 'Георги Георгиев Георгиев',
		'salary'    => '700.00',
		'egn'       => '9871324650',
		'iban'      => 'BG80BNBG96611020345678',
	],
];

$generator = new ExpressBank_ZapDep_Generator($company_name, $company_iban);

foreach ($payroll_data as $employee) {
	try {
		$generator->add_employee(new ExpressBank_ZapDep_Employee(
			$employee['full_name'],
			$employee['salary'],
			$employee['egn'],
			$employee['iban']
		));
	} catch (Exception $e) {
		echo "Error with employee " . $employee['full_name'] . ": " . $e->getMessage();
	}
}


// Uncomment this to force browser to download the file instead of dumping it directly to the user:
// header('Content-Disposition: attachment; filename="ZAPDEP.DAP"');

header('Content-type: text/plain; charset=' . $generator->encoding);
echo $generator->generate();
