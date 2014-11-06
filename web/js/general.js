jQuery(document).ready(function() {

	String.prototype.trim = function()
	{
    return this.replace(/(?:^\s+|\s+$)/g, '');
	}

	jQuery.expr[':'].insensitiveContains = function(a,i,m) {
		return jQuery(a).text().toUpperCase().indexOf(m[3].toUpperCase()) >= 0;
	};

	jQuery.expr[':'].checkboxList = function (a,i,m) {
		if (jQuery(a).filter('ul.checkbox_list').size() > 0)
		{
			if (m[3] != undefined)
			{
				var vals = m[3].split(',');

				if (jQuery(a).find('input[name^=' + vals[0].trim() + '[' + vals[1].trim() + '][]]').size() == 0)
				{
					return false;
				}
			}

			return true;
		}

		return false;
	}

	jQuery.fn.addZoneFilter = function(options) {
		return jQuery(this);
	};

	jQuery.fn.addSearchBox = function(options) {
		var defaults = {
			enableEnterKey: true,
			insensitive: true,
			prefix: null
		};

		var opts = jQuery.extend(defaults, options);

		jQuery(this).each(function () {

			if (opts.prefix == null)
			{
				opts.prefix = this.id;
			}

			var search_input_name = opts.prefix + '_search_input';
			var clear_input_name = opts.prefix + '_clear_input';

			jQuery(this).before(
				'<div class="actions">' +
					'<label for="' + search_input_name + '">Rechercher: </label>' +
					'<input id="' + search_input_name + '" type="text" />' +
					'<input id="' + clear_input_name + '" type="button" value="Effacer" />' + 
				'</div>'
			);

			var search_div = jQuery(this).parents('td').find('div.actions');
			var search_input = search_div.find('input#' + search_input_name);
			var values_options = jQuery(this).find('option');
			var clear_input = search_div.find('input#' + clear_input_name);

			clear_input.click(function() {
				search_input.val('');
				search_input.keyup();
			});

			if (opts.enableEnterKey)
			{
				search_input.keydown(function (e) {
					if (e.keyCode == 13)
					{
						if (!search_input.hasClass('error'))
						{
							search_input.val('');
						}

						return false;
					}
				});
			}

			search_input.keyup(function () {

				if (jQuery(this).val() == '')
				{
					clear_input.attr('disabled', 'disabled');
					search_input.removeClass('error');
				} else
				{
					clear_input.attr('disabled', '');
				}

				if (jQuery(this).val() != '')
				{
					var result = null;
					if (opts.insensitive)
					{
						result = values_options.filter(':insensitiveContains("' + jQuery(this).val() + '"):first');
					} else
					{
						result = values_options.filter(':contains("' + jQuery(this).val() + '"):first').attr('selected', 'selected');
					}

					if (result.size() > 0)
					{
						result.attr('selected', 'selected');
						search_input.removeClass('error');
					} else
					{
						search_input.addClass('error');
					}
				}
			});
		});

		return jQuery(this);
	};

	jQuery.fn.addFilter = function(options) {
		var defaults = {
			min: 5,
			enableEnterKey: true,
			insensitive: true,
			prefix: null
		};

		var opts = jQuery.extend(defaults, options);

		jQuery(this).each(function () {

		if (jQuery(this).find('li').size() >= opts.min)
		{
			if (opts.prefix == null)
			{
				// On récupère le nom en l'extrayant du nom global
				var name = jQuery(this).find('input').attr('name');
				var rexpb = new RegExp('^[a-z]*[[]', 'i');
				var rexpe = new RegExp('][[]]$', 'i');
				opts.prefix = name.replace(rexpb, '').replace(rexpe, '');
			}

			var search_input_name = opts.prefix + '_search_input';
			var clear_input_name = opts.prefix + '_clear_input';
			var all_input_name = opts.prefix + '_all_input';

			jQuery(this).before(
				'<div class="actions">' + 
					'<label for="' + search_input_name + '">Filtre: </label>' +
					'<input id="' + search_input_name + '" type="text" />' +
					'<input id="' + clear_input_name + '" type="button" value="Effacer" />' + 
					'<input id="' + all_input_name + '" type="button" value="Afficher tout" />' +
				'</div>'
			);

			// Add the filter input and the clear input
			jQuery(this).prepend(
				'<li class="empty">Aucune sélection.</li>'
			);

			// Make vars
			var empty_li = jQuery(this).find('li.empty');
			var search_div = jQuery(this).parents('td').find('div.actions');
			var search_input = search_div.find('input#' + search_input_name);
			var values_li = jQuery(this).find('li:not(.empty)');
			var values_input = values_li.find('input:checkbox, input:radio');
			var clear_input = search_div.find('input#' + clear_input_name);
			var all_input = search_div.find('input#' + all_input_name);

			clear_input.click(function() {
				search_input.val('');
				search_input.keyup();
			});

			all_input.click(function() {
				search_input.val('*');
				search_input.keyup();
			});

			values_input.filter(':not(:checked)').parent('li').hide();

			values_input.change(function () {
				search_input.keyup();
			});

			if (opts.enableEnterKey)
			{
				search_input.keypress(function (e) {
					if (e.keyCode == 13)
					{
						if (search_input.val() == '*' && values_input.filter(':not(:checked)').size() == 0)
						{
							values_input.click();
							clear_input.click();
							return false;
						}

						if (!search_input.hasClass('error'))
						{
							if (values_input.filter(':radio:visible:not(:checked)').size() <= 1)
							{
								values_input.filter(':not(:checked):visible').attr('checked', 'checked');
								clear_input.click();
							}
						}

						return false;
					}
				});
			}

			search_input.keyup(function () {

				if (jQuery(this).val() == '*')
				{
					values_li.show();
					clear_input.attr('disabled', '');
					all_input.attr('disabled', 'disabled');
				} else
				{
					if (jQuery(this).val() == '')
					{
						clear_input.attr('disabled', 'disabled');
						search_input.removeClass('error');
					} else
					{
						clear_input.attr('disabled', '');
					}

					all_input.attr('disabled', '');

					values_input.filter(':not(:checked)').parent('li').hide();
					values_input.filter(':checked').parent('li').show('fast');

					if (jQuery(this).val() != '')
					{
						var result = null;

						if (opts.insensitive)
						{
							result = values_li.find('label:insensitiveContains("' + jQuery(this).val() + '")').parent('li');
						} else
						{
							result = values_li.find('label:contains("' + jQuery(this).val() + '")').parent('li');
						}

						if (result.size() > 0)
						{
							result.show();
							search_input.removeClass('error');
						} else
						{
							search_input.addClass('error');
						}
					}
				}

				if (values_input.filter(':checked').size() == 0)
				{
					empty_li.show('fast');
				} else
				{
					empty_li.hide();
				}
			});

			search_input.keyup();
		}

		});

		return jQuery(this);
	};
});
