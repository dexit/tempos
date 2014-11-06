Autocompleter.Ezkode = Class.create(Autocompleter.Local, {
  getUpdatedChoices: function() {
    var address = document.getElementById(location_input).value;
  
    geocoder.geocode( { address: address}, 
                      function(results, status) {

      location_autocomplete.options.array.clear();

      if (status == google.maps.GeocoderStatus.OK && results.length) {
      
        if (status != google.maps.GeocoderStatus.ZERO_RESULTS) {

          for (var i = 0; i < results.length; i++) {
            location_autocomplete.options.array.push(results[i].formatted_address);
          }
        
          location_autocomplete.updateChoices("<ul><li>" + location_autocomplete.options.array.join('</li><li>') + "</li></ul>");
          location_autocomplete.show();
        } else {
          location_autocomplete.hide();
        }
      
      } else {
        location_autocomplete.options.array.push("Google geocoder was unsuccessful due to: " + status);
        location_autocomplete.updateChoices("<ul><li>" + location_autocomplete.options.array.join('</li><li>') + "</li></ul>");
        location_autocomplete.show();
      }
    });
  }
});

var geocoder;
var location_autocomplete;
var location_input; //initialized in sfWidgetFormInputGeoComplete


function initialize() {
  geocoder = new google.maps.Geocoder();
  location_autocomplete = new Autocompleter.Ezkode(location_input, 'geo_complete_suggestions', [], {});
}

window.onload = initialize;
