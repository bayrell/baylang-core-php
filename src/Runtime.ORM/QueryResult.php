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

use Runtime\ORM\Query;

class QueryResult extends \Runtime\Vector
{
	var $q;
	var $rows;
	
	
	/**
	 * Returns pages
	 */
	function getPages(){ return $this->q ? $this->q->getPages($this->rows) : 0; }
	
	
	/**
	 * Returns page
	 */
	function getPage(){ return $this->q ? $this->q->getPage() : 0; }
	
	
	/**
	 * Returns count
	 */
	function getCount(){ return $this->rows; }
	
	
	/**
	 * Returns limit
	 */
	function getLimit(){ return $this->q ? $this->q->_limit : 1; }
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->q = null;
		$this->rows = 0;
	}
	static function getClassName(){ return "Runtime.ORM.QueryResult"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}