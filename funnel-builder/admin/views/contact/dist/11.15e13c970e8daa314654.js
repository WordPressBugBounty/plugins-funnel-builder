(window.webpackJsonp=window.webpackJsonp||[]).push([[11,23],{400:function(e,t,n){"use strict";n.r(t);var r=n(0),c=n(119),o=n(2),l=n(1),u=n(326),a=n(257),i=n(76),s=n(4),p=n(14),f=function(e){var t=Object(r.useRef)(!0),n=Object(a.a)(),c=n.getTemplates,l=n.getActiveBuilder,u=n.getActiveGroup,i=Object(o.isEmpty)(c()),s=u(),p=l();Object(r.useEffect)((function(){var n,r;t.current||!e.current||i||(r=null===(n=e.current)||void 0===n?void 0:n.getClientRects()[0].top)&&e.current&&r<252.86&&window.scrollTo(0,60)}),[i,s,p]),Object(r.useEffect)((function(){t.current&&!Object(o.isEmpty)(p)&&(window.scrollTo(0,0),t.current=!1)}),[p])},b=n(21),d=n(270);function m(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(e);t&&(r=r.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,r)}return n}function O(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?m(Object(n),!0).forEach((function(t){v(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):m(Object(n)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}function v(e,t,n){return t in e?Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}):e[t]=n,e}t.default=function(e){var t=Object(b.a)("ul").isProFeatureLocked,n=Object(a.a)(),m=n.getTemplates,v=n.getBuilders,g=(n.getFilters,n.getDefaultBuilder),j=n.templateLoading,h=n.getActiveBuilder,_=n.getActiveSubFilter,w=n.getActiveGroup,y=n.getCurrentTemplate,k=Object(i.a)().setActiveGroup,E=e.onImportComplete,P=e.isStoreCheckout,S=void 0!==P&&P,C=e.scratchConfirmModalData,F=e.storeCheckoutId,T=e.isStoreChekoutExists,N=void 0!==T&&T,A=e.templateListNotice,D=e.showImportNotice,I=e.urlDependency,L=void 0===I||I,x=e.showEditors,B=void 0===x||x,R=e.showTemplatesNav,G=void 0===R||R,z=e.showBuildFromScratch,J=void 0===z||z,M=e.showSelectedTemplates,U=void 0!==M&&M,q=e.previewCustomizeEnabled,W=void 0!==q&&q,K=e.hideTabs,Y=void 0===K?[]:K,$=Object(p.i)().importType,H=m(),Q=v(),V=JSON.parse(JSON.stringify(Q)),X=g(),Z=h(),ee=j(),te=_(),ne=y(),re=Object(c.useParams)(),ce=re.id,oe=void 0===ce?0:ce,le=re.filter,ue=void 0===le?"funnel":le,ae=re.builder,ie=re.templateId,se=re.step,pe=Object(r.useRef)();f(!Object(s.cb)()&&pe);var fe,be=L?ie:ne.id,de=L?se:ne.activeStep,me=S&&F?F:oe,Oe=w(),ve=me||S||!L?Oe:ue,ge={funnel:Object(l.__)("All","funnel-builder"),wc_checkout:Object(l.__)("Checkout","funnel-builder"),landing:Object(l.__)("Sales Funnels","funnel-builder"),optin:Object(l.__)("Lead Funnels","funnel-builder")};((me||N||Y.includes("wc_checkout"))&&delete ge.wc_checkout,N&&(ge.upsell&&delete ge.upsell,me||(ge.wc_checkout=Object(l.__)("Checkout","funnel-builder"))),!t||me||Y.includes("upsell")||(ge.upsell=Object(l.__)("One Click Upsell","funnel-builder")),V&&V.wc_checkout&&V.wc_checkout.customizer&&delete V.wc_checkout.customizer,"funnel"===$)&&(null===(fe=V[ve])||void 0===fe||delete fe.wp_editor);Object(r.useEffect)((function(){return function(){k("funnel")}}),[]);var je=function(){var e=ae||X;return Object(o.isEmpty)(Z)||(e=Z),V&&V[ve]&&!V[ve][e]&&(e=V[ve][X]?X:"elementor"),e},he=["1","2","3","inline","popup"],_e=function(e,t){return("wc_checkout"===t||"optin"===t)&&te&&he.includes(te)?H[t][Z][e].group_type[te]:e},we={landing:"sp",optin:"op",optin_ty:"op-confirmed",wc_checkout:"checkouts",upsell:"offer",wc_thankyou:"order-confirmed"},ye=function(e,t,n){var r=we.hasOwnProperty(e)?we[e]:"";return"oxy"===n&&(n="oxygen"),("customizer"===n||"wc_checkout"===e&&"wp_editor"===n)&&(n="elementor"),"https://templates-".concat(n,".funnelswp.com/").concat(r,"/").concat(t.prevslug,"/")},ke=Object(s.cb)()?"/store-checkout":me?"/funnels/".concat(me):"/templates";return Object(r.createElement)(r.Fragment,null,Object(r.createElement)(u.a,{filters:d.e,templates:H,activeBuilder:je(),templateCards:function(){if(Object(o.isEmpty)(H)||Object(o.isEmpty)([ve]))return{};var e,t=je(),n={};if("upsell"===ve)n=null===(e=H[ve])||void 0===e?void 0:e[t];else if("funnel"===ve){var r=H[ve][t];for(var c in r){var l,u=r[c];if(r[c].hasOwnProperty("group")||U&&!U.includes(u.name))if(!(u.group.includes("sales")||u.group.includes("optin")||null!==(l=r[c])&&void 0!==l&&l.build_from_scratch))continue;n[c]=r[c]}}else for(var a in H.funnel[t]){var i=H.funnel[t][a];(!U||U&&U.includes(i.name))&&("landing"===ve&&i.group.includes("sales")||"optin"===ve&&i.group.includes("optin")||"wc_checkout"===ve&&i.group.includes("wc_checkout"))&&(n[a]=i)}return n}(),editors:V,defaultBuilder:X,isTemplateLoading:ee,activeTemplateGroup:ve,templateGroup:ge,previewData:function(){if(Object(o.isEmpty)(H)||!be)return{};var e={success:!0},t=[],n=[];try{var r=je();if("upsell"===ve){t=[];var c=r;for(var u in"oxy"===c&&(c="oxygen"),"customizer"===c&&(c="elementor"),H[ve][r]){var a=H[ve][r][u];if(!a.build_from_scratch){var i={};i.id=u,i.funnel=u,i.slug=u,i.name=a.name,i.thumbnail=a.thumbnail,i.template=be,i.pro=a.pro,i.preview="https://templates-".concat(c,".funnelswp.com/offer/").concat(null==a?void 0:a.prevslug,"/"),i.group_type={},i.type="upsell",t.push(i),n.push({id:u,preview:"https://templates-".concat(c,".funnelswp.com/offer/").concat(null==a?void 0:a.prevslug,"/")})}}e.activeTemplate={id:be,thumbnail:be,preview:"https://templates-".concat(c,".funnelswp.com/offer/").concat(H[ve][r][be].prevslug,"/"),slug:be,prevId:be,name:H[ve][r][be].name,pro:H[ve][r][be].pro,tempPro:H[ve][r][be].pro,group:"upsell",type:"upsell"},e.thumbnails=t,e.previews=n}else{t=[];var s="funnel";switch(ve){case"optin":s="optin";break;case"landing":s="sales";break;case"wc_checkout":s="checkout"}var p=H.funnel[r][be];t=p.steps?p.steps.map((function(e){var t=H[e.type][r][_e(e.slug,e.type)];if(void 0===t)throw new Error("Step template not found with slug "+_e(e.slug,e.type));var c="";switch(e.type){case"landing":c=Object(l.__)("Sales Page","funnel-builder");break;case"wc_checkout":c=Object(l.__)("Checkout Page","funnel-builder");break;case"upsell":c=Object(l.__)("One Click Upsell","funnel-builder");break;case"wc_thankyou":c=Object(l.__)("Thank You Page","funnel-builder");break;case"optin":c=Object(l.__)("Optin Page","funnel-builder");break;case"optin_ty":c=Object(l.__)("Optin Confirmation Page","funnel-builder")}t.group_type?Object.values(t.group_type).forEach((function(t){var c=H[e.type][r][t];n.push({id:"".concat(e.type,"-").concat(c.prevslug),preview:ye(e.type,c,r)})})):n.push({id:"".concat(e.type,"-").concat(t.prevslug),preview:ye(e.type,t,r)});return{id:"".concat(e.type,"-").concat(be),slug:_e(e.slug,e.type),funnel:be,step:e.type,name:c,thumbnail:null==t?void 0:t.thumbnail,pro:null==t?void 0:t.pro,type:"funnel",group:s,group_type:t.group_type||{},preview:ye(e.type,t,r),filter:t.group?t.group[0]:""}})):[],e.thumbnails=t,e.previews=n;var f=p?p.steps.filter((function(e){return e.type===de})):[],b=H[f[0].type][r][_e(f[0].slug,f[0].type)];e.activeTemplate={thumbnail:"".concat(de,"-").concat(be),prevId:"".concat(de,"-").concat(b.prevslug),preview:ye(f[0].type,b,r),slug:be,name:H.funnel[r][be].name,pro:H.funnel[r][be].pro,tempPro:H.funnel[r][be].pro,group:"funnel",filters:!!b.group_type&&Object.keys(b.group_type),type:de,funnelGroup:p.group,filter:"wc_checkout"===de?b.no_steps:b.group?b.group[0]:""}}}catch(t){console.log("Failed to prepare template preview data:",t),e.success=!0}return e},funnelId:me,onImportComplete:E,templateFilterCheck:function(e){return!me||"funnel"===$||!1===te||!1===te||!(!me||!e.group)&&(Array.isArray(e.group)?e.group.includes("inline"===te?"optin":te):te===e.group)},activeFilter:te,isStoreCheckout:S,parentRoute:ke,showTemplatesNav:G,showBuildFromScratch:!!J&&("funnel"!==$||S&&!me),funnelTitleModalData:O({},S&&{title:Object(l.__)("Store Checkout","funnel-builder")}),scratchConfirmModalData:C,importApiPrefix:S?"store-checkout":"funnels",templateListNotice:A,showImportNotice:D,urlDependency:L,showEditors:B,previewCustomizeEnabled:W,listRef:pe}))}},603:function(e,t,n){"use strict";var r=n(0),c=n(2),o=n(1),l=n(13),u=n.n(l),a=n(9),i=n(4),s=n(395),p=(n(939),n(7)),f=(n(940),n(6)),b=n.n(f),d=n(22),m=function(e){var t=e.value,n=void 0===t?0:t,c=e.content,o=void 0===c?"":c,l=e.isLoading,u=e.error;return Object(r.createElement)("div",{className:"bwf-circular-progress-bar"},Object(r.createElement)("div",{className:b()("bwf-circular-progress-bar__circle",{"is-loading":l,"is-complete":!l&&!u,"is-danger":u}),role:"progressbar","aria-valuenow":n,"aria-valuemin":"0","aria-valuemax":"100",style:{"--value":n,"--pg-bar-color":u?d.DANGER_COLOR:d.BLUE_BASE}}),Object(r.createElement)("div",{className:"bwf-circular-progress-bar__content"},u?Object(r.createElement)(p.a,{icon:"cross",size:"72",color:"#ffffff"}):l?o:Object(r.createElement)(p.a,{icon:"tick",size:"72",color:"#ffffff"})))},O=n(21),v=n(47),g=n(249),j=n(380),h=n(26),_=n(23);function w(e,t){var n=Object.keys(e);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(e);t&&(r=r.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),n.push.apply(n,r)}return n}function y(e){for(var t=1;t<arguments.length;t++){var n=null!=arguments[t]?arguments[t]:{};t%2?w(Object(n),!0).forEach((function(t){k(e,t,n[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(n)):w(Object(n)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(n,t))}))}return e}function k(e,t,n){return t in e?Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}):e[t]=n,e}function E(e,t){return function(e){if(Array.isArray(e))return e}(e)||function(e,t){var n=null==e?null:"undefined"!=typeof Symbol&&e[Symbol.iterator]||e["@@iterator"];if(null==n)return;var r,c,o=[],l=!0,u=!1;try{for(n=n.call(e);!(l=(r=n.next()).done)&&(o.push(r.value),!t||o.length!==t);l=!0);}catch(e){u=!0,c=e}finally{try{l||null==n.return||n.return()}finally{if(u)throw c}}return o}(e,t)||function(e,t){if(!e)return;if("string"==typeof e)return P(e,t);var n=Object.prototype.toString.call(e).slice(8,-1);"Object"===n&&e.constructor&&(n=e.constructor.name);if("Map"===n||"Set"===n)return Array.from(e);if("Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n))return P(e,t)}(e,t)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function P(e,t){(null==t||t>e.length)&&(t=e.length);for(var n=0,r=new Array(t);n<t;n++)r[n]=e[n];return r}var S=Object(r.createElement)(r.Fragment,null,Object(o.__)("It seems there was an issue during the import. Please contact","funnel-builder"),Object(r.createElement)("a",{href:"https://funnelkit.com/support/?utm_source=WordPress&utm_medium=Funnel+Import+Failed&utm_campaign=Lite+Plugin",target:"_blank",rel:"noopener noreferrer"},Object(o.__)(" support ","funnel-builder")),Object(o.__)("with JSON for a review.","funnle-builder"));t.a=function(e){e.isOpen;var t=e.onRequestClose,n=e.onSuccess,l=void 0===n?c.noop:n,f=e.modalTitle,b=e.apiPath,d=e.showSuccessState,w=void 0===d||d,k=e.extraParams,P=void 0===k?{}:k,C=e.enableClone,F=void 0!==C&&C,T=Object(O.a)().isProFeatureLocked,N=E(Object(r.useState)({status:!1,modalTitle:f||Object(o.__)("Import Funnel","funnel-builder")}),2),A=N[0],D=N[1],I=E(Object(r.useState)(null),2),L=I[0],x=I[1],B=E(Object(r.useState)("json"),2),R=B[0],G=B[1],z=E(Object(r.useState)(0),2),J=z[0],M=z[1],U=E(Object(r.useState)(!1),2),q=U[0],W=U[1],K=Object(O.b)().showSnackbar,Y=Object(v.a)().updateStatus,$=Object(h.a)().setGlobalCheckout,H=Object(r.useRef)(new AbortController),Q=function(e){if(l(e),e.setup&&Y(e.setup),Object(i.cb)()&&J&&$({funnel_id:e.funnel_id,funnel_name:"",funnel_status:!1}),"json"===R){if(!w)return void t(!0);D(y(y({},A),{},{status:!0,title:Object(o.__)("Sucessfully Imported","funnel-builder"),loading:!1,message:Object(o.__)("Successfully Imported and added in your FunnelKit.","funnel-builder")}))}else t(!0)},V=function(){D(y(y({},A),{},{status:!1,loading:!1,error:!0,title:Object(o.__)("Importing Failed","funnel-builder"),message:S})),x(null)};Object(r.useEffect)((function(){L&&function(){D(y(y({},A),{},{title:Object(o.__)("Please wait…","funnel-builder"),loading:!0,message:Object(r.createElement)(r.Fragment,null,Object(o.__)("Please wait! We are importing ")+(null==L?void 0:L.name)+Object(o.__)(" data."))}));try{var e=new FormData;if(e.append("files",L),!Object(c.isEmpty)(P))for(var t=0,n=Object.entries(P);t<n.length;t++){var l=E(n[t],2),a=l[0],s=l[1];e.append(a,s)}u()({method:"POST",body:e,path:Object(i.r)(b||"/funnels/import"),signal:H.current.signal}).then((function(e){1==e.status?Q(e):V()})).catch((function(){V()}))}catch(e){console.log(e),V()}}()}),[L]);var X=function(){var e;null===(e=H.current)||void 0===e||e.abort(),t(A.status)},Z=function(){switch(R){case"json":return!L;case"existing":return!J;default:return!1}};return Object(r.createElement)(a.Modal,{className:"bwf-admin-modal bwf-admin-modal-small bwf-import-funnel-modal",title:A.modalTitle,onRequestClose:X,shouldCloseOnClickOutside:!1},A.title&&Object(r.createElement)("div",{className:"bwf-import-modal-content"},Object(r.createElement)(m,{value:"100",content:Object(r.createElement)(p.a,{icon:"import-file",size:"56"}),isLoading:A.loading,error:A.error}),Object(r.createElement)("div",{className:"bwf-drop-import-content bwf-mt-10"},A.message),(!0===A.status||A.error)&&Object(r.createElement)(_.a,{isPrimary:!0,onClick:X,className:"bwf-import-modal-close-btn"},Object(o.__)("Close","funnel-builder"))),!A.title&&Object(r.createElement)(r.Fragment,null,F&&Object(r.createElement)(g.a,{options:[{value:"json",label:Object(o.__)("Upload JSON","funnel-builder")},{value:"existing",label:Object(o.__)("Clone Funnel","funnel-builder"),isPro:T}],selected:R,onChange:function(e){G(e)},className:""}),"json"===R?Object(r.createElement)(s.a,{onFileSelected:function(e){return x(e)},filetypeArray:["application/json"]}):Object(r.createElement)(r.Fragment,null,Object(r.createElement)(j.a,{activeGroup:Object(i.cb)()?"wc_checkout":"",onSelect:function(e){return M(e)}}),Object(r.createElement)("div",{className:"bwf-mt-24 bwf-form-buttons".concat(isRtl?" wf_funnel_left_align":" wf_funnel_right_align")},Object(r.createElement)(_.a,{onClick:t,className:"bwf-modal-cancel-btn"},Object(o.__)("Cancel","funnel-builder")),Object(r.createElement)(_.a,{isPrimary:!0,disabled:Z()||q,isBusy:q,onClick:function(){W(!0),u()({path:Object(i.r)("".concat(Object(i.cb)()?"/store-checkout":"/funnels","/duplicate/").concat(J)),method:Object(i.cb)()?"POST":"GET"}).then((function(e){e.status?Q(e):K(e.msg),W(!1)})).catch((function(e){K(e.msg),W(!1)}))},className:Z()?"bwf-no-ripple":"",name:"add-funnel"},Object(o.__)("Done","funnel-builder"))))))}},939:function(e,t,n){},940:function(e,t,n){}}]);