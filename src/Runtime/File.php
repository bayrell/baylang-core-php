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
namespace Runtime;

use Runtime\BaseObject;

class File extends \Runtime\BaseObject
{
	var $name;
	var $path;
	var $size;
	
	
	/**
	 * Constructor
	 */
	function __construct($params)
	{
		parent::__construct();
		$this->_assign_values($params);
	}
	
	
	/**
	 * Returns file name
	 */
	function getName(){ return $this->name; }
	
	
	/**
	 * Returns file path
	 */
	function getPath(){ return $this->path; }
	
	
	/**
	 * Returns file size
	 */
	function getSize(){ return $this->size; }
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->name = "";
		$this->path = "";
		$this->size = 0;
	}
	static function getClassName(){ return "Runtime.File"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}