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
namespace Runtime\Widget\Dialog;

use Runtime\BaseModel;
use Runtime\Widget\Dialog\DialogMessage;


class DialogModel extends \Runtime\BaseModel
{
	var $is_open;
	
	
	/**
	 * Show dialog
	 */
	function show()
	{
		$this->is_open = true;
	}
	
	
	/**
	 * Hide dialog
	 */
	function hide()
	{
		$this->is_open = false;
		$this->listener->emit(new \Runtime\Widget\Dialog\DialogMessage(new \Runtime\Map([
			"name" => "hide",
		])));
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->is_open = false;
	}
	static function getClassName(){ return "Runtime.Widget.Dialog.DialogModel"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}