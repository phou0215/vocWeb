// success: function(data) {
// 	$.each(data.arrjson, function(index, arrjson) {
// 		$('#tabList').append("<tr><td>" + arrjson.no + "</td><td>" + arrjson.name + "</td></tr>");
// 	});

var font_size = 11;
var colors = ["89,105,255", "255,64,123", "46,197,81", "255,199,80","124,252,000","238,232,170","205,133,63","240,230,140",
              "230,230,250","106,90,205","1,191,255","25,25,112","64,224,208","220,20,60"];

$(function(){

  var netType = $("#selectNet option:selected").val();
  var avgType = $("#selectAvg option:selected").val();
  var item_size = 0;
  var jsonData = null;
  var values_region = {};
  var netIndex_no = [];
  var avg_string = "";

  $.ajax({
      url:"/voc/chart/phpdata/view/view_region.php",
      type:"post",
      dataType:"json",
      data:{"avgType":avgType, "netType":netType},
      async: false,
      success: function (data){
        jsonData = data;
        console.log(jsonData);
      },
      error: function (request, status, error){
        console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
      }
    });
    //item_size
    item_size = jsonData.values_base.length;

    //netIndex_no set
    if(netType == "5G"){
      netIndex_no = [0,2,4];
    }else{
      netIndex_no = [1,3,5];
    }

    //avg_string set
    if(avgType == "1"){
      avg_string = "전주";
    }else if(avgType == "2"){
      avg_string = "평일평균";
    }else{
      avg_string = "3주평균";
    }

    //values setting
    for(var key in jsonData.values_base){
      if(key == "KR-11"){
        values_region[key] = jsonData.values_base[key][0][netIndex_no[0]] + jsonData.values_base[key][1][netIndex_no[0]];
      }else{
        values_region[key] = jsonData.values_base[key][netIndex_no[0]];
      }
    }
    $('#locationmap').empty();
    $('#locationmap').vectorMap({

                map: 'kr_mill',
                backgroundColor: '#22313F',
                normalizeFunction: 'polynomial',
                series: {
                  regions: [{
                    values: values_region,
                    scale: ['#C8EEFF', '#006491'],
                    attribute: 'fill',
                  }]
                },
                // backgroundColor: 'transparent',
                borderColor: '#000',
                borderOpacity: 0,
                borderWidth: 0,
                zoomOnScroll: false,
                color: '#25d5f2',
                regionStyle: {
                  initial: {fill: "#e3eaef"},
                  hover:{fill: '#e3eaef'},
                  selected: {fill: '#e3eaef'}
                },
               // markerStyle: {
               //     initial: {
               //         r: 9,
               //         fill: "#25d5f2",
               //         "fill-opacity": .9,
               //         stroke: "#fff",
               //         "stroke-width": 7,
               //         "stroke-opacity": .4
               //     },
               //     hover: {
               //         stroke: "#fff",
               //         "fill-opacity": 1,
               //         "stroke-width": 1.5
               //     }
               // },

               // markers: [{
               //     latLng: [40.71, -74],
               //     name: "New York"
               // }, {
               //     latLng: [37.77, -122.41],
               //     name: "San Francisco"
               // }, {
               //     latLng: [-33.86, 151.2],
               //     name: "Sydney"
               // }, {
               //     latLng: [1.3, 103.8],
               //     name: "Singapore"
               // }],

               onRegionTipShow: function(e, el, code){
                 if (code == 'KR-11'){
                   el.html(el.html()+
                   "<br><b class='text-primary'>최근:</b>(강북: "+numberWithCommas(jsonData.values_base[code][0][netIndex_no[0]])+" 강남:"+numberWithCommas(jsonData.values_base[code][1][netIndex_no[0]])+")"+
                   "<br><b class='text-warning'>"+avg_string+":</b>(강북: "+numberWithCommas(jsonData.values_base[code][0][netIndex_no[1]])+" 강남:"+numberWithCommas(jsonData.values_base[code][1][netIndex_no[1]])+")"+
                   "<br><b class='text-success'>증감:</b>(강북:"+jsonData.values_base[code][0][netIndex_no[2]]+"% 강남:"+jsonData.values_base[code][1][netIndex_no[2]]+"%)");
              		}else{
              			// for Russia show redeclarated values
                   el.html(el.html()+
                   "<br><b class='text-primary'>최근:</b>("+numberWithCommas(jsonData.values_base[code][netIndex_no[0]])+")"+
                   "<br><b class='text-warning'>"+avg_string+":</b>("+numberWithCommas(jsonData.values_base[code][netIndex_no[1]])+")"+
                   "<br><b class='text-success'>증감:</b>("+numberWithCommas(jsonData.values_base[code][netIndex_no[2]])+"%)");
                 }
              },
               hoverOpacity: 0.5,
               hoverColor: false,
               selectedColor: '#c9dfaf',
               selectedRegions: [],
               showTooltip: true,
               // onRegionClick: function(element, code, region) {
               //     var message = 'You clicked "' + region + '" which has the code: ' + code.toUpperCase();
               //     alert(message);
               // }
           });

});

function region_update(){

  var netType = $("#selectNet option:selected").val();
  var avgType = $("#selectAvg option:selected").val();
  var item_size = 0;
  var jsonData = null;
  var values_region = {};
  var netIndex_no = [];
  var avg_string = "";
  $.ajax({
      url:"/voc/chart/phpdata/view/view_region.php",
      type:"post",
      dataType:"json",
      data:{"avgType":avgType, "netType":netType},
      async: false,
      success: function (data){
        jsonData = data;
        console.log(jsonData);
      },
      error: function (request, status, error){
        console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
      }
    });
    //item_size
    item_size = jsonData.values_base.length;

    //netIndex_no set
    if(netType == "5G"){
      netIndex_no = [0,2,4];
    }else{
      netIndex_no = [1,3,5];
    }

    //avg_string set
    if(avgType == "1"){
      avg_string = "전주";
    }else if(avgType == "2"){
      avg_string = "평일평균";
    }else{
      avg_string = "3주평균";
    }

    //values setting
    for(var key in jsonData.values_base){
      if(key == "KR-11"){
        values_region[key] = jsonData.values_base[key][0][netIndex_no[0]] + jsonData.values_base[key][1][netIndex_no[0]];
      }else{
        values_region[key] = jsonData.values_base[key][netIndex_no[0]];
      }
    }
    $('#locationmap').empty();
    $('#locationmap').vectorMap({

               map: 'kr_mill',
               backgroundColor: '#22313F',
               normalizeFunction: 'polynomial',
               series: {
                 regions: [{
                   values: values_region,
                   scale: ['#C8EEFF', '#006491'],
                   attribute: 'fill',
                 }]
               },
               // backgroundColor: 'transparent',
               borderColor: '#000',
               borderOpacity: 0,
               borderWidth: 0,
               zoomOnScroll: false,
               color: '#25d5f2',
               regionStyle: {
                 initial: {fill: "#e3eaef"},
                 hover:{fill: '#e3eaef'},
                 selected: {fill: '#e3eaef'}
               },
               // markerStyle: {
               //     initial: {
               //         r: 9,
               //         fill: "#25d5f2",
               //         "fill-opacity": .9,
               //         stroke: "#fff",
               //         "stroke-width": 7,
               //         "stroke-opacity": .4
               //     },
               //     hover: {
               //         stroke: "#fff",
               //         "fill-opacity": 1,
               //         "stroke-width": 1.5
               //     }
               // },

               // markers: [{
               //     latLng: [40.71, -74],
               //     name: "New York"
               // }, {
               //     latLng: [37.77, -122.41],
               //     name: "San Francisco"
               // }, {
               //     latLng: [-33.86, 151.2],
               //     name: "Sydney"
               // }, {
               //     latLng: [1.3, 103.8],
               //     name: "Singapore"
               // }],

               onRegionTipShow: function(e, el, code){
             		if (code == 'KR-11'){
                  el.html(el.html()+
                  "<br><b class='text-primary'>최근:</b>(강북: "+numberWithCommas(jsonData.values_base[code][0][netIndex_no[0]])+" 강남:"+numberWithCommas(jsonData.values_base[code][1][netIndex_no[0]])+")"+
                  "<br><b class='text-warning'>"+avg_string+":</b>(강북: "+numberWithCommas(jsonData.values_base[code][0][netIndex_no[1]])+" 강남:"+numberWithCommas(jsonData.values_base[code][1][netIndex_no[1]])+")"+
                  "<br><b class='text-success'>증감:</b>(강북:"+jsonData.values_base[code][0][netIndex_no[2]]+"% 강남:"+jsonData.values_base[code][1][netIndex_no[2]]+"%)");
             		}else{
             			// for Russia show redeclarated values
                  el.html(el.html()+
                  "<br><b class='text-primary'>최근:</b>("+numberWithCommas(jsonData.values_base[code][netIndex_no[0]])+")"+
                  "<br><b class='text-warning'>"+avg_string+":</b>("+numberWithCommas(jsonData.values_base[code][netIndex_no[1]])+")"+
                  "<br><b class='text-success'>증감:</b>("+numberWithCommas(jsonData.values_base[code][netIndex_no[2]])+"%)");
                }
              },
               hoverOpacity: 0.5,
               hoverColor: false,
               selectedColor: '#c9dfaf',
               selectedRegions: [],
               showTooltip: true,
               // onRegionClick: function(element, code, region) {
               //     var message = 'You clicked "' + region + '" which has the code: ' + code.toUpperCase();
               //     alert(message);
               // }
           });
}

function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}
