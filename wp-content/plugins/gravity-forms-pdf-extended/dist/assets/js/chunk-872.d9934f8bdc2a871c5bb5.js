"use strict";(self.webpackChunkgravity_pdf=self.webpackChunkgravity_pdf||[]).push([[872],{4872:(e,t,r)=>{r.r(t),r.d(t,{TemplateSingle:()=>O,default:()=>F});r(1817),r(4916),r(4723);var s=r(7294),a=r(5697),p=r.n(a),n=r(6706),i=r(5439),l=(r(6992),r(3948),r(7658),r(6550));function o(e,t,r){return t in e?Object.defineProperty(e,t,{value:r,enumerable:!0,configurable:!0,writable:!0}):e[t]=r,e}
/**
 * Renders the template navigation header that get displayed on the
 * /template/:id pages.
 *
 * @package     Gravity PDF
 * @copyright   Copyright (c) 2022, Blue Liquid Designs
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       4.1
 */class m extends s.Component{constructor(){super(...arguments),o(this,"handlePreviousTemplate",(e=>{e.preventDefault(),e.stopPropagation();var t=this.props.templates[this.props.templateIndex-1].id;t&&this.props.history.push("/template/"+t)})),o(this,"handleNextTemplate",(e=>{e.preventDefault(),e.stopPropagation();var t=this.props.templates[this.props.templateIndex+1].id;t&&this.props.history.push("/template/"+t)})),o(this,"handleKeyPress",(e=>{this.props.isFirst||37!==e.keyCode||this.handlePreviousTemplate(e),this.props.isLast||39!==e.keyCode||this.handleNextTemplate(e)}))}componentDidMount(){window.addEventListener("keydown",this.handleKeyPress,!1)}componentWillUnmount(){window.removeEventListener("keydown",this.handleKeyPress,!1)}render(){var e=this.props.isFirst,t=this.props.isLast,r=e?"dashicons dashicons-no left disabled":"dashicons dashicons-no left",a=t?"dashicons dashicons-no right disabled":"dashicons dashicons-no right",p=e?"disabled":"",n=t?"disabled":"";return s.createElement("span",null,s.createElement("button",{onClick:this.handlePreviousTemplate,onKeyDown:this.handleKeyPress,className:r,tabIndex:"141",disabled:p},s.createElement("span",{className:"screen-reader-text"},this.props.showPreviousTemplateText)),s.createElement("button",{onClick:this.handleNextTemplate,onKeyDown:this.handleKeyPress,className:a,tabIndex:"141",disabled:n},s.createElement("span",{className:"screen-reader-text"},this.props.showNextTemplateText)))}}o(m,"propTypes",{templates:p().array.isRequired,templateIndex:p().number.isRequired,history:p().object,isFirst:p().bool,isLast:p().bool,showPreviousTemplateText:p().string,showNextTemplateText:p().string});const c=(0,l.EN)((0,n.$j)(((e,t)=>{var r=t.templates,s=t.template.id,a=r.length-1;return{isFirst:r[0].id===s,isLast:r[a].id===s}}))(m));var h=r(6152),d=r(4738);function u(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var s=Object.getOwnPropertySymbols(e);t&&(s=s.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,s)}return r}function T(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?u(Object(r),!0).forEach((function(t){x(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):u(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}function x(e,t,r){return t in e?Object.defineProperty(e,t,{value:r,enumerable:!0,configurable:!0,writable:!0}):e[t]=r,e}
/**
 * Renders a delete button which then queries our server and
 * removes the selected PDF template
 *
 * @package     Gravity PDF
 * @copyright   Copyright (c) 2022, Blue Liquid Designs
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       4.1
 */class g extends s.Component{constructor(){super(...arguments),x(this,"deleteTemplate",(e=>{e.preventDefault(),e.stopPropagation(),window.confirm(this.props.templateConfirmDeleteText)&&(this.props.templateProcessing(this.props.template.id),"success"===this.props.getTemplateProcessing&&this.props.history.push("/template"),this.props.onTemplateDelete(this.props.template.id))})),x(this,"ajaxFailed",(()=>{var e=T(T({},this.props.template),{},{error:this.props.templateDeleteErrorText});this.props.addTemplate(e),this.props.history.push("/template"),this.props.clearTemplateProcessing()}))}componentDidUpdate(){var{getTemplateProcessing:e,history:t}=this.props;"success"===e&&t.push("/template"),"failed"===e&&this.ajaxFailed()}render(){var e=this.props.callbackFunction?this.props.callbackFunction:this.deleteTemplate;return s.createElement("a",{onClick:e,href:"#",tabIndex:"150",className:"button button-secondary delete-theme ed_button","aria-label":this.props.buttonText+" "+GFPDF.template},this.props.buttonText)}}x(g,"propTypes",{template:p().object,addTemplate:p().func,onTemplateDelete:p().func,callbackFunction:p().func,templateProcessing:p().func,clearTemplateProcessing:p().func,getTemplateProcessing:p().string,history:p().object,buttonText:p().string,templateConfirmDeleteText:p().string,templateDeleteErrorText:p().string});const b=(0,l.EN)((0,n.$j)((e=>({getTemplateProcessing:e.template.templateProcessing})),(e=>({addTemplate:t=>{e((0,d.WF)(t))},onTemplateDelete:t=>{e((0,d.Xc)(t))},templateProcessing:t=>{e((0,d.er)(t))},clearTemplateProcessing:()=>{e((0,d.Lt)())}})))(g));function f(e,t,r){return t in e?Object.defineProperty(e,t,{value:r,enumerable:!0,configurable:!0,writable:!0}):e[t]=r,e}
/**
 * Renders the template footer actions that get displayed on the
 * /template/:id pages.
 *
 * @package     Gravity PDF
 * @copyright   Copyright (c) 2022, Blue Liquid Designs
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       4.1
 */class v extends s.Component{constructor(){super(...arguments),f(this,"notCoreTemplate",(e=>-1!==e.path.indexOf(this.props.pdfWorkingDirPath)))}render(){var e=this.props.template,t=e.compatible;return s.createElement("div",{className:"theme-actions"},!this.props.isActiveTemplate&&t?s.createElement(h.ZP,{template:e,buttonText:this.props.activateText}):null,!this.props.isActiveTemplate&&this.notCoreTemplate(e)?s.createElement(b,{template:e,ajaxUrl:this.props.ajaxUrl,ajaxNonce:this.props.ajaxNonce,buttonText:this.props.templateDeleteText,templateConfirmDeleteText:this.props.templateConfirmDeleteText,templateDeleteErrorText:this.props.templateDeleteErrorText}):null)}}f(v,"propTypes",{template:p().object.isRequired,isActiveTemplate:p().bool,ajaxUrl:p().string,ajaxNonce:p().string,activateText:p().string,pdfWorkingDirPath:p().string,templateDeleteText:p().string,templateConfirmDeleteText:p().string,templateDeleteErrorText:p().string});const P=v;
/**
 * Display the Template Screenshot for the individual templates (uses different markup - out of our control)
 *
 * @package     Gravity PDF
 * @copyright   Copyright (c) 2022, Blue Liquid Designs
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       4.1
 */
var y=e=>{var{image:t}=e,r=t?"screenshot":"screenshot blank";return s.createElement("div",{className:"theme-screenshots"},s.createElement("div",{className:r},t?s.createElement("img",{src:t,alt:""}):null))};y.propTypes={image:p().string};const D=y;var E,j,w,N=r(38),C=r(5218),k=r(3223);
/**
 * Renders a single PDF template, which get displayed on the /template/:id page.
 *
 * @package     Gravity PDF
 * @copyright   Copyright (c) 2022, Blue Liquid Designs
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       4.1
 */
class O extends s.Component{shouldComponentUpdate(e){return null!=e.template}render(){var e=this.props.template,t=this.props.activeTemplate===e.id;return s.createElement(i.Z,{header:s.createElement(c,{template:e,templateIndex:this.props.templateIndex,templates:this.props.templates,showPreviousTemplateText:this.props.showPreviousTemplateText,showNextTemplateText:this.props.showNextTemplateText}),footer:s.createElement(P,{template:e,isActiveTemplate:t,ajaxUrl:this.props.ajaxUrl,ajaxNonce:this.props.ajaxNonce,activateText:this.props.activateText,pdfWorkingDirPath:this.props.pdfWorkingDirPath,templateDeleteText:this.props.templateDeleteText,templateConfirmDeleteText:this.props.templateConfirmDeleteText,templateDeleteErrorText:this.props.templateDeleteErrorText}),closeRoute:"/template"},s.createElement("div",{id:"gfpdf-template-detail-view",className:"gfpdf-template-detail"},s.createElement(D,{image:e.screenshot}),s.createElement("div",{className:"theme-info"},s.createElement(C.PP,{isCurrentTemplate:t,label:this.props.currentTemplateText}),s.createElement(C.VG,{name:e.template,version:e.version,versionLabel:this.props.versionText}),s.createElement(C.S3,{author:e.author,uri:e["author uri"]}),s.createElement(C.ZA,{group:e.group,label:this.props.groupText}),e.long_message?s.createElement(N.Z,{text:e.long_message}):null,e.long_error?s.createElement(N.Z,{text:e.long_error,error:!0}):null,s.createElement(C.dk,{desc:e.description}),s.createElement(C.$G,{tags:e.tags,label:this.props.tagsText}))))}}E=O,j="propTypes",w={template:p().object,activeTemplate:p().string,templateIndex:p().number,templates:p().array,showPreviousTemplateText:p().string,showNextTemplateText:p().string,ajaxUrl:p().string,ajaxNonce:p().string,activateText:p().string,pdfWorkingDirPath:p().string,templateDeleteText:p().string,templateConfirmDeleteText:p().string,templateDeleteErrorText:p().string,currentTemplateText:p().string,versionText:p().string,groupText:p().string,tagsText:p().string},j in E?Object.defineProperty(E,j,{value:w,enumerable:!0,configurable:!0,writable:!0}):E[j]=w;const F=(0,n.$j)(((e,t)=>{var r=(0,k.ZP)(e),s=t.match.params.id,a=e=>e.id===s;return{template:r.find(a),templateIndex:r.findIndex(a),templates:r,activeTemplate:e.template.activeTemplate}}))(O)}}]);