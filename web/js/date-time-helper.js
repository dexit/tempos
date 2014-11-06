jQuery(document).ready(function() {

	/*jQuery('form select[name$=[month]]').each(function () {

		var daddy = jQuery(this).parent('*');
		var error_list = daddy.find('ul.error_list');
		var month_select = jQuery(this).find('~ select[name$=[month]]');
		var day_select = jQuery(this).find('~ select[name$=[day]]');
		var year_select = jQuery(this).find('~ select[name$=[year]]');
		var hour_select = jQuery(this).find('~ select[name$=[hour]]');
		var minute_select = jQuery(this).find('~ select[name$=[minute]]');

		// Clears everything
		daddy.text('');

		// Append selects back, hidden
		if (error_list)
		{
			daddy.append(error_list);
		}
		daddy.append(day_select);
		daddy.append(month_select);
		daddy.append(year_select);

		day_select.hide();
		month_select.hide();
		year_select.hide();

		var start_year = year_select.find('option[value!=]:first').val();
		var stop_year = year_select.find('option:last').val();

		daddy.append('<input type="text" class="datepicker" />');
		
		daddy.find('.datepicker').datepicker({
			changeMonth: true,
			changeYear: true,
			yearRange: start_year + ':' + stop_year,
			onClose: function(dateText, inst)
			{
				var adate = new Date(dateText);

				if (adate)
				{
					day_select.val(adate.getDate());
					month_select.val(adate.getMonth() + 1);
					year_select.val(adate.getFullYear());
				}
			}
		});

		var month = month_select.find('option:selected').text();
		var day = day_select.find('option:selected').text();
		var year = year_select.find('option:selected').text();

		daddy.find('.datepicker').datepicker('setDate', new Date(day + '/' + month + '/' + year));

		if (hour_select.size() > 0)
		{
			daddy.append('<span>&nbsp;</span>');
			daddy.append(hour_select);
			daddy.append('<span>:</span>');
			daddy.append(minute_select);
		}
	});*/
});
