(window.webpackJsonp=window.webpackJsonp||[]).push([[6],{271:function(e,t,r){"use strict";r.d(t,"a",(function(){return s})),r.d(t,"b",(function(){return u}));var n=r(0),o=r(17),a=r.n(o),i=["component","children"];function c(e,t){if(null==e)return{};var r,n,o=function(e,t){if(null==e)return{};var r,n,o={},a=Object.keys(e);for(n=0;n<a.length;n++)r=a[n],t.indexOf(r)>=0||(o[r]=e[r]);return o}(e,t);if(Object.getOwnPropertySymbols){var a=Object.getOwnPropertySymbols(e);for(n=0;n<a.length;n++)r=a[n],t.indexOf(r)>=0||Object.prototype.propertyIsEnumerable.call(e,r)&&(o[r]=e[r])}return o}var l=Object(n.createContext)(2);function s(e){return Object(n.createElement)(l.Consumer,null,(function(t){var r="h"+Math.min(t,6);return Object(n.createElement)(r,e)}))}function u(e){var t=e.component,r=e.children,o=c(e,i),a=t||"div";return Object(n.createElement)(l.Consumer,null,(function(e){return Object(n.createElement)(l.Provider,{value:e+1},!1===t?r:Object(n.createElement)(a,o,r))}))}u.propTypes={component:a.a.oneOfType([a.a.func,a.a.string,a.a.bool]),children:a.a.node}},312:function(e,t,r){"use strict";var n=r(0),o=r(1),a=r(6),i=r.n(a),c=r(2),l=r(17),s=r.n(l),u=r(336),f=r(108),b=r(79),p=function(e){var t=e.children;return Object(n.createElement)("div",{className:"bwf-ellipsis-menu__title"},t)};p.propTypes={children:s.a.node};var d=p,y=r(339),h=r(314),m=r(376),g=function(e){var t=e.data;return Object(n.createElement)("ul",{className:"bwf-table-summary bwf-display-flex"},t.map((function(e,t){var r=e.label,o=e.value;return Object(n.createElement)("li",{className:"bwf-table-summary-item",key:t},Object(n.createElement)("span",{className:"bwf-table-summary-value"},o),Object(n.createElement)("span",{className:"bwf-table-summary-label"},r))})))};g.propTypes={data:s.a.array};var O=g,v=(r(381),r(249));function w(e){return(w="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}function j(e){return function(e){if(Array.isArray(e))return P(e)}(e)||function(e){if("undefined"!=typeof Symbol&&null!=e[Symbol.iterator]||null!=e["@@iterator"])return Array.from(e)}(e)||function(e,t){if(!e)return;if("string"==typeof e)return P(e,t);var r=Object.prototype.toString.call(e).slice(8,-1);"Object"===r&&e.constructor&&(r=e.constructor.name);if("Map"===r||"Set"===r)return Array.from(e);if("Arguments"===r||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(r))return P(e,t)}(e)||function(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function P(e,t){(null==t||t>e.length)&&(t=e.length);for(var r=0,n=new Array(t);r<t;r++)n[r]=e[r];return n}function C(e,t){for(var r=0;r<t.length;r++){var n=t[r];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(e,n.key,n)}}function E(e,t){return(E=Object.setPrototypeOf||function(e,t){return e.__proto__=t,e})(e,t)}function S(e){var t=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Boolean.prototype.valueOf.call(Reflect.construct(Boolean,[],(function(){}))),!0}catch(e){return!1}}();return function(){var r,n=N(e);if(t){var o=N(this).constructor;r=Reflect.construct(n,arguments,o)}else r=n.apply(this,arguments);return k(this,r)}}function k(e,t){if(t&&("object"===w(t)||"function"==typeof t))return t;if(void 0!==t)throw new TypeError("Derived constructors may only return object or undefined");return _(e)}function _(e){if(void 0===e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return e}function N(e){return(N=Object.setPrototypeOf?Object.getPrototypeOf:function(e){return e.__proto__||Object.getPrototypeOf(e)})(e)}var R=function(e){!function(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function");e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,writable:!0,configurable:!0}}),t&&E(e,t)}(s,e);var t,r,a,l=S(s);function s(e){var t;!function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}(this,s);var r=(t=l.call(this,e)).getShowCols(e.headers);return t.state={showCols:r},t.onColumnToggle=t.onColumnToggle.bind(_(t)),t.onPageChange=t.onPageChange.bind(_(t)),t}return t=s,(r=[{key:"componentDidUpdate",value:function(e){var t=e.headers,r=e.query,n=this.props,o=n.headers,a=n.onColumnsChange,i=n.query,l=this.state.showCols;if(Object(c.isEqual)(o,t)||this.setState({showCols:this.getShowCols(o)}),i.orderby!==r.orderby&&!l.includes(i.orderby)){var s=l.concat(i.orderby);this.setState({showCols:s}),a(s)}}},{key:"getShowCols",value:function(e){return e.map((function(e){var t=e.key,r=e.visible;return!(void 0!==r&&!r)&&t})).filter(Boolean)}},{key:"getVisibleHeaders",value:function(){var e=this.props.headers,t=this.state.showCols;return e.filter((function(e){var r=e.key;return t.includes(r)}))}},{key:"getVisibleRows",value:function(){var e=this.props,t=e.headers,r=e.rows,n=this.state.showCols;return r.map((function(e){return t.map((function(t,r){var o=t.key;return n.includes(o)&&e[r]})).filter(Boolean)}))}},{key:"onColumnToggle",value:function(e){var t=this,r=this.props,n=r.headers,o=r.query,a=r.onQueryChange,i=r.onColumnsChange;return function(){t.setState((function(t){if(t.showCols.includes(e)){if(o.orderby===e){var r=Object(c.find)(n,{defaultSort:!0})||Object(c.first)(n)||{};a("sort")(r.key,"desc")}var l=Object(c.without)(t.showCols,e);return i(l,e),{showCols:l}}var s=[].concat(j(t.showCols),[e]);return i(s,e),{showCols:s}}))}}},{key:"onPageChange",value:function(){var e=this.props,t=e.onPageChange,r=e.onQueryChange;t&&t.apply(void 0,arguments),r&&r("paged").apply(void 0,arguments)}},{key:"render",value:function(){var e=this,t=this.props,r=t.actions,a=t.className,c=t.isLoading,l=t.onQueryChange,s=t.onSort,p=t.query,g=t.rowHeader,v=t.rowsPerPage,w=t.showMenu,j=t.summary,P=t.title,C=t.totalRows,E=t.rowClasses,S=t.emptyMessage,k=t.filterColumnIcon,_=void 0===k?"":k,N=t.extraSectionBeforefilterCol,R=t.hideHeader,T=t.footer,x=t.showPagination,I=void 0===x||x,q=this.state.showCols,B=this.props.headers,L=this.getVisibleHeaders(),H=this.getVisibleRows(),A=i()("bwf-table","bwf-analytics-card",a,{"bwf-table-placeholder":c});return Object(n.createElement)(u.a,{className:A,title:P,action:r,hideHeader:R&&"yes"==R?"yes":"no",footer:T,menu:!(!N&&!w)&&Object(n.createElement)("div",null,N,w&&Object(n.createElement)(f.a,{label:Object(o.__)("Choose which values to display","funnel-builder"),buttonIcon:_,renderContent:function(){return Object(n.createElement)(n.Fragment,null,Object(n.createElement)(d,null,Object(o.__)("Columns:","funnel-builder")),B.map((function(t){var r=t.key,o=t.label;return t.required?null:Object(n.createElement)(b.a,{checked:q.includes(r),isCheckbox:!0,isClickable:!0,key:r,onInvoke:e.onColumnToggle(r)},o)})))}})),belowHeader:!!this.props.beforeTable&&this.props.beforeTable},c?Object(n.createElement)(n.Fragment,null,Object(n.createElement)("span",{className:"screen-reader-text"},Object(o.__)("Your requested data is loading","funnel-builder")),Object(n.createElement)(m.a,{numberOfRows:v,headers:L,rowHeader:g,caption:P,query:p})):Object(n.createElement)(h.a,{rows:H,headers:L,rowHeader:g,caption:P,query:p,emptyMessage:S,onSort:s||l("sort"),rowClasses:E}),I&&!c&&Object(n.createElement)(y.a,{key:parseInt(p.paged,10)||1,page:parseInt(p.paged,10)||1,perPage:v,total:C,onPageChange:this.onPageChange,onPerPageChange:l("per_page")}),j&&Object(n.createElement)(O,{data:j}))}}])&&C(t.prototype,r),a&&C(t,a),s}(n.Component);R.propTypes={headers:s.a.arrayOf(s.a.shape({hiddenByDefault:s.a.bool,defaultSort:s.a.bool,isSortable:s.a.bool,key:s.a.string,label:s.a.oneOfType([s.a.string,s.a.node]),required:s.a.bool})),ids:s.a.arrayOf(s.a.number),isLoading:s.a.bool,onQueryChange:s.a.func,onColumnsChange:s.a.func,onSort:s.a.func,query:s.a.object,rowHeader:s.a.oneOfType([s.a.number,s.a.bool]),rows:s.a.arrayOf(s.a.arrayOf(s.a.shape({display:s.a.node,value:s.a.oneOfType([s.a.string,s.a.number,s.a.bool])}))).isRequired,rowsPerPage:s.a.number.isRequired,showMenu:s.a.bool,summary:s.a.arrayOf(s.a.shape({label:s.a.node,value:s.a.oneOfType([s.a.string,s.a.number])})),totalRows:s.a.oneOfType([s.a.number,s.a.string]).isRequired,rowClasses:s.a.arrayOf(s.a.string),showPagination:s.a.bool};var T={isLoading:!1,onQueryChange:function(){return function(){}},onColumnsChange:function(){},onSort:void 0,query:{},rowHeader:0,rows:[],showMenu:!0,rowClasses:[],showPagination:!0};t.a=Object(v.a)(R,T)},314:function(e,t,r){"use strict";var n=r(0),o=r(2),a=r(6),i=r.n(a),c=r(17),l=r.n(c),s=r(1),u=r(9),f=r(52),b=r(761),p=r(762),d=r(763),y=r(7),h=r(25),m=r(249);function g(e){return(g="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}function O(){return(O=Object.assign||function(e){for(var t=1;t<arguments.length;t++){var r=arguments[t];for(var n in r)Object.prototype.hasOwnProperty.call(r,n)&&(e[n]=r[n])}return e}).apply(this,arguments)}function v(e,t){for(var r=0;r<t.length;r++){var n=t[r];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(e,n.key,n)}}function w(e,t){return(w=Object.setPrototypeOf||function(e,t){return e.__proto__=t,e})(e,t)}function j(e){var t=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Boolean.prototype.valueOf.call(Reflect.construct(Boolean,[],(function(){}))),!0}catch(e){return!1}}();return function(){var r,n=E(e);if(t){var o=E(this).constructor;r=Reflect.construct(n,arguments,o)}else r=n.apply(this,arguments);return P(this,r)}}function P(e,t){if(t&&("object"===g(t)||"function"==typeof t))return t;if(void 0!==t)throw new TypeError("Derived constructors may only return object or undefined");return C(e)}function C(e){if(void 0===e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return e}function E(e){return(E=Object.setPrototypeOf?Object.getPrototypeOf:function(e){return e.__proto__||Object.getPrototypeOf(e)})(e)}var S=function(e){!function(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function");e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,writable:!0,configurable:!0}}),t&&w(e,t)}(l,e);var t,r,a,c=j(l);function l(e){var t;return function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}(this,l),(t=c.call(this,e)).state={tabIndex:null,isScrollableRight:!1,isScrollableLeft:!1},t.container=Object(n.createRef)(),t.sortBy=t.sortBy.bind(C(t)),t.updateTableShadow=t.updateTableShadow.bind(C(t)),t}return t=l,(r=[{key:"componentDidMount",value:function(){var e=this.container.current,t=e.scrollWidth>e.clientWidth;this.setState({tabIndex:t?"0":null}),this.updateTableShadow(),window.addEventListener("resize",this.updateTableShadow)}},{key:"componentDidUpdate",value:function(){this.updateTableShadow()}},{key:"componentWillUnmount",value:function(){window.removeEventListener("resize",this.updateTableShadow)}},{key:"sortBy",value:function(e){var t=this,r=this.props,n=r.headers,a=r.query;return function(){var r=a.orderby||Object(o.get)(Object(o.find)(n,{defaultSort:!0}),"key",!1),i=a.order||Object(o.get)(Object(o.find)(n,{key:r}),"defaultOrder","desc"),c="desc";e===r&&(c="desc"===i?"asc":"desc"),t.props.onSort(e,c)}}},{key:"updateTableShadow",value:function(){var e=this.container.current,t=this.state,r=t.isScrollableRight,n=t.isScrollableLeft,o=e.scrollWidth-e.scrollLeft<=e.offsetWidth;o&&r?this.setState({isScrollableRight:!1}):o||this.state.isScrollableRight||this.setState({isScrollableRight:!0});var a=e.scrollLeft<=0;a&&n?this.setState({isScrollableLeft:!1}):a||n||this.setState({isScrollableLeft:!0})}},{key:"render",value:function(){var e=this,t=this.props,r=t.ariaHidden,a=t.caption,c=t.classNames,l=t.headers,f=t.instanceId,m=t.query,g=t.rowHeader,v=t.rows,w=t.rowClasses,j=t.emptyMessage,P=this.state,C=P.isScrollableRight,E=P.isScrollableLeft,S=P.tabIndex,k=i()("bwf-table-table",c,{"is-scrollable-right":C,"is-scrollable-left":E,"is-empty-data":!R}),_=m.orderby||Object(o.get)(Object(o.find)(l,{defaultSort:!0}),"key",!1),N=m.order||Object(o.get)(Object(o.find)(l,{key:_}),"defaultOrder","desc"),R=!!v.length;return Object(n.createElement)("div",{className:k,ref:this.container,tabIndex:S,"aria-hidden":r,"aria-labelledby":"caption-".concat(f),role:"group",onScroll:this.updateTableShadow},Object(n.createElement)("table",{width:"100%"},Object(n.createElement)("caption",{id:"caption-".concat(f),className:"bwf-table-caption screen-reader-text"},a,"0"===S&&Object(n.createElement)("small",null,Object(s.__)("(scroll to see more)","funnel-builder"))),Object(n.createElement)("tbody",null,Object(n.createElement)("tr",null,l.map((function(t,r){var a=t.cellClassName,c=t.isSortable,l=t.isNumeric,y=t.key,m=t.label,g=t.screenReaderLabel,v=t.isPro,w="header-".concat(f,"-").concat(r),j={className:i()("bwf-table-header",a,{"is-sortable":c,"is-sorted":_===y,"is-numeric":l})};c&&(j["aria-sort"]="none",_===y&&(j["aria-sort"]="asc"===N?"ascending":"descending"));var P=_===y&&"asc"!==N?Object(s.sprintf)(Object(s.__)("Sort by %s in ascending order","funnel-builder"),g):Object(s.sprintf)(Object(s.__)("Sort by %s in descending order","funnel-builder"),g),C=Object(n.createElement)(n.Fragment,null,Object(n.createElement)("span",{"aria-hidden":Boolean(g)},m),v&&Object(n.createElement)(h.b,null),g&&Object(n.createElement)("span",{className:"screen-reader-text"},g));return Object(n.createElement)("th",O({role:"columnheader",scope:"col",key:r},j),c?Object(n.createElement)(n.Fragment,null,Object(n.createElement)(u.Button,{"aria-describedby":w,onClick:R?e.sortBy(y):o.noop},C,_===y&&"asc"===N?Object(n.createElement)(b.a,{icon:p.a}):Object(n.createElement)(b.a,{icon:d.a})),Object(n.createElement)("span",{className:"screen-reader-text",id:w},P)):C)}))),R?v.map((function(e,t){return Object(n.createElement)("tr",{key:t,className:w[t]},e.map((function(e,t){var o=l[t],a=o.cellClassName,c=(o.isLeftAligned,o.isNumeric),s=o.isPro,u=g===t,f=u?"th":"td",b=i()("bwf-table-item",a,{"is-conversion":"conversion"===l[t].key,"is-numeric":c,"is-sorted":_===l[t].key});return Object(n.createElement)(f,{scope:u?"row":null,key:t,className:b},s&&!r?"-":function(e){return e.display||null}(e))})))})):Object(n.createElement)("tr",{className:"bwf-table-empty"},Object(n.createElement)("td",{className:"bwf-table-empty-item",colSpan:l.length},Object(n.createElement)(y.a,{icon:"no-data",height:"82",width:"112"}),Object(n.createElement)("div",{className:"bwf-no-data"},Object(s.__)("No Data Available","funnel-builder")),j?Object(n.createElement)("div",{className:"bwf-info"},j):null)))))}}])&&v(t.prototype,r),a&&v(t,a),l}(n.Component);S.propTypes={ariaHidden:l.a.bool,className:l.a.string,headers:l.a.arrayOf(l.a.shape({defaultSort:l.a.bool,defaultOrder:l.a.string,isLeftAligned:l.a.bool,isNumeric:l.a.bool,isSortable:l.a.bool,key:l.a.string,label:l.a.node,required:l.a.bool,screenReaderLabel:l.a.string})),onSort:l.a.func,query:l.a.object,rows:l.a.arrayOf(l.a.arrayOf(l.a.shape({display:l.a.node,value:l.a.oneOfType([l.a.string,l.a.number,l.a.bool])}))).isRequired,rowHeader:l.a.oneOfType([l.a.number,l.a.bool]),rowClasses:l.a.arrayOf(l.a.string)};var k={ariaHidden:!1,headers:[],onSort:o.noop,query:{},rowHeader:0,rowClasses:[]};t.a=Object(f.withInstanceId)(Object(m.a)(S,k))},336:function(e,t,r){"use strict";var n=r(0),o=r(6),a=r.n(o),i=r(17),c=r.n(i),l=(r(108),r(271));r(379);function s(e){return(s="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}function u(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function f(e,t){for(var r=0;r<t.length;r++){var n=t[r];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(e,n.key,n)}}function b(e,t){return(b=Object.setPrototypeOf||function(e,t){return e.__proto__=t,e})(e,t)}function p(e){var t=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Boolean.prototype.valueOf.call(Reflect.construct(Boolean,[],(function(){}))),!0}catch(e){return!1}}();return function(){var r,n=y(e);if(t){var o=y(this).constructor;r=Reflect.construct(n,arguments,o)}else r=n.apply(this,arguments);return d(this,r)}}function d(e,t){if(t&&("object"===s(t)||"function"==typeof t))return t;if(void 0!==t)throw new TypeError("Derived constructors may only return object or undefined");return function(e){if(void 0===e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return e}(e)}function y(e){return(y=Object.setPrototypeOf?Object.getPrototypeOf:function(e){return e.__proto__||Object.getPrototypeOf(e)})(e)}var h=function(e){!function(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function");e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,writable:!0,configurable:!0}}),t&&b(e,t)}(c,e);var t,r,o,i=p(c);function c(){return u(this,c),i.apply(this,arguments)}return t=c,(r=[{key:"render",value:function(){var e=this.props,t=e.action,r=e.children,o=e.description,i=e.isInactive,c=e.menu,s=e.title,u=e.hideHeader,f=e.footer,b=a()("bwf-card",this.props.className,{"has-menu":!!c,"has-action":!!t,"is-inactive":!!i});return Object(n.createElement)("div",{className:b},u&&"no"==u&&(!!s||t||c)&&Object(n.createElement)(n.Fragment,null,Object(n.createElement)("div",{className:"bwf-card-header"},!!s&&Object(n.createElement)("div",{className:"bwf-card-title-wrapper"},Object(n.createElement)("div",{className:"bwf-card-title bwf-card-header-item"},s),o&&Object(n.createElement)("div",{className:"bwf-card-description bwf-card-header-item"},o)),t&&Object(n.createElement)("div",{className:"bwf-card-action bwf-card-header-item"},t),c&&Object(n.createElement)("div",{className:"bwf-card-menu bwf-card-header-item"},c))),Object(n.createElement)(l.b,{className:"bwf-card-body"},r),f&&Object(n.createElement)("div",{className:"bwf-card-footer"},f))}}])&&f(t.prototype,r),o&&f(t,o),c}(n.Component);h.propTypes={action:c.a.node,className:c.a.string,description:c.a.oneOfType([c.a.string,c.a.node]),isInactive:c.a.bool,title:c.a.oneOfType([c.a.string,c.a.node])};t.a=h},339:function(e,t,r){"use strict";var n=r(0),o=r(1),a=r(9),i=r(6),c=r.n(i),l=r(17),s=r.n(l),u=r(2),f=(r(357),r(7)),b=r(247),p=r(249);function d(e){return(d="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}function y(e,t){for(var r=0;r<t.length;r++){var n=t[r];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(e,n.key,n)}}function h(e,t){return(h=Object.setPrototypeOf||function(e,t){return e.__proto__=t,e})(e,t)}function m(e){var t=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Boolean.prototype.valueOf.call(Reflect.construct(Boolean,[],(function(){}))),!0}catch(e){return!1}}();return function(){var r,n=v(e);if(t){var o=v(this).constructor;r=Reflect.construct(n,arguments,o)}else r=n.apply(this,arguments);return g(this,r)}}function g(e,t){if(t&&("object"===d(t)||"function"==typeof t))return t;if(void 0!==t)throw new TypeError("Derived constructors may only return object or undefined");return O(e)}function O(e){if(void 0===e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return e}function v(e){return(v=Object.setPrototypeOf?Object.getPrototypeOf:function(e){return e.__proto__||Object.getPrototypeOf(e)})(e)}var w=[10,25,50,75,100],j=function(e){!function(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function");e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,writable:!0,configurable:!0}}),t&&h(e,t)}(s,e);var t,r,i,l=m(s);function s(e){var t;return function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}(this,s),(t=l.call(this,e)).state={inputValue:t.props.page},t.previousPage=t.previousPage.bind(O(t)),t.nextPage=t.nextPage.bind(O(t)),t.onInputChange=t.onInputChange.bind(O(t)),t.onInputBlur=t.onInputBlur.bind(O(t)),t.perPageChange=t.perPageChange.bind(O(t)),t.selectInputValue=t.selectInputValue.bind(O(t)),t}return t=s,(r=[{key:"previousPage",value:function(e){e.stopPropagation();var t=this.props,r=t.page,n=t.onPageChange;r-1<1||n(r-1,"previous")}},{key:"nextPage",value:function(e){e.stopPropagation();var t=this.props,r=t.page,n=t.onPageChange;r+1>this.pageCount||n(r+1,"next")}},{key:"perPageChange",value:function(e){var t=this.props,r=t.onPerPageChange,n=t.onPageChange,o=t.total,a=t.page;r(parseInt(e,10));var i=Math.ceil(o/parseInt(e,10));a>i&&n(i)}},{key:"onInputChange",value:function(e){this.setState({inputValue:e.target.value})}},{key:"onInputBlur",value:function(e){var t=this.props,r=t.onPageChange,n=t.page,o=parseInt(e.target.value,10);o!==n&&Number.isFinite(o)&&o>0&&this.pageCount&&this.pageCount>=o&&r(o,"goto")}},{key:"selectInputValue",value:function(e){e.target.select()}},{key:"renderPageArrows",value:function(){var e=this.props,t=e.page,r=e.showPageArrowsLabel;if(this.pageCount<=1)return null;var i=c()("bwf-pagination-link",{"is-active":t>1}),l=c()("bwf-pagination-link",{"is-active":t<this.pageCount});return Object(n.createElement)("div",{className:"bwf-pagination-page-arrows"},r&&Object(n.createElement)("span",{className:"bwf-pagination-page-arrows-label",role:"status","aria-live":"polite"},Object(o.sprintf)(Object(o.__)("Page %d of %d","funnel-builder"),t,this.pageCount)),Object(n.createElement)("div",{className:"bwf-pagination-page-arrows-buttons"},Object(n.createElement)(a.Button,{className:i,disabled:!(t>1),onClick:this.previousPage,label:Object(o.__)("Previous Page","funnel-builder")},Object(n.createElement)(f.a,{icon:"arrow-left",width:16})),Object(n.createElement)(a.Button,{className:l,disabled:!(t<this.pageCount),onClick:this.nextPage,label:Object(o.__)("Next Page","funnel-builder")},Object(n.createElement)(f.a,{icon:"arrow-right",width:16,color:"#0073AA"}))))}},{key:"renderPerPagePicker",value:function(){var e,t=w.map((function(e){return{key:e.toString(),label:e.toString()}}));return Object(n.createElement)("div",{className:"bwf-pagination-per-page-picker"},Object(n.createElement)(b.b,{selected:null===(e=this.props.perPage)||void 0===e?void 0:e.toString(),onChange:this.perPageChange,options:t,labelPosition:"side"}),Object(n.createElement)("label",{className:"bwf-pagination-per-page-picker__label"},Object(o.__)("Per Page","funnel-builder")))}},{key:"render",value:function(){var e=this.props,t=e.total,r=e.perPage,o=e.className,a=e.showPerPagePicker;this.pageCount=Math.ceil(t/r);var i=c()("bwf-pagination",o);return this.pageCount<=1?t>w[0]&&Object(n.createElement)("div",{className:i},this.renderPerPagePicker())||null:Object(n.createElement)("div",{className:i},this.renderPageArrows(),a&&this.renderPerPagePicker())}}])&&y(t.prototype,r),i&&y(t,i),s}(n.Component);j.propTypes={page:s.a.number.isRequired,onPageChange:s.a.func,perPage:s.a.number.isRequired,onPerPageChange:s.a.func,total:s.a.oneOfType([s.a.number,s.a.string]).isRequired,className:s.a.string,showPagePicker:s.a.bool,showPerPagePicker:s.a.bool,showPageArrowsLabel:s.a.bool};var P={onPageChange:u.noop,onPerPageChange:u.noop,showPagePicker:!1,showPerPagePicker:!0,showPageArrowsLabel:!0};t.a=Object(p.a)(j,P)},357:function(e,t,r){},376:function(e,t,r){"use strict";var n=r(0),o=r(2),a=r(17),i=r.n(a),c=r(314);function l(e){return(l="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}var s=["numberOfRows"];function u(){return(u=Object.assign||function(e){for(var t=1;t<arguments.length;t++){var r=arguments[t];for(var n in r)Object.prototype.hasOwnProperty.call(r,n)&&(e[n]=r[n])}return e}).apply(this,arguments)}function f(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,n)}return r}function b(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?f(Object(r),!0).forEach((function(t){p(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):f(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}function p(e,t,r){return t in e?Object.defineProperty(e,t,{value:r,enumerable:!0,configurable:!0,writable:!0}):e[t]=r,e}function d(e,t){if(null==e)return{};var r,n,o=function(e,t){if(null==e)return{};var r,n,o={},a=Object.keys(e);for(n=0;n<a.length;n++)r=a[n],t.indexOf(r)>=0||(o[r]=e[r]);return o}(e,t);if(Object.getOwnPropertySymbols){var a=Object.getOwnPropertySymbols(e);for(n=0;n<a.length;n++)r=a[n],t.indexOf(r)>=0||Object.prototype.propertyIsEnumerable.call(e,r)&&(o[r]=e[r])}return o}function y(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function h(e,t){for(var r=0;r<t.length;r++){var n=t[r];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(e,n.key,n)}}function m(e,t){return(m=Object.setPrototypeOf||function(e,t){return e.__proto__=t,e})(e,t)}function g(e){var t=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Boolean.prototype.valueOf.call(Reflect.construct(Boolean,[],(function(){}))),!0}catch(e){return!1}}();return function(){var r,n=v(e);if(t){var o=v(this).constructor;r=Reflect.construct(n,arguments,o)}else r=n.apply(this,arguments);return O(this,r)}}function O(e,t){if(t&&("object"===l(t)||"function"==typeof t))return t;if(void 0!==t)throw new TypeError("Derived constructors may only return object or undefined");return function(e){if(void 0===e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return e}(e)}function v(e){return(v=Object.setPrototypeOf?Object.getPrototypeOf:function(e){return e.__proto__||Object.getPrototypeOf(e)})(e)}var w=function(e){!function(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function");e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,writable:!0,configurable:!0}}),t&&m(e,t)}(l,e);var t,r,a,i=g(l);function l(){return y(this,l),i.apply(this,arguments)}return t=l,(r=[{key:"render",value:function(){var e=this,t=this.props,r=t.numberOfRows,a=void 0===r?5:r,i=d(t,s),l=Object(o.range)(a).map((function(){return e.props.headers.map((function(){return{display:Object(n.createElement)("span",{className:"is-placeholder"})}}))})),f=this.props.headers.map((function(e){return b(b({},e),{},{label:Object(n.createElement)("span",{className:"is-placeholder"})})}));return Object(n.createElement)(c.a,u({ariaHidden:!0,classNames:"is-loading",rows:l},i,{headers:f}))}}])&&h(t.prototype,r),a&&h(t,a),l}(n.Component);w.propTypes={query:i.a.object,headers:i.a.arrayOf(i.a.shape({hiddenByDefault:i.a.bool,defaultSort:i.a.bool,isSortable:i.a.bool,key:i.a.string,label:i.a.node,required:i.a.bool})),numberOfRows:i.a.number},t.a=w},379:function(e,t,r){},381:function(e,t,r){}}]);