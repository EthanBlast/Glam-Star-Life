(function($){
	function DarkNavigation(el){
		var el = $(el);
		var deck = el.find('.slidedeck').slidedeck();
		var prev = el.find('.sd-node-previous');
		var next = el.find('.sd-node-next');
		var primaryNavs = el.find('.sd-node-nav-primary a.sd-node-nav-link');
		
		var updateActive = function(activeSlide){
			if(deck.options.cycle == false){
				if(activeSlide == 1){
					prev.addClass('disabled');
					next.removeClass('disabled');
				}
				if(activeSlide == deck.slides.length){
					next.addClass('disabled');
					prev.removeClass('disabled');
				}
			}
			primaryNavs.removeClass('active');
			$(primaryNavs[activeSlide - 1]).addClass('active');
		};
		
		var oldNext = deck.next;
		deck.next = function(params){
			var nextSlide = Math.min(deck.slides.length,(deck.current + 1));
			if(deck.options.cycle === true){
				if(deck.current + 1 > deck.slides.length){
					nextSlide = 1;
				}
			}
			
			oldNext(params);
			updateActive(nextSlide);
		};
		var oldPrev = deck.prev;
		deck.prev = function(params){
			var prevSlide = Math.max(1,(deck.current - 1));
			if(deck.options.cycle === true){
				if(deck.current - 1 < 1){
					prevSlide = deck.slides.length;
				}
			}
			
			oldPrev(params);
			updateActive(prevSlide);
		};
		var oldGoTo = deck.goTo;
		deck.goTo = function(ind, params){
			oldGoTo(ind, params);
			updateActive(Math.min(deck.slides.length,Math.max(1,ind)));
		};
		
		el.find('.sd-node-nav-link').bind('click', function(event){
			event.preventDefault();

			var action = this.href.split('#')[1];
			
			deck.pauseAutoPlay = true;

			switch(action){
				case "previous":
					deck.prev();
				break;
				case "next":
					deck.next();
				break;
				default:
					deck.goTo(action);
				break;
			}
		});
		
		$(primaryNavs[0]).addClass('active');
		
        deck.loaded(function(){
            for(var z=0, slides=el.find('dd.slide .sd-node-container'); z<slides.length; z++){
                var thisSlide = $(slides[z]);
                var slideWidth = thisSlide.innerWidth();
                
                if(thisSlide.find('.sd-node-image').length){
                    thisSlide.find('.sd-node-content').css({
                        width: Math.floor((slideWidth - 320)) + "px"
                    });
                    
                    if(navigator.userAgent.toLowerCase().match(/msie 7/) ? true : false){
                        slideImage.css({
                            position: 'relative',
                            top: ((262 - slideImage[0].height) / 2) + "px"
                        });
                    }
                } else {
                    thisSlide.find('.sd-node-content').css({
                        width: Math.floor(slideWidth * 0.9) + "px"
                    });
                }
            }
        });
		
        $(window).load(function(){
            el.find('dd.slide .sd-node-container .sd-node-image-child img').each(function(){
                $(this).attr('width', this.width).css({
                    top: (262 - this.height) + "px"
                });
            });
        });
		
		return true;
	};
	
	$(document).ready(function(){
		for(var i=0, decks=$('.slidedeck_frame.skin-dark'); i<decks.length; i++){
			var thisDeck = decks[i];
		    
			if(typeof(thisDeck.SlideDeck_skinDarkNavigation) == 'undefined'){
				thisDeck.SlideDeck_skinDarkNavigation = DarkNavigation(thisDeck);
			}
		}
	});
})(jQuery);