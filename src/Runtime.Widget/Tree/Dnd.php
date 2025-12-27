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
namespace Runtime\Widget\Tree;

use Runtime\BaseObject;

class Dnd extends \Runtime\BaseObject
{
	var $is_drag;
	var $drag_elem;
	var $drag_start_point;
	var $drag_dest_box;
	var $drag_dest_elem;
	var $drag_dest_item;
	var $drag_dest_kind;
	
	
	/**
	 * Returns src elem
	 */
	function getSrc()
	{
		if (!$this->drag_elem) return null;
		$src_elem_path = $this->drag_elem->getAttribute("data-path");
		$src_elem = $src_elem_path ? \Runtime\rs::split(".", $src_elem_path) : new \Runtime\Vector();
		return $src_elem->map(function ($s){ return \Runtime\rtl::toInt($s); });
	}
	
	
	/**
	 * Returns dest elem
	 */
	function getDest()
	{
		if (!$this->drag_dest_elem) return null;
		$dest_elem_path = $this->drag_dest_elem->getAttribute("data-path");
		$dest_elem = $dest_elem_path ? \Runtime\rs::split(".", $dest_elem_path) : new \Runtime\Vector();
		return $dest_elem->map(function ($s){ return \Runtime\rtl::toInt($s); });
	}
	
	
	/**
	 * Find drag elem
	 */
	function findDragElem($elem)
	{
		if ($elem->classList->contains("tree_widget__item_label")) return $elem->parentElement;
		return $elem;
	}
	
	
	/**
	 * Find elem by path
	 */
	function findElemByPath($path)
	{
		$path = ".tree_widget__item[data-path='" . $path . "']";
		return $document->querySelector($path);
	}
	
	
	/**
	 * Returns true if elem inside drag_elem
	 */
	function checkInside($elem)
	{
		if (!$this->drag_elem) return false;
		if ($elem == $this->drag_elem) return false;
		$drag_elem_path = $this->drag_elem->getAttribute("data-path");
		$elem_path = $elem->getAttribute("data-path");
		if ($drag_elem_path == $elem_path) return true;
		if (\Runtime\rs::substr($elem_path, 0, \Runtime\rs::strlen($drag_elem_path) + 1) == $drag_elem_path . ".") return true;
		return false;
	}
	
	
	/**
	 * Set start drag item
	 */
	function setStartDragItem($e)
	{
		$this->drag_elem = $this->findDragElem($e->currentTarget);
		$this->drag_start_point = new \Runtime\Map([
			"x" => $e->layerX,
			"y" => $e->layerY,
		]);
	}
	
	
	/**
	 * Mouse move item
	 */
	function onMouseMoveItem($e, $item)
	{
		if ($this->drag_elem == null) return;
		/* Try to start drag & drop */
		if (!$this->is_drag) $this->startDrag($e);
		if (!$this->is_drag) return;
		/* Drag & Drop started */
		$target = $e->currentTarget;
		$top = $target->offsetTop;
		$bottom = $target->offsetTop + $target->clientHeight;
		$center = ($top + $bottom) / 2;
		$kind = "before";
		if ($e->layerY >= $center)
		{
			$kind = "into";
		}
		$this->setDragDestElement($target, $item, $kind);
		$e->preventDefault();
	}
	
	
	/**
	 * On mouse move
	 */
	function onMouseMove($e)
	{
		if ($this->drag_elem == null) return;
		/* Try to start drag & drop */
		if (!$this->is_drag) $this->startDrag($e);
		if (!$this->is_drag) return;
		/* Outside of tree contents */
		$tree_content = $this->getRef("content");
		if ($e->layerY > $tree_content->clientHeight)
		{
			$this->setDragDestElement($tree_content, null, "after");
			$e->preventDefault();
			return false;
		}
	}
	
	
	/**
	 * Start Drag & Drop
	 */
	function startDrag($e)
	{
		if (!$this->model->dnd) return false;
		if ($this->is_drag != false) return false;
		if ($this->drag_start_point == null) return false;
		if (Math::abs($e->layerY - $this->drag_start_point->get("y")) > 5) return false;
		$this->is_drag = true;
		return true;
	}
	
	
	/**
	 * Stop drag & drop
	 */
	function stopDrag()
	{
		/* Do drag & drop */
		if ($this->drag_dest_box && $this->drag_elem && $this->drag_dest_elem)
		{
			$this->model->dragElement($this->getSrc(), $this->getDest(), $this->drag_dest_kind);
		}
		$this->is_drag = false;
		$this->drag_dest_box = null;
		$this->drag_dest_elem = null;
		$this->drag_dest_item = null;
		$this->drag_dest_kind = null;
		$this->drag_elem = null;
		$this->drag_start_point = null;
	}
	
	
	/**
	 * Set drag & drop dest element
	 */
	function setDragDestElement($elem, $item, $kind)
	{
		if (!$this->is_drag) return;
		if ($this->checkInside($elem)) return;
		if ($kind == "into" && $this->drag_elem == $elem) $kind = "before";
		if ($kind == "into" && $item != null && !$item->canDragInside()) $kind = "after";
		if ($this->drag_dest_elem == $elem && $this->drag_dest_kind == $kind) return;
		/* Setup box */
		if ($this->drag_elem == $elem)
		{
			$this->drag_dest_box = null;
			return;
		}
		/* Setup dest element */
		$this->drag_dest_elem = $elem;
		$this->drag_dest_item = $item;
		/* Get elem path */
		$src_path = $this->getSrc();
		$dest_path = $this->getDest();
		if ($dest_path == null)
		{
			$this->drag_dest_box = null;
			return;
		}
		/* Can drag */
		$can_drag = $this->model->canDrag($src_path, $dest_path, $kind);
		if (!$can_drag)
		{
			if ($kind == "into")
			{
				$kind = "after";
				$can_drag = $this->model->canDrag($src_path, $dest_path, $kind);
				if (!$can_drag)
				{
					$this->drag_dest_box = null;
					return;
				}
			}
		}
		/* Setup dest values */
		$this->drag_dest_kind = $kind;
		$this->drag_dest_box = $this->getBoxStyles($elem, $kind);
	}
	
	
	/**
	 * Returns box styles by element
	 */
	function getBoxStyles($elem, $kind = "")
	{
		$left = $elem->offsetLeft;
		$top = $elem->offsetTop;
		$width = $elem->clientWidth - 1;
		$height = $elem->clientHeight - 1;
		if ($kind == "before") return \Runtime\rs::join(";", new \Runtime\Vector(
			"left: " . $left . "px",
			"top: " . $top . "px",
			"width: " . $width . "px",
			"height: 1px",
		));
		if ($kind == "after") return \Runtime\rs::join(";", new \Runtime\Vector(
			"left: " . $left . "px",
			"top: " . $top + $height . "px",
			"width: " . $width . "px",
			"height: 1px",
		));
		if ($kind == "into") return \Runtime\rs::join(";", new \Runtime\Vector(
			"left: " . $left . "px",
			"top: " . $top . "px",
			"width: " . $width . "px",
			"height: " . $height . "px",
		));
		return null;
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
		parent::_init();
		$this->is_drag = false;
		$this->drag_elem = null;
		$this->drag_start_point = null;
		$this->drag_dest_box = null;
		$this->drag_dest_elem = null;
		$this->drag_dest_item = null;
		$this->drag_dest_kind = null;
	}
	static function getClassName(){ return "Runtime.Widget.Tree.Dnd"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}