// Ce script n'est plus utilisé dans la nouvelle version de Tempo's

/* 
jQuery(document).ready(function() {
	var menubar = jQuery('div#menubar');

	menubar.find('dt').wrapInner('<a href="" class="opened"></a>');
	menubar.find('dt a').click(
		function() {
			if (jQuery(this).hasClass('opened'))
			{
				jQuery(this).parents().filter('dt:first').next('dd').hide('fast');
				jQuery(this).removeClass('opened');
			} else {
				jQuery(this).parents().filter('dt:first').next('dd').show('fast');
				jQuery(this).addClass('opened');
				//menubar.find('dt a').not(this).parents().filter('dt:first').next('dd').hide('slide');
				//menubar.find('dt a').not(this).removeClass('opened');
			}
			return false;
		}
	);

	// We compress the menu
	//menubar.find('dd').hide();
});
 */