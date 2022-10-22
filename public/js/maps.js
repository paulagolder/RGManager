
window.colors= [];
window.colors[0]="#FF000088";
window.colors[1]="#00FF0088";
window.colors[2]="#0000FF88";
window.colors[3]="#FFFF0088";
window.colors[4]="#FF00FF88";
window.colors[5]="#00FFFF88";
window.colors[6]="#80000088";
window.colors[7]="#00800088";
window.colors[8]="#00008088";
window.colors[9]="#80800088";
window.colors[10]="#80008088";
window.colors[11]="#00808088";
window.colors[12]="#5a5a5a88";


function getColor(i)
{
  return window.colors[i]
}




function myGoodMap(location)
{
  var location_dc =redecode(location);
  var mylocation = JSON.parse(location_dc);
  var lat = mylocation.midlat;
  var long = mylocation.midlong;
  var zoom = 12;
  if( (typeof lat === "undefined") || lat < 40)
  {
    long =-1.8304;
    lat = 52.6854 ;
    zoom = 12;
  }

  if(zoom <1 ) zoom = 1;

  var mymap = L.map('goodmapid').setView([ lat , long], zoom);
  mapLink =
  '<a href="http://openstreetmap.org">OpenStreetMap</a>';
  L.tileLayer(
    'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; ' + mapLink + ' Contributors',
      xmaxZoom: 20,
    }).addTo(mymap);


    return mymap;
}


function myStMap(location)
{
  var location_dc =redecode(location);
  var mylocation = JSON.parse(location_dc);
  var lat = mylocation.midlat;
  var long = mylocation.midlong;
  var zoom = 12;
  if( (typeof lat === "undefined") || lat < 40)
  {
    long =-1.8304;
    lat = 52.6854 ;
    zoom = 12;
  }

  if(zoom <1 ) zoom = 1;

  var mymap = L.map('stmapid').setView([ lat , long], zoom);
  mapLink =
  '<a href="http://openstreetmap.org">OpenStreetMap</a>';
  L.tileLayer(
    'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; ' + mapLink + ' Contributors',
      "maxNativeZoom": 19, "maxZoom": 20,
    }).addTo(mymap);


    return mymap;
}






function setBounds2(amap, amybounds)
{

  var mybounds_dc =redecode(amybounds);
  var mybounds = JSON.parse(mybounds_dc);
  if(mybounds["minlat"]===null) return;
  var bounds =[];
  bounds.push([ mybounds["maxlat"], mybounds["minlong"]]);
  bounds.push([ mybounds["minlat"],mybounds["maxlong"]]);
  amap.fitBounds(bounds);
}

function setBoundsGeodata(amap,geodata)
{
  if(geodata=="") return;
  var mybounds_dc =redecode(geodata);
  var mybounds = JSON.parse(mybounds_dc);
  if(mybounds["minlat"]===null) return;
  var bounds =[];
  bounds.push([ mybounds["maxlat"], mybounds["minlong"]]);
  bounds.push([ mybounds["minlat"],mybounds["maxlong"]]);
  amap.fitBounds(bounds);
}

function setMarkers(amap,amybounds)
{
  var mybounds_dc =redecode(amybounds);
  var mybounds = JSON.parse(mybounds_dc);
  if(mybounds["minlat"]===null) return;
  var nwlat =  mybounds["maxlat"];
  var nwlong =  mybounds["minlong"];
  var selat =  mybounds["minlat"];
  var selong =  mybounds["maxlong"];
  var marker = L.marker([nwlat, nwlong]).addTo(amap);
   marker = L.marker([selat, selong]).addTo(amap);
   marker = L.marker([selat, nwlong]).addTo(amap);
   marker = L.marker([nwlat, selong]).addTo(amap);
}

function setMarkers2(amap,amybounds)
{
  var mybounds_dc =redecode(amybounds);
  var mybounds = JSON.parse(mybounds_dc);
  if(mybounds["minlat"]===null) return;
  var nwlat =  mybounds["maxlat"];
  var nwlong =  mybounds["minlong"];
  var selat =  mybounds["minlat"];
  var selong =  mybounds["maxlong"];
  var marker = L.marker([nwlat, nwlong]).addTo(amap);
  marker = L.marker([selat, selong]).addTo(amap);
  marker = L.marker([selat, nwlong]).addTo(amap);
  marker = L.marker([nwlat, selong]).addTo(amap);
}

function drawBox(amap,mybounds)
{
  var bounds =[];
  bounds.push([ mybounds["nw"]["lat"],mybounds["nw"]["long"]]);
  bounds.push([ mybounds["nw"]["lat"],mybounds["se"]["long"]]);
  bounds.push([ mybounds["se"]["lat"],mybounds["se"]["long"]]);
  bounds.push([ mybounds["se"]["lat"],mybounds["nw"]["long"]]);
  bounds.push([ mybounds["nw"]["lat"],mybounds["nw"]["long"]]);
  var mypolyline = new L.Polyline(bounds, {
    color: 'black',
    weight: 1,
    opacity: 0.5,
    smoothFactor: 1
  });
  mypolyline.addTo(amap);
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
  if( cumbounds[0] == null)
  {
    cumbounds[0] = bounds;
  }else
  {
    cumbounds[0].extend(bounds);
  }

});

}

function makeKMLLayer(amap,kmlfilepath,style, fitbounds=false, label='')
{
  var track =null;
  fetch(kmlfilepath).then(res => res.text()).then(kmltext =>
  {
    const parser = new DOMParser();
    const kml = parser.parseFromString(kmltext, 'text/xml');
    track = new L.KML(kml);
    track.setStyle(style);
    amap.addLayer(track);
     const bounds = track.getBounds();
     if(bounds)
     {
    //  amap.fitBounds(bounds);
    }
    if(label)
       track.bindPopup(label);
  });
  return track;
}


function makeOutlineKml(amap,kmlfilepath,style, fitbounds=false, label='')
{
  var polygon = omnivore.kml(kmlfilepath);
  polygon.setStyle(style);
  polygon.addTo(amap);
 if(fitbounds)
 {
  polygon.on('ready', function() {
    amap.fitBounds(polygon.getBounds())
  });
 }
}

function addMyKML2(mymap, kmlfile, style,fitbounds=false, label='')
{

  var runLayer = omnivore.kml(kmlfile)
  .on('ready', function() {

    runLayer.eachLayer(function(layer) {
      runLayer.bindPopup(layer.feature.properties.name);
      runLayer.setStyle( style);
      // mymap.fitBounds(runLayer.getBounds());
      //runLayer.bindPopup(label);
    });
  })
  .addTo(mymap);

  return runLayer;
}



function addKMLLayer(layergroup,kmlfilepath,style)
{

  fetch(kmlfilepath).then(res => res.text()).then(kmltext =>
  {
    const parser = new DOMParser();

    const kml = parser.parseFromString(kmltext, 'text/xml');
    track = new L.KML(kml);
    track.setStyle(style);
    layergroup.addLayer(track);
  });
}





function addMyKMLLayer(mymap,kmllayer,color)
{
  if(kmllayer)
  {
   // var kmllayer = new L.KML(kmlfilepath, {async: true});
    kmllayer.on("loaded", function(e)
    {
      kmllayer.setStyle({color: color , weight: 14});
     // mymap.fitBounds(e.target.getBounds(), {padding: [50,50]});
    });
    mymap.addLayer(kmllayer);
    return kmllayer
  }
}


function redecode(mystr)
{
    if(typeof mystr === 'object') return mystr;
    if (!mystr.includes('&') ) return mystr;
    var instr = mystr.replace(/&amp;/g  , '&');
    instr = instr.replace(/&gt;/g  , '>');
    instr = instr.replace(/&lt;/g   , '<');
    instr = instr.replace(/&quot;/g  ,  '"');
    instr = instr.replace(/&#39;/g   ,"'");
    instr = instr.replace(/&#039;/g   ,",");


    return instr;
}


function drawPath(amap,mypath,label,style=null)
{
  var polyline = null;
  if(style == null)
  {
     style = {
      color: "#800000",
      weight: 10,
      opacity: 0.4
    };

  }
  if(typeof mypath !== 'object')
  {
    mypath = mypath.replace( "[[{","[{");
    mypath = mypath.replace( "}]]","}]");
    if(mypath.length >4)
    {
  var dpath =redecode(mypath);
  var points = JSON.parse(dpath);
  if(points.length<1) return;
  var bn = 1;
  for(point of points)
  {
    var track = point.steps;
     polyline = L.polyline(track, style).addTo(amap);
     polyline.bindPopup(label);
     bn=bn+1;
  }

  }
}
else
{

  var track = mypath.steps;

  polyline = L.polyline(track, style).addTo(amap);
}
return polyline ;

}


function setEndMarkers(amap,branch)
{
    var markers =[];
    var track = branch.steps;
    var l = track.length;
  var start=track[0];
  if(!start) return;
  if(start.length<2) return;
  scircle = L.circleMarker(start).addTo(amap);
  scircle.setStyle({color: 'green'});
  if(l<2) return;
  var end = track[l-1];
  ecircle = L.circleMarker(end).addTo(amap);
  ecircle.setStyle({color: 'red'});
  markers["start"] = scircle;
  markers["end"]=ecircle;
  return markers;
}

function doesFileExist(urlToFile)
{
  var xhr = new XMLHttpRequest();
  xhr.open('HEAD', urlToFile, false);
  xhr.send();

  if (xhr.status == "404") {
    console.log("File doesn't exist");
    return false;
  } else {
    console.log("File exists");
    return true;
  }
}
