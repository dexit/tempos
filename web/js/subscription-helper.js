jQuery(document).ready(function() {

	var unlimitedCreditInput = jQuery('input[name=subscription[unlimitedCredit]]');
	var creditInput = jQuery('input[name=subscription[credit]]');

	if ((creditInput.size() > 0) && (unlimitedCreditInput.size() > 0))
	{
		unlimitedCreditInput.change(function() {
			if (unlimitedCreditInput.attr('checked') == true)
			{
				creditInput.after('<input value="" />');
				creditInput.find('+ input').hide();
				creditInput.find('+ input').val(creditInput.val());
				creditInput.val('');
				creditInput.parents('tr').hide();
			} else
			{
				creditInput.val(creditInput.find('+ input').val());

				if (creditInput.val() == '')
				{
					creditInput.val('10');
				}

				creditInput.find('+ input').remove();
				creditInput.parents('tr').show();
			}
		});

		if (unlimitedCreditInput.attr('checked') == true)
		{
				creditInput.after('<input value="" />');
				creditInput.find('+ input').hide();
				creditInput.find('+ input').val(creditInput.val());
				creditInput.val('');
				creditInput.parents('tr').hide();
		} else
		{
				creditInput.val(creditInput.find('+ input').val());

				if (creditInput.val() == '')
				{
					creditInput.val('10');
				}

				creditInput.find('+ input').remove();
				creditInput.parents('tr').show();
		}
	}
});
