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
namespace Runtime\Widget\Form;

use Runtime\BaseObject;
use Runtime\Web\ApiResult;
use Runtime\Widget\Form\FormModel;
use Runtime\Widget\Form\FormStorageInterface;


class FormDeleteProxyStorage extends \Runtime\BaseObject implements \Runtime\Widget\Form\FormStorageInterface
{
	var $container;
	var $path;
	var $form;
	
	
	/**
	 * Set form
	 */
	function setForm($form)
	{
		$this->form = $form;
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
	 * Returns items
	 */
	function getItems()
	{
		return \Runtime\rtl::attr($this->container, $this->path);
	}
	
	
	/**
	 * Load form
	 */
	function load(){}
	
	
	/**
	 * Submit form
	 */
	function submit()
	{
		/* Delete item */
		if ($this->form->row_number >= 0)
		{
			$items = $this->getItems();
			$items->remove($this->form->row_number);
		}
		/* Success result */
		$res = new \Runtime\Web\ApiResult();
		$res->success();
		return $res;
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->form = null;
	}
	static function getClassName(){ return "Runtime.Widget.Form.FormDeleteProxyStorage"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}