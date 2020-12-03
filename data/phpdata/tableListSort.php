<?php
    require_once($_SERVER['DOCUMENT_ROOT']."/config/config.php");
    $conn = mysqli_connect($config['host'],$config['user'],$config['password']);
    mysqli_select_db($conn, $config['database']);
    //string of issueId list
    $id_strings = "";
    //voc_sort_first Table Info
    $issueId_list = array();
    $regiDate_list = array();
    $receiveDate_list = array();
    //voc_sort_second Table info
    $model_list = array();
    $manu_list = array();
    //voc_sort_third Table info
    $state_list = array();
    //voc_sort_fourth Table info
    $swVer_list = array();
    $memo_list = array();
    $class1_list = array();

    $today = date('Y-m-d',strtotime("+1 day"));
    $type = $_GET['type'];
    $select_flag = 0;
    $selection_array = [["issueId","receiveDate"],["model2","manu"],["state"],["swVer","class1","memo"]];

    $sql1 = "";
    $sql2 = "";
    $sql3 = "";
    $sql4 = "";

    $start_date = "";
    $end_date = "";
    $selection = "";
    $keyword = "";

    if(isset($_GET['start'])){
      $start_date = date('Y-m-d', strtotime(trim($_GET['start'])));
    }
    if(isset($_GET['end'])){
      $end_date = date('Y-m-d', strtotime(trim($_GET['end'])));
    }
    if(isset($_GET['select'])){
      $selection = $_GET['select'];
      $i = 0;
      while($i<count($selection_array)){
        if(in_array($selection, $selection_array[$i])){
          $select_flag = $i+1;
          break;
        }
        $i++;
      }
    }
    if(isset($_GET['keyword'])){
      $keyword = $_GET['keyword'];
    }

    //connect DB and get Data depend on $select_flag
    ////////////////////////////////////////////////////__Total Type__////////////////////////////////////////////////////
    if($type == "total"){
      $sql1 = "SELECT issueId, regiDate, receiveDate FROM voc_sort_first";
      //voc_sort_first data extract
      $result = mysqli_query($conn, $sql1);
      while($row = mysqli_fetch_array($result)){
        $id_strings = $id_strings."'".$row['issueId']."', ";
        $innerText = "<a href='/voc/data/view.php?id=".$row['issueId']."' target='_blank'>".$row['issueId']."</a>";
        array_push($issueId_list, $innerText);
        array_push($regiDate_list, $row['regiDate']);
        array_push($receiveDate_list, $row['receiveDate']);
      }
      $id_strings = substr($id_strings, 0, mb_strlen($id_strings,"UTF-8")-2);
      $sql2 = "SELECT issueId, model2, manu FROM voc_sort_second WHERE issueId IN(".$id_strings.")";
      $sql3 = "SELECT issueId, state FROM voc_sort_third WHERE issueId IN(".$id_strings.")";
      $sql4 = "SELECT issueId, swVer, memo, class1 FROM voc_sort_fourth WHERE issueId IN(".$id_strings.")";
      //voc_sort_second data extract
      $result = mysqli_query($conn, $sql2);
      while($row = mysqli_fetch_array($result)){
        array_push($model_list, strtoupper($row['model2']));
        array_push($manu_list, $row['manu']);
      }

      //voc_sort_third data extract
      $result = mysqli_query($conn, $sql3);
      while($row = mysqli_fetch_array($result)){
        array_push($state_list, $row['state']);
      }

      //voc_sort_fourth data extract
      $result = mysqli_query($conn, $sql4);
      while($row = mysqli_fetch_array($result)){
        array_push($swVer_list, $row['swVer']);
        $memo_string = mb_strimwidth($row['memo'],'0','30','...','utf-8');
        array_push($memo_list, $memo_string);
        array_push($class1_list, $row['class1']);
      }
    }
    ////////////////////////////////////////////////////__Term Type__////////////////////////////////////////////////////
    else if($type == "term"){
      ///////only end date ///////
      if($start_date == "" && $end_date != ""){
        $sql1 = "SELECT issueId, regiDate, receiveDate FROM voc_sort_first WHERE regiDate <= '".$end_date."'";
      }
      ///////only start date ///////
      else if($start_date != "" && $end_date == ""){
        $sql1 = "SELECT issueId, regiDate, receiveDate FROM voc_sort_first WHERE regiDate BETWEEN '".$start_date."' AND '".$today."'";
      }
      ///////both start date ///////
      else{
        $sql1 = "SELECT issueId, regiDate, receiveDate FROM voc_sort_first WHERE regiDate BETWEEN '".$start_date."' AND '".$end_date."'";
      }
      //voc_sort_first data extract
      $result = mysqli_query($conn, $sql1);
      while($row = mysqli_fetch_array($result)){
        $id_strings = $id_strings."'".$row['issueId']."', ";
        $innerText = "<a href='/voc/data/view.php?id=".$row['issueId']."' target='_blank'>".$row['issueId']."</a>";
        array_push($issueId_list, $innerText);
        array_push($regiDate_list, $row['regiDate']);
        array_push($receiveDate_list, $row['receiveDate']);
      }
      $id_strings = substr($id_strings, 0, mb_strlen($id_strings,"UTF-8")-2);
      $sql2 = "SELECT issueId, model2, manu FROM voc_sort_second WHERE issueId IN(".$id_strings.")";
      $sql3 = "SELECT issueId, state FROM voc_sort_third WHERE issueId IN(".$id_strings.")";
      $sql4 = "SELECT issueId, swVer, memo, class1 FROM voc_sort_fourth WHERE issueId IN(".$id_strings.")";

      //voc_sort_second data extract
      $result = mysqli_query($conn, $sql2);
      while($row = mysqli_fetch_array($result)){
        array_push($model_list, strtoupper($row['model2']));
        array_push($manu_list, $row['manu']);
      }

      //voc_sort_third data extract
      $result = mysqli_query($conn, $sql3);
      while($row = mysqli_fetch_array($result)){
        array_push($state_list, $row['state']);
      }

      //voc_sort_fourth data extract
      $result = mysqli_query($conn, $sql4);
      while($row = mysqli_fetch_array($result)){
        array_push($swVer_list, $row['swVer']);
        $memo_string = mb_strimwidth($row['memo'],'0','30','...','utf-8');
        array_push($memo_list, $memo_string);
        array_push($class1_list, $row['class1']);
      }
    }
    ////////////////////////////////////////////////////__Keyword Type__////////////////////////////////////////////////////
    else if($type == "keyword"){
      ///////keyword in voc_sort_first table case ///////
      if($select_flag == 0 || $select_flag == 1){
        $sql1 = "SELECT issueId, regiDate, receiveDate FROM voc_sort_first WHERE ".$selection." = '".$keyword."'";
        $result = mysqli_query($conn, $sql1);
        while($row = mysqli_fetch_array($result)){
          $id_strings = $id_strings."'".$row['issueId']."', ";
        $innerText = "<a href='/voc/data/view.php?id=".$row['issueId']."' target='_blank'>".$row['issueId']."</a>";
          array_push($issueId_list, $innerText);
          array_push($regiDate_list, $row['regiDate']);
          array_push($receiveDate_list, $row['receiveDate']);
        }
        $id_strings = substr($id_strings, 0, mb_strlen($id_strings,"UTF-8")-2);
        $sql2 = "SELECT issueId, model2, manu FROM voc_sort_second WHERE issueId IN(".$id_strings.")";
        $sql3 = "SELECT issueId, state FROM voc_sort_third WHERE issueId IN(".$id_strings.")";
        $sql4 = "SELECT issueId, swVer, memo, class1 FROM voc_sort_fourth WHERE issueId IN(".$id_strings.")";

        //voc_sort_second data extract
        $result = mysqli_query($conn, $sql2);
        while($row = mysqli_fetch_array($result)){
          array_push($model_list, strtoupper($row['model2']));
          array_push($manu_list, $row['manu']);
        }

        //voc_sort_third data extract
        $result = mysqli_query($conn, $sql3);
        while($row = mysqli_fetch_array($result)){
          array_push($state_list, $row['state']);
        }

        //voc_sort_fourth data extract
        $result = mysqli_query($conn, $sql4);
        while($row = mysqli_fetch_array($result)){
          array_push($swVer_list, $row['swVer']);
          $memo_string = mb_strimwidth($row['memo'],'0','30','...','utf-8');
          array_push($memo_list, $memo_string);
          array_push($class1_list, $row['class1']);
        }
      }
      ///////keyword in voc_sort_second table case ///////
      else if($select_flag == 2){
        $sql2 = "SELECT issueId, model2, manu FROM voc_sort_second WHERE ".$selection." LIKE '%".$keyword."%'";
        $result = mysqli_query($conn, $sql2);
        while($row = mysqli_fetch_array($result)){
          $id_strings = $id_strings."'".$row['issueId']."', ";
        $innerText = "<a href='/voc/data/view.php?id=".$row['issueId']."' target='_blank'>".$row['issueId']."</a>";
          array_push($issueId_list, $innerText);
          array_push($model_list, strtoupper($row['model2']));
          array_push($manu_list, $row['manu']);
        }
        $id_strings = substr($id_strings, 0, mb_strlen($id_strings,"UTF-8")-2);
        $sql1 = "SELECT issueId, regiDate, receiveDate FROM voc_sort_first WHERE issueId IN(".$id_strings.")";
        $sql3 = "SELECT issueId, state FROM voc_sort_third WHERE issueId IN(".$id_strings.")";
        $sql4 = "SELECT issueId, swVer, memo, class1 FROM voc_sort_fourth WHERE issueId IN(".$id_strings.")";
        //voc_sort_first data extract
        $result = mysqli_query($conn, $sql1);
        while($row = mysqli_fetch_array($result)){
          array_push($regiDate_list, $row['regiDate']);
          array_push($receiveDate_list, $row['receiveDate']);
        }
        //voc_sort_third data extract
        $result = mysqli_query($conn, $sql3);
        while($row = mysqli_fetch_array($result)){
          array_push($state_list, $row['state']);
        }
        //voc_sort_fourth data extract
        $result = mysqli_query($conn, $sql4);
        while($row = mysqli_fetch_array($result)){
          array_push($swVer_list, $row['swVer']);
          $memo_string = mb_strimwidth($row['memo'],'0','30','...','utf-8');
          array_push($memo_list, $memo_string);
          array_push($class1_list, $row['class1']);
        }

      }
      ///////keyword in voc_sort_third table case ///////
      else if($select_flag == 3){
        //voc_sort_third data extract
        $sql3 = "SELECT issueId, state FROM voc_sort_third WHERE ".$selection." = '".$keyword."'";
        $result = mysqli_query($conn, $sql3);
        while($row = mysqli_fetch_array($result)){
          $id_strings = $id_strings."'".$row['issueId']."', ";
        $innerText = "<a href='/voc/data/view.php?id=".$row['issueId']."' target='_blank'>".$row['issueId']."</a>";
          array_push($issueId_list, $innerText);
          array_push($state_list, $row['state']);
        }
        $id_strings = substr($id_strings, 0, mb_strlen($id_strings,"UTF-8")-2);
        $sql1 = "SELECT issueId, regiDate, receiveDate FROM voc_sort_first WHERE issueId IN(".$id_strings.")";
        $sql2 = "SELECT issueId, model2, manu FROM voc_sort_second WHERE issueId IN(".$id_strings.")";
        $sql4 = "SELECT issueId, swVer, memo, class1 FROM voc_sort_fourth WHERE issueId IN(".$id_strings.")";

        //voc_sort_first data extract
        $result = mysqli_query($conn, $sql1);
        while($row = mysqli_fetch_array($result)){
          array_push($regiDate_list, $row['regiDate']);
          array_push($receiveDate_list, $row['receiveDate']);
        }

        //voc_sort_second data extract
        $result = mysqli_query($conn, $sql2);
        while($row = mysqli_fetch_array($result)){
          array_push($model_list, strtoupper($row['model2']));
          array_push($manu_list, $row['manu']);
        }

        //voc_sort_fourth data extract
        $result = mysqli_query($conn, $sql4);
        while($row = mysqli_fetch_array($result)){
          array_push($swVer_list, $row['swVer']);
          $memo_string = mb_strimwidth($row['memo'],'0','30','...','utf-8');
          array_push($memo_list, $memo_string);
          array_push($class1_list, $row['class1']);
        }
      }
      ///////keyword in voc_sort_fourth table case ///////
      else{
        //voc_sort_fourth data extract
        $sql4 = "SELECT issueId, swVer, memo, class1 FROM voc_sort_fourth WHERE ".$selection." LIKE '%".$keyword."%'";
        $result = mysqli_query($conn, $sql4);
        while($row = mysqli_fetch_array($result)){
          $id_strings = $id_strings."'".$row['issueId']."', ";
        $innerText = "<a href='/voc/data/view.php?id=".$row['issueId']."' target='_blank'>".$row['issueId']."</a>";
          array_push($issueId_list, $innerText);
          array_push($swVer_list, $row['swVer']);
          $memo_string = mb_strimwidth($row['memo'],'0','30','...','utf-8');
          array_push($memo_list, $memo_string);
          array_push($class1_list, $row['class1']);
        }
        $id_strings = substr($id_strings, 0, mb_strlen($id_strings,"UTF-8")-2);
        $sql1 = "SELECT issueId, regiDate, receiveDate FROM voc_sort_first WHERE issueId IN(".$id_strings.")";
        $sql2 = "SELECT issueId, model2, manu FROM voc_sort_second WHERE issueId IN(".$id_strings.")";
        $sql3 = "SELECT issueId, state FROM voc_sort_third WHERE issueId IN(".$id_strings.")";
        //voc_sort_first data extract
        $result = mysqli_query($conn, $sql1);
        while($row = mysqli_fetch_array($result)){
          array_push($regiDate_list, $row['regiDate']);
          array_push($receiveDate_list, $row['receiveDate']);
        }

        //voc_sort_second data extract
        $result = mysqli_query($conn, $sql2);
        while($row = mysqli_fetch_array($result)){
          array_push($model_list, strtoupper($row['model2']));
          array_push($manu_list, $row['manu']);
        }

        //voc_sort_third data extract
        $result = mysqli_query($conn, $sql3);
        while($row = mysqli_fetch_array($result)){
          array_push($state_list, $row['state']);
        }
      }
    }
    ////////////////////////////////////////////////////__Complex Type__////////////////////////////////////////////////////
    else if($type == "complex"){
      ///////keyword in voc_sort_first table case ///////
      if($select_flag == 0 || $select_flag == 1){
        ///////only end date ///////
        if($start_date == "" && $end_date != ""){
          $sql1 = "SELECT issueId, regiDate, receiveDate FROM voc_sort_first WHERE (regiDate <= '".$end_date."') AND (".$selection." = '".$keyword."')";
        }
        ///////only start date ///////
        else if($start_date != "" && $end_date == ""){
          $sql1 = "SELECT issueId, regiDate, receiveDate FROM voc_sort_first WHERE (regiDate BETWEEN '".$start_date."' AND '".$today."') AND (".$selection." = '".$keyword."')";
        }
        ///////both start date ///////
        else{
          $sql1 = "SELECT issueId, regiDate, receiveDate FROM voc_sort_first WHERE (regiDate BETWEEN '".$start_date."' AND '".$end_date."') AND (".$selection." = '".$keyword."')";
        }
        $result = mysqli_query($conn, $sql1);
        while($row = mysqli_fetch_array($result)){
          $id_strings = $id_strings."'".$row['issueId']."', ";
        $innerText = "<a href='/voc/data/view.php?id=".$row['issueId']."' target='_blank'>".$row['issueId']."</a>";
          array_push($issueId_list, $innerText);
          array_push($regiDate_list, $row['regiDate']);
          array_push($receiveDate_list, $row['receiveDate']);
        }
        $id_strings = substr($id_strings, 0, mb_strlen($id_strings,"UTF-8")-2);
        $sql2 = "SELECT issueId, model2, manu FROM voc_sort_second WHERE issueId IN(".$id_strings.")";
        $sql3 = "SELECT issueId, state FROM voc_sort_third WHERE issueId IN(".$id_strings.")";
        $sql4 = "SELECT issueId, swVer, memo, class1 FROM voc_sort_fourth WHERE issueId IN(".$id_strings.")";

        //voc_sort_second data extract
        $result = mysqli_query($conn, $sql2);
        while($row = mysqli_fetch_array($result)){
          array_push($model_list, strtoupper($row['model2']));
          array_push($manu_list, $row['manu']);
        }

        //voc_sort_third data extract
        $result = mysqli_query($conn, $sql3);
        while($row = mysqli_fetch_array($result)){
          array_push($state_list, $row['state']);
        }

        //voc_sort_fourth data extract
        $result = mysqli_query($conn, $sql4);
        while($row = mysqli_fetch_array($result)){
          array_push($swVer_list, $row['swVer']);
          $memo_string = mb_strimwidth($row['memo'],'0','30','...','utf-8');
          array_push($memo_list, $memo_string);
          array_push($class1_list, $row['class1']);
        }
      }
      ///////keyword in voc_sort_second table case ///////
      else if($select_flag == 2){
        ///////only end date ///////
        if($start_date == "" && $end_date != ""){
          $sql2 = "SELECT issueId, model2, manu FROM voc_sort_second WHERE (regiDate <= '".$end_date."') AND (".$selection." LIKE '%".$keyword."%')";
        }
        ///////only start date ///////
        else if($start_date != "" && $end_date == ""){
          $sql2 = "SELECT issueId, model2, manu FROM voc_sort_second WHERE (regiDate BETWEEN '".$start_date."' AND '".$today."') AND (".$selection." LIKE '%".$keyword."%')";
        }
        ///////both start date ///////
        else{
          $sql2 = "SELECT issueId, model2, manu FROM voc_sort_second WHERE (regiDate BETWEEN '".$start_date."' AND '".$end_date."') AND (".$selection." LIKE '%".$keyword."%')";
        }
        $result = mysqli_query($conn, $sql2);
        while($row = mysqli_fetch_array($result)){
          $id_strings = $id_strings."'".$row['issueId']."', ";
        $innerText = "<a href='/voc/data/view.php?id=".$row['issueId']."' target='_blank'>".$row['issueId']."</a>";
          array_push($issueId_list, $innerText);
          array_push($model_list, strtoupper($row['model2']));
          array_push($manu_list, $row['manu']);
        }
        $id_strings = substr($id_strings, 0, mb_strlen($id_strings,"UTF-8")-2);
        $sql1 = "SELECT issueId, regiDate, receiveDate FROM voc_sort_first WHERE issueId IN(".$id_strings.")";
        $sql3 = "SELECT issueId, state FROM voc_sort_third WHERE issueId IN(".$id_strings.")";
        $sql4 = "SELECT issueId, swVer, memo, class1 FROM voc_sort_fourth WHERE issueId IN(".$id_strings.")";
        //voc_sort_first data extract
        $result = mysqli_query($conn, $sql1);
        while($row = mysqli_fetch_array($result)){
          array_push($regiDate_list, $row['regiDate']);
          array_push($receiveDate_list, $row['receiveDate']);
        }
        //voc_sort_third data extract
        $result = mysqli_query($conn, $sql3);
        while($row = mysqli_fetch_array($result)){
          array_push($state_list, $row['state']);
        }
        //voc_sort_fourth data extract
        $result = mysqli_query($conn, $sql4);
        while($row = mysqli_fetch_array($result)){
          array_push($swVer_list, $row['swVer']);
          $memo_string = mb_strimwidth($row['memo'],'0','30','...','utf-8');
          array_push($memo_list, $memo_string);
          array_push($class1_list, $row['class1']);
        }
      }
      ///////keyword in voc_sort_third table case ///////
      else if($select_flag == 3){
        ///////only end date ///////
        if($start_date == "" && $end_date != ""){
          $sql3 = "SELECT issueId, state FROM voc_sort_third WHERE (regiDate <= '".$end_date."') AND (".$selection." = '".$keyword."')";
        }
        ///////only start date ///////
        else if($start_date != "" && $end_date == ""){
          $sql3 = "SELECT issueId, state FROM voc_sort_third WHERE (regiDate BETWEEN '".$start_date."' AND '".$today."') AND (".$selection." = '".$keyword."')";
        }
        ///////both start date ///////
        else{
          $sql3 = "SELECT issueId, state FROM voc_sort_third WHERE (regiDate BETWEEN '".$start_date."' AND '".$end_date."') AND (".$selection." = '".$keyword."')";
        }
        //voc_sort_third data extract
        $result = mysqli_query($conn, $sql3);
        while($row = mysqli_fetch_array($result)){
          $id_strings = $id_strings."'".$row['issueId']."', ";
        $innerText = "<a href='/voc/data/view.php?id=".$row['issueId']."' target='_blank'>".$row['issueId']."</a>";
          array_push($issueId_list, $innerText);
          array_push($state_list, $row['state']);
        }
        $id_strings = substr($id_strings, 0, mb_strlen($id_strings,"UTF-8")-2);
        $sql1 = "SELECT issueId, regiDate, receiveDate FROM voc_sort_first WHERE issueId IN(".$id_strings.")";
        $sql2 = "SELECT issueId, model2, manu FROM voc_sort_second WHERE issueId IN(".$id_strings.")";
        $sql4 = "SELECT issueId, swVer, memo, class1 FROM voc_sort_fourth WHERE issueId IN(".$id_strings.")";

        //voc_sort_first data extract
        $result = mysqli_query($conn, $sql1);
        while($row = mysqli_fetch_array($result)){
          array_push($regiDate_list, $row['regiDate']);
          array_push($receiveDate_list, $row['receiveDate']);
        }

        //voc_sort_second data extract
        $result = mysqli_query($conn, $sql2);
        while($row = mysqli_fetch_array($result)){
          array_push($model_list, strtoupper($row['model2']));
          array_push($manu_list, $row['manu']);
        }

        //voc_sort_fourth data extract
        $result = mysqli_query($conn, $sql4);
        while($row = mysqli_fetch_array($result)){
          array_push($swVer_list, $row['swVer']);
          $memo_string = mb_strimwidth($row['memo'],'0','30','...','utf-8');
          array_push($memo_list, $memo_string);
          array_push($class1_list, $row['class1']);
        }
      }
      ///////keyword in voc_sort_fourth table case ///////
      else{
        ///////only end date ///////
        if($start_date == "" && $end_date != ""){
          $sql4 = "SELECT issueId, swVer, memo, class1 FROM voc_sort_fourth WHERE (regiDate <= '".$end_date."') AND (".$selection." LIKE '%".$keyword."%')";
        }
        ///////only start date ///////
        else if($start_date != "" && $end_date == ""){
          $sql4 = "SELECT issueId, swVer, memo, class1 FROM voc_sort_fourth WHERE (regiDate BETWEEN '".$start_date."' AND '".$today."') AND (".$selection." LIKE '%".$keyword."%')";
        }
        ///////both start date ///////
        else{
          $sql4 = "SELECT issueId, swVer, memo, class1 FROM voc_sort_fourth WHERE (regiDate BETWEEN '".$start_date."' AND '".$end_date."') AND (".$selection." LIKE '%".$keyword."%')";
        }
        //voc_sort_fourth data extract
        $result = mysqli_query($conn, $sql4);
        while($row = mysqli_fetch_array($result)){
          $id_strings = $id_strings."'".$row['issueId']."', ";
        $innerText = "<a href='/voc/data/view.php?id=".$row['issueId']."' target='_blank'>".$row['issueId']."</a>";
          array_push($issueId_list, $innerText);
          array_push($swVer_list, $row['swVer']);
          $memo_string = mb_strimwidth($row['memo'],'0','30','...','utf-8');
          array_push($memo_list, $memo_string);
          array_push($class1_list, $row['class1']);
        }
        $id_strings = substr($id_strings, 0, mb_strlen($id_strings,"UTF-8")-2);
        $sql1 = "SELECT issueId, regiDate, receiveDate FROM voc_sort_first WHERE issueId IN(".$id_strings.")";
        $sql2 = "SELECT issueId, model2, manu FROM voc_sort_second WHERE issueId IN(".$id_strings.")";
        $sql3 = "SELECT issueId, state FROM voc_sort_third WHERE issueId IN(".$id_strings.")";
        //voc_sort_first data extract
        $result = mysqli_query($conn, $sql1);
        while($row = mysqli_fetch_array($result)){
          array_push($regiDate_list, $row['regiDate']);
          array_push($receiveDate_list, $row['receiveDate']);
        }

        //voc_sort_second data extract
        $result = mysqli_query($conn, $sql2);
        while($row = mysqli_fetch_array($result)){
          array_push($model_list, strtoupper($row['model2']));
          array_push($manu_list, $row['manu']);
        }

        //voc_sort_third data extract
        $result = mysqli_query($conn, $sql3);
        while($row = mysqli_fetch_array($result)){
          array_push($state_list, $row['state']);
        }
      }
    }

    //send chart json data using ajax
    $rows = array();
    $table = array();

    $table["cols"] = array(
        array("label" => "이슈ID", "type" => "string"),
        array("label" => "접수일", "type" => "string"),
        array("label" => "제조사", "type" => "string"),
        array("label" => "모델명", "type" => "string"),
        array("label" => "등록일", "type" => "string"),
        array("label" => "지역(시/도)", "type" => "string"),
        array("label" => "SW버전", "type" => "string"),
        array("label" => "메모", "type" => "string"),
        array("label" => "분류", "type" => "string")
    );

    $i=0;
    while($i<count($issueId_list)){
        $temp = array();
        $temp[] = array("v" => (string) $issueId_list[$i]);
        $temp[] = array("v" => (string) $regiDate_list[$i]);
        $temp[] = array("v" => (string) $manu_list[$i]);
        $temp[] = array("v" => (string) $model_list[$i]);
        $temp[] = array("v" => (string) $receiveDate_list[$i]);
        $temp[] = array("v" => (string) $state_list[$i]);
        $temp[] = array("v" => (string) $swVer_list[$i]);
        $temp[] = array("v" => (string) $memo_list[$i]);
        $temp[] = array("v" => (string) $class1_list[$i]);
        $rows[] = array("c" => $temp);
        $i++;
    }

    $table["rows"] = $rows;
    $jsonTable = json_encode($table,JSON_UNESCAPED_UNICODE);

    header('Content-Type: application/json');
    echo $jsonTable;

    //close connect DB
    mysqli_free_result($result);
    mysqli_close($conn);
?>
