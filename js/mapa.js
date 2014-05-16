var mainMap;
var mapPlacedMarkers = [];

function placeMarkers()
{
    for ( i = 0 ; i < markers.length ; i++)
    {	 
        var marker = new google.maps.Marker({
            id: markers[i][0],
            position: new google.maps.LatLng( markers[i][1], markers[i][2] ),
            icon: markers[i][4],
            map: mainMap
        });

        marker.title = markers[i][3];

        google.maps.event.addListener(marker, 'click', function() {
            if ( !this.infoWindow )
            {
                this.infoWindow = new google.maps.InfoWindow({
                    content: '<div class="infoWindow">' + this.title + '</div>'
                });
                
                this.infoWindowOpened = false;
            }
            
            if ( !this.infoWindowOpened ) {
                for ( i = 0 ; i < mapPlacedMarkers.length ; i++ ) {
                    if ( typeof mapPlacedMarkers[i].infoWindow  != 'undefined' && mapPlacedMarkers[i].infoWindowOpened ) {
                        mapPlacedMarkers[i].infoWindow.close();
                        mapPlacedMarkers[i].infoWindowOpened = false;
                    }
                }
                
                this.infoWindow.open(mainMap,this);
                this.infoWindowOpened = true;
            } else {
                this.infoWindow.close();
                this.infoWindowOpened = false;   
            }
            
         });

         mapPlacedMarkers.push(marker);

/*
        google.maps.event.addListener(marker, 'mouseover', function() {

            if ( !this.infoWindow )
            {
                this.infoWindow = new google.maps.InfoWindow({
                    content : '<div class="infoWindow">' + this.title + '</div>'
                });
            }

            this.infoWindow.open(mainMap, this);
        });

        google.maps.event.addListener(marker, 'mouseout', function() {
            if ( this.infoWindow )
            {
                this.infoWindow.close();
            }
        });*/
    }
}

 $( function() {
    mainMap = new google.maps.Map(document.getElementById('mapita'), {
        zoom: 17,
//          mapTypeId: 'beestag_map',
//           mapTypeControl : false,
//			scaleControl: false,
        streetViewControl: false,
//			zoomControl: false,
        rotateControl: false,
        panControl: true
    }); 

    mainMap.setCenter( new google.maps.LatLng(-34.612328, -58.369355) );
});