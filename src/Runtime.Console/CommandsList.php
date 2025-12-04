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

use Runtime\lib;
use Runtime\BaseProvider;
use Runtime\Method;
use Runtime\Console\BaseCommand;
use Runtime\Console\Annotations\ConsoleCommand;


class CommandsList extends \Runtime\BaseProvider
{
	var $commands_list;
	
	
	/**
	 * Start provider
	 */
	function start()
	{
		$this->commands_list = new \Runtime\Map();
		$commands = \Runtime\rtl::getContext()->getEntities("Runtime.Console.Annotations.ConsoleCommand");
		for ($i = 0; $i < $commands->count(); $i++)
		{
			$info = $commands->get($i);
			$command_class_name = $info->name;
			if ($command_class_name)
			{
				/* Get method getRoutes */
				$getName = new \Runtime\Method($command_class_name, "getName");
				/* Returns command name */
				$name = $getName->apply();
				/* Add to list */
				$this->commands_list->set($name, $command_class_name);
			}
		}
	}
	
	
	/**
	 * Returns command by name
	 */
	function getCommandByName($name){ return $this->commands_list->get($name); }
	
	
	/**
	 * Returns commands list
	 */
	function getCommands(){ return \Runtime\rtl::list($this->commands_list->keys())->sort(); }
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->commands_list = new \Runtime\Map();
	}
	static function getClassName(){ return "Runtime.Console.CommandsList"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}