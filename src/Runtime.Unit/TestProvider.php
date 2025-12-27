<?php
/*!
 *  BayLang Technology
 *
 *  (c) Copyright 2016-2025 "Ildar Bikmamatov" <support@bayrell.org>
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *
 *      https://www.apache.org/licenses/LICENSE-2.0
 *
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *  limitations under the License.
 */
namespace Runtime\Unit;

use Runtime\lib;
use Runtime\BaseProvider;
use Runtime\BaseStruct;
use Runtime\Method;
use Runtime\Exceptions\AssertException;
use Runtime\Exceptions\ItemNotFound;
use Runtime\Unit\Test;
use Runtime\Unit\UnitTest;


class TestProvider extends \Runtime\BaseProvider
{
	var $tests_list;
	
	
	/**
	 * Start provider
	 */
	function start()
	{
		$this->tests_list = \Runtime\rtl::getContext()->getEntities("Runtime.Unit.UnitTest");
	}
	
	
	/**
	 * Returns commands list
	 */
	function getTests(){ return $this->tests_list; }
	
	
	/**
	 * Returns unit test by pos
	 */
	function get($pos){ return $this->tests_list->get($pos); }
	
	
	/**
	 * Returns count of unit tests
	 */
	function count(){ return $this->tests_list->count(); }
	
	
	/**
	 * Run
	 */
	static function run($test_name = "")
	{
		$provider = new \Runtime\Unit\TestProvider();
		$provider->start();
		if ($test_name == "")
		{
			\Runtime\rtl::print("List of all tests:");
			for ($i = 0; $i < $provider->count(); $i++)
			{
				$test = $provider->get($i);
				\Runtime\rtl::print($i + 1 . ") " . $test->name);
			}
			return;
		}
		$provider->runTestByName($test_name);
	}
	
	
	/**
	 * Run
	 */
	static function runAll()
	{
		$provider = new \Runtime\Unit\TestProvider();
		$provider->start();
		for ($i = 0; $i < $provider->count(); $i++)
		{
			$test = $provider->get($i);
			\Runtime\rtl::print("Run " . $test->name);
			$error_code = $provider->runTestByName($test->name);
			if ($error_code != 1)
			{
				return;
			}
		}
	}
	
	
	/**
	 * Run test
	 */
	function runTestByName($test_name)
	{
		$error_code = 0;
		$arr = \Runtime\rs::split("::", $test_name);
		if ($arr->count() == 1)
		{
			/* Run all test in class */
			$error_code = $this->runTestClass($arr->get(0));
		}
		else
		{
			/* Run specific test */
			$error_code = $this->runTestMethod($arr->get(0), $arr->get(1));
		}
		return $error_code;
	}
	
	
	/**
	 * Returns true if TestMethod
	 */
	static function isTestMethod($annotations)
	{
		for ($j = 0; $j < $annotations->count(); $j++)
		{
			$annotation = $annotations->get($j);
			if ($annotation instanceof \Runtime\Unit\Test)
			{
				return true;
			}
		}
		return false;
	}
	
	
	/**
	 * Returns all test methods
	 */
	function getTestMethods($class_name)
	{
		$getMethodsList = new \Runtime\Method($class_name, "getMethodsList");
		$getMethodInfoByName = new \Runtime\Method($class_name, "getMethodInfoByName");
		$methods = $getMethodsList->apply();
		$methods = $methods->filter(function ($method_name) use (&$getMethodInfoByName)
		{
			$method_info = $getMethodInfoByName->apply(new \Runtime\Vector($method_name));
			return static::isTestMethod($method_info);
		});
		return $methods;
	}
	
	
	/**
	 * Run all test in class
	 */
	function runTestClass($class_name)
	{
		$error_code = 1;
		$methods = $this->getTestMethods($class_name);
		for ($i = 0; $i < $methods->count(); $i++)
		{
			$method_name = $methods->get($i);
			$result = $this->runTestMethod($class_name, $method_name);
			if ($result != 1)
			{
				$error_code = -1;
				break;
			}
		}
		if ($error_code == 1)
		{
			\Runtime\rtl::print(\Runtime\rtl::color("green", "Success"));
		}
		return $error_code;
	}
	
	
	/**
	 * Run test method
	 */
	function runTestMethod($class_name, $method_name)
	{
		$error_code = 0;
		try
		{
			$callback = new \Runtime\Method($class_name, $method_name);
			if (!$callback->exists())
			{
				$obj = \Runtime\rtl::newInstance($class_name);
				$callback = new \Runtime\Method($obj, $method_name);
			}
			if ($callback->exists())
			{
				$res = $callback->apply();
				$error_code = 1;
				\Runtime\rtl::print($class_name . "::" . $method_name . " " . \Runtime\rtl::color("green", "Ok"));
			}
			else
			{
				throw new \Runtime\Exceptions\ItemNotFound($class_name . "::" . $method_name, "Method");
			}
		}
		catch (\Runtime\Exceptions\AssertException $e)
		{
			\Runtime\rtl::print($class_name . "::" . $method_name . " " . \Runtime\rtl::color("red", "Error: " . $e->getErrorMessage()));
			$error_code = $e->getErrorCode();
		}
		return $error_code;
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->tests_list = new \Runtime\Vector();
	}
	static function getClassName(){ return "Runtime.Unit.TestProvider"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}