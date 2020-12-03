<?php
    require_once($_SERVER['DOCUMENT_ROOT'].'/config/config.php');
    $conn = mysqli_connect($config['host'],$config['user'],$config['password']);
    mysqli_select_db($conn, $config['database']);


    //handling POST Parameter
    $cate_type = $_POST['cateType'];
    $net_type = $_POST['netType'];
    $baseDate = $_POST['baseDate'];

    // php code start
    $index_extract = array();
    $values_extract = array();
    $returnData = array();
    $result = null;
    $stop_word = ["안내","여부","사항","장비","확인","원클릭","품질","후","문의","이력","진단","부탁드립니다.",
                  "증상","종료","문의","양호","정상","고객","철회","파이","특이","간다","내부","외부",
                  "권유","성향","하심","해당","주심","고함","초기","무관","반려","같다","접수","무관","테스트",
                  "연락","바로","처리","모두","있다","없다","하다","드리다","않다","되어다","되다","부터","예정",
                  "드리다","해드리다"];


    /////////////////////////////////////////////////////////////키워드종합요약 DATA 추출 /////////////////////////////////////////////////////
    $extract_word = array();
    $sql= "SELECT extractWord FROM voc_tot_data WHERE regiDate = '".$baseDate."' AND netMethod2='".$net_type."' AND class1='".$cate_type."'";
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

    //send chart json data using ajax
    $returnData['index_extract'] = $index_extract;
    $returnData['values_extract'] = $values_extract;

    $jsonTable = json_encode($returnData,JSON_UNESCAPED_UNICODE);

    header('Content-Type: application/json');
    echo $jsonTable;

    // close connect DB
    mysqli_free_result($result);
    mysqli_close($conn);
?>
