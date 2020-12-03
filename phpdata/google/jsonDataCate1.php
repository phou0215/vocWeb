<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/config/config.php");
    $conn = mysqli_connect($config['host'],$config['user'],$config['password']);
    mysqli_select_db($conn, $config['database']);
    $class = array("중계기","데이터","통화불량","문자","분류없음","통화권이탈","APP","음질불량","부가서비스","OMD","POS","교품/철회","VOLTE","베터리");
    $values = array();

    $i=0;
    while($i<count($class)){
        $result = mysqli_query($conn,"SELECT COUNT(*) as cnt FROM voc_tot_data WHERE class1='".$class[$i]."'");
        $row = mysqli_fetch_array($result);
        $count_num = $row["cnt"];
        array_push($values, $count_num);
        $i++;
    }

    //send chart json data using ajax
    $rows = array();
    $table = array();
    $flag = true;

    $table['cols'] = array(
        array('label' => 'items', 'type' => 'string'),
        array('label' => 'count', 'type' => 'number'),
    );

    $i=0;
    while($i<count($class)){
        $temp = array();
        $temp[] = array('v' => (string) $class[$i]);
        $temp[] = array('v' => (int) $values[$i]);
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
