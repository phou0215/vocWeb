<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/config/config.php");
    $conn = mysqli_connect($config['host'],$config['user'],$config['password']);
    mysqli_select_db($conn, $config['database']);
    //string of issueId list
    $id_strings = "";
    //voc_first Table Info
    $issueId_list = array();
    $regiDate_list = array();
    //voc_second Table info
    $model_list = array();
    $devLaunchDate_list = array();
    //voc_third Table info
    $state_list = array();
    //voc_fourth Table info
    $swVer_list = array();
    $planCode_list = array();
    $memo_list = array();
    $class1_list = array();
    //voc_first data extrat

    $result = mysqli_query($conn, "SELECT issueId, regiDate, regiTime, model2, devLaunchDate, state, swVer, planCode, memo, class1 FROM voc_tot_data ORDER BY regiDate DESC, regiTime DESC LIMIT 30");
    while($row = mysqli_fetch_array($result)){
      $id_strings = $id_strings."'".$row['issueId']."', ";
      $innerText = "<a href='/voc/data/view.php?id=".$row['issueId']."' target='_blank'>".$row['issueId']."</a>";
      array_push($issueId_list, $innerText);
      array_push($regiDate_list, $row['regiDate']);
      array_push($model_list, strtoupper($row['model2']));
      array_push($devLaunchDate_list, $row['devLaunchDate']);
      array_push($state_list, $row['state']);
      array_push($swVer_list, $row['swVer']);
      array_push($planCode_list, $row['planCode']);
      $memo_string = mb_strimwidth($row['memo'],'0','30','...','utf-8');
      array_push($memo_list, $memo_string);
      array_push($class1_list, $row['class1']);
    }

    //send chart json data using ajax
    $rows = array();
    $table = array();

    $table['cols'] = array(
        array('label' => '이슈ID', 'type' => 'string'),
        array('label' => '등록일', 'type' => 'string'),
        array('label' => '모델명', 'type' => 'string'),
        array('label' => '출시일', 'type' => 'string'),
        array('label' => '지역(시/도)', 'type' => 'string'),
        array('label' => 'SW버전', 'type' => 'string'),
        array('label' => '메모', 'type' => 'string'),
        array('label' => '분류', 'type' => 'string')
    );

    $i=0;
    while($i<count($issueId_list)){
        $temp = array();
        $temp[] = array('v' => (string) $issueId_list[$i]);
        $temp[] = array('v' => (string) $regiDate_list[$i]);
        $temp[] = array('v' => (string) $model_list[$i]);
        $temp[] = array('v' => (string) $devLaunchDate_list[$i]);
        $temp[] = array('v' => (string) $state_list[$i]);
        $temp[] = array('v' => (string) $swVer_list[$i]);
        $temp[] = array('v' => (string) $memo_list[$i]);
        $temp[] = array('v' => (string) $class1_list[$i]);
        $rows[] = array('c' => $temp);
        $i++;
    }

    $table['rows'] = $rows;
    $jsonTable = json_encode($table,JSON_UNESCAPED_UNICODE);

    header('Content-Type: application/json');
    echo $jsonTable;

    //close connect DB
    mysqli_free_result($result);
    mysqli_close($conn);
?>
