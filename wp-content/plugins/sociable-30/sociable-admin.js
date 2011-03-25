jQuery(document).ready(function(){
    sociable_add_list_events();

    jQuery('#sociable_iconset_name').change(function() {
        sociable_get_iconset();
    });

    jQuery('#sociable_iconset_name').keyup(function() {
        sociable_get_iconset();
    });
});

function sociable_get_iconset() {
    var active_sites = new Array();

    jQuery("input[name='sociable[active_sites][]']").each(function() {
        if (this.checked) {
            active_sites.push(this.value);
        }
    });

    var data = {
        action : 'sociable_active_sites',
        iconset_name : jQuery('#sociable_iconset_name').val(),
        active_sites : active_sites
    };

    jQuery('#sociable_site_list_div').load(ajaxurl, data, function(responseText) {
        sociable_add_list_events();
    });
}

function sociable_add_list_events() {
    jQuery("#sociable_site_list").sortable({});

    jQuery("#sociable_site_list input:checkbox").change(function() {
        if (jQuery(this).attr('checked')) {
            jQuery(this).parent().removeClass("inactive");
            jQuery(this).parent().addClass("active");
        } else {
            jQuery(this).parent().removeClass('active');
            jQuery(this).parent().addClass('inactive');
        }
    } );
}

