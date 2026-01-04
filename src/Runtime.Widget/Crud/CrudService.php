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
namespace Runtime\Widget\Crud;

use Runtime\BaseObject;
use Runtime\Exceptions\ApiError;
use Runtime\Exceptions\ItemNotFound;
use Runtime\Web\ApiResult;
use Runtime\Widget\Crud\RulesManager;


class CrudService extends \Runtime\BaseObject
{
	/* Rules */
	var $rules;
	
	/* Item */
	var $pk;
	var $data;
	var $item;
	var $is_create;
	
	/* Search */
	var $search_params;
	var $items;
	var $page;
	var $pages;
	var $limit;
	
	
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();
		$this->initRules();
	}
	
	
	/**
	 * Returns true if create
	 */
	function isCreate(){ return $this->is_create; }
	
	
	/**
	 * Returns true if update
	 */
	function isUpdate(){ return !$this->is_create; }
	
	
	/**
	 * Returns true if search
	 */
	function isSearch(){ return $this->search_params != null; }
	
	
	/**
	 * Set create
	 */
	function setCreate($value)
	{
		$this->is_create = $value;
		$this->rules->setCreate($value);
	}
	
	
	/**
	 * New item
	 */
	function newItem(){ return null; }
	
	
	/**
	 * Find item
	 */
	function findItem($pk){ return null; }
	
	
	/**
	 * Init rules
	 */
	function initRules(){}
	
	
	/**
	 * Returns save fields
	 */
	function getSaveFields()
	{
		return new \Runtime\Vector(
		);
	}
	
	
	/**
	 * Returns primary key
	 */
	function getPrimaryKey($item){ return null; }
	
	
	/**
	 * Set new item
	 */
	function setItem($item, $is_new = false)
	{
		$this->item = $item;
		$this->pk = $item ? $this->getPrimaryKey($this->item) : null;
		$this->setCreate($is_new);
	}
	
	
	/**
	 * Set item
	 */
	function setItemValue($item, $key, $value)
	{
		$item->set($key, $value);
	}
	
	
	/**
	 * Set item data
	 */
	function setItemData($item, $data)
	{
		if (!$data) return;
		$keys = $this->getSaveFields();
		for ($i = 0; $i < $keys->count(); $i++)
		{
			$key = $keys->get($i);
			if (!$data->has($key)) continue;
			$value = $data->get($key);
			$this->setItemValue($item, $key, $value);
		}
	}
	
	
	/**
	 * Convert item
	 */
	function convertItem($item, $fields)
	{
		return $item->intersect($fields);
	}
	
	
	/**
	 * Load item
	 */
	function loadItem($pk, $create_instance = false)
	{
		if ($pk != null && $pk instanceof \Runtime\Dict)
		{
			$item = $this->findItem($pk);
			$this->setItem($item, false);
		}
		else
		{
			if ($create_instance)
			{
				$this->setItem($this->newItem(), true);
			}
		}
		/* Item not found */
		if ($this->item == null)
		{
			throw new \Runtime\Exceptions\ApiError(new \Runtime\Exceptions\ItemNotFound());
		}
	}
	
	
	/**
	 * Validate data
	 */
	function validate()
	{
		$this->rules->validate($this->data);
	}
	
	
	/**
	 * Before search
	 */
	function onSearchBefore()
	{
		$this->rules->onSearchBefore($this);
	}
	
	
	/**
	 * After search
	 */
	function onSearchAfter()
	{
		$this->rules->onSearchAfter($this);
	}
	
	
	/**
	 * Load items
	 */
	function loadItems()
	{
		$this->items = new \Runtime\Vector();
		$this->page = 0;
		$this->pages = 0;
		$this->limit = 0;
	}
	
	
	/**
	 * Search items
	 */
	function search($params)
	{
		/* Set search params */
		$this->search_params = $params;
		/* Before search */
		$this->onSearchBefore();
		/* Load items */
		$this->loadItems();
		/* After search */
		$this->onSearchAfter();
	}
	
	
	/**
	 * Search item
	 */
	function searchItem($pk)
	{
		/* Before search */
		$this->onSearchBefore();
		/* Load item */
		$this->loadItem($pk, false);
		/* After search */
		$this->onSearchAfter();
	}
	
	
	/**
	 * Before save
	 */
	function onSaveBefore()
	{
		$this->rules->onSaveBefore($this);
	}
	
	
	/**
	 * After save
	 */
	function onSaveAfter()
	{
		$this->rules->onSaveAfter($this);
	}
	
	
	/**
	 * Save item
	 */
	function saveItem(){}
	
	
	/**
	 * Save
	 */
	function save($data)
	{
		if ($data == null || !($data instanceof \Runtime\Dict)) $data = new \Runtime\Map();
		/* Validate item */
		$this->data = $data->copy();
		$this->validate();
		if (!$this->rules->correct()) return false;
		/* Before save */
		$this->onSaveBefore();
		if (!$this->rules->correct()) return;
		/* Set item data */
		$this->setItemData($this->item, $this->data);
		/* Save item */
		$this->saveItem();
		/* After save */
		$this->onSaveAfter();
		return true;
	}
	
	
	/**
	 * Before delete
	 */
	function onDeleteBefore()
	{
		$this->rules->onDeleteBefore($this);
	}
	
	
	/**
	 * After delete
	 */
	function onDeleteAfter()
	{
		$this->rules->onDeleteAfter($this);
	}
	
	
	/**
	 * Delete item
	 */
	function deleteItem(){}
	
	
	/**
	 * Delete
	 */
	function delete()
	{
		/* Before delete */
		$this->onDeleteBefore();
		/* Delete item */
		$this->deleteItem();
		/* After delete */
		$this->onDeleteAfter();
		return true;
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->rules = new \Runtime\Widget\Crud\RulesManager();
		$this->pk = null;
		$this->data = null;
		$this->item = null;
		$this->is_create = false;
		$this->search_params = null;
		$this->items = null;
		$this->page = 0;
		$this->pages = 0;
		$this->limit = 0;
	}
	static function getClassName(){ return "Runtime.Widget.Crud.CrudService"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}