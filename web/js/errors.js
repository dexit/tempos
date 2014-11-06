jQuery(document).ready(function() {
	
	jQuery('form ul.error_list').each(function () {
		jQuery(this).each(function () {

			if (jQuery(this).parent('td').find('table').size() > 0)
			{
				return;
			}

			var title = jQuery(this).parent('td').parent('tr').find('> th label').text();

			if (title == '')
			{
				title = 'Erreur';
			}

			jQuery(this).attr('title', title);
			jQuery(this).clone().dialog({modal: true, buttons: { Ok: function() { jQuery(this).dialog('close'); } } });
		});
	});
});
