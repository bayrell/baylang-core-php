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
namespace Runtime\Console\Commands;

use Runtime\Method;
use Runtime\Console\BaseCommand;
use Runtime\Console\CommandsList;


class Help extends \Runtime\Console\BaseCommand
{
	/**
	 * Returns name
	 */
	static function getName(){ return "help"; }
	
	
	/**
	 * Returns description
	 */
	static function getDescription(){ return "Show help"; }
	
	
	/**
	 * Run task
	 */
	static function run()
	{
		\Runtime\rtl::print("Methods:");
		$commands = \Runtime\rtl::getContext()->provider("Runtime.Console.CommandsList");
		$keys = $commands->getCommands();
		for ($i = 0; $i < $keys->count(); $i++)
		{
			$command_name = $keys->get($i);
			$class_name = $commands->getCommandByName($command_name);
			$getDescription = new \Runtime\Method($class_name, "getDescription");
			$command_description = $getDescription->apply();
			\Runtime\rtl::print(\Runtime\rtl::color("yellow", $command_name) . " - " . $command_description);
		}
		return static::SUCCESS;
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
	}
	static function getClassName(){ return "Runtime.Console.Commands.Help"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}