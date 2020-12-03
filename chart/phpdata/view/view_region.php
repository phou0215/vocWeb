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
    $index_base = ['인천','강북','강남','경기','충남','충북','강원','경북','경남','전북','전남'];
    $region_code = array(
      "KR-31"=>"경북",
      "KR-49"=>"전남",
      "KR-48"=>"경남",
      "KR-45"=>"전북",
      "KR-44"=>"충남",
      "KR-47"=>"경북",
      "KR-46"=>"전남",
      "KR-41"=>"경기",
      "KR-43"=>"충북",
      "KR-42"=>"강원",
      "KR-27"=>"경북",
      "KR-11"=>"서울",
      "KR-50"=>"충남",
      "KR-29"=>"전남",
      "KR-28"=>"인천",
      "KR-30"=>"충남",
      "KR-26"=>"경남"
    );
    $index_terms = array();
    $index_weeks = array();
    $index_ex_holiday = array();
    $index_three_week = array();
    $values_region = array();
    $values_base = array();

    $returnData = array();

    $holidayString = "";
    $threeWeekString = "";

    $weeks = array('일','월','화','수','목','금','토');
    $result = null;


    //Set model_type
    $result = mysqli_query($conn, "SELECT * FROM settings");
    $row = mysqli_fetch_array($result);

    //상태 설정값 가져오기
    $values_setting = [[$row["oneweekNormal"], $row["oneweekCaution1"], $row["oneweekCaution2"], $row["oneweekDanger"]],
    [$row["holidayNormal"], $row["holidayCaution1"], $row["holidayCaution2"], $row["holidayDanger"]],
    [$row["threeweekNormal"], $row["threeweekCaution1"], $row["threeweekCaution2"], $row["threeweekDanger"]]];


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
    while($i < count($index_base)){

      $temp_array = array();
      $temp_5G_count = 0;
      $temp_LTE_count = 0;
      $temp_5G_pre_count = 0;
      $temp_LTE_pre_count = 0;

      //recent 5G VOC 개수
      $sql = 'SELECT COUNT(*) AS cnt FROM voc_tot_data WHERE regiDate = "'.$recent.'" AND netMethod2="5G" AND manageCo LIKE "%'.$index_base[$i].'%"';
      $result = mysqli_query($conn, $sql);
      $row = mysqli_fetch_assoc($result);
      $temp_5G_count = $row['cnt'];

      //recent LTE VOC 개수
      $sql = 'SELECT COUNT(*) AS cnt FROM voc_tot_data WHERE regiDate = "'.$recent.'" AND netMethod2="LTE" AND manageCo LIKE "%'.$index_base[$i].'%"';
      $result = mysqli_query($conn, $sql);
      $row = mysqli_fetch_assoc($result);
      $temp_LTE_count = $row['cnt'];

      //비교 선택이 전주일 경우
      if ($avg_type == "1"){

        //비교 5G VOC 개수
        $sql = 'SELECT COUNT(*) AS cnt FROM voc_tot_data WHERE regiDate = "'.$oneWeek.'" AND netMethod2="5G" AND manageCo LIKE "%'.$index_base[$i].'%"';
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $temp_5G_pre_count = $row['cnt'];

        //비교 5G VOC 개수
        $sql = 'SELECT COUNT(*) AS cnt FROM voc_tot_data WHERE regiDate = "'.$oneWeek.'" AND netMethod2="LTE" AND manageCo LIKE "%'.$index_base[$i].'%"';
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $temp_LTE_pre_count = $row['cnt'];

      }
      //비교 선택이 한달 평일 평균일 경우
      else if($avg_type == "2"){

        //비교 5G VOC 개수
        $sql = 'SELECT COUNT(*) AS cnt FROM voc_tot_data WHERE regiDate IN ('.$holidayString.') AND netMethod2="5G" AND manageCo LIKE "%'.$index_base[$i].'%"';
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $temp_5G_pre_count = round($row['cnt']/count($index_ex_holiday));

        //비교 5G VOC 개수
        $sql = 'SELECT COUNT(*) AS cnt FROM voc_tot_data WHERE regiDate IN ('.$holidayString.') AND netMethod2="LTE" AND manageCo LIKE "%'.$index_base[$i].'%"';
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $temp_LTE_pre_count = round($row['cnt']/count($index_ex_holiday));
      }
      //비교 선택이 3주 동일 평균일 경우
      else{

        //비교 5G VOC 개수
        $sql = 'SELECT COUNT(*) AS cnt FROM voc_tot_data WHERE regiDate IN ('.$threeWeekString.') AND netMethod2="5G" AND manageCo LIKE "%'.$index_base[$i].'%"';
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $temp_5G_pre_count = round($row['cnt']/count($index_three_week));

        //비교 5G VOC 개수
        $sql = 'SELECT COUNT(*) AS cnt FROM voc_tot_data WHERE regiDate IN ('.$threeWeekString.') AND netMethod2="LTE" AND manageCo LIKE "%'.$index_base[$i].'%"';
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $temp_LTE_pre_count = round($row['cnt']/count($index_three_week));
      }

      // 추출값 저장
      array_push($temp_array, (int) $temp_5G_count);
      array_push($temp_array, (int) $temp_LTE_count);
      array_push($temp_array, (int) $temp_5G_pre_count);
      array_push($temp_array, (int) $temp_LTE_pre_count);

      $temp_increase_5G = get_compared($temp_5G_pre_count, $temp_5G_count);
      array_push($temp_array, $temp_increase_5G);
      $temp_increase_LTE = get_compared($temp_LTE_pre_count, $temp_LTE_count);
      array_push($temp_array, $temp_increase_LTE);
      //본부 총 데이터 push
      $values_region[$index_base[$i]] = $temp_array;
      $i++;
    }
    //지역 코드에 맞게 값 입력
    while($element = each($region_code)){
         if ($element["key"] == "KR-11"){
           $temp_array = [$values_region["강북"], $values_region["강남"]];
           $values_base["KR-11"] = $temp_array;
         }else{
           $values_base[$element["key"]] = $values_region[$element["value"]];
         }
       }

    //send chart json data using ajax
    $returnData['index_base'] = $index_base;
    $returnData['index_code'] = $region_code;
    $returnData['values_region'] = $values_region;
    $returnData['values_base'] = $values_base;
    $returnData['values_setting'] = $values_setting;
    $jsonTable = json_encode($returnData,JSON_UNESCAPED_UNICODE);

    header('Content-Type: application/json');
    echo $jsonTable;

    // close connect DB
    mysqli_free_result($result);
    mysqli_close($conn);
?>
