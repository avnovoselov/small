<?php
include __DIR__ . "/../../src/Small/Script.php";
include __DIR__ . "/../../src/Small/DaemonInterface.php";
include __DIR__ . "/../../src/Small/Daemon.php";
include __DIR__ . "/../../src/Small/Arguments.php";
include __DIR__ . "/../../src/Small/Terminal.php";
include __DIR__ . "/Counter.php";

$daemon = new Counter('Counter');

$daemon
	->environment()
	->arguments([
		'skip' => 'even',
	])
	->run();