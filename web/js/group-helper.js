jQuery(document).ready(function() {
	
	jQuery('ul:checkboxList("usergroup, usergroup_has_chief_list")').addFilter();
	jQuery('ul:checkboxList("usergroup, usergroup_has_user_list")').addFilter();
	jQuery('ul:checkboxList("usergroupUsers, usergroup_has_user_list")').addFilter();
	jQuery('ul:checkboxList("userSearch, usergroupsAsLeader")').addFilter();
	jQuery('ul:checkboxList("userSearch, usergroupsAsMember")').addFilter();
	jQuery('select[name=usergroupUsers[Usergroup_id]]').addSearchBox();
	jQuery('ul:checkboxList("reporting, users")').addFilter();
	jQuery('ul:checkboxList("reporting, usergroups")').addFilter();
});
