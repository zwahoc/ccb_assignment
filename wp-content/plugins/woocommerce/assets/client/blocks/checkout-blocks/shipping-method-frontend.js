"use strict";(self.webpackChunkwebpackWcBlocksCartCheckoutFrontendJsonp=self.webpackChunkwebpackWcBlocksCartCheckoutFrontendJsonp||[]).push([[9319],{1342:(e,t,o)=>{o.d(t,{A:()=>c});var i=o(7723);const c=({defaultTitle:e=(0,i.__)("Step","woocommerce"),defaultDescription:t=(0,i.__)("Step description text.","woocommerce"),defaultShowStepNumber:o=!0})=>({title:{type:"string",default:e},description:{type:"string",default:t},showStepNumber:{type:"boolean",default:o}})},1669:(e,t,o)=>{o.r(t),o.d(t,{default:()=>R});var i=o(1609),c=o(851),n=o(1616),r=o(4656),s=o(7143),p=o(7594),a=o(5606),l=o(2516),d=o(3588),h=o(7723),m=o(7104),u=o(3705),g=o(1176),k=o(6087),_=o(3835),w=o(5703),v=o(9155),b=o(910);const f=({minRate:e,maxRate:t,multiple:o=!1})=>{if(void 0===e||void 0===t)return null;const c=(0,w.getSetting)("displayCartPricesIncludingTax",!1)?parseInt(e.price,10)+parseInt(e.taxes,10):parseInt(e.price,10),n=(0,w.getSetting)("displayCartPricesIncludingTax",!1)?parseInt(t.price,10)+parseInt(t.taxes,10):parseInt(t.price,10),s=0===c?(0,i.createElement)("em",null,(0,h.__)("free","woocommerce")):(0,i.createElement)(r.FormattedMonetaryAmount,{currency:(0,b.getCurrencyFromPriceResponse)(e),value:c});return(0,i.createElement)("span",{className:"wc-block-checkout__shipping-method-option-price"},c!==n||o?(0,k.createInterpolateElement)(0===c&&0===n?"<price />":(0,h.__)("from <price />","woocommerce"),{price:s}):s)};function x(e){return e?{min:e.reduce(((e,t)=>(0,_.jV)(t.method_id)?e:void 0===e||parseInt(t.price,10)<parseInt(e.price,10)?t:e),void 0),max:e.reduce(((e,t)=>(0,_.jV)(t.method_id)?e:void 0===e||parseInt(t.price,10)>parseInt(e.price,10)?t:e),void 0)}:{min:void 0,max:void 0}}function C(e){return e?{min:e.reduce(((e,t)=>(0,_.jV)(t.method_id)&&(void 0===e||t.price<e.price)?t:e),void 0),max:e.reduce(((e,t)=>(0,_.jV)(t.method_id)&&(void 0===e||t.price>e.price)?t:e),void 0)}:{min:void 0,max:void 0}}const E=(0,h.__)("Pickup","woocommerce"),I=(0,h.__)("Ship","woocommerce");var S=o(8682);const P={hidden:!0,message:(0,h.__)("Shipping options are not available","woocommerce")},y=({checked:e,rate:t,showPrice:o,showIcon:n,toggleText:r,multiple:s,onClick:p})=>(0,i.createElement)(v.$,{render:(0,i.createElement)("div",null),role:"radio",onClick:p,"aria-checked":"pickup"===e,className:(0,c.A)("wc-block-checkout__shipping-method-option",{"wc-block-checkout__shipping-method-option--selected":"pickup"===e})},!0===n&&(0,i.createElement)(m.A,{icon:u.A,size:28,className:"wc-block-checkout__shipping-method-option-icon"}),(0,i.createElement)("span",{className:"wc-block-checkout__shipping-method-option-title"},r),!0===o&&(0,i.createElement)(f,{multiple:s,minRate:t.min,maxRate:t.max})),N=({checked:e,rate:t,showPrice:o,showIcon:n,toggleText:r,onClick:a,shippingCostRequiresAddress:l=!1})=>{const d=(0,s.useSelect)((e=>e(p.cartStore).getShippingRates().some((({shipping_rates:e})=>!e.every(_.J_))))),u=l&&(0,S.ND)()&&!d,w=void 0!==t.min&&void 0!==t.max,{setValidationErrors:b,clearValidationError:x}=(0,s.useDispatch)(p.validationStore);(0,k.useEffect)((()=>("shipping"!==e||w?x("shipping-rates-error"):b({"shipping-rates-error":P}),()=>x("shipping-rates-error"))),[e,x,w,b]);const C=void 0===t.min||u?(0,i.createElement)("span",{className:"wc-block-checkout__shipping-method-option-price"},(0,h.__)("calculated with an address","woocommerce")):(0,i.createElement)(f,{minRate:t.min,maxRate:t.max});return(0,i.createElement)(v.$,{render:(0,i.createElement)("div",null),role:"radio",onClick:a,"aria-checked":"shipping"===e,className:(0,c.A)("wc-block-checkout__shipping-method-option",{"wc-block-checkout__shipping-method-option--selected":"shipping"===e})},!0===n&&(0,i.createElement)(m.A,{icon:g.A,size:28,className:"wc-block-checkout__shipping-method-option-icon"}),(0,i.createElement)("span",{className:"wc-block-checkout__shipping-method-option-title"},r),!0===o&&C)},T=({checked:e,onChange:t,showPrice:o,showIcon:c,localPickupText:n,shippingText:r})=>{var s,p;const{shippingRates:l}=(0,a.m)(),d=(0,w.getSetting)("shippingCostRequiresAddress",!1),h=(0,w.getSetting)("localPickupText",n||E);return(0,i.createElement)("div",{id:"shipping-method",className:"components-button-group wc-block-checkout__shipping-method-container",role:"radiogroup"},(0,i.createElement)(N,{checked:e,onClick:()=>{t("shipping")},rate:x(null===(s=l[0])||void 0===s?void 0:s.shipping_rates),showPrice:o,showIcon:c,shippingCostRequiresAddress:d,toggleText:r||I}),(0,i.createElement)(y,{checked:e,onClick:()=>{t("pickup")},rate:C(null===(p=l[0])||void 0===p?void 0:p.shipping_rates),multiple:l.length>1,showPrice:o,showIcon:c,toggleText:h}))},A={...(0,o(1342).A)({defaultTitle:(0,h.__)("Delivery","woocommerce"),defaultDescription:(0,h.__)("Select how you would like to receive your order.","woocommerce")}),className:{type:"string",default:""},showIcon:{type:"boolean",default:!0},showPrice:{type:"boolean",default:!1},localPickupText:{type:"string",default:E},shippingText:{type:"string",default:I},lock:{type:"object",default:{move:!0,remove:!0}}},R=(0,n.withFilteredAttributes)(A)((({title:e,description:t,children:o,className:n,showPrice:h,showIcon:m,shippingText:u,localPickupText:g})=>{const{showFormStepNumbers:k}=(0,d.O)(),{checkoutIsProcessing:_,prefersCollection:w}=(0,s.useSelect)((e=>{const t=e(p.checkoutStore);return{checkoutIsProcessing:t.isProcessing(),prefersCollection:t.prefersCollection()}})),{setPrefersCollection:v}=(0,s.useDispatch)(p.checkoutStore),{needsShipping:b,isCollectable:f}=(0,a.m)();return l.h0&&b&&f&&l.F7&&l.mH?(0,i.createElement)(r.FormStep,{id:"shipping-method",disabled:_,className:(0,c.A)("wc-block-checkout__shipping-method",n),title:e,description:t,showStepNumber:k},(0,i.createElement)(T,{checked:w?"pickup":"shipping",onChange:e=>{v("pickup"===e)},showPrice:h,showIcon:m,localPickupText:g,shippingText:u}),o):null}))}}]);