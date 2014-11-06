jQuery(document).ready(function() {
	
	jQuery('div.legend').each(function () {
		var legend = jQuery(this);
	
		legend.find('h3').css('cursor', 'pointer');
		legend.find('h3').toggle(function () {
			legend.find('dl').hide();
			legend.css('display', 'inline');
		}, function () {
			legend.find('dl').show();
		});
	
		legend.find('h3').click();
	});
});
