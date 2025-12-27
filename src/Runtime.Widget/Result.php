<?php
/*
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
namespace Runtime\Widget;


class Result extends \Runtime\Component
{
	function render()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		$__v->is_render = true;
		
		/* Element div */
		$__v0 = $__v->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("result", $this->class, $this->getErrorClass(), $componentHash))])));
		$__v0->push($this->model->message);
		
		return $__v;
	}
	/**
	 * Returns error class
	 */
	function getErrorClass()
	{
		if ($this->model->message == "") return "result--hide";
		if ($this->model->isSuccess()) return "result--success";
		if ($this->model->isError()) return "result--error";
		return "";
	}
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
	}
	static function getComponentStyle(){ return ".result--center.h-4c3d{text-align: center}.result--margin_top.h-4c3d, .result--margin_bottom.h-4c3d{margin-top: calc(var(--space) * 2)}.result--field.h-4c3d{margin-top: calc(var(--space) * 0.5)}.result--form.h-4c3d{text-align: center;margin-top: calc(var(--space) * 2);margin-bottom: calc(var(--space) * 2)}.result--form.h-4c3d:last-child{margin-bottom: 0}.result--success.h-4c3d{color: var(--color-success)}.result--error.h-4c3d{color: var(--color-danger)}.result--hide.h-4c3d{display: none}"; }
	static function getRequiredComponents(){ return new \Runtime\Vector(); }
	static function getClassName(){ return "Runtime.Widget.Result"; }
}