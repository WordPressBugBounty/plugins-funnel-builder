(window.webpackJsonp=window.webpackJsonp||[]).push([[16],{253:function(e,t,n){"use strict";n.d(t,"a",(function(){return f}));var c=n(0),r=n(2),a=n(6),i=n.n(a),l=n(52),o=n(9),s=(n(4),n(1)),u=n(60);n(280);function b(e,t){return function(e){if(Array.isArray(e))return e}(e)||function(e,t){var n=null==e?null:"undefined"!=typeof Symbol&&e[Symbol.iterator]||e["@@iterator"];if(null==n)return;var c,r,a=[],i=!0,l=!1;try{for(n=n.call(e);!(i=(c=n.next()).done)&&(a.push(c.value),!t||a.length!==t);i=!0);}catch(e){l=!0,r=e}finally{try{i||null==n.return||n.return()}finally{if(l)throw r}}return a}(e,t)||function(e,t){if(!e)return;if("string"==typeof e)return d(e,t);var n=Object.prototype.toString.call(e).slice(8,-1);"Object"===n&&e.constructor&&(n=e.constructor.name);if("Map"===n||"Set"===n)return Array.from(e);if("Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n))return d(e,t)}(e,t)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function d(e,t){(null==t||t>e.length)&&(t=e.length);for(var n=0,c=new Array(t);n<t;n++)c[n]=e[n];return c}function f(e){var t=e.label,n=void 0===t?"":t,a=e.id,d=void 0===a?"":a,f=e.value,m=e.onChange,O=void 0===m?r.noop:m,j=e.className,v=e.style,p=void 0===v?{}:v,_=e.isHover,y=e.hoverVal,w=void 0===y?"":y,E="inspector-color-".concat(Object(l.useInstanceId)(o.ColorPicker)),h=b(Object(c.useState)(!1),2),x=h[0],g=h[1],N=b(Object(c.useState)(),2),k=N[0],C=N[1],S=b(Object(c.useState)(0),2),A=S[0],P=S[1],L=_?Object(r.isArray)(f)?f:[f,w]:f,D=function(e){if(_){var t=L;return t[A]=e.hex,void O(t,{id:d})}O(e.hex,{id:d})},M=_?L[A]:L;return Object(c.createElement)("div",{className:i()("components-color-field",j),style:p},Object(c.createElement)("div",{className:"components-color-field-label-wrap"},n&&Object(c.createElement)("label",{className:"components-label components-color-field-label",htmlFor:E},n),_&&Object(c.createElement)(o.ButtonGroup,{className:"bwf-color-picker-modes"},Object(c.createElement)(o.Button,{className:i()("is-secondary-gray is-small",{"is-selected":0===A}),onClick:function(){return P(0)}},Object(s.__)("Default","funnel-builder")),Object(c.createElement)(o.Button,{className:i()("is-secondary-gray is-small",{"is-selected":1===A}),onClick:function(){return P(1)}},Object(s.__)("Hover","funnel-builder")))),Object(c.createElement)("div",{className:"component-color-field-inner"},Object(c.createElement)("span",{className:"component-color-indicator-wrap",onClick:function(){return g(!0)}},Object(c.createElement)(o.ColorIndicator,{colorValue:M,ref:C,className:i()({"is-empty":Object(r.isEmpty)(M)})}),Object(c.createElement)("span",{className:"indicator-text"},M||"Select")),x&&Object(c.createElement)(u.a,{position:"top right",onClose:function(){return g(!1)},anchor:k},Object(c.createElement)(o.ColorPicker,{color:M,onChangeComplete:D}),Object(c.createElement)(o.Button,{isLink:!0,className:"bwf-color-picker-reset-btn",onClick:function(){return D({hex:""})}},Object(s.__)("Reset","funnel-builder")))))}},280:function(e,t,n){},308:function(e,t,n){"use strict";var c=n(0),r=n(6),a=n.n(r),i=n(4),l=n(1),o=n(9),s=n(13),u=n.n(s),b=n(2),d=n(75),f=n(310),m=n(61),O=n(275),j=n(22),v=n(7),p=(n(339),n(51)),_=n(27);function y(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var c=Object.getOwnPropertySymbols(e);t&&(c=c.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,c)}return n}function w(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?y(Object(n),!0).forEach((function(t){E(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):y(Object(n)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}function E(e,t,n){return t in e?Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}):e[t]=n,e}var h=[Object(l.__)("New Features","funnel-builder"),Object(l.__)("New Add-ons & Integrations","funnel-builder"),Object(l.__)("World Class Support","funnel-builder"),Object(l.__)("Security Improvements","funnel-builder"),Object(l.__)("Wordpress Compatibility","funnel-builder"),Object(l.__)("Marketing & Payment Compatibility","funnel-builder")],x=function(e){var t=e.onclose,n=e.onAction,r=e.isLoading,a=void 0!==r&&r;return Object(c.createElement)(o.Modal,{className:"bwf-c-s-delete-model bwf-admin-modal bwf-admin-modal-small bwf-admin-modal-no-header bwf-alert-modal bwf-deactivate-modal",isDismissible:!1,onRequestClose:t},Object(c.createElement)(c.Fragment,null,Object(c.createElement)("div",{className:"bwf-custom-header bwf-flex bwf--space-between"},Object(c.createElement)("div",{className:"bwf-modal-title"},Object(l.__)("Deactivating License","funnel-builder")),Object(c.createElement)("div",{className:"bwf-modal-close bwf-cursor-pointer",title:Object(l.__)("Close"),onClick:function(){return t()}},Object(c.createElement)(v.a,{icon:"close",size:20}))),Object(c.createElement)("div",{className:"bwf-content bwf-mt-16"},Object(c.createElement)("span",null,Object(l.__)("You will lose access to the following active license benefits:","funnel-builder")),Object(c.createElement)("br",null),Object(c.createElement)("ul",null,h.map((function(e,t){return Object(c.createElement)("li",{key:"feature-".concat(t)},Object(c.createElement)(v.a,{icon:"cross-circle",color:_.DANGER_COLOR,size:"20"}),e)})))),Object(c.createElement)("div",{className:"wf_funnel_right_align bwf-mt-24"},Object(c.createElement)(o.Button,{className:"bwf-modal-cancel-btn bwf-strong ".concat(a?"bwf-is-active":""),onClick:function(){a||n()}},Object(c.createElement)("span",{style:w({},a&&{visibility:"hidden"})},Object(l.__)("Deactivate","funnel-builder")),a&&Object(c.createElement)(p.a,{color:"#0073aa",style:{position:"absolute"}})),Object(c.createElement)(o.Button,{isPrimary:!0,className:"bwf-btn-medium",onClick:function(){return t()},disabled:a},Object(l.__)("Cancel","funnel-builder")))))},g=n(12),N=n.n(g),k=n(25);function C(e,t,n,c,r,a,i){try{var l=e[a](i),o=l.value}catch(e){return void n(e)}l.done?t(o):Promise.resolve(o).then(c,r)}function S(e,t){return function(e){if(Array.isArray(e))return e}(e)||function(e,t){var n=null==e?null:"undefined"!=typeof Symbol&&e[Symbol.iterator]||e["@@iterator"];if(null==n)return;var c,r,a=[],i=!0,l=!1;try{for(n=n.call(e);!(i=(c=n.next()).done)&&(a.push(c.value),!t||a.length!==t);i=!0);}catch(e){l=!0,r=e}finally{try{i||null==n.return||n.return()}finally{if(l)throw r}}return a}(e,t)||function(e,t){if(!e)return;if("string"==typeof e)return A(e,t);var n=Object.prototype.toString.call(e).slice(8,-1);"Object"===n&&e.constructor&&(n=e.constructor.name);if("Map"===n||"Set"===n)return Array.from(e);if("Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n))return A(e,t)}(e,t)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function A(e,t){(null==t||t>e.length)&&(t=e.length);for(var n=0,c=new Array(t);n<t;n++)c[n]=e[n];return c}t.a=function(e){var t=e.id,n=e.label,r=e.license,s=void 0===r?"":r,p=e.setSettingFieldSchema,_=e.is_manually_deactivated,y=e.activated,w=e.expired,E=function(){return Object(b.isEmpty)(s)||y||w||_?Object(b.isEmpty)(s)||!w||_?!y||Object(b.isEmpty)(s)||_?_?"deactivated":"empty":"activated":"expired":"invalid"},h=function(e){var t=e||E();switch(t){case"activated":case"expired":case"invalid":return s.licence?"xxxxxxxxxxxxxxxxxxxxxxxxxx"+s.licence.substring(26):Object(b.isEmpty)(s.api_key)||"activated"!==t?"":s.api_key;default:return""}},g=Object(k.a)().setProData,A=Object(m.a)().setLiteBar,P=Object(f.a)().updateLicense,L=S(Object(c.useState)((function(){return h()})),2),D=L[0],M=L[1],T=S(Object(c.useState)({loading:!1,status:!1}),2),I=T[0],B=T[1],F=S(Object(c.useState)(""),2),R=F[0],Y=F[1],z=S(Object(c.useState)(!1),2),G=z[0],H=z[1],W=S(Object(c.useState)(!1),2),q=W[0],J=W[1],U=S(Object(c.useState)(!1),2),V=U[0],$=U[1],K=Object(O.a)().data;Object(c.useEffect)((function(){Object(b.size)(D)?(Y(""),$(!0)):V&&!Object(b.size)(D)&&Y(Object(l.__)("Please enter licence key","funnel-builder"))}),[D]);var Q=function(){var e,n=(e=regeneratorRuntime.mark((function e(){return regeneratorRuntime.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return J(!0),e.prev=1,e.next=4,u()({path:Object(i.r)("/license/"),method:"POST",data:{name:t,action:"activated"!==E()?"activate":"deactivate",key:D}}).then((function(e){if(200===e.code){var t,n,r=e.name,a=e.license_data,i=void 0!==a&&a,o=e.license_exist,s=void 0===o?{}:o;e.pro_status,Object(b.isEmpty)(s)||(wffn_contacts_data.license_exist=s),g(e.lev),"activated"!==E()?(A(2),wffn_contacts_data.is_pro=1):(A(1),wffn_contacts_data.is_pro=0);var u=(null===(t=K.woofunnels_general_settings[0])||void 0===t||null===(n=t.tabs)||void 0===n?void 0:n.general).fields.map((function(e){return r===e.id&&(e.license=i,"activated"!==E()?(e.is_manually_deactivated=0,e.expired=0,e.activated=1,wffn_contacts_data.is_pro=1):(e.activated=0,e.is_manually_deactivated=1,wffn_contacts_data.is_pro=0)),e}));p(u),P(u),B({status:!0,loading:!1,content:Object(c.createElement)("div",{className:"bwf-t-center"},Object(c.createElement)("div",{className:"bwf-mt-8"},Object(c.createElement)(v.a,{icon:"check-circle",size:"72"})),Object(c.createElement)("div",{className:"bwf-licence-status"},"activated"!==E()?Object(l.__)("License Activated","funnel-builder"):Object(l.__)("Successfully Deactivated","funnel-builder")))}),J(!1),H(!1)}else 400===e.code&&(J(!1),B({modalTitle:Object(l.__)("License Activation Failed","funnel-builder"),status:!0,error:e.error,hide:8e3}))}));case 4:e.next=10;break;case 6:e.prev=6,e.t0=e.catch(1),J(!1),B({status:!0,error:e.t0.message});case 10:case"end":return e.stop()}}),e,null,[[1,6]])})),function(){var t=this,n=arguments;return new Promise((function(c,r){var a=e.apply(t,n);function i(e){C(a,c,r,i,l,"next",e)}function l(e){C(a,c,r,i,l,"throw",e)}i(void 0)}))});return function(){return n.apply(this,arguments)}}(),X=function(){switch(E()){case"activated":return!0;default:return!1}};Object(c.useEffect)((function(){"deactivated"===E()?M(h("deactivated")):"activated"===E()&&M(h("activated"))}),[E()]);var Z=h()===D&&Object(b.isEmpty)(R)&&Object(c.createElement)("p",{className:"hint"},Object(c.createElement)("span",{className:"bwfan-error"},e.activated?Object(l.__)("License expired on ","funnel-builder")+N()(null==s?void 0:s.expires).format(Object(i.R)()):Object(l.__)("This license needs to be reactivated. Please contact support for any assistance","funnel-builder"),". "),Object(c.createElement)("span",{className:"is-reset-text"},e.activated?Object(l.__)("Got New License? ","funnel-builder"):"",Object(c.createElement)("span",{className:"is-link",onClick:function(){return M("")}},Object(l.__)("Reset","funnel-builder"))));return Object(c.createElement)("div",{className:a()("bwf-components-licence")},n&&Object(c.createElement)("label",{className:"components-label"},n),Object(c.createElement)(c.Fragment,null,Object(c.createElement)("div",{className:"bwf-flex bwf--align-start bwf--g16"},Object(c.createElement)("div",{className:"bwf-flex bwf--align-start bwf-flex-column"},Object(c.createElement)(o.TextControl,{className:a()("bwf-license-text-input",{"has-error":Object(b.size)(R)||"expired"===E()&&h()===D}),placeholder:Object(l.__)("Enter license key here","funnel-builder"),disabled:X(),value:D,autoComplete:"off",onChange:function(e){return M(e)}}),Object(b.size)(R)?Object(c.createElement)("div",{className:"bwf-error-text"},R):null),Object(c.createElement)(j.a,{isSecondary:"empty"===E()||"deactivated"===E()||"expired"===E()||"invalid"===E(),isBusy:"activated"!==E()&&q,disabled:q,className:a()("bwf-ripple bwf-license-action-btn",{"is-inactive":"activated"===E()}),onClick:function(){return function(){return Object(b.isEmpty)(D)?($(!0),void Y(Object(l.__)("Please enter licence key","funnel-builder"))):"activated"!==E()&&D.startsWith("xxxx")?($(!0),void Y(Object(l.__)("Please enter a valid licence key","funnel-builder"))):void("activated"===E()?($(!1),H(!0)):Q())}(s,_)}},function(){switch(E()){case"activated":return Object(l.__)("Deactivate","funnel-builder");default:return Object(l.__)("Activate","funnel-builder")}}())),function(){switch(E()){case"activated":return Object(c.createElement)("p",{className:"hint"},"3"===(null==s?void 0:s.subscription_status)&&s.expires?Object(c.createElement)(c.Fragment,null,Object(l.__)("This license has been cancelled and will expire on ","funnel-builder"),N()(null==s?void 0:s.expires).format("MMMM D, YYYY")):s.expires?Object(c.createElement)(c.Fragment,null,Object(l.__)("License Expires on ","funnel-builder"),N()(null==s?void 0:s.expires).format("MMMM D, YYYY")):Object(l.__)("Lifetime license","funnel-builder"));case"empty":case"deactivated":return Object(c.createElement)("div",{className:"hint bwf-content"},Object(l.__)("Already Purchased? Access your license key in your account. ","funnel-builder"),Object(c.createElement)("a",{href:"https://myaccount.funnelkit.com/",target:"_blank",rel:"noreferrer"},Object(l.__)("Login to your FunnelKit Account","funnel-builder")));case"expired":case"invalid":return Z}}()),Object(c.createElement)(d.a,{confirmText:I.confirmText,modalTitle:I.modalTitle,confirmButtonText:I.buttonConfirm,cancelButtonText:I.buttonCancel,onConfirm:I.onConfirm,isLoading:I.loading,successMessage:I.success,errorMessage:I.error,content:I.content,onRequestClose:function(){return B({status:!1})},isOpen:I.status,autoHide:I.hide,closeIcon:!0}),G&&Object(c.createElement)(x,{isLoading:q,onAction:function(){Q()},onclose:function(){return H(!1)}}))}},339:function(e,t,n){}}]);