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
namespace Runtime\Exceptions;

use Runtime\RuntimeConstant;
use Runtime\Exceptions\RuntimeException;


class ItemNotFound extends \Runtime\Exceptions\RuntimeException
{
	function __construct($name = "", $object = "Item", $prev = null)
	{
		$message = "";
		if ($name != "")
		{
			$message = \Runtime\rs::format("%object% '%name%' not found", new \Runtime\Map(["name" => $name, "object" => $object]));
		}
		else
		{
			$message = \Runtime\rs::format("%object% not found", new \Runtime\Map(["object" => $object]));
		}
		parent::__construct($message, \Runtime\rtl::ERROR_ITEM_NOT_FOUND, $prev);
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
	}
	static function getClassName(){ return "Runtime.Exceptions.ItemNotFound"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}