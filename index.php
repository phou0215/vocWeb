<?php
    session_start();
    if(!$_SESSION){
        session_start();
        if(isset($_SESSION['is_login'])!= true){
          echo "<script>
                  alert('접속을 위해 로그인이 필요합니다.');
                  location.href='/voc/phpdata/signin.php'
                </script>";}}

      require_once($_SERVER['DOCUMENT_ROOT']."/config/config.php");
      $conn = mysqli_connect($config['host'],$config['user'],$config['password']);
      mysqli_select_db($conn, $config['database']);

      //category 목록 가져오기
      $result = mysqli_query($conn, 'SELECT category FROM voc_classes WHERE flag=1');

      // 최신 저장된 데이터 날짜 가져오기
      $result_recent = mysqli_query($conn, "SELECT regiDate FROM voc_tot_data ORDER BY regiDate DESC LIMIT 1");
      $row_recent = mysqli_fetch_assoc($result_recent);
      $recent = $row_recent['regiDate'];
      mysqli_close($conn);
 ?>
<!doctype html>
<html lang="en" class='no-display'>

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/vendor/fonts/circular-std/style.css">
    <link rel="stylesheet" href="assets/libs/css/style.css">
    <link rel="stylesheet" href="assets/vendor/fonts/fontawesome/css/fontawesome-all.css">
    <!-- <link rel="stylesheet" href="assets/vendor/fonts/material-design-iconic-font/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="/voc/assets/vendor/fonts/simple-line-icons/css/simple-line-icons.css">     -->
    <link rel="stylesheet" href="/voc/assets/vendor/fonts/weather-icons/css/weather-icons.css">
    <link rel="stylesheet" href="/voc/assets/vendor/fonts/weather-icons/css/weather-icons-wind.css">
    <link rel="stylesheet" href="assets/vendor/vector-map/jqvmap.css">
    <link rel="stylesheet" href="assets/vendor/jvectormap/jquery-jvectormap-2.0.2.css">
    <link rel="stylesheet" href="assets/vendor/multi-select-min/css/bootstrap-select-min.css">
    <link rel="stylesheet" href="assets/vendor/fonts/flag-icon-css/flag-icon.min.css">
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
                <a class="navbar-brand" href="/voc/index.php">VOMS <samll id="navbar-brand-small">  Voc Monitoring System</small></a>
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
                                            <a href="/voc/index.php" class="connection-item"><img src="/voc/assets/images/home256.png" alt="move index page" ><span class>메인</span></a>
                                        </div>
                                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 ">
                                            <a href="/voc/data/index.php" class="connection-item"><img src="/voc/assets/images/data256.png" alt="move data page" ><span>데이터</span></a>
                                        </div>
                                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 ">
                                            <a href="/voc/chart/modelChart.php" class="connection-item"><img src="/voc/assets/images/chart256.png" alt="move chart page" ><span>차트</span></a>
                                        </div>
                                        <?php
                                          if ($_SESSION['adminAuth']== 1){
                                            echo
                                            '<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 ">
                                                <a href="/voc/admin/devices.php" class="connection-item"><img src="/voc/assets/images/admin256.png" alt="move Admin page" > <span>관리</span></a>
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
                            <a class="nav-link nav-user-img" href="#" id="navbarDropdownMenuLink2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img alt="로그아웃" src="/voc/assets/images/logout.png" alt="" class="user-avatar-md rounded-circle"></a>
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
                <!-- LNB pagehader  -->
                <!-- ============================================================== -->
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="page-header">
                            <h2 class="pageheader-title">메인화면</h2>
                            <!-- <p class="pageheader-text">Nulla euismod urna eros, sit amet scelerisque torton lectus vel mauris facilisis faucibus at enim quis massa lobortis rutrum.</p> -->
                            <div class="page-breadcrumb">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="/voc/index.php" class="breadcrumb-link">메인화면</a></li>
                                        <li class="breadcrumb-item active" aria-current="page">메인화면</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- sparkline chart start-->
                <!-- ============================================================== -->
                <div class="row">
                    <!-- 5G Rate -->
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                        <div class="card">
                            <h5 class="card-header text-success"><strong>5G Rate</strong></h5>
                            <div class="card-body">
                                <div class="metric-value d-inline-block">
                                    <h4 id="spark1_current" class="mb-1 text-primary"></h4>
                                </div>
                                <div id="spark1_font" class="metric-label d-inline-block float-right text-danger">
                                    <span style="color:black;">증감</span>
                                    <i id="spark1_tri" class="fa fa-fw fa-caret-down"></i>
                                    <span id="spark1_value"></span>
                                </div>
                            </div>
                            <div id="sparkline-1"></div>
                            <div class="card-footer text-center bg-white">
                                <a href="#" class="card-link" onClick="MyWindow=window.open('/voc/detailView/detail_5GRate.php','MyWindow','width=1000, height=770'); return false;">View Details</a>
                            </div>
                        </div>
                    </div>
                    <!-- LTE Rate -->
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                        <div class="card">
                            <h5 class="card-header text-secondary"><strong>LTE Rate</strong></h5>
                            <div class="card-body">
                                <div class="metric-value d-inline-block">
                                    <h4 id="spark2_current" class="mb-1 text-primary"></h4>
                                </div>
                                <div id="spark2_font" class="metric-label d-inline-block float-right text-danger">
                                    <span style="color:black;">증감</span>
                                    <i id="spark2_tri" class="fa fa-fw fa-caret-down"></i>
                                    <span id="spark2_value"></span>
                                </div>
                            </div>
                            <div id="sparkline-2"></div>
                            <div class="card-footer text-center bg-white">
                              <a href="#" class="card-link" onClick="MyWindow=window.open('/voc/detailView/detail_LTERate.php','MyWindow','width=1000, height=690'); return false;">View Details</a>
                            </div>
                        </div>
                    </div>
                    <!-- 5G VOC -->
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                        <div class="card">
                            <h5 class="card-header text-success"><strong>5G VOC</strong></h5>
                            <div class="card-body">
                                <div class="metric-value d-inline-block">
                                    <h4 id="spark3_current" class="mb-1 text-primary"></h4>
                                </div>
                                <div id="spark3_font" class="metric-label d-inline-block float-right text-danger">
                                    <span style="color:black;">증감</span>
                                    <i id="spark3_tri" class="fa fa-fw fa-caret-down"></i>
                                    <span id="spark3_value"></span>
                                </div>
                            </div>
                            <div id="sparkline-3"></div>
                            <div class="card-footer text-center bg-white">
                              <a href="#" class="card-link" onClick="MyWindow=window.open('/voc/detailView/detail_5GVoc.php','MyWindow','width=1000, height=690'); return false;">View Details</a>
                            </div>
                        </div>
                    </div>
                    <!-- LTE VOC -->
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 col-12">
                        <div class="card">
                            <h5 class="card-header text-secondary"><strong>LTE VOC</strong></h5>
                            <div class="card-body">
                                <div class="metric-value d-inline-block">
                                    <h4 id="spark4_current" class="mb-1 text-primary"></h4>
                                </div>
                                <div id="spark4_font" class="metric-label d-inline-block float-right text-success">
                                    <span style="color:black;">증감</span>
                                    <i id="spark4_tri" class="fa fa-fw fa-caret-up"></i>
                                    <span id="spark4_value"></span>
                                </div>
                            </div>
                            <div id="sparkline-4"></div>
                            <div class="card-footer text-center bg-white">
                              <a href="#" class="card-link" onClick="MyWindow=window.open('/voc/detailView/detail_LTEVoc.php','MyWindow','width=1000, height=690'); return false;">View Details</a>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- ============================================================== -->
                <!-- sparkline chart End-->
                <!-- ============================================================== -->

                <!-- ============================================================== -->
                <!-- 요약 Table chart start-->
                <!-- ============================================================== -->
                <div class="row">
                  <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                      <div class="card">
                          <div class="card-header d-flex">
                            <h4 class="card-header-title text-primary">단말종합요약</h4>
                            <select id="selectNet1" class="custom-select ml-auto w-auto">
                              <option value="5G" selected='selected'>5G</option>
                              <option value="LTE">LTE</option>
                            </select>
                            <select id="selectAvg1" class="custom-select w-auto" style='margin-left:5px;'>
                              <option value="1" selected='selected'>전주</option>
                              <option value="2">평일평균</option>
                              <option value="3">3주평균</option>
                            </select>
                            <button id='sum_update' type="button" class="btn btn-info" onclick="sum_update();" style=" height:39px; width:100px; margin-left:5px;">OK</button>
                          </div>
                          <div class="card-body p-0">
                              <div class="table-responsive">
                                  <table class="table">
                                      <thead class="bg-light">
                                          <tr class="border-0">
                                              <th class="border-0 text-center">구분</th>
                                              <th class="border-0 text-center">건수</th>
                                              <th class="border-0 text-center">가입자</th>
                                              <th class="border-0 text-center">비율</th>
                                              <th class="border-0 text-center">증감률(비율)</th>
                                              <th class="border-0 text-center">상태</th>
                                          </tr>
                                      </thead>
                                      <tbody id='table_1'>
                                      </tbody>
                                  </table>
                              </div>
                          </div>
                      </div>
                  </div>
                </div>
                <!-- ============================================================== -->
                <!-- 요약 Table chart End-->
                <!-- ============================================================== -->

                <!-- ============================================================== -->
                <!-- 지역별 jVector chart and 정리 table start-->
                <!-- ============================================================== -->
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="card">
                            <div class="card-header d-flex">
                              <h4 class="card-header-title text-primary">지역종합요약</h4>
                              <select id="selectNet2" class="custom-select ml-auto w-auto">
                                <option value="5G" selected='selected'>5G</option>
                                <option value="LTE">LTE</option>
                              </select>
                              <select id="selectAvg2" class="custom-select w-auto" style='margin-left:5px;'>
                                <option value="1" selected='selected'>전주</option>
                                <option value="2">평일평균</option>
                                <option value="3">3주평균</option>
                              </select>
                              <button id='region_update' type="button" class="btn btn-info" onclick="region_update();" style=" height:39px; width:100px; margin-left:5px;">OK</button>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead class="bg-light">
                                            <tr class="border-0">
                                                <th class="border-0 text-center">본부</th>
                                                <th class="border-0 text-center">5G건수</th>
                                                <th class="border-0 text-center">LTE건수</th>
                                                <th class="border-0 text-center">5G증감</th>
                                                <th class="border-0 text-center">LTE증감</th>
                                                <th class="border-0 text-center">상태</th>
                                            </tr>
                                        </thead>
                                        <tbody id="table_2">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- /voc/detailView/detail_region.php -->
                            <div class="card-footer text-center">
                              <!-- onClick="MyWindow=window.open('/voc/detailView/detail_region.php','MyWindow','width=700, height=700'); return false;"                               -->
                              <a href="#" class="btn-primary-link" onClick="MyWindow=window.open('/voc/detailView/detail_region.php','MyWindow','width=1000, height=690'); return false;">View Details</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- 지역별 jVector chart and 정리 table End-->
                <!-- ============================================================== -->

                <!-- ============================================================== -->
                <!-- 어제 키워드 Top 20 및 카테고리별 정리 table start-->
                <!-- ============================================================== -->
                <div class="row">

                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                        <!-- ============================================================== -->
                        <!-- social source  -->
                        <!-- ============================================================== -->
                        <div class="card">
                            <div class="card-header d-flex">
                              <h4 class="card-header-title text-primary">카테고리요약</h4>
                              <select id="selectNet3" class="custom-select ml-auto w-auto">
                                <option value="5G" selected='selected'>5G</option>
                                <option value="LTE">LTE</option>
                              </select>
                              <select id="selectAvg3" class="custom-select w-auto" style='margin-left:5px;'>
                                <option value="1" selected='selected'>전주</option>
                                <option value="2">평일평균</option>
                                <option value="3">3주평균</option>
                              </select>
                              <button id='sum_update' type="button" class="btn btn-info" onclick="category_update();" style=" height:39px; width:100px; margin-left:5px;">OK</button>
                            </div>
                            <div class="card-body p-0">
                              <table class="table">
                                  <thead class="bg-light">
                                      <tr class="border-0">
                                          <th class="border-0 text-center">카테고리</th>
                                          <th class="border-0 text-center">건수</th>
                                          <th class="border-0 text-center">증감률</th>
                                          <th class="border-0 text-center">상태</th>
                                      </tr>
                                  </thead>
                                  <tbody id='table_3'>
                                  </tbody>
                              </table>
                            </div>
                            <div class="card-footer text-center">
                              <a href="#" class="card-link" onClick="MyWindow=window.open('/voc/detailView/detail_category.php','MyWindow','width=1000, height=600'); return false;">View Details</a>
                            </div>
                        </div>
                        <!-- ============================================================== -->
                        <!-- end social source  -->
                        <!-- ============================================================== -->
                    </div>

                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                        <!-- ============================================================== -->
                        <!-- sales traffice source  -->
                        <!-- ============================================================== -->
                        <div class="card">
                            <div class="card-header d-flex">
                              <h4 class="card-header-title text-primary">키워드요약</h4>
                            </div>
                            <div class="card-header d-flex">
                              <input id="baseDate" type="date" style="height:39px; margin-left:5px;" value="<?php echo $recent;?>">
                              <select id="selectNet4" class="custom-select ml-auto w-auto">
                                <option value="5G" selected='selected'>5G</option>
                                <option value="LTE">LTE</option>
                              </select>
                              <select id="selectCate" class="custom-select w-auto" style='margin-left:5px;'>
                                <?php
                                  $i = 0;
                                  while($row = mysqli_fetch_assoc($result)){
                                    if($i == 0){
                                      echo "<option value='".$row['category']."' selected='selected'>".$row['category']."</option> ";
                                    }else{
                                      echo "<option value='".$row['category']."'>".$row['category']."</option> ";
                                    }
                                    $i++;
                                  }
                                ?>
                              </select>
                              <button id='sum_update' type="button" class="btn btn-info" onclick="keyword_update();" style=" height:39px; width:100px; margin-left:5px;">OK</button>
                            </div>
                            <div class="card-body">
                                <canvas id="total_keyword" width="220" height="120"></canvas>
                                <!-- <div class="chart-widget-list">
                                    <p>
                                        <span class="fa-xs text-primary mr-1 legend-title"><i class="fa fa-fw fa-square-full"></i></span><span class="legend-text"> Direct</span>
                                        <span class="float-right">$300.56</span>
                                    </p>
                                    <p>
                                        <span class="fa-xs text-secondary mr-1 legend-title"><i class="fa fa-fw fa-square-full"></i></span>
                                        <span class="legend-text">Affilliate</span>
                                        <span class="float-right">$135.18</span>
                                    </p>
                                    <p>
                                        <span class="fa-xs text-brand mr-1 legend-title"><i class="fa fa-fw fa-square-full"></i></span> <span class="legend-text">Sponsored</span>
                                        <span class="float-right">$48.96</span>
                                    </p>
                                    <p class="mb-0">
                                        <span class="fa-xs text-info mr-1 legend-title"><i class="fa fa-fw fa-square-full"></i></span> <span class="legend-text"> E-mail</span>
                                        <span class="float-right">$154.02</span>
                                    </p>
                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- 어제 키워드 Top 20 및 카테고리별 정리 table End-->
                <!-- ============================================================== -->
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
        <!-- ============================================================== -->
        <!-- end wrapper  -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- end main wrapper  -->
    <!-- ============================================================== -->
    <!-- Optional JavaScript -->
    <!-- jquery 3.3.1 js-->
    <script src="assets/vendor/jquery/jquery-3.3.1.min.js"></script>
    <!-- bootstrap bundle js-->
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>
    <!-- slimscroll js-->
    <script src="assets/vendor/slimscroll/jquery.slimscroll.js"></script>
    <!-- chartjs js-->
    <script src="assets/vendor/charts/charts-bundle/Chart.bundle.js"></script>
    <script src="assets/vendor/charts/charts-bundle/chartjs.js"></script>

    <!-- main js-->
    <script src="assets/libs/js/main-js.js"></script>
    <!-- jvactormap js-->
    <script src="assets/vendor/jvectormap/jquery-jvectormap-2.0.2.min.js"></script>
    <!-- <script src="assets/vendor/jvectormap/jquery-jvectormap-world-mill-en.js"></script> -->
    <script src="assets/vendor/jvectormap/jquery-jvectormap-kr-mill.js"></script>
    <!-- sparkline js-->
    <script src="assets/vendor/charts/sparkline/jquery.sparkline.js"></script>
    <!-- <script src="assets/vendor/charts/sparkline/spark-js.js"></script> -->
    <!-- multiselect js-->
    <script type="text/javascript" src="assets/vendor/multi-select-min/js/bootstrap-select-min.js"></script>
    <!-- dashboard sales js-->
    <script src="assets/libs/js/dashboard-sales.js"></script>
    <script type="text/javascript">
      // $(function(){
      //
      // });
      $(window).resize(function(){
        sparkline();
      });
    </script>
</body>

</html>
