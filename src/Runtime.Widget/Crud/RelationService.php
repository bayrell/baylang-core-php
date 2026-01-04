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

use Runtime\Callback;
use Runtime\ORM\Connection;
use Runtime\ORM\Provider;
use Runtime\ORM\Query;
use Runtime\ORM\QueryField;
use Runtime\ORM\QueryFilter;
use Runtime\ORM\QueryResult;
use Runtime\ORM\Relation;
use Runtime\ORM\Annotations\BaseType;
use Runtime\Widget\Crud\CrudService;


class RelationService extends \Runtime\Widget\Crud\CrudService
{
	var $connection;
	var $provider;
	var $item;
	
	
	/**
	 * Returns relation name
	 */
	function getRelationName(){ return ""; }
	
	
	/**
	 * Returns table name
	 */
	function getTableName()
	{
		$class_name = $this->getRelationName();
		$table_name = (new \Runtime\Callback($class_name, "getTableName"))->apply();
		return $table_name;
	}
	
	
	/**
	 * Returns connection
	 */
	function getConnection(){ return \Runtime\ORM\Connection::get(); }
	
	
	/**
	 * Create search query
	 */
	function createSearchQuery()
	{
		return (new \Runtime\ORM\Query())->select()->relation($this->getRelationName())->addField(new \Runtime\ORM\QueryField($this->getTableName(), "*"));
	}
	
	
	
	/**
	 * Build search query
	 */
	function buildSearchQuery($q){}
	
	
	/**
	 * New item
	 */
	function newItem(){ return \Runtime\ORM\Relation::newInstance($this->getRelationName()); }
	
	
	/**
	 * Find item
	 */
	function findItem($pk)
	{
		if ($pk == null) return null;
		$class_name = $this->getRelationName();
		/* Create query */
		$q = $this->createSearchQuery()->limit(1);
		/* Get primary key */
		$filter = $this->provider->getPrimaryFilter($q->_table_name, $pk);
		$q->setFilter($filter);
		/* Extend query */
		$this->buildSearchQuery($q);
		/* Find relation */
		$item = $this->connection->findRelation($q);
		return $item;
	}
	
	
	/**
	 * Load items
	 */
	function loadItems()
	{
		$this->page = $this->search_params->get("page", 0);
		$this->limit = $this->search_params->get("limit", 10);
		/* Create query */
		$q = $this->createSearchQuery();
		/* Set page */
		$q->limit($this->limit)->page($this->page);
		/* Set found_rows */
		$q->calcFoundRows(true);
		$found_rows = $this->search_params->get("found_rows", true);
		if ($q->_calc_found_rows && !$found_rows) $q->calcFoundRows(false);
		/* Extend query */
		$this->buildSearchQuery($q);
		/* Get query result */
		$conn = \Runtime\ORM\Connection::get();
		$this->items = $conn->fetchAll($q);
		/* Set pages */
		$this->page = $this->items->getPage();
		$this->pages = $this->items->getPages();
		$this->limit = $this->items->q->_limit;
	}
	
	
	/**
	 * Returns primary key
	 */
	function getPrimaryKey($item){ return $item->getPrimaryKey(); }
	
	
	/**
	 * Save item
	 */
	function saveItem()
	{
		$this->item->save($this->connection);
		/* Refresh item */
		$this->item = $this->findItem($this->getPrimaryKey($this->item));
	}
	
	
	/**
	 * Delete item
	 */
	function deleteItem()
	{
		$this->item->delete($this->connection);
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->connection = $this->getConnection();
		$this->provider = \Runtime\rtl::getContext()->provider("Runtime.ORM.Provider");
		$this->item = null;
	}
	static function getClassName(){ return "Runtime.Widget.Crud.RelationService"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}