$(document).ready(function(){var e=$.getUrlParameter(window.location,"flower");function t(e,t,o,n){let a=document.createElementNS("http://www.w3.org/2000/svg","image");a.setAttribute("class","flower"),a.setAttribute("href",e.image),a.setAttribute("data-id",e.hash),a.setAttribute("data-mine",e.mine),a.setAttribute("data-timestamp",e.timestamp),a.setAttribute("data-link",e.link),a.setAttribute("data-owner-id",null!==e.owner?e.owner.id:null),a.setAttribute("data-owner-name",null!==e.owner?e.owner.name:null),a.setAttribute("data-owner-link",null!==e.owner?e.owner.link:null),a.setAttribute("data-toggle","modal"),a.setAttribute("x",t[0]),a.setAttribute("y",t[1]),a.setAttribute("width",o[0]),a.setAttribute("height",o[1]),n.appendChild(a)}let o=3.1415926535,n=100,a=[100,n];var i=0,r=0,l=0,s=[0,0],d=0,m=0,h=0,u=0,f=0;function w(){if(0===i||6===r&&l===i){i+=1,l=0,r=1;let e=s;if(s=[s[0],s[1]-n],d=-o/6,1===i)return f=100,m=100,e}return l===i&&(d-=o/3,l=0,r+=1),l+=1,(s=[s[0]+n*Math.cos(d),s[1]-n*Math.sin(d)])[0]<m?m=s[0]:s[0]+a[0]>h&&(h=s[0]+a[0]),s[1]<u?u=s[1]:s[1]+a[1]>f&&(f=s[1]+a[1]),s}let c=document.getElementById("flowersGrid"),g=document.getElementById("flowersGridGroup"),v=$("#flowersGrid").data("data");$.get(v,function(o){if(!0===o.success)if(o.data&&o.data.flowers){let n=$("#socialModal").data("garden"),i=void 0!==Cookies.get("donation_made_"+n);for(let n=0;n<o.data.flowers.length;n++)t(o.data.flowers[n],w(),a,g),i||null===e||o.data.flowers[n].hash!==e||_(e);let r=$(window).width();r<$(window).height()&&(r=$(window).height()),Math.sqrt(o.data.flowers.length)>r&&(r=100*Math.sqrt(o.data.flowers.length)),$(c).css("width","100%"),$(c).css("height","100%"),c.setAttribute("viewBox",m+" "+u+" "+(h-m)+" "+(f-u));svgPanZoom("#flowersGrid",{panEnabled:!0,controlIconsEnabled:!1,zoomEnabled:!0,dblClickZoomEnabled:!1,mouseWheelZoomEnabled:!0,preventMouseEventsDefault:!0,zoomScaleSensitivity:.2,minZoom:.5,maxZoom:1.5,fit:!1,contain:!0,center:!0,refreshRate:"auto",beforePan:function(e,t){let o=this.getSizes(),n=-(o.viewBox.x+o.viewBox.width)*o.realZoom+100,a=o.width-100-o.viewBox.x*o.realZoom,i=-(o.viewBox.y+o.viewBox.height)*o.realZoom+100,r=o.height-100-o.viewBox.y*o.realZoom,l={};return l.x=Math.max(n,Math.min(a,t.x)),l.y=Math.max(i,Math.min(r,t.y)),l},customEventsHandler:p})}else console.log("datarequest error");else o.messages&&o.messages.length,console.log("datarequest error")},"json"),$(c).height("100%"),$(c).width("100%"),c.setAttribute("viewBox",m+" "+u+" "+(h-m)+" "+(f-u));var p,b=$("#flowerModal");function _(e){let t=$(".flower[data-id="+e+"]");if(t.length>0){let o=$(".flower-info"),n=o.find(".flower-info__owner"),a=$(c).height(),i=$(c).width(),r=0;r=a<i?a/(f-u):i/(h-m),null!==t.data("owner-name")?(n.html(t.data("owner-name")),null!==t.data("owner-link")?n.attr("href",t.data("owner-link")):n.removeAttr("href")):n.html(n.data("default")).removeAttr("href"),o.find(".flower-info__date").html("Расцвел "+moment.unix(t.data("timestamp")).fromNow()),o.find(".flower-info__image").attr("style","background-image: url("+t.attr("href")+")"),o.find(".flower-info__socials a").each(function(){$(this).attr("href",String($(this).data("link")).replace("{link}",encodeURIComponent(t.data("link")))),$("#copyLinkInput").val(t.data("link"))}),history.pushState(null,document.getElementsByTagName("title")[0].innerHTML,$.setUrlParameter(window.location,"flower",e)),b.modal("show"),y()}}function y(){var e=$("#infoCard"),t=e.outerHeight()-e.find(".garden__name").outerHeight()-e.find(".garden__donate").outerHeight()-40;e.find(".garden__donate").outerHeight();$(window).width()<576?e.css("bottom",-t).css("top","inherit"):e.removeAttr("style")}b.on("hidden.bs.modal",function(){history.pushState(null,document.getElementsByTagName("title")[0].innerHTML,$.setUrlParameter(window.location,"flower",null))}),$("body").on("mousedown",".flower",function(e){$(".flower").on("mouseup mousemove",function e(t){"mouseup"===t.type&&_($(this).data("id")),$(".flower").off("mouseup mousemove",e)})}),$(".flower-info .tooltip__close").click(function(){setTimeout(function(){$(".flower-info__share-block").slideUp(),$(".flower-info__arrow").show(),$(".flower-info__btn-share").show()},300)}),$(".flower-info__btn-share").click(function(){$(".flower-info__share-block").slideDown(),$(".flower-info__arrow").hide(),$(this).hide()}),y(),$(window).resize(function(){y()}),p={haltEventListeners:["touchstart","touchend","touchmove","touchleave","touchcancel"],init:function(e){var t=e.instance,o=1,n=0,a=0;this.hammer=Hammer(e.svgElement,{inputClass:Hammer.SUPPORT_POINTER_EVENTS?Hammer.PointerEventInput:Hammer.TouchInput}),this.hammer.get("pinch").set({enable:!0}),this.hammer.on("doubletap",function(e){t.zoomIn()}),this.hammer.on("panstart panmove",function(e){"panstart"===e.type&&(n=0,a=0),t.panBy({x:e.deltaX-n,y:e.deltaY-a}),n=e.deltaX,a=e.deltaY}),this.hammer.on("pinchstart pinchmove",function(e){"pinchstart"===e.type&&(o=t.getZoom(),t.zoomAtPoint(o*e.scale,{x:e.center.x,y:e.center.y})),t.zoomAtPoint(o*e.scale,{x:e.center.x,y:e.center.y})}),e.svgElement.addEventListener("touchmove",function(e){e.preventDefault()})},destroy:function(){this.hammer.destroy()}};let x=$("#socialModal"),A=x.data("garden");void 0!==Cookies.get("donation_made_"+A)&&x.modal("show"),x.on("hidden.bs.modal",function(){null!==e&&_(e)})});
