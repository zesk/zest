<?php
namespace zest;

class Daemon_Pontianak extends Daemon {
	static $daemon_options = array(
		'run_seconds' => 240,
		'sleep_seconds' => 60
	);
	public static function daemon(zesk\Interface_Process $process) {
		parent::_daemon($process, __CLASS__, self::$daemon_options);
	}
}
