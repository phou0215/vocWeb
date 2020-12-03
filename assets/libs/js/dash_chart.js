var font_size = 11;
var colors = ["89,105,255", "255,64,123", "46,197,81", "255,199,80","124,252,000","238,232,170","205,133,63","240,230,140","230,230,250","106,90,205","1,191,255","25,25,112","64,224,208","220,20,60"];
var ele_1 = document.getElementById('models');
var ele_2 = document.getElementById('baseDate');
var ele_3 = document.getElementById('days');
var ele_4 = document.getElementById('startDate');
var ele_5 = document.getElementById('endDate');
var ele_6 = document.getElementById('startDateManu');
var ele_7 = document.getElementById('endDateManu');
var ele_8 = document.getElementById('manus');
var ele_9 = document.getElementById('baseDate_2');
var ele_10 = document.getElementById('days_2');

var date = ele_2.value;
var startDate = ele_4.value;
var endDate = ele_5.value;
var startDateManu = ele_6.value;
var endDateManu = ele_7.value;
var model = ele_1.options[ele_1.selectedIndex].value;
var days = ele_3.options[ele_3.selectedIndex].value;
var date_2 = ele_9.value;
var manu = ele_8.options[ele_8.selectedIndex].value;
var days_2 = ele_10.options[ele_10.selectedIndex].value;


var models = $('#modelsMonth').val();
var manus = $('#manusSelect').val();
var types = $('#dataType').val();

(function(window, document, $, undefined) {

        $(function() {

            if ($('#chartjs_line1').length) {
              var ctx = document.getElementById("chartjs_line1").getContext('2d');
              var jsonData = null;
              var jsonDataTerm2 = $.ajax({
                                          url:"/voc/phpdata/chart/jsonData_manu.php",
                                          type:"post",
                                          dataType:"json",
                                          data:{"startDate":startDateManu, "endDate":endDateManu, "manus":manus},
                                          async: false,
                                          success: function (data) {
                                            // console.log(data);
                                            jsonData = data;
                                          },
                                          error: function (request, status, error) {
                                            console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
                                          }
                                        });
              var dataset = new Array();
              var i = 0;
              while(i< jsonData.values.length){
                var object = {
                    label: jsonData.manus[i],
                    // fill:false,
                    // lineTension: 0,
                    data: jsonData.values[i],
                    backgroundColor: "rgba("+colors[i]+",0.5)",
                    // borderColor: "rgba("+colors[i]+",0.9)",
                    // borderWidth: 2
                  };
                  dataset.push(object);
                  i++;
                }
              if(window.chart1 != undefined){
                  window.chart1.destroy();
              }
              window.chart1 = new Chart(ctx, {
                    type: 'bar',
                    data: {
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
                              fontSize: font_size,
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
                        }
                }


                });
            }

            // if ($('#chartjs_line2').length) {
            //   var ctx = document.getElementById('chartjs_line2').getContext('2d');
            //   var jsonData = null;
            //   var jsonDataTerm2 = $.ajax({
            //                                     url:"/voc/phpdata/chart/jsonData_monthly.php",
            //                                     type:"post",
            //                                     dataType:"json",
            //                                     data:{"types": types},
            //                                     async: false,
            //                                     success: function (data) {
            //                                       // console.log(data);
            //                                       jsonData = data;
            //                                     },
            //                                     error: function (request, status, error) {
            //                                       console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
            //                                     }
            //                                   });
            //   var dataset = new Array();
            //   var i = 0;
            //   while(i< jsonData.values.length){
            //         var object = {
            //               label: jsonData.types[i],
            //               fill:true,
            //               lineTension: 0,
            //               data: jsonData.values[i],
            //               backgroundColor: "rgba("+colors[i]+",0.5)",
            //               borderColor: "rgba("+colors[i]+",0.7)",
            //               borderWidth: 2
            //             };
            //             dataset.push(object);
            //             i++;
            //         }
            //   if(window.chart2 != undefined){
            //       window.chart2.destroy();
            //   }
            //   window.chart2 = new Chart(ctx, {
            //                 type: 'line',
            //                 data: {
            //                     labels: jsonData.index,
            //                     datasets: dataset
            //
            //                 },
            //                 options: {
            //                     responsive: true,
            //                     legend: {
            //                         display: true,
            //                         position: 'bottom',
            //
            //                         labels: {
            //                             fontColor: '#71748d',
            //                             fontFamily: 'Circular Std Book',
            //                             fontSize: font_size,
            //                         }
            //                     },
            //
            //                     elements: {
            //           						point: {
            //           							pointStyle: "circle",
            //                         backgroundColor : "rgba(255,255,225,0.9)",
            //                         hoverRadius: 5,
            //                         borderWidth: 8
            //           						}
            //           					},
            //
            //                     scales: {
            //                         xAxes: [{
            //                             ticks: {
            //                                 autoSkip: true,
            //                                 maxTicksLimit: 20,
            //                                 fontSize: font_size,
            //                                 fontFamily: 'Circular Std Book',
            //                                 fontColor: '#71748d',
            //                             }
            //                         }],
            //                         yAxes: [{
            //                             ticks: {
            //                                 fontSize: font_size,
            //                                 fontFamily: 'Circular Std Book',
            //                                 fontColor: '#71748d',
            //                             }
            //                         }]
            //                     }
            //                 }
            //         });
            // }

            if ($('#chartjs_line3').length) {
              var ctx = document.getElementById("chartjs_line3").getContext('2d');
              var jsonData = null;
              var jsonDataTerm2 = $.ajax({
                                          url:"/voc/phpdata/chart/jsonData_class_model.php",
                                          type:"post",
                                          dataType:"json",
                                          data:{"startDate":startDate, "endDate":endDate, "models":models},
                                          async: false,
                                          success: function (data) {
                                            // console.log(data);
                                            jsonData = data;
                                          },
                                          error: function (request, status, error) {
                                            console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
                                          }
                                        });
                var dataset = new Array();
                var combiData = {
                }
                var object = {
                  type:'bar',
                  label: jsonData.models,
                  id:'y_axis_0',
                  data: jsonData.values,
                  backgroundColor: "rgba("+colors[2]+",0.5)",
                  // borderColor: "rgba("+colors[1]+",0.9)",
                  // borderWidth: 2
                };
                var object_rate = {
                  type:'line',
                  label: jsonData.models+'_rate',
                  id:'y_axis_1',
                  fill:false,
                  lineTension: 0,
                  data: jsonData.rate,
                  // backgroundColor: "rgba("+colors[i]+",0.5)",
                  borderColor: "rgba("+colors[0]+",0.9)",
                  borderWidth: 2
                }
                dataset.push(object);
                dataset.push(object_rate);

                if(window.chart4 != undefined){
                    window.chart4.destroy();
                }
                window.chart4 = new Chart(ctx, {
                  type: 'bar',
                  data:{
                      labels: jsonData.index,
                      datasets: dataset
                  },
                  options: {
                    responsive: true,
                    legend: {
                      display: true,
                      position: 'bottom',
                      labels:{
                        fontColor: '#71748d',
                        fontFamily: 'Circular Std Book',
                        fontSize: font_size,
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
                        // type:'time',
                        ticks: {
                          autoSkip: true,
                          maxTicksLimit: 20,
                          fontSize: font_size,
                          fontFamily: 'Circular Std Book',
                          fontColor: '#71748d'
                        },
                      }],
                      yAxes: [{
                        ticks: {
                          fontSize: font_size,
                          fontFamily: 'Circular Std Book',
                          fontColor: '#71748d',
                          beginAtZero: true,
                        },
                        position: "left",
                        id: "y_axis_0",
                        },{
                        ticks: {
                          fontSize: font_size,
                          fontFamily: 'Circular Std Book',
                          fontColor: '#71748d',
                          beginAtZero: true,
                          display:false
                        },
                        position: "right",
                        id: "y_axis_1",
                        gridLines: { display: false}
                      }]
                    }
                  }
              });
            }

            if ($('#chartjs_bar3').length) {
              var ctx = document.getElementById("chartjs_bar3").getContext('2d');
              var ctx_1 = document.getElementById("chartjs_line4").getContext('2d');
              var jsonData = null;
              var jsonDataTerm2 = $.ajax({
                                          url:"/voc/phpdata/chart/jsonData_class_updown.php",
                                          type:"post",
                                          data:{"model":model,"date":date,"days":days},
                                          dataType:"json",
                                          async: false,
                                          success: function (data) {
                                            // console.log(data);
                                            jsonData = data;
                                          },
                                          error: function (request, status, error) {
                                            console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
                                          }
                                        });
                var dataset = new Array();
                var dataset_1 = new Array();
                var i = 0;
                while(i< jsonData.values.length){
                  var object = {
                      label: jsonData.class[i],
                      data: jsonData.values[i],
                      backgroundColor: "rgba("+colors[i]+",0.9)",
                      borderColor: "rgba("+colors[i]+",0.7)",
                      borderWidth: 2
                  };
                  dataset.push(object);
                  i++;
                }
                if(window.chart5 != undefined){
                    window.chart5.destroy();
                }
                window.chart5 = new Chart(ctx, {
                    type: 'bar',
                    data: {
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
                              fontSize: font_size,
                          }
                        },

                        scales: {
                            xAxes: [{
                                ticks: {
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
                        }
                }


                });

                //증감 차트 부분
                i = 0;
                while(i< jsonData.valuesUpDown.length){
                  var object = {
                      label: jsonData.class[i],
                      data: jsonData.valuesUpDown[i],
                      fill:false,
                      lineTension: 0,
                      // backgroundColor: "rgba("+colors[i]+",0.7)",
                      borderColor: "rgba("+colors[i]+",0.9)",
                      borderWidth: 2
                  };
                  dataset_1.push(object);
                  i++;
                }
                if(window.chart6 != undefined){
                    window.chart6.destroy();
                }
                window.chart6 = new Chart(ctx_1, {
                    type: 'line',
                    data: {
                        // labels: jsonData.indexUpDown,
                        labels: jsonData.index,
                        datasets: dataset_1
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
                              fontSize: font_size,
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
                        }
                }


                });
            }

            if ($('#chartjs_bar4').length) {
              var ctx = document.getElementById("chartjs_bar4").getContext('2d');
              var ctx_1 = document.getElementById("chartjs_line5").getContext('2d');
              var jsonData = null;
              var jsonDataTerm2 = $.ajax({
                                          url:"/voc/phpdata/chart/jsonData_class_updown2.php",
                                          type:"post",
                                          data:{"manu":manu,"date":date_2,"days":days_2},
                                          dataType:"json",
                                          async: false,
                                          success: function (data) {
                                            // console.log(data);
                                            jsonData = data;
                                          },
                                          error: function (request, status, error) {
                                            console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
                                          }
                                        });
                var dataset = new Array();
                var dataset_1 = new Array();
                var i = 0;
                while(i< jsonData.values.length){
                  var object = {
                      label: jsonData.class[i],
                      data: jsonData.values[i],
                      backgroundColor: "rgba("+colors[i]+",0.9)",
                      borderColor: "rgba("+colors[i]+",0.7)",
                      borderWidth: 2
                  };
                  dataset.push(object);
                  i++;
                }
                if(window.chart7 != undefined){
                    window.chart7.destroy();
                }
                window.chart7 = new Chart(ctx, {
                    type: 'bar',
                    data: {
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
                              fontSize: font_size,
                          }
                        },

                        scales: {
                            xAxes: [{
                                ticks: {
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
                        }
                    }
                });

                //증감 차트 부분
                i = 0;
                while(i< jsonData.valuesUpDown.length){
                  var object = {
                      label: jsonData.class[i],
                      data: jsonData.valuesUpDown[i],
                      fill:false,
                      lineTension: 0,
                      // backgroundColor: "rgba("+colors[i]+",0.7)",
                      borderColor: "rgba("+colors[i]+",0.9)",
                      borderWidth: 2
                  };
                  dataset_1.push(object);
                  i++;
                }
                if(window.chart8 != undefined){
                    window.chart8.destroy();
                }
                window.chart8 = new Chart(ctx_1, {
                    type: 'line',
                    data: {
                        // labels: jsonData.indexUpDown,
                        labels: jsonData.index,
                        datasets: dataset_1
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
                              fontSize: font_size,
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
                    }
                }


                });
            }

        });

})(window, document, window.jQuery);

function upChartClass(){

  if ($('#chartjs_bar3').length) {
    var ctx = document.getElementById("chartjs_bar3").getContext('2d');
    var ctx_1 = document.getElementById("chartjs_line4").getContext('2d');
    ele_1 = document.getElementById('models');
    ele_2 = document.getElementById('baseDate');
    ele_3 = document.getElementById('days');
    date = ele_2.value;
    model = ele_1.options[ele_1.selectedIndex].value;
    days = ele_3.options[ele_3.selectedIndex].value;

    var jsonData = null;
    var jsonDataTerm2 = $.ajax({
                                url:"/voc/phpdata/chart/jsonData_class_updown.php",
                                type:"post",
                                data:{"model":model,"date":date,"days":days},
                                dataType:"json",
                                async: false,
                                success: function (data) {
                                  // console.log(data);
                                  jsonData = data;
                                },
                                error: function (request, status, error) {
                                  console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
                                }
                              });
      var dataset = new Array();
      var dataset_1 = new Array();
      var i = 0;
      while(i< jsonData.values.length){
        var object = {
            label: jsonData.class[i],
            data: jsonData.values[i],
            backgroundColor: "rgba("+colors[i]+",0.9)",
            borderColor: "rgba("+colors[i]+",0.7)",
            borderWidth: 2
        };
        dataset.push(object);
        i++;
      }
      if(window.chart5 != undefined){
          window.chart5.destroy();
      }
      window.chart5 = new Chart(ctx, {
          type: 'bar',
          data: {
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
                    fontSize: font_size,
                }
              },

              scales: {
                  xAxes: [{
                      ticks: {
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
              }
          }

      });

      //증감 차트 부분
      i = 0;
      while(i< jsonData.valuesUpDown.length){
        var object = {
            label: jsonData.class[i],
            data: jsonData.valuesUpDown[i],
            fill:false,
            lineTension: 0,
            // backgroundColor: "rgba("+colors[i]+",0.5)",
            borderColor: "rgba("+colors[i]+",0.9)",
            borderWidth: 2
        };
        dataset_1.push(object);
        i++;
      }
      if(window.chart6 != undefined){
          window.chart6.destroy();
      }
      window.chart6 = new Chart(ctx_1, {
          type: 'line',
          data: {
              // labels: jsonData.indexUpDown,
              labels: jsonData.index,
              datasets: dataset_1
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
                    fontSize: font_size,
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
              }
            }
        });
    }

}

function upChartClass_2(){

  if ($('#chartjs_bar4').length) {
    var ctx = document.getElementById("chartjs_bar4").getContext('2d');
    var ctx_1 = document.getElementById("chartjs_line5").getContext('2d');
    ele_8 = document.getElementById('manus');
    ele_9 = document.getElementById('baseDate_2');
    ele_10 = document.getElementById('days_2');

    date_2 = ele_9.value;
    manu = ele_8.options[ele_8.selectedIndex].value;
    days_2 = ele_10.options[ele_10.selectedIndex].value;

    var jsonData = null;
    var jsonDataTerm2 = $.ajax({
                                url:"/voc/phpdata/chart/jsonData_class_updown2.php",
                                type:"post",
                                data:{"manu":manu,"date":date_2,"days":days_2},
                                dataType:"json",
                                async: false,
                                success: function (data) {
                                  // console.log(data);
                                  jsonData = data;
                                },
                                error: function (request, status, error) {
                                  console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
                                }
                              });
      var dataset = new Array();
      var dataset_1 = new Array();
      var i = 0;
      while(i< jsonData.values.length){
        var object = {
            label: jsonData.class[i],
            data: jsonData.values[i],
            backgroundColor: "rgba("+colors[i]+",0.9)",
            borderColor: "rgba("+colors[i]+",0.7)",
            borderWidth: 2
        };
        dataset.push(object);
        i++;
      }
      if(window.chart7 != undefined){
          window.chart7.destroy();
      }
      window.chart7 = new Chart(ctx, {
          type: 'bar',
          data: {
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
                    fontSize: font_size,
                }
              },

              scales: {
                  xAxes: [{
                      ticks: {
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
              }
          }
      });

      //증감 차트 부분
      i = 0;
      while(i< jsonData.valuesUpDown.length){
        var object = {
            label: jsonData.class[i],
            data: jsonData.valuesUpDown[i],
            fill:false,
            lineTension: 0,
            // backgroundColor: "rgba("+colors[i]+",0.5)",
            borderColor: "rgba("+colors[i]+",0.9)",
            borderWidth: 2
        };
        dataset_1.push(object);
        i++;
      }
      if(window.chart8 != undefined){
          window.chart8.destroy();
      }
      window.chart8 = new Chart(ctx_1, {
          type: 'line',
          data: {
              // labels: jsonData.indexUpDown,
              labels: jsonData.index,
              datasets: dataset_1
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
                    fontSize: font_size,
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
            }
          }
      });
  }

}

function upChartMonth(){

  if ($('#chartjs_line3').length) {

    ele_4 = document.getElementById('startDate');
    ele_5 = document.getElementById('endDate');
    models = $('#modelsMonth').val();

    startDate = ele_4.value;
    endDate = ele_5.value;


    var ctx = document.getElementById("chartjs_line3").getContext('2d');
    var jsonData = null;
    var jsonDataTerm2 = $.ajax({
                                url:"/voc/phpdata/chart/jsonData_class_model.php",
                                type:"post",
                                dataType:"json",
                                data:{"startDate":startDate, "endDate":endDate, "models":models},
                                async: false,
                                success: function (data) {
                                  // console.log(data);
                                  jsonData = data;
                                },
                                error: function (request, status, error) {
                                  console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
                                }
                              });
      var dataset = new Array();
      var combiData = {
      }
      var object = {
        type:'bar',
        label: jsonData.models,
        id:'y_axis_0',
        data: jsonData.values,
        backgroundColor: "rgba("+colors[2]+",0.5)",
        // borderColor: "rgba("+colors[1]+",0.9)",
        // borderWidth: 2
      };
      var object_rate = {
        type:'line',
        label: jsonData.models+'_rate',
        id:'y_axis_1',
        fill:false,
        lineTension: 0,
        data: jsonData.rate,
        // backgroundColor: "rgba("+colors[i]+",0.5)",
        borderColor: "rgba("+colors[0]+",0.9)",
        borderWidth: 2
      }
      dataset.push(object);
      dataset.push(object_rate);

      if(window.chart4 != undefined){
          window.chart4.destroy();
      }
      window.chart4 = new Chart(ctx, {
        type: 'bar',
        data:{
            labels: jsonData.index,
            datasets: dataset
        },
        options: {
          responsive: true,
          legend: {
            display: true,
            position: 'bottom',
            labels:{
              fontColor: '#71748d',
              fontFamily: 'Circular Std Book',
              fontSize: font_size,
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
              // type:'time',
              ticks: {
                autoSkip: true,
                maxTicksLimit: 20,
                fontSize: font_size,
                fontFamily: 'Circular Std Book',
                fontColor: '#71748d'
              },
            }],
            yAxes: [{
              ticks: {
                fontSize: font_size,
                fontFamily: 'Circular Std Book',
                fontColor: '#71748d',
                beginAtZero: true,
              },
              position: "left",
              id: "y_axis_0",
              },{
              ticks: {
                fontSize: font_size,
                fontFamily: 'Circular Std Book',
                fontColor: '#71748d',
                beginAtZero: true,
                display: false
              },
              position: "right",
              id: "y_axis_1",
              gridLines: { display: false}
            }]
          }
        }
    });
  }
}

// function upTypeChart(){
//
//   if ($('#chartjs_line2').length) {
//           types = $('#dataType').val();
//           var ctx = document.getElementById('chartjs_line2').getContext('2d');
//           var jsonData = null;
//           var jsonDataTerm2 = $.ajax({
//                                       url:"/voc/phpdata/chart/jsonData_monthly.php",
//                                       type:"post",
//                                       dataType:"json",
//                                       data:{"types":types},
//                                       async: false,
//                                       success: function (data) {
//                                         // console.log(data);
//                                         jsonData = data;
//                                       },
//                                       error: function (request, status, error) {
//                                         console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
//                                       }
//                                     });
//           var dataset = new Array();
//           var i = 0;
//           while(i< jsonData.values.length){
//               var object = {
//                 label: jsonData.types[i],
//                 fill:true,
//                 lineTension: 0,
//                 data: jsonData.values[i],
//                 backgroundColor: "rgba("+colors[i]+",0.5)",
//                 borderColor: "rgba("+colors[i]+",0.7)",
//                 borderWidth: 2
//               };
//               dataset.push(object);
//               i++;
//           }
//           if(window.chart2 != undefined){
//               window.chart2.destroy();
//           }
//           window.chart2 = new Chart(ctx, {
//                   type: 'line',
//                   data: {
//                       labels: jsonData.index,
//                       datasets: dataset
//
//                   },
//                   options: {
//                       responsive: true,
//                       legend: {
//                           display: true,
//                           position: 'bottom',
//
//                           labels: {
//                               fontColor: '#71748d',
//                               fontFamily: 'Circular Std Book',
//                               fontSize: font_size,
//                           }
//                       },
//
//                       elements: {
//                         point: {
//                           pointStyle: "circle",
//                           backgroundColor : "rgba(255,255,225,0.9)",
//                           hoverRadius: 5,
//                           borderWidth: 8
//                         }
//                       },
//
//                       scales: {
//                           xAxes: [{
//                               ticks: {
//                                   autoSkip: true,
//                                   maxTicksLimit: 20,
//                                   fontSize: font_size,
//                                   fontFamily: 'Circular Std Book',
//                                   fontColor: '#71748d',
//                               }
//                           }],
//                           yAxes: [{
//                               ticks: {
//                                   fontSize: font_size,
//                                   fontFamily: 'Circular Std Book',
//                                   fontColor: '#71748d',
//                               }
//                           }]
//                       }
//                 }
//           });
//       }
//
// }

function upChartManu(){

  if ($('#chartjs_line1').length) {
    ele_6 = document.getElementById('startDateManu');
    ele_7 = document.getElementById('endDateManu');
    startDateManu = ele_6.value;
    endDateManu = ele_7.value;
    manus = $('#manusSelect').val();
    var ctx = document.getElementById("chartjs_line1").getContext('2d');
    var jsonData = null;
    var jsonDataTerm2 = $.ajax({
                                url:"/voc/phpdata/chart/jsonData_manu.php",
                                type:"post",
                                dataType:"json",
                                data:{"startDate":startDateManu, "endDate":endDateManu, "manus":manus},
                                async: false,
                                success: function (data) {
                                  // console.log(data);
                                  jsonData = data;
                                },
                                error: function (request, status, error) {
                                  console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
                                }
                              });
      var dataset = new Array();
      var i = 0;
      while(i< jsonData.values.length){
        var object = {
          label: jsonData.manus[i],
          // fill:false,
          // lineTension: 0,
          data: jsonData.values[i],
          backgroundColor: "rgba("+colors[i]+",0.5)",
          // borderColor: "rgba("+colors[i]+",0.7)",
          // borderWidth: 2
        };
        dataset.push(object);
        i++;
      }
      if(window.chart1 != undefined){
          window.chart1.destroy();
      }
      window.chart1 = new Chart(ctx, {
          type: 'bar',
          data: {
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
                    fontSize: font_size,
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
              }
          }

      });
    }

}
