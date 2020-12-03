<?php
    ini_set('memory_limit','-1');
    require_once($_SERVER['DOCUMENT_ROOT']."/config/config.php");
    // require_once($_SERVER['DOCUMENT_ROOT']."/voc/data/index.php");
    $conn = mysqli_connect($config['host'],$config['user'],$config['password']);
    mysqli_select_db($conn, $config['database']);

    $redirect = $_POST['re'];
    $base = $_POST['base'];
    $delimiter = ",";
    $filename = "VOC_DATA(".date('Y-m-d H:i:s').")";
    $filename = iconv("UTF-8", "euc-kr", $filename.".csv");

    // Redirect output to a client’s web browser(Excel5)
    header('Content-Type: text/csv; charset=utf-8;');
    header('Content-Disposition: attachment; filename="'. $filename .'";');
    header('Pragma: no-cache');
    header('Expires: 0');
    //Generate file
    $output = fopen('php://output', 'w');

    // create a file pointer connected to the output stream


    // output the column headings
    $field = array("네트워크본부","운용팀","운용사","서비스번호","접수일","접수시간대","접수시간","상담유형1","상담유형2","상담유형3","상담유형4","등록일","상담사조치1","상담사조치2","상담사조치3",
                  "상담사조치4","단말기제조사","단말기모델명","단말기모델명2","단말기코드","단말기출시일","HDVoice단말여부","NETWORK방식2","발생시기1","발생시기2","지역1",
                  "지역2","지역3","시/도","구/군명","요금제코드명","사용자AGENT","단말기애칭","USIM카드명","댁내중계기여부","VOC접수번호","서비스변경일자","메모","메모요약","메모분류","업데이트 유무",
                  "해외로밍 유무","소프트웨어","추출단어","이슈번호");
    for($i = 0; $i<count($field); $i++){
      $field[$i] = iconv("utf-8", "euc-kr//TRANSLIT", $field[$i]);
    }

    fputcsv($output, $field);
    //output the table data
    $result = mysqli_query($conn, $base);
    // loop over the rows, outputting them
    $num = 0;
    while($row = mysqli_fetch_assoc($result)){
      $memoText = str_replace(","," ",$row['memo']);
      $memoText = str_replace("\r\n","",$memoText);
      $memoSumText = str_replace(","," ",$row['memoSum']);
      $memoSumText = str_replace("\r\n","",$memoSumText);
      $extract = str_replace(",","/",$row['extractWord']);
      $list_time = explode(':', $row['regiTime']);
      $hour = $list_time[0];

      $lineData = array($row['netHead'], $row['manageTeam'], $row['manageCo'], $row['serviceNum'], $row['regiDate'], $hour, $row['regiTime'], $row['counsel1'], $row['counsel2'], $row['counsel3'],
      $row['counsel4'], $row['receiveDate'], $row['action1'], $row['action2'], $row['action3'], $row['action4'], $row['manu'], $row['model'], $row['model2'], $row['devCode'],
      $row['devLaunchDate'], $row['hdvoiceFlag'], $row['netMethod2'], $row['ocSpot1'], $row['ocSpot2'], $row['loc1'], $row['loc2'], $row['loc3'], $row['state'], $row['district'],
      $row['planCode'], $row['userAgent'], $row['petName'], $row['usimName'], $row['repeaterFlag'], $row['vocRecieve'], $row['changeDate'], $memoText, $memoSumText, $row['class1'], $row['updateFlag'],
      $row['roamFlag'], $row['swVer'], $extract, $row['issueId']);
      //charset change utf-8 -> euc-kr
      for($i = 0; $i<count($lineData); $i++){
        $lineData[$i] = iconv("utf-8", "euc-kr//TRANSLIT", $lineData[$i]);
      }
      fputcsv($output, $lineData);
    }
    fclose($output);
    // echo "\xEF\xBB\xBF";
    echo $output;
    mysqli_free_result($result);
    mysqli_close($conn);
    exit();
?>
