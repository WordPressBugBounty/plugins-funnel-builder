(window.webpackJsonp=window.webpackJsonp||[]).push([[2],{294:function(e,t,n){"use strict";var r=n(0),o=n(1),i=n(6),a=n.n(i),c=n(2),s=n(17),l=n.n(s),u=n(9),f=n(52),p=n(55);function d(e){return(d="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}function b(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function h(e,t){for(var n=0;n<t.length;n++){var r=t[n];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(e,r.key,r)}}function y(e,t){return(y=Object.setPrototypeOf||function(e,t){return e.__proto__=t,e})(e,t)}function m(e){var t=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Boolean.prototype.valueOf.call(Reflect.construct(Boolean,[],(function(){}))),!0}catch(e){return!1}}();return function(){var n,r=O(e);if(t){var o=O(this).constructor;n=Reflect.construct(r,arguments,o)}else n=r.apply(this,arguments);return v(this,n)}}function v(e,t){if(t&&("object"===d(t)||"function"==typeof t))return t;if(void 0!==t)throw new TypeError("Derived constructors may only return object or undefined");return g(e)}function g(e){if(void 0===e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return e}function O(e){return(O=Object.setPrototypeOf?Object.getPrototypeOf:function(e){return e.__proto__||Object.getPrototypeOf(e)})(e)}var S=function(e){!function(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function");e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,writable:!0,configurable:!0}}),t&&y(e,t)}(s,e);var t,n,o,i=m(s);function s(){var e;return b(this,s),(e=i.apply(this,arguments)).handleKeyDown=e.handleKeyDown.bind(g(e)),e.select=e.select.bind(g(e)),e.optionRefs={},e.listbox=Object(r.createRef)(),e}return t=s,(n=[{key:"componentDidUpdate",value:function(e){var t=this.props,n=t.options,r=t.selectedIndex;Object(c.isEqual)(n,e.options)||(this.optionRefs={}),r!==e.selectedIndex&&this.scrollToOption(r)}},{key:"getOptionRef",value:function(e){return this.optionRefs.hasOwnProperty(e)||(this.optionRefs[e]=Object(r.createRef)()),this.optionRefs[e]}},{key:"select",value:function(e){var t=this.props.onSelect;e.isDisabled||t(e)}},{key:"scrollToOption",value:function(e){var t=this.listbox.current;if(!(t.scrollHeight<=t.clientHeight)&&this.optionRefs[e]){var n=this.optionRefs[e].current,r=t.clientHeight+t.scrollTop,o=n.offsetTop+n.offsetHeight;o>r?t.scrollTop=o-t.clientHeight:n.offsetTop<t.scrollTop&&(t.scrollTop=n.offsetTop)}}},{key:"handleKeyDown",value:function(e){var t=this.props,n=t.decrementSelectedIndex,r=t.incrementSelectedIndex,o=t.options,i=t.onSearch,a=t.selectedIndex,c=t.setExpanded;if(0!==o.length)switch(e.keyCode){case p.UP:n(),e.preventDefault(),e.stopPropagation();break;case p.DOWN:r(),e.preventDefault(),e.stopPropagation();break;case p.ENTER:this.select(o[a]),e.preventDefault(),e.stopPropagation();break;case p.LEFT:case p.RIGHT:c(!1);break;case p.ESCAPE:return c(!1),void i(null);case p.TAB:this.select(o[a]),c(!1),i(null)}}},{key:"toggleKeyEvents",value:function(e){this.props.node[e?"addEventListener":"removeEventListener"]("keydown",this.handleKeyDown,!0)}},{key:"componentDidMount",value:function(){this.toggleKeyEvents(!0)}},{key:"componentWillUnmount",value:function(){this.toggleKeyEvents(!1)}},{key:"render",value:function(){var e=this,t=this.props,n=t.instanceId,o=t.listboxId,i=t.options,c=t.selectedIndex,s=t.staticList,l=a()("bwf-select-control-listbox",{"is-static":s});return Object(r.createElement)("div",{ref:this.listbox,id:o,role:"listbox",className:l,tabIndex:"-1"},i.map((function(t,o){return Object(r.createElement)(u.Button,{ref:e.getOptionRef(o),key:t.key,id:"bwf-select-control-option-".concat(n,"-").concat(t.key),role:"option","aria-selected":o===c,disabled:t.isDisabled,className:a()("bwf-select-control-option",{"is-selected":o===c}),onClick:function(){return e.select(t)},tabIndex:"-1"},t.label)})))}}])&&h(t.prototype,n),o&&h(t,o),s}(r.Component);S.propTypes={instanceId:l.a.number,listboxId:l.a.string,node:l.a.instanceOf(Element).isRequired,onSelect:l.a.func,options:l.a.arrayOf(l.a.shape({isDisabled:l.a.bool,key:l.a.oneOfType([l.a.number,l.a.string]).isRequired,keywords:l.a.arrayOf(l.a.oneOfType([l.a.string,l.a.number])),label:l.a.oneOfType([l.a.string,l.a.object]),value:l.a.any})).isRequired,selectedIndex:l.a.number,staticList:l.a.bool};var w=S,j=n(364);function k(e){return(k="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}function x(e){return function(e){if(Array.isArray(e))return E(e)}(e)||function(e){if("undefined"!=typeof Symbol&&null!=e[Symbol.iterator]||null!=e["@@iterator"])return Array.from(e)}(e)||function(e,t){if(!e)return;if("string"==typeof e)return E(e,t);var n=Object.prototype.toString.call(e).slice(8,-1);"Object"===n&&e.constructor&&(n=e.constructor.name);if("Map"===n||"Set"===n)return Array.from(e);if("Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n))return E(e,t)}(e)||function(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function E(e,t){(null==t||t>e.length)&&(t=e.length);for(var n=0,r=new Array(t);n<t;n++)r[n]=e[n];return r}function I(e,t){for(var n=0;n<t.length;n++){var r=t[n];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(e,r.key,r)}}function T(e,t){return(T=Object.setPrototypeOf||function(e,t){return e.__proto__=t,e})(e,t)}function R(e){var t=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Boolean.prototype.valueOf.call(Reflect.construct(Boolean,[],(function(){}))),!0}catch(e){return!1}}();return function(){var n,r=A(e);if(t){var o=A(this).constructor;n=Reflect.construct(r,arguments,o)}else n=r.apply(this,arguments);return _(this,n)}}function _(e,t){if(t&&("object"===k(t)||"function"==typeof t))return t;if(void 0!==t)throw new TypeError("Derived constructors may only return object or undefined");return P(e)}function P(e){if(void 0===e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return e}function A(e){return(A=Object.setPrototypeOf?Object.getPrototypeOf:function(e){return e.__proto__||Object.getPrototypeOf(e)})(e)}var C=function(e){!function(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function");e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,writable:!0,configurable:!0}}),t&&T(e,t)}(s,e);var t,n,i,a=R(s);function s(e){var t;return function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}(this,s),(t=a.call(this,e)).removeAll=t.removeAll.bind(P(t)),t.removeResult=t.removeResult.bind(P(t)),t}return t=s,(n=[{key:"removeAll",value:function(){(0,this.props.onChange)([])}},{key:"removeResult",value:function(e){var t=this;return function(){var n=t.props,r=n.selected,o=n.onChange,i=Object(c.findIndex)(r,{key:e});o([].concat(x(r.slice(0,i)),x(r.slice(i+1))))}}},{key:"render",value:function(){var e=this,t=this.props,n=t.selected,i=t.showClearButton;return n.length?Object(r.createElement)(r.Fragment,null,Object(r.createElement)("div",{className:"bwf-select-control-tags"},n.map((function(t,i){if(!t.label)return null;var a=Object(o.sprintf)(Object(o.__)("%1$s (%2$s of %3$s)","funnel-builder"),t.label,i+1,n.length);return Object(r.createElement)(j.a,{key:t.key,id:t.key,label:t.label,remove:e.removeResult,screenReaderLabel:a})}))),i&&Object(r.createElement)(u.Button,{className:"bwf-select-control-clear",isLink:!0,onClick:this.removeAll},Object(r.createElement)(u.Icon,{icon:"dismiss"}),Object(r.createElement)("span",{className:"screen-reader-text"},Object(o.__)("Clear all","funnel-builder")))):null}}])&&I(t.prototype,n),i&&I(t,i),s}(r.Component);C.propTypes={onChange:l.a.func,onSelect:l.a.func,selected:l.a.arrayOf(l.a.shape({key:l.a.oneOfType([l.a.number,l.a.string]).isRequired,label:l.a.string})),showClearButton:l.a.bool};var F=C,B=n(7);function D(e){return(D="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}function N(e){return function(e){if(Array.isArray(e))return q(e)}(e)||function(e){if("undefined"!=typeof Symbol&&null!=e[Symbol.iterator]||null!=e["@@iterator"])return Array.from(e)}(e)||function(e,t){if(!e)return;if("string"==typeof e)return q(e,t);var n=Object.prototype.toString.call(e).slice(8,-1);"Object"===n&&e.constructor&&(n=e.constructor.name);if("Map"===n||"Set"===n)return Array.from(e);if("Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n))return q(e,t)}(e)||function(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function q(e,t){(null==t||t>e.length)&&(t=e.length);for(var n=0,r=new Array(t);n<t;n++)r[n]=e[n];return r}function M(e,t){for(var n=0;n<t.length;n++){var r=t[n];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(e,r.key,r)}}function K(e,t){return(K=Object.setPrototypeOf||function(e,t){return e.__proto__=t,e})(e,t)}function L(e){var t=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Boolean.prototype.valueOf.call(Reflect.construct(Boolean,[],(function(){}))),!0}catch(e){return!1}}();return function(){var n,r=$(e);if(t){var o=$(this).constructor;n=Reflect.construct(r,arguments,o)}else n=r.apply(this,arguments);return U(this,n)}}function U(e,t){if(t&&("object"===D(t)||"function"==typeof t))return t;if(void 0!==t)throw new TypeError("Derived constructors may only return object or undefined");return W(e)}function W(e){if(void 0===e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return e}function $(e){return($=Object.setPrototypeOf?Object.getPrototypeOf:function(e){return e.__proto__||Object.getPrototypeOf(e)})(e)}var H=function(e){!function(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function");e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,writable:!0,configurable:!0}}),t&&K(e,t)}(s,e);var t,n,i,c=L(s);function s(e){var t;return function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}(this,s),(t=c.call(this,e)).state={isActive:!1},t.input=Object(r.createRef)(),t.updateSearch=t.updateSearch.bind(W(t)),t.onFocus=t.onFocus.bind(W(t)),t.onBlur=t.onBlur.bind(W(t)),t.onKeyDown=t.onKeyDown.bind(W(t)),t}return t=s,(n=[{key:"updateSearch",value:function(e){var t=this;return function(n){t.isBWFMaintainSingleTerm()||e(n.target.value)}}},{key:"onFocus",value:function(e){var t=this,n=this.props,r=n.isSearchable,o=n.setExpanded,i=n.showAllOnFocus,a=n.updateFilteredOptions;return function(n){t.setState({isActive:!0}),r&&i?(n.target.select(),a("")):r?e(n.target.value):o(!0)}}},{key:"onBlur",value:function(){var e=this.props.onBlur;"function"==typeof e&&e(),this.setState({isActive:!1})}},{key:"onKeyDown",value:function(e){var t=this.props,n=t.decrementSelectedIndex,r=t.incrementSelectedIndex,o=t.selected,i=t.onChange,a=t.query,c=t.setExpanded;p.BACKSPACE===e.keyCode&&!a&&o.length&&i(N(o.slice(0,-1))),p.DOWN===e.keyCode&&(r(),c(!0),e.preventDefault(),e.stopPropagation()),p.UP===e.keyCode&&(n(),c(!0),e.preventDefault(),e.stopPropagation())}},{key:"renderButton",value:function(){var e=this.props,t=e.multiple,n=e.selected;return t||!n.length?null:Object(r.createElement)("div",{className:"bwf-select-control-control-value"},n[0].label)}},{key:"renderInput",value:function(){var e=this.props,t=e.activeId,n=e.hasTags,o=e.inlineTags,i=e.instanceId,a=e.isExpanded,c=e.isSearchable,s=e.listboxId,l=e.onSearch,u=e.placeholder,f=e.searchInputType,p=this.state.isActive,d=this.isBWFMaintainSingleTerm();return Object(r.createElement)("input",{autoComplete:"off",className:"bwf-select-control-control-input",id:"bwf-select-control-".concat(i,"-control-input"),ref:this.input,type:c?f:"button",value:this.getInputValue(),placeholder:p&&!d?u:"",onChange:this.updateSearch(l),onFocus:this.onFocus(l),onBlur:this.onBlur,onKeyDown:this.onKeyDown,role:"combobox","aria-autocomplete":"list","aria-expanded":a,"aria-haspopup":"true","aria-owns":s,"aria-controls":s,"aria-activedescendant":t,"aria-describedby":n&&o?"search-inline-input-".concat(i):null})}},{key:"isBWFMaintainSingleTerm",value:function(){var e=this.props,t=e.selected;return!!e.bwfMaintainSingleTerm&&t.length>0}},{key:"getInputValue",value:function(){var e=this.props,t=e.isFocused,n=e.isSearchable,r=e.multiple,o=e.query,i=e.selected,a=i.length?i[0].label:"";return r||t?this.isBWFMaintainSingleTerm()?"":n&&t&&o?o:"":a}},{key:"render",value:function(){var e=this,t=this.props,n=t.className,i=t.hasTags,c=t.help,s=t.inlineTags,l=t.instanceId,u=t.isSearchable,f=t.label,p=t.query,d=this.state.isActive;return Object(r.createElement)("div",{className:a()("components-base-control","bwf-select-control-control",n,{empty:!p||0===p.length,"is-active":d,"has-tags":s&&i,"with-value":this.getInputValue().length,"has-error":!!c}),onClick:function(){e.input.current.focus()}},u&&Object(r.createElement)(B.a,{icon:"search",size:18}),s&&Object(r.createElement)(F,this.props),Object(r.createElement)("div",{className:"components-base-control__field"},!!f&&Object(r.createElement)("label",{htmlFor:"bwf-select-control-".concat(l,"-control-input"),className:"components-base-control__label"},f),this.renderInput(),s&&Object(r.createElement)("span",{id:"search-inline-input-".concat(l),className:"screen-reader-text"},Object(o.__)("Move backward for selected items","funnel-builder")),!!c&&Object(r.createElement)("p",{id:"bwf-select-control-".concat(l,"__help"),className:"components-base-control__help"},c)))}}])&&M(t.prototype,n),i&&M(t,i),s}(r.Component);H.propTypes={hasTags:l.a.bool,help:l.a.oneOfType([l.a.string,l.a.node]),inlineTags:l.a.bool,isSearchable:l.a.bool,instanceId:l.a.number,label:l.a.string,listboxId:l.a.string,onBlur:l.a.func,onChange:l.a.func,onSearch:l.a.func,placeholder:l.a.string,query:l.a.string,selected:l.a.arrayOf(l.a.shape({key:l.a.oneOfType([l.a.number,l.a.string]).isRequired,label:l.a.string})),showAllOnFocus:l.a.bool,bwfMaintainSingleTerm:l.a.bool};var V=H,J=(n(453),n(124));function z(e){return(z="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}function G(){return(G=Object.assign||function(e){for(var t=1;t<arguments.length;t++){var n=arguments[t];for(var r in n)Object.prototype.hasOwnProperty.call(n,r)&&(e[r]=n[r])}return e}).apply(this,arguments)}function Q(e){return function(e){if(Array.isArray(e))return X(e)}(e)||function(e){if("undefined"!=typeof Symbol&&null!=e[Symbol.iterator]||null!=e["@@iterator"])return Array.from(e)}(e)||function(e,t){if(!e)return;if("string"==typeof e)return X(e,t);var n=Object.prototype.toString.call(e).slice(8,-1);"Object"===n&&e.constructor&&(n=e.constructor.name);if("Map"===n||"Set"===n)return Array.from(e);if("Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n))return X(e,t)}(e)||function(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function X(e,t){(null==t||t>e.length)&&(t=e.length);for(var n=0,r=new Array(t);n<t;n++)r[n]=e[n];return r}function Y(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(e);t&&(r=r.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,r)}return n}function Z(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?Y(Object(n),!0).forEach((function(t){ae(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):Y(Object(n)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}function ee(e,t){for(var n=0;n<t.length;n++){var r=t[n];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(e,r.key,r)}}function te(e,t){return(te=Object.setPrototypeOf||function(e,t){return e.__proto__=t,e})(e,t)}function ne(e){var t=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Boolean.prototype.valueOf.call(Reflect.construct(Boolean,[],(function(){}))),!0}catch(e){return!1}}();return function(){var n,r=ie(e);if(t){var o=ie(this).constructor;n=Reflect.construct(r,arguments,o)}else n=r.apply(this,arguments);return re(this,n)}}function re(e,t){if(t&&("object"===z(t)||"function"==typeof t))return t;if(void 0!==t)throw new TypeError("Derived constructors may only return object or undefined");return oe(e)}function oe(e){if(void 0===e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return e}function ie(e){return(ie=Object.setPrototypeOf?Object.getPrototypeOf:function(e){return e.__proto__||Object.getPrototypeOf(e)})(e)}function ae(e,t,n){return t in e?Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}):e[t]=n,e}var ce=function(e){!function(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function");e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,writable:!0,configurable:!0}}),t&&te(e,t)}(l,e);var t,n,i,s=ne(l);function l(e){var t;return function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}(this,l),ae(oe(t=s.call(this,e)),"mounted",!0),t.state=Z(Z({},t.constructor.getInitialState()),{},{filteredOptions:[],selectedIndex:0}),t.bindNode=t.bindNode.bind(oe(t)),t.decrementSelectedIndex=t.decrementSelectedIndex.bind(oe(t)),t.incrementSelectedIndex=t.incrementSelectedIndex.bind(oe(t)),t.onAutofillChange=t.onAutofillChange.bind(oe(t)),t.updateFilteredOptions=Object(c.debounce)(t.updateFilteredOptions.bind(oe(t)),e.searchDebounceTime),t.search=t.search.bind(oe(t)),t.selectOption=t.selectOption.bind(oe(t)),t.setExpanded=t.setExpanded.bind(oe(t)),t}return t=l,i=[{key:"getInitialState",value:function(){return{isExpanded:!1,isFocused:!1,query:""}}}],(n=[{key:"bindNode",value:function(e){this.node=e}},{key:"reset",value:function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:this.getSelected(),t=this.props.multiple,n=this.constructor.getInitialState();!t&&e.length&&e[0].label&&(n.query=e[0].label),this.setState(n)}},{key:"handleFocusOutside",value:function(){this.reset()}},{key:"hasTags",value:function(){var e=this.props,t=e.multiple,n=e.selected;return!!t&&n.some((function(e){return Boolean(e.label)}))}},{key:"getSelected",value:function(){var e=this.props,t=e.multiple,n=e.options,r=e.selected;if(t||Array.isArray(r))return r;var o=n.find((function(e){return e.key===r}));return o?[o]:[]}},{key:"selectOption",value:function(e){var t=this.props,n=t.multiple,r=t.onChange,o=t.selected,i=this.state.query,a=n?[].concat(Q(o),[e]):[e];this.reset(a),Array.isArray(o)?-1===Object(c.findIndex)(o,{key:e.key})&&r(a,i):o!==e.key&&r(e.key,i)}},{key:"decrementSelectedIndex",value:function(){var e=this.state.selectedIndex,t=this.getOptions(),n=null!==e?(0===e?t.length:e)-1:t.length-1;this.setState({selectedIndex:n})}},{key:"incrementSelectedIndex",value:function(){var e=this.state.selectedIndex,t=this.getOptions(),n=null!==e?(e+1)%t.length:0;this.setState({selectedIndex:n})}},{key:"announce",value:function(e){var t=this.props.debouncedSpeak;t&&(e.length?t(Object(o.sprintf)(Object(o._n)("%d result found, use up and down arrow keys to navigate.","%d results found, use up and down arrow keys to navigate.",e.length,"funnel-builder"),e.length),"assertive"):t(Object(o.__)("No results.","funnel-builder"),"assertive"))}},{key:"getOptions",value:function(){var e=this.props,t=e.isSearchable,n=e.options,r=this.state.filteredOptions;return t?r:n}},{key:"getFilteredOptions",value:function(e,t){for(var n=this.props,r=n.excludeSelectedOptions,o=n.getSearchExpression,i=n.maxResults,a=n.onFilter,s=this.getSelected().map((function(e){return e.key})),l=[],u=o(Object(c.escapeRegExp)(t?t.trim():"")),f=u?new RegExp(u,"i"):/^$/,p=0;p<e.length;p++){var d=e[p];if(!r||!s.includes(d.key)){var b=d.keywords,h=void 0===b?[]:b;if("string"==typeof d.label&&(h=[].concat(Q(h),[d.label])),h.some((function(e){return f.test(e)}))&&(l.push(d),i&&l.length===i))break}}return a(l,t)}},{key:"setExpanded",value:function(e){this.setState({isExpanded:e})}},{key:"search",value:function(e){this.setState({query:e,isFocused:!0}),this.updateFilteredOptions(e)}},{key:"updateFilteredOptions",value:function(e){var t=this,n=this.props,r=n.hideBeforeSearch,o=n.options,i=n.onSearch,a=this.activePromise=Promise.resolve(i(o,e)).then((function(n){if(a===t.activePromise&&t.mounted){var o=null===e||e.length||r?t.getFilteredOptions(n,e):n;t.setState({selectedIndex:0,filteredOptions:o,isExpanded:Boolean(o.length)},(function(){return t.announce(o)}))}}))}},{key:"onAutofillChange",value:function(e){var t=this.props.options,n=this.getFilteredOptions(t,e.target.value);1===n.length&&this.selectOption(n[0])}},{key:"componentWillUnmount",value:function(){this.mounted=!1}},{key:"render",value:function(){var e=this.props,t=e.autofill,n=e.children,o=e.className,i=e.controlClassName,c=e.inlineTags,s=e.instanceId,l=e.isSearchable,u=e.options,f=this.state,p=f.isExpanded,d=f.isFocused,b=f.selectedIndex,h=this.hasTags(),y=(u[b]||{}).key,m=void 0===y?"":y,v=p?"bwf-select-control-listbox-".concat(s):null,g=p?"bwf-select-control-option-".concat(s,"-").concat(m):null;return Object(r.createElement)("div",{className:a()("bwf-select-control bwf-select-control-1",o,{"has-inline-tags":h&&c,"is-focused":d,"is-searchable":l}),ref:this.bindNode},t&&Object(r.createElement)("input",{onChange:this.onAutofillChange,name:t,type:"text",className:"bwf-select-control-autofill-input",tabIndex:"-1"}),n,Object(r.createElement)(V,G({},this.props,this.state,{activeId:g,className:i,hasTags:h,isExpanded:p,listboxId:v,onSearch:this.search,selected:this.getSelected(),setExpanded:this.setExpanded,updateFilteredOptions:this.updateFilteredOptions,decrementSelectedIndex:this.decrementSelectedIndex,incrementSelectedIndex:this.incrementSelectedIndex,bwfMaintainSingleTerm:this.props.bwfMaintainSingleTerm})),!c&&h&&Object(r.createElement)(F,G({},this.props,{selected:this.getSelected()})),p&&Object(r.createElement)(w,G({},this.props,this.state,{activeId:g,listboxId:v,node:this.node,onSelect:this.selectOption,onSearch:this.search,options:this.getOptions(),decrementSelectedIndex:this.decrementSelectedIndex,incrementSelectedIndex:this.incrementSelectedIndex,setExpanded:this.setExpanded})))}}])&&ee(t.prototype,n),i&&ee(t,i),l}(r.Component);ce.propTypes={autofill:l.a.string,children:l.a.node,className:l.a.string,controlClassName:l.a.string,excludeSelectedOptions:l.a.bool,onFilter:l.a.func,getSearchExpression:l.a.func,help:l.a.oneOfType([l.a.string,l.a.node]),inlineTags:l.a.bool,isSearchable:l.a.bool,label:l.a.string,onChange:l.a.func,onSearch:l.a.func,options:l.a.arrayOf(l.a.shape({isDisabled:l.a.bool,key:l.a.oneOfType([l.a.number,l.a.string]).isRequired,keywords:l.a.arrayOf(l.a.oneOfType([l.a.string,l.a.number])),label:l.a.oneOfType([l.a.string,l.a.object]),value:l.a.any})).isRequired,placeholder:l.a.string,searchDebounceTime:l.a.number,selected:l.a.oneOfType([l.a.string,l.a.arrayOf(l.a.shape({key:l.a.oneOfType([l.a.number,l.a.string]).isRequired,label:l.a.string}))]),maxResults:l.a.number,multiple:l.a.bool,showClearButton:l.a.bool,searchInputType:l.a.oneOf(["text","search","number","email","tel","url"]),hideBeforeSearch:l.a.bool,showAllOnFocus:l.a.bool,staticList:l.a.bool,bwfMaintainSingleTerm:l.a.bool};var se={autofill:null,excludeSelectedOptions:!0,getSearchExpression:c.identity,inlineTags:!1,isSearchable:!1,onChange:c.noop,onFilter:c.identity,onSearch:function(e){return Promise.resolve(e)},maxResults:0,multiple:!1,searchDebounceTime:0,searchInputType:"search",selected:[],showAllOnFocus:!1,showClearButton:!1,hideBeforeSearch:!1,staticList:!1,bwfMaintainSingleTerm:!1},le=Object(J.a)(ce,se);t.a=Object(f.compose)([u.withSpokenMessages,f.withInstanceId,u.withFocusOutside])(le)},453:function(e,t,n){}}]);