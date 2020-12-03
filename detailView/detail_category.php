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
    $sql_model_5G = "";
    $sql_model_LTE = "";
    $model_type = "";

    $result = mysqli_query($conn, "SELECT * FROM settings");
    $row = mysqli_fetch_array($result);
    $model_flag = $row['deviceFlag'];

    if($model_flag == '0'){
      $sql_model_5G = "SELECT * FROM voc_models WHERE flag='1' AND cellType='5G' ORDER BY focusOn DESC, model ASC";
      $sql_model_LTE = "SELECT * FROM voc_models WHERE flag='1' AND cellType='LTE' ORDER BY focusOn DESC, model ASC";
      $model_type = "voc_models";
    }else{
      $sql_model_5G = "SELECT * FROM voc_models2 WHERE flag='1' AND cellType='5G' ORDER BY focusOn DESC, model ASC";
      $sql_model_LTE = "SELECT * FROM voc_models2 WHERE flag='1' AND cellType='LTE' ORDER BY focusOn DESC, model ASC";
      $model_type = "voc_models2";
    }

    $sql_date = 'SELECT regiDate FROM voc_tot_data ORDER BY regiDate DESC LIMIT 1';
    $result_date = mysqli_query($conn, $sql_date);
    $row_date = mysqli_fetch_assoc($result_date);

    $result_model_5G = mysqli_query($conn, $sql_model_5G);
    $result_model_LTE = mysqli_query($conn, $sql_model_LTE);

    $sql = 'SELECT category FROM voc_classes WHERE flag=1';
    $result = mysqli_query($conn, $sql);
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
                <select id="netType" class="selectpicker" onchange="changeSelect();" style="font-size:10px; margin-left:10px;" data-style="btn-primary">
                  <option value="5G" selected="selected">5G</option>
                  <option value="LTE">LTE</option>
                </select>
              </li>
              <li id='select1'>
                <input id="modelType" type="text" hidden name="" value="<?php echo $model_type ?>">
                <!-- data-actions-box="true" -->
                <select id="selectModel_5G" class="selectpicker" style="font-size:10px; margin-left:5px;" multiple data-live-search="true" data-size="6" data-max-options="4" data-style="btn-primary" data-width="100px">
                <?php
                      echo "<option value='total_focus' class='multiple' selected='selected'>5G관심</option>";
                      echo "<option value='total_hole' class='multiple' selected='selected'>5G전체</option>";
                      while($row = mysqli_fetch_array($result_model_5G)){
                        echo "<option value='".$row['model']."' class='multiple'>".substr($row['model'], 0, 50)."</option>";
                      }
                ?>
                </select>
              </li>
              <li id='select2' style='display:none;'>
                <select id="selectModel_LTE" class="selectpicker" style="font-size:10px; margin-left:5px; display:none;" multiple data-live-search="true" data-size="6" data-max-options="4" data-style="btn-primary" data-width="100px">
                <?php
                      echo "<option value='total_focus' class='multiple' selected='selected'>LTE관심</option>";
                      echo "<option value='total_hole' class='multiple' selected='selected'>LTE전체</option>";
                      while($row = mysqli_fetch_array($result_model_LTE)){
                        echo "<option value='".$row['model']."' class='multiple'>".substr($row['model'], 0, 50)."</option>";
                      }
                ?>
                </select>
              </li>
              <li>
                <select id="selectCategory" class="selectpicker" style="font-size:10px; margin-left:5px;" data-size="6" data-style="btn-primary" data-width="100px">
                <?php
                      $i = 0;
                      while($row=mysqli_fetch_array($result)){
                        if($i == 0){
                          echo "<option value='".$row['category']."' selected='selected'>".$row['category']."</option>";
                        }else{
                          echo "<option value='".$row['category']."'>".$row['category']."</option>";
                        }
                        $i++;
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
    <script type="text/javascript" src="/voc/assets/libs/js/detail/details_category.js"></script>
    <script type="text/javascript">
        $(function(){
          $('html').removeClass('no-display');
        });
    </script>
</body>
</html>
