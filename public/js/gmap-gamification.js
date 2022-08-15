// Gamification Googlemap Start
jQuery(document).on('keypress', 'input#pac-input-gamification', function (e) {
    if (e.keyCode === 13) {
        return false;
    }
});

var marker2;
var map2;
placeID2 = document.getElementById('google_place_id_gamification').value;
var longitude2 = 0;
var latitude2 = 0;

function initGamificactionMap() {
    latitude2 = parseFloat(document.getElementById('latGamify').value);
    longitude2 = parseFloat(document.getElementById('longGamify').value);

    if (isNaN(latitude2)){
        latitude2 = 0;
    }
    if (isNaN(longitude2)){
        longitude2 = 0;
    }
    console.log(longitude2)
    console.log(latitude2)
    map2 = new google.maps.Map(document.getElementById('mapGoogleGamification'), {
        zoom: 13,
        center: {lat: latitude2, lng: longitude2}
    });

    marker2 = new google.maps.Marker({
        map: map2,
        draggable: false,
        animation: google.maps.Animation.DROP,
        position: {lat: latitude2, lng: longitude2}
    });

    // marker2.addListener('click', handleEvent2);
    // marker2.addListener('drag', handleEvent2);
    // marker2.addListener('dragend', handleEvent2);
    // Create the search box and link it to the UI element.
    var input2 = document.getElementById('pac-input-gamification');
    var searchBox2 = new google.maps.places.SearchBox(input2);
    map2.controls[google.maps.ControlPosition.TOP_LEFT].push(input2);

    // Bias the SearchBox results towards current map's viewport.
    map2.addListener('bounds_changed', function () {
        searchBox2.setBounds(map2.getBounds());
    });


    searchBox2.addListener('places_changed', function () {
        var places2 = searchBox2.getPlaces();

        if (places2.length === 0)
            return;

        var bounds2 = new google.maps.LatLngBounds();
        places2.forEach(function (place2) {

            if (!place2.geometry)
                return;

            if (place2.geometry.viewport)
                bounds2.union(place2.geometry.viewport);
            else
                bounds2.extend(place2.geometry.location);

            // console.log(place2)
            setLongLat2(place2.place_id,place2.geometry.location.lat(),place2.geometry.location.lng());
        });

        map2.fitBounds(bounds2);
        placeMarker2(map2.center);

    });

    google.maps.event.addListener(map2, 'click', function (event) {
        placeMarker2(event.latLng);
        handleEvent2(event)
        // setLongLat();
    });

    function placeMarker2(location) {

        marker2.setPosition(location);
        //map.setCenter(location);
        // setLongLat();
    }

    setLongLat2(placeID2,latitude2,longitude2);
}
function handleEvent2(event) {
    document.getElementById('latGamify').value = event.latLng.lat();
    document.getElementById('longGamify').value = event.latLng.lng();
}
function setLongLat2(place_id,lat,long) {
    console.log(long)
    console.log(lat)
    console.log(place_id)
    document.getElementById('longGamify').value = long;
    document.getElementById('latGamify').value = lat;
    document.getElementById('google_place_id_gamification').value = place_id;
}

function toggleBounce2() {
    if (marker2.getAnimation() !== null) {
        marker2.setAnimation(null);
    } else {
        marker2.setAnimation(google.maps.Animation.BOUNCE);
    }
}

// function changeToMap(el){
//     var request = {
//         query: el.find('option:selected').text(),
//         fields: ['name', 'geometry'],
//     };
//
//     var service = new google.maps.places.PlacesService(map);
//
//     service.findPlaceFromQuery(request, function(results, status) {
//         if (status === google.maps.places.PlacesServiceStatus.OK) {
//             map.setCenter(results[0].geometry.location);
//             marker.setPosition(results[0].geometry.location);
//             setLongLat();
//         }
//     });
// }
// $(document).on('change','select[id=city_search_gamification], select[id=state_search_gamification]', function () {
//     changeToMap($(this))
// });

// Gamification Googlemap End