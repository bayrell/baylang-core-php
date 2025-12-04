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
namespace Runtime\Widget;

use Runtime\Hooks\RuntimeHook;
use Runtime\Widget\CSS;


class AppHook extends \Runtime\Hooks\RuntimeHook
{
	/**
	 * Register hooks
	 */
	function register_hooks()
	{
		$this->register(static::COMPONENTS, "components", 50);
	}
	
	
	/**
	 * Components
	 */
	function components($params)
	{
		$components = new \Runtime\Vector(
			"Runtime.Widget.CSS",
		);
		$components->appendItems($params->get("components"));
		$params->set("components", $components);
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
	}
	static function getClassName(){ return "Runtime.Widget.AppHook"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}