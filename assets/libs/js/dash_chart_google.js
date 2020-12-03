
// Load the Visualization API and the piechart package.
google.charts.load('current', {'packages':['corechart']});
google.charts.load('current', {'packages':['table']});
// Set a callback to run when the Google Visualization API is loaded.
google.charts.setOnLoadCallback(drawChart);
var myWindow = null;
var jsonDataCate1 = $.ajax({
                            url:"/voc/phpdata/chart/jsonDataCate1.php",
                            dataType:"json",
                            async: false
                          }).responseText;

var jsonDataTerm1 = $.ajax({
                            url:"/voc/phpdata/chart/jsonDataTerm1.php",
                            dataType:"json",
                            async: false
                            }).responseText;

var jsonDataModel1 = $.ajax({
                            url:"/voc/phpdata/chart/jsonDataCate1.php",
                            dataType:"json",
                            async: false
                            }).responseText;

var jsonDataManu1 = $.ajax({
                            url:"/voc/phpdata/chart/jsonDataCate1.php",
                            dataType:"json",
                            async: false
                            }).responseText;

var jsonDataTable1 = $.ajax({
                            url:"/voc/phpdata/chart/jsonDataTable1.php",
                            dataType:"json",
                            async: false
                            }).responseText;

function drawChart(){
    var data1 = new google.visualization.DataTable(jsonDataCate1);
    var data2 = new google.visualization.DataTable(jsonDataTerm1);
    var data3 = new google.visualization.DataTable(jsonDataModel1);
    var data4 = new google.visualization.DataTable(jsonDataManu1);
    var data5 = new google.visualization.DataTable(jsonDataTable1);

    var options1 = {
                      height:'100%',
                      width: '98%',
                      pieHole: 0.4,
                      slices: {
                              // 0: { color: '1294BF'},
                              // 1: { color: '18C6FF'},
                              // 2: { color: '0C637F'},
                              // 3: { color: '063140'},
                              0: { color: '41848F'},
                              1: { color: '72A7A3'},
                              2: { color: '97C0B7'},
                              3: { color: 'EEE9D1'},
                            },
                      chartArea:{left:5,top:5,right:5,bottom:20,width:'100%',height:'100%'},
                      legend:{ position:'bottom', alignment:'center', textStyle:{color:'balck'}},
                      // is3D: true,
                      backgroundColor :{fill:'fff'},
                      pieResidueSliceColor: 'white'
                  };

    var options2 = {
                     height:'100%',
                     width: '98%',
                     showNumber:true,
                     backgroundColor :{fill:'fff'}
                   };

    var options3 = {
                    height:'100%',
                    width: '98%',
                    isStacked: true,
                    hAxis:{baselineColor:'black',textStyle:{fontSize:12,color:'black'}},
                    series: {
                            // 0: { color: '1294BF'},
                            // 1: { color: '18C6FF'},
                            // 2: { color: '0C637F'},
                            // 3: { color: '0D11BF'},
                            0: { color: '41848F'},
                            1: { color: '72A7A3'},
                            2: { color: '97C0B7'},
                            3: { color: 'EEE9D1'},
                    },
                    legend: {position: 'top', maxLines: 1, textStyle: {fontSize: 12,color:'black'}},
                    vAxis:{
        //                        baselineColor: '#fff',
        //                        gridlineColor: '#fff',
        //                        textPosition: 'none',
                            baselineColor:'white',
                            textStyle:{fontSize:12, color:'black'},
                            gridlines:{count: 4}
                    },
                    backgroundColor :{fill:'fff'}
                  };

    var options4 = {
                    height:'100%',
                    width: '98%',
                    chartArea:{left:5,top:5,right:5,bottom:20,width:'100%',height:'100%'},
                    legend:{ position:'bottom', alignment:'center', textStyle:{color:'black'}},
                    slices: {
                            // 0: { color: '1294BF'},
                            // 1: { color: '18C6FF'},
                            // 2: { color: '0C637F'},
                            // 3: { color: '063140'},
                            0: { color: '41848F'},
                            1: { color: '72A7A3'},
                            2: { color: '97C0B7'},
                            3: { color: 'EEE9D1'},
                    },
                    is3D: true,
                    backgroundColor :{fill:'fff'},
                    pieResidueSliceColor: 'black'
                  };

    var options5 = {
                    height:'100%',
                    width: '100%',
                    showNumber:true,
                    allowHtml:true,
                    page: 'enable',
                    pageSize: 50,
                    pagingSymbols: {prev: 'prev',next: 'next'},
                    backgroundColor :{fill:'fff'}
                    };

    var chart1 = new google.visualization.PieChart(document.getElementById('sparkline-revenue'));
    var chart2 = new google.visualization.LineChart(document.getElementById('sparkline-revenue2'));
    var chart3 = new google.visualization.ColumnChart(document.getElementById('sparkline-revenue3'));
    var chart4 = new google.visualization.PieChart(document.getElementById('sparkline-revenue4'));
    var chart5 = new google.visualization.Table(document.getElementById('sparkline-revenue5'));
    // Wait for the chart to finish drawing before calling the getImageURI() method.
    google.visualization.events.addListener(chart1, 'ready', function () {
      var imgUrl = chart1.getImageURI();
      document.getElementById('revenue_img1').src = imgUrl;
    });

    chart1.draw(data1, options1);
    chart2.draw(data2, options2);
    chart3.draw(data3, options3);
    chart4.draw(data4, options4);
    chart5.draw(data5, options5);
}
function downloadImg() {
  var divText = document.getElementById("revenue_img1").outerHTML;
  myWindow = window.open('', '', 'width=700,height=700');
  var doc = myWindow.document;
  doc.write('<img' + ' id="graficar" ' +' />');
  doc.write(divText);
  doc.close();
}
