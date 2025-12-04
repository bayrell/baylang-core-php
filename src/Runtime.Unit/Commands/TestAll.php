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
namespace Runtime\Unit\Commands;

use Runtime\Console\BaseCommand;
use Runtime\Unit\TestProvider;
use Runtime\Unit\UnitTest;


class TestAll extends \Runtime\Console\BaseCommand
{
	/**
	 * Returns name
	 */
	static function getName(){ return "test::all"; }
	
	
	/**
	 * Returns description
	 */
	static function getDescription(){ return "Run all tests"; }
	
	
	/**
	 * Run task
	 */
	static function run()
	{
		$error_code = static::SUCCESS;
		/* List all tests */
		\Runtime\rtl::print("Run all tests:");
		$tests = \Runtime\rtl::getContext()->provider("Runtime.Unit.TestProvider");
		for ($i = 0; $i < $tests->count(); $i++)
		{
			$test = $tests->get($i);
			$error_code = $tests->runTestByName($test->name);
			if ($error_code != static::SUCCESS)
			{
				break;
			}
		}
		if ($error_code == 1)
		{
			\Runtime\rtl::print(\Runtime\rtl::color("green", "OK"));
		}
		else
		{
			\Runtime\rtl::print(\Runtime\rtl::color("red", "Fail"));
		}
		return $error_code;
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
	}
	static function getClassName(){ return "Runtime.Unit.Commands.TestAll"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}