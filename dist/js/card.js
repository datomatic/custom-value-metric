(()=>{var e,t={794:(e,t,r)=>{"use strict";const n=Vue;const o={computed:{userTimezone:function(){return Nova.config("userTimezone")||Nova.config("timezone")},usesTwelveHourTime:function(){return 12===function(e){var t=Intl.DateTimeFormat(e,{hour:"numeric"}).resolvedOptions().hourCycle;return"h23"==t||"h24"==t?24:12}((new Intl.DateTimeFormat).resolvedOptions().locale)}}},a={created:function(){Nova.$on("metric-refresh",this.fetch),Nova.$on("resources-deleted",this.fetch),Nova.$on("resources-restored",this.fetch),this.card.refreshWhenActionRuns&&Nova.$on("action-executed",this.fetch)},beforeUnmount:function(){Nova.$off("metric-refresh",this.fetch),Nova.$off("resources-deleted",this.fetch),Nova.$off("resources-restored",this.fetch),Nova.$off("action-executed",this.fetch)}};var i={class:"h-6 flex items-center mb-4"},s={class:"mr-3 leading-tight text-sm font-bold"},l={class:"grid grid-cols-2 gap text-xs"},c={key:0},u={class:"text-70 font-bold uppercase mt-2 mb-1"},p={class:"flex"},d={class:"text-sm text-xl"},g={key:0,class:"ml-2 text-sm font-bold text-80"},m={class:"flex text-90 items-center text-80",style:{"min-height":"24px"}},h={key:0},f={key:0},v={class:"text-xs text-gray-400 whitespace-nowrap"},x={key:1},y={key:1},w={key:0},$={key:1},E={key:2},k={key:1},b={class:"flex items-center text-4xl mb-4"},R={key:0,class:"ml-2 text-sm font-bold text-80"},N={class:"flex items-center text-80 font-bold"},C={key:0,xmlns:"http://www.w3.org/2000/svg",class:"text-danger stroke-current mr-2",width:"24",height:"24",fill:"none",viewBox:"0 0 24 24",stroke:"currentColor"},B=[(0,n.createElementVNode)("path",{"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"},null,-1)],V={key:1,class:"text-success stroke-current mr-2",width:"24",height:"24",fill:"none",viewBox:"0 0 24 24",stroke:"currentColor"},D=[(0,n.createElementVNode)("path",{"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"},null,-1)],S={key:2},O={key:0},_={key:1},z={key:3},T={key:0},j={key:1},F={key:2};var P=r(923),L=r.n(P),W=r(37),I=r.n(W);var K={methods:{copyValueToClipboard:function(e){if(navigator.clipboard)navigator.clipboard.writeText(e);else if(window.clipboardData)window.clipboardData.setData("Text",e);else{var t=document.createElement("input"),r=[document.documentElement.scrollTop,document.documentElement.scrollLeft],n=r[0],o=r[1];document.body.appendChild(t),t.value=e,t.focus(),t.select(),document.documentElement.scrollTop=n,document.documentElement.scrollLeft=o,document.execCommand("copy"),t.remove()}}}};const M={name:"CustomBaseValueMetric",mixins:[K],emits:["selected"],props:{loading:{default:!0},copyable:{default:!1},title:{},helpText:{},helpWidth:{},maxWidth:{},multi:{default:!1},metrics:{type:Array,default:function(){return[]}},rangeGroupClass:"",selectedRangeKey:[String,Number],ranges:{type:Array,default:function(){return[]}},format:{type:String,default:"(0[.]00a)"},tooltipFormat:{type:String,default:"(0[.]00)"}},data:function(){return{copied:!1}},methods:{handleChange:function(e){var t,r=(null==e||null===(t=e.target)||void 0===t?void 0:t.value)||e;this.$emit("selected",r)},handleCopyClick:function(e){var t=this;e.copyable&&(this.copied=!0,this.copyValueToClipboard(this.tooltipFormattedValue),setTimeout((function(){t.copied=!1}),2e3))},growthPercentage:function(e){return Math.abs(this.increaseOrDecrease(e))},increaseOrDecrease:function(e){return 0==e.previous||null==e.previous||0==e.value?0:((e.value-e.previous)/e.previous*100).toFixed(2)},increaseOrDecreaseLabel:function(e){switch(Math.sign(this.increaseOrDecrease(e))){case 1:return"Increase";case 0:return"Constant";case-1:return"Decrease"}},sign:function(e){switch(Math.sign(this.increaseOrDecrease(e))){case 1:return"+";case 0:return"";case-1:return"-"}},isNullValue:function(e){return null==e.value},formattedValue:function(e){return this.isNullValue(e)?"":e.prefix+Nova.formatNumber(new String(e.value),e.format)},tooltipFormattedValue:function(e){return this.isNullValue(e)?"":e.prefix+Nova.formatNumber(new String(e.value),e.tooltipFormat)},spanClass:function(e){return e.span?"col-span-"+e.span:""},formattedSuffix:function(e){return!1===e.suffixInflection?e.suffix:(t=e.value,r=e.suffix,I()(r)&&null==r.match(/^(.*)[A-Za-zÀ-ÖØ-öø-ÿ]$/)?r:t>1||0==t?L().pluralize(r):L().singularize(r));var t,r}}};var A=r(744);const G={name:"CustomValueMetric",mixins:[o,a],components:{CustomBaseValueMetric:(0,A.Z)(M,[["render",function(e,t,r,o,a,P){var L=this,W=(0,n.resolveComponent)("HelpTextTooltip"),I=(0,n.resolveComponent)("SelectControl"),K=(0,n.resolveComponent)("LoadingCard");return(0,n.openBlock)(),(0,n.createBlock)(K,{loading:r.loading,class:"px-6 py-4"},{default:(0,n.withCtx)((function(){return[(0,n.createElementVNode)("div",i,[(0,n.createElementVNode)("h3",s,(0,n.toDisplayString)(r.title),1),(0,n.createVNode)(W,{text:r.helpText,width:r.helpWidth},null,8,["text","width"]),r.ranges.length>0?((0,n.openBlock)(),(0,n.createBlock)(I,{key:0,class:(0,n.normalizeClass)(["ml-auto w-[6rem] flex-shrink-0",r.rangeGroupClass]),size:"xxs",options:r.ranges,onChange:P.handleChange,"aria-label":e.__("Select Ranges")},null,8,["class","options","onChange","aria-label"])):(0,n.createCommentVNode)("",!0)]),(0,n.createElementVNode)("div",l,[((0,n.openBlock)(!0),(0,n.createElementBlock)(n.Fragment,null,(0,n.renderList)(L.metrics,(function(t){return(0,n.openBlock)(),(0,n.createElementBlock)("div",{class:(0,n.normalizeClass)(["p-1",P.spanClass(t)])},[r.multi?((0,n.openBlock)(),(0,n.createElementBlock)("div",c,[(0,n.createElementVNode)("div",u,(0,n.toDisplayString)(t.name),1),(0,n.createElementVNode)("div",p,[(0,n.createElementVNode)("div",d,(0,n.toDisplayString)(P.formattedValue(t)),1),t.suffix?((0,n.openBlock)(),(0,n.createElementBlock)("span",g,(0,n.toDisplayString)(P.formattedSuffix(t)),1)):(0,n.createCommentVNode)("",!0)]),(0,n.createElementVNode)("div",m,[0!=P.increaseOrDecrease(t)?((0,n.openBlock)(),(0,n.createElementBlock)("span",h,[0!==P.growthPercentage(t)?((0,n.openBlock)(),(0,n.createElementBlock)("span",f,[(0,n.createElementVNode)("span",{class:(0,n.normalizeClass)(["text-base",P.increaseOrDecrease(t)<0?"text-red-700":"text-green-700"])},(0,n.toDisplayString)(P.increaseOrDecrease(t)<0?"-":"+")+(0,n.toDisplayString)(Math.round(P.growthPercentage(t)))+"% ",3),(0,n.createElementVNode)("span",v," ("+(0,n.toDisplayString)(P.formattedValue(t))+") ",1)])):((0,n.openBlock)(),(0,n.createElementBlock)("span",x,(0,n.toDisplayString)(e.__("No Increase")),1))])):((0,n.openBlock)(),(0,n.createElementBlock)("span",y,["0"==t.previous&&"0"!=t.value?((0,n.openBlock)(),(0,n.createElementBlock)("span",w,(0,n.toDisplayString)(e.__("No Prior Data")),1)):(0,n.createCommentVNode)("",!0),"0"!=t.value||"0"==t.previous||t.zeroResult?(0,n.createCommentVNode)("",!0):((0,n.openBlock)(),(0,n.createElementBlock)("span",$,(0,n.toDisplayString)(e.__("No Current Data")),1)),"0"!=t.value||"0"!=t.previous||t.zeroResult?(0,n.createCommentVNode)("",!0):((0,n.openBlock)(),(0,n.createElementBlock)("span",E,(0,n.toDisplayString)(e.__("No Data")),1))]))])])):((0,n.openBlock)(),(0,n.createElementBlock)("div",k,[(0,n.createElementVNode)("p",b,[(0,n.createTextVNode)((0,n.toDisplayString)(P.formattedValue(t))+" ",1),t.suffix?((0,n.openBlock)(),(0,n.createElementBlock)("span",R,(0,n.toDisplayString)(P.formattedSuffix(t)),1)):(0,n.createCommentVNode)("",!0)]),(0,n.createElementVNode)("div",null,[(0,n.createElementVNode)("div",N,["Decrease"==P.increaseOrDecreaseLabel(t)?((0,n.openBlock)(),(0,n.createElementBlock)("svg",C,B)):(0,n.createCommentVNode)("",!0),"Increase"==P.increaseOrDecreaseLabel(t)?((0,n.openBlock)(),(0,n.createElementBlock)("svg",V,D)):(0,n.createCommentVNode)("",!0),0!=P.increaseOrDecrease(t)?((0,n.openBlock)(),(0,n.createElementBlock)("span",S,[0!==P.growthPercentage(t)?((0,n.openBlock)(),(0,n.createElementBlock)("span",O,[(0,n.createTextVNode)((0,n.toDisplayString)(P.growthPercentage(t))+"% "+(0,n.toDisplayString)(e.__(P.increaseOrDecreaseLabel(t)))+" ",1),(0,n.createElementVNode)("span",null," ("+(0,n.toDisplayString)(P.formattedValue(t))+") ",1)])):((0,n.openBlock)(),(0,n.createElementBlock)("span",_,(0,n.toDisplayString)(e.__("No Increase")),1))])):((0,n.openBlock)(),(0,n.createElementBlock)("span",z,["0"==t.previous&&"0"!=t.value?((0,n.openBlock)(),(0,n.createElementBlock)("span",T,(0,n.toDisplayString)(e.__("No Prior Data")),1)):(0,n.createCommentVNode)("",!0),"0"!=t.value||"0"==t.previous||t.zeroResult?(0,n.createCommentVNode)("",!0):((0,n.openBlock)(),(0,n.createElementBlock)("span",j,(0,n.toDisplayString)(e.__("No Current Data")),1)),"0"!=t.value||"0"!=t.previous||t.zeroResult?(0,n.createCommentVNode)("",!0):((0,n.openBlock)(),(0,n.createElementBlock)("span",F,(0,n.toDisplayString)(e.__("No Data")),1))]))])])]))],2)})),256))])]})),_:1},8,["loading"])}]])},props:{card:{type:Object,required:!0},resourceName:{type:String,default:""},resourceId:{type:[Number,String],default:""},lens:{type:String,default:""}},data:function(){return{loading:!0,copyable:!1,format:"(0[.]00a)",tooltipFormat:"(0[.]00)",metrics:{}}},watch:{resourceId:function(){this.fetch()}},created:function(){this.hasRanges&&(this.selectedRangeKey=this.card.selectedRangeKey||this.card.ranges[0].value),this.fetch()},mounted:function(){this.card&&!0===this.card.refreshWhenFiltersChange&&Nova.$on("filter-changed",this.fetch(this.selectedRangeKey))},beforeUnmount:function(){this.card&&!0===this.card.refreshWhenFiltersChange&&Nova.$off("filter-changed",this.fetch(this.selectedRangeKey))},methods:{handleRangeSelected:function(e){this.selectedRangeKey=e;for(var t=document.getElementsByClassName(this.rangeGroupClass),r=0;r<t.length;r++){var n=t[r].getElementsByTagName("select")[0];if(n.value!=e){n.value=e,Array.from(n.options).find((function(t){return t.value===e})).selected=!0;var o=new Event("change");n.dispatchEvent(o)}}this.fetch()},fetch:function(){var e=this;this.loading=!0,function(e){var t=arguments.length>1&&void 0!==arguments[1]?arguments[1]:100;return Promise.all([e,new Promise((function(e){setTimeout((function(){return e()}),t)}))]).then((function(e){return e[0]}))}(Nova.request().get(this.metricEndpoint,this.metricPayload())).then((function(t){var r=t.data.value,n=r.multi,o=r.rangeGroupClass,a=r.metrics;e.multi=n||e.multi,e.rangeGroupClass=o||e.rangeGroupClass,e.metrics=a,e.loading=!1}))},metricPayload:function(){var e={params:{timezone:this.userTimezone}};return!Nova.missingResource(this.resourceName)&&this.card&&!0===this.card.refreshWhenFiltersChange&&(e.params.filter=this.$store.getters["".concat(this.resourceName,"/currentEncodedFilters")]),this.hasRanges&&(e.params.range=this.selectedRangeKey),e}},computed:{hasRanges:function(){return this.card.ranges.length>0},metricEndpoint:function(){var e=""!==this.lens?"/lens/".concat(this.lens):"";return this.resourceName&&this.resourceId?"/nova-api/".concat(this.resourceName).concat(e,"/").concat(this.resourceId,"/metrics/").concat(this.card.uriKey):this.resourceName?"/nova-api/".concat(this.resourceName).concat(e,"/metrics/").concat(this.card.uriKey):"/nova-api/metrics/".concat(this.card.uriKey)}}},U=(0,A.Z)(G,[["render",function(e,t,r,o,a,i){var s=(0,n.resolveComponent)("CustomBaseValueMetric");return(0,n.openBlock)(),(0,n.createBlock)(s,{onSelected:i.handleRangeSelected,title:r.card.name,"help-text":r.card.helpText,"help-width":r.card.helpWidth,multi:e.multi,rangeGroupClass:e.rangeGroupClass,metrics:e.metrics,ranges:r.card.ranges,format:e.format,"tooltip-format":e.tooltipFormat,"selected-range-key":e.selectedRangeKey,loading:e.loading},null,8,["onSelected","title","help-text","help-width","multi","rangeGroupClass","metrics","ranges","format","tooltip-format","selected-range-key","loading"])}]]);Nova.booting((function(e){e.component("custom-value-metric",U)}))},923:e=>{"use strict";var t={uncountableWords:["equipment","information","rice","money","species","series","fish","sheep","moose","deer","news"],pluralRules:[[new RegExp("(m)an$","gi"),"$1en"],[new RegExp("(pe)rson$","gi"),"$1ople"],[new RegExp("(child)$","gi"),"$1ren"],[new RegExp("^(ox)$","gi"),"$1en"],[new RegExp("(ax|test)is$","gi"),"$1es"],[new RegExp("(octop|vir)us$","gi"),"$1i"],[new RegExp("(alias|status)$","gi"),"$1es"],[new RegExp("(bu)s$","gi"),"$1ses"],[new RegExp("(buffal|tomat|potat)o$","gi"),"$1oes"],[new RegExp("([ti])um$","gi"),"$1a"],[new RegExp("sis$","gi"),"ses"],[new RegExp("(?:([^f])fe|([lr])f)$","gi"),"$1$2ves"],[new RegExp("(hive)$","gi"),"$1s"],[new RegExp("([^aeiouy]|qu)y$","gi"),"$1ies"],[new RegExp("(x|ch|ss|sh)$","gi"),"$1es"],[new RegExp("(matr|vert|ind)ix|ex$","gi"),"$1ices"],[new RegExp("([m|l])ouse$","gi"),"$1ice"],[new RegExp("(quiz)$","gi"),"$1zes"],[new RegExp("s$","gi"),"s"],[new RegExp("$","gi"),"s"]],singularRules:[[new RegExp("(m)en$","gi"),"$1an"],[new RegExp("(pe)ople$","gi"),"$1rson"],[new RegExp("(child)ren$","gi"),"$1"],[new RegExp("([ti])a$","gi"),"$1um"],[new RegExp("((a)naly|(b)a|(d)iagno|(p)arenthe|(p)rogno|(s)ynop|(t)he)ses$","gi"),"$1$2sis"],[new RegExp("(hive)s$","gi"),"$1"],[new RegExp("(tive)s$","gi"),"$1"],[new RegExp("(curve)s$","gi"),"$1"],[new RegExp("([lr])ves$","gi"),"$1f"],[new RegExp("([^fo])ves$","gi"),"$1fe"],[new RegExp("([^aeiouy]|qu)ies$","gi"),"$1y"],[new RegExp("(s)eries$","gi"),"$1eries"],[new RegExp("(m)ovies$","gi"),"$1ovie"],[new RegExp("(x|ch|ss|sh)es$","gi"),"$1"],[new RegExp("([m|l])ice$","gi"),"$1ouse"],[new RegExp("(bus)es$","gi"),"$1"],[new RegExp("(o)es$","gi"),"$1"],[new RegExp("(shoe)s$","gi"),"$1"],[new RegExp("(cris|ax|test)es$","gi"),"$1is"],[new RegExp("(octop|vir)i$","gi"),"$1us"],[new RegExp("(alias|status)es$","gi"),"$1"],[new RegExp("^(ox)en","gi"),"$1"],[new RegExp("(vert|ind)ices$","gi"),"$1ex"],[new RegExp("(matr)ices$","gi"),"$1ix"],[new RegExp("(quiz)zes$","gi"),"$1"],[new RegExp("s$","gi"),""]],nonTitlecasedWords:["and","or","nor","a","an","the","so","but","to","of","at","by","from","into","on","onto","off","out","in","over","with","for"],idSuffix:new RegExp("(_ids|_id)$","g"),underbar:new RegExp("_","g"),spaceOrUnderbar:new RegExp("[ _]","g"),uppercase:new RegExp("([A-Z])","g"),underbarPrefix:new RegExp("^_"),applyRules:function(e,t,r,n){if(n)e=n;else if(!(r.indexOf(e.toLowerCase())>-1))for(var o=0;o<t.length;o++)if(e.match(t[o][0])){e=e.replace(t[o][0],t[o][1]);break}return e},pluralize:function(e,t){return this.applyRules(e,this.pluralRules,this.uncountableWords,t)},singularize:function(e,t){return this.applyRules(e,this.singularRules,this.uncountableWords,t)},camelize:function(e,t){for(var r=e.split("/"),n=0;n<r.length;n++){for(var o=r[n].split("_"),a=t&&n+1===r.length?1:0;a<o.length;a++)o[a]=o[a].charAt(0).toUpperCase()+o[a].substring(1);r[n]=o.join("")}if(e=r.join("::"),!0===t){var i=e.charAt(0).toLowerCase(),s=e.slice(1);e=i+s}return e},underscore:function(e){for(var t=e.split("::"),r=0;r<t.length;r++)t[r]=t[r].replace(this.uppercase,"_$1"),t[r]=t[r].replace(this.underbarPrefix,"");return e=t.join("/").toLowerCase()},humanize:function(e,t){return e=(e=(e=e.toLowerCase()).replace(this.idSuffix,"")).replace(this.underbar," "),t||(e=this.capitalize(e)),e},capitalize:function(e){return e=(e=e.toLowerCase()).substring(0,1).toUpperCase()+e.substring(1)},dasherize:function(e){return e=e.replace(this.spaceOrUnderbar,"-")},camel2words:function(e,t){!0===t?(e=this.camelize(e),e=this.underscore(e)):e=e.toLowerCase();for(var r=(e=e.replace(this.underbar," ")).split(" "),n=0;n<r.length;n++){for(var o=r[n].split("-"),a=0;a<o.length;a++)this.nonTitlecasedWords.indexOf(o[a].toLowerCase())<0&&(o[a]=this.capitalize(o[a]));r[n]=o.join("-")}return e=(e=r.join(" ")).substring(0,1).toUpperCase()+e.substring(1)},demodulize:function(e){var t=e.split("::");return e=t[t.length-1]},tableize:function(e){return e=this.pluralize(this.underscore(e))},classify:function(e){return e=this.singularize(this.camelize(e))},foreignKey:function(e,t){return e=this.underscore(this.demodulize(e))+(t?"":"_")+"id"},ordinalize:function(e){for(var t=e.split(" "),r=0;r<t.length;r++){if(NaN===parseInt(t[r])){var n=t[r].substring(t[r].length-2),o=t[r].substring(t[r].length-1),a="th";"11"!=n&&"12"!=n&&"13"!=n&&("1"===o?a="st":"2"===o?a="nd":"3"===o&&(a="rd")),t[r]+=a}}return e=t.join(" ")}};e.exports=t},705:(e,t,r)=>{var n=r(639).Symbol;e.exports=n},239:(e,t,r)=>{var n=r(705),o=r(607),a=r(333),i=n?n.toStringTag:void 0;e.exports=function(e){return null==e?void 0===e?"[object Undefined]":"[object Null]":i&&i in Object(e)?o(e):a(e)}},957:(e,t,r)=>{var n="object"==typeof r.g&&r.g&&r.g.Object===Object&&r.g;e.exports=n},607:(e,t,r)=>{var n=r(705),o=Object.prototype,a=o.hasOwnProperty,i=o.toString,s=n?n.toStringTag:void 0;e.exports=function(e){var t=a.call(e,s),r=e[s];try{e[s]=void 0;var n=!0}catch(e){}var o=i.call(e);return n&&(t?e[s]=r:delete e[s]),o}},333:e=>{var t=Object.prototype.toString;e.exports=function(e){return t.call(e)}},639:(e,t,r)=>{var n=r(957),o="object"==typeof self&&self&&self.Object===Object&&self,a=n||o||Function("return this")();e.exports=a},469:e=>{var t=Array.isArray;e.exports=t},5:e=>{e.exports=function(e){return null!=e&&"object"==typeof e}},37:(e,t,r)=>{var n=r(239),o=r(469),a=r(5);e.exports=function(e){return"string"==typeof e||!o(e)&&a(e)&&"[object String]"==n(e)}},962:()=>{},744:(e,t)=>{"use strict";t.Z=(e,t)=>{const r=e.__vccOpts||e;for(const[e,n]of t)r[e]=n;return r}}},r={};function n(e){var o=r[e];if(void 0!==o)return o.exports;var a=r[e]={exports:{}};return t[e](a,a.exports,n),a.exports}n.m=t,e=[],n.O=(t,r,o,a)=>{if(!r){var i=1/0;for(u=0;u<e.length;u++){for(var[r,o,a]=e[u],s=!0,l=0;l<r.length;l++)(!1&a||i>=a)&&Object.keys(n.O).every((e=>n.O[e](r[l])))?r.splice(l--,1):(s=!1,a<i&&(i=a));if(s){e.splice(u--,1);var c=o();void 0!==c&&(t=c)}}return t}a=a||0;for(var u=e.length;u>0&&e[u-1][2]>a;u--)e[u]=e[u-1];e[u]=[r,o,a]},n.n=e=>{var t=e&&e.__esModule?()=>e.default:()=>e;return n.d(t,{a:t}),t},n.d=(e,t)=>{for(var r in t)n.o(t,r)&&!n.o(e,r)&&Object.defineProperty(e,r,{enumerable:!0,get:t[r]})},n.g=function(){if("object"==typeof globalThis)return globalThis;try{return this||new Function("return this")()}catch(e){if("object"==typeof window)return window}}(),n.o=(e,t)=>Object.prototype.hasOwnProperty.call(e,t),(()=>{var e={440:0,458:0};n.O.j=t=>0===e[t];var t=(t,r)=>{var o,a,[i,s,l]=r,c=0;if(i.some((t=>0!==e[t]))){for(o in s)n.o(s,o)&&(n.m[o]=s[o]);if(l)var u=l(n)}for(t&&t(r);c<i.length;c++)a=i[c],n.o(e,a)&&e[a]&&e[a][0](),e[a]=0;return n.O(u)},r=self.webpackChunkdatomatic_custom_value_metric=self.webpackChunkdatomatic_custom_value_metric||[];r.forEach(t.bind(null,0)),r.push=t.bind(null,r.push.bind(r))})(),n.O(void 0,[458],(()=>n(794)));var o=n.O(void 0,[458],(()=>n(962)));o=n.O(o)})();