// success: function(data) {
// 	$.each(data.arrjson, function(index, arrjson) {
// 		$('#tabList').append("<tr><td>" + arrjson.no + "</td><td>" + arrjson.name + "</td></tr>");
// 	});

var font_size = 11;
var colors = ["89,105,255", "255,64,123", "46,197,81", "255,199,80","124,252,000","238,232,170","205,133,63","240,230,140",
              "230,230,250","106,90,205","1,191,255","25,25,112","64,224,208","220,20,60"];

(function(window, document, $, undefined) {

        // 선택된 모델
        var ele_1 = $('#selectModel_5G');
        // 네트워크 종류(5G/LTE)
        var ele_2 = $('#netType');
        // 선택한 카테고리
        var ele_3 = $('#selectCategory');
        // 시작일자
        var ele_4 = $('#startDate');
        // 종료일자
        var ele_5 = $('#endDate');
        //모델 타입(Origin/Mapping)
        var ele_6 = $('#modelType');

        var models = ele_1.val();
        var type = ele_2.val();
        var select_class = ele_3.val();
        var startDate = ele_4.val();
        var endDate = ele_5.val();
        var modelType = ele_6.val();
        var item_size = 0;

        if ($('#chartjs_line1').length) {
              var jsonData = null;
              var ctx = document.getElementById("chartjs_line1").getContext('2d');
              $.ajax({
                                          url:"/voc/chart/phpdata/view/view_category.php",
                                          type:"post",
                                          dataType:"json",
                                          data:{"startDate":startDate, "endDate":endDate, "models":models, "type":type, "modelType":modelType, "selectClass":select_class},
                                          async: false,
                                          success: function (data) {
                                            console.log(data);
                                            jsonData = data;
                                          },
                                          error: function (request, status, error) {
                                            console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
                                          }
                                        });
                //item_size
                item_size = jsonData.values.length
                //dataset array
                var dataset = new Array();

                //each chart initiation
                if(window.chart != undefined){
                    window.chart.destroy();
                }

                //dataset_1 Main chart generatge
                var i = 0;
                while(i< item_size){
                  var object = {
                    type:'line',
                    label: jsonData.models[i],
                    fill:true,
                    lineTension: 0,
                    data: jsonData.values[i],
                    backgroundColor: "rgba("+colors[i]+",0.5)",
                    borderColor: "rgba("+colors[i]+",0.9)",
                    borderWidth: 2
                  };
                  dataset.push(object);
                  i++;
                }
                window.chart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        // labels: jsonData.indexUpDown,
                        labels: jsonData.index,
                        datasets: dataset
                    },
                    options: {
                      // tooltips: {
                      //   callbacks: {
                      //     title: function(tooltipItem, data) {
                      //       return data['labels'][tooltipItem[0]['index']];
                      //     },
                      //     label: function(tooltipItem, data) {
                      //       return data['datasets'][0]['data'][tooltipItem['index']];
                      //     },
                      //     afterLabel: function(tooltipItem, data) {
                      //       var dataset = data['datasets'][0];
                      //       var percent = Math.round((dataset['data'][tooltipItem['index']] / dataset["_meta"][0]['total']) * 100)
                      //       return '(' + percent + '%)';
                      //     }
                      //   },
                        responsive: true,

                        legend: {
                          display: true,
                          position: 'bottom',

                          labels: {
                              fontColor: '#71748d',
                              fontFamily: 'Circular Std Book',
                              fontSize: font_size
                          }
                        },

                        elements: {
                          point: {
                            pointStyle: "circle",
                            backgroundColor : "rgba(255,255,225,0.9)",
                            hoverRadius: 5,
                            borderWidth: 8
                          }
                        },

                        scales: {
                            xAxes: [{
                                ticks: {
                                    autoSkip: true,
                                    maxTicksLimit: 20,
                                    fontSize: font_size,
                                    fontFamily: 'Circular Std Book',
                                    fontColor: '#71748d'
                                }
                            }],
                            yAxes: [{
                                ticks: {
                                    // beginAtZero:true,
                                    fontSize: font_size,
                                    fontFamily: 'Circular Std Book',
                                    fontColor: '#71748d'
                                    // userCallback:function(value, index, values){
                                    //   value = value.toString();
                                    //   value = value.split(/(?=(?:...)*$)/);
                                    //   value = value.join(',');
                                    //   return value;
                                    // }
                                }
                            }]
                        }
                    }
                });
          }
})(window, document, window.jQuery);

function update(){

  // 선택된 모델
  var ele_1 = null;
  // 네트워크 종류(5G/LTE)
  var ele_2 = $('#netType');
  // 선택한 카테고리
  var ele_3 = $('#selectCategory');
  // 시작일자
  var ele_4 = $('#startDate');
  // 종료일자
  var ele_5 = $('#endDate');
  //모델 타입(Origin/Mapping)
  var ele_6 = $('#modelType');

  var models = null;
  var type = ele_2.val();
  var select_class = ele_3.val();
  var startDate = ele_4.val();
  var endDate = ele_5.val();
  var modelType = ele_6.val();

  //netType check
  if (type == "5G"){
    ele_1 = $("#selectModel_5G");
    models = ele_1.val();
  }else{
    ele_1 = $("#selectModel_LTE");
    models = ele_1.val();
  }

  if(models.length == 0){
    alert("특정모델 혹은 전체를 선택해주세요.");
    return null;
   }

  if ($('#chartjs_line1').length){
    var jsonData = null;
    var ctx = document.getElementById("chartjs_line1").getContext('2d');
    $.ajax({
                                   url:"/voc/chart/phpdata/view/view_category.php",
                                   type:"post",
                                   dataType:"json",
                                   data:{"startDate":startDate, "endDate":endDate, "models":models, "type":type, "modelType":modelType, "selectClass":select_class},
                                   async: false,
                                   success: function (data) {
                                     console.log(data);
                                     jsonData = data;
                                   },
                                   error: function (request, status, error) {
                                     console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
                                   }
                                 });

         //item_size
         item_size = jsonData.values.length
         //dataset array
         var dataset = new Array();

         //each chart initiation
         if(window.chart != undefined){
             window.chart.destroy();
         }

         //dataset_1 Main chart generatge
         var i = 0;
         while(i< item_size){
           var object = {
             type:'line',
             label: jsonData.models[i],
             fill:true,
             lineTension: 0,
             data: jsonData.values[i],
             backgroundColor: "rgba("+colors[i]+",0.5)",
             borderColor: "rgba("+colors[i]+",0.9)",
             borderWidth: 2
           };
           dataset.push(object);
           i++;
         }

         window.chart = new Chart(ctx, {
             type: 'line',
             data: {
                 // labels: jsonData.indexUpDown,
                 labels: jsonData.index,
                 datasets: dataset
             },
             options: {
                 responsive: true,
                 scales: {
                     yAxes: [{

                     }]
                 },
                 legend: {
                   display: true,
                   position: 'bottom',

                   labels: {
                       fontColor: '#71748d',
                       fontFamily: 'Circular Std Book',
                       fontSize: font_size
                   }
                 },

                 elements: {
                   point: {
                     pointStyle: "circle",
                     backgroundColor : "rgba(255,255,225,0.9)",
                     hoverRadius: 5,
                     borderWidth: 8
                   }
                 },

                 scales: {
                     xAxes: [{
                         ticks: {
                             autoSkip: true,
                             maxTicksLimit: 20,
                             fontSize: font_size,
                             fontFamily: 'Circular Std Book',
                             fontColor: '#71748d'
                         }
                     }],
                     yAxes: [{
                         ticks: {
                             fontSize: font_size,
                             fontFamily: 'Circular Std Book',
                             fontColor: '#71748d'
                         }
                     }]
                 }
              }
          });
      }
}

function changeSelect(){

  // 선택된 모델
  var ele_1 = $('#select1');
  // 네트워크 종류(5G/LTE)
  var ele_2 = $('#select2');
  // 네트워크 종류(5G/LTE)
  var ele_3 = $('#netType');

  if(ele_3.val() == "5G"){
    ele_1.show();
    ele_2.hide();
  }else{
    ele_1.hide();
    ele_2.show();
  }
}

function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}
