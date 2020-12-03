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
    $result = mysqli_query($conn, "SELECT * FROM settings");
    $row = mysqli_fetch_array($result);
    $model_flag = $row['deviceFlag'];

    if($model_flag == '0'){
      $sql_model = "SELECT * FROM voc_models WHERE flag='1' ORDER BY regiDate DESC, model ASC";
    }else{
      $sql_model = "SELECT * FROM voc_models2 WHERE flag='1' ORDER BY regiDate DESC, model ASC";
    }

    $sql_date = 'SELECT regiDate FROM voc_tot_data ORDER BY regiDate DESC LIMIT 1';
    $result_date = mysqli_query($conn, $sql_date);
    $row_date = mysqli_fetch_assoc($result_date);

    $result_model = mysqli_query($conn, $sql_model);
    $models = array();
    while($row = mysqli_fetch_assoc($result_model)){
          array_push($models, $row['model']);
    }
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
    <link rel="stylesheet" href="/voc/assets/vendor/fonts/circular-std/style.css">
    <link rel="stylesheet" href="/voc/assets/libs/css/style.css">
    <!-- <link href="http://fonts.googleapis.com/earlyaccess/jejugothic.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="/voc/assets/vendor/multi-select-min/css/bootstrap-select-min.css">
    <link rel="stylesheet" href="/voc/assets/vendor/fonts/fontawesome/css/fontawesome-all.css">
    <title>VOC 모니터링</title>
</head>

<body>
    <!-- ============================================================== -->
    <!-- main wrapper -->
    <!-- ============================================================== -->
    <div class="dashboard-main-wrapper">
        <!-- ============================================================== -->
        <!-- navbar -->
        <!-- ============================================================== -->
        <div class="dashboard-header">
            <nav class="navbar navbar-expand-lg bg-white fixed-top">
                <a class="navbar-brand" href="/voc/index.php">VOMS <samll id="navbar-brand-small">VOC Monitoring System</small></a>
                <div class="collapse navbar-collapse " id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto navbar-right-top">
                      <!-- ============================================================== -->
                      <!-- ============================GBN Menu========================== -->
                        <li class="nav-item dropdown connection">
                            <a class="nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="fas fa-fw fa-th"></i> </a>
                            <ul class="dropdown-menu dropdown-menu-right connection-dropdown">
                                <li class="connection-list">
                                    <div class="row">
                                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 ">
                                            <a href="/voc/index.php" class="connection-item"><img src="/voc/assets/images/home256.png" alt="move index page" > <span>HOME</span></a>
                                        </div>
                                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 ">
                                            <a href="/voc/data/index.php" class="connection-item"><img src="/voc/assets/images/data256.png" alt="move data page" > <span>DATA</span></a>
                                        </div>
                                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 ">
                                            <a href="/voc/chart/newChart.php" class="connection-item"><img src="/voc/assets/images/chart256.png" alt="move chart page" > <span>CHART</span></a>
                                        </div>
                                        <?php
                                          if ($_SESSION['adminAuth']== 1){
                                            echo
                                            '<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 ">
                                                <a href="/voc/admin/devices.php" class="connection-item"><img src="/voc/assets/images/admin256.png" alt="move Admin page" > <span>ADMIN</span></a>
                                            </div>';
                                          }
                                        ?>
                                    </div>
                                </li>
                            </ul>
                        </li>
                        <!-- ================================================================== -->
                        <!-- ============================End GBN Menu========================== -->
                        <!-- ================================================================== -->
                        <!-- =======================Account Profile Menu======================= -->
                        <li class="nav-item dropdown nav-user">
                            <a class="nav-link nav-user-img" href="#" id="navbarDropdownMenuLink2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="/voc/assets/images/logout.png" alt="" class="user-avatar-md rounded-circle"></a>
                            <div class="dropdown-menu dropdown-menu-right nav-user-dropdown" aria-labelledby="navbarDropdownMenuLink2">
                                <div class="nav-user-info">
                                    <h5 class="mb-0 text-white nav-user-name"><?php echo $_SESSION['name'];?> </h5>
                                    <span class="status"></span><span class="ml-2"><?php if($_SESSION['adminAuth'] == 1){echo "Available  (관리자)";}else{echo "Available  (일반)";}?></span>
                                </div>
                                <a class="dropdown-item" href="/voc/admin/account.php"><i class="fas fa-user mr-2"></i>Account</a>
                                <a class="dropdown-item" href="#"><i class="fas fa-cog mr-2"></i>Setting</a>
                                <a class="dropdown-item" href="/voc/phpdata/logout.php"><i class="fas fa-power-off mr-2"></i>Logout</a>
                            </div>
                        </li>
                        <!-- ================================================================== -->
                        <!-- =======================End Account Profile Menu=================== -->
                    </ul>
                </div>
            </nav>
        </div>
        <!-- ============================================================== -->
        <!-- end navbar -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- left sidebar -->
        <!-- ============================================================== -->
        <div class="nav-left-sidebar sidebar-dark">
          <div class="menu-list">
              <nav class="navbar navbar-expand-lg navbar-light">
                  <a class="d-xl-none d-lg-none" href="/voc/index.php">메인화면</a>
                  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                      <span class="navbar-toggler-icon"></span>
                  </button>
                  <div class="collapse navbar-collapse" id="navbarNav">
                      <ul class="navbar-nav flex-column">
                          <li class="nav-divider">
                              Menu
                          </li>
                          <li class="nav-item ">
                              <a class="nav-link active" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-1" aria-controls="submenu-1"><i class="fa fa-fw fa-user-circle"></i>메인화면<span class="badge badge-success">6</span></a>
                              <div id="submenu-1" class="collapse submenu" style="">
                                  <ul class="nav flex-column">
                                      <li class="nav-item">
                                          <a class="nav-link" href="/voc/index.php">메인화면</a>
                                      </li>
                                  </ul>
                              </div>
                          </li>
                          <li class="nav-item">
                              <a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-2" aria-controls="submenu-2"><i class="fa fa-fw fa-rocket"></i>VOC데이터</a>
                              <div id="submenu-2" class="collapse submenu" style="">
                                  <ul class="nav flex-column">
                                      <li class="nav-item">
                                          <a class="nav-link" href="/voc/data/index.php">VOC 통품전체</a>
                                      </li>
                                      <li class="nav-item">
                                          <a class="nav-link" href="/voc/data/sort.php">VOC 불만성유형</a>
                                      </li>
                                      <li class="nav-item">
                                          <a class="nav-link" href="/voc/data/subscribe.php">가입자 정보</a>
                                      </li>
                                  </ul>
                              </div>
                          </li>
                          <li class="nav-item">
                              <a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-3" aria-controls="submenu-3"><i class="fas fa-fw fa-chart-pie"></i>VOC차트</a>
                              <div id="submenu-3" class="collapse submenu" style="">
                                  <ul class="nav flex-column">
                                    <li class="nav-item">
                                        <a class="nav-link" href="/voc/chart/modelChart.php">모델차트</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="/voc/chart/manuChart.php">제조사차트</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="/voc/chart/keywordChart.php">키워드차트</a>
                                    </li>
                                  </ul>
                              </div>
                          </li>
                          <?php
                            if($_SESSION['adminAuth'] == 1){
                                  echo '
                                  <li class="nav-item ">
                                      <a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-4" aria-controls="submenu-4"><i class="fab fa-fw fa-wpforms"></i>관리페이지</a>
                                      <div id="submenu-4" class="collapse submenu" style="">
                                          <ul class="nav flex-column">
                                              <li class="nav-item">
                                                  <a class="nav-link" href="/voc/admin/devices.php">단말리스트관리</a>
                                              </li>
                                              <li class="nav-item">
                                                  <a class="nav-link" href="/voc/admin/deviceAd.php">단말추가</a>
                                              </li>
                                              <li class="nav-item">
                                                  <a class="nav-link" href="/voc/admin/classes.php">분류리스트관리</a>
                                              </li>
                                              <li class="nav-item">
                                                  <a class="nav-link" href="/voc/admin/classAd.php">분류추가</a>
                                              </li>
                                              <li class="nav-item">
                                                  <a class="nav-link" href="/voc/admin/accounts.php">사용자관리</a>
                                              </li>
                                              <li class="nav-item">
                                                  <a class="nav-link" href="/voc/admin/settings.php">상태설정</a>
                                              </li>
                                          </ul>
                                      </div>
                                  </li>
                                  ';
                              }
                          ?>
                      </ul>
                  </div>
              </nav>
          </div>
        </div>
        <!-- ============================================================== -->
        <!-- end left sidebar -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- wrapper  -->
        <!-- ============================================================== -->
        <div class="dashboard-wrapper">
            <div class="container-fluid  dashboard-content">
                <!-- ============================================================== -->
                <!-- pageheader -->
                <!-- ============================================================== -->
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="page-header">
                            <h2 class="pageheader-title">Subscriber Chart</h2>
                            <p class="pageheader-text"></p>
                            <div class="page-breadcrumb">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="/voc/index.php" class="breadcrumb-link">Dashboard</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">Subscriber Chart</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- end pageheader -->
                <!-- selector section -->
                <div class="row">
                  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="card">
                      <div id='searchForm' class="card-body" style="padding-right:10px;">
                        <!-- onsubmit="return checkValue();" -->
                        <ul id="searchList">
                          <li>
                            <!-- data-actions-box="true" -->
                            <select id="selectModel" class="selectpicker" style="font-size:11px; margin-left:5px;" multiple data-live-search="true" data-size="8" multiple data-max-options="6" data-style="btn-primary">
                              <?php
                                echo "<option value='total_focus' class='multiple' selected='selected'>관심전체</option>";
                                // echo "<option value='total_hole' class='multiple' selected='selected'>단말전체</option>";
                                for($i=0;$i<count($models);$i++){
                                  echo "<option value='".$models[$i]."' class='multiple'>".substr($models[$i], 0, 50)."</option>";
                                }
                              ?>
                            </select>
                          </li>
                          <li>
                            <select id="selectType" class="selectpicker" style="font-size:11px; margin-left:5px;" data-style="btn-primary">
                              <option value="voc_tot_data" selected="selected">통품전체</option>
                              <option value="voc_sort_data">단말조치</option>
                            </select>
                          </li>
                          <li>
                            <select id="selectKey" class="selectpicker" style="font-size:11px; margin-left:5px;" multiple onchange="checkKeys();" data-style="btn-primary">
                              <option class="op" value="userAgent">사용자AGENT</option>
                              <option class="op" value="counsel2">상담유형2</option>
                              <option class="op" value="counsel3">상담유형3</option>
                              <option class="op" value="action3">상담사조치3</option>
                              <option class="op" value="planCode">요금제</option>
                              <option class="op" value="devCode">단말기코드</option>
                              <option class="op" value="memo">메모내용</option>
                              <option class="op" value="state">지역(시/도)</option>
                            </select>
                          </li>
                          <li>
                            <input id="startDate" type="date" style="height:41px; width:130px; margin-left:5px;" value="<?php echo date("Y-m-d", strtotime($row_date['regiDate']."- 7 days"));?>">
                          </li>
                          <li>
                            <input id="endDate" type="date" style="height:41px; width:130px; margin-left:5px;" value="<?php echo date("Y-m-d", strtotime($row_date['regiDate']));?>">
                          </li>
                          <li class='choice_li' hidden>
                            <input id="key1" type="text" style="height:41px; width:80px; margin-left:5px;" value="" placeholder="">
                          </li>
                          <li class='choice_li' hidden>
                            <input id="key2" type="text" style="height:41px; width:80px; margin-left:5px;" value="" placeholder="">
                          </li>
                          <li class='choice_li' hidden>
                            <input id="key3" ype="text" style="height:41px; width:80px; margin-left:5px;" value="" placeholder="">
                          </li>
                          <li>
                            <button id='btn_update' type="button" class="btn btn-success" onclick="update();" style=" height:41px; width:60px; margin-left:5px;">OK</button>
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
                          <h5 class="card-header">Total VOC Count</h5>
                          <div class="card-body">
                            <canvas id="chartjs_line1" height="60"></canvas>
                          </div>
                        </div>
                    </div>
                </div>

                <!-- ============================ second chart row Total VOC and Term Rate ============================ -->
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="card">
                          <h5 class="card-header">Class Category Count</h5>
                          <div class="card-body">
                            <canvas id="chartjs_bar1" height="60"></canvas>
                          </div>
                        </div>
                    </div>
                </div>
                <!-- ============================ third chart row Total VOC and Term Rate ============================ -->
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
                        <div class="card">
                            <h5 class="card-header">Management Team Count</h5>
                            <div class="card-body">
                                <canvas id="chartjs_bar2" height="160px"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
                        <div class="card">
                            <h5 class="card-header">Network Count</h5>
                            <div class="card-body">
                                <canvas id="chartjs_bar3" height="160px"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ============================ fourth chart row Total VOC and Term Rate ============================ -->
                <!-- <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
                        <div class="card">
                            <h5 class="card-header">Update Count</h5>
                            <div class="card-body">
                                <canvas id="chartjs_pie1" height="160px"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
                        <div class="card">
                            <h5 class="card-header">Roamming Count</h5>
                            <div class="card-body">
                                <canvas id="chartjs_pie2" height="160px"></canvas>
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>
            <!-- ============================================================== -->
            <!-- footer -->
            <!-- ============================================================== -->
            <div class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                              Copyright © 2019 TestEnC. All rights reserved.
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="text-md-right footer-links d-none d-sm-block">
                                <a href="http://gw.bwlee325.cafe24.com/groupware/index.php" target="_blank">Visit Groupware</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- end footer -->
            <!-- ============================================================== -->
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
    <script type="text/javascript" src="/voc/assets/libs/js/subs_chart.js"></script>
    <script type="text/javascript">
        $(function(){
          $('html').removeClass('no-display');
        });
    </script>
</body>
</html>
