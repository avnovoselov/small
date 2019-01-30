<?php
include __DIR__ . "/../../Small/Terminal.php";


$terminal = new \Small\Terminal([
	'loadingPulseSize' => 5,
	'loadingSymbol'    => '*',
	'shift'            => 1,
]);

for ($i = 0; $i <= 100; $i++) {
	$terminal->loading($i, \Small\Terminal::TYPE_LOADING_PERCENT);
	usleep(25000);
}
$terminal->blank();
for ($i = 0; $i <= 100; $i++) {
	$terminal->loading($i, \Small\Terminal::TYPE_LOADING_BAR);
	usleep(25000);
}
$terminal->blank();
for ($i = 0; $i <= 100; $i++) {
	$terminal->loading($i, \Small\Terminal::TYPE_LOADING_ROTATE);
	usleep(25000);
}
$terminal->blank();
for ($i = 0; $i <= 100; $i++) {
	$terminal->loading($i, \Small\Terminal::TYPE_LOADING_PULSE);
	usleep(25000);
}

$terminal
	->separator()
	->shift()
	->info('Between')
	->unshift()
	->separator()
	->blank()
	->separator()
	->header('header', \Small\Terminal::TEXT_COLOR_GREEN)
	->shift()
	->info('Info')
	->success('Success')
	->warning('Warning')
	->danger('Danger')
	->critical('Critical')
	->resetShift();