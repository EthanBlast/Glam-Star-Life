jQuery(document).ready(function(){

;(function($) {
    
      $("#show-box .ss-toggler-open").click(function(){
        $("#show-box .ss-show-advanced").slideToggle(1200);
        $(this).hide();
        return false;
      
    });
    
    $("#show-box .ss-toggler-close").click(function(){
        $("#show-box .ss-show-advanced").slideToggle("slow");
        $("#show-box .ss-toggler-open").show();
        return false;
      
    });

})(jQuery);
    
});
    


	

