jQuery(document).ready(function() {

	jQuery('ul.blocklist li').each(function () {

		jQuery(this).css('cursor', 'pointer');
		var link = jQuery(this).find('a');

		link.remove();

		jQuery(this).bind('click', function (event) {
			window.location = link.attr('href');
		});
	});

	jQuery('table.gantt').each(function () {
		var table = jQuery(this);

		table.find('tbody td').each(function () {
			var td = jQuery(this);

			var a = td.find('a');

			if (a.size() > 0)
			{
				a.hide();
				td.attr('title', a.text());
				td.css('cursor', 'pointer');
				td.click(function () {
					window.location = a.attr('href');
				});
			} else
			{
				td.attr('title', td.text());
				if (td.attr('class').substring(td.attr('class').lastIndexOf(" ")) != " print_only") {
					td.text('');
				} 
			}
		});
	});
});
