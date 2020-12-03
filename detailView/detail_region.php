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
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/voc/assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/voc/assets/vendor/fonts/circular-std/style.css" >
    <link rel="stylesheet" href="/voc/assets/libs/css/style.css">
    <link rel="stylesheet" href="/voc/assets/vendor/fonts/fontawesome/css/fontawesome-all.css">
    <link rel="stylesheet" href="/voc/assets/vendor/vector-map/jqvmap.css">
    <link rel="stylesheet" href="/voc/assets/vendor/jvectormap/jquery-jvectormap-2.0.2.css">
    <link rel="stylesheet" href="/voc/assets/vendor/fonts/flag-icon-css/flag-icon.min.css">
    <title>Region detail chart</title>
</head>

<body>
    <div class="row">
      <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="card">
          <div class="card-header d-flex">
            <h4 class="card-header-title text-primary">지역별 상세</h4>
            <select id="selectNet" class="custom-select ml-auto w-auto">
              <option value="5G" selected='selected'>5G</option>
              <option value="LTE">LTE</option>
            </select>
            <select id="selectAvg" class="custom-select w-auto" style='margin-left:5px;'>
              <option value="1" selected='selected'>전주</option>
              <option value="2">평일평균</option>
              <option value="3">3주평균</option>
            </select>
            <button id='region_update' type="button" class="btn btn-info" onclick="region_update();" style=" height:39px; width:100px; margin-left:5px;">OK</button>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="card">
          <h5 class="card-header text-primary">전국분포</h5>
          <div class="card-body">
            <div id="locationmap" style="width:100%; height:550px"></div>
          </div>
          <!-- <div class="card-body border-top">
            <div class="row">
              <div class="col-xl-6">
                <div class="sell-ratio">
                  <h5 class="mb-1 mt-0 font-weight-normal">New York</h5>
                  <div class="progress-w-percent">
                    <span class="progress-value">72k </span>
                    <div class="progress progress-sm">
                      <div class="progress-bar" role="progressbar" style="width: 72%;" aria-valuenow="72" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-xl-6">
                <div class="sell-ratio">
                  <h5 class="mb-1 mt-0 font-weight-normal">New York</h5>
                  <div class="progress-w-percent">
                    <span class="progress-value">72k </span>
                    <div class="progress progress-sm">
                      <div class="progress-bar" role="progressbar" style="width: 72%;" aria-valuenow="72" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-xl-6">
                <div class="sell-ratio">
                  <h5 class="mb-1 mt-0 font-weight-normal">New York</h5>
                  <div class="progress-w-percent">
                    <span class="progress-value">72k </span>
                    <div class="progress progress-sm">
                      <div class="progress-bar" role="progressbar" style="width: 72%;" aria-valuenow="72" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-xl-6">
                <div class="sell-ratio">
                  <h5 class="mb-1 mt-0 font-weight-normal">New York</h5>
                  <div class="progress-w-percent">
                    <span class="progress-value">72k </span>
                    <div class="progress progress-sm">
                      <div class="progress-bar" role="progressbar" style="width: 72%;" aria-valuenow="72" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div> -->
        </div>
      </div>
    </div>
    <!-- ============================================================== -->
    <!-- end main wrapper  -->
    <!-- ============================================================== -->
    <!-- Optional JavaScript -->
    <!-- jquery 3.3.1 js-->
    <script src="/voc/assets/vendor/jquery/jquery-3.3.1.min.js"></script>
    <!-- bootstrap bundle js-->
    <script src="/voc/assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>
    <!-- slimscroll js-->
    <script src="/voc/assets/vendor/slimscroll/jquery.slimscroll.js"></script>
    <!-- main js-->
    <script src="/voc/assets/libs/js/main-js.js"></script>
    <!-- jvactormap js-->
    <script src="/voc/assets/vendor/jvectormap/jquery-jvectormap-2.0.2.min.js"></script>
    <script src="/voc/assets/vendor/jvectormap/jquery-jvectormap-kr-mill.js"></script>
     <!-- dashboard sales js-->
    <script src="/voc/assets/libs/js/detail/details_region.js"></script>
</body>

</html>
