jQuery(document).ready(function() {

	// Physical Access Controller magic

	var nb_pac = 1;
	var pacs = new Array();

	// Compte le nombre de contrôleurs
	while (jQuery('select[name=configuration[physical_access_controller'+(nb_pac+1)+']]').length > 0)
	{
		nb_pac++;
	}

	// Pour chaque contrôleur...
	for (var i = 1; i <= nb_pac; i++)
	{
		pacs[i] = jQuery('select[name=configuration[physical_access_controller' + i + ']]');

		var tbody = pacs[i].parent('td').parent('tr').parent('tbody');

		if (tbody.size() > 0)
		{
			pacs[i].change(function () {
				pac = jQuery(this);

				var id_pac = pac.attr('id');
				var size = id_pac.length;
				var part;
				var cursor;
				var id = "";

				// Recherche de l'id dans l'attribut 'id' de l'élément html pac
				for (var p = size; p > 0; p--)
				{
					cursor = size - p + 1;
					part = id_pac.substr(p - 1, cursor);

					if (isNaN(parseInt(part)))
					{
						break;
					} else
					{
						id = parseInt(part);
					}
				}

				// Si l'id est null (si c'est le premier élément)
				if (isNaN(id))
				{
					id = "";
				}

				var selected = pac.val();
				var options = pac.find('option');

				options.each(function () {
					var value = jQuery(this).val();

					if (value != selected)
					{
						tbody.find('tr th label[for=configuration_' + value + id + ']').parent('th').parent('tr').hide();
					} else
					{
						tbody.find('tr th label[for=configuration_' + value + id + ']').parent('th').parent('tr').show();
					}
				});
			});

			pacs[i].change();
		}
	}

	/*
	// Physical Access Controller magic
	// Ancien Script ne gérant qu'un seul contrôleur

	var physical_access_controller = jQuery('select[name=configuration[physical_access_controller]]');
	if (physical_access_controller.size() > 0)
	{
		var tbody = physical_access_controller.parent('td').parent('tr').parent('tbody');

		if (tbody.size() > 0)
		{
			physical_access_controller.change(function () {
				var options = physical_access_controller.find('option');
				var selected = physical_access_controller.val();

				options.each(function () {
					var value = jQuery(this).val();

					if (value != selected)
					{
						tbody.find('tr th label[for=configuration_' + value + ']').parent('th').parent('tr').hide();
					} else
					{
						tbody.find('tr th label[for=configuration_' + value + ']').parent('th').parent('tr').show();
					}
				});
			});

			physical_access_controller.change();
		}
	}
	// */

	// Home Automation Controller magic

	var nb_hac = 1;
	var hacs = new Array();

	// Compte le nombre de contrôleurs
	while (jQuery('select[name=configuration[home_automation_controller'+(nb_hac+1)+']]').length > 0)
	{
		nb_hac++;
	}

	// Pour chaque contrôleur...
	for (var j = 1; j <= nb_hac; j++)
	{
		hacs[j] = jQuery('select[name=configuration[home_automation_controller' + j + ']]');

		var tbody = hacs[j].parent('td').parent('tr').parent('tbody');

		if (tbody.size() > 0)
		{
			hacs[j].change(function () {
				hac = jQuery(this);

				var id_hac = hac.attr('id');
				var size = id_hac.length;
				var part;
				var cursor;
				var id = "";

				// Recherche de l'id dans l'attribut 'id' de l'élément html hac
				for (var p = size; p > 0; p--)
				{
					cursor = size - p + 1;
					part = id_hac.substr(p - 1, cursor);

					if (isNaN(parseInt(part)))
					{
						break;
					} else
					{
						id = parseInt(part);
					}
				}

				// Si l'id est null (si c'est le premier élément)
				if (isNaN(id))
				{
					id = "";
				}

				var selected = hac.val();
				var options = hac.find('option');

				options.each(function () {
					var value = jQuery(this).val();

					if (value != selected)
					{
						tbody.find('tr th label[for=configuration_' + value + id + ']').parent('th').parent('tr').hide();
					} else
					{
						tbody.find('tr th label[for=configuration_' + value + id + ']').parent('th').parent('tr').show();
					}
				});
			});

			hacs[j].change();
		}
	}
	// */

	/* var home_automation_controller = jQuery('select[name=configuration[home_automation_controller]]');

	if (home_automation_controller.size() > 0)
	{
		var tbody = home_automation_controller.parent('td').parent('tr').parent('tbody');

		if (tbody.size() > 0)
		{
			home_automation_controller.change(function () {
				var options = home_automation_controller.find('option');
				var selected = home_automation_controller.val();

				options.each(function () {
					var value = jQuery(this).val();

					if (value != selected)
					{
						tbody.find('tr th label[for=configuration_' + value + ']').parent('th').parent('tr').hide();
					} else
					{
						tbody.find('tr th label[for=configuration_' + value + ']').parent('th').parent('tr').show();
					}
				});
			});

			home_automation_controller.change();
		}
	}
	// */

	// Network magic

	var network = jQuery('label[for^=configuration_Network]').parent('th').parent('tr').find('td');

	if (network.size() > 0)
	{
		var ipv4_type = network.find('label[for=configuration_Network_ipv4_type]').parent('th').parent('tr').find('td select');

		if (ipv4_type.size() > 0)
		{
			ipv4_type.change(function () {
				var selected = ipv4_type.val();

				if (selected != "static")
				{
					network.find('tr th label[for=configuration_Network_ipv4_address]').parent('th').parent('tr').hide();
					network.find('tr th label[for=configuration_Network_ipv4_netmask]').parent('th').parent('tr').hide();
					network.find('tr th label[for=configuration_Network_ipv4_gateway]').parent('th').parent('tr').hide();
				} else
				{
					network.find('tr th label[for=configuration_Network_ipv4_address]').parent('th').parent('tr').show();
					network.find('tr th label[for=configuration_Network_ipv4_netmask]').parent('th').parent('tr').show();
					network.find('tr th label[for=configuration_Network_ipv4_gateway]').parent('th').parent('tr').show();
				}
			});

			ipv4_type.change();
		}
	}

	// Email magic

	var email = jQuery('label[for^=configuration_Email]').parent('th').parent('tr').find('td');

	if (email.size() > 0)
	{
		var use_mail = email.find('label[for=configuration_Email_use_mail]').parent('th').parent('tr').find('td input');
		var use_authentication = email.find('label[for=configuration_Email_smtp_use_authentication]').parent('th').parent('tr').find('td input');

		if (use_mail.size() > 0)
		{
			use_mail.change(function () {
				var checked = use_mail.attr('checked');

				if (!checked)
				{
					email.find('tr th label[for!=configuration_Email_use_mail]').parent('th').parent('tr').hide();
				} else
				{
					email.find('tr th label[for!=configuration_Email_use_mail]').parent('th').parent('tr').show();
					use_authentication.change();
				}
			});

			use_mail.change();
		}

		if (use_authentication.size() > 0)
		{
			use_authentication.change(function () {
				var checked = use_authentication.attr('checked');

				if (!checked)
				{
					email.find('tr th label[for=configuration_Email_smtp_username]').parent('th').parent('tr').hide();
					email.find('tr th label[for=configuration_Email_smtp_password]').parent('th').parent('tr').hide();
				} else
				{
					email.find('tr th label[for=configuration_Email_smtp_username]').parent('th').parent('tr').show();
					email.find('tr th label[for=configuration_Email_smtp_password]').parent('th').parent('tr').show();
				}
			});

			use_authentication.change();
		}
	}

	// Backup magic

	var backup = jQuery('label[for^=configuration_Backup]').parent('th').parent('tr').find('td');

	if (backup.size() > 0)
	{
		var backup_method = backup.find('label[for=configuration_Backup_backup_method]').parent('th').parent('tr').find('td select');

		if (backup_method.size() > 0)
		{
			backup_method.change(function () {
				var selected = backup_method.val();

				if (selected == "none")
				{
					backup.find('tr th label[for!=configuration_Backup_backup_method]').parent('th').parent('tr').hide();
				} else
				{
					backup.find('tr th label[for!=configuration_Backup_backup_method]').parent('th').parent('tr').show();
				}
			});

			backup_method.change();
		}
	}

});
