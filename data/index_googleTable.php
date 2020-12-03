<?php
    session_start();
    if(!$_SESSION){
        session_start();
        if(isset($_SESSION['is_login'])!= true){
          echo "<script>
                  alert('접속을 위해 로그인이 필요합니다.');
                  location.href='/voc/phpdata/signin.php'
                </script>";}}
 ?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/voc/assets/vendor/bootstrap/css/bootstrap.min.css">
    <link href="/voc/assets/vendor/fonts/circular-std/style.css" rel="stylesheet">
    <link rel="stylesheet" href="/voc/assets/libs/css/style.css">
    <link rel="stylesheet" href="/voc/assets/vendor/fonts/fontawesome/css/fontawesome-all.css">
    <link rel="stylesheet" href="/voc/assets/vendor/fonts/material-design-iconic-font/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="/voc/assets/vendor/fonts/flag-icon-css/flag-icon.min.css">
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
                                            <a href="/voc/index.php" class="connection-item"><img src="/voc/assets/images/home256.png" alt="move index page" > <span>HOME</span></a>
                                        </div>
                                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 ">
                                            <a href="/voc/data/index.php" class="connection-item"><img src="/voc/assets/images/data256.png" alt="move data page" > <span>DATA</span></a>
                                        </div>
                                        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 ">
                                            <a href="/voc/chart/index.php" class="connection-item"><img src="/voc/assets/images/chart256.png" alt="move chart page" > <span>CHART</span></a>
                                        </div>
                                        <?php
                                          if ($_SESSION['adminAuth']== 1){
                                            echo
                                            '<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 ">
                                                <a href="/voc/admin/index.php" class="connection-item"><img src="/voc/assets/images/admin256.png" alt="move Admin page" > <span>ADMIN</span></a>
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
                            <a class="nav-link nav-user-img" href="#" id="navbarDropdownMenuLink2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="/voc/assets/images/logout64.png" alt="" class="user-avatar-md rounded-circle"></a>
                            <div class="dropdown-menu dropdown-menu-right nav-user-dropdown" aria-labelledby="navbarDropdownMenuLink2">
                                <div class="nav-user-info">
                                    <h5 class="mb-0 text-white nav-user-name"><?php echo $_SESSION['name'];?> </h5>
                                    <span class="status"></span><span class="ml-2"><?php if($_SESSION['adminAuth'] == 1){echo "Available  (관리자)";}else{echo "Available  (일반)";}?></span>
                                </div>
                                <a class="dropdown-item" href="#"><i class="fas fa-user mr-2"></i>Account</a>
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
                    <a class="d-xl-none d-lg-none" href="#">Dashboard</a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav flex-column">
                            <li class="nav-divider">
                                Menu
                            </li>
                            <li class="nav-item ">
                                <a class="nav-link active" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-1" aria-controls="submenu-1"><i class="fa fa-fw fa-user-circle"></i>Dashboard <span class="badge badge-success">6</span></a>
                                <div id="submenu-1" class="collapse submenu" style="">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link" href="/voc/index.php">HOME</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-2" aria-controls="submenu-2"><i class="fa fa-fw fa-rocket"></i>DATA</a>
                                <div id="submenu-2" class="collapse submenu" style="">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link" href="/voc/data/index.php">VOC Total Data</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="/voc/data/sort.php">VOC Sort Data</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-3" aria-controls="submenu-3"><i class="fas fa-fw fa-chart-pie"></i>CHART</a>
                                <div id="submenu-3" class="collapse submenu" style="">
                                    <ul class="nav flex-column">
                                        <li class="nav-item">
                                            <a class="nav-link" href="/voc/chart/totalChart.php">Total Chart</a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <?php
                              if($_SESSION['adminAuth'] == 1){
                                    echo '
                                    <li class="nav-item ">
                                        <a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-4" aria-controls="submenu-4"><i class="fab fa-fw fa-wpforms"></i>ADMIN</a>
                                        <div id="submenu-4" class="collapse submenu" style="">
                                            <ul class="nav flex-column">
                                                <li class="nav-item">
                                                    <a class="nav-link" href="/voc/admin/devices.php">Device List</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" href="/voc/admin/deviceAd.php">Add Device</a>
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
            <div class="dashboard-ecommerce">
                <div class="container-fluid dashboard-content ">
                    <!-- ============================================================== -->
                    <!-- pageheader  -->
                    <!-- ============================================================== -->
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="page-header">
                                <h2 class="pageheader-title">VOC Total Data List</h2>
                                <!-- <p class="pageheader-text">Nulla euismod urna eros, sit amet scelerisque torton lectus vel mauris facilisis faucibus at enim quis massa lobortis rutrum.</p> -->
                                <div class="page-breadcrumb">
                                    <nav aria-label="breadcrumb">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item"><a href="/voc/index.php" class="breadcrumb-link">Dashboard</a></li>
                                            <li class="breadcrumb-item active" aria-current="page">VOC Total Data List</li>
                                        </ol>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ============================================================== -->
                    <!-- end pageheader  -->
                    <!-- ============================================================== -->
                    <div class="ecommerce-widget">
                        <!-- ============================ first Select Button ============================ -->
                        <div class="row">
                          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="card">
                              <div id='searchForm' class="card-body">
                                        <p id="sd" hidden data_text="total"></p>
                                        <ul id="searchList">
                                          <li>
                                            <label for="startDate">Start</label>
                                          </li>
                                          <li>
                                            <input id="startDate" type="date" name="startDate" style="height:40px;">
                                          </li>
                                          <li>
                                            <label for="startDate">End</label>
                                          </li>
                                          <li>
                                            <input id="endDate" type="date" name="endDate" style="height:40px;">
                                          </li>
                                          <li>
                                            <label for="searchSelect">Class</label>
                                          </li>
                                          <li>
                                            <select id="searchSelect" onchange="checkDate();" style="height:40px;">
                                              <option value='issueId'>이슈ID</option>
                                              <option value='receiveDate'>등록일</option>
                                              <option value='model2'>모델명</option>
                                              <option value='manu'>제조사</option>
                                              <option value='state'>시도(시/도)</option>
                                              <option value="swVer">SW 버전</option>
                                              <option value='class1'>메모분류</option>
                                              <option value='memo'>메모내용</option>
                                            </select>
                                          </li>
                                          <li>
                                            <input id="searchText" type="text" name="searchValue" placeholder="검색어" style="height:40px;" onkeypress="if(event.keyCode==13){searchTable(); return false;}">
                                          </li>
                                          <li>
                                            <button class="btn btn-primary" type="button" onclick="searchTable();" style="height:40px;">Search</button>
                                          </li>
                                          <li>
                                            <div class="btn_control">
                                              <button class="btn btn-secondary" type="button" onclick="totalTable();" style="height:40px;">Total List</button>
                                              <button class="btn btn-info excel" type="button" onclick="fileDownload();" style="height:40px;">Excel</button>
                                            </div>
                                          </li>
                                        </ul>
                                  </div>
                            </div>
                          </div>
                        </div>
                        <!-- ============================ second Select Button =========================== -->
                        <div class="row">
                          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="card">
                              <div class="card-body">
                                <h4 class="text-muted">VOC LIST</h4>
                                <label id='voc_caption' style='color:#5969ff;font-weight:bold;'></label>
                                <div id="table-content" style="padding-bottom:2px;padding-left:2px;padding-right:2px;"></div>
                              </div>
                            </div>
                          </div>
                        </div>
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
    <!-- jquery 3.3.1 -->
    <script src="/voc/assets/vendor/jquery/jquery-3.3.1.min.js"></script>
    <!-- bootstap bundle js -->
    <script src="/voc/assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>
    <!-- slimscroll js -->
    <script src="/voc/assets/vendor/slimscroll/jquery.slimscroll.js"></script>
    <!-- main js -->
    <script src="/voc/assets/libs/js/main-js.js"></script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" src="/voc/assets/libs/js/table.js"></script>
    <script type="text/javascript">
      var windowObj1;
      $(window).resize(function(){searchTable();});
    </script>
</body>

</html>
