(window.webpackJsonp=window.webpackJsonp||[]).push([[0],{246:function(e,t,n){"use strict";n.d(t,"a",(function(){return ue}));var r=n(0),o=n(1),a=n(6),i=n.n(a),c=n(2),s=n(17),l=n.n(s),u=n(9),f=n(52),p=n(55),d=n(38),b=n(60);function h(e){return(h="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}function y(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function m(e,t){for(var n=0;n<t.length;n++){var r=t[n];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(e,r.key,r)}}function v(e,t){return(v=Object.setPrototypeOf||function(e,t){return e.__proto__=t,e})(e,t)}function O(e){var t=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Boolean.prototype.valueOf.call(Reflect.construct(Boolean,[],(function(){}))),!0}catch(e){return!1}}();return function(){var n,r=w(e);if(t){var o=w(this).constructor;n=Reflect.construct(r,arguments,o)}else n=r.apply(this,arguments);return g(this,n)}}function g(e,t){if(t&&("object"===h(t)||"function"==typeof t))return t;if(void 0!==t)throw new TypeError("Derived constructors may only return object or undefined");return S(e)}function S(e){if(void 0===e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return e}function w(e){return(w=Object.setPrototypeOf?Object.getPrototypeOf:function(e){return e.__proto__||Object.getPrototypeOf(e)})(e)}var j=function(e){!function(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function");e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,writable:!0,configurable:!0}}),t&&v(e,t)}(s,e);var t,n,o,a=O(s);function s(){var e;return y(this,s),(e=a.apply(this,arguments)).handleKeyDown=e.handleKeyDown.bind(S(e)),e.select=e.select.bind(S(e)),e.optionRefs={},e.listbox=Object(r.createRef)(),e}return t=s,(n=[{key:"componentDidUpdate",value:function(e){var t=this.props,n=t.options,r=t.selectedIndex;Object(c.isEqual)(n,e.options)||(this.optionRefs={}),r!==e.selectedIndex&&this.scrollToOption(r)}},{key:"getOptionRef",value:function(e){return this.optionRefs.hasOwnProperty(e)||(this.optionRefs[e]=Object(r.createRef)()),this.optionRefs[e]}},{key:"select",value:function(e){var t=this.props.onSelect;e.isDisabled||t(e)}},{key:"scrollToOption",value:function(e){var t=this.listbox.current;if(!(t.scrollHeight<=t.clientHeight)&&this.optionRefs[e]){var n=this.optionRefs[e].current,r=t.clientHeight+t.scrollTop,o=n.offsetTop+n.offsetHeight;o>r?t.scrollTop=o-t.clientHeight:n.offsetTop<t.scrollTop&&(t.scrollTop=n.offsetTop)}}},{key:"handleKeyDown",value:function(e){var t=this.props,n=t.decrementSelectedIndex,r=t.incrementSelectedIndex,o=t.options,a=t.onSearch,i=t.selectedIndex,c=t.setExpanded;if(0!==o.length)switch(e.keyCode){case p.UP:n(),e.preventDefault(),e.stopPropagation();break;case p.DOWN:r(),e.preventDefault(),e.stopPropagation();break;case p.ENTER:this.select(o[i]),e.preventDefault(),e.stopPropagation();break;case p.LEFT:case p.RIGHT:c(!1);break;case p.ESCAPE:return c(!1),void a(null);case p.TAB:this.select(o[i]),c(!1),a(null)}}},{key:"toggleKeyEvents",value:function(e){this.props.node[e?"addEventListener":"removeEventListener"]("keydown",this.handleKeyDown,!0)}},{key:"componentDidMount",value:function(){this.toggleKeyEvents(!0)}},{key:"componentWillUnmount",value:function(){this.toggleKeyEvents(!1)}},{key:"render",value:function(){var e=this,t=this.props,n=t.instanceId,o=t.listboxId,a=t.options,s=t.selectedIndex,l=t.staticList,u=t.hoverState,f=t.label,p=t.bottomFixed,h=i()("bwf-select-control__listbox",{"is-static":l,"has-label":!Object(c.isEmpty)(f),"is-bottom-fixed":p});return Object(r.createElement)(b.a,{ref:this.listbox,focusOnMount:!1,id:o,className:h,placement:"bottom"},a.map((function(t,o){return Object(r.createElement)("div",{key:o,onMouseOver:function(){u(o)},className:"".concat(void 0!==t.nameKey?"bwf-select-optgroup-wrap":"")},Object(r.createElement)("div",{className:"bwf-select-optgroup ".concat(null==t.nameKey?"bwf-hide":"")},t.nameKey),Object(r.createElement)("div",{ref:e.getOptionRef(o),key:t.key,id:"bwf-select-control__option-".concat(n,"-").concat(t.key),role:"option","aria-selected":o===s,disabled:t.isDisabled,className:i()("bwf-select-control__option",{"is-selected":o===s}),onClick:function(){return e.select(t)},tabIndex:"-1"},Object(d.decodeEntities)(t.label)))})))}}])&&m(t.prototype,n),o&&m(t,o),s}(r.Component);j.propTypes={instanceId:l.a.number,listboxId:l.a.string,node:l.a.instanceOf(Element).isRequired,onSelect:l.a.func,options:l.a.arrayOf(l.a.shape({isDisabled:l.a.bool,key:l.a.oneOfType([l.a.number,l.a.string]).isRequired,keywords:l.a.arrayOf(l.a.oneOfType([l.a.string,l.a.number])),label:l.a.oneOfType([l.a.string,l.a.object]),value:l.a.any})).isRequired,selectedIndex:l.a.number,staticList:l.a.bool};var E=j,x=n(364);function k(e){return(k="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}function I(e){return function(e){if(Array.isArray(e))return _(e)}(e)||function(e){if("undefined"!=typeof Symbol&&null!=e[Symbol.iterator]||null!=e["@@iterator"])return Array.from(e)}(e)||function(e,t){if(!e)return;if("string"==typeof e)return _(e,t);var n=Object.prototype.toString.call(e).slice(8,-1);"Object"===n&&e.constructor&&(n=e.constructor.name);if("Map"===n||"Set"===n)return Array.from(e);if("Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n))return _(e,t)}(e)||function(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function _(e,t){(null==t||t>e.length)&&(t=e.length);for(var n=0,r=new Array(t);n<t;n++)r[n]=e[n];return r}function T(e,t){for(var n=0;n<t.length;n++){var r=t[n];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(e,r.key,r)}}function R(e,t){return(R=Object.setPrototypeOf||function(e,t){return e.__proto__=t,e})(e,t)}function A(e){var t=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Boolean.prototype.valueOf.call(Reflect.construct(Boolean,[],(function(){}))),!0}catch(e){return!1}}();return function(){var n,r=N(e);if(t){var o=N(this).constructor;n=Reflect.construct(r,arguments,o)}else n=r.apply(this,arguments);return C(this,n)}}function C(e,t){if(t&&("object"===k(t)||"function"==typeof t))return t;if(void 0!==t)throw new TypeError("Derived constructors may only return object or undefined");return P(e)}function P(e){if(void 0===e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return e}function N(e){return(N=Object.setPrototypeOf?Object.getPrototypeOf:function(e){return e.__proto__||Object.getPrototypeOf(e)})(e)}var B=function(e){!function(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function");e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,writable:!0,configurable:!0}}),t&&R(e,t)}(s,e);var t,n,a,i=A(s);function s(e){var t;return function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}(this,s),(t=i.call(this,e)).removeAll=t.removeAll.bind(P(t)),t.removeResult=t.removeResult.bind(P(t)),t}return t=s,(n=[{key:"removeAll",value:function(){(0,this.props.onChange)([])}},{key:"removeResult",value:function(e){var t=this.props,n=t.selected,r=t.onChange,o=Object(c.findIndex)(n,{key:e});r([].concat(I(n.slice(0,o)),I(n.slice(o+1))))}},{key:"render",value:function(){var e=this,t=this.props,n=t.selected,a=t.showClearButton;return n.length?Object(r.createElement)(r.Fragment,null,Object(r.createElement)("div",{className:"bwf-select-control__tags"},n.map((function(t,a){if(!t.label)return null;var i=Object(o.sprintf)(Object(o.__)("%1$s (%2$s of %3$s)","funnel-builder"),t.label,a+1,n.length);return Object(r.createElement)(x.a,{key:t.key,id:t.key,label:t.label,remove:e.removeResult,screenReaderLabel:i})}))),a&&Object(r.createElement)(u.Button,{className:"bwf-select-control__clear",isLink:!0,onClick:this.removeAll},Object(r.createElement)(u.Icon,{icon:"dismiss"}),Object(r.createElement)("span",{className:"screen-reader-text"},Object(o.__)("Clear all","funnel-builder")))):null}}])&&T(t.prototype,n),a&&T(t,a),s}(r.Component);B.propTypes={onChange:l.a.func,onSelect:l.a.func,selected:l.a.arrayOf(l.a.shape({key:l.a.oneOfType([l.a.number,l.a.string]).isRequired,label:l.a.string})),showClearButton:l.a.bool};var D=B,F=n(7);function q(e){return(q="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}function K(e){return function(e){if(Array.isArray(e))return L(e)}(e)||function(e){if("undefined"!=typeof Symbol&&null!=e[Symbol.iterator]||null!=e["@@iterator"])return Array.from(e)}(e)||function(e,t){if(!e)return;if("string"==typeof e)return L(e,t);var n=Object.prototype.toString.call(e).slice(8,-1);"Object"===n&&e.constructor&&(n=e.constructor.name);if("Map"===n||"Set"===n)return Array.from(e);if("Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n))return L(e,t)}(e)||function(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function L(e,t){(null==t||t>e.length)&&(t=e.length);for(var n=0,r=new Array(t);n<t;n++)r[n]=e[n];return r}function H(e,t){for(var n=0;n<t.length;n++){var r=t[n];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(e,r.key,r)}}function M(e,t){return(M=Object.setPrototypeOf||function(e,t){return e.__proto__=t,e})(e,t)}function U(e){var t=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Boolean.prototype.valueOf.call(Reflect.construct(Boolean,[],(function(){}))),!0}catch(e){return!1}}();return function(){var n,r=V(e);if(t){var o=V(this).constructor;n=Reflect.construct(r,arguments,o)}else n=r.apply(this,arguments);return $(this,n)}}function $(e,t){if(t&&("object"===q(t)||"function"==typeof t))return t;if(void 0!==t)throw new TypeError("Derived constructors may only return object or undefined");return Q(e)}function Q(e){if(void 0===e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return e}function V(e){return(V=Object.setPrototypeOf?Object.getPrototypeOf:function(e){return e.__proto__||Object.getPrototypeOf(e)})(e)}var z=function(e){!function(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function");e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,writable:!0,configurable:!0}}),t&&M(e,t)}(s,e);var t,n,a,c=U(s);function s(e){var t;return function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}(this,s),(t=c.call(this,e)).state={isActive:!1},t.input=Object(r.createRef)(),t.updateSearch=t.updateSearch.bind(Q(t)),t.onFocus=t.onFocus.bind(Q(t)),t.onBlur=t.onBlur.bind(Q(t)),t.onKeyDown=t.onKeyDown.bind(Q(t)),t}return t=s,(n=[{key:"updateSearch",value:function(e){return function(t){e(t.target.value)}}},{key:"onFocus",value:function(e){var t=this,n=this.props,r=n.isSearchable,o=n.setExpanded,a=n.showAllOnFocus,i=n.updateSearchOptions,c=n.isExpanded;return function(n){t.setState({isActive:!0}),r&&a?(n.target.select(),i("")):r?e(n.target.value):o(!c)}}},{key:"onBlur",value:function(){var e=this.props.onBlur;"function"==typeof e&&e(),this.setState({isActive:!1})}},{key:"onKeyDown",value:function(e){var t=this.props,n=t.decrementSelectedIndex,r=t.incrementSelectedIndex,o=t.selected,a=t.onChange,i=t.query,c=t.setExpanded;p.BACKSPACE===e.keyCode&&!i&&o.length&&a(K(o.slice(0,-1))),p.DOWN===e.keyCode&&(r(),c(!0),e.preventDefault(),e.stopPropagation()),p.UP===e.keyCode&&(n(),c(!0),e.preventDefault(),e.stopPropagation())}},{key:"renderButton",value:function(){var e=this.props,t=e.multiple,n=e.selected;return t||!n.length?null:Object(r.createElement)("div",{className:"bwf-select-control__control-value"},n[0].label)}},{key:"renderInput",value:function(){var e,t,n=this.props,o=n.activeId,a=n.hasTags,i=n.inlineTags,c=n.instanceId,s=n.isExpanded,l=n.isSearchable,u=n.listboxId,f=n.onSearch,p=n.placeholder,d=n.searchInputType,b=n.disabled,h=this.state.isActive;return Object(r.createElement)("div",{className:"bwf-control-container"},Object(r.createElement)("input",{autoComplete:"off",className:"bwf-select-control__control-input",id:"bwf-select-control-".concat(c,"__control-input"),ref:this.input,type:l?d:"button",value:this.getInputValue(),placeholder:h?p:"",onChange:this.updateSearch(f),onClick:this.onFocus(f),onBlur:this.onBlur,onKeyDown:this.onKeyDown,role:"combobox","aria-autocomplete":"list","aria-expanded":s,"aria-haspopup":"true","aria-owns":u,"aria-controls":u,"aria-activedescendant":o,disabled:b,"aria-describedby":a&&i?"search-inline-input-".concat(c):null}),Object(r.createElement)("div",{className:"bwf-select-arrow-icon",onClick:this.onFocus(f)},Object(r.createElement)(F.a,{icon:s?"angle-up":"angle-down",size:12})),(null===(e=this.props.selected[0])||void 0===e?void 0:e.hint)&&Object(r.createElement)("div",{className:"bwf-select-hint"},null===(t=this.props.selected[0])||void 0===t?void 0:t.hint))}},{key:"getInputValue",value:function(){var e=this.props,t=e.inlineTags,n=e.isFocused,r=e.isSearchable,o=e.multiple,a=e.query,i=e.selected,c=i.length?i[0].label:"Select";return o||n||t?r&&n&&a?a:"":c}},{key:"render",value:function(){var e,t=this,n=this.props,a=n.className,c=n.hasTags,s=n.help,l=n.inlineTags,u=n.instanceId,f=n.isSearchable,p=n.label,d=n.query;return this.state.isActive,Object(r.createElement)("div",{className:i()("components-base-control","bwf-select-control__control",a,{empty:!d||0===d.length,"has-tags":l&&c,"with-value":null===(e=this.getInputValue())||void 0===e?void 0:e.length,"has-error":!!s}),onClick:function(){t.input.current.focus()}},f&&Object(r.createElement)(F.a,{icon:"search",size:18}),l&&Object(r.createElement)(D,this.props),Object(r.createElement)("div",{className:"components-base-control__field"},!!p&&Object(r.createElement)("label",{htmlFor:"bwf-select-control-".concat(u,"__control-input"),className:"bwf-select-controls-label"},p),this.renderInput(),l&&Object(r.createElement)("span",{id:"search-inline-input-".concat(u),className:"screen-reader-text"},Object(o.__)("Move backward for selected items","funnel-builder")),!!s&&Object(r.createElement)("p",{id:"bwf-select-control-".concat(u,"__help"),className:"components-base-control__help"},s)))}}])&&H(t.prototype,n),a&&H(t,a),s}(r.Component);z.propTypes={hasTags:l.a.bool,help:l.a.oneOfType([l.a.string,l.a.node]),inlineTags:l.a.bool,isSearchable:l.a.bool,instanceId:l.a.number,label:l.a.string,listboxId:l.a.string,onBlur:l.a.func,onChange:l.a.func,onSearch:l.a.func,placeholder:l.a.string,query:l.a.string,selected:l.a.arrayOf(l.a.shape({key:l.a.oneOfType([l.a.number,l.a.string]).isRequired,label:l.a.string})),showAllOnFocus:l.a.bool};var W=z,J=(n(451),n(123));function G(e){return(G="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}function X(){return(X=Object.assign||function(e){for(var t=1;t<arguments.length;t++){var n=arguments[t];for(var r in n)Object.prototype.hasOwnProperty.call(n,r)&&(e[r]=n[r])}return e}).apply(this,arguments)}function Y(e){return function(e){if(Array.isArray(e))return Z(e)}(e)||function(e){if("undefined"!=typeof Symbol&&null!=e[Symbol.iterator]||null!=e["@@iterator"])return Array.from(e)}(e)||function(e,t){if(!e)return;if("string"==typeof e)return Z(e,t);var n=Object.prototype.toString.call(e).slice(8,-1);"Object"===n&&e.constructor&&(n=e.constructor.name);if("Map"===n||"Set"===n)return Array.from(e);if("Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n))return Z(e,t)}(e)||function(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function Z(e,t){(null==t||t>e.length)&&(t=e.length);for(var n=0,r=new Array(t);n<t;n++)r[n]=e[n];return r}function ee(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(e);t&&(r=r.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,r)}return n}function te(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?ee(Object(n),!0).forEach((function(t){ne(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):ee(Object(n)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}function ne(e,t,n){return t in e?Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}):e[t]=n,e}function re(e,t){for(var n=0;n<t.length;n++){var r=t[n];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(e,r.key,r)}}function oe(e,t){return(oe=Object.setPrototypeOf||function(e,t){return e.__proto__=t,e})(e,t)}function ae(e){var t=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Boolean.prototype.valueOf.call(Reflect.construct(Boolean,[],(function(){}))),!0}catch(e){return!1}}();return function(){var n,r=se(e);if(t){var o=se(this).constructor;n=Reflect.construct(r,arguments,o)}else n=r.apply(this,arguments);return ie(this,n)}}function ie(e,t){if(t&&("object"===G(t)||"function"==typeof t))return t;if(void 0!==t)throw new TypeError("Derived constructors may only return object or undefined");return ce(e)}function ce(e){if(void 0===e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return e}function se(e){return(se=Object.setPrototypeOf?Object.getPrototypeOf:function(e){return e.__proto__||Object.getPrototypeOf(e)})(e)}var le={isExpanded:!1,isFocused:!1,query:""},ue=function(e){!function(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function");e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,writable:!0,configurable:!0}}),t&&oe(e,t)}(l,e);var t,n,a,s=ae(l);function l(e){var t;return function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}(this,l),(t=s.call(this,e)).state=te(te({},le),{},{searchOptions:[],selectedIndex:0}),t.bindNode=t.bindNode.bind(ce(t)),t.decrementSelectedIndex=t.decrementSelectedIndex.bind(ce(t)),t.incrementSelectedIndex=t.incrementSelectedIndex.bind(ce(t)),t.onAutofillChange=t.onAutofillChange.bind(ce(t)),t.updateSearchOptions=Object(c.debounce)(t.updateSearchOptions.bind(ce(t)),e.searchDebounceTime),t.search=t.search.bind(ce(t)),t.selectOption=t.selectOption.bind(ce(t)),t.setExpanded=t.setExpanded.bind(ce(t)),t.setHoverState=t.setHoverState.bind(ce(t)),t}return t=l,(n=[{key:"bindNode",value:function(e){this.node=e}},{key:"reset",value:function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:this.getSelected(),t=this.props.inlineTags,n=te({},le);!t&&e.length&&e[0].label&&(n.query=e[0].label),this.setState(n)}},{key:"handleFocusOutside",value:function(){this.reset()}},{key:"hasTags",value:function(){var e=this.props,t=e.inlineTags,n=e.selected;return!!t&&(Array.isArray(n)?n.some((function(e){return Boolean(e.label)})):Boolean(n))}},{key:"getSelected",value:function(){var e=this.props,t=e.multiple,n=e.options,r=e.selected;if(t||Array.isArray(r))return t&&!Array.isArray(r)?Object(c.isEmpty)(r)?[]:[r]:r;var o=n.find((function(e){return e.key===r}));return o?[o]:[]}},{key:"selectOption",value:function(e){var t=this.props,n=t.multiple,r=t.onChange,o=t.selected,a=this.state.query,i=n?[].concat(Y(o),[e]):[e];this.reset(i),n&&Array.isArray(o)?-1===Object(c.findIndex)(o,{key:e.key})&&r(i,a):o!==e.key&&r(e.key,a)}},{key:"decrementSelectedIndex",value:function(){var e=this.state.selectedIndex,t=this.getOptions(),n=null!==e?(0===e?t.length:e)-1:t.length-1;this.setState({selectedIndex:n})}},{key:"incrementSelectedIndex",value:function(){var e=this.state.selectedIndex,t=this.getOptions(),n=null!==e?(e+1)%t.length:0;this.setState({selectedIndex:n})}},{key:"setHoverState",value:function(e){this.setState({selectedIndex:e})}},{key:"announce",value:function(e){var t=this.props.debouncedSpeak;t&&(e.length?t(Object(o.sprintf)(Object(o._n)("%d result found, use up and down arrow keys to navigate.","%d results found, use up and down arrow keys to navigate.",e.length,"funnel-builder"),e.length),"assertive"):t(Object(o.__)("No results.","funnel-builder"),"assertive"))}},{key:"getOptions",value:function(){var e=this.props,t=e.isSearchable,n=e.options,r=(e.excludeSelectedOptions,this.state.searchOptions);return this.getSelected().map((function(e){return e.key})),t?r:n}},{key:"getOptionsByQuery",value:function(e,t){for(var n=this.props,r=n.getSearchExpression,o=n.maxResults,a=n.onFilter,i=[],s=r(Object(c.escapeRegExp)(t?t.trim():"")),l=s?new RegExp(s,"i"):/^$/,u=0;u<e.length;u++){var f=e[u],p=f.keywords,d=void 0===p?[]:p;if("string"==typeof f.label&&(d=[].concat(Y(d),[f.label])),d.some((function(e){return l.test(e)}))&&(i.push(f),o&&i.length===o))break}return a(i,t)}},{key:"setExpanded",value:function(e){var t=this.props,n=t.selected,r=void 0===n?"":n,o=t.options.findIndex((function(e){return"string"==typeof e?e===r:e.key===r}));this.setState({selectedIndex:0<o?o:0}),this.setState({isExpanded:e})}},{key:"search",value:function(e){var t=this,n=this.cacheSearchOptions||[],r=null===e||e.length||this.props.hideBeforeSearch?this.getOptionsByQuery(n,e):n;this.setState({query:e,isFocused:!0,selectedIndex:0,searchOptions:r},(function(){t.setState({isExpanded:Boolean(t.getOptions().length)})})),this.updateSearchOptions(e)}},{key:"updateSearchOptions",value:function(e){var t=this,n=this.props,r=n.hideBeforeSearch,o=n.options,a=n.onSearch,i=this.activePromise=Promise.resolve(a(o,e)).then((function(n){if(i===t.activePromise){t.cacheSearchOptions=n;var o=null===e||e.length||r?t.getOptionsByQuery(n,e):n;t.setState({selectedIndex:0,searchOptions:o},(function(){t.setState({isExpanded:Boolean(t.getOptions().length)}),t.announce(o)}))}}))}},{key:"onAutofillChange",value:function(e){var t=this.props.options,n=this.getOptionsByQuery(t,e.target.value);1===n.length&&this.selectOption(n[0])}},{key:"render",value:function(){var e=this.props,t=e.autofill,n=e.children,o=e.className,a=e.controlClassName,c=e.inlineTags,s=e.instanceId,l=e.isSearchable,u=e.options,f=e.multiple,p=e.disabled,d=void 0!==p&&p,b=e.label,h=this.state,y=h.isExpanded,m=h.isFocused,v=h.selectedIndex,O=this.hasTags(),g=(u[v]||{}).key,S=void 0===g?"":g,w=y?"bwf-select-control__listbox-".concat(s):null,j=y?"bwf-select-control__option-".concat(s,"-").concat(S):null;return Object(r.createElement)("div",{className:i()("bwf-select-control bwf-select-control-2",o,{"has-inline-tags":O&&c,"is-focused":m,"is-searchable":l,"is-disabled":d,"no-label":!b}),ref:this.bindNode},t&&Object(r.createElement)("input",{onChange:this.onAutofillChange,name:t,type:"text",className:"bwf-select-control__autofill-input",tabIndex:"-1",disabled:d}),n,Object(r.createElement)(W,X({},this.props,this.state,{activeId:j,className:a,hasTags:O,isExpanded:y,listboxId:w,onSearch:this.search,selected:this.getSelected(),setExpanded:this.setExpanded,updateSearchOptions:this.updateSearchOptions,decrementSelectedIndex:this.decrementSelectedIndex,incrementSelectedIndex:this.incrementSelectedIndex,disabled:d})),!c&&f&&Object(r.createElement)(D,X({},this.props,{selected:this.getSelected(),options:this.getOptions()})),y&&Object(r.createElement)(E,X({},this.props,this.state,{activeId:j,listboxId:w,node:this.node,onSelect:this.selectOption,onSearch:this.search,options:this.getOptions(),decrementSelectedIndex:this.decrementSelectedIndex,incrementSelectedIndex:this.incrementSelectedIndex,setExpanded:this.setExpanded,hoverState:this.setHoverState})))}}])&&re(t.prototype,n),a&&re(t,a),l}(r.Component);ue.propTypes={autofill:l.a.string,children:l.a.node,className:l.a.string,controlClassName:l.a.string,excludeSelectedOptions:l.a.bool,onFilter:l.a.func,getSearchExpression:l.a.func,help:l.a.oneOfType([l.a.string,l.a.node]),inlineTags:l.a.bool,isSearchable:l.a.bool,label:l.a.string,onChange:l.a.func,onSearch:l.a.func,options:l.a.arrayOf(l.a.shape({isDisabled:l.a.bool,key:l.a.oneOfType([l.a.number,l.a.string]).isRequired,keywords:l.a.arrayOf(l.a.oneOfType([l.a.string,l.a.number])),label:l.a.oneOfType([l.a.string,l.a.object]),value:l.a.any})).isRequired,placeholder:l.a.string,searchDebounceTime:l.a.number,selected:l.a.oneOfType([l.a.string,l.a.number,l.a.arrayOf(l.a.shape({key:l.a.oneOfType([l.a.number,l.a.string]).isRequired,label:l.a.string}))]),maxResults:l.a.number,multiple:l.a.bool,showClearButton:l.a.bool,searchInputType:l.a.oneOf(["text","search","number","email","tel","url"]),hideBeforeSearch:l.a.bool,showAllOnFocus:l.a.bool,staticList:l.a.bool,disabled:l.a.bool};var fe={autofill:null,excludeSelectedOptions:!0,getSearchExpression:c.identity,inlineTags:!1,isSearchable:!1,onChange:c.noop,onFilter:c.identity,onSearch:function(e){return Promise.resolve(e)},maxResults:0,multiple:!1,searchDebounceTime:0,searchInputType:"search",selected:[],showAllOnFocus:!1,showClearButton:!1,hideBeforeSearch:!1,staticList:!1},pe=Object(J.a)(ue,fe);t.b=Object(f.compose)([u.withSpokenMessages,f.withInstanceId,u.withFocusOutside])(pe)},364:function(e,t,n){"use strict";var r=n(0),o=n(1),a=n(6),i=n.n(a),c=n(9),s=n(38),l=n(17),u=n.n(l),f=n(52),p=n(60);n(376);function d(e,t){return function(e){if(Array.isArray(e))return e}(e)||function(e,t){var n=null==e?null:"undefined"!=typeof Symbol&&e[Symbol.iterator]||e["@@iterator"];if(null==n)return;var r,o,a=[],i=!0,c=!1;try{for(n=n.call(e);!(i=(r=n.next()).done)&&(a.push(r.value),!t||a.length!==t);i=!0);}catch(e){c=!0,o=e}finally{try{i||null==n.return||n.return()}finally{if(c)throw o}}return a}(e,t)||function(e,t){if(!e)return;if("string"==typeof e)return b(e,t);var n=Object.prototype.toString.call(e).slice(8,-1);"Object"===n&&e.constructor&&(n=e.constructor.name);if("Map"===n||"Set"===n)return Array.from(e);if("Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n))return b(e,t)}(e,t)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function b(e,t){(null==t||t>e.length)&&(t=e.length);for(var n=0,r=new Array(t);n<t;n++)r[n]=e[n];return r}var h=function(e){var t=e.id,n=e.instanceId,a=e.label,l=e.popoverContents,u=e.remove,f=e.screenReaderLabel,b=e.className,h=e.forceClose,y=void 0===h||h,m=e.isTagVisible;f=f||a;var v=d(Object(r.useState)(!1),2),O=v[0],g=v[1];if(Object(r.useEffect)((function(){y||g(!1)}),[y]),Object(r.useEffect)((function(){void 0!==m&&m(O)}),[O]),!a)return null;a=Object(s.decodeEntities)(a);var S=i()("bwf-tag",b,{"has-remove":!!u}),w="bwf-tag-label-".concat(n),j=Object(r.createElement)(r.Fragment,null,Object(r.createElement)("span",{className:"screen-reader-text"},f),Object(r.createElement)("span",{"aria-hidden":"true"},a));return Object(r.createElement)("span",{className:S},l?Object(r.createElement)(c.Button,{className:"bwf-tag-text",id:w,onClick:function(){return g(!0)}},j):Object(r.createElement)("span",{className:"bwf-tag-text",id:w},j),l&&O&&Object(r.createElement)("div",{className:"bwf-popover-content"},Object(r.createElement)(p.a,{onClose:function(){return g(!1)},position:"bottom"},l)),u&&Object(r.createElement)(c.Button,{className:"bwf-tag-remove",onClick:u(t),label:Object(o.sprintf)(Object(o.__)("Remove %s","funnel-builder"),a),"aria-describedby":w},Object(r.createElement)(c.Dashicon,{icon:"dismiss",size:20})))};h.propTypes={id:u.a.oneOfType([u.a.number,u.a.string]),label:u.a.oneOfType([u.a.element,u.a.string]).isRequired,popoverContents:u.a.node,remove:u.a.func,screenReaderLabel:u.a.string,forceClose:u.a.bool},t.a=Object(f.withInstanceId)(h)},376:function(e,t,n){},451:function(e,t,n){}}]);