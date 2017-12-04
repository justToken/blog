/*
-------Jane template jquery-------
  author : 秋天日记
  url : http://www.qtnote.com
*/

;(function($, window, undefined) {

    //识别设备

    var isMobile = {
       Android: function() {
           return navigator.userAgent.match(/Android/i) ? true: false;
       },
       BlackBerry: function() {
           return navigator.userAgent.match(/BlackBerry/i) ? true: false;
       },
       iOS: function() {
           return navigator.userAgent.match(/iPhone/i) ? true: false;
       },
       Windows: function() {
           return navigator.userAgent.match(/IEMobile/i) ? true: false;
       },
       any: function() {
           return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Windows());
       },

    }

      //处理pc nav dropmenu
    if(!isMobile.any()){
             $('ul.nav li.dropdown').hover(function(){
                   $(this).children('ul.dropdown-menu').fadeIn(); 
               }, function(){
                   $(this).children('ul.dropdown-menu').stop().fadeOut(); 
              });
            $(document).off('click.bs.dropdown.data-api'); 
      
        }
        //导航 fixed
        $(window).scroll(function(){
            if($(this).scrollTop()>160){
                  $('#nav-collapse').addClass('nav-collapse-fixed');
                  $('.navbar').addClass('nav-fixed');
            } else {
                  $('#nav-collapse').removeClass('nav-collapse-fixed');
                  $('.navbar').removeClass('nav-fixed');
        }});

        //更改bootstarp tabs click 为 hover
        $(document).off('click.bs.tab.data-api', '[data-hover="tab"]');
        $(document).on('mouseenter.bs.tab.data-api', '[data-toggle="tab"], [data-hover="tab"]', function () {
          $(this).tab('show');
        });

       //使自适应导航与搜索框之间切换
         $('.navbar-toggle').click(function(){
              $('#nav-collapse').collapse('hide');
         });

         $('#search-toggle').click(function(){
              $('.navbar-collapse').collapse('hide');
         });


            $("#myCarousel").carousel('cycle');// 初始化轮播
          
           $("[data-toggle='tooltip']").tooltip();//tooltip
         
    /*-----------goTop---------------*/
        var $backTop = this.$backTop = $('<div class="cbbfixed">'+
          '<a class="gotop cbbtn">'+
            '<span class="glyphicon glyphicon-chevron-up"></span>'+
          '</a>'+
        '</div>');
        $('body').append($backTop);
        
        $backTop.click(function(){
          $("html, body").animate({
            scrollTop: 0
          }, 500);
        });

        var timmer = null;
        $(window).bind("scroll",function() {
                var d = $(document).scrollTop(),
                e = $(window).height();
                0 < d ? $backTop.css("bottom", "50px") : $backTop.css("bottom", "-50px");
          clearTimeout(timmer);
          timmer = setTimeout(function() {
                    clearTimeout(timmer)
                },500);
         });
/*-----------goTop END---------------*/

/*------------------图片延时加载 使用jquery.lazyload.js插件-----------------*/
eval(function(p,a,c,k,e,d){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--){d[e(c)]=k[c]||e(c)}k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1};while(c--){if(k[c]){p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c])}}return p}('(5($){$.J.L=5(r){8 1={d:0,A:0,b:"h",v:"N",3:4};6(r){$.D(1,r)}8 m=9;6("h"==1.b){$(1.3).p("h",5(b){8 C=0;m.t(5(){6(!$.k(9,1)&&!$.l(9,1)){$(9).z("o")}j{6(C++>1.A){g B}}});8 w=$.M(m,5(f){g!f.e});m=$(w)})}g 9.t(5(){8 2=9;$(2).c("s",$(2).c("i"));6("h"!=1.b||$.k(2,1)||$.l(2,1)){6(1.u){$(2).c("i",1.u)}j{$(2).K("i")}2.e=B}j{2.e=x}$(2).T("o",5(){6(!9.e){$("<V />").p("X",5(){$(2).Y().c("i",$(2).c("s"))[1.v](1.Z);2.e=x}).c("i",$(2).c("s"))}});6("h"!=1.b){$(2).p(1.b,5(b){6(!2.e){$(2).z("o")}})}})};$.k=5(f,1){6(1.3===E||1.3===4){8 7=$(4).F()+$(4).O()}j{8 7=$(1.3).n().G+$(1.3).F()}g 7<=$(f).n().G-1.d};$.l=5(f,1){6(1.3===E||1.3===4){8 7=$(4).I()+$(4).U()}j{8 7=$(1.3).n().q+$(1.3).I()}g 7<=$(f).n().q-1.d};$.D($.P[\':\'],{"Q-H-7":"$.k(a, {d : 0, 3: 4})","R-H-7":"!$.k(a, {d : 0, 3: 4})","S-y-7":"$.l(a, {d : 0, 3: 4})","q-y-7":"!$.l(a, {d : 0, 3: 4})"})})(W);',62,62,'|settings|self|container|window|function|if|fold|var|this||event|attr|threshold|loaded|element|return|scroll|src|else|belowthefold|rightoffold|elements|offset|appear|bind|left|options|original|each|placeholder|effect|temp|true|of|trigger|failurelimit|false|counter|extend|undefined|height|top|the|width|fn|removeAttr|lazyload|grep|show|scrollTop|expr|below|above|right|one|scrollLeft|img|jQuery|load|hide|effectspeed'.split('|'),0,{}))
  $("#log-list img,#echo-log img,#side img").lazyload({
  placeholder : "./content/templates/jane/images/load.gif",   
  effect : "fadeIn"
  });
/*-----------END-------图片延时加载 使用jquery.lazyload.js插件---------END--------*/
	

})(jQuery, this);
window.onload=function(){

	$('div').removeClass('loader');
}

/* jQuery Ajax 提交、刷新 评论   本插件由 简爱 http://www.gouji.org/ 提取完善、移植 至 EMLOG */
/*由 秋天日记 修改整合在Jane主题*/
// JS 仿 PHP 获取 GET 数据
var $_GET=(function(){
  var js=document.scripts;
  var url=js[js.length-1].src;
  var u=url.split("?");
  if(typeof(u[1])=="string"){
    u=u[1].split("&");
    var get={};
    for(var i in u){
      var j=u[i].split("=");
      get[j[0]]=decodeURIComponent(j[1]);
    }
    return get;
  }
  else{
    return {};
  }
})();


$(function(){
  JA_comment_($_GET['list'], $_GET['form'], $_GET['css'], $_GET['msg']);
});


function JA_comment_(JA_commentList, JA_commentForm, JA_commentCss, JA_comment) {
    if (!JA_commentList) var JA_commentList = "#comment_list";
    if (!JA_commentForm) var JA_commentForm = "#commentform";
    var JA_commentFormSubmit = $(JA_commentForm).find("button[type=submit]");
    var JA_commentFormT = $(JA_commentForm + ' textarea');
    var JA_commentForm = $(JA_commentForm);
    JA_commentForm.submit(function () {
        var name = $('#commentform input[name=comname]').val();
        var email = $('#commentform input[name=commail]').val();
        var comment = $('#commentform textarea[name=comment]').val();
        if(name.length<1){
            $('#commentform input[name=comname]').focus();
            return false;
        }
        if(email.length<1){
            $('#commentform input[name=commail]').focus();
            return false;

        }
        if(comment.length<1){
            $('#commentform textarea[name=comment]').focus();
            return false;
        }
        var q = JA_commentForm.serialize();
        JA_commentFormT.attr("disabled", true);
        JA_commentFormSubmit.attr("disabled", true);
        JA_commentFormSubmit.html("请&nbsp;求&nbsp;中&nbsp;&nbsp;&nbsp;<img src='./content/templates/jane/images/load.gif' />");
        $.post(JA_commentForm.attr("action"), q, function (d) {
            var reg = /<div class=\"main\">[\r\n]*<p>(.*?)<\/p>/i;
            if (reg.test(d)) {
                $('#comment-post').prepend('<div id="comment-error" style="display:none;"><div class="alert alert-warning panel-body" style="margin:auto;width:90%"><a href="#" class="close" data-dismiss="alert">&times;</a>'+d.match(reg)[1]+'</div></div>');
                $('#comment-post #comment-error').fadeIn(2000);
               JA_commentFormSubmit.html('发&nbsp&nbsp表&nbsp评&nbsp论');
				$("[name=comment]").val("");
            } else {
                var p = $("input[name=pid]").val();
                cancelReply();
                $("[name=comment]").val("");
                u = JA_commentList.split(",");
                for (var i in u) {
                    $(u[i]).html('<div id="show'+i+'" style="display:none">'+$(d).find(u[i]).html()+'</div>');
                    $('#show'+i).fadeIn(1500);
                };
                var body = (window.opera) ? (document.compatMode == "CSS1Compat" ? $('html') : $('body')) : $('html,body');
                if (p != 0) {
                    var body = (window.opera) ? (document.compatMode == "CSS1Compat" ? $('html') : $('body')) : $('html,body');
                    body.animate({
                        scrollTop: $("#comment-" + p).offset().top - 50
                    }, "normal", function () {
                       JA_commentFormSubmit.html('发&nbsp&nbsp表&nbsp评&nbsp论');
                    })
                } else {
                    var body = (window.opera) ? (document.compatMode == "CSS1Compat" ? $('html') : $('body')) : $('html,body');
                    body.animate({
                        scrollTop: $(JA_commentList).offset().top - 50
                    }, "normal", function () {
                        JA_commentFormSubmit.html('发&nbsp&nbsp表&nbsp评&nbsp论');
                    })
                }
            };
            JA_commentFormT.attr("disabled", false);
            JA_commentFormSubmit.attr("disabled", false)
        });
        return false
    });
}
/************Ajax END******************/




/*lightbox*/

/*
	By Osvaldas Valutis, www.osvaldas.info
	Available for use under the MIT License
*/

;( function( $, window, document, undefined )
{
	'use strict';

	var cssTransitionSupport = function()
		{
			var s = document.body || document.documentElement, s = s.style;
			if( s.WebkitTransition == '' ) return '-webkit-';
			if( s.MozTransition == '' ) return '-moz-';
			if( s.OTransition == '' ) return '-o-';
			if( s.transition == '' ) return '';
			return false;
		},

		isCssTransitionSupport = cssTransitionSupport() === false ? false : true,

		cssTransitionTranslateX = function( element, positionX, speed )
		{
			var options = {}, prefix = cssTransitionSupport();
			options[ prefix + 'transform' ]	 = 'translateX(' + positionX + ')';
			options[ prefix + 'transition' ] = prefix + 'transform ' + speed + 's linear';
			element.css( options );
		},

		hasTouch	= ( 'ontouchstart' in window ),
		hasPointers = window.navigator.pointerEnabled || window.navigator.msPointerEnabled,
		wasTouched	= function( event )
		{
			if( hasTouch )
				return true;

			if( !hasPointers || typeof event === 'undefined' || typeof event.pointerType === 'undefined' )
				return false;

			if( typeof event.MSPOINTER_TYPE_MOUSE !== 'undefined' )
			{
				if( event.MSPOINTER_TYPE_MOUSE != event.pointerType )
					return true;
			}
			else
				if( event.pointerType != 'mouse' )
					return true;

			return false;
		};

	$.fn.imageLightbox = function( options )
	{
		var options	   = $.extend(
						 {
						 	selector:		'id="imagelightbox"',
						 	allowedTypes:	'png|jpg|jpeg|gif',
						 	animationSpeed:	250,
						 	preloadNext:	true,
						 	enableKeyboard:	true,
						 	quitOnEnd:		false,
						 	quitOnImgClick: false,
						 	quitOnDocClick: true,
						 	onStart:		false,
						 	onEnd:			false,
						 	onLoadStart:	false,
						 	onLoadEnd:		false
						 },
						 options ),

			targets		= $([]),
			target		= $(),
			image		= $(),
			imageWidth	= 0,
			imageHeight = 0,
			swipeDiff	= 0,
			inProgress	= false,

			isTargetValid = function( element )
			{
				return $( element ).prop( 'tagName' ).toLowerCase() == 'a' && ( new RegExp( '\.(' + options.allowedTypes + ')$', 'i' ) ).test( $( element ).attr( 'href' ) );
			},

			setImage = function()
			{
				if( !image.length ) return true;

				var screenWidth	 = $( window ).width() * 0.8,
					screenHeight = $( window ).height() * 0.9,
					tmpImage 	 = new Image();

				tmpImage.src	= image.attr( 'src' );
				tmpImage.onload = function()
				{
					imageWidth	 = tmpImage.width;
					imageHeight	 = tmpImage.height;

					if( imageWidth > screenWidth || imageHeight > screenHeight )
					{
						var ratio	 = imageWidth / imageHeight > screenWidth / screenHeight ? imageWidth / screenWidth : imageHeight / screenHeight;
						imageWidth	/= ratio;
						imageHeight	/= ratio;
					}

					image.css(
					{
						'width':  imageWidth + 'px',
						'height': imageHeight + 'px',
						'top':    ( $( window ).height() - imageHeight ) / 2 + 'px',
						'left':   ( $( window ).width() - imageWidth ) / 2 + 'px'
					});
				};
			},

			loadImage = function( direction )
			{
				if( inProgress ) return false;

				direction = typeof direction === 'undefined' ? false : direction == 'left' ? 1 : -1;

				if( image.length )
				{
					if( direction !== false && ( targets.length < 2 || ( options.quitOnEnd === true && ( ( direction === -1 && targets.index( target ) == 0 ) || ( direction === 1 && targets.index( target ) == targets.length - 1 ) ) ) ) )
					{
						quitLightbox();
						return false;
					}
					var params = { 'opacity': 0 };
					if( isCssTransitionSupport ) cssTransitionTranslateX( image, ( 100 * direction ) - swipeDiff + 'px', options.animationSpeed / 1000 );
					else params.left = parseInt( image.css( 'left' ) ) + 100 * direction + 'px';
					image.animate( params, options.animationSpeed, function(){ removeImage(); });
					swipeDiff = 0;
				}

				inProgress = true;
				if( options.onLoadStart !== false ) options.onLoadStart();

				setTimeout( function()
				{
					image = $( '<img ' + options.selector + ' />' )
					.attr( 'src', target.attr( 'href' ) )
					.load( function()
					{
						image.appendTo( 'body' );
						setImage();

						var params = { 'opacity': 1 };

						image.css( 'opacity', 0 );
						if( isCssTransitionSupport )
						{
							cssTransitionTranslateX( image, -100 * direction + 'px', 0 );
							setTimeout( function(){ cssTransitionTranslateX( image, 0 + 'px', options.animationSpeed / 1000 ) }, 50 );
						}
						else
						{
							var imagePosLeft = parseInt( image.css( 'left' ) );
							params.left = imagePosLeft + 'px';
							image.css( 'left', imagePosLeft - 100 * direction + 'px' );
						}

						image.animate( params, options.animationSpeed, function()
						{
							inProgress = false;
							if( options.onLoadEnd !== false ) options.onLoadEnd();
						});
						if( options.preloadNext )
						{
							var nextTarget = targets.eq( targets.index( target ) + 1 );
							if( !nextTarget.length ) nextTarget = targets.eq( 0 );
							$( '<img />' ).attr( 'src', nextTarget.attr( 'href' ) ).load();
						}
					})
					.error( function()
					{
						if( options.onLoadEnd !== false ) options.onLoadEnd();
					})

					var swipeStart	 = 0,
						swipeEnd	 = 0,
						imagePosLeft = 0;

					image.on( hasPointers ? 'pointerup MSPointerUp' : 'click', function( e )
					{
						e.preventDefault();
						if( options.quitOnImgClick )
						{
							quitLightbox();
							return false;
						}
						if( wasTouched( e.originalEvent ) ) return true;
					    var posX = ( e.pageX || e.originalEvent.pageX ) - e.target.offsetLeft;
						target = targets.eq( targets.index( target ) - ( imageWidth / 2 > posX ? 1 : -1 ) );
						if( !target.length ) target = targets.eq( imageWidth / 2 > posX ? targets.length : 0 );
						loadImage( imageWidth / 2 > posX ? 'left' : 'right' );
					})
					.on( 'touchstart pointerdown MSPointerDown', function( e )
					{
						if( !wasTouched( e.originalEvent ) || options.quitOnImgClick ) return true;
						if( isCssTransitionSupport ) imagePosLeft = parseInt( image.css( 'left' ) );
						swipeStart = e.originalEvent.pageX || e.originalEvent.touches[ 0 ].pageX;
					})
					.on( 'touchmove pointermove MSPointerMove', function( e )
					{
						if( !wasTouched( e.originalEvent ) || options.quitOnImgClick ) return true;
						e.preventDefault();
						swipeEnd = e.originalEvent.pageX || e.originalEvent.touches[ 0 ].pageX;
						swipeDiff = swipeStart - swipeEnd;
						if( isCssTransitionSupport ) cssTransitionTranslateX( image, -swipeDiff + 'px', 0 );
						else image.css( 'left', imagePosLeft - swipeDiff + 'px' );
					})
					.on( 'touchend touchcancel pointerup pointercancel MSPointerUp MSPointerCancel', function( e )
					{
						if( !wasTouched( e.originalEvent ) || options.quitOnImgClick ) return true;
						if( Math.abs( swipeDiff ) > 50 )
						{
							target = targets.eq( targets.index( target ) - ( swipeDiff < 0 ? 1 : -1 ) );
							if( !target.length ) target = targets.eq( swipeDiff < 0 ? targets.length : 0 );
							loadImage( swipeDiff > 0 ? 'right' : 'left' );	
						}
						else
						{
							if( isCssTransitionSupport ) cssTransitionTranslateX( image, 0 + 'px', options.animationSpeed / 1000 );
							else image.animate({ 'left': imagePosLeft + 'px' }, options.animationSpeed / 2 );
						}
					});

				}, options.animationSpeed + 100 );
			},

			removeImage = function()
			{
				if( !image.length ) return false;
				image.remove();
				image = $();
			},

			quitLightbox = function()
			{
				if( !image.length ) return false;
				image.animate({ 'opacity': 0 }, options.animationSpeed, function()
				{
					removeImage();
					inProgress = false;
					if( options.onEnd !== false ) options.onEnd();
				});
			};

		$( window ).on( 'resize', setImage );

		if( options.quitOnDocClick )
		{
			$( document ).on( hasTouch ? 'touchend' : 'click', function( e )
			{
				if( image.length && !$( e.target ).is( image ) ) quitLightbox();
			})
		}

		if( options.enableKeyboard )
		{
			$( document ).on( 'keyup', function( e )
			{
				if( !image.length ) return true;
				e.preventDefault();
				if( e.keyCode == 27 ) quitLightbox();
				if( e.keyCode == 37 || e.keyCode == 39 )
				{
					target = targets.eq( targets.index( target ) - ( e.keyCode == 37 ? 1 : -1 ) );
					if( !target.length ) target = targets.eq( e.keyCode == 37 ? targets.length : 0 );
					loadImage( e.keyCode == 37 ? 'left' : 'right' );
				}
			});
		}

		$( document ).on( 'click', this.selector, function( e )
		{
			if( !isTargetValid( this ) ) return true;
			e.preventDefault();
			if( inProgress ) return false;
			inProgress = false;
			if( options.onStart !== false ) options.onStart();
			target = $( this );
			loadImage();
		});

		this.each( function()
		{
			if( !isTargetValid( this ) ) return true;
			targets = targets.add( $( this ) );
		});

		this.switchImageLightbox = function( index )
		{
			var tmpTarget = targets.eq( index );
			if( tmpTarget.length )
			{
				var currentIndex = targets.index( target );
				target = tmpTarget;
				loadImage( index < currentIndex ? 'left' : 'right' );
			}
			return this;
		};

		this.quitImageLightbox = function()
		{
			quitLightbox();
			return this;
		};

		return this;
	};
})( jQuery, window, document );

	$( function()
	{
			// ACTIVITY INDICATOR
		var activityIndicatorOn = function()
			{
				$( '<div id="imagelightbox-loading"><div></div></div>' ).appendTo( 'body' );
			},
			activityIndicatorOff = function()
			{
				$( '#imagelightbox-loading' ).remove();
			},
				// CLOSE BUTTON

			closeButtonOn = function( instance )
			{
				$( '<button type="button" id="imagelightbox-close" title="Close"></button>' ).appendTo( 'body' ).on( 'click touchend', function(){ $( this ).remove(); instance.quitImageLightbox();	$( '#imagelightbox-loading' ).remove(); return false; });
			},
			closeButtonOff = function()
			{
				$( '#imagelightbox-close' ).remove();
			}

		
		//	WITH ARROWS & ACTIVITY INDICATIO
		var selectorG = '#echo-log p a,#echo-log div a';
		var instanceG = $( selectorG ).imageLightbox(
		{
			onStart:		function() {  closeButtonOn( instanceG ); },
			onEnd:			function() { closeButtonOff();},
			onLoadStart: 	function(){ activityIndicatorOn(); },
			onLoadEnd:	 	function(){ $( '.imagelightbox-arrow' ).css( 'display', 'block' ); activityIndicatorOff(); }
		});
	});
/*-------------------lightbox end-------------*/

