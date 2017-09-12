<?php
/**
 * @desc       
 * @version    $URL: https://code.marketacumen.com/zesk/trunk/modules/zest/classes/test/xmlrpc/server.inc $
 * @author     $Author: kent $
 * @package    modules
 * @subpackage zesk_test
 * @copyright  Copyright (C) 2013, {company}. All rights reserved.
 */
namespace zest;

use xmlrpc\Server;
use zesk\str;

class Test_XMLRPC_Server extends Server {
	protected $rpc_methods = array(
		"capitalize" => array(
			"string",
			"this:capitalize",
			array(
				"string" => "string"
			),
			"Capitalizes a word",
			array(
				"string" => "String to capitalize"
			)
		)
	);
	function rpc_capitalize($string) {
		return str::capitalize($string);
	}
}
