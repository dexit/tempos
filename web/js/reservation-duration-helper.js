jQuery(document).ready(function() {
	var reservation_duration = jQuery('input#reservation_duration');

	reservation_duration.parent('td').each(function() {
		// This is ugly, but we have to put this in french until we find a better way for JS translation.
		jQuery(this).append('<div class="actions"></div>');
		var actions = jQuery(this).find('div.actions');

		jQuery(this).append('<span id="strtime"></span>');
		actions.append('<input id="plus1hour" type="button" value="+1 heure" />');
		actions.append('<input id="plus1day" type="button" value="+1 jour" />');
		actions.append('<input id="plus7days" type="button" value="+7 jours" />');
		actions.append('<input id="minus7days" type="button" value="-7 jours" />');
		actions.append('<input id="minus1day" type="button" value="-1 jour" />');
		actions.append('<input id="minus1hour" type="button" value="-1 heure" />');

		function updateStrTime()
		{
			var duration = parseInt(reservation_duration.val());
			var text = '';

			if (duration < 60)
			{
				text += duration + ' minute(s)';
			} else
			{
				if (duration > 60 * 24)
				{
					text += Math.floor(duration / (60 * 24)) + ' jour(s)';
					duration %= 60 * 24;

					text += ', ';
				}

				text += Math.floor(duration / 60) + ' heure(s)';

				if (duration % 60 > 0)
				{
					text += ' et ' + (duration % 60) + ' minute(s)';
				}
			}

			jQuery('#strtime').text(text);
		}

		function increaseDuration(val)
		{
			var new_val = parseInt(reservation_duration.val()) + val;

			if (new_val < 0)
			{
				new_val = 0;
			}

			reservation_duration.val(new_val);

			updateStrTime();
		}

		updateStrTime();

		reservation_duration.change(function() {
			updateStrTime();
		});

		jQuery('#plus1hour').click(function() { increaseDuration(60); });
		jQuery('#plus1day').click(function() { increaseDuration(60 * 24); });
		jQuery('#plus7days').click(function() { increaseDuration(60 * 24 * 7); });
		jQuery('#minus7days').click(function() { increaseDuration(-60 * 24 * 7); });
		jQuery('#minus1day').click(function() { increaseDuration(-60 * 24); });
		jQuery('#minus1hour').click(function() { increaseDuration(-60); });
	});
});
