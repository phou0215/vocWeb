var font_size = 11;
var colors = ["89,105,255", "255,64,123", "46,197,81", "255,199,80","124,252,000","238,232,170","205,133,63","240,230,140","230,230,250","106,90,205","1,191,255","25,25,112","64,224,208","220,20,60"];

(function(window, document, $, undefined) {

        var ele_1 = $('#selectManu');
        var ele_2 = $('#selectType');
        var ele_3 = $('#startDate');
        var ele_4 = $('#endDate');
        var ele_5 = $('#selectKey');
        var ele_6 = $('#key1');
        var ele_7 = $('#key2');
        var ele_8 = $('#key3');

        var manus = ele_1.val();
        var keys = ele_5.val();
        var type = ele_2.val();
        var startDate = ele_3.val();
        var endDate = ele_4.val();
        var keyword_1 = ele_6.val();
        var keyword_2 = ele_7.val();
        var keyword_3 = ele_8.val();

        $(function() {

            if ($('#chartjs_line1').length) {
              var ctx_1 = document.getElementById("chartjs_line1").getContext('2d');
              var ctx_2 = document.getElementById("chartjs_bar1").getContext('2d');
              var ctx_3 = document.getElementById("chartjs_bar2").getContext('2d');
              var ctx_4 = document.getElementById("chartjs_bar3").getContext('2d');
              var ctx_5 = document.getElementById("chartjs_bar4").getContext('2d');
              var jsonData = null;
              $.ajax({
                                          url:"/voc/chart/phpdata/manu/jsonData_manu_total.php",
                                          type:"post",
                                          dataType:"json",
                                          data:{"startDate":startDate, "endDate":endDate, "manus":manus, "type":type, "keys":keys, "keyword_1":keyword_1, "keyword_2":keyword_2, "keyword_3":keyword_3},
                                          async: false,
                                          success: function (data) {
                                            // console.log(data);
                                            jsonData = data;
                                          },
                                          error: function (request, status, error) {
                                            console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
                                          }
                                        });
                //dataset array
                var dataset_1 = new Array();
                var dataset_2 = new Array();
                var dataset_3 = new Array();
                var dataset_4 = new Array();
                var dataset_5 = new Array();

                //each chart initiation
                if(window.chart_1 != undefined){
                    window.chart_1.destroy();
                }
                if(window.chart_2 != undefined){
                    window.chart_2.destroy();
                }
                if(window.chart_3 != undefined){
                    window.chart_3.destroy();
                }
                if(window.chart_4 != undefined){
                    window.chart_4.destroy();
                }
                if(window.chart_5 != undefined){
                    window.chart_5.destroy();
                }

                //dataset_1 Main line chart generatge
                var i = 0;
                while(i< jsonData.values.length){
                  var object = {
                    label: jsonData.index_manu[i],
                    // fill:true,
                    // lineTension:0,
                    data: jsonData.values[i][0],
                    backgroundColor: "rgba("+colors[i]+",0.5)",
                    // borderColor: "rgba("+colors[i]+",0.7)",
                    // borderWidth: 2
                  };
                  dataset_1.push(object);
                  i++;
                }

                //dataset_2 stacked bar chart generatge
                var i = 0;
                while(i < jsonData.values_class.length){
                  var object = {
                    label: jsonData.index_manu[i],
                    data: jsonData.values_class[i],
                    backgroundColor: "rgba("+colors[i]+",0.5)",
                    // borderColor: "rgba("+colors[i]+",0.7)",
                    // borderWidth: 2
                  };
                  dataset_2.push(object);
                  i++;
                }

                //dataset_3 stacked bar chart generatge
                var i = 0;
                while(i < jsonData.values_manage.length){
                  var object = {
                    label: jsonData.index_manu[i],
                    data: jsonData.values_manage[i],
                    backgroundColor: "rgba("+colors[i]+",0.5)",
                    borderColor: "rgba("+colors[i]+",0.7)",
                    borderWidth: 2,
                    fill:true
                  };
                  dataset_3.push(object);
                  i++;
                }

                //dataset_4 stacked bar chart generatge
                var i = 0;
                while(i < jsonData.values_network.length){
                  var object = {
                    label: jsonData.index_manu[i],
                    data: jsonData.values_network[i],
                    backgroundColor: "rgba("+colors[i]+",0.5)",
                    borderColor: "rgba("+colors[i]+",0.7)",
                    borderWidth: 2
                  };
                  dataset_4.push(object);
                  i++;
                }

                //dataset_5 Main chart generatge
                var i = 0;
                while(i < jsonData.values_pearson.length){
                  var object = {
                    label: jsonData.index_manu[i],
                    data: jsonData.values_pearson[i],
                    backgroundColor: "rgba("+colors[i]+",0.5)",
                    // borderColor: "rgba("+colors[i]+",0.7)",
                    // borderWidth: 2
                  };
                  dataset_5.push(object);
                  i++;
                }


                window.chart_1 = new Chart(ctx_1, {
                    type: 'bar',
                    data: {
                        labels: jsonData.index,
                        datasets: dataset_1
                    },
                    options: {
                        responsive: true,

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
                                // type:'time',
                                ticks: {
                                    autoSkip: true,
                                    maxTicksLimit: 20,
                                    fontSize: font_size,
                                    fontFamily: 'Circular Std Book',
                                    fontColor: '#71748d',
                                }
                            }],
                            yAxes: [{
                                ticks: {
                                    fontSize: font_size,
                                    fontFamily: 'Circular Std Book',
                                    fontColor: '#71748d',
                                }
                            }]
                        },
                        legend: {
                          display: true,
                          position: 'bottom',

                          labels: {
                              fontColor: '#71748d',
                              fontFamily: 'Circular Std Book',
                              fontSize: font_size,
                          }
                    },
                }

                });

                window.chart_2 = new Chart(ctx_2, {
                    type: 'bar',
                    data: {
                        labels: jsonData.index_class,
                        datasets: dataset_2
                    },
                    options: {
                        responsive: true,
                        scales: {
                            xAxes: [{
                                // stacked: true,
                                ticks: {
                                    autoSkip: true,
                                    maxTicksLimit: 20,
                                    fontSize: font_size,
                                    fontFamily: 'Circular Std Book',
                                    fontColor: '#71748d',
                                }
                            }],
                            yAxes: [{
                                // stacked: true,
                                ticks: {
                                    beginAtZero: true,
                                    fontSize: font_size,
                                    fontFamily: 'Circular Std Book',
                                    fontColor: '#71748d',
                                }
                            }]
                        },
                        legend: {
                          display: true,
                          position: 'bottom',

                          labels: {
                              fontColor: '#71748d',
                              fontFamily: 'Circular Std Book',
                              fontSize: font_size,
                          }
                    },
                }

                });

                window.chart_3 = new Chart(ctx_3, {
                    type: 'radar',
                    data: {
                        labels: jsonData.index_manage,
                        datasets: dataset_3
                    },
                    options: {
                        responsive: true,
                        scales: {
                            ticks:{
                              beginAtZero: true
                            }
                            // xAxes: [{
                            //     stacked: true,
                            //     ticks: {
                            //         autoSkip: true,
                            //         maxTicksLimit: 20,
                            //         fontSize: font_size,
                            //         fontFamily: 'Circular Std Book',
                            //         fontColor: '#71748d',
                            //     }
                            // }],
                            // yAxes: [{
                            //     stacked: true,
                            //     ticks: {
                            //         beginAtZero: true,
                            //         fontSize: font_size,
                            //         fontFamily: 'Circular Std Book',
                            //         fontColor: '#71748d',
                            //     }
                            // }]
                        },
                        legend: {
                          display: true,
                          position: 'bottom',

                          labels: {
                              fontColor: '#71748d',
                              fontFamily: 'Circular Std Book',
                              fontSize: font_size,
                          }
                    },
                }

                });

                window.chart_4 = new Chart(ctx_4, {
                    type: 'radar',
                    data: {
                        labels: jsonData.index_network,
                        datasets: dataset_4
                    },
                    options: {
                        responsive: true,
                        scales: {
                            ticks:{
                              beginAtZero: true
                            }
                            // xAxes: [{
                            //     stacked: true,
                            //     ticks: {
                            //         autoSkip: true,
                            //         maxTicksLimit: 20,
                            //         fontSize: font_size,
                            //         fontFamily: 'Circular Std Book',
                            //         fontColor: '#71748d',
                            //     }
                            // }],
                            // yAxes: [{
                            //     stacked: true,
                            //     ticks: {
                            //         beginAtZero: true,
                            //         fontSize: font_size,
                            //         fontFamily: 'Circular Std Book',
                            //         fontColor: '#71748d',
                            //     }
                            // }]
                        },
                        legend: {
                          display: true,
                          position: 'bottom',

                          labels: {
                              fontColor: '#71748d',
                              fontFamily: 'Circular Std Book',
                              fontSize: font_size,
                          }
                    },
                }

                });

                window.chart_5 = new Chart(ctx_5, {
                    type: 'bar',
                    data: {
                        labels: jsonData.index_class,
                        datasets: dataset_5
                    },
                    options: {
                      tooltips: {
                          callbacks: {
                              label: function(tooltipItem, data){
                                var dsLabel = data.datasets[tooltipItem.datasetIndex].label;
                                var y_value = tooltipItem.yLabel;
                                var pearson_data = parseFloat(y_value);
                                if(0.7 < pearson_data && pearson_data <= 1){
                                  return dsLabel + ': ' + y_value+" 메우강함(+)";
                                  // return data['datasets'][0]['data'][tooltipItem['index']]+" : 메우강함(+)";
                                }else if(0.3 < pearson_data && pearson_data <= 0.7){
                                  return dsLabel + ': ' + y_value+" 강함(+)";
                                  // return data['datasets'][0]['data'][tooltipItem['index']]+" : 강함(+)";
                                }else if(0.1 < pearson_data && pearson_data <= 0.3){
                                  return dsLabel + ': ' + y_value+" 약함(+)";
                                  // return data['datasets'][0]['data'][tooltipItem['index']]+" : 약함(+)";
                                }else if(-0.1 < pearson_data && pearson_data <= 0.1){
                                  return dsLabel + ': ' + y_value+" 관계없음";
                                  // return data['datasets'][0]['data'][tooltipItem['index']]+" : 관계없음";
                                }else if(-0.3 < pearson_data && pearson_data <= -0.1){
                                  return dsLabel + ': ' + y_value+" 약함(-)";
                                  // return data['datasets'][0]['data'][tooltipItem['index']]+" : 약함(-)";
                                }else if(-0.7 < pearson_data && pearson_data <= -0.3){
                                  return dsLabel + ': ' + y_value+" 강함(-)";
                                  // return data['datasets'][0]['data'][tooltipItem['index']]+" : 강함(-)";
                                }else{
                                  return dsLabel + ': ' + y_value+" 메우강함(-)";
                                  // return data['datasets'][0]['data'][tooltipItem['index']]+" : 매우강함(-)";
                                }
                              }
                          }
                      },
                        // title: {
                        //     display: true,
                        //     text: '0.7 < R: 강, 0.3 <= R <= 0.7 : 중, 0.3 < R : 하'
                        // },
                        responsive: true,
                        scales: {
                            xAxes: [{
                                stacked: false,
                                ticks: {
                                    autoSkip: false,
                                    maxTicksLimit: 20,
                                    fontSize: font_size,
                                    fontFamily: 'Circular Std Book',
                                    fontColor: '#71748d',
                                }
                            }],
                            yAxes: [{
                                stacked: false,
                                ticks: {
                                    // beginAtZero: true,
                                    autoSkip: false,
                                    min: -1,
                                    max: 1,
                                    fontSize: font_size,
                                    fontFamily: 'Circular Std Book',
                                    fontColor: '#71748d',
                                }
                            }]
                        },
                        legend: {
                          display: true,
                          position: 'bottom',

                          labels: {
                              fontColor: '#71748d',
                              fontFamily: 'Circular Std Book',
                              fontSize: font_size,
                          }
                    },
                }
                });
            }
        });
})(window, document, window.jQuery);

function checkKeys(){
  setTimeout(function(){
    var ele = $('[data-id="selectKey"]');
    var selectBox = $('#selectKey');
    var title = ele.attr("title");
    var title_array = title.split(",");
    var display = $('[data-id="selectKey"] .filter-option-inner-inner');
    var list_eles= $('.choice_li');
    var options = $('[data-id="selectKey"] + .dropdown-menu li[class="selected"]');
    var roles = $('[data-id="selectKey"] + .dropdown-menu li[class="selected"] a');
    var count = options.length;
    var ele_6 = $('#key1');
    var ele_7 = $('#key2');
    var ele_8 = $('#key3');

    if(count == 0){
      list_eles.each(function(index, item){
        $(this).attr("hidden","hidden");
        ele_6.val("");
        ele_7.val("");
        ele_8.val("");
      });
    }
    else if(count  > 3){
      alert("키워드 조회에서 3개 이상 선택 불가합니다.");
        ele_6.val("");
        ele_7.val("");
        ele_8.val("");
        options.each(function(index, item){
          $(this).removeAttr("class");
        });

        roles.each(function(index, item){
          $(this).attr("class","dropdown-item");
          $(this).attr("aria-selected","false");
        });

        list_eles.each(function(index, item){
          $(this).attr("hidden","hidden");
        });

        ele.attr("title", "Nothing selected");
        display.text("Nothing selected");
        selectBox.val("");
    }else{
      list_eles.each(function(index, item){
        if(index > count-1){
          $(this).attr("hidden","hidden");
          $('#key'+String(index+1)).val("");
        }else{
          $(this).removeAttr("hidden");
        }
      });
    }
  }, 300);
}

function update(){

   var ele_1 = $('#selectManu');
   var ele_2 = $('#selectType');
   var ele_3 = $('#startDate');
   var ele_4 = $('#endDate');
   var ele_5 = $('#selectKey');
   var ele_6 = $('#key1');
   var ele_7 = $('#key2');
   var ele_8 = $('#key3');
   var manus = ele_1.val();
   var keys = ele_5.val();
   var type = ele_2.val();
   var startDate = ele_3.val();
   var endDate = ele_4.val();
   var keyword_1 = ele_6.val();
   var keyword_2 = ele_7.val();
   var keyword_3 = ele_8.val();

   if(manus.length == 0){
     alert("특정제조사 혹은 전체를 선택해주세요.");
     return null;
   }

   if(keys.length > 0){
     if(keys.length == 1 && keyword_1 == ""){
       alert("키워드를 정확히 입력하여 주세요.");
       return null;
     }else if(keys.length == 2 && (keyword_1 == "" || keyword_2 == "")){
       alert("키워드를 정확히 입력하여 주세요.");
       return null;
     }else if(keys.length == 3 && (keyword_1 == "" || keyword_2 == "" || keyword_3 == "")){
       alert("키워드를 정확히 입력하여 주세요.");
       return null;
     }
   }

   if ($('#chartjs_line1').length) {
     var ctx_1 = document.getElementById("chartjs_line1").getContext('2d');
     var ctx_2 = document.getElementById("chartjs_bar1").getContext('2d');
     var ctx_3 = document.getElementById("chartjs_bar2").getContext('2d');
     var ctx_4 = document.getElementById("chartjs_bar3").getContext('2d');
     var ctx_5 = document.getElementById("chartjs_bar4").getContext('2d');
     var jsonData = null;
     $.ajax({
                                 url:"/voc/chart/phpdata/manu/jsonData_manu_total.php",
                                 type:"post",
                                 dataType:"json",
                                 data:{"startDate":startDate, "endDate":endDate, "manus":manus, "type":type, "keys":keys, "keyword_1":keyword_1, "keyword_2":keyword_2, "keyword_3":keyword_3},
                                 async: false,
                                 success: function (data) {
                                   // console.log(data);
                                   jsonData = data;
                                 },
                                 error: function (request, status, error) {
                                   console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
                                 }
                               });
       //dataset array
       var dataset_1 = new Array();
       var dataset_2 = new Array();
       var dataset_3 = new Array();
       var dataset_4 = new Array();
       var dataset_5 = new Array();

       //each chart initiation
       if(window.chart_1 != undefined){
           window.chart_1.destroy();
       }
       if(window.chart_2 != undefined){
           window.chart_2.destroy();
       }
       if(window.chart_3 != undefined){
           window.chart_3.destroy();
       }
       if(window.chart_4 != undefined){
           window.chart_4.destroy();
       }
       if(window.chart_5 != undefined){
           window.chart_5.destroy();
       }

       //dataset_1 Main line chart generatge
       var i = 0;
       while(i< jsonData.values.length){
         var object = {
           label: jsonData.index_manu[i],
           // fill:true,
           // lineTension:0,
           data: jsonData.values[i][0],
           backgroundColor: "rgba("+colors[i]+",0.5)",
           // borderColor: "rgba("+colors[i]+",0.7)",
           // borderWidth: 2
         };
         dataset_1.push(object);
         i++;
       }

       //dataset_2 stacked bar chart generatge
       var i = 0;
       while(i < jsonData.values_class.length){
         var object = {
           label: jsonData.index_manu[i],
           data: jsonData.values_class[i],
           backgroundColor: "rgba("+colors[i]+",0.5)",
           // borderColor: "rgba("+colors[i]+",0.7)",
           // borderWidth: 2
         };
         dataset_2.push(object);
         i++;
       }

       //dataset_3 stacked bar chart generatge
       var i = 0;
       while(i < jsonData.values_manage.length){
         var object = {
           label: jsonData.index_manu[i],
           data: jsonData.values_manage[i],
           backgroundColor: "rgba("+colors[i]+",0.5)",
           borderColor: "rgba("+colors[i]+",0.7)",
           borderWidth: 2,
           fill:true
         };
         dataset_3.push(object);
         i++;
       }

       //dataset_4 stacked bar chart generatge
       var i = 0;
       while(i < jsonData.values_network.length){
         var object = {
           label: jsonData.index_manu[i],
           data: jsonData.values_network[i],
           backgroundColor: "rgba("+colors[i]+",0.5)",
           borderColor: "rgba("+colors[i]+",0.7)",
           borderWidth: 2
         };
         dataset_4.push(object);
         i++;
       }

       //dataset_5 Main chart generatge
       var i = 0;
       while(i < jsonData.values_pearson.length){
         var object = {
           label: jsonData.index_manu[i],
           data: jsonData.values_pearson[i],
           backgroundColor: "rgba("+colors[i]+",0.5)",
           // borderColor: "rgba("+colors[i]+",0.7)",
           // borderWidth: 2
         };
         dataset_5.push(object);
         i++;
       }


       window.chart_1 = new Chart(ctx_1, {
           type: 'bar',
           data: {
               labels: jsonData.index,
               datasets: dataset_1
           },
           options: {
               responsive: true,

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
                       // type:'time',
                       ticks: {
                           autoSkip: true,
                           maxTicksLimit: 20,
                           fontSize: font_size,
                           fontFamily: 'Circular Std Book',
                           fontColor: '#71748d',
                       }
                   }],
                   yAxes: [{
                       ticks: {
                           fontSize: font_size,
                           fontFamily: 'Circular Std Book',
                           fontColor: '#71748d',
                       }
                   }]
               },
               legend: {
                 display: true,
                 position: 'bottom',

                 labels: {
                     fontColor: '#71748d',
                     fontFamily: 'Circular Std Book',
                     fontSize: font_size,
                 }
           },
       }

       });

       window.chart_2 = new Chart(ctx_2, {
           type: 'bar',
           data: {
               labels: jsonData.index_class,
               datasets: dataset_2
           },
           options: {
               responsive: true,
               scales: {
                   xAxes: [{
                       // stacked: true,
                       ticks: {
                           autoSkip: true,
                           maxTicksLimit: 20,
                           fontSize: font_size,
                           fontFamily: 'Circular Std Book',
                           fontColor: '#71748d',
                       }
                   }],
                   yAxes: [{
                       // stacked: true,
                       ticks: {
                           beginAtZero: true,
                           fontSize: font_size,
                           fontFamily: 'Circular Std Book',
                           fontColor: '#71748d',
                       }
                   }]
               },
               legend: {
                 display: true,
                 position: 'bottom',

                 labels: {
                     fontColor: '#71748d',
                     fontFamily: 'Circular Std Book',
                     fontSize: font_size,
                 }
           },
       }

       });

       window.chart_3 = new Chart(ctx_3, {
           type: 'radar',
           data: {
               labels: jsonData.index_manage,
               datasets: dataset_3
           },
           options: {
               responsive: true,
               scales: {
                   ticks:{
                     beginAtZero: true
                   }
                   // xAxes: [{
                   //     stacked: true,
                   //     ticks: {
                   //         autoSkip: true,
                   //         maxTicksLimit: 20,
                   //         fontSize: font_size,
                   //         fontFamily: 'Circular Std Book',
                   //         fontColor: '#71748d',
                   //     }
                   // }],
                   // yAxes: [{
                   //     stacked: true,
                   //     ticks: {
                   //         beginAtZero: true,
                   //         fontSize: font_size,
                   //         fontFamily: 'Circular Std Book',
                   //         fontColor: '#71748d',
                   //     }
                   // }]
               },
               legend: {
                 display: true,
                 position: 'bottom',

                 labels: {
                     fontColor: '#71748d',
                     fontFamily: 'Circular Std Book',
                     fontSize: font_size,
                 }
           },
       }

       });

       window.chart_4 = new Chart(ctx_4, {
           type: 'radar',
           data: {
               labels: jsonData.index_network,
               datasets: dataset_4
           },
           options: {
               responsive: true,
               scales: {
                   ticks:{
                     beginAtZero: true
                   }
                   // xAxes: [{
                   //     stacked: true,
                   //     ticks: {
                   //         autoSkip: true,
                   //         maxTicksLimit: 20,
                   //         fontSize: font_size,
                   //         fontFamily: 'Circular Std Book',
                   //         fontColor: '#71748d',
                   //     }
                   // }],
                   // yAxes: [{
                   //     stacked: true,
                   //     ticks: {
                   //         beginAtZero: true,
                   //         fontSize: font_size,
                   //         fontFamily: 'Circular Std Book',
                   //         fontColor: '#71748d',
                   //     }
                   // }]
               },
               legend: {
                 display: true,
                 position: 'bottom',

                 labels: {
                     fontColor: '#71748d',
                     fontFamily: 'Circular Std Book',
                     fontSize: font_size,
                 }
           },
       }

       });

       window.chart_5 = new Chart(ctx_5, {
           type: 'bar',
           data: {
               labels: jsonData.index_class,
               datasets: dataset_5
           },
           options: {
             tooltips: {
                 callbacks: {
                     label: function(tooltipItem, data){
                       var dsLabel = data.datasets[tooltipItem.datasetIndex].label;
                       var y_value = tooltipItem.yLabel;
                       var pearson_data = parseFloat(y_value);
                       if(0.7 < pearson_data && pearson_data <= 1){
                         return dsLabel + ': ' + y_value+" 메우강함(+)";
                         // return data['datasets'][0]['data'][tooltipItem['index']]+" : 메우강함(+)";
                       }else if(0.3 < pearson_data && pearson_data <= 0.7){
                         return dsLabel + ': ' + y_value+" 강함(+)";
                         // return data['datasets'][0]['data'][tooltipItem['index']]+" : 강함(+)";
                       }else if(0.1 < pearson_data && pearson_data <= 0.3){
                         return dsLabel + ': ' + y_value+" 약함(+)";
                         // return data['datasets'][0]['data'][tooltipItem['index']]+" : 약함(+)";
                       }else if(-0.1 < pearson_data && pearson_data <= 0.1){
                         return dsLabel + ': ' + y_value+" 관계없음";
                         // return data['datasets'][0]['data'][tooltipItem['index']]+" : 관계없음";
                       }else if(-0.3 < pearson_data && pearson_data <= -0.1){
                         return dsLabel + ': ' + y_value+" 약함(-)";
                         // return data['datasets'][0]['data'][tooltipItem['index']]+" : 약함(-)";
                       }else if(-0.7 < pearson_data && pearson_data <= -0.3){
                         return dsLabel + ': ' + y_value+" 강함(-)";
                         // return data['datasets'][0]['data'][tooltipItem['index']]+" : 강함(-)";
                       }else{
                         return dsLabel + ': ' + y_value+" 메우강함(-)";
                         // return data['datasets'][0]['data'][tooltipItem['index']]+" : 매우강함(-)";
                       }
                     }
                 }
             },
               // title: {
               //     display: true,
               //     text: '0.7 < R: 강, 0.3 <= R <= 0.7 : 중, 0.3 < R : 하'
               // },
               responsive: true,
               scales: {
                   xAxes: [{
                       stacked: false,
                       ticks: {
                           autoSkip: false,
                           maxTicksLimit: 20,
                           fontSize: font_size,
                           fontFamily: 'Circular Std Book',
                           fontColor: '#71748d',
                       }
                   }],
                   yAxes: [{
                       stacked: false,
                       ticks: {
                           // beginAtZero: true,
                           autoSkip: false,
                           min: -1,
                           max: 1,
                           fontSize: font_size,
                           fontFamily: 'Circular Std Book',
                           fontColor: '#71748d',
                       }
                   }]
               },
               legend: {
                 display: true,
                 position: 'bottom',

                 labels: {
                     fontColor: '#71748d',
                     fontFamily: 'Circular Std Book',
                     fontSize: font_size,
                 }
           },
       }
       });
   }
}
