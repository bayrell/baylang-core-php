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
namespace Runtime;

use Runtime\Entity\Entity;
use Runtime\Entity\Provider;


class ModuleDescription
{
	/**
	 * Returns module name
	 * @return string
	 */
	static function getModuleName(){ return "Runtime"; }
	
	
	/**
	 * Returns module name
	 * @return string
	 */
	static function getModuleVersion(){ return "1.0"; }
	
	
	/**
	 * Returns required modules
	 * @return Map<string>
	 */
	static function requiredModules(){ return null; }
	
	
	/**
	 * Returns enities
	 */
	static function entities()
	{
		return new \Runtime\Vector(
			new \Runtime\Entity\Provider("hash", "Runtime.Providers.GlobalHash"),
			new \Runtime\Entity\Provider("output", "Runtime.Providers.OutputProvider"),
			new \Runtime\Entity\Provider("hook", "Runtime.Providers.HookProvider"),
			new \Runtime\Entity\Provider("render", "Runtime.Providers.RenderContent"),
		);
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
	}
	static function getClassName(){ return "Runtime.ModuleDescription"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}