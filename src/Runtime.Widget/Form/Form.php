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
namespace Runtime\Widget\Form;

use Runtime\Callback;
use Runtime\Web\ApiResult;
use Runtime\Web\Annotations\Param;
use Runtime\Widget\Button;
use Runtime\Widget\Input;
use Runtime\Widget\RowButtons;
use Runtime\Widget\RowButtonsModel;
use Runtime\Widget\TextArea;
use Runtime\Widget\WidgetResult;
use Runtime\Widget\WidgetResultModel;


class Form extends \Runtime\Component
{
	function renderContent()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		if ($this->model->form_content != "")
		{
			/* Element div */
			$__v0 = $__v->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("widget_form__content", $componentHash))])));
			$__v0->push($this->model->form_content);
		}
		else
		{
			$__v->push($this->renderSlot("content"));
		}
		
		return $__v;
	}
	function renderField($field)
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		$field_name = $field->get("name");
		$field_model = $field->get("model", null);
		$field_calculate = $field->get("calculate", null);
		$field_component = $field->get("component");
		$field_props = $field->get("props", new \Runtime\Map());
		$value = "";
		$data = new \Runtime\Map([
			"item" => $this->model->item,
			"field_name" => $field_name,
			"form" => $this->model,
		]);
		if ($field_calculate)
		{
			$value = \Runtime\rtl::apply($field_calculate, new \Runtime\Vector($data));
		}
		else
		{
			$value = $this->model->getItemValue($field_name);
		}
		
		$_ = $data->set("value", $value);
		if ($field_component != null)
		{
			/* Element $field_component */
			$__v->element($field_component, (new \Runtime\Map(["value" => $value, "name" => $field_name, "data" => $data]))->concat($field_props));
		}
		else
		{
			$__v->push($this->renderWidget($field_model, $field_props->concat(new \Runtime\Map([
				"name" => $field_name,
				"value" => $value,
				"data" => $data,
				"ref" => "field_" . $field_name,
				"onValueChange" => function ($message) use (&$field_name)
				{
					$this->model->onFieldChange($field_name, $message->value);
				},
			]))));
		}
		
		return $__v;
	}
	function renderFieldResult($field)
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		$field_name = $field->get("name");
		$field_error = $this->model->getFieldResult($field_name);
		if ($field_error->count() == 0)
		{
			/* Element div */
			$__v->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("widget_form__field_error widget_form__field_error--hide", $componentHash)), "data-name" => $field_name])));
		}
		else
		{
			/* Element div */
			$__v0 = $__v->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("widget_form__field_error", $componentHash)), "data-name" => $field_name])));
			
			for ($i = 0; $i < $field_error->count(); $i++)
			{
				/* Element div */
				$__v1 = $__v0->element("div");
				$__v1->push($field_error->get($i));
			}
		}
		
		return $__v;
	}
	function renderFieldLabel($field)
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		/* Element span */
		$__v0 = $__v->element("span");
		$__v0->push($field->get("label"));
		
		return $__v;
	}
	function renderFieldButtons($field)
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		if ($field->has("buttons"))
		{
			$buttons = $field->get("buttons");
			if ($buttons instanceof \Runtime\Widget\RowButtonsModel)
			{
				$__v->push($this->renderWidget($buttons));
			}
			else
			{
				/* Element div */
				$__v0 = $__v->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("widget_form__field_buttons", $componentHash))])));
				
				for ($i = 0; $i < $buttons->count(); $i++)
				{
					$settings = $buttons->get($i);
					$props = $settings->get("props");
					$content = $settings->get("content");
					
					/* Element Runtime.Widget.Button */
					$__v0->element("Runtime.Widget.Button", (new \Runtime\Map([]))->concat($props));
				}
			}
		}
		
		return $__v;
	}
	function renderFieldRow($field)
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		$is_show = true;
		$field_name = $field->get("name");
		$field_show = $field->get("show", null);
		if ($field_show)
		{
			$data = new \Runtime\Map([
				"item" => $this->model->item,
				"field_name" => $field_name,
				"form" => $this->model,
			]);
			$is_show = \Runtime\rtl::apply($field_show, new \Runtime\Vector($data));
		}
		
		if ($is_show)
		{
			$show_label = $this->model->field_settings->get("show_label", true);
			
			/* Element div */
			$__v0 = $__v->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("widget_form__field_row", $componentHash)), "data-name" => $field_name])));
			
			if ($field->has("label") && ($show_label === true || $show_label == "true"))
			{
				/* Element div */
				$__v1 = $__v0->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("widget_form__field_label", $componentHash))])));
				$__v1->push($this->renderFieldLabel($field));
				$__v1->push($this->renderFieldButtons($field));
			}
			$__v0->push($this->renderField($field));
			$__v0->push($this->renderFieldResult($field));
		}
		
		return $__v;
	}
	function renderFields()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		/* Element div */
		$__v0 = $__v->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("widget_form__fields", $componentHash))])));
		
		if ($this->model)
		{
			for ($i = 0; $i < $this->model->fields->count(); $i++)
			{
				$__v0->push($this->renderFieldRow($this->model->fields->get($i)));
			}
		}
		
		return $__v;
	}
	function renderBottomButtons()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		if ($this->model && $this->model->bottom_buttons->count() > 0)
		{
			$__v->push($this->renderWidget($this->model->bottom_buttons));
		}
		
		return $__v;
	}
	function renderResult()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		
		if ($this->model && $this->model->show_result)
		{
			$__v->push($this->renderWidget($this->model->result));
		}
		
		return $__v;
	}
	function render()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		$__v->is_render = true;
		
		/* Element div */
		$__v0 = $__v->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("widget_form", $this->class, static::mergeStyles("widget_form", $this->model->styles), $componentHash))])));
		$__v0->push($this->renderContent());
		$__v0->push($this->renderFields());
		$__v0->push($this->renderBottomButtons());
		$__v0->push($this->renderResult());
		
		return $__v;
	}
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
	}
	static function getComponentStyle(){ return ".widget_form.h-b6a7 .widget_form__content{text-align: center;font-size: 16px;margin-bottom: 10px}.widget_form.h-b6a7 .widget_form__field_row{margin-bottom: 10px}.widget_form.h-b6a7 .widget_form__field_label{display: flex;align-items: center;padding-bottom: 10px}.widget_form.h-b6a7 .widget_form__field_error{color: var(--widget-color-danger);margin-top: 5px}.widget_form.h-b6a7 .widget_form__field_error--hide{display: none}.widget_form.h-b6a7 %(RowButtons)widget_form__bottom_buttons{justify-content: center}.widget_form.fixed.h-b6a7{max-width: 600px;margin-left: auto;margin-right: auto}"; }
	static function getRequiredComponents(){ return new \Runtime\Vector("Runtime.Widget.Button", "Runtime.Widget.Input", "Runtime.Widget.RowButtons", "Runtime.Widget.TextArea", "Runtime.Widget.WidgetResult"); }
	static function getClassName(){ return "Runtime.Widget.Form.Form"; }
}