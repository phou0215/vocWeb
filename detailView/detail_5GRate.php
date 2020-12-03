<?php
    session_start();
    if(!$_SESSION){
        session_start();
        if(isset($_SESSION['is_login'])!= true){
          echo '<script>
                  alert("접속을 위해 로그인이 필요합니다.");
                  location.href="/voc/phpdata/signin.php"
                </script>';}}

    require_once($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
    $conn = mysqli_connect($config['host'],$config['user'],$config['password']);
    mysqli_select_db($conn, $config['database']);

    $model_flag = "";
    $sql_model = "";
    $model_type = "";
    $result = mysqli_query($conn, "SELECT * FROM settings");
    $row = mysqli_fetch_array($result);
    $model_flag = $row['deviceFlag'];

    if($model_flag == '0'){
      $sql_model = "SELECT * FROM voc_models WHERE flag='1' AND cellType='5G' ORDER BY focusOn DESC, model ASC";
      $model_type = "voc_models";
    }else{
      $sql_model = "SELECT * FROM voc_models2 WHERE flag='1' AND cellType='5G' ORDER BY focusOn DESC, model ASC";
      $model_type = "voc_models2";
    }

    $sql_date = 'SELECT regiDate FROM voc_tot_data ORDER BY regiDate DESC LIMIT 1';
    $result_date = mysqli_query($conn, $sql_date);
    $row_date = mysqli_fetch_assoc($result_date);

    $result_model = mysqli_query($conn, $sql_model);
    // $models = array();
    // while($row = mysqli_fetch_assoc($result_model)){
    //       array_push($models, $row['model']);
    // }
    mysqli_close($conn);
 ?>
<!doctype html>
<html lang="en" class='no-display'>

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/voc/assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/voc/assets/vendor/fonts/circular-std/style.css" >
    <link rel="stylesheet" href="/voc/assets/libs/css/style.css">
    <!-- <link href="http://fonts.googleapis.com/earlyaccess/jejugothic.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="/voc/assets/vendor/multi-select-min/css/bootstrap-select-min.css">
    <link rel="stylesheet" href="/voc/assets/vendor/fonts/fontawesome/css/fontawesome-all.css">
    <title>5G Rate detail chart</title>
</head>

<body>
    <!-- ============================================================== -->
    <!-- main wrapper -->
    <!-- ============================================================== -->
    <!-- <div class="row">
      <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
          <h3 class="pageheader-title" style="color:blue;">5G Rate 상세</h3>
          <p class="pageheader-text"></p>
          <p class="pageheader-text"></p>
          <p class="pageheader-text"></p>
        </div>
      </div>
    </div> -->

    <div class="row">
      <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="card">
          <div id='searchForm' class="card-body" style="padding-right:10px;">
            <!-- onsubmit="return checkValue();" -->
            <ul id="searchList">
              <li>
                <select id="selectType" class="selectpicker" style="font-size:10px; margin-left:10px;" data-style="btn-primary" data-width:"100px">
                  <option value="voc_tot_data" selected="selected">통품전체</option>
                  <option value="voc_sort_data">불만성전체</option>
                </select>
              </li>
              <li>
                <input id="modelType" type="text" hidden name="" value="<?php echo $model_type ?>">
                <!-- data-actions-box="true" -->
                <select id="selectModel" class="selectpicker" style="font-size:10px; margin-left:5px;" multiple data-live-search="true" data-size="6" data-max-options="4" data-style="btn-primary" data-width="100px">
                <?php
                      echo "<option value='total_focus' class='multiple' selected='selected'>5G관심</option>";
                      echo "<option value='total_hole' class='multiple' selected='selected'>5G전체</option>";
                      while($row=mysqli_fetch_array($result_model)){
                        echo "<option value='".$row['model']."' class='multiple'>".substr($row['model'], 0, 50)."</option>";
                      }
                ?>
                </select>
              </li>
              <li>
                <input id="startDate" type="date" style="height:41px; width:130px; margin-left:5px;" hidden="hidden" value="<?php echo date("Y-m-d", strtotime($row_date['regiDate']."- 1 month"));?>">
              </li>
              <li>
                <input id="endDate" type="date" style="height:41px; width:130px; margin-left:5px;"  hidden="hidden" value="<?php echo date("Y-m-d", strtotime($row_date['regiDate']));?>">
              </li>
              <li>
                <button id='btn_update' type="button" class="btn btn-success" onclick="update();" style=" height:41px; width:160px; margin-left:5px; margin-right:10px;">OK</button>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <!-- ============================ first chart row Total VOC and Term Rate ============================ -->
    <div class="row">
      <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="card">
          <div class="card-body">
            <!-- height="60" -->
            <canvas id="chartjs_line1" height=100></canvas>
          </div>
          <div class="row">

            <div id="list_group1" hidden="hidden" class="card col-xl-3 col-lg-3 col-md-3 col-sm-3 col-3">
                <div class="card-header d-flex">
                  <h5 class="mb-0" style="margin-right:5px;"></h5>
                  <h4 class="mb-0 text-primary" style="font-weight:bold;"></h4>
                </div>
                <div class="card-body">
                  <div class="list-group">
                    <p style="font-size:12px;">전주 :<span id="list_item1-1"></span><i id="list_tri1-1" class="fa fa-fw fa-caret-down text-danger"></i><span id="list_per1-1" class='text-danger'></span></p>
                    <p style="font-size:12px;">평일 평균 :<span id="list_item1-2"></span><i id="list_tri1-2" class="fa fa-fw fa-caret-down text-danger"></i><span id="list_per1-2" class='text-danger'></span></p>
                    <p style="font-size:12px;">3주 평균 :<span id="list_item1-3"></span><i id="list_tri1-3" class="fa fa-fw fa-caret-down text-danger"></i><span id="list_per1-3" class='text-danger'></span></p>
                  </div>
                </div>
            </div>

            <div id="list_group2" hidden="hidden" class="card col-xl-3 col-lg-3 col-md-3 col-sm-3 col-3">
                <div class="card-header d-flex">
                  <h5 class="mb-0" style="margin-right:5px;"></h5>
                  <h4 class="mb-0 text-primary" style="font-weight:bold;"></h4>
                </div>
                <div class="card-body">
                  <div class="list-group">
                    <p style="font-size:12px;">전주 :<span id="list_item2-1"></span><i id="list_tri2-1" class="fa fa-fw fa-caret-down text-danger"></i><span id="list_per2-1" class='text-danger'></span></p>
                    <p style="font-size:12px;">평일 평균 :<span id="list_item2-2"></span><i id="list_tri2-2" class="fa fa-fw fa-caret-down text-danger"></i><span id="list_per2-2" class='text-danger'></span></p>
                    <p style="font-size:12px;">3주 평균 :<span id="list_item2-3"></span><i id="list_tri2-3" class="fa fa-fw fa-caret-down text-danger"></i><span id="list_per2-3" class='text-danger'></span></p>
                  </div>
                </div>
            </div>

            <div id="list_group3" hidden="hidden" class="card col-xl-3 col-lg-3 col-md-3 col-sm-3 col-3">
                <div class="card-header d-flex">
                  <h5 class="mb-0" style="margin-right:5px;"></h5>
                  <h4 class="mb-0 text-primary" style="font-weight:bold;"></h4>
                </div>
                <div class="card-body">
                  <div class="list-group">
                    <p style="font-size:12px;">전주 :<span id="list_item3-1"></span><i id="list_tri3-1" class="fa fa-fw fa-caret-down text-danger"></i><span id="list_per3-1" class='text-danger'></span></p>
                    <p style="font-size:12px;">평일 평균 :<span id="list_item3-2"></span><i id="list_tri3-2" class="fa fa-fw fa-caret-down text-danger"></i><span id="list_per3-2" class='text-danger'></span></p>
                    <p style="font-size:12px;">3주 평균 :<span id="list_item3-3"></span><i id="list_tri3-3" class="fa fa-fw fa-caret-down text-danger"></i><span id="list_per3-3" class='text-danger'></span></p>
                  </div>
                </div>
            </div>

            <div id="list_group4" hidden="hidden" class="card col-xl-3 col-lg-3 col-md-3 col-sm-3 col-3">
                <div class="card-header d-flex">
                  <h5 class="mb-0" style="margin-right:5px;"></h5>
                  <h4 class="mb-0 text-primary" style="font-weight:bold;"></h4>
                </div>
                <div class="card-body">
                  <div class="list-group">
                    <p style="font-size:12px;">전주 :<span id="list_item4-1"></span><i id="list_tri4-1" class="fa fa-fw fa-caret-down text-danger"></i><span id="list_per4-1" class='text-danger'></span></p>
                    <p style="font-size:12px;">평일 평균 :<span id="list_item4-2"></span><i id="list_tri4-2" class="fa fa-fw fa-caret-down text-danger"></i><span id="list_per4-2" class='text-danger'></span></p>
                    <p style="font-size:12px;">3주 평균 :<span id="list_item4-3"></span><i id="list_tri4-3" class="fa fa-fw fa-caret-down text-danger"></i><span id="list_per4-3" class='text-danger'></span></p>
                  </div>
                </div>
            </div>

          </div>
        </div>
      </div>
    </div>
    <!-- ============================================================== -->
    <!-- end main wrapper -->
    <!-- ============================================================== -->
    <!-- Optional JavaScript -->
    <script src="/voc/assets/vendor/jquery/jquery-3.3.1.min.js"></script>
    <script src="/voc/assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>
    <script src="/voc/assets/vendor/slimscroll/jquery.slimscroll.js"></script>
    <script src="/voc/assets/vendor/charts/charts-bundle/Chart.bundle.js"></script>
    <script src="/voc/assets/vendor/charts/charts-bundle/chartjs.js"></script>
    <script src="/voc/assets/libs/js/main-js.js"></script>
    <script type="text/javascript" src="/voc/assets/vendor/multi-select-min/js/bootstrap-select-min.js"></script>
    <script type="text/javascript" src="/voc/assets/libs/js/detail/details_5G_rate.js"></script>
    <script type="text/javascript">
        $(function(){
          $('html').removeClass('no-display');
        });
    </script>
</body>
</html>
