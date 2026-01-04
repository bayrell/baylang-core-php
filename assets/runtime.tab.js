"use strict;"
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
if (typeof Runtime == 'undefined') Runtime = {};
if (typeof Runtime.Widget == 'undefined') Runtime.Widget = {};
if (typeof Runtime.Widget.Tab == 'undefined') Runtime.Widget.Tab = {};
Runtime.Widget.Tab.Tab = {
	name: "Runtime.Widget.Tab.Tab",
	extends: Runtime.Component,
	props: {
		name: {default: ""},
		title: {default: ""},
		href: {default: ""},
	},
	methods:
	{
		render: function()
		{
			const rs = use("Runtime.rs");
			const componentHash = rs.getComponentHash(this.getClassName());
			let __v = new Runtime.VirtualDom(this);
			
			let model = this.getParent().model;
			
			/* Element div */
			let __v0 = __v.element("div", new Runtime.Map({"class": rs.className(["tabs__item", model.isActive(this.name) ? "tabs__item--active" : "", componentHash]), "data-tab": this.name}));
			
			if (this.canShow)
			{
				__v0.push(this.renderSlot("default"));
			}
			
			return __v;
		},
		getClassName: function(){ return "Runtime.Widget.Tab.Tab"; },
	},
	computed:
	{
		/**
		 * Returns true if tab can show
		 */
		canShow: function()
		{
			if (this.href == "") return true;
			let model = this.getParent().model;
			if (model.isActive(this.name)) return true;
			return false;
		},
	},
	getComponentStyle: function(){ return ".tabs__item.h-8789{position: relative;display: none}.tabs__item--active.h-8789{display: block}"; },
	getRequiredComponents: function(){ return new Runtime.Vector(); },
};
window["Runtime.Widget.Tab.Tab"] = Runtime.Widget.Tab.Tab;
"use strict;"
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
if (typeof Runtime == 'undefined') Runtime = {};
if (typeof Runtime.Widget == 'undefined') Runtime.Widget = {};
if (typeof Runtime.Widget.Tab == 'undefined') Runtime.Widget.Tab = {};
Runtime.Widget.Tab.TabMessage = class extends Runtime.Message
{
	/* ========= Class init functions ========= */
	_init()
	{
		super._init();
		this.name = "tab";
		this.key = "";
	}
	static getClassName(){ return "Runtime.Widget.Tab.TabMessage"; }
	static getMethodsList(){ return null; }
	static getMethodInfoByName(field_name){ return null; }
	static getInterfaces(){ return []; }
};
window["Runtime.Widget.Tab.TabMessage"] = Runtime.Widget.Tab.TabMessage;
"use strict;"
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
if (typeof Runtime == 'undefined') Runtime = {};
if (typeof Runtime.Widget == 'undefined') Runtime.Widget = {};
if (typeof Runtime.Widget.Tab == 'undefined') Runtime.Widget.Tab = {};
Runtime.Widget.Tab.Tabs = {
	name: "Runtime.Widget.Tab.Tabs",
	extends: Runtime.Component,
	methods:
	{
		renderHeader: function()
		{
			const rs = use("Runtime.rs");
			const componentHash = rs.getComponentHash(this.getClassName());
			let __v = new Runtime.VirtualDom(this);
			
			/* Element div */
			let __v0 = __v.element("div", new Runtime.Map({"class": rs.className(["tabs__header", componentHash])}));
			
			let items = this.items;
			for (let i = 0; i < items.count(); i++)
			{
				let tab = items.get(i);
				let tab_name = tab.get("name");
				let tab_title = tab.get("title");
				let tab_href = tab.get("href");
				let is_active = this.model.isActive(tab_name);
				if (tab_href == null)
				{
					/* Element div */
					let __v1 = __v0.element("div", new Runtime.Map({"class": rs.className(["tabs__header_item", is_active ? "tabs__header_item--active" : "", componentHash]), "data-tab": tab_name, "onClick": this.hash(0) ? this.hash(0) : this.hash(0, (event) =>
					{
						this.onClick(tab_name);
					})}));
					__v1.push(tab_title);
				}
				else
				{
					/* Element a */
					let __v2 = __v0.element("a", new Runtime.Map({"class": rs.className(["tabs__header_item", is_active ? "tabs__header_item--active" : "", componentHash]), "data-tab": tab_name, "href": tab_href}));
					__v2.push(tab_title);
				}
			}
			
			return __v;
		},
		render: function()
		{
			const rs = use("Runtime.rs");
			const componentHash = rs.getComponentHash(this.getClassName());
			let __v = new Runtime.VirtualDom(this);
			
			/* Element div */
			let __v0 = __v.element("div", new Runtime.Map({"class": rs.className(["tabs", this.class, componentHash])}));
			__v0.push(this.renderHeader());
			
			/* Element div */
			let __v1 = __v0.element("div", new Runtime.Map({"class": rs.className(["tabs__content", componentHash])}));
			__v1.push(this.renderSlot("default"));
			
			return __v;
		},
		/**
		 * OnClick
		 */
		onClick: function(tab_key)
		{
			this.model.setActive(tab_key);
			this.emit(new Runtime.Widget.Tab.TabMessage(Runtime.Map.create({
				"key": tab_key,
			})));
		},
		getClassName: function(){ return "Runtime.Widget.Tab.Tabs"; },
	},
	computed:
	{
		/**
		 * Returns items
		 */
		items: function()
		{
			let result = Runtime.Vector.create([]);
			let items = Runtime.Vector.create([]);
			let vdom = this.renderSlot("default");
			if (vdom instanceof Runtime.VirtualDom) items = vdom.items;
			else
			{
				items = Runtime.Vector.create(vdom);
			}
			for (let i = 0; i < items.count(); i++)
			{
				let item = items.get(i);
				if (item instanceof Runtime.VirtualDom)
				{
					result.push(Runtime.Map.create({
						"name": item.attrs.get("name"),
						"title": item.attrs.get("title"),
						"href": item.attrs.get("href"),
					}));
				}
				else
				{
					result.push(Runtime.Map.create({
						"name": item.props.name,
						"title": item.props.title,
						"href": item.props.href,
					}));
				}
			}
			return result;
		},
	},
	getComponentStyle: function(){ return ".tabs.h-209{position: relative}.tabs__header.h-209{display: flex;position: relative;border-bottom-width: var(--border-width);border-bottom-color: var(--color-border);border-bottom-style: solid;transition: background-color var(--transition) var(--transition-type),\n\t\tborder-color var(--transition) var(--transition-type),\n\t\tcolor var(--transition) var(--transition-type)}.tabs__header_item.h-209{position: relative;padding: calc(1.5 * var(--space));border-color: transparent;border-width: var(--border-width);border-style: solid;border-bottom-width: 0px;transition: background-color var(--transition) var(--transition-type),\n\t\tborder-color var(--transition) var(--transition-type),\n\t\tcolor var(--transition) var(--transition-type);text-decoration: none;color: inherit;cursor: pointer;-webkit-user-select: none;-moz-user-select: none;-khtml-user-select: none;-ms-user-select: none;top: var(--border-width)}.tabs__header_item.h-209:hover, .tabs__header_item.h-209:visited, .tabs__header_item.h-209:visited:hover, .tabs__header_item.h-209:focus{text-decoration: none;color: inherit;box-shadow: none;outline: transparent}.tabs__header_item--active.h-209{background-color: var(--color-background);border-color: var(--color-border)}.tabs__content.h-209{margin-top: calc(2 * var(--space))}.tabs__item.h-209{position: relative;display: none}.tabs__item--active.h-209{display: block}"; },
	getRequiredComponents: function(){ return new Runtime.Vector("Runtime.Widget.Tab.Tab"); },
};
window["Runtime.Widget.Tab.Tabs"] = Runtime.Widget.Tab.Tabs;
"use strict;"
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
if (typeof Runtime == 'undefined') Runtime = {};
if (typeof Runtime.Widget == 'undefined') Runtime.Widget = {};
if (typeof Runtime.Widget.Tab == 'undefined') Runtime.Widget.Tab = {};
Runtime.Widget.Tab.TabsModel = class extends Runtime.BaseModel
{
	/**
	 * Init widget params
	 */
	initParams(params)
	{
		super.initParams(params);
		if (params == null) return;
		if (params.has("active")) this.active = params.get("active");
		if (params.has("render")) this.render = params.get("render");
	}
	
	
	/**
	 * Returns true if active
	 */
	isActive(name){ return this.active == name; }
	
	
	/**
	 * Set active
	 */
	setActive(active)
	{
		this.active = active;
	}
	
	
	/**
	 * Can show
	 */
	canShow(tab_key)
	{
		let tab = this.items.findItem(Runtime.lib.equalAttr("key", tab_key));
		if (tab == null) return false;
		if (tab.has("href") && tab.get("key") != tab_key) return false;
		return true;
	}
	
	
	/* ========= Class init functions ========= */
	_init()
	{
		super._init();
		this.active = "";
		this.render = true;
		this.component = "Runtime.Widget.Tab.Tabs";
	}
	static getClassName(){ return "Runtime.Widget.Tab.TabsModel"; }
	static getMethodsList(){ return null; }
	static getMethodInfoByName(field_name){ return null; }
	static getInterfaces(){ return []; }
};
window["Runtime.Widget.Tab.TabsModel"] = Runtime.Widget.Tab.TabsModel;
