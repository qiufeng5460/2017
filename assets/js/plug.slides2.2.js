/*
* 幻灯片插件写法
* Writer Zhangyp
* CreateDate: 2014-11-19
* UpdateDate: 2015-12-09

使用方法案例：
<div id="advertisement" class="slides">
	<ul class="slides" style="height:{$list[0]['img_height']}px;overflow:hidden;">
		<volist name="list" id="vo">
			<li class="slides" style="background:{$vo.bgcolor};">
				<empty name="vo.url">
					<img src="/uploads/Link/{$vo.img_name}" alt="{$vo.title}"/>
				<else/>
					<a href="{$vo.url}" target="_blank" title="{$vo.title}"><img src="/uploads/Link/{$vo.img_name}" alt="{$vo.title}"/></a>
				</empty>
			</li>
		</volist>
	</ul>
</div>
<script type="text/javascript">$("#advertisement").slides();</script>

注意：ul.slides必须的height和overflow两个css属性
*/
(function ($) {
	var methods = {
		init:function(options){
			var settings = $.extend({
				'index':0,//默认显示的下标
				'btnShow':true,//是否显示左右按钮
				'btnIndexShow':true,//是否显示下标按钮
				'autoScrool':true,//是否定时滚动
				'speed':'3',//定时滚动时间间隔(单位：秒)
				'height':'0'//高度(单位：像素)
			},options);
			return this.each(function(){
				var divObj=$(this);
				/*
				var liWidthStr=divObj.css("width");
				var liWidth=parseInt(liWidthStr.substr(0,liWidthStr.length-2));
				var liHightStr=divObj.children("ul.slides").css("height");
				var liHight=parseInt(liHightStr.substr(0,liHightStr.length-2));
				*/
				var liWidth=parseInt(settings.width);
				var liHight=parseInt(settings.height);
				var bodyW=parseInt(document.body.clientWidth);
				if(liWidth>bodyW){
					//liHight=liHight*bodyW/liWidth;
					liWidth=bodyW;
				}
				
				divObj.children("ul.slides").css({"height":liHight+"px"});

				var slides_nav_a='<img src="http://localhost/wp-content/themes/twentyseventeen-child/assets/js/plugslides/slides_nav_a.png" style="border:0px;"/>';
				var slides_nav_a_cur='<img src="http://localhost/wp-content/themes/twentyseventeen-child/assets/js/plugslides/slides_nav_a_cur.png" style="border:0px;"/>';
				var liLength=divObj.children("ul.slides").children("li.slides").length;
				divObj.append('<div class="slides_nav"></div>');
				for(var i=1;i<=liLength;i++){
					if(i==1){
						divObj.find("div.slides_nav").append("<a hidefocus='true'>"+slides_nav_a_cur+"</a>");
					}else{
						divObj.find("div.slides_nav").append("<a hidefocus='true'>"+slides_nav_a+"</a>");
					}
				}
				divObj.children("ul.slides").append(divObj.children("ul.slides").html());
				if(settings.btnShow){
					divObj.append('<a class="pre" title="pre" hidefocus="true"><i class="png"></i></a>');
					divObj.append('<a class="next" title="next" hidefocus="true"><i class="png"></i></a>');
				}
				
				divObj.css({"position":"relative","width":liWidth+"px","height":liHight+"px","overflow":"hidden","margin":"0px auto"});
				//art.dialog.tips(liWidth);
				
				divObj.children("ul.slides").css({"position":"absolute","left":"0px","width":liLength*liWidth*2+"px","height":liHight+"px","list-style-type":"none","margin":"0px","padding":"0px","overflow":"hidden"});
				divObj.children("ul.slides").children("li.slides").css({"float":"left","width":liWidth+"px","height":liHight+"px","overflow":"hidden","text-align":"center"});
				divObj.children("ul.slides").children("li.slides").children("a").css({"display":"block","overflow":"hidden","height":"100%","width":"100%"});
				//divObj.children("ul.slides").children("li.slides").children("img").css({"width":liWidth+"px"});
				
				//divObj.children("ul.slides").children("li.slides").css({"font-size":liHight/2+"px","line-height":liHight+"px"});//临时调试用的
				divObj.children("div.slides_nav").css({"position":"absolute","bottom":"5px","text-align":"center","width":liWidth+"px"});
				divObj.children("div.slides_nav").children("a").css({"padding":"8px","cursor":"default"});
				
				divObj.children("a.pre").css({"display":"block","top":(liHight-60)/2+"px","position":"absolute","left":"15px","text-decoration":"none","font-weight":"bold","height":"60px","width":"30px","text-align":"center","line-height":"60px","-moz-opacity":"0.5","opacity":"0.5","filter":"alpha(opacity=50)","background":"#aeaeae","cursor":"pointer"});
				divObj.children("a.next").css({"display":"block","top":(liHight-60)/2+"px","position":"absolute","right":"15px","text-decoration":"none","font-weight":"bold","height":"60px","width":"30px","text-align":"center","line-height":"60px","-moz-opacity":"0.5","opacity":"0.5","filter":"alpha(opacity=50)","background":"#aeaeae","cursor":"pointer"});
				divObj.children("a.pre").children("i").css({"width":"21px","height":"32px","position":"absolute","left":"4.5px","top":"14px","background":"url(http://localhost/wp-content/themes/twentyseventeen-child/assets/js/plugslides/slider_pre_next.png) no-repeat","background-position":"-72px -172px","overflow":"hidden"});
				divObj.children("a.next").children("i").css({"width":"21px","height":"32px","position":"absolute","left":"4.5px","top":"14px","background":"url(http://localhost/wp-content/themes/twentyseventeen-child/assets/js/plugslides/slider_pre_next.png) no-repeat","background-position":"-42px -172px","overflow":"hidden"});
				
				var flag=true;
				var index=settings.index;
				divObj.find("div.slides_nav").children("a").eq(index).html(slides_nav_a_cur).siblings("a").html(slides_nav_a);
				divObj.parent("div").css({"background-color":divObj.children("ul.slides").children("li.slides").eq(index).css("background-color")});
				
				if(settings.btnIndexShow==false){
					divObj.find("div.slides_nav").hide();
				}
				
				divObj.children("ul.slides").css("left","-"+liWidth*index+"px");
				//上一张
				divObj.children("a.pre").bind("click",function(){
					if(!flag){
						return;
					}
					flag=false;
					index--;
					if(index<0){
						index=liLength-1;
						divObj.children("ul.slides").css("left","-"+liWidth*(index+1)+"px");
					}else{
						divObj.find("div.slides_nav").children("a").eq(index).html(slides_nav_a_cur).siblings("a").html(slides_nav_a);
					}
					var ulLeftStr=divObj.children("ul.slides").css("left");
					var ulLeft=parseInt(ulLeftStr.substr(0,ulLeftStr.length-2));
					divObj.children("ul.slides").animate({left:"-"+liWidth*index+"px"},500,function(){
						flag=true;
					});
					divObj.parent("div").css({"background-color":divObj.children("ul.slides").children("li.slides").eq(index).css("background-color")});
				});
				//下一张
				divObj.children("a.next").bind("click",function(){
					if(!flag){
						return;
					}
					flag=false;
					index++;
					if(index>=liLength){
						divObj.find("div.slides_nav").children("a").eq(0).html(slides_nav_a_cur).siblings("a").html(slides_nav_a);
					}else{
						divObj.find("div.slides_nav").children("a").eq(index).html(slides_nav_a_cur).siblings("a").html(slides_nav_a);
					}
					var ulLeftStr=divObj.children("ul.slides").css("left");
					var ulLeft=parseInt(ulLeftStr.substr(0,ulLeftStr.length-2));
					divObj.children("ul.slides").animate({left:"-"+liWidth*index+"px"},500,function(){
						if(index>=liLength){
							index=0;
							divObj.children("ul.slides").css("left","0px");
						}
						flag=true;
					});
					divObj.parent("div").css({"background-color":divObj.children("ul.slides").children("li.slides").eq(index).css("background-color")});
				});
				//鼠标点击导航
				divObj.find("div.slides_nav").children("a").bind("mouseenter",function(e){
					if(!flag){
						return;
					}
					flag=false;
					index=$(this).index();
					divObj.find("div.slides_nav").children("a").eq(index).html(slides_nav_a_cur).siblings("a").html(slides_nav_a);
					var ulLeftStr=divObj.children("ul.slides").css("left");
					var ulLeft=parseInt(ulLeftStr.substr(0,ulLeftStr.length-2));
					divObj.children("ul.slides").animate({left:"-"+liWidth*index+"px"},1,function(){
						flag=true;;
					});
					divObj.parent("div").css({"background-color":divObj.children("ul.slides").children("li.slides").eq(index).css("background-color")});
				});
				if(liLength==1){
					return;
				}
				var clearId=0;
				if(settings.autoScrool){
					clearId=setInterval(function(){
						if(!flag){
							return;
						}
						flag=false;
						index++;
						if(index>=liLength){
							divObj.find("div.slides_nav").children("a").eq(0).html(slides_nav_a_cur).siblings("a").html(slides_nav_a);
						}else{
							divObj.find("div.slides_nav").children("a").eq(index).html(slides_nav_a_cur).siblings("a").html(slides_nav_a);
						}
						var ulLeftStr=divObj.children("ul.slides").css("left");
						var ulLeft=parseInt(ulLeftStr.substr(0,ulLeftStr.length-2));
						divObj.children("ul.slides").animate({left:"-"+liWidth*index+"px"},500,function(){
							if(index>=liLength){
								index=0;
								divObj.children("ul.slides").css("left","0px");
							}
							flag=true;
						});
						divObj.parent("div").css({"background-color":divObj.children("ul.slides").children("li.slides").eq(index).css("background-color")});
					},settings.speed*1000);
				}
				divObj.bind("mouseenter",function(){
					clearInterval(clearId);
				});
				divObj.bind("mouseleave",function(){
					clearId=setInterval(function(){
						if(!flag){
							return;
						}
						flag=false;
						index++;
						if(index>=liLength){
							divObj.find("div.slides_nav").children("a").eq(0).html(slides_nav_a_cur).siblings("a").html(slides_nav_a);
						}else{
							divObj.find("div.slides_nav").children("a").eq(index).html(slides_nav_a_cur).siblings("a").html(slides_nav_a);
						}
						var ulLeftStr=divObj.children("ul.slides").css("left");
						var ulLeft=parseInt(ulLeftStr.substr(0,ulLeftStr.length-2));
						divObj.children("ul.slides").animate({left:"-"+liWidth*index+"px"},500,function(){
							if(index>=liLength){
								index=0;
								divObj.children("ul.slides").css("left","0px");
							}
							flag=true;
						});
						divObj.parent("div").css({"background-color":divObj.children("ul.slides").children("li.slides").eq(index).css("background-color")});
					},settings.speed*1000);
				});
			});
		},
		autoScrool:function(){
			//业务代码
		}
	};

	$.fn.slides=function(method){
		// 方法调用
		if (methods[method]) {
			return methods[method].apply(this, Array.prototype.slice.call(arguments,1));
		}else if(typeof method === 'object' || !method) {
			return methods.init.apply(this, arguments);
		}else{
			alert('Method：' + method + ' does not exist on jQuery.slides');
		}
	}
        
        $("#advertisement").slides({"height":300,"width":600});
})(jQuery);
