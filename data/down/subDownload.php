<?php
    ini_set('memory_limit','-1');
    require_once($_SERVER['DOCUMENT_ROOT']."/config/config.php");
    // require_once($_SERVER['DOCUMENT_ROOT']."/voc/data/index.php");
    $conn = mysqli_connect($config['host'],$config['user'],$config['password']);
    mysqli_select_db($conn, $config['database']);

    $redirect = $_POST['re'];
    $base = $_POST['base'];
    $delimiter = ",";
    $filename = "Subscriber_DATA(".date('Y-m-d H:i:s').")";
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
    $field = array("추출일", "모델명", "모델명2", "등록일", "HD-V가입자", "LTE가입자", "5G가입자", "전체가입자", "고유번호");
    //charset change utf-8 -> euc-kr
    for($i = 0; $i<count($field); $i++){
      $field[$i] = iconv("utf-8", "euc-kr//TRANSLIT", $field[$i]);
    }

    fputcsv($output, $field);
    //output the table data
    $result = mysqli_query($conn, $base);
    // loop over the rows, outputting them
    $num = 0;
    while($row = mysqli_fetch_assoc($result)){

      $lineData = array($row['subsDate'], $row['model'], $row['model2'], $row['regiDate'], $row['subsHDV'], $row['subsLTE'], $row['subs5G'], $row['numSubs'], $row['uniqueId']);
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
