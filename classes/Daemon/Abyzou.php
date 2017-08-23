<?php
namespace zest;

class Daemon_Abyzou extends Daemon {
	static $daemon_options = array(
		'run_seconds' => 10,
		'sleep_seconds' => 10
	);
	public static function daemon(zesk\Interface_Process $process) {
		self::_daemon($process, __CLASS__, self::$daemon_options);
	}
}
