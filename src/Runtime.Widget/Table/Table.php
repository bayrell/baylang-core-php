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
namespace Runtime\Widget\Table;


class Table extends \Runtime\Component
{
	function renderHeader()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		$__v->push($this->renderSlot("header"));
		
		return $__v;
	}
	function renderRow($item, $row_number)
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		$__v->push($this->renderSlot("row", new \Runtime\Vector($item, $row_number)));
		
		return $__v;
	}
	function renderTable()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		/* Element table */
		$__v0 = $__v->element("table");
		
		/* Element tbody */
		$__v1 = $__v0->element("tbody");
		
		/* Element tr */
		$__v2 = $__v1->element("tr", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("table__header", $componentHash))])));
		$__v2->push($this->renderHeader());
		
		if ($this->model->items)
		{
			for ($i = 0; $i < $this->model->items->count(); $i++)
			{
				$item = $this->model->items->get($i);
				
				/* Element tr */
				$__v3 = $__v1->element("tr", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("table__row", $componentHash))])));
				$__v3->push($this->renderRow($item, $i));
			}
		}
		
		return $__v;
	}
	function render()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		$__v->is_render = true;
		
		/* Element div */
		$__v0 = $__v->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("table", $componentHash))])));
		$__v0->push($this->renderTable());
		
		return $__v;
	}
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
	}
	static function getComponentStyle(){ return ".table.h-6433 table{border-collapse: collapse;background-color: var(--color-background);border: 1px var(--color-border) solid}.table.h-6433 th, .table.h-6433 td{padding: var(--space);border-bottom: 1px var(--color-border) solid}.table.h-6433 th{font-weight: bold}.table__row.h-6433:nth-child(even){background-color: var(--color-surface)}.table__row.h-6433:last-child{border-bottom-width: 0px}"; }
	static function getRequiredComponents(){ return new \Runtime\Vector(); }
	static function getClassName(){ return "Runtime.Widget.Table.Table"; }
}