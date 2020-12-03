var font_size = 11;
var colors = ["89,105,255", "255,64,123", "46,197,81", "255,199,80","124,252,000","238,232,170",
              "205,133,63","240,230,140","230,230,250","106,90,205","1,191,255","25,25,112",
              "64,224,208","220,20,60","167,128,80","80,110,200","20,120,68","10,40,128",
              "32,60,20","1,20,200","45,170,220","250,60,12","30,76,120","210,10,185"];
// metric-label d-inline-block float-right text-success
// metric-label d-inline-block float-right text-danger
// $( 'h1' ).attr( 'title', 'Hello' );
// fa fa-fw fa-caret-up


//각 스파클링 값(class mb-1 text-primary)
var spark1_current = $('#spark1_current');
var spark2_current = $('#spark2_current');
var spark3_current = $('#spark3_current');
var spark4_current = $('#spark4_current');
//증감 글자 색(i tag)
var spark1_font = $('#spark1_font');
var spark2_font = $('#spark2_font');
var spark3_font = $('#spark3_font');
var spark4_font = $('#spark4_font');
//증감 기호(i tag)
var spark1_tri = $('#spark1_tri');
var spark2_tri = $('#spark2_tri');
var spark3_tri = $('#spark3_tri');
var spark4_tri = $('#spark4_tri');
//증감 퍼센트(span tag)
var spark1_per = $('#spark1_value');
var spark2_per = $('#spark2_value');
var spark3_per = $('#spark3_value');
var spark4_per = $('#spark4_value');
//단말종합요약
var table_summary = $('#table_1');
var table_region = $('#table_2');
var table_category = $('#table_3');
var table_keyword = $('.chart-widget-list');
// $("#셀렉트박스ID option:selected").val();
// $("#셀렉트ID option:eq(1)").attr("selected", "selected");

var jsonData = null;
var offset = {};
var i = 0;
var size = 0;
var regular_status = null;

//한 번만 데이터 얻는다 얻음 From '/voc/chart/phpdata/index/jsonData_sparkline.php'
$.ajax({
  url:"/voc/chart/phpdata/index/jsonData_index.php",
  type:"post",
  dataType:"json",
  // data:{"startDate":startDateManu, "endDate":endDateManu, "manus":manus},
  async: false,
  success: function (data) {
    // console.log(data);
    jsonData = data;
    size = jsonData.index_term.length;
    regular_status = jsonData.values_setting[0];
  },
  error: function (request, status, error) {
    console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
  }
});

//페이지 시작 시 동작
$(function() {

  "use strict";
  $('html').removeClass('no-display');


 // ==============================================================
 // Spakline1 Cards
 // ==============================================================
  offset = {}
  i =0
  while(i< jsonData.index_week.length){
      offset[i] = jsonData.index_week[i]+' ('+jsonData.values_5G_rate[i]+')',
      i++;
    }
  $("#sparkline-1").sparkline(jsonData.values_5G_rate, {
        type: 'line',
        width: '100%',
        height: '100',
        lineColor: '#5969ff',
        fillColor: '#dbdeff',
        lineWidth: 2,
        spotColor: '#FF671B',
        minSpotColor: '#4B2AEB',
        maxSpotColor: '#E82A23',
        highlightSpotColor: undefined,
        highlightLineColor: undefined,
        //tooltip
        tooltipFormat: '{{offset:offset}}',
        tooltipValueLookups: {'offset': offset},
        resize:true
    });
  //증감 values
  spark1_current.text(jsonData.values_5G_rate[size-1]+'‰');
  spark1_per.text(jsonData.values_compared[0]+'%');

  //증감 symbol 및 color 조정
  if(jsonData.values_compared[0] < 0){
    spark1_current.attr('class', 'mb-1 text-primary');
    spark1_font.attr('class', 'metric-label d-inline-block float-right text-primary');
    spark1_tri.attr('class', 'fa fa-fw fa-caret-down');
  }else if(jsonData.values_compared[0] > 0){
    spark1_current.attr('class', 'mb-1 text-danger');
    spark1_font.attr('class', 'metric-label d-inline-block float-right text-danger');
    spark1_tri.attr('class', 'fa fa-fw fa-caret-up');
  }else{
    spark1_current.attr('class', 'mb-1 text-success');
    spark1_font.attr('class', 'metric-label d-inline-block float-right text-success');
    spark1_tri.attr('class', 'fa fa-fw fa-ellipsis-h');
  }

  // ==============================================================
  // Spakline2 Cards
  // ==============================================================
  offset = {}
  i =0
  while(i< jsonData.index_week.length){
    offset[i] = jsonData.index_week[i]+' ('+jsonData.values_LTE_rate[i]+')',
    i++;
  }
  $("#sparkline-2").sparkline(jsonData.values_LTE_rate, {
        type: 'line',
        width: '100%',
        height: '100',
        lineColor: '#ff407b',
        fillColor: '#ffdbe6',
        lineWidth: 2,
        spotColor: '#FF671B',
        minSpotColor: '#4B2AEB',
        maxSpotColor: '#E82A23',
        highlightSpotColor: undefined,
        highlightLineColor: undefined,
        //tooltip
        tooltipFormat: '{{offset:offset}}',
        tooltipValueLookups: {'offset': offset},
        resize:true
    });
  //증감 values
  spark2_current.text(jsonData.values_LTE_rate[size-1]+'‰');
  spark2_per.text(jsonData.values_compared[2]+'%');

  //증감 symbol 및 color 조정
  if(jsonData.values_compared[2] < 0){
    spark2_current.attr('class', 'mb-1 text-primary');
    spark2_font.attr('class', 'metric-label d-inline-block float-right text-primary');
    spark2_tri.attr('class', 'fa fa-fw fa-caret-down');
  }else if(jsonData.values_compared[2] > 0){
    spark2_current.attr('class', 'mb-1 text-danger');
    spark2_font.attr('class', 'metric-label d-inline-block float-right text-danger');
    spark2_tri.attr('class', 'fa fa-fw fa-caret-up');
  }else{
    spark2_current.attr('class', 'mb-1 text-success');
    spark2_font.attr('class', 'metric-label d-inline-block float-right text-success');
    spark2_tri.attr('class', 'fa fa-fw fa-ellipsis-h');
  }

  // ==============================================================
  // Spakline3 Cards
  // ==============================================================
  offset = {}
  i =0
  while(i< jsonData.index_week.length){
    offset[i] = jsonData.index_week[i]+' ('+jsonData.values_5G_voc[i]+')',
    i++;
  }
  $("#sparkline-3").sparkline(jsonData.values_5G_voc, {
        type: 'line',
        width: '100%',
        height: '100',
        lineColor: '#25d5f2',
        fillColor: '#dffaff',
        lineWidth: 2,
        spotColor: '#FF671B',
        minSpotColor: '#4B2AEB',
        maxSpotColor: '#E82A23',
        highlightSpotColor: undefined,
        highlightLineColor: undefined,
        //tooltip
        tooltipFormat: '{{offset:offset}}',
        tooltipValueLookups: {'offset':offset},
        resize:true
    });
  //증감 values
  spark3_current.text(numberWithCommas(jsonData.values_5G_voc[size-1])+'건');
  spark3_per.text(jsonData.values_compared[1]+'%');

  //증감 symbol 및 color 조정
  if(jsonData.values_compared[1] < 0){
    spark3_current.attr('class', 'mb-1 text-primary');
    spark3_font.attr('class', 'metric-label d-inline-block float-right text-primary');
    spark3_tri.attr('class', 'fa fa-fw fa-caret-down');
  }else if(jsonData.values_compared[1] > 0){
    spark3_current.attr('class', 'mb-1 text-danger');
    spark3_font.attr('class', 'metric-label d-inline-block float-right text-danger');
    spark3_tri.attr('class', 'fa fa-fw fa-caret-up');
  }else{
    spark3_current.attr('class', 'mb-1 text-success');
    spark3_font.attr('class', 'metric-label d-inline-block float-right text-success');
    spark3_tri.attr('class', 'fa fa-fw fa-ellipsis-h');
  }

  // ==============================================================
  // Spakline4 Cards
  // ==============================================================
  offset = {}
  i =0
  while(i< jsonData.index_week.length){
    offset[i] = jsonData.index_week[i]+' ('+jsonData.values_LTE_voc[i]+')',
    i++;
  }
  $("#sparkline-4").sparkline(jsonData.values_LTE_voc, {
        type: 'line',
        width: '100%',
        height: '100',
        lineColor: '#fec957',
        fillColor: '#fff2d5',
        lineWidth: 2,
        spotColor: '#FF671B',
        minSpotColor: '#4B2AEB',
        maxSpotColor: '#E82A23',
        highlightSpotColor: undefined,
        highlightLineColor: undefined,
        //tooltip
        tooltipFormat: '{{offset:offset}}',
        tooltipValueLookups: {'offset':offset},
        resize:true
    });
  //증감 values
  spark4_current.text(numberWithCommas(jsonData.values_LTE_voc[size-1])+'건');
  spark4_per.text(jsonData.values_compared[3]+'%');

  //증감 symbol 및 color 조정
  if(jsonData.values_compared[3] < 0){
    spark4_current.attr('class', 'mb-1 text-primary');
    spark4_font.attr('class', 'metric-label d-inline-block float-right text-primary');
    spark4_tri.attr('class', 'fa fa-fw fa-caret-down');
  }else if(jsonData.values_compared[3] > 0){
    spark4_current.attr('class', 'mb-1 text-danger');
    spark4_font.attr('class', 'metric-label d-inline-block float-right text-danger');
    spark4_tri.attr('class', 'fa fa-fw fa-caret-up');
  }else{
    spark4_current.attr('class', 'mb-1 text-success');
    spark4_font.attr('class', 'metric-label d-inline-block float-right text-success');
    spark4_tri.attr('class', 'fa fa-fw fa-ellipsis-h');
  }

  //////////////////////////////////////////////////////////////////// 단말종합요약 Table 생성 ////////////////////////////////////////////////////////////////////
  var str = '';
  i =0;

  while(i<jsonData.index_summary_devices.length){
    var count_cur = jsonData.values_summary[i][0];
    var count_pre = jsonData.values_summary[i][2];
    var returns_count = formater(count_cur, count_pre, true);

    var sub_cur = jsonData.values_summary[i][1];
    var sub_pre = jsonData.values_summary[i][3];
    var returns_sub = formater(sub_cur, sub_pre, true);

    var rate_cur = jsonData.values_summary[i][4];
    var rate_pre = jsonData.values_summary[i][5];
    var returns_rate = formater(rate_cur, rate_pre, false);

    var compare = jsonData.values_summary[i][6];
    var returns_compare = formater2(compare);
    var returns_status = formater3(compare, rate_pre, regular_status);

    str += '<tr>';
    //구분
    str += '<td class="text-left">'+jsonData.index_summary_devices[i]+'</td>';
    //건수
    str += '<td class="text-left">';
    str += '<div class="metric-label d-inline-block float-right '+returns_count[2]+'">';
    str += '<span class="text-dark">'+returns_count[0]+'</span><span>'+returns_count[1]+'</span></div></td>';
    //가입자
    str += '<td class="text-left">';
    str += '<div class="metric-label d-inline-block float-right '+returns_sub[2]+'">';
    str += '<span class="text-dark">'+returns_sub[0]+'</span><span>'+returns_sub[1]+'</span></div></td>';
    //비율
    str += '<td class="text-left">';
    str += '<div class="metric-label d-inline-block float-right '+returns_rate[2]+'">';
    str += '<span class="text-dark">'+returns_rate[0]+'</span><span>'+returns_rate[1]+'</span></div></td>';
    //증감
    str += '<td class="text-left">';
    str += '<div class="metric-label d-inline-block float-right '+returns_compare[1]+'">';
    str += '<i class="fa fa-fw '+returns_compare[2]+'"></i>';
    str += '<span>'+returns_compare[0]+'</span></div></td>';
    //상태
    str += '<td class="text-left">';
    str += '<div class="metric-label d-inline-block float-right '+returns_status[1]+'">';
    str += '<i class="m-r-10 '+returns_status[2]+'"></i>';
    str += '<span>'+returns_status[0]+'</span></div></td>';
    i++;
  }
  str += '</tr>'
  table_summary.append(str);

  //////////////////////////////////////////////////////////////////// 지역종합요약 Table 생성 ////////////////////////////////////////////////////////////////////
  str = '';
  i =0;

  while(i<jsonData.index_base.length){
    var count_5G_cur = jsonData.values_base[i][0];
    var count_5G_pre = jsonData.values_base[i][2];
    var returns_5G = formater(count_5G_cur, count_5G_pre, true);

    var count_LTE_cur = jsonData.values_base[i][1];
    var count_LTE_pre = jsonData.values_base[i][3];
    var returns_LTE = formater(count_LTE_cur, count_LTE_pre, true);

    var compare_5G = jsonData.values_base[i][4];
    var returns_compare_5G = formater2(compare_5G);

    var compare_LTE = jsonData.values_base[i][5];
    var returns_compare_LTE = formater2(compare_LTE);

    var returns_region_status = formater3(compare_5G, count_5G_pre, regular_status);

    str += '<tr>';
    //구분
    str += '<td class="text-left">'+jsonData.index_base[i]+'</td>';
    //5G 건수
    str += '<td class="text-left">';
    str += '<div class="metric-label d-inline-block float-right '+returns_5G[2]+'">';
    str += '<span class="text-dark">'+returns_5G[0]+'</span><span>'+returns_5G[1]+'</span></div></td>';
    //LTE 건수
    str += '<td class="text-left">';
    str += '<div class="metric-label d-inline-block float-right '+returns_LTE[2]+'">';
    str += '<span class="text-dark">'+returns_LTE[0]+'</span><span>'+returns_LTE[1]+'</span></div></td>';
    //5G증감
    str += '<td class="text-left">';
    str += '<div class="metric-label d-inline-block float-right '+returns_compare_5G[1]+'">';
    str += '<i class="fa fa-fw '+returns_compare_5G[2]+'"></i>';
    str += '<span>'+returns_compare_5G[0]+'</span></div></td>';
    //LTE증감
    str += '<td class="text-left">';
    str += '<div class="metric-label d-inline-block float-right '+returns_compare_LTE[1]+'">';
    str += '<i class="fa fa-fw '+returns_compare_LTE[2]+'"></i>';
    str += '<span>'+returns_compare_LTE[0]+'</span></div></td>';
    //상태
    str += '<td class="text-left">';
    str += '<div class="metric-label d-inline-block float-right '+returns_region_status[1]+'">';
    str += '<i class="m-r-10 '+returns_region_status[2]+'"></i>';
    str += '<span>'+returns_region_status[0]+'</span></div></td>';
    i++;
  }
  str += '</tr>'
  table_region.append(str);



  //////////////////////////////////////////////////////////////////// 카테고리종합요약 Table 생성 ////////////////////////////////////////////////////////////////////
  str = '';
  i =0;

  while(i<jsonData.index_category.length){
    var count_cur = jsonData.values_category[i][0];
    var count_pre = jsonData.values_category[i][1];
    var returns_voc = formater(count_cur, count_pre, true);

    var compare = jsonData.values_category[i][2];
    var returns_compare = formater2(compare);

    var returns_category_status = formater3(compare, count_pre, regular_status);

    str += '<tr>';
    //카테고리
    str += '<td class="text-left">'+jsonData.index_category[i]+'</td>';
    //건수
    str += '<td class="text-left">';
    str += '<div class="metric-label d-inline-block float-right '+returns_voc[2]+'">';
    str += '<span class="text-dark">'+returns_voc[0]+'</span><span>'+returns_voc[1]+'</span></div></td>';
    //증감
    str += '<td class="text-left">';
    str += '<div class="metric-label d-inline-block float-right '+returns_compare[1]+'">';
    str += '<i class="fa fa-fw '+returns_compare[2]+'"></i>';
    str += '<span>'+returns_compare[0]+'</span></div></td>';
    //상태
    str += '<td class="text-left">';
    str += '<div class="metric-label d-inline-block float-right '+returns_category_status[1]+'">';
    str += '<i class="m-r-10 '+returns_category_status[2]+'"></i>';
    str += '<span>'+returns_category_status[0]+'</span></div></td>';
    i++;
  }
  str += '</tr>'
  table_category.append(str);

  // ==============================================================
  // total keyword
  // ==============================================================
  var ctx = document.getElementById("total_keyword").getContext('2d');

  //each chart initiation
  if(window.chart != undefined){
    window.chart.destroy();
  }

  i = 0;
  var backgrounds = new Array();
  while(i< jsonData.values_extract.length){
    backgrounds.push("rgba("+colors[i]+", 0.8)");
    i++;
  }
  window.chart = new Chart(ctx, {
                type: 'doughnut',

                data: {
                    labels: jsonData.index_extract,
                    datasets: [{
                        backgroundColor: backgrounds,
                        data: jsonData.values_extract
                    }]
                },
                options: {
                    legend: {
                        display: false
                    }
                }
            });

});

//sparkline chart resizing
function sparkline(){
  // ==============================================================
  // Spakline1 Cards
  // ==============================================================
   offset = {}
   i =0
   while(i< jsonData.index_week.length){
       offset[i] = jsonData.index_week[i]+' ('+jsonData.values_5G_rate[i]+')',
       i++;
     }
   $("#sparkline-1").sparkline(jsonData.values_5G_rate, {
         type: 'line',
         width: '100%',
         height: '100',
         lineColor: '#5969ff',
         fillColor: '#dbdeff',
         lineWidth: 2,
         spotColor: '#FF671B',
         minSpotColor: '#4B2AEB',
         maxSpotColor: '#E82A23',
         highlightSpotColor: undefined,
         highlightLineColor: undefined,
         //tooltip
         tooltipFormat: '{{offset:offset}}',
         tooltipValueLookups: {'offset': offset},
         resize:true
     });
   //증감 values
   spark1_current.text(jsonData.values_5G_rate[size-1]+'‰');
   spark1_per.text(jsonData.values_compared[0]+'%');

   //증감 symbol 및 color 조정
   if(jsonData.values_compared[0] < 0){
     spark1_current.attr('class', 'mb-1 text-primary');
     spark1_font.attr('class', 'metric-label d-inline-block float-right text-primary');
     spark1_tri.attr('class', 'fa fa-fw fa-caret-down');
   }else if(jsonData.values_compared[0] > 0){
     spark1_current.attr('class', 'mb-1 text-danger');
     spark1_font.attr('class', 'metric-label d-inline-block float-right text-danger');
     spark1_tri.attr('class', 'fa fa-fw fa-caret-up');
   }else{
     spark1_current.attr('class', 'mb-1 text-success');
     spark1_font.attr('class', 'metric-label d-inline-block float-right text-success');
     spark1_tri.attr('class', 'fa fa-fw fa-ellipsis-h');
   }

   // ==============================================================
   // Spakline2 Cards
   // ==============================================================
   offset = {}
   i =0
   while(i< jsonData.index_week.length){
     offset[i] = jsonData.index_week[i]+' ('+jsonData.values_LTE_rate[i]+')',
     i++;
   }
   $("#sparkline-2").sparkline(jsonData.values_LTE_rate, {
         type: 'line',
         width: '100%',
         height: '100',
         lineColor: '#ff407b',
         fillColor: '#ffdbe6',
         lineWidth: 2,
         spotColor: '#FF671B',
         minSpotColor: '#4B2AEB',
         maxSpotColor: '#E82A23',
         highlightSpotColor: undefined,
         highlightLineColor: undefined,
         //tooltip
         tooltipFormat: '{{offset:offset}}',
         tooltipValueLookups: {'offset': offset},
         resize:true
     });
   //증감 values
   spark2_current.text(jsonData.values_LTE_rate[size-1]+'‰');
   spark2_per.text(jsonData.values_compared[2]+'%');

   //증감 symbol 및 color 조정
   if(jsonData.values_compared[2] < 0){
     spark2_current.attr('class', 'mb-1 text-primary');
     spark2_font.attr('class', 'metric-label d-inline-block float-right text-primary');
     spark2_tri.attr('class', 'fa fa-fw fa-caret-down');
   }else if(jsonData.values_compared[2] > 0){
     spark2_current.attr('class', 'mb-1 text-danger');
     spark2_font.attr('class', 'metric-label d-inline-block float-right text-danger');
     spark2_tri.attr('class', 'fa fa-fw fa-caret-up');
   }else{
     spark2_current.attr('class', 'mb-1 text-success');
     spark2_font.attr('class', 'metric-label d-inline-block float-right text-success');
     spark2_tri.attr('class', 'fa fa-fw fa-ellipsis-h');
   }

   // ==============================================================
   // Spakline3 Cards
   // ==============================================================
   offset = {}
   i =0
   while(i< jsonData.index_week.length){
     offset[i] = jsonData.index_week[i]+' ('+jsonData.values_5G_voc[i]+')',
     i++;
   }
   $("#sparkline-3").sparkline(jsonData.values_5G_voc, {
         type: 'line',
         width: '100%',
         height: '100',
         lineColor: '#25d5f2',
         fillColor: '#dffaff',
         lineWidth: 2,
         spotColor: '#FF671B',
         minSpotColor: '#4B2AEB',
         maxSpotColor: '#E82A23',
         highlightSpotColor: undefined,
         highlightLineColor: undefined,
         //tooltip
         tooltipFormat: '{{offset:offset}}',
         tooltipValueLookups: {'offset':offset},
         resize:true
     });
   //증감 values
   spark3_current.text(numberWithCommas(jsonData.values_5G_voc[size-1])+'건');
   spark3_per.text(jsonData.values_compared[1]+'%');

   //증감 symbol 및 color 조정
   if(jsonData.values_compared[1] < 0){
     spark3_current.attr('class', 'mb-1 text-primary');
     spark3_font.attr('class', 'metric-label d-inline-block float-right text-primary');
     spark3_tri.attr('class', 'fa fa-fw fa-caret-down');
   }else if(jsonData.values_compared[1] > 0){
     spark3_current.attr('class', 'mb-1 text-danger');
     spark3_font.attr('class', 'metric-label d-inline-block float-right text-danger');
     spark3_tri.attr('class', 'fa fa-fw fa-caret-up');
   }else{
     spark3_current.attr('class', 'mb-1 text-success');
     spark3_font.attr('class', 'metric-label d-inline-block float-right text-success');
     spark3_tri.attr('class', 'fa fa-fw fa-ellipsis-h');
   }

   // ==============================================================
   // Spakline4 Cards
   // ==============================================================
   offset = {}
   i =0
   while(i< jsonData.index_week.length){
     offset[i] = jsonData.index_week[i]+' ('+jsonData.values_LTE_voc[i]+')',
     i++;
   }
   $("#sparkline-4").sparkline(jsonData.values_LTE_voc, {
         type: 'line',
         width: '100%',
         height: '100',
         lineColor: '#fec957',
         fillColor: '#fff2d5',
         lineWidth: 2,
         spotColor: '#FF671B',
         minSpotColor: '#4B2AEB',
         maxSpotColor: '#E82A23',
         highlightSpotColor: undefined,
         highlightLineColor: undefined,
         //tooltip
         tooltipFormat: '{{offset:offset}}',
         tooltipValueLookups: {'offset':offset},
         resize:true
     });
   //증감 values
   spark4_current.text(numberWithCommas(jsonData.values_LTE_voc[size-1])+'건');
   spark4_per.text(jsonData.values_compared[3]+'%');

   //증감 symbol 및 color 조정
   if(jsonData.values_compared[3] < 0){
     spark4_current.attr('class', 'mb-1 text-primary');
     spark4_font.attr('class', 'metric-label d-inline-block float-right text-primary');
     spark4_tri.attr('class', 'fa fa-fw fa-caret-down');
   }else if(jsonData.values_compared[3] > 0){
     spark4_current.attr('class', 'mb-1 text-danger');
     spark4_font.attr('class', 'metric-label d-inline-block float-right text-danger');
     spark4_tri.attr('class', 'fa fa-fw fa-caret-up');
   }else{
     spark4_current.attr('class', 'mb-1 text-success');
     spark4_font.attr('class', 'metric-label d-inline-block float-right text-success');
     spark4_tri.attr('class', 'fa fa-fw fa-ellipsis-h');
   }

}

//단말종합요약 테이블
function sum_update(){

  var json = null;
  var netType = $("#selectNet1 option:selected").val();
  var avgType = $("#selectAvg1 option:selected").val();
  // POST summary data connect
  $.ajax({
      url : "/voc/chart/phpdata/index/jsonData_summary.php",
      data: {"netType":netType, "avgType":avgType},
      type : 'post',
      async: false,
      success: function (data) {
        // console.log(data);
        json = data;
        if(avgType == "1"){
          regular_status = json.values_setting[0];
        }else if(avgType == "2"){
          regular_status = json.values_setting[1];
        }else{
          regular_status = json.values_setting[2];
        }
      },
      error: function (request, status, error) {
        console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
      }
  });

  //이전 요소 삭제
  table_summary.empty();
  // 단말종합요약 Table 새로 생성
  var str = '';
  i =0;
  while(i<json.index_summary_devices.length){
    var count_cur = json.values_summary[i][0];
    var count_pre = json.values_summary[i][2];
    var returns_count = formater(count_cur, count_pre, true);

    var sub_cur = json.values_summary[i][1];
    var sub_pre = json.values_summary[i][3];
    var returns_sub = formater(sub_cur, sub_pre, true);

    var rate_cur = json.values_summary[i][4];
    var rate_pre = json.values_summary[i][5];
    var returns_rate = formater(rate_cur, rate_pre, false);

    var compare = json.values_summary[i][6];
    var returns_compare = formater2(compare);
    var returns_status = formater3(compare, rate_pre, regular_status);

    str += '<tr>';
    //구분
    str += '<td class="text-left">'+json.index_summary_devices[i]+'</td>';
    //건수
    str += '<td class="text-left">';
    str += '<div class="metric-label d-inline-block float-right '+returns_count[2]+'">';
    str += '<span class="text-dark">'+returns_count[0]+'</span><span>'+returns_count[1]+'</span></div></td>';
    //가입자
    str += '<td class="text-left">';
    str += '<div class="metric-label d-inline-block float-right '+returns_sub[2]+'">';
    str += '<span class="text-dark">'+returns_sub[0]+'</span><span>'+returns_sub[1]+'</span></div></td>';
    //비율
    str += '<td class="text-left">';
    str += '<div class="metric-label d-inline-block float-right '+returns_rate[2]+'">';
    str += '<span class="text-dark">'+returns_rate[0]+'</span><span>'+returns_rate[1]+'</span></div></td>';
    //증감
    str += '<td class="text-left">';
    str += '<div class="metric-label d-inline-block float-right '+returns_compare[1]+'">';
    str += '<i class="fa fa-fw '+returns_compare[2]+'"></i>';
    str += '<span>'+returns_compare[0]+'</span></div></td>';
    //상태
    str += '<td class="text-left">';
    str += '<div class="metric-label d-inline-block float-right '+returns_status[1]+'">';
    str += '<i class="m-r-10 '+returns_status[2]+'"></i>';
    str += '<span>'+returns_status[0]+'</span></div></td>';
    i++;
  }
  str += '</tr>'
  table_summary.append(str);
}

//지역종합요약
function region_update(){

  var json = null;
  var netType = $("#selectNet2 option:selected").val();
  var avgType = $("#selectAvg2 option:selected").val();
  // POST summary data connect
  $.ajax({
      url : "/voc/chart/phpdata/index/jsonData_region.php",
      data: {"netType":netType, "avgType":avgType},
      type : 'post',
      async: false,
      success: function (data) {
        // console.log(data);
        json = data;
        if(avgType == "1"){
          regular_status = json.values_setting[0];
        }else if(avgType == "2"){
          regular_status = json.values_setting[1];
        }else{
          regular_status = json.values_setting[2];
        }
      },
      error: function (request, status, error) {
        console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
      }
  });

  //이전 요소 삭제
  table_region.empty();
  // 단말종합요약 Table 새로 생성
  var str = '';
  i =0;
  while(i<json.index_base.length){
    var count_5G_cur = json.values_base[i][0];
    var count_5G_pre = json.values_base[i][2];
    var returns_5G = formater(count_5G_cur, count_5G_pre, true);

    var count_LTE_cur = json.values_base[i][1];
    var count_LTE_pre = json.values_base[i][3];
    var returns_LTE = formater(count_LTE_cur, count_LTE_pre, true);

    var compare_5G = json.values_base[i][4];
    var returns_compare_5G = formater2(compare_5G);

    var compare_LTE = json.values_base[i][5];
    var returns_compare_LTE = formater2(compare_LTE);

    var returns_region_status= null;
    if(netType == "5G"){
      returns_region_status = formater3(compare_5G, count_5G_pre, regular_status);
    }else{
      returns_region_status = formater3(compare_LTE, count_LTE_pre, regular_status);
    }

    str += '<tr>';
    //구분
    str += '<td class="text-left">'+json.index_base[i]+'</td>';
    //5G 건수
    str += '<td class="text-left">';
    str += '<div class="metric-label d-inline-block float-right '+returns_5G[2]+'">';
    str += '<span class="text-dark">'+returns_5G[0]+'</span><span>'+returns_5G[1]+'</span></div></td>';
    //LTE 건수
    str += '<td class="text-left">';
    str += '<div class="metric-label d-inline-block float-right '+returns_LTE[2]+'">';
    str += '<span class="text-dark">'+returns_LTE[0]+'</span><span>'+returns_LTE[1]+'</span></div></td>';
    //5G증감
    str += '<td class="text-left">';
    str += '<div class="metric-label d-inline-block float-right '+returns_compare_5G[1]+'">';
    str += '<i class="fa fa-fw '+returns_compare_5G[2]+'"></i>';
    str += '<span>'+returns_compare_5G[0]+'</span></div></td>';
    //LTE증감
    str += '<td class="text-left">';
    str += '<div class="metric-label d-inline-block float-right '+returns_compare_LTE[1]+'">';
    str += '<i class="fa fa-fw '+returns_compare_LTE[2]+'"></i>';
    str += '<span>'+returns_compare_LTE[0]+'</span></div></td>';
    //상태
    str += '<td class="text-left">';
    str += '<div class="metric-label d-inline-block float-right '+returns_region_status[1]+'">';
    str += '<i class="m-r-10 '+returns_region_status[2]+'"></i>';
    str += '<span>'+returns_region_status[0]+'</span></div></td>';
    i++;
  }
  str += '</tr>'
  table_region.append(str);
}

//카테고리종합요약
function category_update(){

  var json = null;
  var netType = $("#selectNet3 option:selected").val();
  var avgType = $("#selectAvg3 option:selected").val();
  // POST summary data connect
  $.ajax({
      url : "/voc/chart/phpdata/index/jsonData_category.php",
      data: {"netType":netType, "avgType":avgType},
      type : 'post',
      async: false,
      success: function (data) {
        // console.log(data);
        json = data;
        if(avgType == "1"){
          regular_status = json.values_setting[0];
        }else if(avgType == "2"){
          regular_status = json.values_setting[1];
        }else{
          regular_status = json.values_setting[2];
        }
      },
      error: function (request, status, error) {
        console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
      }
  });

  //이전 요소 삭제
  table_category.empty();
  // 단말종합요약 Table 새로 생성
  str = '';
  i =0;

  while(i<json.index_category.length){
    var count_cur = json.values_category[i][0];
    var count_pre = json.values_category[i][1];
    var returns_voc = formater(count_cur, count_pre, true);

    var compare = json.values_category[i][2];
    var returns_compare = formater2(compare);

    var returns_category_status = formater3(compare, count_pre, regular_status);

    str += '<tr>';
    //카테고리
    str += '<td class="text-left">'+json.index_category[i]+'</td>';
    //건수
    str += '<td class="text-left">';
    str += '<div class="metric-label d-inline-block float-right '+returns_voc[2]+'">';
    str += '<span class="text-dark">'+returns_voc[0]+'</span><span>'+returns_voc[1]+'</span></div></td>';
    //증감
    str += '<td class="text-left">';
    str += '<div class="metric-label d-inline-block float-right '+returns_compare[1]+'">';
    str += '<i class="fa fa-fw '+returns_compare[2]+'"></i>';
    str += '<span>'+returns_compare[0]+'</span></div></td>';
    //상태
    str += '<td class="text-left">';
    str += '<div class="metric-label d-inline-block float-right '+returns_category_status[1]+'">';
    str += '<i class="m-r-10 '+returns_category_status[2]+'"></i>';
    str += '<span>'+returns_category_status[0]+'</span></div></td>';
    i++;
  }
  str += '</tr>'
  table_category.append(str);
}

//키워드종합요약
function keyword_update(){

    var json = null;
    var baseDate = $('#baseDate').val();
    var netType = $("#selectNet4 option:selected").val();
    var cateType = $("#selectCate option:selected").val();
    // POST summary data connect
    $.ajax({
        url : "/voc/chart/phpdata/index/jsonData_keyword.php",
        data: {"baseDate":baseDate, "netType":netType, "cateType":cateType},
        type : 'post',
        async: false,
        success: function (data) {
          console.log(data);
          json = data;
        },
        error: function (request, status, error) {
          console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
        }
    });

    // ==============================================================
    // total keyword
    // ==============================================================
    var ctx = document.getElementById("total_keyword").getContext('2d');

    //each chart initiation
    if(window.chart != undefined){
      window.chart.destroy();
    }

    i = 0;
    var backgrounds = new Array();
    while(i< json.values_extract.length){
      backgrounds.push("rgba("+colors[i]+", 0.8)");
      i++;
    }

    window.chart = new Chart(ctx, {
                  type: 'doughnut',

                  data: {
                      labels: json.index_extract,
                      datasets: [{
                          backgroundColor: backgrounds,
                          data: json.values_extract
                      }]
                  },
                  options: {
                      legend: {
                          display: false
                      }
                  }
              });

}

//table 데이터 일반 포맷
function formater(cur, pre, flag){
  var returns = [];
  if(flag){
    if(cur - pre > 0){
      returns.push(numberWithCommas(cur));
      returns.push(' (+'+numberFits(cur-pre)+')');
      returns.push('text-danger');
    }else if(cur - pre < 0){
      returns.push(numberWithCommas(cur));
      returns.push(' ('+numberFits(cur-pre)+')');
      returns.push('text-primary');
    }else{
      returns.push(numberWithCommas(cur));
      returns.push(' (--)');
      returns.push('text-success');
    }
  }else{
    if(cur - pre > 0){
      returns.push(cur);
      returns.push(' (+'+numberFits(cur-pre)+')');
      returns.push('text-danger');
    }else if(cur - pre < 0){
      returns.push(cur);
      returns.push(' ('+numberFits(cur-pre)+')');
      returns.push('text-primary');
    }else{
      returns.push(cur);
      returns.push(' (--)');
      returns.push('text-success');
    }
  }

  return returns;
}

//table 데이터 증감율 포맷
function formater2(x){
  var returns = [];
  if(x > 0){
    returns.push(String(x)+'%');
    returns.push('text-danger');
    returns.push('fa-caret-up');
  }else if(x < 0){
    returns.push(String(x)+'%');
    returns.push('text-primary');
    returns.push('fa-caret-down');
  }else{
    returns.push('0%');
    returns.push('text-success');
    returns.push('fa-ellipsis-h');
  }
  return returns;
}

//table 기준값 비교 상태값 포맷
function formater3(x, pre, values){

  var returns = [];
  if (pre > 0){
    if(x < values[0]){
      returns.push('양호');
      returns.push('text-primary');
      returns.push('wi wi-day-sunny');
    }else if(x >= values[1] && x <= values[2]){
      returns.push('주의');
      returns.push('text-warning');
      returns.push('wi wi-day-cloudy');
    }else{
      returns.push('심각');
      returns.push('text-danger');
      returns.push('wi wi-storm-showers');
    }
  }else{
    returns.push('N/A');
    returns.push('text-success');
    // returns.push('wi wi-na');
    returns.push('wi wi-stars')
  }
  return returns;
}

//Number 소수점 여부 확인 후 return
function numberFits(x){

  if(Number.isInteger(x)){
    return x;
  }else{
    return x.toFixed(4);
  }
}

//콤마 insert function
function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}
