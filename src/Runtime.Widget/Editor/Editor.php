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
namespace Runtime\Widget\Editor;

use Runtime\Reference;
use Runtime\Web\Messages\ValueChangeMessage;


class Editor extends \Runtime\Widget\Field
{
	function render()
	{
		$componentHash = \Runtime\rs::getComponentHash(static::getClassName());
		$__v = new \Runtime\VirtualDom($this);
		$__v->is_render = true;
		
		$props = $this->getProps();
		
		/* Element div */
		$__v0 = $__v->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("widget_text", $componentHash))])));
		
		/* Element div */
		$__v1 = $__v0->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("widget_text__lines", $componentHash))])));
		
		/* Element div */
		$__v2 = $__v1->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("widget_text__wrap", $componentHash))])));
		
		for ($i = 0; $i < $this->lines->count(); $i++)
		{
			/* Element div */
			$__v3 = $__v2->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("line", $componentHash)), "style" => "height: " . $this->lines->get($i) . "px"])));
			$__v3->push($i + 1);
		}
		
		/* Element div */
		$__v0->element("div", (new \Runtime\Map(["class" => \Runtime\rs::className(new \Runtime\Vector("widget_text__editable", $this->class, $componentHash)), "name" => $this->name, "contenteditable" => "plaintext-only"]))->concat($props));
		
		return $__v;
	}
	var $reference;
	var $readonly;
	var $timeout;
	var $name;
	var $value;
	var $lines;
	var $change_timer;
	var $old_value;
	/**
	 * Returns value
	 */
	function getValue()
	{
		$nodes = $this->getRef("text")->childNodes;
		$content = new \Runtime\Vector();
		for ($i = 0; $i < $nodes->length; $i++)
		{
			$item = $nodes->item($i)->innerText->trimRight();
			$content->push($item);
		}
		return \Runtime\rs::join("\n", $content);
	}
	/**
	 * Set value
	 */
	function setValue($content)
	{
		$text = $this->getRef("text");
		$this->old_value = $content;
		/* Add lines */
		$text->innerHTML = "";
		$lines = \Runtime\rs::split("\n", $content ? $content : "");
		for ($i = 0; $i < $lines->count(); $i++)
		{
			$line_text = $lines->get($i)->trimRight();
			$line_elem = $this->createNewLine($line_text);
			$text->append($line_elem);
		}
		/* Update lines */
		$this->updateLinesCount();
	}
	/**
	 * Returns textarea props
	 */
	function getProps()
	{
		if ($this->readonly) return new \Runtime\Map([
			"readonly" => true,
		]);
		return new \Runtime\Map();
	}
	/**
	 * Returns new line
	 */
	function createNewLine($text = "")
	{
		$div = $document->createElement("div");
		$div->classList->add("line");
		$div->innerText = $text;
		return $div;
	}
	/**
	 * Returns line
	 */
	function findLine($node)
	{
		while ($node && ($node->nodeType == 3 || !$node->classList->contains("line")))
		{
			$node = $node->parentElement;
		}
		return $node;
	}
	/**
	 * Returns line element
	 */
	function getLineElement($pos){ return $this->getRef("text")->childNodes->item($pos); }
	/**
	 * Returns line element
	 */
	function getLinePosition($line)
	{
		$nodes = $this->getRef("text")->childNodes;
		for ($i = 0; $i < $nodes->length; $i++)
		{
			if ($nodes->item($i) == $line) return $i;
		}
		return -1;
	}
	/**
	 * Returns node offset
	 */
	function getNodeOffset($line, $node, $offset)
	{
		$pos = 0;
		$is_line = $line == $node;
		$current = $line->firstChild;
		while ($current)
		{
			if ($is_line && $offset == 0) break;
			if ($current->nodeType == 3)
			{
				if ($is_line)
				{
					$offset = $offset - 1;
				}
				else
				{
					if ($current == $node)
					{
						return $pos + $offset;
					}
				}
				$pos = $pos + $current->textContent->length;
			}
			$current = $current->nextSibling;
		}
		return $pos;
	}
	/**
	 * Returns selection
	 */
	function getSelection(){ return $this->getRef("text")->ownerDocument->defaultView->getSelection(); }
	/**
	 * Returns cursor position
	 */
	function getCursorPos()
	{
		$selection = $this->getSelection();
		$range = $selection->getRangeAt(0);
		/* Get range start */
		$start_line = $this->findLine($range->startContainer);
		$start_y = $this->getLinePosition($start_line);
		$start_x = $this->getNodeOffset($start_line, $range->startContainer, $range->startOffset);
		/* Get range end */
		$end_line = $this->findLine($range->endContainer);
		$end_y = $this->getLinePosition($end_line);
		$end_x = $this->getNodeOffset($end_line, $range->endContainer, $range->endOffset);
		return new \Runtime\Map([
			"start_y" => $start_y,
			"start_x" => $start_x,
			"end_y" => $end_y,
			"end_x" => $end_x,
		]);
	}
	/**
	 * Set cursor position
	 */
	function setCursorPos($x, $y)
	{
		$selection = $this->getSelection();
		$range = $document->createRange();
		/* Get first node of line */
		$line_elem = $this->getLineElement($y);
		$current = $line_elem->firstChild;
		/* Find node contains x */
		$pos = 0;
		$offset = 0;
		while ($current)
		{
			if ($current->nodeType == 3)
			{
				$node_size = $current->textContent->length;
				if ($pos + $node_size >= $x)
				{
					$offset = $x - $pos;
					break;
				}
				$pos = $pos + $node_size;
			}
			$current = $current->nextSibling;
		}
		/* If node not found */
		if ($current == null)
		{
			$current = $line_elem;
			$offset = $line_elem->childNodes->length;
		}
		/* Set cursor */
		$range->setStart($current, $offset);
		$range->setEnd($current, $offset);
		$selection->removeAllRanges();
		$selection->addRange($range);
		return $range;
	}
	/**
	 * Update lines count
	 */
	function updateLinesCount()
	{
		$this->lines = new \Runtime\Vector();
		$nodes = $this->getRef("text")->childNodes;
		for ($i = 0; $i < $nodes->length; $i++)
		{
			$item = $nodes->item($i);
			$this->lines->push($item->getBoundingClientRect()->height);
		}
	}
	/**
	 * Mounted event
	 */
	function onMounted()
	{
		if ($this->reference) $this->reference->setValue($this);
		$this->setValue($this->value);
	}
	/**
	 * Updated event
	 */
	function onUpdated()
	{
		if ($this->old_value == $this->value) return;
		if ($this->change_timer) return;
		$this->setValue($this->value);
	}
	/**
	 * Paste text to current position
	 */
	function pasteText($text)
	{
		$cursor = $this->getCursorPos();
		$line_pos = $cursor->get("end_y");
		$line_offset = $cursor->get("end_x");
		$line_offset_new = 0;
		$line_elem = $this->getLineElement($line_pos);
		$line_text = $line_elem->innerText;
		$lines = \Runtime\rs::split("\n", $text);
		if ($lines->count() > 1)
		{
			/* Change first line */
			$first_line = $lines->shift();
			$line_elem->innerText = $line_text->slice(0, $line_offset) . $first_line;
			/* Change last line */
			$lines_last_pos = $lines->count() - 1;
			$line_offset_new = \Runtime\rs::strlen($lines->get($lines_last_pos));
			$lines->set($lines_last_pos, $lines->get($lines_last_pos) . $line_text->slice($line_offset));
		}
		else
		{
			$first_line = $line_text->slice(0, $line_offset) . $lines->pop();
			$line_elem->innerText = $first_line . $line_text->slice($line_offset);
			$line_offset_new = \Runtime\rs::strlen($first_line);
		}
		/* Insert lines after line_elem */
		$lines_count = $lines->count();
		for ($i = 0; $i < $lines_count; $i++)
		{
			$line_content = $lines->get($i);
			$line_new = $this->createNewLine($line_content);
			$line_elem = $line_elem->insertAdjacentElement("afterend", $line_new);
		}
		$this->setCursorPos($line_offset_new, $line_pos + $lines->count());
		$this->updateLinesCount();
	}
	/**
	 * Key down event
	 */
	function onKeyDown($e)
	{
		if ($e->key == "Enter")
		{
			$e->preventDefault();
			$e->stopPropagation();
			$this->pasteText("\n");
		}
		else if ($e->key == "Backspace")
		{
			$cursor = $this->getCursorPos();
			if ($cursor->get("start_x") == 0)
			{
				$window->setTimeout(function ()
				{
					$this->updateLinesCount();
				}, 10);
			}
		}
		else if ($e->key == "Delete")
		{
			$cursor = $this->getCursorPos();
			$line = $this->getLineElement($cursor->get("start_y"));
			if ($cursor->get("start_x") == $line->innerText->length)
			{
				$window->setTimeout(function ()
				{
					$this->updateLinesCount();
				}, 10);
			}
		}
		else if ($e->key == "Tab")
		{
			$e->preventDefault();
			$e->stopPropagation();
			/* Shift + Tab */
			if ($e->shiftKey)
			{
				$cursor = $this->getCursorPos();
				$line = $this->getLineElement($cursor->get("start_y"));
				$offset = $cursor->get("start_x");
				if ($offset == 0) return;
				$text = $line->innerText;
				$last_char = \Runtime\rs::charAt($text, $offset - 1);
				if ($last_char != "\t") return;
				$line->innerText = $text->slice(0, $offset - 1) . $text->slice($offset);
				$this->setCursorPos($offset - 1, $cursor->get("start_y"));
			}
			else
			{
				$node = $document->createTextNode("\t");
				$selection = $this->getSelection();
				$range = $selection->getRangeAt(0);
				$range->insertNode($node);
				$range->setStartAfter($node);
				$range->setEndAfter($node);
			}
		}
		$this->sendChangeMessage();
	}
	/**
	 * Paste event
	 */
	function onPaste($e)
	{
		$e->preventDefault();
		$e->stopPropagation();
		$text = $e->clipboardData->getData("text");
		$this->pasteText($text);
		$this->sendChangeMessage();
	}
	/**
	 * Input event
	 */
	function onInput($e)
	{
		$this->sendChangeMessage();
	}
	/**
	 * Send change message
	 */
	function sendChangeMessage()
	{
		if ($this->change_timer != null)
		{
			$window->clearTimeout($this->change_timer);
			$this->change_timer = null;
		}
		$this->change_timer = $window->setTimeout(function ()
		{
			$this->onChange();
		}, $this->timeout);
	}
	/**
	 * Change event
	 */
	function onChange($e)
	{
		/* Clear timer */
		if ($this->change_timer != null)
		{
			$window->clearTimeout($this->change_timer);
			$this->change_timer = null;
		}
		/* Get value */
		$value = $this->getValue();
		/* Send value change */
		$this->emit("valueChange", new \Runtime\Web\Messages\ValueChangeMessage(new \Runtime\Map([
			"value" => $value,
			"old_value" => $this->old_value,
			"data" => $this->data,
		])));
		/* Set old value */
		$this->old_value = $value;
		$this->change_timer = null;
	}
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->reference = null;
		$this->readonly = false;
		$this->timeout = 500;
		$this->name = "";
		$this->value = "";
		$this->lines = new \Runtime\Vector();
		$this->change_timer = null;
		$this->old_value = null;
	}
	static function getComponentStyle(){ return ".widget_text.h-7a01{display: flex;align-items: flex-start;width: 100%;max-width: 100%;border-width: var(--widget-border-width);border-color: var(--widget-color-border);border-style: solid;border-radius: 4px;overflow: auto}.widget_text__lines.h-7a01{display: flex;align-items: center;flex-direction: column;width: 20px;min-height: 100%;padding-top: var(--widget-button-padding-y);border-right-width: var(--widget-border-width);border-right-color: var(--widget-color-border);border-right-style: solid}.widget_text__lines.h-7a01 .line{text-align: right;min-height: 21px}.widget_text__editable.h-7a01{width: calc(100% - 20px);min-height: 100%;font-family: monospace;font-size: var(--widget-font-size);padding: var(--widget-button-padding-y) var(--widget-button-padding-x);margin: 0;background-color: var(--widget-color-default);box-shadow: none;outline: transparent;line-height: 1.5;tab-size: 4;text-wrap: balance;white-space: pre}.widget_text__editable.h-7a01 .line{min-height: 21px}.widget_text__editable.wrap.h-7a01{overflow-wrap: break-word;text-wrap: wrap}.widget_text__editable.overflow.h-7a01{overflow: auto;text-wrap: nowrap}"; }
	static function getRequiredComponents(){ return new \Runtime\Vector(); }
	static function getClassName(){ return "Runtime.Widget.Editor.Editor"; }
}