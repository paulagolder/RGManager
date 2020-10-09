
window.colors= [];
window.colors[0]="#FF000088";
window.colors[1]="#00FF0088";
window.colors[2]="#0000FF88";
window.colors[3]="#FFFF0088";
window.colors[4]="#FF00FF88";
window.colors[5]="#00FFFF88";
window.colors[6]="#FF000088";
window.colors[7]="#00FF0088";
window.colors[8]="#0000FF88";
window.colors[9]="#FFFF0088";
window.colors[10]="#FF00FF88";
window.colors[11]="#00FFFF88";

function getColor(i)
{
  return window.colors[i]
}
 // $location=  ''{"longitude":-1.8304,"latitude":52.6854 }';



function myMapper4(location)
{
  var location_dc =redecode(location);
  var mylocation = JSON.parse(location_dc);
  var lat = mylocation.latitude;
  var long = mylocation.longitude;
  var zoom = 18;
  if( lat < 40)
  {
    long =-1.8304;
    lat = 52.6854 ;
    zoom = 12;
  }

  if(zoom <1 ) zoom = 1;

  var mymap = L.map('mapid').setView([ lat , long], zoom);
  mapLink =
  '<a href="http://openstreetmap.org">OpenStreetMap</a>';
  L.tileLayer(
    'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; ' + mapLink + ' Contributors',
      maxZoom: 20,
    }).addTo(mymap);
 // var marker = L.marker([lat , long]).addTo(mymap);

  var kmlfilepath = mylocation.kml;
  if(CheckUrl(mylocation.kml))
  {
    kmlfilepath = mylocation.kml;
  }
  else if(CheckUrl("http://lerot.org/RoadGroups/maps/"+mylocation.RoadgroupId+".kml"))
  {
    kmlfilepath =  "http://lerot.org/RoadGroups/maps/"+mylocation.RoadgroupId+".kml";
  }
  else if(CheckUrl("http://lerot.org/RoadGroups/maps/"+mylocation.WardId+".kml"))
  {
    kmlfilepath =  "http://lerot.org/RoadGroups/maps/"+mylocation.WardId+".kml";
  }
  else
  {
    kmlfilepath = "http://lerot.org/RoadGroups/maps/LDC.kml";
  }
  addMyKML(mymap,kmlfilepath,'#0000FF');

  return mymap;
}


  function mySwMap(location)
  {
    var location_dc =redecode(location);
    var mylocation = JSON.parse(location_dc);
    var lat = mylocation.latitude;
    var long = mylocation.longitude;
    var zoom = 18;
    if( lat < 40)
    {
      long =-1.8304;
      lat = 52.6854 ;
      zoom = 12;
    }

    if(zoom <1 ) zoom = 1;

    var mymap = L.map('swmapid').setView([ lat , long], zoom);
    mapLink =
    '<a href="http://openstreetmap.org">OpenStreetMap</a>';
    L.tileLayer(
      'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; ' + mapLink + ' Contributors',
        maxZoom: 20,
      }).addTo(mymap);


    //var marker = L.marker([lat , long]).addTo(mymap);
   // var label = mylocation.name;
   // marker.bindPopup(label);
   // marker.on('mouseover',function(ev) {
   //   marker.openPopup();
   // });

  return mymap;
}

function myRgMap(location)
{
  var location_dc =redecode(location);
  var mylocation = JSON.parse(location_dc);
  var lat = mylocation.latitude;
  var long = mylocation.longitude;
  var zoom = 18;
  if( lat < 40)
  {
    long =-1.8304;
    lat = 52.6854 ;
    zoom = 12;
  }

  if(zoom <1 ) zoom = 1;

  var mymap = L.map('rgmapid').setView([ lat , long], zoom);
  mapLink =
  '<a href="http://openstreetmap.org">OpenStreetMap</a>';
  L.tileLayer(
    'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; ' + mapLink + ' Contributors',
      maxZoom: 20,
    }).addTo(mymap);


    return mymap;
}

function myWMap(location)
{
  var location_dc =redecode(location);
  var mylocation = JSON.parse(location_dc);
  var lat = mylocation.latitude;
  var long = mylocation.longitude;
  var zoom = 18;
  if( lat < 40)
  {
    long =-1.8304;
    lat = 52.6854 ;
    zoom = 12;
  }

  if(zoom <1 ) zoom = 1;

  var mymap = L.map('wmapid').setView([ lat , long], zoom);
  mapLink =
  '<a href="http://openstreetmap.org">OpenStreetMap</a>';
  L.tileLayer(
    'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; ' + mapLink + ' Contributors',
      maxZoom: 20,
    }).addTo(mymap);


    return mymap;
}

function myMapper6(mapid,location)
{
  var location_dc =redecode(location);
  var mylocation = JSON.parse(location_dc);
  var lat = mylocation.latitude;
  var long = mylocation.longitude;
  var zoom = 18;
  if( lat < 40)
  {
    long =-1.8304;
    lat = 52.6854 ;
    zoom = 12;
  }

  if(zoom <1 ) zoom = 1;

  var mymap = L.map(mapid).setView([ lat , long], zoom);
  mapLink =
  '<a href="http://openstreetmap.org">OpenStreetMap</a>';
  L.tileLayer(
    'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; ' + mapLink + ' Contributors',
      maxZoom: 20,
    }).addTo(mymap);
    var marker = L.marker([lat , long]).addTo(mymap);
    var label = mylocation.name;
    marker.bindPopup(label);
    marker.on('mouseover',function(ev) {
      marker.openPopup();
    });
    return mymap;
}

function CheckUrl(url)
{
  try {
  var http = null;
  if (window.XMLHttpRequest)
  {// code for IE7+, Firefox, Chrome, Opera, Safari
     http=new XMLHttpRequest();
  }
  else
  {// code for IE6, IE5
     http=new ActiveXObject("Microsoft.XMLHTTP");
  }
  http.open('HEAD', url, false);
  http.send();
  return http.status!=404;

  }
  catch(err) {
    return false;
  }
}



function addMyKML(mymap,kmlfilepath,color, cumbounds)
{
  fetch(kmlfilepath).then(res => res.text()).then(kmltext =>
  {
  const parser = new DOMParser();
  const kml = parser.parseFromString(kmltext, 'text/xml');
  const track = new L.KML(kml);
  track.setStyle({color: color , weight: 14 ,  clickable: true ,opacity: 0.7, fillOpacity: 0.1 });
  mymap.addLayer(track);

  const bounds = track.getBounds();
  if( cumbounds == null)
  {
    cumbounds = bounds;
  }else
  {
    cumbounds.extendby(bounds);
  }

});

}

function makeKMLLayer(amap,kmlfilepath,style)
{
  var track =null;
  fetch(kmlfilepath).then(res => res.text()).then(kmltext =>
  {
    const parser = new DOMParser();

    const kml = parser.parseFromString(kmltext, 'text/xml');
    track = new L.KML(kml);
    track.setStyle();
    amap.addLayer(track);

    const bounds = track.getBounds();
   amap.fitBounds(bounds);
  });
}

function addMyKMLtracks(mymap,tracks,color)
{
  var track;

  for (track of tracks)
  {
    var path = "http://lerot.org/RoadGroups/maps/"+track+".kml";
    if(CheckUrl(path))
    {
      addMyKML(mymap,path,color);
    }
  }

}



function addMyKMLLayer(mymap,kmllayer,color)
{
  if(kmllayer)
  {
   // var kmllayer = new L.KML(kmlfilepath, {async: true});
    kmllayer.on("loaded", function(e)
    {
      kmllayer.setStyle({color: color , weight: 14});
      mymap.fitBounds(e.target.getBounds(), {padding: [50,50]});
    });
    mymap.addLayer(kmllayer);
    return kmllayer
  }
}


function redecode(mystr)
{
    var instr = mystr.replace(/&amp;/g  , '&');
    instr = instr.replace(/&gt;/g  , '>');
    instr = instr.replace(/&lt;/g   , '<');
    instr = instr.replace(/&quot;/g  ,  '"');
    instr = instr.replace(/&#39;/g   ,"'");
    return instr;
}

//mymappera(mymapwards,52.6854, -1.8304,12);

