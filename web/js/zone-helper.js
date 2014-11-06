jQuery(document).ready(function() {

	jQuery('ul:checkboxList("room, zone_has_room_list")').addZoneFilter();
	jQuery('ul:checkboxList("reporting, zones")').addFilter();
});
