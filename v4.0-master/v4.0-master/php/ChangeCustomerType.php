<?php
/**
	The MIT License (MIT)
	
	Copyright (c) 2015 Ignacio Nieto Carvajal
	
	Permission is hereby granted, free of charge, to any person obtaining a copy
	of this software and associated documentation files (the "Software"), to deal
	in the Software without restriction, including without limitation the rights
	to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
	copies of the Software, and to permit persons to whom the Software is
	furnished to do so, subject to the following conditions:
	
	The above copyright notice and this permission notice shall be included in
	all copies or substantial portions of the Software.
	
	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
	IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
	AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
	LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
	OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
	THE SOFTWARE.
*/

require_once('DbHandler.php');
require_once('CRMDefaults.php');
require_once('LanguageHandler.php');
require('Session.php');

$lh = \creamy\LanguageHandler::getInstance();
$user = \creamy\CreamyUser::currentUser();

// check required fields
$validated = 1;
if (!isset($_POST["customerid"])) {
	$validated = 0;
}
if (!isset($_POST["old_customer_type"])) {
	$validated = 0;
}
if (!isset($_POST["new_customer_type"])) {
	$validated = 0;
}

if ($validated == 1) {
	$db = new \creamy\DbHandler();

	// parameters
	$customerid = $_POST["customerid"];
	$oldCustomerType = $_POST["old_customer_type"];
	$newCustomerType = $_POST["new_customer_type"];

	// create customer and return result.
	$result = $db->changeCustomerType($customerid, $oldCustomerType, $newCustomerType);
	if ($result === true) { ob_clean(); print CRM_DEFAULT_SUCCESS_RESPONSE; }
	else { ob_clean(); $lh->translateText("unable_modify_customer"); } 
} else { ob_clean(); $lh->translateText("some_fields_missing"); }
?>