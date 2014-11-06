jQuery(document).ready(function() {

	// Color selection stuff
	if (jQuery('#activity_color').size())
	{
		jQuery('#activity_color').parent('td').append('<div id="colorpicker"></<div>');
		jQuery('#colorpicker').farbtastic('#activity_color');
		jQuery('#colorpicker').hide();

		jQuery('#activity_color').focus(function() {
			jQuery('#colorpicker').show('slow');
		});

		jQuery('#activity_color').blur(function() {
			jQuery('#colorpicker').hide('slow');
		});
	}

	/* Compter le nombre d'occurence
	var inputs = Array();
    $(".required").each(function(index, item)
    {
        var n = $(item).attr("name");
        if(inputs.indexOf(n) == -1) { inputs.push(n); }
    }); */
	
	//alert('Message: ' + jQuery('activity').size());
	// The activities selection list
	jQuery('ul:checkboxList("room, room_has_activity_list")').addFilter();
	jQuery('ul:checkboxList("activity, room_has_activity_list")').addFilter();
	jQuery('ul:checkboxList("activity, activity_has_feature_list")').addFilter();
	jQuery('ul:checkboxList("feature, activity_has_feature_list")').addFilter();
	jQuery('ul:checkboxList("usergroup, usergroup_has_activity_list")').addFilter();
	jQuery('ul:checkboxList("userSearch, activities")').addFilter();
	jQuery('ul:checkboxList("reporting, activities")').addFilter();
	jQuery('ul:checkboxList("occupancy, activities")').addFilter();
	jQuery('ul:checkboxList("subscription, Activity_id")').addFilter();
	jQuery('ul:checkboxList("reservationDelete, activity")').addFilter();
	jQuery('ul.radio_list:has(input[name=subscription[Activity_id]])').addFilter({ prefix: 'subscription_activity_id' });
});
