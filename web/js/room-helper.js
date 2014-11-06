jQuery(document).ready(function() {
	
	//jQuery('select[name=roomSearch[Activity_id]]').addSearchBox();
	jQuery('select[name^=roomSearch]').addSearchBox();
	jQuery('ul:checkboxList("room, room_has_energyaction_list")').addFilter();
	jQuery('ul:checkboxList("energyaction, room_has_energyaction_list")').addFilter();
	jQuery('ul:checkboxList("reservationDelete, rooms")').addFilter();
	jQuery('ul:checkboxList("reporting, rooms")').addFilter();
});
