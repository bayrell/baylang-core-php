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

use Runtime\Web\RenderListModel;
use Runtime\Widget\Pagination;
use Runtime\Widget\RowButtons;
use Runtime\Widget\Table\TableModel;


class Table extends \Runtime\Component
{
	function renderHeader()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		$model = $this->model;
		$fields = $model->fields;
		if ($fields)
		{
			/* Element tr */
			$__v0 = $__v->element("tr", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("widget_table__row_header", $componentHash))])));
			
			for ($i = 0; $i < $fields->count(); $i++)
			{
				$field = $fields[$i];
				$field_name = $field->get("name");
				
				/* Element th */
				$__v1 = $__v0->element("th", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("widget_table__th widget_table__th--" . $field_name, $componentHash))])));
				$__v1->push($field->get("label", ""));
			}
		}
		
		return $__v;
	}
	function renderField($item, $row_number, $field)
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		$model = $this->model;
		$storage = $model->storage;
		$field_name = $field->get("name");
		$field_calculate = $field->get("calculate", null);
		$field_component = $field->get("component", null);
		$field_model = $field->get("model", null);
		$value = "";
		$data = new \Runtime\Map([
			"item" => $item,
			"field_name" => $field_name,
			"row_number" => $row_number,
			"table" => $this->model,
		]);
		if ($field_calculate)
		{
			$value = \Runtime\rtl::apply($field_calculate, new \Runtime\Vector($data));
		}
		else
		{
			$value = $this->model->getItemValue($item, $field_name);
		}
		
		$_ = $data->set("value", $value);
		
		/* Element td */
		$__v0 = $__v->element("td", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("widget_table__td widget_table__td--" . $field_name, $componentHash))])));
		
		if ($field_name == "row_number")
		{
			$__v0->push($this->model->limit * $this->model->page + $row_number + 1);
		}
		else if ($field_component != null)
		{
			$props = $field->get("props", new \Runtime\Map());
			
			/* Element $field_component */
			$__v0->element($field_component, (new \Runtime\Map(["value" => $value, "data" => $data]))->concat($props));
		}
		else if ($field_model != null)
		{
			$__v0->push($this->renderWidget($field_model, new \Runtime\Map([
				"value" => $value,
				"data" => $data,
			])));
		}
		else
		{
			$__v0->push($value);
		}
		
		return $__v;
	}
	function renderRow($item, $row_number)
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		$model = $this->model;
		$fields = $model->fields;
		
		/* Element tr */
		$__v0 = $__v->element("tr", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("widget_table__tr", $this->isRowSelected($row_number) ? "selected" : "", $componentHash)), "data-row" => $row_number])));
		
		if ($fields)
		{
			for ($i = 0; $i < $fields->count(); $i++)
			{
				$field = $fields[$i];
				$__v0->push($this->renderField($item, $row_number, $field));
			}
		}
		
		return $__v;
	}
	function renderPagination()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		$fields = $this->model->fields;
		if ($fields && $this->model->pages > 1)
		{
			$props = $this->model->pagination_props;
			$pagination_class_name = $this->model->pagination_class_name;
			
			/* Element tr */
			$__v0 = $__v->element("tr");
			
			/* Element td */
			$__v1 = $__v0->element("td", (new \Runtime\Map(["colspan" => $fields->count()])));
			
			/* Element $pagination_class_name */
			$__v1->element($pagination_class_name, (new \Runtime\Map(["page" => $this->model->page + 1, "pages" => $this->model->pages]))->concat($props));
		}
		
		return $__v;
	}
	function renderBody()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		if ($this->model && $this->model->storage)
		{
			$items = $this->model->storage->getItems();
			if ($items)
			{
				for ($i = 0; $i < $items->count(); $i++)
				{
					$item = $items->get($i);
					$__v->push($this->renderRow($item, $i));
				}
			}
			$__v->push($this->renderPagination());
		}
		
		return $__v;
	}
	function renderTopButtons()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		$__v->push($this->renderWidget($this->model->top_buttons));
		
		return $__v;
	}
	function renderTable()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		/* Element table */
		$__v0 = $__v->element("table", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("widget_table__table", $componentHash))])));
		
		/* Element tbody */
		$__v1 = $__v0->element("tbody");
		$__v1->push($this->renderHeader());
		$__v1->push($this->renderBody());
		
		return $__v;
	}
	function renderWidgets()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		$__v->push($this->renderWidget($this->model->render_list));
		
		return $__v;
	}
	function render()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		$__v->is_render = true;
		
		/* Element div */
		$__v0 = $__v->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("widget_table", $this->class, static::mergeStyles("widget_table", $this->model->styles), $componentHash))])));
		$__v0->push($this->renderTopButtons());
		$__v0->push($this->renderTable());
		$__v0->push($this->renderWidgets());
		
		return $__v;
	}
	/**
	 * Returns true if row selected
	 */
	function isRowSelected($row_number){ return $this->model->row_selected == $row_number; }
	/**
	 * OnRowClick
	 */
	function onRowClick($row_number)
	{
		$this->model->onRowClick($row_number);
	}
	/**
	 * On change page
	 */
	function onChangePage($page){}
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
	}
	static function getComponentStyle(){ return ".widget_table__table.h-6433{width: auto;border-collapse: collapse;vertical-align: top;background-color: var(--widget-color-table-background)}.widget_table__th.h-6433{text-align: center}.widget_table__th.h-6433, .widget_table__td.h-6433{vertical-align: middle;padding: var(--widget-space)}.widget_table__tr.selected.h-6433 td{background-color: var(--widget-color-selected);color: var(--widget-color-selected-text)}.widget_table--border.h-6433 .widget_table__table{border-color: var(--widget-color-border);border-width: var(--widget-border-width);border-style: solid}.widget_table--border.h-6433 .widget_table__th, .widget_table--border.h-6433 .widget_table__td{border-bottom-color: var(--widget-color-border);border-bottom-width: var(--widget-border-width);border-bottom-style: solid}.widget_table--stretch.h-6433 .widget_table__table{width: 100%}"; }
	static function getRequiredComponents(){ return new \Runtime\Vector("Runtime.Widget.Pagination"); }
	static function getClassName(){ return "Runtime.Widget.Table.Table"; }
}