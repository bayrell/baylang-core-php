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
namespace Runtime\Web;

use Runtime\re;
use Runtime\rtl;
use Runtime\lib;
use Runtime\Math;
use Runtime\Vector;


class RenderHelper
{
	/**
	 * From rgb
	 */
	static function rgbToInt($color)
	{
		$ch = static::substr($color, 0, 1);
		if ($ch == "#") $color = static::substr($color, 1);
		$r = "";
		$g = "";
		$b = "";
		$sz = static::strlen($color);
		if ($sz == 3)
		{
			$r = \Runtime\rs::substr($color, 0, 1);
			$r .= $r;
			$g = \Runtime\rs::substr($color, 1, 1);
			$g .= $g;
			$b = \Runtime\rs::substr($color, 2, 1);
			$b .= $b;
		}
		else if ($sz == 6)
		{
			$r = \Runtime\rs::substr($color, 0, 2);
			$g = \Runtime\rs::substr($color, 2, 2);
			$b = \Runtime\rs::substr($color, 4, 2);
		}
		$r = static::hexdec($r);
		$g = static::hexdec($g);
		$b = static::hexdec($b);
		return new \Runtime\Vector($r, $g, $b);
	}
	
	
	/**
	 * From rgb
	 */
	static function intToRgb($r, $g, $b)
	{
		return sprintf("%02x%02x%02x", $r, $g, $b);
	}
	
	
	/**
	 * Brightness
	 */
	static function brightness($color, $percent)
	{
		$result = static::rgbToInt($color);
		$r = $result[0];
		$g = $result[1];
		$b = $result[2];
		$r = \Runtime\Math::round($r + $r * $percent / 100);
		$g = \Runtime\Math::round($g + $g * $percent / 100);
		$b = \Runtime\Math::round($b + $b * $percent / 100);
		if ($r > 255) $r = 255;
		if ($g > 255) $g = 255;
		if ($b > 255) $b = 255;
		if ($r < 0) $r = 0;
		if ($g < 0) $g = 0;
		if ($b < 0) $b = 0;
		return "#" . static::intToRgb($r, $g, $b);
	}
	
	
	/**
	 * Strip tags
	 */
	static function strip_tags($content, $allowed_tags = null)
	{
		if ($allowed_tags == null)
		{
			$content = \Runtime\re::replace("<[^>]+>", "", $content);
			$content = \Runtime\rs::trim(\Runtime\rs::spaceless($content));
			return $content;
		}
		$matches = \Runtime\re::matchAll("<[^>]+>", $content, "i");
		if ($matches)
		{
			for ($i = 0; $i < $matches->count(); $i++)
			{
				$match = $matches[$i];
				$tag_str = $match[0];
				$tag_match = \Runtime\re::matchAll("<(\\/|)([a-zA-Z]+)(|[^>]*)>", $tag_str, "i");
				if ($tag_match)
				{
					$tag_name = static::strtolower($tag_match[0][2]);
					if ($allowed_tags->indexOf($tag_name) == -1)
					{
						$content = static::replace($tag_str, "", $content);
					}
				}
			}
		}
		$content = \Runtime\rs::trim(\Runtime\rs::spaceless($content));
		return $content;
	}
	
	
	/* ========= Class init functions ========= */
	function _init()
	{
	}
	static function getClassName(){ return "Runtime.Web.RenderHelper"; }
	static function getMethodsList(){ return null; }
	static function getMethodInfoByName($field_name){ return null; }
}