jQuery(document).ready(function(){
    jQuery(".pfs-post-link").click(function(){
        jQuery(this).parent('#pfs-alert').hide();
        var id = '#pfs-post-box-' + jQuery(this).attr('id').replace('-link','');
        var distrl = (jQuery(window).width()-600)/2;
        var disttb = (jQuery(window).height()-400)/2
        jQuery(id).css({zIndex:'200',top:"50px",left:distrl+"px",width:'600px',position:'absolute'}).show();
    });
    jQuery("#closex").click(function(){
        jQuery(this).parent('.pfs-post-box').hide();
        jQuery(this).parent('#pfs-alert').hide();
    });
    jQuery("form.pfs").submit(function() {
        jQuery(this).ajaxSubmit({
            type: "POST",
            url: jQuery(this).attr('action'),
            dataType:'json',
            beforeSend: function(){
                jQuery('.pfs-post-box #post').val('posting...');
            },
            complete: function(request,textStatus,error) {
                data = jQuery.parseJSON(request.responseText);
                if (data && data.error) {
                    jQuery('#pfs-alert').addClass('error').html('<p>'+data.error+'</p>').show();
                    jQuery('.pfs-post-box #post').val('Post');
                } else {
                    jQuery('.pfs-post-box').children().not('.closex').remove();
                    jQuery('.pfs-post-box').append('<h3>'+data.success+'</h3>').show();
                    jQuery(".pfs-post-box").fadeTo(3000,1).fadeOut(1000);
                    jQuery("#closex").click();
                    location.reload();
                }
            }
        });
        return false;
    });
});
