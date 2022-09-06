/* http://blog.bassta.bg/2013/05/simple-fade-infade-out-slideshow-with-tweenlite/ */

$(function(){
	
	var $slides = $(".slide");			//slides
	var currentSlide = 0;				//keep track on the current slide
	var stayTime = 10;					//time the slide stays
	var slideTime = 1.3;				//fade in / fade out time
				
	TweenLite.set($slides.filter(":gt(0)"), {autoAlpha:0});	//we hide all images after the first one
	TweenLite.delayedCall(stayTime, nextSlide);				//start the slideshow
				
	function nextSlide(){					
			TweenLite.to( $slides.eq(currentSlide), slideTime, {autoAlpha:0} );		//fade out the old slide
			currentSlide = ++currentSlide % $slides.length;							//find out the next slide			
			TweenLite.to( $slides.eq(currentSlide), slideTime, {autoAlpha:1} );		//fade in the next slide
			TweenLite.delayedCall(stayTime, nextSlide);								//wait a couple of seconds before next slide
	}
	
	
	var $slides2 = $(".slide2");			//slides
	var currentSlide2 = 0;				//keep track on the current slide
	var stayTime2 = 9;					//time the slide stays
	var slideTime2 = 1.4;				//fade in / fade out time
				
	TweenLite.set($slides2.filter(":gt(0)"), {autoAlpha:0});	//we hide all images after the first one
	TweenLite.delayedCall(stayTime2, nextSlide2);				//start the slideshow
				
	function nextSlide2(){					
			TweenLite.to( $slides2.eq(currentSlide2), slideTime2, {autoAlpha:0} );		//fade out the old slide
			currentSlide2 = ++currentSlide2 % $slides2.length;							//find out the next slide			
			TweenLite.to( $slides2.eq(currentSlide2), slideTime2, {autoAlpha:1} );		//fade in the next slide
			TweenLite.delayedCall(stayTime2, nextSlide2);								//wait a couple of seconds before next slide
	}
	
	
	$(document).ready(function(){		
		
		var maxHeight = -1;
		$('.slide2').each(function() {
			if ($(this).height() > maxHeight)
				maxHeight = $(this).height();
		});
		$('.testimonial-box2').height(maxHeight);
		
	});
	
	
	

});

	
