(window.webpackJsonp=window.webpackJsonp||[]).push([[33],{1135:function(e,t,n){},1164:function(e,t,n){"use strict";n.r(t);var a=n(0),r=n(118),c=n(1),l=n(28),s=n(4),i=n(146),o=n(21),b=function(){var e=Object(o.a)().isProFeatureLocked,t=[{key:"funnels",href:Object(s.J)({},"/analytics/funnels",{}),label:Object(c.__)("Funnels","funnel-builder"),isProTab:e},{key:"orders",href:Object(s.J)({},"/analytics/orders",{}),label:Object(c.__)("Conversions","funnel-builder")},{key:"referrers",href:Object(s.J)({},"/analytics/referrers",{}),label:Object(c.__)("Referrers","funnel-builder"),isProTab:e}],n=location&&location.search?Object(l.parse)(location.search.substring(1)):{},r=n.path.split("/")&&n.path.split("/").length>=3?n.path.split("/")[2].toLowerCase():"funnels";return Object(a.createElement)(i.a,{selected:"experiments"===r?"steps":"contacts"===r?"orders":r,SidebarItems:t,title:Object(c.__)("Analytics","funnel-builder")})},u=n(61),d=n(14),O=n(109),f=n(134),j=n(24),m=n(2),p=n(94),y=n(93),v=n(95),E=n(86),g=n(85),h=n(40),w=n(89),_=n(128),L=["edit"];function N(e,t){if(null==e)return{};var n,a,r=function(e,t){if(null==e)return{};var n,a,r={},c=Object.keys(e);for(a=0;a<c.length;a++)n=c[a],t.indexOf(n)>=0||(r[n]=e[n]);return r}(e,t);if(Object.getOwnPropertySymbols){var c=Object.getOwnPropertySymbols(e);for(a=0;a<c.length;a++)n=c[a],t.indexOf(n)>=0||Object.prototype.propertyIsEnumerable.call(e,n)&&(r[n]=e[n])}return r}var k=function(e,t,n){return decodeURIComponent(Object(d.f)(e,t,n))},P=function(){var e=Object(o.a)().isProFeatureLocked,t=Object(d.i)(),n=(t.edit,N(t,L)),l=Object(u.a)(),i=l.resetHeaderMenu,b=(l.setPageHeader,l.setStepHeader),P=l.setL2NavType,x=l.setIsStepNavActive,C=l.setL2Nav,T=l.setActiveMultiple,F=l.setPageBackLink,R=Object(h.a)().getPreviousStep,S=Object(E.a)(),z=S.setOrderLoading,J=S.setOrders,A=Object(g.a)(),I=A.setLeadLoading,H=A.setLeads,M=Object(v.a)(),B=M.getTotalContacts,D=M.getMessage,K=Object(w.a)().setReferrerLoading,U=Object(_.a)().resetAnalyticsData,q=Object(f.a)().getContact,G=Object(p.a)().getTotalOrders,Q=Object(y.a)().getTotalLeads,V=B(),W=G(),X=Q(),Y=(D(),R()),Z=q(),$=(null!=Z?Z:{}).user_info,ee=void 0===$?{}:$,te=(null==ee?void 0:ee.first_name)&&"".concat(null==ee?void 0:ee.first_name," ").concat(null==ee?void 0:ee.last_name),ne=Object(r.useParams)(),ae=ne.tab,re=void 0===ae?"funnels":ae,ce=ne.tab2,le=function(e,t){var n=Object(m.isEmpty)(ce)?"orders":ce;return Object(a.createElement)(a.Fragment,null,Object(a.createElement)("div",{className:"bwf-flex bwf--g8"},Object(a.createElement)("span",null,Object(m.capitalize)(e)),t>0?Object(a.createElement)(j.b,{content:t,type:e===n?"secondary":"tertiary"}):null))},se={orders:{name:le(Object(c.__)("orders","funnel-builder"),W),link:k({},"/analytics/orders",n),count:W},optins:{name:le(Object(c.__)("optins","funnel-builder"),X),link:k({},"/analytics/orders/optins",n),count:X}},ie=function(e,t){return Object(a.createElement)(O.a,{modalTitle:e,isBack:t,disabled:["publish","edit","preview","stepType","draftContainer","abContainer","draft","action"]})};Object(a.useEffect)((function(){return function(){z(!0),J([],1,25),I(!0),H([],1,25),K(!0),U()}}),[]),Object(a.useEffect)((function(){i(),x(!0),T({leftNav:"analytics",rightNav:ce}),"overview"===re?b(Object(a.createElement)("div",{className:"bwf-header-title bwf-mb-16"},Object(c.__)("Overview","funnel-builder"))):"funnels"===re?b(Object(a.createElement)("div",{className:"bwf-flex bwf--space-between bwf-mb-16"},Object(a.createElement)("div",{className:"bwf-header-title"},Object(c.__)("Funnels","funnel-builder"),e&&Object(a.createElement)(j.a,null)))):"conversion-tracking"===re?b(Object(a.createElement)("div",{className:"bwf-header-title bwf-mb-16"},Object(c.__)("Conversion Tracking","funnel-builder"))):"referrers"===re?b(Object(a.createElement)("div",{className:"bwf-header-title bwf-mb-16"},Object(c.__)("Referrers","funnel-builder"),e&&Object(a.createElement)(j.a,null))):"contacts"===re?void 0!==ce&&("orders"===Y&&z(!1),"leads"===Y&&I(!1),F(Object(s.J)({},"".concat("orders"===Y?"/analytics/orders":"leads"===Y?"/analytics/orders/optins":"/analytics/orders"))),b(ie("Details",!0))):"contact-export"===re?(F(Object(s.J)({},"/analytics/contacts")),b(ie("Export Contacts"))):"orders"===re&&(b(Object(a.createElement)("div",{className:"bwf-header-title bwf-mb-16"},Object(c.__)("Conversions","funnel-builder"))),T({leftNav:"analytics",rightNav:!isNaN(W)&&!isNaN(X)&&(ce?"optins":"orders")}),C(se),P("menu"))}),[re,ce,te,W,X,V,Y])},x=(n(1135),n(152)),C=n(148),T=n(37),F=n(245),R=n(758),S=(n(41),Object(a.lazy)((function(){return Promise.all([n.e(1),n.e(0),n.e(6),n.e(13),n.e(25)]).then(n.bind(null,1151))}))),z=Object(a.lazy)((function(){return Promise.all([n.e(3),n.e(4),n.e(7),n.e(0),n.e(27)]).then(n.bind(null,760))})),J=Object(a.lazy)((function(){return Promise.all([n.e(0),n.e(6),n.e(28)]).then(n.bind(null,600))})),A=Object(a.lazy)((function(){return Promise.all([n.e(3),n.e(4),n.e(0),n.e(6),n.e(35)]).then(n.bind(null,602))})),I=Object(a.lazy)((function(){return Promise.all([n.e(1),n.e(3),n.e(4),n.e(0),n.e(31)]).then(n.bind(null,1166))})),H=Object(a.lazy)((function(){return Promise.all([n.e(1),n.e(3),n.e(4),n.e(0),n.e(31)]).then(n.bind(null,1163))}));t.default=function(){Object(s.i)(Object(c.__)("Analytics","funnel-builder")),P();var e=Object(C.a)(),t=e.setContacts,n=e.setContactLoading,l=e.setContactsFiltersList,i=Object(g.a)(),o=i.setLeadLoading,u=i.setLeadsFiltersList,d=i.setLeadsFirstTimeLoading,O=Object(E.a)(),f=O.setOrderLoading,j=O.setOrdersFiltersList,m=O.setOrdersFirstTimeLoading,p=Object(w.a)().setReferrerLoading;Object(a.useEffect)((function(){return function(){n(!0),t([],1,25),f(!0),o(!0),p(!0),l([]),u([]),j([]),m(null),d(null)}}),[]);var y=Object(R.useParams)(),v=y.tab2,h=y.tab,_=void 0===h?"funnel":h;return Object(a.createElement)("div",{className:"bwf-analytics-wrap"},Object(a.createElement)(b,null),Object(a.createElement)("div",null,Object(a.createElement)(x.a,{isLoading:!1}),Object(a.createElement)(a.Suspense,{fallback:Object(a.createElement)(a.Fragment,null)},Object(a.createElement)(F.a,null,Object(a.createElement)(T.a,{pageKey:null!=v?v:_},(function(e){var t=e.ref;return Object(a.createElement)("div",{ref:t},Object(a.createElement)(r.Switch,null,Object(a.createElement)(r.Route,{exact:!0,path:["/analytics/contacts","/analytics/contact-export","/analytics/contacts/all","/analytics/contacts/customers","/analytics/contacts/leads","/analytics/contacts/:id"],render:function(e){return Object(a.createElement)(S,e)}}),Object(a.createElement)(r.Route,{exact:!0,path:["/analytics/orders"],render:function(e){return Object(a.createElement)(I,e)}}),Object(a.createElement)(r.Route,{exact:!0,path:["/analytics/orders/:ordersType"],render:function(e){return Object(a.createElement)(H,e)}}),Object(a.createElement)(r.Route,{exact:!0,path:["/analytics","/analytics/funnels"],render:function(e){return Object(a.createElement)(z,e)}}),Object(a.createElement)(r.Route,{exact:!0,path:["/analytics/conversion-tracking"],render:function(e){return Object(a.createElement)(J,e)}}),Object(a.createElement)(r.Route,{exact:!0,path:["/analytics/referrers"],render:function(e){return Object(a.createElement)(A,e)}}),s.o))}))))))}}}]);