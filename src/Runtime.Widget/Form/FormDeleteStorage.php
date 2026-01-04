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

use Runtime\Web\ApiResult;
use Runtime\Widget\Form\FormModel;
use Runtime\Widget\Form\FormSaveStorage;
use Runtime\Widget\Form\FormStorageInterface;


class FormDeleteStorage extends \Runtime\Widget\Form\FormSaveStorage
{
	var $method_name;
	
	
	/**
	 * Submit form
	 */
	function submit()
	{
		$post_data = new \Runtime\Map([
			"pk" => $this->form->pk,
		]);
		$post_data = $this->form->mergePostData($post_data, "submit");
		$res = $this->form->layout->callApi(new \Runtime\Map([
			"api_name" => $this->getApiName(),
			"method_name" => $this->method_name,
			"data" => $post_data,
		]));
		return $res;
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->method_name = "actionDelete";
	}
	static function getClassName(){ return "Runtime.Widget.Form.FormDeleteStorage"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}