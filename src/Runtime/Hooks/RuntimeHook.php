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
namespace Runtime\Hooks;

use Runtime\Hooks\BaseHook;

class RuntimeHook extends \Runtime\Hooks\BaseHook
{
	const INIT = "runtime::init";
	const START = "runtime::start";
	const LAUNCHED = "runtime::launched";
	const RUN = "runtime::run";
	const ENV = "runtime::env";
	const MOUNT = "runtime::mount";
	const ASSETS = "runtime::assets";
	const COMPONENTS = "runtime::components";
	const LAYOUT_HEADER = "runtime::header";
	const LAYOUT_FOOTER = "runtime::footer";
	const LAYOUT_NAME = "runtime::layout_name";
	const CREATE_CONTAINER = "runtime::create_container";
	const CREATE_CONTAINER_DATA = "runtime::create_container_data";
	const CREATE_LAYOUT = "runtime::create_layout";
	const CHANGE_LAYOUT = "runtime::change_layout";
	const TITLE = "runtime::title";
	
	
	/**
	 * Register hooks
	 */
	function register_hooks()
	{
		parent::register_hooks();
		$this->provider->setAsync(new \Runtime\Vector(
			static::INIT,
			static::START,
			static::LAUNCHED,
			static::RUN,
		));
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
	}
	static function getClassName(){ return "Runtime.Hooks.RuntimeHook"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}