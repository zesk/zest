<?php
namespace zest;

class Daemon_Lamashtu extends Daemon {
	static $daemon_options = array(
		'run_seconds' => 1800,
		'sleep_seconds' => 60
	);
	public static function daemon(zesk\Interface_Process $process) {
		parent::_daemon($process, __CLASS__, self::$daemon_options);
	}
}
