<?php
/*!
 *  BayLang Technology
 *
 *  (c) Copyright 2016-2024 "Ildar Bikmamatov" <support@bayrell.org>
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
namespace Runtime\ORM;

use Runtime\Hooks\BaseHook;

class DatabaseSchema extends \Runtime\Hooks\BaseHook
{
	const REGISTER = "runtime.orm.database::register";
	const SAVE_AFTER = "runtime.orm.database::save_after";
	const SAVE_BEFORE = "runtime.orm.database::save_before";
	
	
	/**
	 * Register hooks
	 */
	function register_hooks()
	{
		parent::register_hooks();
		$this->provider->setAsync(new \Runtime\Vector(
			static::REGISTER,
			static::SAVE_AFTER,
			static::SAVE_BEFORE,
		));
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
	}
	static function getClassName(){ return "Runtime.ORM.DatabaseSchema"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}