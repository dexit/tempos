jQuery(document).ready(function() {

	jQuery('div.filter').each(function () {

		var filter = jQuery(this);
		filter.prepend('<p><input type="button" class="filter" value="" /></p>');
		var showLink = filter.find('input.filter');
		showLink.attr('value', filter.attr('title'));
		filter.attr('title', '');
		var table = filter.find('table');

		showLink.click(function() {
			if (table.is(':visible'))
			{
				table.hide();
			} else
			{
				if (filter.hasClass('autoopen'))
				{
				} else
				{
					table.css('position', 'absolute');
					table.css('width', jQuery('div#body').width());
				}

				table.show();
			}
			return false;
		});

		if ((table.find('ul.error_list').size() == 0) && (!filter.hasClass('autoopen')))
		{
			table.hide();
		}
	});
});
