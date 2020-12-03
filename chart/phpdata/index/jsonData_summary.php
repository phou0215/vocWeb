<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
    $conn = mysqli_connect($config['host'],$config['user'],$config['password']);
    mysqli_select_db($conn, $config['database']);

    // 배열 맨 앞 추가 함수는 array_unshift
    function get_rate($count, $sub){
      if($count == 0 || $sub == 0){
        return 0;
      }
      else{
        $temp_rate = ($count / $sub)*1000;
        $temp_rate = round($temp_rate, 4);
        return $temp_rate;
      }
    }

    function get_compared($pre , $cur){

      if($pre == 0){
        return 0;
      }
      else{
        $temp = round((($cur-$pre) / $pre)*100, 2);
        return $temp;
      }
    }


    $result = mysqli_query($conn, 'SELECT regiDate FROM voc_tot_data ORDER BY regiDate DESC LIMIT 1');
    $row = mysqli_fetch_assoc($result);
    $recent = $row['regiDate'];
    $startDate = date("Y-m-d", strtotime($row['regiDate']."- 1 month"));
    $endDate = date('Y-m-d', strtotime($row['regiDate']));

    //handling POST Parameter
    $avg_type = $_POST['avgType'];
    $net_type = $_POST['netType'];

    // php code start
    $index_summary_devices = array();
    $index_terms = array();
    $index_weeks = array();
    $index_ex_holiday = array();
    $index_three_week = array();
    $values_summary = array();

    $returnData = array();

    $modelsString = "";
    $modelsString_focus = "";
    $holidayString = "";
    $threeWeekString = "";

    $model_type = "";
    $model_sort = "";
    $weeks = array('일','월','화','수','목','금','토');
    $result = null;


    //Set model_type
    $result = mysqli_query($conn, "SELECT * FROM settings");
    $row = mysqli_fetch_array($result);
    $model_flag = $row['deviceFlag'];

    if($model_flag == '0'){
      $model_type = "voc_models";
      $model_sort = "model";
    }else{
      $model_type = "voc_models2";
      $model_sort = "model2";
    }
    //상태 설정값 가져오기
    $values_setting = [[$row["oneweekNormal"], $row["oneweekCaution1"], $row["oneweekCaution2"], $row["oneweekDanger"]],
    [$row["holidayNormal"], $row["holidayCaution1"], $row["holidayCaution2"], $row["holidayDanger"]],
    [$row["threeweekNormal"], $row["threeweekCaution1"], $row["threeweekCaution2"], $row["threeweekDanger"]]];


    //index_models push 전체/관심
    array_push($index_summary_devices, $net_type."전체");
    array_push($index_summary_devices, $net_type."관심");


    // 관심단말 디바이스 목록 가져오기
    $result = mysqli_query($conn, 'SELECT model FROM '.$model_type.' WHERE focusOn = "1" AND cellType = "'.$net_type.'"');
    $index_focus = array();
    while($row = mysqli_fetch_assoc($result)){
      array_push($index_focus, '"'.$row['model'].'"');
      array_push($index_summary_devices, $row['model']);
    }
    $modelsString_focus = implode(',', $index_focus);

    // 전체 등록 단말 목록 가져오기
    $result = mysqli_query($conn, 'SELECT model FROM '.$model_type.' WHERE flag = "1" AND cellType = "'.$net_type.'"');
    $index_total = array();
    while($row = mysqli_fetch_assoc($result)){
      array_push($index_total, '"'.$row['model'].'"');
    }
    $modelsString = implode(',', $index_total);


    //시작일에서 종료일까지 일수 구하기
    $days = (intval((strtotime($endDate)-strtotime($startDate)) / 86400));

    //시작일 날짜와 요일을 각각 push
    array_push($index_terms, $startDate);
    array_push($index_weeks, $weeks[(int) date('w',strtotime($startDate))]);

    //종료일까지(시작일 제외한) 날짜와 요일을 각각 push
    $i = 1;
    while($i <= $days){
      $temp_day = date('Y-m-d', strtotime($startDate.'+'.$i.' days'));
      $thisWeek = date('w',strtotime($temp_day));
      array_push($index_terms, $temp_day);
      array_push($index_weeks, $weeks[(int) $thisWeek]);
      $i++;
    }

    // 최근일 요일 값 import
    $endWeek = $index_weeks[count($index_weeks)-1];

    // 전주 날짜 값
    $oneWeek = $index_terms[count($index_terms)-8];


    // 공휴일 제외 날짜와 3주전 날짜 값 추출
    $i = 0;
    while($i< count($index_weeks)){

      //holiday 제외 날짜 배열 Add
      if($index_weeks[$i] != "토" && $index_weeks[$i] != "일"){
        array_push($index_ex_holiday, '"'.$index_terms[$i].'"');
      }
      //3week 동일 날짜 배열 Add
      if($index_weeks[$i] == $endWeek && $i != count($index_terms)-1){
        array_push($index_three_week, '"'.$index_terms[$i].'"');
      }
      $i++;
    }
    $holidayString = implode(',', $index_ex_holiday);
    $threeWeekString = implode(',', $index_three_week);

    ////////////////////////////////////////////////////VOC TOTAL DATA 추출 ///////////////////////////////////////////////
    $i=0;
    while($i < count($index_summary_devices)){

      $temp_count = 0;
      $temp_sum = 0;
      $temp_rate = 0;
      $temp_pre_count = 0;
      $temp_pre_sum = 0;
      $temp_pre_rate = 0;
      $temp_increase = 0;
      $temp_array = array();

      ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
      //////////////////////////////////////////////////////////////전체단말 값////////////////////////////////////////////////////////////////
      ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
      ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
      ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
      if($i == 0){

        //recent VOC 개수
        $sql = 'SELECT COUNT(*) AS cnt FROM voc_tot_data WHERE regiDate = "'.$recent.'" AND '.$model_sort.' IN ('.$modelsString.')';
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $temp_count = $row['cnt'];

        //recent 가입자 수
        $sql = 'SELECT SUM(numSubs) AS total FROM voc_subscriber WHERE subsDate = "'.$recent.'" AND '.$model_sort.' IN ('.$modelsString.')';
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $temp_sum = $row['total'];
        if($temp_sum == NULL){
          $temp_sum = 0;
        }

        //비교 선택이 전주일 경우
        if ($avg_type == "1"){

          //비교 VOC 개수
          $sql = 'SELECT COUNT(*) AS cnt FROM voc_tot_data WHERE regiDate = "'.$oneWeek.'" AND '.$model_sort.' IN ('.$modelsString.')';
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $temp_pre_count = $row['cnt'];

          //비교 가입자 수
          $sql = 'SELECT SUM(numSubs) AS total FROM voc_subscriber WHERE subsDate = "'.$oneWeek.'" AND '.$model_sort.' IN ('.$modelsString.')';
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $temp_pre_sum = $row['total'];
          if($temp_pre_sum == NULL){
            $temp_pre_sum = 0;
          }
        }
        //비교 선택이 한달 평일 평균일 경우
        else if($avg_type == "2"){
          //비교 VOC 개수
          $sql = 'SELECT COUNT(*) AS cnt FROM voc_tot_data WHERE regiDate IN ('.$holidayString.') AND '.$model_sort.' IN ('.$modelsString.')';
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $temp_pre_count = round($row['cnt']/count($index_ex_holiday));

          //비교 가입자 수
          $sql = 'SELECT SUM(numSubs) AS total FROM voc_subscriber WHERE subsDate IN ('.$holidayString.') AND '.$model_sort.' IN ('.$modelsString.')';
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $temp_pre_sum = $row['total'];
          if($temp_pre_sum == NULL){
            $temp_pre_sum = 0;
          }
          $temp_pre_sum = round($temp_pre_sum / count($index_ex_holiday));
        }
        //비교 선택이 3주 동일 평균일 경우
        else{
          //비교 VOC 개수
          $sql = 'SELECT COUNT(*) AS cnt FROM voc_tot_data WHERE regiDate IN ('.$threeWeekString.') AND '.$model_sort.' IN ('.$modelsString.')';
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $temp_pre_count = round($row['cnt']/count($index_three_week));

          //비교 가입자 수
          $sql = 'SELECT SUM(numSubs) AS total FROM voc_subscriber WHERE subsDate IN ('.$threeWeekString.') AND '.$model_sort.' IN ('.$modelsString.')';
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $temp_pre_sum = $row['total'];
          if($temp_pre_sum == NULL){
            $temp_pre_sum = 0;
          }
          $temp_pre_sum = round($temp_pre_sum / count($index_three_week));
        }
      ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
      //////////////////////////////////////////////////////////////관심단말 값////////////////////////////////////////////////////////////////
      ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
      ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
      ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
      }else if($i == 1){

        //recent VOC 개수
        $sql = 'SELECT COUNT(*) AS cnt FROM voc_tot_data WHERE regiDate = "'.$recent.'" AND '.$model_sort.' IN ('.$modelsString_focus.')';
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $temp_count = $row['cnt'];

        //recent 가입자 수
        $sql = 'SELECT SUM(numSubs) AS total FROM voc_subscriber WHERE subsDate = "'.$recent.'" AND '.$model_sort.' IN ('.$modelsString_focus.')';
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $temp_sum = $row['total'];
        if($temp_sum == NULL){
          $temp_sum = 0;
        }

        //비교 선택이 전주일 경우
        if ($avg_type == "1"){

          //비교 VOC 개수
          $sql = 'SELECT COUNT(*) AS cnt FROM voc_tot_data WHERE regiDate = "'.$oneWeek.'" AND '.$model_sort.' IN ('.$modelsString_focus.')';
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $temp_pre_count = $row['cnt'];

          //비교 가입자 수
          $sql = 'SELECT SUM(numSubs) AS total FROM voc_subscriber WHERE subsDate = "'.$oneWeek.'" AND '.$model_sort.' IN ('.$modelsString_focus.')';
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $temp_pre_sum = $row['total'];
          if($temp_pre_sum == NULL){
            $temp_pre_sum = 0;
          }
        }
        //비교 선택이 한달 평일 평균일 경우
        else if($avg_type == "2"){
          //비교 VOC 개수
          $sql = 'SELECT COUNT(*) AS cnt FROM voc_tot_data WHERE regiDate IN ('.$holidayString.') AND '.$model_sort.' IN ('.$modelsString_focus.')';
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $temp_pre_count = round($row['cnt']/count($index_ex_holiday));

          //비교 가입자 수
          $sql = 'SELECT SUM(numSubs) AS total FROM voc_subscriber WHERE subsDate IN ('.$holidayString.') AND '.$model_sort.' IN ('.$modelsString_focus.')';
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $temp_pre_sum = $row['total'];
          if($temp_pre_sum == NULL){
            $temp_pre_sum = 0;
          }
          $temp_pre_sum = round($temp_pre_sum / count($index_ex_holiday));
        }
        //비교 선택이 3주 동일 평균일 경우
        else{
          //비교 VOC 개수
          $sql = 'SELECT COUNT(*) AS cnt FROM voc_tot_data WHERE regiDate IN ('.$threeWeekString.') AND '.$model_sort.' IN ('.$modelsString_focus.')';
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $temp_pre_count = round($row['cnt']/count($index_three_week));

          //비교 가입자 수
          $sql = 'SELECT SUM(numSubs) AS total FROM voc_subscriber WHERE subsDate IN ('.$threeWeekString.') AND '.$model_sort.' IN ('.$modelsString_focus.')';
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $temp_pre_sum = $row['total'];
          if($temp_pre_sum == NULL){
            $temp_pre_sum = 0;
          }
          $temp_pre_sum = round($temp_pre_sum / count($index_three_week));
        }
      }
      ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
      //////////////////////////////////////////////////////////////개별 모델 값////////////////////////////////////////////////////////////////
      ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
      ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
      ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
      else{
        //recent VOC 개수
        $sql = 'SELECT COUNT(*) AS cnt FROM voc_tot_data WHERE regiDate = "'.$recent.'" AND '.$model_sort.'="'.$index_summary_devices[$i].'"';
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $temp_count = $row['cnt'];

        //recent 가입자 수
        $sql = 'SELECT SUM(numSubs) AS total FROM voc_subscriber WHERE subsDate = "'.$recent.'" AND '.$model_sort.'="'.$index_summary_devices[$i].'"';
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $temp_sum = $row['total'];
        if($temp_sum == NULL){
          $temp_sum = 0;
        }

        //비교 선택이 전주일 경우
        if ($avg_type == "1"){

          //비교 VOC 개수
          $sql = 'SELECT COUNT(*) AS cnt FROM voc_tot_data WHERE regiDate = "'.$oneWeek.'" AND '.$model_sort.'="'.$index_summary_devices[$i].'"';
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $temp_pre_count = $row['cnt'];

          //비교 가입자 수
          $sql = 'SELECT SUM(numSubs) AS total FROM voc_subscriber WHERE subsDate = "'.$oneWeek.'" AND '.$model_sort.'="'.$index_summary_devices[$i].'"';
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $temp_pre_sum = $row['total'];
          if($temp_pre_sum == NULL){
            $temp_pre_sum = 0;
          }
        }
        //비교 선택이 한달 평일 평균일 경우
        else if($avg_type == "2"){
          //비교 VOC 개수
          $sql = 'SELECT COUNT(*) AS cnt FROM voc_tot_data WHERE regiDate IN ('.$holidayString.') AND '.$model_sort.'="'.$index_summary_devices[$i].'"';
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $temp_pre_count = round($row['cnt']/count($index_ex_holiday));

          //비교 가입자 수
          $sql = 'SELECT SUM(numSubs) AS total FROM voc_subscriber WHERE subsDate IN ('.$holidayString.') AND '.$model_sort.'="'.$index_summary_devices[$i].'"';
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $temp_pre_sum = $row['total'];
          if($temp_pre_sum == NULL){
            $temp_pre_sum = 0;
          }
          $temp_pre_sum = round($temp_pre_sum / count($index_ex_holiday));
        }
        //비교 선택이 3주 동일 평균일 경우
        else{
          //비교 VOC 개수
          $sql = 'SELECT COUNT(*) AS cnt FROM voc_tot_data WHERE regiDate IN ('.$threeWeekString.') AND '.$model_sort.'="'.$index_summary_devices[$i].'"';
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $temp_pre_count = round($row['cnt']/count($index_three_week));

          //비교 가입자 수
          $sql = 'SELECT SUM(numSubs) AS total FROM voc_subscriber WHERE subsDate IN ('.$threeWeekString.') AND '.$model_sort.'="'.$index_summary_devices[$i].'"';
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_assoc($result);
          $temp_pre_sum = $row['total'];
          if($temp_pre_sum == NULL){
            $temp_pre_sum = 0;
          }
          $temp_pre_sum = round($temp_pre_sum / count($index_three_week));
        }
      }

      // 추출값 저장
      array_push($temp_array, (int) $temp_count);
      array_push($temp_array, (int) $temp_sum);
      array_push($temp_array, (int) $temp_pre_count);
      array_push($temp_array, (int) $temp_pre_sum);
      // 비율 계산
      $temp_rate_cur = get_rate($temp_count, $temp_sum);
      array_push($temp_array, $temp_rate_cur);
      $temp_rate_pre = get_rate($temp_pre_count, $temp_pre_sum);
      array_push($temp_array, $temp_rate_pre);
      // 비교 증감 계산
      $temp_increase = get_compared($temp_rate_pre, $temp_rate_cur);
      array_push($temp_array, $temp_increase);
      //
      array_push($values_summary, $temp_array);
      $i++;
    }

    //send chart json data using ajax
    $returnData['index_summary_devices'] = $index_summary_devices;
    $returnData['values_summary'] = $values_summary;
    $returnData['values_setting'] = $values_setting;
    $jsonTable = json_encode($returnData,JSON_UNESCAPED_UNICODE);

    header('Content-Type: application/json');
    echo $jsonTable;

    // close connect DB
    mysqli_free_result($result);
    mysqli_close($conn);
?>
