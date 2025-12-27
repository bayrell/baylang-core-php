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
namespace Runtime\ORM\Annotations;

use Runtime\DateTime;
use Runtime\Serializer\DateTimeType as RuntimeRule;
use Runtime\ORM\Annotations\BaseType;
use Runtime\ORM\Connection;


class DateTimeType extends \Runtime\ORM\Annotations\BaseType
{
	var $tz;
	var $autocreate;
	var $autoupdate;
	
	
	/**
	 * Returns rule
	 */
	function getRule(){ return new \Runtime\Serializer\DateTimeType(); }
	
	
	/**
	 * Prepare data before save
	 */
	function prepare($item, $is_update)
	{
		if (!$is_update && $this->autocreate)
		{
			if (!$item->has($this->name)) $item->set($this->name, \Runtime\DateTime::now());
		}
		if ($is_update && $this->autoupdate)
		{
			$item->set($this->name, \Runtime\DateTime::now());
		}
		return $item;
	}
	
	
	/**
	 * Process item from database
	 */
	function fromDatabase($conn, $item)
	{
		if (!$item->has($this->name)) return $item;
		$value = $item->get($this->name);
		$item->set($this->name, static::convertFromDatabase($value));
		$item = $conn->fromDatabase($this, $item, $this->name);
		return $item;
	}
	
	
	/**
	 * Process item to database
	 */
	function toDatabase($conn, $item)
	{
		$value = $item->get($this->name);
		if ($value == null) return $item;
		$item->set($this->name, static::convertToDatabase($value));
		$item = $conn->toDatabase($this, $item, $this->name);
		return $item;
	}
	
	
	/**
	 * Convert from db time
	 */
	static function convertFromDatabase($value)
	{
		if ($value == null) return null;
		return \Runtime\DateTime::fromString($value);
	}
	
	
	/**
	 * Convert to db time
	 */
	static function convertToDatabase($value)
	{
		if (!($value instanceof \Runtime\DateTime)) return null;
		return $value->setOffset(0)->format();
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->tz = "UTC";
		$this->autocreate = false;
		$this->autoupdate = false;
	}
	static function getClassName(){ return "Runtime.ORM.Annotations.DateTimeType"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}