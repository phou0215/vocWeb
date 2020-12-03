var font_size = 11;
var colors = ["89,105,255", "255,64,123", "46,197,81", "255,199,80","124,252,000","238,232,170","205,133,63","240,230,140","230,230,250","106,90,205","1,191,255","25,25,112","64,224,208","220,20,60","70,191,189","252,180,92","247,70,74","148,159,177","51,143,82"];
var bgcolor = [];
for(var i=0;i<colors.length;i++){
  var t_color = "rgba("+colors[i]+",0.8)";
  bgcolor.push(t_color);
}

(function(window, document, $, undefined) {

        var ele_1 = $('#selectType'); // 통품전체 / 불만성유형
        var ele_2 = $('#netType'); // 5G/ LTE
        var ele_3 = $('#startDate'); // 시작날짜
        var ele_4 = $('#endDate'); // 종료날짜
        var ele_5 = $('#selectKeys'); // 검색어
        var ele_6 = $('#selectModel_5G'); // 5G 단말기 선택
        var ele_7 = $('#selectModel_LTE'); // LTE 단말기 선택
        var ele_8 = $('#table_1');// 지역별 표
        var ele_9 = $('#modelType'); // origin / mapping
        var timeline = ["00시","01시","02시","03시","04시","05시","06시","07시","08시","09시","10시","11시","12시",
                        "13시","14시","15시","16시","17시","18시","19시","20시","21시","22시","23시"]

        var sort = ele_1.val();
        var netType = ele_2.val();
        var startDate = ele_3.val();
        var endDate = ele_4.val();
        var key = ele_5.val();
        var select5G = ele_6.val();
        var selectLTE = ele_7.val();
        var modelType = ele_9.val();

        // chartjs_bar1 ==> 모델별 키워드 VOC 날짜 COUNT
        // chartjs_line2 ==> 제조사별 키워드 Rate 날짜 COUNT
        // chartjs_pie1 ==> 키워드 비율
        // table_1 ==> 지역별 모델 건수

        if ($('#chartjs_bar1').length) {

              var ctx_1 = document.getElementById("chartjs_bar1").getContext('2d');
              var ctx_2 = document.getElementById("chartjs_line2").getContext('2d');
              var ctx_3 = document.getElementById("chartjs_pie1").getContext('2d');
              var ctx_4 = document.getElementById("chartjs_bar2").getContext('2d');
              var jsonData = null;

              $.ajax({
                url:"/voc/chart/phpdata/keyword/jsonData_keyword_total.php",
                type:"post",
                dataType:"json",
                data:{"startDate":startDate, "endDate":endDate, "key":key, "sort":sort, "netType":netType, "model":select5G, "modelType":modelType},
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

                //dataset_1 keyword Voc Bar chart generatge
                var i = 0;
                while(i< jsonData.values.length){
                  var object = {
                    label: jsonData.index_class[i],
                    data: jsonData.values[i][0],
                    backgroundColor: "rgba("+colors[i]+",0.7)",
                  };
                  dataset_1.push(object);
                  i++;
                }

                //dataset_2 keyword Rate Line chart generatge
                var i = 0;
                while(i< jsonData.values.length){
                  var object = {
                    type:'line',
                    label: jsonData.index_class[i],
                    fill:true,
                    lineTension: 0,
                    data: jsonData.values[i][2],
                    backgroundColor: "rgba("+colors[i]+",0.5)",
                    borderColor: "rgba("+colors[i]+",0.9)",
                    borderWidth: 2
                  };
                  dataset_2.push(object);
                  i++;
                }

                //dataset_4 Time Voc Bar chart generatge
                // var i = 0;
                // while(i< jsonData.values_timeline.length){
                //   var object = {}
                //   if (i != jsonData.values_timeline.length-1){
                //      object = {
                //       label: jsonData.index_date[i],
                //       data: jsonData.values_timeline[i],
                //       backgroundColor: "rgba("+colors[i]+",0.7)",
                //     };
                //   }else{
                //     object = {
                //      label: '평균',
                //      data: jsonData.values_timeline[i],
                //      backgroundColor: "rgba("+colors[i]+",0.7)",
                //    };
                //   }
                //   dataset_4.push(object);
                //   i++;
                // }

                //dataset_4 Time Voc Bar chart generatge
                var i = 0;
                while(i< jsonData.values_timeline.length){
                  var object = {
                    label: jsonData.index_date[i],
                    data: jsonData.values_timeline[i],
                    backgroundColor: "rgba("+colors[i]+",0.7)"
                  };
                  dataset_4.push(object);
                  i++;
                }

                //dataset_3  Pie model keyword extract
                var object3 = {
                  backgroundColor: bgcolor,
                  data: jsonData.values_extract
                  // borderColor: "rgba("+colors[i]+",0.7)",
                  // borderWidth: 2
                };
                dataset_3.push(object3);


                window.chart_1 = new Chart(ctx_1, {
                    type: 'bar',
                    data: {
                        labels: jsonData.index_date,
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
                    type: 'line',
                    data: {
                        // labels: jsonData.indexUpDown,
                        labels: jsonData.index_date,
                        datasets: dataset_2
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

                window.chart_4 = new Chart(ctx_4, {
                    type: 'bar',
                    data: {
                        labels: timeline,
                        datasets: dataset_4
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
                window.chart_3 = new Chart(ctx_3, {
                    type: 'doughnut',
                    data: {
                        labels: jsonData.index_extract,
                        datasets: dataset_3
                    },
                    options: {

                        legend: {
                        display: true,
                        position: 'left',

                        labels: {
                            fontColor: '#71748d',
                            fontFamily: 'Circular Std Book',
                            fontSize: font_size,
                        }
                    },
                  }
                });

                // Table 작성
                //이전 요소 삭제
                ele_8.empty();

                // 단말종합요약 Table 새로 생성
                var str = '';
                str += '<table class="table table-striped">';
                //구분
                str += '<thead>';
                str += '<tr>';
                str += '<th scope="col">지역</th>';
                str += '<th scope="col">지역별 건수</th>';
                str += '<th scope="col">지역별 비율</th>';
                str += '</tr>';
                str += '</thead>';
                str += '<tbody>';

                i =0;
                while(i<jsonData.index_manageCo.length){

                  str += '<tr>';
                  str += '<td>'+jsonData.index_manageCo[i]+'</td>';
                  str += '<td>'+jsonData.values_manageCo[i]+'건</td>';
                  str += '<td>'+jsonData.values_manageCo_per[i]+'%</td>';
                  str += '</tr>';
                  i++;
                }
                str += '</tbody>';
                str += '</table>';
                ele_8.append(str);
            }

})(window, document, window.jQuery);

function update(){

  var ele_1 = $('#selectType'); // 통품전체 / 불만성유형
  var ele_2 = $('#netType'); // 5G/ LTE
  var ele_3 = $('#startDate'); // 시작날짜
  var ele_4 = $('#endDate'); // 종료날짜
  var ele_5 = $('#selectKeys'); // 검색어
  var ele_6 = $('#selectModel_5G'); // 5G 단말기 선택
  var ele_7 = $('#selectModel_LTE'); // LTE 단말기 선택
  var ele_8 = $('#table_1');// 지역별 표
  var ele_9 = $('#modelType'); // origin / mapping
  var timeline = ["00시","01시","02시","03시","04시","05시","06시","07시","08시","09시","10시","11시","12시",
                  "13시","14시","15시","16시","17시","18시","19시","20시","21시","22시","23시"]

  var sort = ele_1.val();
  var netType = ele_2.val();
  var startDate = ele_3.val();
  var endDate = ele_4.val();
  var key = ele_5.val();
  var select5G = ele_6.val();
  var selectLTE = ele_7.val();
  var modelType = ele_9.val();


  // chartjs_bar1 ==> 모델별 키워드 VOC 날짜 COUNT
  // chartjs_line2 ==> 제조사별 키워드 Rate 날짜 COUNT
  // chartjs_pie1 ==> 키워드 비율
  // table_1 ==> 지역별 모델 건수

  if ($('#chartjs_bar1').length) {

        var ctx_1 = document.getElementById("chartjs_bar1").getContext('2d');
        var ctx_2 = document.getElementById("chartjs_line2").getContext('2d');
        var ctx_3 = document.getElementById("chartjs_pie1").getContext('2d');
        var ctx_4 = document.getElementById("chartjs_bar2").getContext('2d');
        var jsonData = null;
        var selected_model;
        if (netType == '5G'){
          selected_model = select5G
        }else{
          selected_model = selectLTE
        }


        $.ajax({
          url:"/voc/chart/phpdata/keyword/jsonData_keyword_total.php",
          type:"post",
          dataType:"json",
          data:{"startDate":startDate, "endDate":endDate, "key":key, "sort":sort, "netType":netType, "model":selected_model, "modelType":modelType},
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

          //dataset_1 keyword Voc Bar chart generatge
          var i = 0;
          while(i< jsonData.values.length){
            var object = {
              label: jsonData.index_class[i],
              data: jsonData.values[i][0],
              backgroundColor: "rgba("+colors[i]+",0.7)",
            };
            dataset_1.push(object);
            i++;
          }

          //dataset_2 keyword Rate Line chart generatge
          var i = 0;
          while(i< jsonData.values.length){
            var object = {
              type:'line',
              label: jsonData.index_class[i],
              fill:true,
              lineTension: 0,
              data: jsonData.values[i][2],
              backgroundColor: "rgba("+colors[i]+",0.5)",
              borderColor: "rgba("+colors[i]+",0.9)",
              borderWidth: 2
            };
            dataset_2.push(object);
            i++;
          }

          //dataset_4 Time Voc Bar chart generatge
          // var i = 0;
          // while(i< jsonData.values_timeline.length){
          //   var object = {}
          //   if (i != jsonData.values_timeline.length-1){
          //      object = {
          //       label: jsonData.index_date[i],
          //       data: jsonData.values_timeline[i],
          //       backgroundColor: "rgba("+colors[i]+",0.7)",
          //     };
          //   }else{
          //     object = {
          //      label: '평균',
          //      data: jsonData.values_timeline[i],
          //      backgroundColor: "rgba("+colors[i]+",0.7)",
          //    };
          //   }
          //   dataset_4.push(object);
          //   i++;
          // }

          //dataset_4 Time Voc Bar chart generatge
          var i = 0;
          while(i< jsonData.values_timeline.length){
            var object = {
              label: jsonData.index_date[i],
              data: jsonData.values_timeline[i],
              backgroundColor: "rgba("+colors[i]+",0.7)"
            };
            dataset_4.push(object);
            i++;
          }

          //dataset_3  Pie model keyword extract
          var object3 = {
            backgroundColor: bgcolor,
            data: jsonData.values_extract
            // borderColor: "rgba("+colors[i]+",0.7)",
            // borderWidth: 2
          };
          dataset_3.push(object3);


          window.chart_1 = new Chart(ctx_1, {
              type: 'bar',
              data: {
                  labels: jsonData.index_date,
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
              type: 'line',
              data: {
                  // labels: jsonData.indexUpDown,
                  labels: jsonData.index_date,
                  datasets: dataset_2
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

          window.chart_4 = new Chart(ctx_4, {
              type: 'bar',
              data: {
                  labels: timeline,
                  datasets: dataset_4
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
          window.chart_3 = new Chart(ctx_3, {
              type: 'doughnut',
              data: {
                  labels: jsonData.index_extract,
                  datasets: dataset_3
              },
              options: {

                  legend: {
                  display: true,
                  position: 'left',

                  labels: {
                      fontColor: '#71748d',
                      fontFamily: 'Circular Std Book',
                      fontSize: font_size,
                  }
              },
            }
          });

          // Table 작성
          //이전 요소 삭제
          ele_8.empty();

          // 단말종합요약 Table 새로 생성
          var str = '';
          str += '<table class="table table-striped">';
          //구분
          str += '<thead>';
          str += '<tr>';
          str += '<th scope="col">지역</th>';
          str += '<th scope="col">지역별 건수</th>';
          str += '<th scope="col">지역별 비율</th>';
          str += '</tr>';
          str += '</thead>';
          str += '<tbody>';

          i =0;
          while(i<jsonData.index_manageCo.length){

            str += '<tr>';
            str += '<td>'+jsonData.index_manageCo[i]+'</td>';
            str += '<td>'+jsonData.values_manageCo[i]+'건</td>';
            str += '<td>'+jsonData.values_manageCo_per[i]+'%</td>';
            str += '</tr>';
            i++;
          }
          str += '</tbody>';
          str += '</table>';
          ele_8.append(str);
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
