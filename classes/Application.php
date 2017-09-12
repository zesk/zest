<?php
/**
 * @desc
 * @version    $URL: https://code.marketacumen.com/zesk/trunk/modules/zest/classes/zest.inc $
 * @author     $Author: kent $
 * @package    modules
 * @subpackage zesk_test
 * @copyright  Copyright (C) 2013, {company}. All rights reserved.
 */
namespace zest;

use zesk\Directory;
use zesk\Database;
use zesk\Request;
use zesk\Response;
/**
 * The Zest application allow for test running and writing directory within the browser for
 * immediate satisfaction running tests against any code you write.
 *
 * @author kent
 *
 */
class Application extends \zesk\Application {
	
	/**
	 * How the router is found
	 *
	 * @var string
	 */
	public $file = __FILE__;
	
	/**
	 *
	 * @var array
	 */
	protected $register_hooks = array(
		'zesk\Database',
		'zesk\Settings'
	);
	
	/**
	 *
	 * @var array
	 */
	protected $load_modules = array(
		'XMLRPC',
		'Bootstrap',
		'MySQL',
		'Developer',
		'Logger_Footer'
	);
	protected $object_classes = array(
		'zesk\\Server',
		'zesk\\Lock',
		"zest\\Test",
		"zest\\Project",
		"zesk\\Settings",
		"zest\\Code_Source",
		"zest\\Code_Class",
		"zest\\Code_Method",
		"zest\\Code_Function"
	);
	public function hook_request() {
		$request = parent::hook_request();
		// Danger TODO
		$modules = $request->geta("module", array(), ";");
		if (count($modules)) {
			$this->modules->load($modules);
		}
		return $request;
	}
	public static function widget_test(Request $request, Response $response) {
		$path = str_replace("/", "_", ltrim($request->path(), "/"));
		return $this->widget_factory("zesk\\Control_Form", null, $this)->child($this->widget_factory($path))->execute();
	}
	public static function run_test(Request $reqeust, Response $response, $string) {
		global $zesk;
		/* @var $zesk zesk\Kernel */
		$test = new Command_Test(array(
			"Command_Test",
			$string
		), array(
			"directory" => $zesk->configuration->path_get("Zest::test_directory", $zesk->paths->zesk()),
			"debug" => $reqeust->getb("debug"),
			"debug-command" => $reqeust->getb("debug-command"),
			"verbose" => $reqeust->getb("verbose"),
			"no-database" => true,
			"sandbox" => true,
			"strict" => true,
			"command_local_open" => null
		));
		
		$result = array();
		$result['test_pattern'] = $string;
		
		ob_start();
		$result['result'] = $test->go();
		$result['status'] = intval($result['result']) === 0;
		$result['content'] = ob_get_clean();
		
		$response->json($result);
	}
	public function preconfigure(array $options = array()) {
		global $zesk;
		
		$this->modules->load("Logger_File");
		
		/* @var $zesk \zesk\Kernel */
		$zesk->autoloader->path($this->application_root('command'), array(
			"class_prefix" => "zest\\Command"
		));
		$this->configure_include(array(
			'zest.conf',
			'zest-' . php_uname('n') . ".conf"
		));
		$severity = $this->development(true) ? "debug" : "info";
		$zesk->logger->register_handler("file", \zesk\Logger\File::factory($this->application_root('log/zest.log')), $zesk->logger->levels_select($severity));
		
		$prefix = __NAMESPACE__ . "\\";
		$this->register_class($prefix . "Daemon_Abyzou");
		$this->register_class($prefix . "Daemon_Belphegor");
		$this->register_class($prefix . "Daemon_Jinkininki");
		$this->register_class($prefix . "Daemon_Lamashtu");
		$this->register_class($prefix . "Daemon_Pontianak");
		
		Database::database_default("zest");
		Directory::depend($this->application_root("/data/"));
		
		$this->logger->notice("Default socket is " . ini_get("mysqli.default_socket"));
		//		zesk\Database::register("zest", "sqlite3://localhost" . $this->application_root("/data/zest.sqlite3"));
		Database::register("zest", "mysqli://zest:zest@localhost/zest");
		// create database zest;
		// grant all privileges on zest.* TO zest@localhost IDENTIFIED BY 'zest';
		// flush privileges;
	}
	
	public function xmlrpc_server() {
		$server = new Test_XMLRPC_Server($this);
		$server->serve();
	}
}
