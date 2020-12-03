var font_size = 11;
var colors = ["89,105,255", "255,64,123", "46,197,81", "255,199,80","124,252,000","238,232,170","205,133,63","240,230,140","230,230,250","106,90,205","1,191,255","25,25,112","64,224,208","220,20,60"];


(function(window, document, $, undefined) {

        var ele_1 = $('#selectModel');
        var ele_2 = $('#selectType');
        var ele_3 = $('#startDate');
        var ele_4 = $('#endDate');
        var ele_5 = $('#selectKey');
        var ele_6 = $('#key1');
        var ele_7 = $('#key2');
        var ele_8 = $('#key3');

        var models = ele_1.val();
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
              var jsonData = null;
              $.ajax({
                                          url:"/voc/chart/phpdata/model/jsonData_subs_total.php",
                                          type:"post",
                                          dataType:"json",
                                          data:{"startDate":startDate, "endDate":endDate, "models":models, "type":type, "keys":keys, "keyword_1":keyword_1, "keyword_2":keyword_2, "keyword_3":keyword_3},
                                          async: false,
                                          success: function (data) {
                                            console.log(data);
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

                //dataset_1 Main chart generatge
                var i = 0;
                while(i< jsonData.values.length){
                  var object = {
                    label: jsonData.models[i],
                    fill:false,
                    lineTension: 0,
                    data: jsonData.values[i],
                    // backgroundColor: "rgba("+colors[i]+",0.5)",
                    borderColor: "rgba("+colors[i]+",0.9)",
                    borderWidth: 2
                  };
                  dataset_1.push(object);
                  i++;
                }

                //dataset_2 Main chart generatge
                var i = 0;
                while(i < jsonData.values_class.length){
                  var object = {
                    label: jsonData.index_class[i],
                    data: jsonData.values_class[i],
                    backgroundColor: "rgba("+colors[i]+",0.9)",
                    borderColor: "rgba("+colors[i]+",0.7)",
                    borderWidth: 2
                  };
                  dataset_2.push(object);
                  i++;
                }

                //dataset_3 Main chart generatge
                var i = 0;
                while(i < jsonData.values_manage.length){
                  var object = {
                    label: jsonData.index_manage[i],
                    data: jsonData.values_manage[i],
                    backgroundColor: "rgba("+colors[i]+",0.9)",
                    borderColor: "rgba("+colors[i]+",0.7)",
                    borderWidth: 2
                  };
                  dataset_3.push(object);
                  i++;
                }

                //dataset_4 Main chart generatge
                var i = 0;
                while(i < jsonData.values_network.length){
                  var object = {
                    label: jsonData.index_network[i],
                    data: jsonData.values_network[i],
                    backgroundColor: "rgba("+colors[i]+",0.9)",
                    borderColor: "rgba("+colors[i]+",0.7)",
                    borderWidth: 2
                  };
                  dataset_4.push(object);
                  i++;
                }

                window.chart_1 = new Chart(ctx_1, {
                    type: 'line',
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
                        labels: jsonData.index,
                        datasets: dataset_2
                    },
                    options: {
                        responsive: true,
                        scales: {
                            xAxes: [{
                                stacked: true,
                                ticks: {
                                    autoSkip: true,
                                    maxTicksLimit: 20,
                                    fontSize: font_size,
                                    fontFamily: 'Circular Std Book',
                                    fontColor: '#71748d',
                                }
                            }],
                            yAxes: [{
                                stacked: true,
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
                    type: 'bar',
                    data: {
                        labels: jsonData.models,
                        datasets: dataset_3
                    },
                    options: {
                        responsive: true,
                        scales: {
                            xAxes: [{
                                stacked: true,
                                ticks: {
                                    autoSkip: true,
                                    maxTicksLimit: 20,
                                    fontSize: font_size,
                                    fontFamily: 'Circular Std Book',
                                    fontColor: '#71748d',
                                }
                            }],
                            yAxes: [{
                                stacked: true,
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

                window.chart_4 = new Chart(ctx_4, {
                    type: 'bar',
                    data: {
                        labels: jsonData.models,
                        datasets: dataset_4
                    },
                    options: {
                        responsive: true,
                        scales: {
                            xAxes: [{
                                stacked: true,
                                ticks: {
                                    autoSkip: true,
                                    maxTicksLimit: 20,
                                    fontSize: font_size,
                                    fontFamily: 'Circular Std Book',
                                    fontColor: '#71748d',
                                }
                            }],
                            yAxes: [{
                                stacked: true,
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

   var ele_1 = $('#selectModel');
   var ele_2 = $('#selectType');
   var ele_3 = $('#startDate');
   var ele_4 = $('#endDate');
   var ele_5 = $('#selectKey');
   var ele_6 = $('#key1');
   var ele_7 = $('#key2');
   var ele_8 = $('#key3');
   var models = ele_1.val();
   var keys = ele_5.val();
   var type = ele_2.val();
   var startDate = ele_3.val();
   var endDate = ele_4.val();
   var keyword_1 = ele_6.val();
   var keyword_2 = ele_7.val();
   var keyword_3 = ele_8.val();

   if(models.length == 0){
     alert("특정모델 혹은 전체를 선택해주세요.");
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
     var jsonData = null;
     $.ajax({
                                 url:"/voc/chart/phpdata/model/jsonData_model_total.php",
                                 type:"post",
                                 dataType:"json",
                                 data:{"startDate":startDate, "endDate":endDate, "models":models, "type":type, "keys":keys, "keyword_1":keyword_1, "keyword_2":keyword_2, "keyword_3":keyword_3},
                                 async: false,
                                 success: function (data) {
                                   console.log(data);
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

       //dataset_1 Main chart generatge
       var i = 0;
       while(i< jsonData.values.length){
         var object = {
           label: jsonData.models[i],
           fill:false,
           lineTension: 0,
           data: jsonData.values[i],
           // backgroundColor: "rgba("+colors[i]+",0.5)",
           borderColor: "rgba("+colors[i]+",0.9)",
           borderWidth: 2
         };
         dataset_1.push(object);
         i++;
       }

       //dataset_2 Main chart generatge
       var i = 0;
       while(i < jsonData.values_class.length){
         var object = {
           label: jsonData.index_class[i],
           data: jsonData.values_class[i],
           backgroundColor: "rgba("+colors[i]+",0.9)",
           borderColor: "rgba("+colors[i]+",0.7)",
           borderWidth: 2
         };
         dataset_2.push(object);
         i++;
       }

       //dataset_3 Main chart generatge
       var i = 0;
       while(i < jsonData.values_manage.length){
         var object = {
           label: jsonData.index_manage[i],
           data: jsonData.values_manage[i],
           backgroundColor: "rgba("+colors[i]+",0.9)",
           borderColor: "rgba("+colors[i]+",0.7)",
           borderWidth: 2
         };
         dataset_3.push(object);
         i++;
       }

       //dataset_4 Main chart generatge
       var i = 0;
       while(i < jsonData.values_network.length){
         var object = {
           label: jsonData.index_network[i],
           data: jsonData.values_network[i],
           backgroundColor: "rgba("+colors[i]+",0.9)",
           borderColor: "rgba("+colors[i]+",0.7)",
           borderWidth: 2
         };
         dataset_4.push(object);
         i++;
       }

       window.chart_1 = new Chart(ctx_1, {
           type: 'line',
           data: {
               labels: jsonData.index,
               datasets: dataset_1
           },
           options: {
               responsive: true,
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
               labels: jsonData.index,
               datasets: dataset_2
           },
           options: {
               responsive: true,
               scales: {
                   xAxes: [{
                       stacked: true,
                       ticks: {
                           autoSkip: true,
                           maxTicksLimit: 20,
                           fontSize: font_size,
                           fontFamily: 'Circular Std Book',
                           fontColor: '#71748d',
                       }
                   }],
                   yAxes: [{
                       stacked: true,
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
           type: 'bar',
           data: {
               labels: jsonData.models,
               datasets: dataset_3
           },
           options: {
               responsive: true,
               scales: {
                   xAxes: [{
                       stacked: true,
                       ticks: {
                           autoSkip: true,
                           maxTicksLimit: 20,
                           fontSize: font_size,
                           fontFamily: 'Circular Std Book',
                           fontColor: '#71748d',
                       }
                   }],
                   yAxes: [{
                       stacked: true,
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

       window.chart_4 = new Chart(ctx_4, {
           type: 'bar',
           data: {
               labels: jsonData.models,
               datasets: dataset_4
           },
           options: {
               responsive: true,
               scales: {
                   xAxes: [{
                       stacked: true,
                       ticks: {
                           autoSkip: true,
                           maxTicksLimit: 20,
                           fontSize: font_size,
                           fontFamily: 'Circular Std Book',
                           fontColor: '#71748d',
                       }
                   }],
                   yAxes: [{
                       stacked: true,
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
   }
}
