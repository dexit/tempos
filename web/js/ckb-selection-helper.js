var checkflag = "true";

function checkAllCkb(size)
{
	if (size === null || typeof(size) == 'undefined')
	{
		var size = 0;
	}
	
	for (i = 1; i <= size; i++)
	{
		var element = document.getElementsByName('ckb' + i);
		
		if (checkflag == "false")
		{
			if (typeof(element[0]) != 'undefined')
			{
				if (!element[0].disabled)
				{
					element[0].checked = true;
				}
			}
		} else
		{
			if (typeof(element[0]) != 'undefined')
			{
				element[0].checked = false;
			}
		}
	}
	
	if (checkflag == "false")
	{
		checkflag = "true";
		return "Tout décocher";
	} else
	{
		checkflag = "false";
		return "Tout cocher";
	}
}

function reverseAllCkb(size)
{
	if (size === null || typeof(size) == 'undefined')
	{
		var size = 0;
	}
	
	for (i = 1; i <= size; i++)
	{
		var element = document.getElementsByName('ckb' + i);
		
		if (typeof(element[0]) != 'undefined')
		{
			if (element[0].checked == true)
			{
				element[0].checked = false;
			} else
			{
				if (!element[0].disabled)
				{
					element[0].checked = true;
				}
			}
		}
	}
}