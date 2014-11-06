jQuery(document).ready(function() {
	
	jQuery('ul:checkboxList("reservation, reservation_has_user_list")').addFilter();
	
	jQuery.fn.inval = function(options) {
		var defaults = {
			attribute: 'id',
			expr: ''
		};

		var opts = jQuery.extend(defaults, options);

		var result = $(this).attr(opts.attribute);
		result = result.replace(opts.expr, '$1');

		return parseInt(result);
	}

	jQuery.fn.selectionTable = function(options) {
		var defaults = {
			itemClass: 'tselectable',
			selectionClass: 'tselection',
			selectedClass: 'tselected',
			dayExpr: /book-(.*)-.*/,
			indexExpr: /book-.*-(.*)/
		};

		var opts = jQuery.extend(defaults, options);

		jQuery(this).each(function () {
			var table = jQuery(this);
			var items = table.find('td.' + opts.itemClass);
			var startItem = null;
			var downItem = null;
			var downDay = null;
			var downIndex = null;
			var duration = 0;
			
			var selectIndexItems = function(min, max, day)
			{
				items.each(function() {
					var item = jQuery(this);

					var itemDay = item.inval({ expr: opts.dayExpr });

					var ok = false;

					if (itemDay == day)
					{
						var itemIndex = item.inval({ expr: opts.indexExpr });

						if ((itemIndex >= min) && (itemIndex <= max))
						{
							ok = true;
						}
					}

					if (ok)
					{
						item.addClass(opts.selectionClass);
					} else
					{
						item.removeClass(opts.selectionClass);
					}
				});

				duration = (max - min + 1);
			};

			var mouseDownHandler = function (e)
			{
				var buttonLeft = 0;

				if (jQuery.browser.msie)
				{
					buttonLeft = 1;
				}

				if (e.button == buttonLeft)
				{
					downItem = jQuery(this);
					startItem = downItem;

					downItem.addClass(opts.selectionClass);
					startItem.addClass(opts.selectedClass);

					downDay = downItem.inval({ expr: opts.dayExpr });
					downIndex = downItem.inval({ expr: opts.indexExpr });
					duration = 1;

					jQuery(document).mouseup(mouseUpHandler);
					jQuery(document).keypress(keyPressHandler);

					e.cancelBubble = true;

					if (e.stopPropagation)
					{
						e.stopPropagation();
					}

					return false;
				}
			};

			var keyPressHandler = function(e)
			{
				if (e.keyCode = 27)
				{
					jQuery(document).unbind('mouseup', mouseUpHandler);
					jQuery(document).unbind('keypress', keyPressHandler);

					items.removeClass(opts.selectionClass);
					items.removeClass(opts.selectedClass);

					downItem = null;
					downDay = null;
					downIndex = null;
					duration = 0;
				}
			}

			var mouseEnterHandler = function (e)
			{
				if (downItem)
				{
					var target = jQuery(this);
					var targetIndex = target.inval({ expr: opts.indexExpr });

					startItem.removeClass(opts.selectedClass);

					if (targetIndex < downIndex)
					{
						startItem = target;
						selectIndexItems(targetIndex, downIndex, downDay);
					} else
					{
						startItem = downItem;
						selectIndexItems(downIndex, targetIndex, downDay);
					}

					startItem.addClass(opts.selectedClass);
				}
			};

			var mouseUpHandler = function () {
				jQuery(document).unbind('mouseup', mouseUpHandler);
				jQuery(document).unbind('keypress', keyPressHandler);
				
				items.removeClass(opts.selectionClass);
				downItem.removeClass(opts.selectedClass);

				if (duration < 1)
				{
					duration = 1;
				}

				window.location = startItem.find('a').attr('href') + '/duration/' + Math.floor(duration * 30);

				downItem = null;
				downDay = null;
				downIndex = null;
				duration = 0;

				return false;
			};

			items.each(function () {

				// Hides the links inside
				jQuery(this).find('a').hide();
				jQuery(this).append('<span class="autohide">' + jQuery(this).find('a').text() + '</span>');

				if ('undefined' !== typeof this.onselectstart)
				{
					this.onselectstart = function() { return false; };
				}

				jQuery(this).mousedown(mouseDownHandler);
				jQuery(this).mouseenter(mouseEnterHandler);
				jQuery(this).css('cursor', 'pointer');
			});
		});
	};

	jQuery('table.planning').selectionTable();
});
