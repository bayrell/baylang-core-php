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

use Runtime\Widget\Form\Form;
use Runtime\Widget\Form\FormRow;
use Runtime\Widget\Table\Table;
use Runtime\Widget\Table\TableDialog;


class TableWrap extends \Runtime\Component
{
	function renderTopButtons()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		if ($this->slot("top_buttons"))
		{
			$__v->push($this->renderSlot("top_buttons"));
		}
		
		return $__v;
	}
	function renderDialog()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		if ($this->dialog == "true")
		{
			/* Element Runtime.Widget.Table.TableDialog */
			$__v0 = $__v->element("Runtime.Widget.Table.TableDialog", (new \Runtime\Map(["model" => $this->model->dialog, "manager" => $this->model])));
			
			/* Slot save */
			$__v0->slot("save", function ()
			{
				$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
				$__v = new \Runtime\VirtualDom($this);
				
				/* Element Runtime.Widget.Form.Form */
				$__v->element("Runtime.Widget.Form.Form", (new \Runtime\Map(["fields" => $this->form_fields, "model" => $this->model->form])));
				
				return $__v;
			});
		}
		
		return $__v;
	}
	function renderTableValue($item, $field, $row_number)
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		$field_name = $field->get("name");
		if ($field_name == "row_number")
		{
			$__v->push($row_number + $this->row_offset + 1);
		}
		else if ($field->has("component"))
		{
			$component = $field->get("component");
			$props = $field->get("props", new \Runtime\Map());
			
			/* Element $component */
			$__v->element($component, (new \Runtime\Map(["name" => $field_name, "value" => $item->get($field_name)]))->concat($props));
		}
		else if ($field->has("model"))
		{
			$__v->push($this->renderWidget($field->get("model")));
		}
		else if ($field->has("slot"))
		{
			$__v->push($this->renderSlot($field->get("slot"), new \Runtime\Vector($item, $field, $row_number)));
		}
		else if ($field->has("value"))
		{
			$__v->push(\Runtime\rtl::apply($field->get("value"), new \Runtime\Vector($item, $field, $row_number)));
		}
		else
		{
			$__v->push($item->get($field_name));
		}
		
		return $__v;
	}
	function render()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		$__v->is_render = true;
		
		/* Element div */
		$__v0 = $__v->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("table_wrap", $componentHash))])));
		$__v0->push($this->renderTopButtons());
		
		/* Element Runtime.Widget.Table.Table */
		$__v1 = $__v0->element("Runtime.Widget.Table.Table", (new \Runtime\Map(["model" => $this->model->table])));
		
		/* Slot header */
		$__v1->slot("header", function ()
		{
			$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
			$__v = new \Runtime\VirtualDom($this);
			
			for ($i = 0; $i < $this->table_fields->count(); $i++)
			{
				$field = $this->table_fields->get($i);
				
				/* Element th */
				$__v0 = $__v->element("th");
				$__v0->push($field->get("label"));
			}
			
			return $__v;
		});
		
		/* Slot row */
		$__v1->slot("row", function ($item, $row_number)
		{
			$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
			$__v = new \Runtime\VirtualDom($this);
			
			for ($i = 0; $i < $this->table_fields->count(); $i++)
			{
				$field = $this->table_fields->get($i);
				
				/* Element td */
				$__v0 = $__v->element("td");
				$__v0->push($this->renderTableValue($item, $field, $row_number));
			}
			
			return $__v;
		});
		$__v0->push($this->renderDialog());
		
		return $__v;
	}
	var $table_fields;
	var $form_fields;
	var $dialog;
	/**
	 * Returns offset
	 */
	function row_offset()
	{
		if (!$this->model->loader) return 0;
		return $this->model->loader->page * $this->model->loader->limit;
	}
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->table_fields = new \Runtime\Vector();
		$this->form_fields = new \Runtime\Vector();
		$this->dialog = "true";
	}
	static function getComponentStyle(){ return ".table_wrap.h-714c > .row_buttons{margin-bottom: calc(var(--space) * 2)}"; }
	static function getRequiredComponents(){ return new \Runtime\Vector("Runtime.Widget.Form.Form", "Runtime.Widget.Form.FormRow", "Runtime.Widget.Table.Table", "Runtime.Widget.Table.TableDialog"); }
	static function getClassName(){ return "Runtime.Widget.Table.TableWrap"; }
}