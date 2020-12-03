// success: function(data) {
// 	$.each(data.arrjson, function(index, arrjson) {
// 		$('#tabList').append("<tr><td>" + arrjson.no + "</td><td>" + arrjson.name + "</td></tr>");
// 	});

var font_size = 11;
var colors = ["89,105,255", "255,64,123", "46,197,81", "255,199,80","124,252,000","238,232,170","205,133,63","240,230,140",
              "230,230,250","106,90,205","1,191,255","25,25,112","64,224,208","220,20,60"];
var list_group = ["#list_group1", "#list_group2", "#list_group3", "#list_group4"];

var item_group = [["#list_item1-1", "#list_item1-2", "#list_item1-3"],["#list_item2-1", "#list_item2-2", "#list_item2-3"],
                 ["#list_item3-1", "#list_item3-2", "#list_item3-3"],["#list_item4-1", "#list_item4-2", "#list_item4-3"]];

var tri_group = [["#list_tri1-1", "#list_tri1-2", "#list_tri1-3"],["#list_tri2-1", "#list_tri2-2", "#list_tri2-3"],
                ["#list_tri3-1", "#list_tri3-2", "#list_tri3-3"],["#list_tri4-1", "#list_tri4-2", "#list_tri4-3"]];

var per_group = [["#list_per1-1", "#list_per1-2", "#list_per1-3"],["#list_per2-1", "#list_per2-2", "#list_per2-3"],
                ["#list_per3-1", "#list_per3-2", "#list_per3-3"],["#list_per4-1", "#list_per4-2", "#list_per4-3"]];

(function(window, document, $, undefined) {

        // 선택된 모델
        var ele_1 = $('#selectModel');
        // 데이터 종류(전체/불만성)
        var ele_2 = $('#selectType');
        // 시작일자
        var ele_3 = $('#startDate');
        // 종료일자
        var ele_4 = $('#endDate');
        //모델 타입(Origin/Mapping)
        var ele_5 = $('#modelType');

        var models = ele_1.val();
        var type = ele_2.val();
        var startDate = ele_3.val();
        var endDate = ele_4.val();
        var modelType = ele_5.val();
        var item_size = 0;

        if ($('#chartjs_line1').length) {
              var ctx = document.getElementById("chartjs_line1").getContext('2d');
              var jsonData = null;
              $.ajax({
                                          url:"/voc/chart/phpdata/view/view_LTERate.php",
                                          type:"post",
                                          dataType:"json",
                                          data:{"startDate":startDate, "endDate":endDate, "models":models, "type":type, "modelType":modelType},
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
                    data: jsonData.values[i][2],
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
                                    // beginAtZero:true,
                                    fontSize: font_size,
                                    fontFamily: 'Circular Std Book',
                                    fontColor: '#71748d',
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
        // list group setting
        var i = 0;
        var idx_last = jsonData.values[0][0].length-1;
        while(i < 4){
          if(i < item_size){
                //팝업 화면에 노출 선택
                $(list_group[i]).removeAttr('hidden');
                //h5 tag에 선택 모델 이름 넣기
                $(list_group[i]+" h5").text(jsonData.models[i]);
                //h4 tag에 최근 값 넣기
                $(list_group[i]+" h4").text(jsonData.values[i][2][idx_last]+"‰");
                // 전주 비교 수치에서 양수/음수/보합 각각 Red/Blue/Green 입력
                if (jsonData.values[i][4][0] < 0){
                  $(list_group[i]+" h4").attr('class', 'mb-0 text-primary');
                }else if(jsonData.values[i][4][0] > 0){
                  $(list_group[i]+" h4").attr('class', 'mb-0 text-danger');
                }else{
                  $(list_group[i]+" h4").attr('class', 'mb-0 text-success');
                }
                //각 증감 수치 입력
                var j = 0;
                while(j < 3){
                  //증감 수
                  var in_data = jsonData.values[i][3][j];
                  $(item_group[i][j]).text("  "+in_data+" ");
                  //증감율
                  var in_per = jsonData.values[i][4][j];
                  $(per_group[i][j]).text(" "+in_per+"%");
                  if (in_per < 0){
                    $(per_group[i][j]).attr('class', 'text-primary');
                    $(tri_group[i][j]).attr('class', 'fa fa-fw fa-caret-down text-primary');
                  }else if(in_per > 0){
                    $(per_group[i][j]).attr('class', 'text-danger');
                    $(tri_group[i][j]).attr('class', 'fa fa-fw fa-caret-up text-danger');
                  }else{
                    $(per_group[i][j]).attr('class', 'text-success');
                    $(tri_group[i][j]).attr('class', 'fas fa-minus text-success');
                  }
                  j++;
                }
            }else{
                $(list_group[i]).attr('hidden', 'hidden');
            }
            i++;
          }
})(window, document, window.jQuery);


function update(){

  // 선택된 모델
  var ele_1 = $('#selectModel');
  // 데이터 종류(전체/불만성)
  var ele_2 = $('#selectType');
  // 시작일자
  var ele_3 = $('#startDate');
  // 종료일자
  var ele_4 = $('#endDate');
  //모델 타입(Origin/Mapping)
  var ele_5 = $('#modelType');

  var models = ele_1.val();
  var type = ele_2.val();
  var startDate = ele_3.val();
  var endDate = ele_4.val();
  var modelType = ele_5.val();

   if(models.length == 0){
     alert("특정모델 혹은 전체를 선택해주세요.");
     return null;
   }

   if ($('#chartjs_line1').length) {
       var ctx = document.getElementById("chartjs_line1").getContext('2d');
       var jsonData = null;
       $.ajax({
                                   url:"/voc/chart/phpdata/view/view_LTERate.php",
                                   type:"post",
                                   dataType:"json",
                                   data:{"startDate":startDate, "endDate":endDate, "models":models, "type":type, "modelType":modelType},
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
             data: jsonData.values[i][2],
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

     // list group setting
     var i = 0;
     var idx_last = jsonData.values[0][0].length-1;
     while(i < 4){
       if(i < item_size){
           //팝업 화면에 노출 선택
           $(list_group[i]).removeAttr('hidden');
           //h5 tag에 선택 모델 이름 넣기
           $(list_group[i]+" h5").text(jsonData.models[i]);
           //h4 tag에 최근 값 넣기
           $(list_group[i]+" h4").text(jsonData.values[i][2][idx_last]+"‰");
           // 전주 비교 수치에서 양수/음수/보합 각각 Red/Blue/Green 입력
           if (jsonData.values[i][4][0] < 0){
             $(list_group[i]+" h4").attr('class', 'mb-0 text-primary');
           }else if(jsonData.values[i][4][0] > 0){
             $(list_group[i]+" h4").attr('class', 'mb-0 text-danger');
           }else{
             $(list_group[i]+" h4").attr('class', 'mb-0 text-success');
           }
           //각 증감 수치 입력
           var j = 0;
           while(j < 3){
             //증감 수
             var in_data = jsonData.values[i][3][j];
             $(item_group[i][j]).text("  "+in_data+" ");
             //증감율
             var in_per = jsonData.values[i][4][j];
             $(per_group[i][j]).text(" "+in_per+'%');
             if (in_per < 0){
               $(per_group[i][j]).attr('class', 'text-primary');
               $(tri_group[i][j]).attr('class', 'fa fa-fw fa-caret-down text-primary');
             }else if(in_per > 0){
               $(per_group[i][j]).attr('class', 'text-danger');
               $(tri_group[i][j]).attr('class', 'fa fa-fw fa-caret-up text-danger');
             }else{
               $(per_group[i][j]).attr('class', 'text-success');
               $(tri_group[i][j]).attr('class', 'fas fa-minus text-success');
             }
             j++;
           }
       }else{
           $(list_group[i]).attr('hidden', 'hidden');
       }
       i++;
     }
}


function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}
