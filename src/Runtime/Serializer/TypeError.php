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
namespace Runtime\Serializer;

use Runtime\BaseObject;

class TypeError extends \Runtime\BaseObject
{
	var $path;
	var $message;
	
	
	/**
	 * Create type error
	 */
	function __construct($message)
	{
		parent::__construct();
		$this->message = $message;
	}
	
	
	/**
	 * Returns error message
	 */
	function getMessage()
	{
		$path = $this->path->slice()->reverse();
		$field_name = $path->first();
		if ($path->count() > 1) $field_name .= "[" . \Runtime\rs::join("][", $path) . "]";
		return $field_name . ": " . $this->message;
	}
	
	
	/**
	 * Returns messages;
	 */
	static function getMessages($errors){ return $errors->map(function ($error){ return $error->getMessage(); }); }
	
	
	
	/**
	 * Returns field name
	 */
	function getFieldName(){ return \Runtime\rs::join(".", $this->path->slice()->reverse()); }
	
	
	/**
	 * Add key to field name
	 */
	function addKey($key)
	{
		$this->path->push($key);
	}
	
	
	/**
	 * Convert errors to Map
	 */
	static function getMap($errors)
	{
		$result = new \Runtime\Map();
		for ($i = 0; $i < $errors->count(); $i++)
		{
			$item = $errors->get($i);
			$key = $item->getFieldName();
			if (!$result->has($key)) $result->set($key, new \Runtime\Vector());
			$arr = $result->get($key);
			$arr->push($item->message);
		}
		return $result;
	}
	
	
	/**
	 * Add field to errors
	 */
	static function addFieldErrors($errors, $key)
	{
		for ($i = 0; $i < $errors->count(); $i++)
		{
			$item = $errors->get($i);
			if (\Runtime\rtl::isString($item))
			{
				$item = new \Runtime\Serializer\TypeError($item);
				$errors->set($i, $item);
			}
			$item->addKey($key);
		}
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->path = new \Runtime\Vector();
		$this->message = "";
	}
	static function getClassName(){ return "Runtime.Serializer.TypeError"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}