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
namespace Runtime\Console;

use Runtime\Entity\Provider;
use Runtime\Console\CommandsList;
use Runtime\Console\Annotations\ConsoleCommand;


class ModuleDescription
{
	/**
	 * Returns module name
	 * @return string
	 */
	static function getModuleName(){ return "Runtime.Console"; }
	
	
	/**
	 * Returns module name
	 * @return string
	 */
	static function getModuleVersion(){ return "0.12.0"; }
	
	
	/**
	 * Returns required modules
	 * @return Map<string, string>
	 */
	static function requiredModules()
	{
		return new \Runtime\Map([
			"Runtime" => "*",
		]);
	}
	
	
	/**
	 * Returns enities
	 */
	static function entities()
	{
		return new \Runtime\Vector(
			new \Runtime\Console\Annotations\ConsoleCommand("Runtime.Console.Commands.Help"),
			new \Runtime\Entity\Provider("Runtime.Console.CommandsList", new \Runtime\Console\CommandsList()),
		);
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
	}
	static function getClassName(){ return "Runtime.Console.ModuleDescription"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}