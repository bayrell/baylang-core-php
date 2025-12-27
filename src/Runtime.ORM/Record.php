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

use Runtime\BaseObject;
use Runtime\ORM\Provider;


class Record extends \Runtime\BaseObject
{
	var $_old_data;
	var $_new_data;
	var $_relation;
	
	
	/**
	 * Create new Record
	 */
	function __construct($data, $relation)
	{
		parent::__construct();
		$this->_relation = $relation;
		$this->_initData($data);
	}
	
	
	/**
	 * Returns relation
	 */
	function relation(){ return $this->_relation; }
	
	
	/**
	 * Convert to Dict
	 */
	function all(){ return $this->_new_data->copy(); }
	function old(){ return $this->_old_data->copy(); }
	function getData(){ return $this->_new_data->copy(); }
	function intersect($fields = null){ return $this->_new_data->intersect($fields); }
	
	
	/**
	 * Returns primary key
	 */
	function getPrimaryKey()
	{
		return $this->relation->getPrimaryKey($this->_new_data);
	}
	
	
	/**
	 * Returns value
	 */
	function get($name, $def_value = null){ return $this->_new_data->get($name, $def_value); }
	
	
	/**
	 * Set new value
	 */
	function set($name, $value)
	{
		$this->_new_data->set($name, $value);
	}
	
	
	/**
	 * Assign data
	 */
	function assign($data)
	{
		$keys = \Runtime\rtl::list($data->keys());
		for ($i = 0; $i < $keys->count(); $i++)
		{
			$key = $keys->get($i);
			$this->set($key, $data->get($key));
		}
	}
	function setData($item){ return $this->assign($item); }
	
	
	/**
	 * Get updated data
	 */
	function getUpdatedData()
	{
		if ($this->_new_data == null) return new \Runtime\Map();
		$res = new \Runtime\Map();
		$new_data_keys = \Runtime\rtl::list($this->_new_data->keys());
		for ($i = 0; $i < $new_data_keys->count(); $i++)
		{
			$field_name = $new_data_keys[$i];
			$new_value = $this->_new_data->get($field_name);
			if ($this->_old_data == null)
			{
				$res->set($field_name, $new_value);
			}
			else
			{
				if (!$this->_old_data->has($field_name))
				{
					$res->set($field_name, $new_value);
				}
				else
				{
					$old_value = $this->_old_data->get($field_name);
					if ($new_value != $old_value)
					{
						$res->set($field_name, $new_value);
					}
				}
			}
		}
		return $res;
	}
	
	
	/**
	 * Init data
	 */
	function _initData($data = null)
	{
		$this->_old_data = $data != null ? new \Runtime\Map($data) : null;
		$this->_new_data = $data != null ? new \Runtime\Map($data) : new \Runtime\Map();
	}
	
	
	/**
	 * Returns true if object is new
	 */
	function isNew()
	{
		return $this->_old_data ? false : true;
	}
	
	
	/**
	 * Returns true if data has loaded from database
	 */
	function isUpdate()
	{
		return $this->_old_data ? true : false;
	}
	
	
	/**
	 * Returns true if model is changed
	 */
	function isChanged()
	{
		$d1 = $this->_old_data;
		$d2 = $this->_new_data;
		if ($d1 == null) return true;
		if ($d2 == null) return true;
		$d1_keys = $d1->keys();
		$d2_keys = $d2->keys();
		for ($i = 0; $i < $d1_keys->count(); $i++)
		{
			$key1 = $d1_keys->get($i);
			if (!$d2->has($key1))
			{
				return true;
			}
			$value1 = $d1->get($key1);
			$value2 = $d2->get($key1);
			if ($value1 != $value2)
			{
				return true;
			}
		}
		for ($i = 0; $i < $d2_keys->count(); $i++)
		{
			$key2 = $d2_keys->get($i);
			if (!$d1->has($key2))
			{
				return true;
			}
		}
		return false;
	}
	
	
	/**
	 * Save object
	 */
	function save()
	{
		$this->_relation->save($this);
	}
	
	
	/**
	 * Delete object
	 */
	function delete()
	{
		$this->_relation->delete($this);
	}
	
	
	/**
	 * Refresh object
	 */
	function refresh()
	{
		$this->_relation->refresh($this);
	}
	
	
	/**
	 * Returns value
	 */
	function __get($key)
	{
		return $this->get($key);
	}
	
	
	/**
	 * Set value
	 */
	function __set($key, $value)
	{
		return $this->set($key, $value);
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->_old_data = null;
		$this->_new_data = new \Runtime\Map();
		$this->_relation = null;
	}
	static function getClassName(){ return "Runtime.ORM.Record"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}