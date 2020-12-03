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

    $retrunData = array();
    $index_spakline_terms = array();
    $index_spakline_weeks = array();
    $index_summary_devices = array();
    $index_extract = array();
    $index_manageCo = [['인천','강북','강남','경기'],['충남','충북','강원'],['경북','경남'],['전북','전남']];
    $index_base = ['수도권','중부','동부','서부'];
    $index_category = [];
    $index_network = ['LTE','5G'];
    $weeks = array('일','월','화','수','목','금','토');
    $stop_word = ["안내","여부","사항","장비","확인","원클릭","품질","후","문의","이력","진단","부탁드립니다.",
                  "증상","종료","문의","양호","정상","고객","철회","파이","특이","간다","내부","외부",
                  "권유","성향","하심","해당","주심","고함","초기","무관","반려","같다","접수","무관","테스트",
                  "연락","바로","처리","모두","있다","없다","하다","드리다","않다","되어다","되다","부터","예정",
                  "드리다","해드리다"];

    //날짜별 또는 모델별 2차원 배열
    $values_5G_rate = array();
    $values_LTE_rate = array();
    $values_5G_voc = array();
    $values_LTE_voc = array();
    $values_5G_subs = array();
    $values_LTE_subs = array();
    $values_compared = array();
    $values_summary = array();
    $values_base = array();
    $values_manageCo = array();
    $values_category = array();
    $values_extract = array();

    $models5Gstring = "";
    $modelsLTEstring = "";
    $model_type = "";
    $model_sort = "";

    // 최신 저장된 데이터 날짜 가져오기
    $sql = "SELECT regiDate FROM voc_tot_data ORDER BY regiDate DESC LIMIT 1";
    $query = mysqli_query($conn, $sql);
    $result = mysqli_fetch_assoc($query);
    $recent = $result['regiDate'];

    //최근 데이터 날짜에서 4주간 기간을 start/end date로 나누어 배열에 넣는다
    $i = 14;
    while($i != 0){
      $day = date('Y-m-d', strtotime($recent.'-'.$i.' days'));
      array_push($index_spakline_terms, $day);
      $i--;
    }
    array_push($index_spakline_terms, $recent);

    $i = 0;
    while($i < count($index_spakline_terms)){
      $temp_week = date('w', strtotime($index_spakline_terms[$i]));
      array_push($index_spakline_weeks, $index_spakline_terms[$i].'('.$weeks[(int) $temp_week].')');
      $i++;
    }

    // model setting check origin/mapping
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


    //5G 디바이스 목록 가져오기
    $result = mysqli_query($conn, 'SELECT model FROM '.$model_type.' WHERE cellType = "5G" AND flag = "1"');
    $index_total = array();
    while($row = mysqli_fetch_assoc($result)){
      array_push($index_total, '"'.$row['model'].'"');
    }
    $models5Gstring = implode(',', $index_total);

    //5G 관심단말 목록 가져오기
    $result = mysqli_query($conn, 'SELECT model FROM '.$model_type.' WHERE cellType = "5G" AND  focusOn= "1" AND flag = "1"');
    while($row = mysqli_fetch_assoc($result)){
      array_push($index_summary_devices, $row['model']);
    }

    //LTE 디바이스 목록 가져오기
    $result = mysqli_query($conn, 'SELECT model FROM '.$model_type.' WHERE cellType = "LTE" AND flag = "1"');
    $index_total = array();
    while($row = mysqli_fetch_assoc($result)){
      array_push($index_total, '"'.$row['model'].'"');
    }
    $modelsLTEstring = implode(',', $index_total);

    //category 목록 가져오기
    $result = mysqli_query($conn, 'SELECT category FROM voc_classes WHERE flag=1');
    // $temp_classes = array();
    while($row = mysqli_fetch_assoc($result)){
      array_push($index_category, $row['category']);
    }

    /////////////////////////////////////////////////////////////SPARK LINE DATA 추출 /////////////////////////////////////////////////////
    // 5G 단말
    $i=0;
    while($i < count($index_spakline_terms)){
      # get voc count 5g by each date
      $sql = 'SELECT COUNT(*) AS cnt FROM voc_tot_data WHERE regiDate = "'.$index_spakline_terms[$i].'" AND '.$model_sort.' IN('.$models5Gstring.')';
      $query = mysqli_query($conn, $sql);
      $result = mysqli_fetch_assoc($query);
      $temp_count = $result['cnt'];
      array_push($values_5G_voc, (int) $temp_count);

      # get subscribers count 5g by each date
      $sql = 'SELECT SUM(numSubs) AS total FROM voc_subscriber WHERE subsDate = "'.$index_spakline_terms[$i].'" AND '.$model_sort.' IN('.$models5Gstring.')';
      $query = mysqli_query($conn, $sql);
      $result = mysqli_fetch_assoc($query);
      $temp_sum = $result['total'];
      if($temp_sum == NULL){
        $temp_sum = 0;
      }
      array_push($values_5G_subs, (int) $temp_sum);
      $temp_rate = get_rate($temp_count, $temp_sum);
      array_push($values_5G_rate, $temp_rate);
      $i++;
    }
    $temp_per_1 = get_compared($values_5G_rate[7], $values_5G_rate[14]);
    $temp_per_2 = get_compared($values_5G_voc[7], $values_5G_voc[14]);
    array_push($values_compared, $temp_per_1);
    array_push($values_compared, $temp_per_2);

    // LTE 단말
    $i = 0;
    while($i < count($index_spakline_terms)){
      $sql = 'SELECT COUNT(*) AS cnt FROM voc_tot_data WHERE regiDate = "'.$index_spakline_terms[$i].'" AND '.$model_sort.' IN('.$modelsLTEstring.')';
      $query = mysqli_query($conn, $sql);
      $result = mysqli_fetch_assoc($query);
      $temp_count = $result['cnt'];
      array_push($values_LTE_voc, (int) $temp_count);

      # get subscribers count 5g by each date
      $sql = 'SELECT SUM(numSubs) AS total FROM voc_subscriber WHERE subsDate = "'.$index_spakline_terms[$i].'" AND '.$model_sort.' IN('.$modelsLTEstring.')';
      $query = mysqli_query($conn, $sql);
      $result = mysqli_fetch_assoc($query);
      $temp_sum = $result['total'];
      if($temp_sum == NULL){
        $temp_sum = 0;
      }
      array_push($values_LTE_subs, (int) $temp_sum);
      $temp_rate = get_rate($temp_count, $temp_sum);
      array_push($values_LTE_rate, $temp_rate);
      $i++;
    }
    $temp_per_1 = get_compared($values_LTE_rate[7], $values_LTE_rate[14]);
    $temp_per_2 = get_compared($values_LTE_voc[7], $values_LTE_voc[14]);
    array_push($values_compared, $temp_per_1);
    array_push($values_compared, $temp_per_2);


    /////////////////////////////////////////////////////////////단말종합요약 DATA 추출 /////////////////////////////////////////////////////

    //Summary 관심목록 디바이스 통계수치
    $i = 0;
    $tot_focus_voc = 0;
    $tot_focus_subs = 0;
    $tot_focus_voc_pre = 0;
    $tot_focus_subs_pre = 0;

    while($i < count($index_summary_devices)){
      //모델마다 값을 담을 배열
      $temp_array = array();

      //recent VOC 개수
      $sql = 'SELECT COUNT(*) AS cnt FROM voc_tot_data WHERE regiDate = "'.$recent.'" AND '.$model_sort.'="'.$index_summary_devices[$i].'"';
      $query = mysqli_query($conn, $sql);
      $result = mysqli_fetch_assoc($query);
      $temp_count = $result['cnt'];

      //recent 가입자 수
      $sql = 'SELECT SUM(numSubs) AS total FROM voc_subscriber WHERE subsDate = "'.$recent.'" AND '.$model_sort.'="'.$index_summary_devices[$i].'"';
      $query = mysqli_query($conn, $sql);
      $result = mysqli_fetch_assoc($query);
      $temp_sum = $result['total'];
      if($temp_sum == NULL){
        $temp_sum = 0;
      }

      //before one week VOC 개수
      $sql = 'SELECT COUNT(*) AS cnt FROM voc_tot_data WHERE regiDate = "'.$index_spakline_terms[7].'" AND '.$model_sort.'="'.$index_summary_devices[$i].'"';
      $query = mysqli_query($conn, $sql);
      $result = mysqli_fetch_assoc($query);
      $temp_pre_count = $result['cnt'];

      //before one week 가입자 수
      $sql = 'SELECT SUM(numSubs) AS total FROM voc_subscriber WHERE subsDate = "'.$index_spakline_terms[7].'" AND '.$model_sort.'="'.$index_summary_devices[$i].'"';
      $query = mysqli_query($conn, $sql);
      $result = mysqli_fetch_assoc($query);
      $temp_pre_sum = $result['total'];
      if($temp_pre_sum == NULL){
        $temp_pre_sum = 0;
      }

      // 추출값 저장
      array_push($temp_array, (int) $temp_count);
      array_push($temp_array, (int) $temp_sum);
      array_push($temp_array, (int) $temp_pre_count);
      array_push($temp_array, (int) $temp_pre_sum);

      $temp_rate_cur = get_rate($temp_count, $temp_sum);
      array_push($temp_array, $temp_rate_cur);
      $temp_rate_pre = get_rate($temp_pre_count, $temp_pre_sum);
      array_push($temp_array, $temp_rate_pre);

      $temp_increase = get_compared($temp_rate_pre, $temp_rate_cur);
      array_push($temp_array, $temp_increase);

      // 최근 일 VOC/SUB  값 저장 및 지난 주 동일일 VOC 저장
      $tot_focus_voc = $tot_focus_voc + $temp_count;
      $tot_focus_subs = $tot_focus_subs + $temp_sum;
      $tot_focus_voc_pre = $tot_focus_voc_pre + $temp_pre_count;
      $tot_focus_subs_pre = $tot_focus_subs_pre + $temp_pre_sum;
      array_push($values_summary, $temp_array);
      $i++;
    }

    //5G 전체 통계
    $temp_tot = [$values_5G_voc[14], $values_5G_subs[14], $values_5G_voc[7], $values_5G_subs[7], $values_5G_rate[14], $values_5G_rate[7], $values_compared[0]];

    //5G 관심 통계
    $temp_focus_rate_cur = get_rate($tot_focus_voc , $tot_focus_subs);
    $temp_focus_rate_pre = get_rate($tot_focus_voc_pre, $tot_focus_subs_pre);
    $temp_focus_compare = get_compared($temp_focus_rate_pre, $temp_focus_rate_cur);
    $temp_focus = [$tot_focus_voc, $tot_focus_subs, $tot_focus_voc_pre, $tot_focus_subs_pre, $temp_focus_rate_cur, $temp_focus_rate_pre, $temp_focus_compare];

    array_unshift($values_summary, $temp_focus);
    array_unshift($values_summary, $temp_tot);
    array_unshift($index_summary_devices, "5G관심");
    array_unshift($index_summary_devices, "5G전체");



    /////////////////////////////////////////////////////////////지역종합요약 DATA 추출 /////////////////////////////////////////////////////

    //Summary 관심목록 디바이스 통계수치
    $i = 0;

    while($i < count($index_base)){
      //모델마다 값을 담을 배열
      $temp_array = array();

      //recent 5G VOC 개수
      $sql = 'SELECT COUNT(*) AS cnt FROM voc_tot_data WHERE regiDate = "'.$recent.'" AND netMethod2="5G" AND netHead LIKE "%'.$index_base[$i].'%"';
      $query = mysqli_query($conn, $sql);
      $result = mysqli_fetch_assoc($query);
      $temp_5G_count = $result['cnt'];

      //recent LTE VOC 개수
      $sql = 'SELECT COUNT(*) AS cnt FROM voc_tot_data WHERE regiDate = "'.$recent.'" AND netMethod2="LTE" AND netHead LIKE "%'.$index_base[$i].'%"';
      $query = mysqli_query($conn, $sql);
      $result = mysqli_fetch_assoc($query);
      $temp_LTE_count = $result['cnt'];

      //before one week 5G VOC 개수
      $sql = 'SELECT COUNT(*) AS cnt FROM voc_tot_data WHERE regiDate = "'.$index_spakline_terms[7].'" AND netMethod2="5G" AND netHead LIKE "%'.$index_base[$i].'%"';
      $query = mysqli_query($conn, $sql);
      $result = mysqli_fetch_assoc($query);
      $temp_5G_pre_count = $result['cnt'];

      //before one week LTE VOC 개수
      $sql = 'SELECT COUNT(*) AS cnt FROM voc_tot_data WHERE regiDate = "'.$index_spakline_terms[7].'" AND netMethod2="LTE" AND netHead LIKE "%'.$index_base[$i].'%"';
      $query = mysqli_query($conn, $sql);
      $result = mysqli_fetch_assoc($query);
      $temp_LTE_pre_count = $result['cnt'];

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
      array_push($values_base, $temp_array);
      $i++;
    }

    /////////////////////////////////////////////////////////////카테고리종합요약 DATA 추출 /////////////////////////////////////////////////////
    //Summary 관심목록 디바이스 통계수치
    $i = 0;

    while($i < count($index_category)){
      //모델마다 값을 담을 배열
      $temp_array = array();

      //recent 5G VOC 개수
      $sql = 'SELECT COUNT(*) AS cnt FROM voc_tot_data WHERE regiDate = "'.$recent.'" AND netMethod2="5G" AND class1="'.$index_category[$i].'"';
      $query = mysqli_query($conn, $sql);
      $result = mysqli_fetch_assoc($query);
      $temp_recent_count = $result['cnt'];

      // //recent LTE VOC 개수
      // $sql = 'SELECT COUNT(*) AS cnt FROM voc_tot_data WHERE regiDate = "'.$recent.'" AND netMethod2="LTE" AND class1="'.$temp_classes[$i].'"';
      // $query = mysqli_query($conn, $sql);
      // $result = mysqli_fetch_assoc($query);
      // $temp_LTE_count = $result['cnt'];
      //
      //before one week 5G VOC 개수
      $sql = 'SELECT COUNT(*) AS cnt FROM voc_tot_data WHERE regiDate = "'.$index_spakline_terms[7].'" AND netMethod2="5G" AND class1="'.$index_category[$i].'"';
      $query = mysqli_query($conn, $sql);
      $result = mysqli_fetch_assoc($query);
      $temp_pre_count = $result['cnt'];
      //
      // //before one week LTE VOC 개수
      // $sql = 'SELECT COUNT(*) AS cnt FROM voc_tot_data WHERE regiDate = "'.$index_spakline_terms[7].'" AND netMethod2="LTE" AND class1="'.$temp_classes[$i].'"';
      // $query = mysqli_query($conn, $sql);
      // $result = mysqli_fetch_assoc($query);
      // $temp_LTE_pre_count = $result['cnt'];

      // 추출값 저장
      array_push($temp_array, (int) $temp_recent_count);
      array_push($temp_array, (int) $temp_pre_count);

      $temp_increase = get_compared($temp_pre_count, $temp_recent_count);
      array_push($temp_array, $temp_increase);
      // $temp_increase_LTE = get_compared($temp_LTE_pre_count, $temp_LTE_count);
      // array_push($temp_array, $temp_increase_LTE);
      //본부 총 데이터 push
      array_push($values_category, $temp_array);
      $i++;
    }

    /////////////////////////////////////////////////////////////키워드종합요약 DATA 추출 /////////////////////////////////////////////////////
    $extract_word = array();
    $sql= "SELECT extractWord FROM voc_tot_data WHERE regiDate = '".$recent."' AND class1='".$index_category[0]."' AND netMethod2='5G'";
    $result = mysqli_query($conn, $sql);

    //$extract_word에 추출된 단어 넣기
    $i = 0;
    while($row=mysqli_fetch_assoc($result)){
      $words = explode(',',$row['extractWord']);
      $j = 0;
      while($j < count($words)){
        array_push($extract_word, $words[$j]);
        $j++;
      }
      $i++;
    }

    //$extract_word에 중복 개수 count 및 key(키워드)와 value(갯수)를 분리
    $num = array_count_values($extract_word);
    arsort($num);
    $j = 0;
    foreach( $num as $key => $value ){
      if($j == 20){
        break;
      }else{
        if(!in_array($key, $stop_word) && mb_strlen($key , 'utf-8') > 1){
          array_push($index_extract, $key);
          array_push($values_extract, $value);
          $j++;
        }else{
          continue;
        }
      }
    }

    /////////////////////////////////////////////////////////////모든 DATA Json formating/////////////////////////////////////////////////////
    //send chart json data using ajax
    $returnData['index_term'] = $index_spakline_terms;
    $returnData['index_week'] = $index_spakline_weeks;
    $returnData['index_summary_devices'] = $index_summary_devices;
    $returnData['index_base'] = $index_base;
    $returnData['index_category'] = $index_category;
    $returnData['index_extract'] = $index_extract;

    $returnData['values_5G_rate'] = $values_5G_rate;
    $returnData['values_5G_voc'] = $values_5G_voc;
    $returnData['values_5G_subs'] = $values_5G_subs;
    $returnData['values_LTE_rate'] = $values_LTE_rate;
    $returnData['values_LTE_voc'] = $values_LTE_voc;
    $returnData['values_LTE_subs'] = $values_LTE_subs;
    $returnData['values_compared'] = $values_compared;
    $returnData['values_summary'] = $values_summary;
    $returnData['values_setting'] = $values_setting;
    $returnData['values_base'] = $values_base;
    $returnData['values_category'] = $values_category;
    $returnData['values_extract'] = $values_extract;

    $jsonTable = json_encode($returnData,JSON_UNESCAPED_UNICODE);

    header('Content-Type: application/json');
    echo $jsonTable;

    // close connect DB
    mysqli_free_result($query);
    mysqli_close($conn);
?>
