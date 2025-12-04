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
namespace Runtime\Widget\Table;

use Runtime\BaseObject;
use Runtime\Web\ApiResult;
use Runtime\Widget\Table\TableModel;
use Runtime\Widget\Table\TableStorageInterface;


class TableStorage extends \Runtime\BaseObject implements \Runtime\Widget\Table\TableStorageInterface
{
	var $api_name;
	var $table;
	
	
	/**
	 * Returns api name
	 */
	function getApiName(){ return $this->api_name; }
	
	
	/**
	 * Returns items
	 */
	function getItems(){ return $this->table->items; }
	
	
	/**
	 * Set table
	 */
	function setTable($table)
	{
		$this->table = $table;
	}
	
	
	/**
	 * Constructor
	 */
	function __construct($params = null)
	{
		parent::__construct();
		$this->_assign_values($params);
	}
	
	
	/**
	 * Load form
	 */
	function load()
	{
		$post_data = new \Runtime\Map([
			"page" => $this->table->page,
			"limit" => $this->table->limit,
		]);
		$post_data = $this->table->mergePostData($post_data, "load");
		$res = $this->table->layout->callApi(new \Runtime\Map([
			"api_name" => $this->getApiName(),
			"method_name" => "actionSearch",
			"data" => $post_data,
		]));
		return $res;
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->api_name = "";
		$this->table = null;
	}
	static function getClassName(){ return "Runtime.Widget.Table.TableStorage"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}