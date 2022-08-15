var marker;
var map;
var longitude = 0;
var latitude = 0;
var placeID = null;
placeID = document.getElementById('google_place_id').value;
window.$ = window.jQuery = jQuery;
$(document).on('keypress', 'input#pac-input', function (e) {
    if (e.keyCode === 13) {
        return false;
    }
});

function renderGoogleMap() {
    latitude = parseFloat(document.getElementById('lat').value);
    longitude = parseFloat(document.getElementById('long').value);

    if (isNaN(latitude)){
        latitude = 0;
    }
    if (isNaN(longitude)){
        longitude = 0;
    }
    var input = document.getElementById('pac-input');
    var searchBox = new google.maps.places.SearchBox(input);


    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 13,
        center: {lat: latitude, lng: longitude}
    });

    marker = new google.maps.Marker({
        map: map,
        draggable: false,
        animation: google.maps.Animation.DROP,
        position: {lat: latitude, lng: longitude}
    });

    // marker.addListener('click', handleEvent);
    // marker.addListener('drag', handleEvent);
    // marker.addListener('dragend', handleEvent);


    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
    map.addListener('bounds_changed', function () {
        searchBox.setBounds(map.getBounds());
    });

    searchBox.addListener('places_changed', function () {
        var places = searchBox.getPlaces();

        if (places.length === 0)
            return;

        var bounds = new google.maps.LatLngBounds();
        places.forEach(function (place) {
            if (!place.geometry)
                return;

            if (place.geometry.viewport)
                bounds.union(place.geometry.viewport);
            else
                bounds.extend(place.geometry.location);
            setLongLat(place.place_id,place.geometry.location.lat(),place.geometry.location.lng());
            // console.log(place.formatted_address)
            // $('textarea[name=address_company]').val(place.formatted_address);
        });

        map.fitBounds(bounds);
        placeMarker(map.center);


    });

    // google.maps.event.addListener(map, 'click', function (event) {
    //     placeMarker(event.latLng);
    //     handleEvent(event)
    // });

    function placeMarker(location) {
        marker.setPosition(location);
    }
    // var request = {
    //     query: el.find('option:selected').text(),
    //     fields: ['name', 'geometry'],
    // };
    //
    // var service = new google.maps.places.PlacesService(map);
    //
    // service.find(request, function(results, status) {
    //     if (status === google.maps.places.PlacesServiceStatus.OK) {
    //         map.setCenter(results[0].geometry.location);
    //         marker.setPosition(results[0].geometry.location);
    //         setLongLat();
    //     }
    // });

    setLongLat(placeID,latitude,longitude);
}

function handleEvent(event) {

    document.getElementById('lat').value = event.latLng.lat();
    document.getElementById('long').value = event.latLng.lng();
}

function setLongLat(place_id,lat,long) {
    document.getElementById('long').value = long;
    document.getElementById('lat').value = lat;
    document.getElementById('google_place_id').value = place_id
}

function toggleBounce() {
    if (marker.getAnimation() !== null) {
        marker.setAnimation(null);
    } else {
        marker.setAnimation(google.maps.Animation.BOUNCE);
    }
}

