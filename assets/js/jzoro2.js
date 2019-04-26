/*
需要预设的参数包括：
总图片个数：html中li数
显示图片个数：js中show_pic_num变量
图片间隔：css中margin-right
以下参数不需要预设，由js获取或设置：
图片实际尺寸，show_box显示图片宽度，pic_box总图片宽度
*/

(function ($) {
	
$( document ).ready( function() {

    //用全局变量index接收当前的下标，后期如需做分页器更方便使用。
    var index = 0;
    //lock开关锁思想： 防止用户快速点击按钮，出现动画未结束就进行下一个动画，以致轮播出现混乱。
    var lock = true;
	//li数量
	var li_length=$("#pic_box li").length;
	//需要显示的pic数量
	var show_pic_num=5;
	//从CSS图片间隔，直接返回的是50px，需要从字符串中提取数值
	var margin_right=parseInt($(".pic_box li").css("margin-right").replace(/[^0-9]/ig,""));
	//从CSS获取图片尺寸
	var img_num=li_length;
	var pic_width=pic_height=0;
	$(".pic_box img").each(function(i){
		var img=$(this);
		//$("<img/>")这里是创建一个临时的img标签，类似js创建一个new Image()对象！
		$("<img/>").attr("src",$(img).attr("src")).load(function(){
	    /*
        如果要获取图片的真实的宽度和高度有三点必须注意
        1、需要创建一个image对象：如这里的$("<img/>")
        2、指定图片的src路径,设置给创建的image对象
        3、一定要在图片加载完成后执行如.load()函数里执行
        */
		//如果尺寸是0的话说明没有图片
	    if((!this.width) || (!this.height)){
			alert("pic size is zore");
		}
		//在图片尺寸都不为0的时候比较本次图片和上次图片的尺寸确认尺寸是否有变化
		if(pic_width&&pic_height&&this.width&&this.height){
			if((pic_width!=this.width) || (pic_height!=this.height)){
				alert("pic size is different");
			}
		}
		
		pic_width=this.width;
		pic_height=this.height;
        //需要等pic加载完之后才能获取正确的pic size，然后计算出show_box和pic_box的真正需要宽度，并设置CSS
		if(!--img_num){
			
             $(".show_box").css({width:show_pic_num*(pic_width+margin_right)});
			 $(".pic_box").css({width:(show_pic_num+li_length)*(pic_width+margin_right)});
		}
		})
		
	});
    //是否显示左右箭头，默认不显示
	var arr_show=false;
	if(!arr_show){
		$("#arr span").css({"display":"none"})
	}

	
	//将li的需要显示的数量的pic复制到li列表最后，避免留空白
	$("#pic_box").append($("#pic_box li").slice(0,show_pic_num).clone(true));

    

    function rightmove(){
	    if(lock){
		lock=false;
		index++;

		$(".pic_box").animate({left:"-"+(pic_width+margin_right)*index+"px"},"slow",function(){
			if(index==li_length){
				$(".pic_box").css("left","0px");
				index=0;
			}
           lock=true;
		});
		}	
	}  

    function leftmove(){
		if(lock){
			lock=false;
			index--;
			if(index<0){
				index=li_length-1;
				$(".pic_box").css("left","-"+(pic_width+margin_right)*li_length+"px");
			}


			$(".pic_box").animate({left:"-"+(pic_width+margin_right)*index+"px"},"slow",function(){
				lock=true;
			});
		}
	}	
	 
	$("#right").click(function(){
        
        rightmove();

	});
	
	$("#left").click(function(){
        leftmove();
	});
	
	var flag;
    function autoPlay(){
      flag = setInterval(function(){
        rightmove();
      },2000);
    }
	
	$(".show_box").mouseenter(function(){
		//$("#arr").css("display":"block");
		clearInterval(flag);
	});
	
    $(".show_box").mouseleave(function(){
		//$("#arr").css("display":"none");		
		autoPlay();
	});
	
	autoPlay();
});

})(jQuery);
