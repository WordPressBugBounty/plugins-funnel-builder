(window.webpackJsonp=window.webpackJsonp||[]).push([[22],{1133:function(e,t,n){},1166:function(e,t,n){"use strict";n.r(t);var r=n(0),a=n(2),c=n(9),l=n(1),o=n(7),i=n(61),s=n(4);n(1133);function b(e){return function(e){if(Array.isArray(e))return u(e)}(e)||function(e){if("undefined"!=typeof Symbol&&null!=e[Symbol.iterator]||null!=e["@@iterator"])return Array.from(e)}(e)||function(e,t){if(!e)return;if("string"==typeof e)return u(e,t);var n=Object.prototype.toString.call(e).slice(8,-1);"Object"===n&&e.constructor&&(n=e.constructor.name);if("Map"===n||"Set"===n)return Array.from(e);if("Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n))return u(e,t)}(e)||function(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function u(e,t){(null==t||t>e.length)&&(t=e.length);for(var n=0,r=new Array(t);n<t;n++)r[n]=e[n];return r}var d=function(){return Object(r.createElement)("div",{className:"bwf-onboarding-wrap bwf-onboarding-placeholder"},Object(r.createElement)("div",{className:"bwf-onboarding-header"},Object(r.createElement)("div",{className:"bwf-placeholder-temp bwf-t-center bwf-w-400 bwf-h-40"}),Object(r.createElement)("div",{className:"bwf-onboarding-subheading"},Object(r.createElement)("div",{className:"bwf-placeholder-temp bwf-t-center bwf-w-500 bwf-h-18"})),Object(r.createElement)("div",{className:"bwf-onboarding-info-cards-wrap"},b(Array(3)).map((function(e,t){return Object(r.createElement)("div",{className:"bwf-onboarding-info-card ",key:"bwf-onboarding-info-card-placeholder-".concat(t)},Object(r.createElement)("div",{className:"bwf-placeholder-temp bwf-h-40 bwf-w-40 bwf-rounded-6"}),Object(r.createElement)("div",null,Object(r.createElement)("div",{className:"bwf-placeholder-temp bwf-w-210 bwf-h-25"}),Object(r.createElement)("div",{className:"bwf-placeholder-temp bwf-w-210 bwf-h-16"}),Object(r.createElement)("div",{className:"bwf-placeholder-temp bwf-w-180 bwf-h-16 bwf-mb-5"}),Object(r.createElement)("div",{className:"bwf-placeholder-temp bwf-w-120 bwf-h-24 bwf-rounded-4"})))}))),Object(r.createElement)("div",{className:"bwf-onboarding-steps-wrap"},Object(r.createElement)("div",{className:"bwf-placeholder-temp bwf-h-24 bwf-w-120 bwf-mb-10"}),b(Array(3)).map((function(e,t){return Object(r.createElement)("div",{className:"bwf-onboarding-step",key:"bwf-onboarding-step-placeholder-".concat(t)},Object(r.createElement)("div",{className:"bwf-onboarding-step__inner"},Object(r.createElement)("div",{className:"bwf-placeholder-temp bwf-w-180 bwf-h-24"}),Object(r.createElement)("div",{className:"bwf-onboarding-step__right-content"},Object(r.createElement)("div",{className:"bwf-placeholder-temp bwf-w-90 bwf-h-12 bwf-mr-5"}),Object(r.createElement)("div",{className:"bwf-onboarding-step-progress bwf-mr-5"}),Object(r.createElement)("div",{className:"bwf-placeholder-temp bwf-w-12 bwf-h-8"}))))})))))},f=n(59),m=n(14),p=n(33),O=n(305);function w(e,t){return function(e){if(Array.isArray(e))return e}(e)||function(e,t){var n=null==e?null:"undefined"!=typeof Symbol&&e[Symbol.iterator]||e["@@iterator"];if(null==n)return;var r,a,c=[],l=!0,o=!1;try{for(n=n.call(e);!(l=(r=n.next()).done)&&(c.push(r.value),!t||c.length!==t);l=!0);}catch(e){o=!0,a=e}finally{try{l||null==n.return||n.return()}finally{if(o)throw a}}return c}(e,t)||function(e,t){if(!e)return;if("string"==typeof e)return j(e,t);var n=Object.prototype.toString.call(e).slice(8,-1);"Object"===n&&e.constructor&&(n=e.constructor.name);if("Map"===n||"Set"===n)return Array.from(e);if("Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n))return j(e,t)}(e,t)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function j(e,t){(null==t||t>e.length)&&(t=e.length);for(var n=0,r=new Array(t);n<t;n++)r[n]=e[n];return r}var _=function(e){var t=w(Object(r.useState)(""),2),n=t[0],a=t[1],o=w(Object(r.useState)(""),2),i=o[0],s=o[1],b=w(Object(r.useState)("1"),2),u=b[0],d=b[1],f=e.onConfirm;return Object(r.createElement)("div",{className:"bwf-onboarding-substep__form-wrap"},Object(r.createElement)("div",{className:"bwf-onboarding-substep__form"},Object(r.createElement)(c.TextControl,{label:Object(l.__)("Name","funnel-builder"),placeholder:Object(l.__)("Type here…","funnel-builder"),value:n,onChange:function(e){return a(e)}}),Object(r.createElement)(c.TextControl,{label:Object(l.__)("Email","funnel-builder"),placeholder:Object(l.__)("Type here…","funnel-builder"),value:i,onChange:function(e){return s(e)}}),Object(r.createElement)("div",null,Object(r.createElement)("label",null,Object(l.__)("Industry","funnel-builder")),Object(r.createElement)(O.a,{placeholder:Object(l.__)("Select","funnel-builder"),options:[{label:"Select",key:"1"}],selected:u,onChange:function(e){d(e)}}))),Object(r.createElement)("div",{className:"bwf-onboarding-substep__form-action-wrap"},Object(r.createElement)(c.Button,{isPrimary:!0,onClick:f},"Download Now"),Object(r.createElement)(c.Button,null,"Edit info")))},g=function(e){var t=e.onRequestClose;return Object(r.createElement)(c.Modal,{className:"bwf-admin-modal bwf-admin-modal-squeezy bwf-t-center bwf-checklist-download__success-modal",onRequestClose:t,isDismissible:!1},Object(r.createElement)(o.a,{icon:"checkmark-circle-filled",width:"48",height:"48",color:"#0073AA"}),Object(r.createElement)("div",{className:"bwf-checklist-download__success-modal-title"},Object(l.__)("Sucessfully Sent","funnel-builder")),Object(r.createElement)("div",{className:"bwf-checklist-download__success-modal-subtext"},Object(l.__)("We have sent a personalised business growth hacks tips to your email johndoe@gmail.com","funnel-builder")),Object(r.createElement)(c.Button,{isPrimary:!0,onClick:t},"Close"))},h=n(22),v=(n(6),n(63));Object(r.createElement)("svg",{width:"171",height:"12",viewBox:"0 0 171 12",fill:"none",xmlns:"http://www.w3.org/2000/svg"},Object(r.createElement)("path",{d:"M0.5 10.9708C10.8333 0.304142 53.3 -1.12931 80.5 4.47069C114.5 11.4707 146 14.4705 170 4.471",stroke:"#82838E",strokeDasharray:"2 2"})),Object(r.createElement)("svg",{width:"72",height:"72",viewBox:"0 0 72 72",fill:"none",xmlns:"http://www.w3.org/2000/svg"},Object(r.createElement)("circle",{cx:"36",cy:"36",r:"36",fill:"#6273E9"}),Object(r.createElement)("path",{fillRule:"evenodd",clipRule:"evenodd",d:"M32.9954 28.7209C32.9954 27.178 34.2597 26.5846 36.3536 26.5846C39.3562 26.5846 43.1489 27.4945 46.1515 29.1165V19.8198C42.8724 18.5143 39.6327 18 36.3536 18C28.3335 18 23 22.1934 23 29.1956C23 40.1143 38.0129 38.3736 38.0129 43.0813C38.0129 44.9011 36.4326 45.4945 34.2202 45.4945C30.941 45.4945 26.7532 44.1495 23.4346 42.3297V51.7451C27.1088 53.3275 30.8225 54 34.2202 54C42.4378 54 48.0874 49.9253 48.0874 42.844C48.0479 31.0549 32.9954 33.1516 32.9954 28.7209Z",fill:"white"})),n(192);var y=n(26),E=n(20),k=n(21),N=n(25);function C(e){return function(e){if(Array.isArray(e))return A(e)}(e)||function(e){if("undefined"!=typeof Symbol&&null!=e[Symbol.iterator]||null!=e["@@iterator"])return Array.from(e)}(e)||S(e)||function(){throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function x(e,t){return function(e){if(Array.isArray(e))return e}(e)||function(e,t){var n=null==e?null:"undefined"!=typeof Symbol&&e[Symbol.iterator]||e["@@iterator"];if(null==n)return;var r,a,c=[],l=!0,o=!1;try{for(n=n.call(e);!(l=(r=n.next()).done)&&(c.push(r.value),!t||c.length!==t);l=!0);}catch(e){o=!0,a=e}finally{try{l||null==n.return||n.return()}finally{if(o)throw a}}return c}(e,t)||S(e,t)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function S(e,t){if(e){if("string"==typeof e)return A(e,t);var n=Object.prototype.toString.call(e).slice(8,-1);return"Object"===n&&e.constructor&&(n=e.constructor.name),"Map"===n||"Set"===n?Array.from(e):"Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)?A(e,t):void 0}}function A(e,t){(null==t||t>e.length)&&(t=e.length);for(var n=0,r=new Array(t);n<t;n++)r[n]=e[n];return r}var T=[{icon:"messaging",buttonText:Object(l.__)("Send Query","funnel-builder"),className:"bwf-onboarding-info-card__green",text1:Object(l.__)("Need Dedicated Support?","funnel-builder"),text2:Object(l.__)("Our passionate and friendly support team is here to help.","funnel-builder"),link:"https://funnelkit.com/support/?utm_source=WordPress&utm_medium=Setup+Support+Card&utm_campaign=Lite+Plugin"},{icon:"community-2",buttonText:Object(l.__)("Join Our Community","funnel-builder"),className:"bwf-onboarding-info-card__yellow",text1:Object(l.__)("Funnel Builder Community","funnel-builder"),text2:Object(l.__)("Join our community of helpful folks. Get feedback and share wins.","funnel-builder"),link:"https://www.facebook.com/groups/233743063908243"},{icon:"guide",buttonText:Object(l.__)("Learn More","funnel-builder"),className:"bwf-onboarding-info-card__purple",text1:Object(l.__)("Explore Resources","funnel-builder"),text2:Object(l.__)("Explore our extensive documentation, blogs, and video tutorials.","funnel-builder"),link:"https://funnelkit.com/documentation/?utm_source=WordPress&utm_medium=Setup+Doc+Card&utm_campaign=Lite+Plugin"}],I=[{id:2,title:Object(l.__)("Optimise Checkout","funnel-builder"),substeps:[{title:Object(l.__)("Create a Conversion Optimised Checkout","funnel-builder"),subText:Object(l.__)("Pick a template, add a checkout and thank you page. Optionally mark checkout as Global Checkout.","funnel-builder"),buttonText:Object(l.__)("Create","funnel-builder"),key:"is_checkout",status:!0}]},{id:3,title:Object(l.__)("Increase Average Order Value ","funnel-builder"),substeps:[{title:Object(l.__)("Add Order Bumps ","funnel-builder"),subText:Object(l.__)("Recommend cross-sells on the checkout page, improve product discoverability and increase average value.","funnel-builder"),buttonText:Object(l.__)("Add","funnel-builder"),pro:!0,key:"is_orderbump",status:!1},{title:Object(l.__)("Add One Click Upsells","funnel-builder"),subText:Object(l.__)("Increase 10-15% order value with post-purchase one-click offers and let users add products with a single click.","funnel-builder"),buttonText:Object(l.__)("Add","funnel-builder"),pro:!0,key:"is_upsells",status:!1}]},{id:4,title:Object(l.__)("Improve Ads Performance","funnel-builder"),substeps:[{title:Object(l.__)("Enable Pixel Tracking","funnel-builder"),subText:Object(l.__)("Send data to 8 popular ad networks such as Facebook, Google Analytics and get maximum ROI on your ads","funnel-builder"),buttonText:Object(l.__)("Go to Settings","funnel-builder"),status:!1,key:"tracking"}]},{id:5,title:Object(l.__)("Recover Lost Revenue & Increase Repeat Purchases","funnel-builder"),substeps:[{title:Object(l.__)("Install Automations","funnel-builder"),subText:Object(l.__)("Create Abandoned Cart Recovery and Create Post Purchase Follow-up to generate more revenue.","funnel-builder"),buttonText:Object(l.__)("Install","funnel-builder"),status:!1,key:"automations"}]}],R=(t.default=function(){var e=Object(k.a)().isProFeatureLocked,t=x(Object(r.useState)([]),2),n=t[0],s=t[1],b=x(Object(r.useState)(!1),2),u=b[0],O=b[1],w=x(Object(r.useState)(!1),2),j=w[0],S=w[1],A=x(Object(r.useState)(!1),2),P=(A[0],A[1],x(Object(r.useState)(!1),2)),B=(P[0],P[1],Object(i.a)()),L=B.setPageHeader,M=B.setL2Nav,F=B.setActiveMultiple,U=Object(p.a)().getStatus,G=(Object(y.a)().setSiteOptions,Object(E.a)().siteOptions,Object(k.b)().showSnackbar,U()),D=G.stripe,q=(D=void 0===D?{}:D).status,z=void 0===q?"not_installed":q;Object(r.useEffect)((function(){L(""),M(""),F({leftNav:"setup"})}),[]);var J=!e,K=function(e){var t=e.substeps.map((function(e){return e.key})),n=0;return t.forEach((function(t){if(t){var r,a=e.substeps.find((function(e){return e.key===t}));"stripe"===t&&"connected"===(null===(r=G[t])||void 0===r?void 0:r.status)?n++:!G[t]||!J&&a.pro||n++}})),0===n?"0%":n/e.substeps.length*100+"%"},V={id:1,title:Object(l.__)("Setup Stripe Gateway","funnel-builder"),substeps:[{title:Object(l.__)("Onboarding Process","funnel-builder"),subText:Object(l.__)("Set up to get deeper integration with FunnelKit, Apple/Google Pay Express checkout, upsells on zero order total and more.","funnel-builder"),key:"stripe",status:!0}]};return Object(r.createElement)(r.Fragment,null,G.isLoading||!G?Object(r.createElement)(d,null):Object(r.createElement)("div",{className:"bwf-onboarding-wrap"},Object(r.createElement)("div",{className:"bwf-onboarding-header"},Object(r.createElement)("div",{className:"bwf-onboarding-heading"},Object(l.__)("FunnelKit Setup Guide","funnel-builder")),Object(r.createElement)("div",{className:"bwf-onboarding-subheading"},Object(l.__)("Complete the setup to unlock the full potential of FunnelKit and maximize your revenue","funnel-builder"))),Object(r.createElement)("div",{className:"bwf-onboarding-steps-wrap"},"connected"!==z&&Object(r.createElement)("div",{className:"bwf-onboarding-step"},Object(r.createElement)("div",{className:"bwf-onboarding-step__inner",role:"button",tabIndex:"-1"},Object(r.createElement)("div",{className:"bwf-onboarding-step__title"},Object(r.createElement)("div",{className:"bwf-onboarding-step__index"},"1"),Object(r.createElement)("div",null,Object(r.createElement)("div",{className:"bwf-onboarding-step__title-inner"},Object(r.createElement)("div",null,Object(l.__)("Install stripe gateway by Funnelkit","funnel-builder")),Object(r.createElement)("div",{className:"bwf-tags bwf-tag-orange"},Object(l.__)("Recommended","funnel-builder"))),Object(r.createElement)("div",{className:"bwf-onboarding-step__subtext"},Object(l.__)("A Stripe gateway plugin that gets funnel builder. Under two min setup to get features such as Apple/Google Pay, deeper compatibility with FunnelKit including upsells on even zero order total.","funnel-builder")))),Object(r.createElement)(v.a,null))),[].concat(C("connected"===z?[V]:[]),I).map((function(e,t){return Object(r.createElement)("div",{className:"bwf-onboarding-step",key:"bwf-onboarding-step-".concat(t)},Object(r.createElement)("div",{className:"bwf-onboarding-step__inner",onClick:function(){return t=e.id,void s((function(e){return e.includes(t)?e.filter((function(e){return e!==t})):[].concat(C(e),[t])}));var t},role:"button",tabIndex:"-1"},Object(r.createElement)("div",{className:"bwf-onboarding-step__title"},Object(r.createElement)("div",{className:"bwf-onboarding-step__index"},e.id),e.title,e.pro&&!J&&Object(r.createElement)(N.b,null)),Object(r.createElement)("div",{className:"bwf-onboarding-step__right-content"},Object(r.createElement)("div",{className:"bwf-onboarding-step-progress-percent"},K(e),"100%"===K(e)?Object(l.__)(" Completed","funnel-builder"):Object(l.__)(" Complete","funnel-builder")),Object(r.createElement)("div",{className:"bwf-onboarding-step-progress"},Object(r.createElement)("div",{className:"bwf-onboarding-step-progress__inner",style:{width:K(e)}})),Object(r.createElement)("div",null,Object(r.createElement)(c.Button,{className:"bwf-onboarding-step-icon"},Object(r.createElement)(o.a,{icon:n.includes(e.id)?"angle-up":"angle-down",width:"16",height:"16",color:"#353030"}))))),Object(r.createElement)("div",{className:"bwf-onboarding-substep-wrap"},n.includes(e.id)&&e.substeps.map((function(e,n){return"download_checklist"===e.key?Object(r.createElement)(_,{key:"bwf-onboarding-substep-".concat(t).concat(n),onConfirm:function(){return S(!0)}}):Object(r.createElement)("div",{className:"bwf-onboarding-substep",key:"bwf-onboarding-substep-".concat(t).concat(n)},Object(r.createElement)("div",null,Object(r.createElement)("div",{className:"bwf-onboarding-step__title"},e.title),Object(r.createElement)("div",{className:"bwf-onboarding-step__subtext"},e.subText)),Object(r.createElement)("div",null,!J&&e.pro||!G[e.key]?e.buttonText&&Object(r.createElement)(c.Button,{isPrimary:!0,className:"bwf-onboarding-substep__action-btn bwf-no-ripple",onClick:function(){return function(e){e.pro&&!J?O(e.key):"is_orderbump"===e.key||"is_checkout"===e.key||"is_upsells"===e.key?!Object(a.isArray)(G.funnels)||G.funnels.length>1||0===G.funnels.length?Object(m.k)({},"/funnels"):Object(m.k)({},"/funnels/".concat(G.funnels[0])):"tracking"===e.key?Object(m.k)({},"/settings/funnelkit_pixel_tracking/facebook_pixel"):"automations"===e.key&&Object(m.k)({},"/automations")}(e)}},!J&&e.pro&&Object(r.createElement)(o.a,{icon:"king",size:"20",color:"#ffffff"}),e.buttonText):Object(r.createElement)(o.a,{icon:"checkmark-circle-filled",width:"28",height:"28",color:h.BRAND_COLOR})))}))))}))),Object(r.createElement)("div",{className:"bwf-onboarding-info-cards-wrap bwf-mt-12"},T.map((function(e,t){return Object(r.createElement)("div",{className:"bwf-onboarding-info-card "+e.className,key:"bwf-onboarding-info-card-".concat(t),onClick:function(){e.link&&window.open(e.link,"__blank")}},Object(r.createElement)("div",null,Object(r.createElement)("div",{className:"bwf-onboarding-info-card-icon"},Object(r.createElement)(o.a,{icon:e.icon,height:"36",width:"36"}))),Object(r.createElement)("div",{className:"bwf-onboarding-info-card__content"},Object(r.createElement)("div",{className:"bwf-onboarding-info-card__text-1"},e.text1),Object(r.createElement)("div",{className:"bwf-onboarding-info-card__text-2"},e.text2),Object(r.createElement)(c.Button,{className:"bwf-onboarding-info-card__button"},e.buttonText)))})))),!Object(a.isEmpty)(u)&&Object(r.createElement)(f.a,{isOpen:!Object(a.isEmpty)(u),onRequestClose:function(){return O(!1)},modalContent:R(u)}),j&&Object(r.createElement)(g,{onRequestClose:function(){return S(!1)}}),!1)},function(e){switch(e){case"is_orderbump":return{title:Object(l.__)("Order Bump","funnel-builder"),image:Object(s.L)()+"order-bump.png",content:{benefits:[Object(l.__)("Boost your sales effortlessly with the Order Bumps. Let users add products with just one click on checkout page.","funnel-builder")]},proLink:Object(s.M)(["Onboarding","Order","Bump","Upgrade","Modal"])};case"is_upsells":return{title:Object(l.__)("One Click Upsells","funnel-builder"),image:Object(s.L)()+"upsell.png",content:{benefits:[Object(l.__)("Grab buyer's attention and make post purchase One Click Upsells based on items purchased. Let them accept offers with a single click.","funnel-builder")]},proLink:Object(s.M)(["Onboarding","One","Click","Upsell","Upgrade","Modal"])};default:return{}}})},380:function(e,t,n){"use strict";var r=n(0),a=n(1),c=n(6),l=n.n(c),o=n(9),i=n(38),s=n(17),b=n.n(s),u=n(52),d=n(60);n(417);function f(e,t){return function(e){if(Array.isArray(e))return e}(e)||function(e,t){var n=null==e?null:"undefined"!=typeof Symbol&&e[Symbol.iterator]||e["@@iterator"];if(null==n)return;var r,a,c=[],l=!0,o=!1;try{for(n=n.call(e);!(l=(r=n.next()).done)&&(c.push(r.value),!t||c.length!==t);l=!0);}catch(e){o=!0,a=e}finally{try{l||null==n.return||n.return()}finally{if(o)throw a}}return c}(e,t)||function(e,t){if(!e)return;if("string"==typeof e)return m(e,t);var n=Object.prototype.toString.call(e).slice(8,-1);"Object"===n&&e.constructor&&(n=e.constructor.name);if("Map"===n||"Set"===n)return Array.from(e);if("Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n))return m(e,t)}(e,t)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function m(e,t){(null==t||t>e.length)&&(t=e.length);for(var n=0,r=new Array(t);n<t;n++)r[n]=e[n];return r}var p=function(e){var t=e.id,n=e.instanceId,c=e.label,s=e.popoverContents,b=e.remove,u=e.screenReaderLabel,m=e.className,p=e.forceClose,O=void 0===p||p,w=e.isTagVisible;u=u||c;var j=f(Object(r.useState)(!1),2),_=j[0],g=j[1];if(Object(r.useEffect)((function(){O||g(!1)}),[O]),Object(r.useEffect)((function(){void 0!==w&&w(_)}),[_]),!c)return null;c=Object(i.decodeEntities)(c);var h=l()("bwf-tag",m,{"has-remove":!!b}),v="bwf-tag-label-".concat(n),y=Object(r.createElement)(r.Fragment,null,Object(r.createElement)("span",{className:"screen-reader-text"},u),Object(r.createElement)("span",{"aria-hidden":"true"},c));return Object(r.createElement)("span",{className:h},s?Object(r.createElement)(o.Button,{className:"bwf-tag-text",id:v,onClick:function(){return g(!0)}},y):Object(r.createElement)("span",{className:"bwf-tag-text",id:v},y),s&&_&&Object(r.createElement)("div",{className:"bwf-popover-content"},Object(r.createElement)(d.a,{onClose:function(){return g(!1)},position:"bottom"},s)),b&&Object(r.createElement)(o.Button,{className:"bwf-tag-remove",onClick:b(t),label:Object(a.sprintf)(Object(a.__)("Remove %s","funnel-builder"),c),"aria-describedby":v},Object(r.createElement)(o.Dashicon,{icon:"dismiss",size:20})))};p.propTypes={id:b.a.oneOfType([b.a.number,b.a.string]),label:b.a.oneOfType([b.a.element,b.a.string]).isRequired,popoverContents:b.a.node,remove:b.a.func,screenReaderLabel:b.a.string,forceClose:b.a.bool},t.a=Object(u.withInstanceId)(p)},417:function(e,t,n){}}]);