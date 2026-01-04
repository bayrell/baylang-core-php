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
namespace Runtime\Widget\Api;

use Runtime\Exceptions\ApiError;
use Runtime\Exceptions\FieldException;
use Runtime\Exceptions\RuntimeException;
use Runtime\Serializer\BaseType;
use Runtime\Serializer\IntegerType;
use Runtime\Serializer\MapType;
use Runtime\ORM\Connection;
use Runtime\ORM\Query;
use Runtime\ORM\QueryResult;
use Runtime\ORM\Record;
use Runtime\ORM\Relation;
use Runtime\Web\ApiRequest;
use Runtime\Web\ApiResult;
use Runtime\Web\BaseApi;
use Runtime\Widget\Api\Rules\BaseRule;


class SearchApi extends \Runtime\Web\BaseApi
{
	var $action;
	var $connection_name;
	var $connection;
	var $relation;
	var $items;
	var $primary_key;
	var $foreign_key;
	
	
	/**
	 * Returns relation name
	 */
	function getRelationName(){ return ""; }
	
	
	/**
	 * Constructor
	 */
	function __construct()
	{
		parent::__construct();
		$this->relation = \Runtime\rtl::newInstance($this->getRelationName());
	}
	
	
	/**
	 * Returns save rules
	 */
	function rules(){ return new \Runtime\Vector(); }
	
	
	/**
	 * Returns serialize rules for pk
	 */
	function getPrimaryRules($rules){}
	
	
	/**
	 * Returns item fields
	 */
	function getItemFields(){ return new \Runtime\Vector(); }
	
	
	/**
	 * Convert item
	 */
	function convertItem($item){ return $item; }
	
	
	/**
	 * Filter primary key
	 */
	function getPrimaryKey($pk)
	{
		$errors = new \Runtime\Vector();
		/* Get primary key rule */
		$rule = new \Runtime\Serializer\MapType();
		$this->getPrimaryRules($rule);
		if (!$rule)
		{
			throw new \Runtime\Exceptions\ApiError(new \Runtime\Exceptions\RuntimeException("Primary rule not found"));
		}
		/* Filter primary key */
		$pk = $rule->filter($pk, $errors, null);
		/* Check errors */
		if ($errors->count() > 0)
		{
			TypeError::addFieldErrors($errors, "pk");
			throw new \Runtime\Exceptions\ApiError(new \Runtime\Exceptions\FieldException(TypeError::getMap($errors)));
		}
		return $pk;
	}
	
	
	/**
	 * Set primary key
	 */
	function setPrimaryKey($pk)
	{
		if (!($pk instanceof \Runtime\Map))
		{
			throw new \Runtime\Exceptions\ApiError(new \Runtime\Exceptions\RuntimeException("Primary key not found"));
		}
		$this->primary_key = $this->getPrimaryKey($pk);
	}
	
	
	/**
	 * Set foreign key
	 */
	function setForeignKey($foreign_key)
	{
		$this->foreign_key = $foreign_key;
	}
	
	
	/**
	 * Returns max limit
	 */
	function getMaxLimit(){ return 100; }
	
	
	/**
	 * Returns limit
	 */
	function getLimit()
	{
		$limit = \Runtime\rtl::toInt($this->data->get("limit", 10));
		if (!\Runtime\rtl::isInteger($limit)) $limit = 0;
		$max_limit = $this->getMaxLimit();
		if ($limit > $max_limit && $max_limit >= 0) $limit = $max_limit;
		return $limit;
	}
	
	
	/**
	 * Returns page
	 */
	function getPage()
	{
		$page = \Runtime\rtl::toInt($this->data->get("page", 0));
		if (!\Runtime\rtl::isInteger($page)) $page = 0;
		return $page;
	}
	
	
	/**
	 * Build query
	 */
	function buildQuery($q)
	{
		if ($this->isItem())
		{
			$q->addFilter($this->relation->getPrimaryFilter($this->primary_key));
			$q->limit(1);
		}
	}
	
	
	/**
	 * Search items
	 */
	function search()
	{
		/* Build query */
		$q = $this->relation->select();
		$q->limit($this->getLimit());
		$q->page($this->getPage());
		$q->calcFoundRows();
		$this->buildQuery($q);
		/* Search before */
		$rules = $this->rules();
		for ($i = 0; $i < $rules->count(); $i++)
		{
			$rule = $rules->get($i);
			$rule->onSearchBefore($this, $q);
		}
		/* Search */
		$this->items = $this->relation->fetchAll($q);
		/* Search after */
		$rules = $this->rules();
		for ($i = 0; $i < $rules->count(); $i++)
		{
			$rule = $rules->get($i);
			$rule->onSearchAfter($this);
		}
	}
	
	
	/**
	 * Set result
	 */
	function setResult()
	{
		if ($this->isSearch() && $this->items)
		{
			$this->result->data->set("items", $this->items->map(function ($data)
			{
				$data = $this->convertItem($data);
				return $data->intersect($this->getItemFields());
			}));
			$this->result->data->set("count", $this->items->getCount());
			$this->result->data->set("limit", $this->items->getLimit());
			$this->result->data->set("page", $this->items->getPage());
			$this->result->data->set("pages", $this->items->getPages());
		}
		else if ($this->isItem() && $this->items)
		{
			$data = $this->items->get(0);
			if ($data)
			{
				$data = $this->convertItem($data);
				$data = $data->intersect($this->getItemFields());
			}
			$this->result->data->set("item", $data);
		}
	}
	
	
	/**
	 * Returns data rules
	 */
	function getDataRules($rules)
	{
		$rules->addType("page", new \Runtime\Serializer\IntegerType(new \Runtime\Map(["default" => 0])));
		$rules->addType("limit", new \Runtime\Serializer\IntegerType(new \Runtime\Map(["default" => 10])));
	}
	
	
	/**
	 * Returns action
	 */
	function isItem(){ return $this->action == "item"; }
	function isSearch(){ return $this->action == "search"; }
	
	
	/**
	 * Action search
	 */
	function actionSearch()
	{
		$this->setAction("search");
		/* Filter data */
		$this->filterData();
		/* Set foreign key */
		$this->setForeignKey($this->data->get("foreign_key"));
		/* Search */
		$this->search();
		$this->setResult();
		$this->success();
	}
	
	
	/**
	 * Action item
	 */
	function actionItem()
	{
		$this->setAction("item");
		/* Filter data */
		$this->filterData();
		/* Set primary key */
		$pk = $this->request->get("pk");
		$this->setPrimaryKey($pk);
		/* Set foreign key */
		$this->setForeignKey($this->data->get("foreign_key"));
		/* Search */
		$this->search();
		$this->setResult();
		$this->success();
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->action = "";
		$this->connection_name = "";
		$this->connection = null;
		$this->relation = null;
		$this->items = null;
		$this->primary_key = null;
		$this->foreign_key = null;
	}
	static function getClassName(){ return "Runtime.Widget.Api.SearchApi"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}