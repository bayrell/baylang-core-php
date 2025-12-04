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

use Runtime\io;
use Runtime\BaseProvider;
use Runtime\Method;
use Runtime\Context;
use Runtime\Entity;
use Runtime\Console\CommandsList;


class App extends \Runtime\BaseProvider
{
	/**
	 * Init app
	 */
	function init(){}
	
	
	/**
	 * Start app
	 */
	function start(){}
	
	
	/**
	 * Returns exit code from command
	 */
	function getExitCode($command_error)
	{
		if ($command_error == 0) return 1;
		if ($command_error > 0) return 0;
		return 0 - $command_error;
	}
	
	
	/**
	 * Main entry point
	 */
	function main()
	{
		$command_error = -1;
		/* Run console command */
		$commands = \Runtime\rtl::getContext()->provider("Runtime.Console.CommandsList");
		$cmd = \Runtime\rtl::getContext()->cli_args[1];
		if ($cmd == null) $cmd = "help";
		/* Find class name */
		$class_name = $commands->getCommandByName($cmd);
		if (!\Runtime\rtl::classExists($class_name))
		{
			\Runtime\rtl::error("Command " . $cmd . " not found");
			return $this->getExitCode(-1);
		}
		/* Find command */
		$command_run = new \Runtime\Method($class_name, "run");
		if (!$command_run->exists())
		{
			$command_run = new \Runtime\Method(\Runtime\rtl::newInstance($class_name), "run");
			if (!$command_run->exists())
			{
				\Runtime\rtl::error("Command " . $cmd . " not found");
				return $this->getExitCode(-1);
			}
		}
		/* Run command */
		$command_error = $command_run->apply();
		return $this->getExitCode($command_error);
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
	}
	static function getClassName(){ return "Runtime.Console.App"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}