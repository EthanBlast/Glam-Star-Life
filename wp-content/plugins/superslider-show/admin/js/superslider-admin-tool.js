jQuery(document).ready(function(){

;(function($) {

   $(".ss_tool").tooltip({ 
        track: false,        
        delay: 0,        
        fade: 250,
        left: 10,
        top: -10,
        opacity: 0.7,
        showURL: false,
        extraClass: "superTool",
        //showBody: " - ",
        showURL: false,
        bodyHandler: function() { 
       
            return  $($(this).attr("href")).html();             
        }
    });

})(jQuery);
    
});
    


	

