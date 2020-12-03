<?php
    session_start();
    if(!$_SESSION){
        session_start();
        if(isset($_SESSION['is_login'])!= true){
          echo "<script>
                  alert('접속을 위해 로그인이 필요합니다.');
                  location.href='/voc/phpdata/signin.php'
                </script>";}}

    $root = $_SERVER['DOCUMENT_ROOT'];
    require_once($root."/config/config.php");
    $conn = mysqli_connect($config['host'],$config['user'],$config['password']);
    mysqli_select_db($conn, $config['database']);

    $today = date('Y-m-d');
    $select_flag = 0;
    // $selection_array = [["issueId","receiveDate"],["model2","manu"],["netHead"],["swVer","class1","memo"]];
    $selection = "";
    $selection_2 = "";
    $keyword = "";
    $keyword_2 = "";
    $date_type = "";
    $total_count = 0;
    $hole  = 0;
    $print = "";
    $result = null;

    $download_base = "";
    $download_table_type = "";
    $search_type = "";


    /////////////////////////////////////////////////////////////////// 각 변수 및 설정 값 변경/////////////////////////////////////////////////////////////////////////////
    /* Paging Start */
    if(isset($_GET['page'])){
        $page = (int) $_GET['page'];
    }else{
        $page = 1;
    }

    /* keyword search multi or single check and default 0(single)*/
    if(isset($_GET['selectType'])){
        $selectType = $_GET['selectType'];
    }else{
        $selectType = "0";
    }

    /*one Page set*/
    //1Page당 보여줄 개수
    if(isset($_GET['onePage'])){
        $onePage = (int) $_GET['onePage'];
    }else{
        $onePage = 20;
    }

    //only searchValue search
    if(isset($_GET['indexName']) && isset($_GET['searchValue']) && !isset($_GET['startDate']) && !isset($_GET['endDate'])){
      //Multi Search Type
      if($selectType == "1"){
        $temp_selections = explode(",",($_GET['indexName']));
        $oriSelection = $_GET['indexName'];
        $selection = trim($temp_selections[0]);
        $selection_2 = trim($temp_selections[1]);
        $keyword = trim($_GET['searchValue']);
        $keyword_2 = trim($_GET['searchValue_2']);
      //Single Search Type
      }else{
        $selection = trim($_GET['indexName']);
        $keyword = trim($_GET['searchValue']);
      }
      $startDate = NULL;
      $endDate = NULL;
      $flag = "Y";
      $dateFlag = "N";

    //combination searchValue and termValue search
    }else if(isset($_GET['indexName']) && isset($_GET['searchValue']) && (isset($_GET['startDate']) || isset($_GET['endDate']))){
      //Multi Search Type
      if($selectType == "1"){
        $temp_selections = explode(",",($_GET['indexName']));
        $oriSelection = $_GET['indexName'];
        $selection = trim($temp_selections[0]);
        $selection_2 = trim($temp_selections[1]);
        $keyword = trim($_GET['searchValue']);
        $keyword_2 = trim($_GET['searchValue_2']);
      //Single Search Type
      }else{
        $selection = trim($_GET['indexName']);
        $keyword = trim($_GET['searchValue']);
      }

      $flag = "Y";
      $dateFlag = "Y";

      //Date Type check :
      // 0 --> start date empty
      // 1 --> end date empty
      // 2 --> neither start date nor end date empty
      if(!isset($_GET['startDate']) && isset($_GET['endDate'])){
        $date_type = "0";
        $startDate = NULL;
        $endDate = $_GET['endDate'];
      }else if(isset($_GET['startDate']) && !isset($_GET['endDate'])){
        $date_type = "1";
        $startDate = $_GET['startDate'];
        $endDate = $today;
      }else{
        $date_type = "2";
        $startDate = $_GET['startDate'];
        $endDate = $_GET['endDate'];
      }

      //only termValue search
    }else if(!isset($_GET['indexName']) && !isset($_GET['searchValue']) && (isset($_GET['startDate']) || isset($_GET['endDate']))){
      $flag = "N";
      $dateFlag = "Y";

      //Date Type check :
      // 0 --> start date empty
      // 1 --> end date empty
      // 2 --> neither start date nor end date empty
      if(!isset($_GET['startDate']) && isset($_GET['endDate'])){
        $date_type = "0";
        $startDate = NULL;
        $endDate = $_GET['endDate'];
      }else if(isset($_GET['startDate']) && !isset($_GET['endDate'])){
        $date_type = "1";
        $startDate = $_GET['startDate'];
        $endDate = $today;
      }else{
        $date_type = "2";
        $startDate = $_GET['startDate'];
        $endDate = $_GET['endDate'];
      }
       // none condition total search
    }
    else{
        $flag = "N";
        $dateFlag = "N";
    }
    $result = mysqli_query($conn, "SELECT count(*) as cnt FROM voc_tot_data");
    $row = mysqli_fetch_assoc($result);
    $hole = $row['cnt'];
    if($hole == 0){
      mysqli_free_result($result);
      mysqli_close($conn);
      echo "<script>
              alert('저장된 데이터가 없습니다.');
            </script>";
    }
    $currentLimit = ($page * $onePage) - $onePage;

    //////////////////////////////////////////////////////////total search(no condition)//////////////////////////////////////////////////////
    if($flag == "N" && $dateFlag == "N"){
        $sql = "SELECT issueId, regiDate, receiveDate, regiTime, model, model2, manu, netHead, swVer, memo, class1 FROM voc_tot_data ORDER BY regiDate DESC, issueId ASC, model2 DESC LIMIT ".$currentLimit.",".$onePage;
        $sql_count = "SELECT count(*) as cnt FROM voc_tot_data";
        ///////////Excel download variables////////////
        $download_base = "SELECT * FROM voc_tot_data ORDER BY regiDate DESC, issueId ASC, model2 DESC";
        $search_type = "total";
        ///////////////////////////////////////////////
        $result = mysqli_query($conn, $sql_count);
        $row = mysqli_fetch_assoc($result);
        $total_count = $row['cnt'];
        // 1st table put in the row array\
        if($total_count == 0){
            mysqli_free_result($result);
            mysqli_close($conn);
            echo "<script>
                    alert('조회된 데이터가 없습니다.');
                    location.href = '/voc/data/index.php';
                  </script>";
        }
        //voc_tot_data data extract
        $result = mysqli_query($conn, $sql);
        //set Page button bottom
        setPage($conn, $result, $total_count);
    //////////////////////////////////////////////////////////Term search(date condition)//////////////////////////////////////////////////////
    }else if($flag == "N" && $dateFlag == "Y"){
      // 0 --> start date empty
      // 1 --> end date empty
      // 2 --> neither start date nor end date empty
      ///////only end date ///////
      $search_type = "term";
      if($date_type == "0"){
        $sql = "SELECT issueId, regiDate, receiveDate, regiTime, model, model2, manu, netHead, swVer, memo, class1 FROM voc_tot_data WHERE regiDate <= '".$endDate."' ORDER BY regiDate DESC, issueId ASC, model2 DESC LIMIT ".$currentLimit.",".$onePage;
        $sql_count = "SELECT count(*) as cnt FROM voc_tot_data WHERE regiDate <= '".$endDate."'";
        ///////////Excel download variables////////////
        $download_base = "SELECT * FROM voc_tot_data WHERE regiDate <= '".$endDate."' ORDER BY regiDate DESC, issueId ASC, model2 DESC";
      }
      ///////only start date ///////
      else if($date_type == "1"){
        $sql = "SELECT issueId, regiDate, receiveDate, regiTime, model, model2, manu, netHead, swVer, memo, class1 FROM voc_tot_data WHERE regiDate BETWEEN '".$startDate."' AND '".$today."' ORDER BY regiDate DESC, issueId ASC, model2 DESC LIMIT ".$currentLimit.",".$onePage;
        $sql_count = "SELECT count(*) as cnt FROM voc_tot_data WHERE regiDate BETWEEN '".$startDate."' AND '".$today."'";
        ///////////Excel download variables////////////
        $download_base = "SELECT * FROM voc_tot_data WHERE regiDate BETWEEN '".$startDate."' AND '".$today."' ORDER BY regiDate DESC, issueId ASC, model2 DESC";

      }
      ///////both start date ///////
      else{
        $sql = "SELECT issueId, regiDate, receiveDate, regiTime, model, model2, manu, netHead, swVer, memo, class1 FROM voc_tot_data WHERE regiDate BETWEEN '".$startDate."' AND '".$endDate."' ORDER BY regiDate DESC, issueId ASC, model2 DESC LIMIT ".$currentLimit.",".$onePage;
        $sql_count = "SELECT count(*) as cnt FROM voc_tot_data WHERE regiDate BETWEEN '".$startDate."' AND '".$endDate."'";
        ///////////Excel download variables////////////
        $download_base = "SELECT * FROM voc_tot_data WHERE regiDate BETWEEN '".$startDate."' AND '".$endDate."' ORDER BY regiDate DESC, issueId ASC, model2 DESC";
      }

      $result = mysqli_query($conn, $sql_count);
      $row = mysqli_fetch_assoc($result);
      $total_count = $row['cnt'];
      // 1st table put in the row array\
      if($total_count == 0){
            mysqli_free_result($result);
            mysqli_close($conn);
            echo "<script>
                    alert('조회된 데이터가 없습니다.');
                    location.href = '/voc/data/index.php';
                  </script>";
      }
      //voc_tot_data data extract
      $result = mysqli_query($conn, $sql);
      //set Page button bottom
      setPage($conn, $result, $total_count);
    //////////////////////////////////////////////////////////keyword search(keyword condition)//////////////////////////////////////////////////////
    }else if($flag == "Y" && $dateFlag == "N"){
        ///////keyword in voc_tot_data table case ///////
        $search_type = "keyword";
        //Single Search Type
      	if($selectType == "0"){
          # memo내용 검색인 경우
          if($selection == 'memo'){
              ///////keyword string in table index column value case ///////
              $sql = "SELECT issueId, regiDate, receiveDate, regiTime, model, model2, manu, netHead, swVer, memo, class1 FROM voc_tot_data WHERE ".$selection." REGEXP '".$keyword."' ORDER BY regiDate DESC, issueId ASC, model2 DESC LIMIT ".$currentLimit.",".$onePage;
              ///////////Excel download variables////////////
              $download_base = "SELECT * FROM voc_tot_data WHERE ".$selection." REGEXP '".$keyword."' ORDER BY regiDate DESC, issueId ASC, model2 DESC";
              $sql_count = "SELECT count(*) as cnt FROM voc_tot_data WHERE ".$selection." REGEXP '".$keyword."'";
          }
          # memo내용 검색이 아닌 경우
          else{
              ///////keyword string in table index column value case ///////
              $sql = "SELECT issueId, regiDate, receiveDate, regiTime, model, model2, manu, netHead, swVer, memo, class1 FROM voc_tot_data WHERE ".$selection." LIKE '".$keyword."%' ORDER BY regiDate DESC, issueId ASC, model2 DESC LIMIT ".$currentLimit.",".$onePage;
              ///////////Excel download variables////////////
              $download_base = "SELECT * FROM voc_tot_data WHERE ".$selection." LIKE '".$keyword."%' ORDER BY regiDate DESC, issueId ASC, model2 DESC";
              $sql_count = "SELECT count(*) as cnt FROM voc_tot_data WHERE ".$selection." LIKE '".$keyword."%'";
          }

      	}
        //Multi Search Type
        else{
            # memo내용 검색이 keyword2에 있는 경우
            if($selection_2 == 'memo'){
              $sql = "SELECT issueId, regiDate, receiveDate, regiTime, model, model2, manu, netHead, swVer, memo, class1 FROM voc_tot_data WHERE (".$selection." LIKE '".$keyword."%') AND (".$selection_2." REGEXP '".$keyword_2."') ORDER BY regiDate DESC, issueId ASC, model2 DESC LIMIT ".$currentLimit.",".$onePage;
              ///////////Excel download variables////////////
              $download_base = "SELECT * FROM voc_tot_data WHERE (".$selection." LIKE '".$keyword."%') AND (".$selection_2." REGEXP '".$keyword_2."') ORDER BY regiDate DESC, issueId ASC, model2 DESC";
              $sql_count = "SELECT count(*) as cnt FROM voc_tot_data WHERE (".$selection." LIKE '".$keyword."%') AND (".$selection_2." REGEXP '".$keyword_2."')";
            }
            # memo내용 검색이 keyword2애 없는 경우
            else{
              $sql = "SELECT issueId, regiDate, receiveDate, regiTime, model, model2, manu, netHead, swVer, memo, class1 FROM voc_tot_data WHERE (".$selection." LIKE '".$keyword."%') AND (".$selection_2." LIKE '".$keyword_2."%') ORDER BY regiDate DESC, issueId ASC, model2 DESC LIMIT ".$currentLimit.",".$onePage;
              ///////////Excel download variables////////////
              $download_base = "SELECT * FROM voc_tot_data WHERE (".$selection." LIKE '".$keyword."%') AND (".$selection_2." LIKE '".$keyword_2."%') ORDER BY regiDate DESC, issueId ASC, model2 DESC";
              $sql_count = "SELECT count(*) as cnt FROM voc_tot_data WHERE (".$selection." LIKE '".$keyword."%') AND (".$selection_2." LIKE '".$keyword_2."%')";
            }
      	}

        $result = mysqli_query($conn, $sql_count);
        $row = mysqli_fetch_assoc($result);
        $total_count = $row['cnt'];
        // 1st table put in the row array\
        if($total_count == 0){
            mysqli_free_result($result);
            mysqli_close($conn);
            echo "<script>
                    alert('조회된 데이터가 없습니다.');
                    location.href = '/voc/data/index.php';
                  </script>";
        }
        //voc_tot_data data extract
        $result = mysqli_query($conn, $sql);

        //set Page button bottom
        setPage($conn, $result, $total_count);
    //////////////////////////////////////////////////////////complex search(date and keyword condition)//////////////////////////////////////////////////////
    }else{
        $search_type = "complex";
        ///////date_type 0  complex case///////
        if($date_type == 0){
          // 0 --> start date empty
          // 1 --> end date empty
          // 2 --> neither start date nor end date empty
          //Single Search Type
          if($selectType == "0"){
            # memo내용 검색인 경우
            if($selection == 'memo'){
              $sql = "SELECT issueId, regiDate, receiveDate, regiTime, model, model2, manu, netHead, swVer, memo, class1 FROM voc_tot_data WHERE (regiDate <= '".$endDate."') AND (".$selection." REGEXP '".$keyword."') ORDER BY regiDate DESC, issueId ASC, model2 DESC LIMIT ".$currentLimit.",".$onePage;
              $sql_count = "SELECT count(*) as cnt FROM voc_tot_data WHERE (regiDate <= '".$endDate."') AND (".$selection." REGEXP '".$keyword."')";
              ///////////Excel download variables////////////
              $download_base = "SELECT * FROM voc_tot_data WHERE (regiDate <= '".$endDate."') AND (".$selection." REGEXP '".$keyword."') ORDER BY regiDate DESC, issueId ASC, model2 DESC";
            }
            # memo내용 검색이 아닌 경우
            else{
              $sql = "SELECT issueId, regiDate, receiveDate, regiTime, model, model2, manu, netHead, swVer, memo, class1 FROM voc_tot_data WHERE (regiDate <= '".$endDate."') AND (".$selection." LIKE '".$keyword."%') ORDER BY regiDate DESC, issueId ASC, model2 DESC LIMIT ".$currentLimit.",".$onePage;
              $sql_count = "SELECT count(*) as cnt FROM voc_tot_data WHERE (regiDate <= '".$endDate."') AND (".$selection." LIKE '".$keyword."%')";
              ///////////Excel download variables////////////
              $download_base = "SELECT * FROM voc_tot_data WHERE (regiDate <= '".$endDate."') AND (".$selection." LIKE '".$keyword."%') ORDER BY regiDate DESC, issueId ASC, model2 DESC";
            }
          }
          //Multi Search Type
          else{
            # memo내용 검색이 keyword2에 있는 경우
            if($selection_2 == 'memo'){
              # memo내용 검색이 keyword2애 없는 경우
              $sql = "SELECT issueId, regiDate, receiveDate, regiTime, model, model2, manu, netHead, swVer, memo, class1 FROM voc_tot_data WHERE (regiDate <= '".$endDate."') AND (".$selection." LIKE '".$keyword."%') AND (".$selection_2." REGEXP '".$keyword_2."') ORDER BY regiDate DESC, issueId ASC, model2 DESC LIMIT ".$currentLimit.",".$onePage;
              $sql_count = "SELECT count(*) as cnt FROM voc_tot_data WHERE (regiDate <= '".$endDate."') AND (".$selection." LIKE '".$keyword."%') AND (".$selection_2." REGEXP '".$keyword_2."')";
              ///////////Excel download variables////////////
              $download_base = "SELECT * FROM voc_tot_data WHERE (regiDate <= '".$endDate."') AND (".$selection." LIKE '".$keyword."%') AND (".$selection_2." REGEXP '".$keyword_2."') ORDER BY regiDate DESC, issueId ASC, model2 DESC";
            }
            else{
              # memo내용 검색이 keyword2애 없는 경우
              $sql = "SELECT issueId, regiDate, receiveDate, regiTime, model, model2, manu, netHead, swVer, memo, class1 FROM voc_tot_data WHERE (regiDate <= '".$endDate."') AND (".$selection." LIKE '".$keyword."%') AND (".$selection_2." LIKE '".$keyword_2."%') ORDER BY regiDate DESC, issueId ASC, model2 DESC LIMIT ".$currentLimit.",".$onePage;
              $sql_count = "SELECT count(*) as cnt FROM voc_tot_data WHERE (regiDate <= '".$endDate."') AND (".$selection." LIKE '".$keyword."%') AND (".$selection_2." LIKE '".$keyword_2."%')";
              ///////////Excel download variables////////////
              $download_base = "SELECT * FROM voc_tot_data WHERE (regiDate <= '".$endDate."') AND (".$selection." LIKE '".$keyword."%') AND (".$selection_2." LIKE '".$keyword_2."%') ORDER BY regiDate DESC, issueId ASC, model2 DESC";
            }
          }
          $result = mysqli_query($conn, $sql_count);
          $row = mysqli_fetch_assoc($result);
          $total_count = $row['cnt'];
          // 1st table put in the row array\
          if($total_count == 0){
              mysqli_free_result($result);
              mysqli_close($conn);
              echo "<script>
                      alert('조회된 데이터가 없습니다.');
                      location.href = '/voc/data/index.php';
                    </script>";
          }
	        //voc_tot_data data extract
          $result = mysqli_query($conn, $sql);
          //set Page button bottom
          setPage($conn, $result, $total_count);
        }
        ///////date_type 1  complex case///////
        else if($date_type == 1){
          // 0 --> start date empty
          // 1 --> end date empty
          // 2 --> neither start date nor end date empty
          ///////single search type ///////
          if($selectType == "0"){
            # memo내용 검색인 경우
            if($selection == 'memo'){
              $sql = "SELECT issueId, regiDate, receiveDate, regiTime, model, model2, manu, netHead, swVer, memo, class1 FROM voc_tot_data WHERE (regiDate BETWEEN '".$startDate."' AND '".$today."') AND (".$selection." REGEXP '".$keyword."') ORDER BY regiDate DESC, issueId ASC, model2 DESC LIMIT ".$currentLimit.",".$onePage;
              $sql_count = "SELECT count(*) as cnt FROM voc_tot_data WHERE (regiDate BETWEEN '".$startDate."' AND '".$today."') AND (".$selection." REGEXP '".$keyword."')";
              ///////////Excel download variables////////////
              $download_base = "SELECT * FROM voc_tot_data WHERE (regiDate BETWEEN '".$startDate."' AND '".$today."') AND (".$selection." REGEXP '".$keyword."') ORDER BY regiDate DESC, issueId ASC, model2 DESC";
            }
            # memo내용 검색이 아닌 경우
            else{
              $sql = "SELECT issueId, regiDate, receiveDate, regiTime, model, model2, manu, netHead, swVer, memo, class1 FROM voc_tot_data WHERE (regiDate BETWEEN '".$startDate."' AND '".$today."') AND (".$selection." LIKE '".$keyword."%') ORDER BY regiDate DESC, issueId ASC, model2 DESC LIMIT ".$currentLimit.",".$onePage;
              $sql_count = "SELECT count(*) as cnt FROM voc_tot_data WHERE (regiDate BETWEEN '".$startDate."' AND '".$today."') AND (".$selection." LIKE '".$keyword."%')";
              ///////////Excel download variables////////////
              $download_base = "SELECT * FROM voc_tot_data WHERE (regiDate BETWEEN '".$startDate."' AND '".$today."') AND (".$selection." LIKE '".$keyword."%') ORDER BY regiDate DESC, issueId ASC, model2 DESC";
            }
          }
          ///////multi search type ///////
          else{
            # memo내용 검색이 keyword2에 있는 경우
            if($selection_2 == 'memo'){
              $sql = "SELECT issueId, regiDate, receiveDate, regiTime, model, model2, manu, netHead, swVer, memo, class1 FROM voc_tot_data WHERE (regiDate BETWEEN '".$startDate."' AND '".$today."') AND (".$selection." LIKE '".$keyword."%') AND (".$selection_2." REGEXP '".$keyword_2."') ORDER BY regiDate DESC, issueId ASC, model2 DESC LIMIT ".$currentLimit.",".$onePage;
              $sql_count = "SELECT count(*) as cnt FROM voc_tot_data WHERE (regiDate BETWEEN '".$startDate."' AND '".$today."') AND (".$selection." LIKE '".$keyword."%') AND (".$selection_2." REGEXP '".$keyword_2."')";
              ///////////Excel download variables////////////
              $download_base = "SELECT * FROM voc_tot_data WHERE (regiDate BETWEEN '".$startDate."' AND '".$today."') AND (".$selection." LIKE '".$keyword."%') AND (".$selection_2." REGEXP '".$keyword_2."') ORDER BY regiDate DESC, issueId ASC, model2 DESC";
            }
            # memo내용 검색이 keyword2애 없는 경우
            else{
              $sql = "SELECT issueId, regiDate, receiveDate, regiTime, model, model2, manu, netHead, swVer, memo, class1 FROM voc_tot_data WHERE (regiDate BETWEEN '".$startDate."' AND '".$today."') AND (".$selection." LIKE '".$keyword."%') AND (".$selection_2." LIKE '".$keyword_2."%') ORDER BY regiDate DESC, issueId ASC, model2 DESC LIMIT ".$currentLimit.",".$onePage;
              $sql_count = "SELECT count(*) as cnt FROM voc_tot_data WHERE (regiDate BETWEEN '".$startDate."' AND '".$today."') AND (".$selection." LIKE '".$keyword."%') AND (".$selection_2." LIKE '".$keyword_2."%')";
              ///////////Excel download variables////////////
              $download_base = "SELECT * FROM voc_tot_data WHERE (regiDate BETWEEN '".$startDate."' AND '".$today."') AND (".$selection." LIKE '".$keyword."%') AND (".$selection_2." LIKE '".$keyword_2."%') ORDER BY regiDate DESC, issueId ASC, model2 DESC";
            }
          }
          $result = mysqli_query($conn, $sql_count);
          $row = mysqli_fetch_assoc($result);
          $total_count = $row['cnt'];
          // 1st table put in the row array\
          if($total_count == 0){
              mysqli_free_result($result);
              mysqli_close($conn);
              echo "<script>
                      alert('조회된 데이터가 없습니다.');
                      location.href = '/voc/data/index.php';
                    </script>";
          }
	        //voc_tot_data data extract
          $result = mysqli_query($conn, $sql);
          //set Page button bottom
          setPage($conn, $result, $total_count);
        }
        ///////date_type 2  complex case///////
        else{
          // 0 --> start date empty
          // 1 --> end date empty
          // 2 --> neither start date nor end date empty
          //Single Search Type
          if($selectType == "0"){
            # memo내용 검색인 경우
            if($selection == 'memo'){
              $sql = "SELECT issueId, regiDate, receiveDate, regiTime, model, model2, manu, netHead, swVer, memo, class1 FROM voc_tot_data WHERE (regiDate BETWEEN '".$startDate."' AND '".$endDate."') AND (".$selection." REGEXP '".$keyword."') ORDER BY regiDate DESC, issueId ASC, model2 DESC LIMIT ".$currentLimit.",".$onePage;
              $sql_count = "SELECT count(*) as cnt FROM voc_tot_data WHERE (regiDate BETWEEN '".$startDate."' AND '".$endDate."') AND (".$selection." REGEXP '".$keyword."')";
              ///////////Excel download variables////////////
              $download_base = "SELECT * FROM voc_tot_data WHERE (regiDate BETWEEN '".$startDate."' AND '".$endDate."') AND (".$selection." REGEXP '".$keyword."') ORDER BY regiDate DESC, issueId ASC, model2 DESC";
            }
            # memo내용 검색이 아닌 경우
            else{
              $sql = "SELECT issueId, regiDate, receiveDate, regiTime, model, model2, manu, netHead, swVer, memo, class1 FROM voc_tot_data WHERE (regiDate BETWEEN '".$startDate."' AND '".$endDate."') AND (".$selection." LIKE '".$keyword."%') ORDER BY regiDate DESC, issueId ASC, model2 DESC LIMIT ".$currentLimit.",".$onePage;
              $sql_count = "SELECT count(*) as cnt FROM voc_tot_data WHERE (regiDate BETWEEN '".$startDate."' AND '".$endDate."') AND (".$selection." LIKE '".$keyword."%')";
              ///////////Excel download variables////////////
              $download_base = "SELECT * FROM voc_tot_data WHERE (regiDate BETWEEN '".$startDate."' AND '".$endDate."') AND (".$selection." LIKE '".$keyword."%') ORDER BY regiDate DESC, issueId ASC, model2 DESC";
            }
          }
          //Multi Search Type
          else{
            # memo내용 검색이 keyword2에 있는 경우
            if($selection_2 == 'memo'){
              $sql = "SELECT issueId, regiDate, receiveDate, regiTime, model, model2, manu, netHead, swVer, memo, class1 FROM voc_tot_data WHERE (regiDate BETWEEN '".$startDate."' AND '".$endDate."') AND (".$selection." LIKE '".$keyword."%') AND (".$selection_2." REGEXP '".$keyword_2."') ORDER BY regiDate DESC, issueId ASC, model2 DESC LIMIT ".$currentLimit.",".$onePage;
              $sql_count = "SELECT count(*) as cnt FROM voc_tot_data WHERE (regiDate BETWEEN '".$startDate."' AND '".$endDate."') AND (".$selection." LIKE '".$keyword."%') AND (".$selection_2." REGEXP '".$keyword_2."')";
              ///////////Excel download variables////////////
              $download_base = "SELECT * FROM voc_tot_data WHERE (regiDate BETWEEN '".$startDate."' AND '".$endDate."') AND (".$selection." LIKE '".$keyword."%') AND (".$selection_2." REGEXP '".$keyword_2."') ORDER BY regiDate DESC, issueId ASC, model2 DESC";
            }
            # memo내용 검색이 keyword2에 없는 경우
            else{
              $sql = "SELECT issueId, regiDate, receiveDate, regiTime, model, model2, manu, netHead, swVer, memo, class1 FROM voc_tot_data WHERE (regiDate BETWEEN '".$startDate."' AND '".$endDate."') AND (".$selection." LIKE '".$keyword."%') AND (".$selection_2." LIKE '".$keyword_2."%') ORDER BY regiDate DESC, issueId ASC, model2 DESC LIMIT ".$currentLimit.",".$onePage;
              $sql_count = "SELECT count(*) as cnt FROM voc_tot_data WHERE (regiDate BETWEEN '".$startDate."' AND '".$endDate."') AND (".$selection." LIKE '".$keyword."%') AND (".$selection_2." LIKE '".$keyword_2."%')";
              ///////////Excel download variables////////////
              $download_base = "SELECT * FROM voc_tot_data WHERE (regiDate BETWEEN '".$startDate."' AND '".$endDate."') AND (".$selection." LIKE '".$keyword."%') AND (".$selection_2." LIKE '".$keyword_2."%') ORDER BY regiDate DESC, issueId ASC, model2 DESC";
            }
          }
          $result = mysqli_query($conn, $sql_count);
          $row = mysqli_fetch_assoc($result);
          $total_count = $row['cnt'];
          // 1st table put in the row array\
          if($total_count == 0){
              mysqli_free_result($result);
              mysqli_close($conn);
              echo "<script>
                      alert('조회된 데이터가 없습니다.');
                      location.href = '/voc/data/index.php';
                    </script>";
          }
	        //voc_tot_data data extract
          $result = mysqli_query($conn, $sql);
          //set Page button bottom
          setPage($conn, $result, $total_count);
        }
      }

    //Page Setting function
    function setPage($conn, $result, $total){
      //총 페이지 수
      $allPage = ceil($total / $GLOBALS['onePage']);
      //Page Validation Check
      if($GLOBALS['page'] < 1 || ($allPage && $GLOBALS['page']) > $allPage){
        mysqli_free_result($result);
        mysqli_close($conn);
        echo "<script>
                alert('존재하지 않는 페이지 입니다.');
                location.href='/voc/index.php';
              </script>";
      }
          // $result = mysqli_query($GLOBALS['conn'], $exeSql);
          // $row = mysqli_fetch_assoc($result);

      //하단의 보여줄 총 Tab 수
      $oneSection = 10;
      //현재 위치
      $currentSection = ceil($GLOBALS['page'] / $oneSection);
      $allSection = ceil($allPage / $oneSection);
      $firstPage = ($currentSection * $oneSection) - ($oneSection - 1);
      if($currentSection == $allSection){
        $lastPage = $allPage; //현재 섹션이 마지막 섹션이라면 $allPage가 마지막 페이지
      }else{
        $lastPage = $currentSection * $oneSection; //현재 섹션의 마지막 페이지
      }
      $prevPage = (($currentSection - 1) * $oneSection); //이전 페이지
      $nextPage = (($currentSection + 1) * $oneSection) - ($oneSection - 1); //다음 페이지

      $GLOBALS['print'] = "<ul class='pagination justify-content-center'>";
      //Single search type
      if($GLOBALS['selectType'] == "0"){
        //첫 페이지가 아니라면 처음 버튼을 생성
        if($GLOBALS['page'] != 1){
          if($GLOBALS['flag'] == "Y" && $GLOBALS['dateFlag'] == "N"){
              $GLOBALS['print'] = $GLOBALS['print']."<li class='page-item'><a class='page-link' href='/voc/data/index.php?page=1&indexName=".$GLOBALS['selection']."&searchValue=".$GLOBALS['keyword']."&onePage=".$GLOBALS['onePage']."'>처음</a></li>";
          }else if($GLOBALS['flag'] == "N" && $GLOBALS['dateFlag'] == "Y"){
              $GLOBALS['print'] = $GLOBALS['print']."<li class='page-item'><a class='page-link' href='/voc/data/index.php?page=1&startDate=".$GLOBALS['startDate']."&endDate=".$GLOBALS['endDate']."&onePage=".$GLOBALS['onePage']."'>처음</a></li>";
          }else if($GLOBALS['flag'] == "Y" && $GLOBALS['dateFlag'] == "Y"){
              $GLOBALS['print'] = $GLOBALS['print']."<li class='page-item'><a class='page-link' href='/voc/data/index.php?page=1&indexName=".$GLOBALS['selection']."&searchValue=".$GLOBALS['keyword']."&startDate=".$GLOBALS['startDate']."&endDate=".$GLOBALS['endDate']."&onePage=".$GLOBALS['onePage']."'>처음</a></li>";
          }else{
              $GLOBALS['print'] = $GLOBALS['print']."<li class='page-item'><a class='page-link' href='/voc/data/index.php?page=1&onePage=".$GLOBALS['onePage']."'>처음</a></li>";
          }
        }
        //첫 섹션이 아니라면 이전 버튼을 생성
        if($currentSection != 1){
          if($GLOBALS['flag'] == "Y" && $GLOBALS['dateFlag'] == "N"){
              $GLOBALS['print'] = $GLOBALS['print']."<li class='page-item'><a class='page-link' href='/voc/data/index.php?page=".$prevPage."&indexName=".$GLOBALS['selection']."&searchValue=".$GLOBALS['keyword']."&onePage=".$GLOBALS['onePage']."'>이전</a></li>";
            }else if($GLOBALS['flag'] == "N" && $GLOBALS['dateFlag'] =="Y"){
              $GLOBALS['print'] = $GLOBALS['print']."<li class='page-item'><a class='page-link' href='/voc/data/index.php?page=".$prevPage."&startDate=".$GLOBALS['startDate']."&endDate=".$GLOBALS['endDate']."&onePage=".$GLOBALS['onePage']."'>이전</a></li>";
            }else if($GLOBALS['flag'] == "Y" && $GLOBALS['dateFlag'] == "Y"){
              $GLOBALS['print'] = $GLOBALS['print']."<li class='page-item'><a class='page-link' href='/voc/data/index.php?page=".$prevPage."&indexName=".$GLOBALS['selection']."&searchValue=".$GLOBALS['keyword']."&startDate=".$GLOBALS['startDate']."&endDate=".$GLOBALS['endDate']."&onePage=".$GLOBALS['onePage']."'>이전</a></li>";
            }else{
              $GLOBALS['print'] = $GLOBALS['print']."<li class='page-item'><a class='page-link' href='/voc/data/index.php?page=".$prevPage."&onePage=".$GLOBALS['onePage']."'>이전</a></li>";
            }
        }
        //Section gener
        for($i = $firstPage; $i<=$lastPage; $i++){
            if($i != $GLOBALS['page']){
              if($GLOBALS['flag'] =="Y" && $GLOBALS['dateFlag'] == "N"){
                $GLOBALS['print'] = $GLOBALS['print']."<li class='page-item'><a class='page-link' href='/voc/data/index.php?page=".$i."&indexName=".$GLOBALS['selection']."&searchValue=".$GLOBALS['keyword']."&onePage=".$GLOBALS['onePage']."'>".$i."</a></li>";
              }else if($GLOBALS['flag'] =="N" && $GLOBALS['dateFlag'] == "Y"){
                $GLOBALS['print'] = $GLOBALS['print']."<li class='page-item'><a class='page-link' href='/voc/data/index.php?page=".$i."&startDate=".$GLOBALS['startDate']."&endDate=".$GLOBALS['endDate']."&onePage=".$GLOBALS['onePage']."'>".$i."</a></li>";
              }else if($GLOBALS['flag'] =="Y" && $GLOBALS['dateFlag'] == "Y"){
                $GLOBALS['print'] = $GLOBALS['print']."<li class='page-item'><a class='page-link' href='/voc/data/index.php?page=".$i."&indexName=".$GLOBALS['selection']."&searchValue=".$GLOBALS['keyword']."&startDate=".$GLOBALS['startDate']."&endDate=".$GLOBALS['endDate']."&onePage=".$GLOBALS['onePage']."'>".$i."</a></li>";
              }else{
                $GLOBALS['print'] = $GLOBALS['print']."<li class='page-item'><a class='page-link' href='/voc/data/index.php?page=".$i."&onePage=".$GLOBALS['onePage']."'>".$i."</a></li>";
              }
            }
        }
        //마지막 섹션이 아니라면 다음 버튼을 생성
        if($currentSection != $allSection){
            if($GLOBALS['flag'] == "Y" && $GLOBALS['dateFlag'] == "N"){
              $GLOBALS['print'] =$GLOBALS['print']."<li class='page-item'><a class='page-link' href='/voc/data/index.php?page=".$nextPage."&indexName=".$GLOBALS['selection']."&searchValue=".$GLOBALS['keyword']."&onePage=".$GLOBALS['onePage']."'>다음</a></li>";
            }else if($GLOBALS['flag'] == "N" && $GLOBALS['dateFlag'] == "Y"){
              $GLOBALS['print'] =$GLOBALS['print']."<li class='page-item'><a class='page-link' href='/voc/data/index.php?page=".$nextPage."&startDate=".$GLOBALS['startDate']."&endDate=".$GLOBALS['endDate']."&onePage=".$GLOBALS['onePage']."'>다음</a></li>";
            }else if($GLOBALS['flag'] == "Y" && $GLOBALS['dateFlag'] == "Y"){
              $GLOBALS['print'] =$GLOBALS['print']."<li class='page-item'><a class='page-link' href='/voc/data/index.php?page=".$nextPage."&indexName=".$GLOBALS['selection']."&searchValue=".$GLOBALS['keyword']."&startDate=".$GLOBALS['startDate']."&endDate=".$GLOBALS['endDate']."&onePage=".$GLOBALS['onePage']."'>다음</a></li>";
            }else{
              $GLOBALS['print'] =$GLOBALS['print']."<li class='page-item'><a class='page-link' href='/voc/data/index.php?page=".$nextPage."&onePage=".$GLOBALS['onePage']."'>다음</a></li>";
            }
        }
        //마지막 페이지가 아니라면 끝 버튼을 생성
        if($GLOBALS['page'] != $allPage){
            if($GLOBALS['flag'] == "Y" && $GLOBALS['dateFlag'] == "N"){
              $GLOBALS['print'] =$GLOBALS['print']."<li class='page-item'><a class='page-link' href='/voc/data/index.php?page=".$allPage."&indexName=".$GLOBALS['selection']."&searchValue=".$GLOBALS['keyword']."&onePage=".$GLOBALS['onePage']."'>끝</a></li>";
            }else if($GLOBALS['flag'] == "N" && $GLOBALS['dateFlag'] == "Y"){
              $GLOBALS['print'] =$GLOBALS['print']."<li class='page-item'><a class='page-link' href='/voc/data/index.php?page=".$allPage."&startDate=".$GLOBALS['startDate']."&endDate=".$GLOBALS['endDate']."&onePage=".$GLOBALS['onePage']."'>끝</a></li>";
            }else if($GLOBALS['flag'] == "Y" && $GLOBALS['dateFlag'] == "Y"){
              $GLOBALS['print'] =$GLOBALS['print']."<li class='page-item'><a class='page-link' href='/voc/data/index.php?page=".$allPage."&indexName=".$GLOBALS['selection']."&searchValue=".$GLOBALS['keyword']."&startDate=".$GLOBALS['startDate']."&endDate=".$GLOBALS['endDate']."&onePage=".$GLOBALS['onePage']."'>끝</a></li>";
            }else{
              $GLOBALS['print'] =$GLOBALS['print']."<li class='page-item'><a class='page-link' href='/voc/data/index.php?page=".$allPage."&onePage=".$GLOBALS['onePage']."'>끝</a></li>";
            }
        }
      }
      //Multi search type
      else{
        //첫 페이지가 아니라면 처음 버튼을 생성
        if($GLOBALS['page'] != 1){
          if($GLOBALS['flag'] == "Y" && $GLOBALS['dateFlag'] == "N"){
              $GLOBALS['print'] = $GLOBALS['print']."<li class='page-item'><a class='page-link' href='/voc/data/index.php?page=1&selectType=1&indexName=".$GLOBALS['oriSelection']."&searchValue=".$GLOBALS['keyword']."&searchValue_2=".$GLOBALS['keyword_2']."&onePage=".$GLOBALS['onePage']."'>처음</a></li>";
          }else if($GLOBALS['flag'] == "N" && $GLOBALS['dateFlag'] == "Y"){
              $GLOBALS['print'] = $GLOBALS['print']."<li class='page-item'><a class='page-link' href='/voc/data/index.php?page=1&startDate=".$GLOBALS['startDate']."&endDate=".$GLOBALS['endDate']."&onePage=".$GLOBALS['onePage']."'>처음</a></li>";
          }else if($GLOBALS['flag'] == "Y" && $GLOBALS['dateFlag'] == "Y"){
              $GLOBALS['print'] = $GLOBALS['print']."<li class='page-item'><a class='page-link' href='/voc/data/index.php?page=1&selectType=1&indexName=".$GLOBALS['oriSelection']."&searchValue=".$GLOBALS['keyword']."&searchValue_2=".$GLOBALS['keyword_2']."&startDate=".$GLOBALS['startDate']."&endDate=".$GLOBALS['endDate']."&onePage=".$GLOBALS['onePage']."'>처음</a></li>";
          }else{
              $GLOBALS['print'] = $GLOBALS['print']."<li class='page-item'><a class='page-link' href='/voc/data/index.php?page=1&onePage=".$GLOBALS['onePage']."'>처음</a></li>";
          }
        }
        //첫 섹션이 아니라면 이전 버튼을 생성
        if($currentSection != 1){
          if($GLOBALS['flag'] == "Y" && $GLOBALS['dateFlag'] == "N"){
              $GLOBALS['print'] = $GLOBALS['print']."<li class='page-item'><a class='page-link' href='/voc/data/index.php?page=".$prevPage."&selectType=1&indexName=".$GLOBALS['oriSelection']."&searchValue=".$GLOBALS['keyword']."&searchValue_2=".$GLOBALS['keyword_2']."&onePage=".$GLOBALS['onePage']."'>이전</a></li>";
            }else if($GLOBALS['flag'] == "N" && $GLOBALS['dateFlag'] =="Y"){
              $GLOBALS['print'] = $GLOBALS['print']."<li class='page-item'><a class='page-link' href='/voc/data/index.php?page=".$prevPage."&startDate=".$GLOBALS['startDate']."&endDate=".$GLOBALS['endDate']."&onePage=".$GLOBALS['onePage']."'>이전</a></li>";
            }else if($GLOBALS['flag'] == "Y" && $GLOBALS['dateFlag'] == "Y"){
              $GLOBALS['print'] = $GLOBALS['print']."<li class='page-item'><a class='page-link' href='/voc/data/index.php?page=".$prevPage."&selectType=1&indexName=".$GLOBALS['oriSelection']."&searchValue=".$GLOBALS['keyword']."&searchValue_2=".$GLOBALS['keyword_2']."&startDate=".$GLOBALS['startDate']."&endDate=".$GLOBALS['endDate']."&onePage=".$GLOBALS['onePage']."'>이전</a></li>";
            }else{
              $GLOBALS['print'] = $GLOBALS['print']."<li class='page-item'><a class='page-link' href='/voc/data/index.php?page=".$prevPage."&onePage=".$GLOBALS['onePage']."'>이전</a></li>";
            }
        }
        //Section gener
        for($i = $firstPage; $i<=$lastPage; $i++){
            if($i != $GLOBALS['page']){
              if($GLOBALS['flag'] =="Y" && $GLOBALS['dateFlag'] == "N"){
                $GLOBALS['print'] = $GLOBALS['print']."<li class='page-item'><a class='page-link' href='/voc/data/index.php?page=".$i."&selectType=1&indexName=".$GLOBALS['oriSelection']."&searchValue=".$GLOBALS['keyword']."&searchValue_2=".$GLOBALS['keyword_2']."&onePage=".$GLOBALS['onePage']."'>".$i."</a></li>";
              }else if($GLOBALS['flag'] =="N" && $GLOBALS['dateFlag'] == "Y"){
                $GLOBALS['print'] = $GLOBALS['print']."<li class='page-item'><a class='page-link' href='/voc/data/index.php?page=".$i."&startDate=".$GLOBALS['startDate']."&endDate=".$GLOBALS['endDate']."&onePage=".$GLOBALS['onePage']."'>".$i."</a></li>";
              }else if($GLOBALS['flag'] =="Y" && $GLOBALS['dateFlag'] == "Y"){
                $GLOBALS['print'] = $GLOBALS['print']."<li class='page-item'><a class='page-link' href='/voc/data/index.php?page=".$i."&selectType=1&indexName=".$GLOBALS['oriSelection']."&searchValue=".$GLOBALS['keyword']."&searchValue_2=".$GLOBALS['keyword_2']."&startDate=".$GLOBALS['startDate']."&endDate=".$GLOBALS['endDate']."&onePage=".$GLOBALS['onePage']."'>".$i."</a></li>";
              }else{
                $GLOBALS['print'] = $GLOBALS['print']."<li class='page-item'><a class='page-link' href='/voc/data/index.php?page=".$i."&onePage=".$GLOBALS['onePage']."'>".$i."</a></li>";
              }
            }
        }
        //마지막 섹션이 아니라면 다음 버튼을 생성
        if($currentSection != $allSection){
            if($GLOBALS['flag'] == "Y" && $GLOBALS['dateFlag'] == "N"){
              $GLOBALS['print'] =$GLOBALS['print']."<li class='page-item'><a class='page-link' href='/voc/data/index.php?page=".$nextPage."&selectType=1&indexName=".$GLOBALS['oriSelection']."&searchValue=".$GLOBALS['keyword']."&searchValue_2=".$GLOBALS['keyword_2']."&onePage=".$GLOBALS['onePage']."'>다음</a></li>";
            }else if($GLOBALS['flag'] == "N" && $GLOBALS['dateFlag'] == "Y"){
              $GLOBALS['print'] =$GLOBALS['print']."<li class='page-item'><a class='page-link' href='/voc/data/index.php?page=".$nextPage."&startDate=".$GLOBALS['startDate']."&endDate=".$GLOBALS['endDate']."&onePage=".$GLOBALS['onePage']."'>다음</a></li>";
            }else if($GLOBALS['flag'] == "Y" && $GLOBALS['dateFlag'] == "Y"){
              $GLOBALS['print'] =$GLOBALS['print']."<li class='page-item'><a class='page-link' href='/voc/data/index.php?page=".$nextPage."&selectType=1&indexName=".$GLOBALS['oriSelection']."&searchValue=".$GLOBALS['keyword']."&searchValue_2=".$GLOBALS['keyword_2']."&startDate=".$GLOBALS['startDate']."&endDate=".$GLOBALS['endDate']."&onePage=".$GLOBALS['onePage']."'>다음</a></li>";
            }else{
              $GLOBALS['print'] =$GLOBALS['print']."<li class='page-item'><a class='page-link' href='/voc/data/index.php?page=".$nextPage."&onePage=".$GLOBALS['onePage']."'>다음</a></li>";
            }
        }
        //마지막 페이지가 아니라면 끝 버튼을 생성
        if($GLOBALS['page'] != $allPage){
            if($GLOBALS['flag'] == "Y" && $GLOBALS['dateFlag'] == "N"){
              $GLOBALS['print'] =$GLOBALS['print']."<li class='page-item'><a class='page-link' href='/voc/data/index.php?page=".$allPage."&selectType=1&indexName=".$GLOBALS['oriSelection']."&searchValue=".$GLOBALS['keyword']."&searchValue_2=".$GLOBALS['keyword_2']."&onePage=".$GLOBALS['onePage']."'>끝</a></li>";
            }else if($GLOBALS['flag'] == "N" && $GLOBALS['dateFlag'] == "Y"){
              $GLOBALS['print'] =$GLOBALS['print']."<li class='page-item'><a class='page-link' href='/voc/data/index.php?page=".$allPage."&startDate=".$GLOBALS['startDate']."&endDate=".$GLOBALS['endDate']."&onePage=".$GLOBALS['onePage']."'>끝</a></li>";
            }else if($GLOBALS['flag'] == "Y" && $GLOBALS['dateFlag'] == "Y"){
              $GLOBALS['print'] =$GLOBALS['print']."<li class='page-item'><a class='page-link' href='/voc/data/index.php?page=".$allPage."&selectType=1&indexName=".$GLOBALS['oriSelection']."&searchValue=".$GLOBALS['keyword']."&searchValue_2=".$GLOBALS['keyword_2']."&startDate=".$GLOBALS['startDate']."&endDate=".$GLOBALS['endDate']."&onePage=".$GLOBALS['onePage']."'>끝</a></li>";
            }else{
              $GLOBALS['print'] =$GLOBALS['print']."<li class='page-item'><a class='page-link' href='/voc/data/index.php?page=".$allPage."&onePage=".$GLOBALS['onePage']."'>끝</a></li>";
            }
        }
      }
      $GLOBALS['print'] = $GLOBALS['print']."</ul>";

    }
    mysqli_close($conn);
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/voc/assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="/voc/assets/vendor/fonts/circular-std/style.css">
    <link rel="stylesheet" href="/voc/assets/libs/css/style.css">
    <!-- <link href="http://fonts.googleapis.com/earlyaccess/jejugothic.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="/voc/assets/vendor/fonts/fontawesome/css/fontawesome-all.css">
    <link rel="stylesheet" href="/voc/assets/vendor/fonts/material-design-iconic-font/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="/voc/assets/vendor/fonts/flag-icon-css/flag-icon.min.css">
    <title>VOC 모니터링</title>
</head>

<body>
    <!-- ============================================================== -->
    <!-- main wrapper -->
    <!-- ============================================================== -->
    <div class="dashboard-main-wrapper">
        <!-- ============================================================== -->
        <!-- navbar -->
        <!-- ============================================================== -->
        <div class="dashboard-header">
            <nav class="navbar navbar-expand-lg bg-white fixed-top">
                <a class="navbar-brand" href="/voc/index.php">VOMS <samll id="navbar-brand-small">  Voc Monitoring System</small></a>
                <div class="collapse navbar-collapse " id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto navbar-right-top">
                      <!-- ============================================================== -->
                      <!-- ============================GBN Menu========================== -->
                      <li class="nav-item dropdown connection">
                          <a class="nav-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="fas fa-fw fa-th"></i> </a>
                          <ul class="dropdown-menu dropdown-menu-right connection-dropdown">
                              <li class="connection-list">
                                  <div class="row">
                                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 ">
                                          <a href="/voc/index.php" class="connection-item"><img src="/voc/assets/images/home256.png" alt="move index page" ><span class>메인</span></a>
                                      </div>
                                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 ">
                                          <a href="/voc/data/index.php" class="connection-item"><img src="/voc/assets/images/data256.png" alt="move data page" ><span>데이터</span></a>
                                      </div>
                                      <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 ">
                                          <a href="/voc/chart/modelChart.php" class="connection-item"><img src="/voc/assets/images/chart256.png" alt="move chart page" ><span>차트</span></a>
                                      </div>
                                      <?php
                                        if ($_SESSION['adminAuth']== 1){
                                          echo
                                          '<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12 ">
                                              <a href="/voc/admin/devices.php" class="connection-item"><img src="/voc/assets/images/admin256.png" alt="move Admin page" > <span>관리</span></a>
                                          </div>';
                                        }
                                      ?>
                                  </div>
                              </li>
                          </ul>
                      </li>
                        <!-- ================================================================== -->
                        <!-- ============================End GBN Menu========================== -->
                        <!-- ================================================================== -->
                        <!-- =======================Account Profile Menu======================= -->
                        <li class="nav-item dropdown nav-user">
                            <a class="nav-link nav-user-img" href="#" id="navbarDropdownMenuLink2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="/voc/assets/images/logout.png" alt="" class="user-avatar-md rounded-circle"></a>
                            <div class="dropdown-menu dropdown-menu-right nav-user-dropdown" aria-labelledby="navbarDropdownMenuLink2">
                                <div class="nav-user-info">
                                    <h5 class="mb-0 text-white nav-user-name"><?php echo $_SESSION['name'];?> </h5>
                                    <span class="status"></span><span class="ml-2"><?php if($_SESSION['adminAuth'] == 1){echo "Available  (관리자)";}else{echo "Available  (일반)";}?></span>
                                </div>
                                <a class="dropdown-item" href="/voc/admin/account.php"><i class="fas fa-user mr-2"></i>Account</a>
                                <a class="dropdown-item" href="#"><i class="fas fa-cog mr-2"></i>Setting</a>
                                <a class="dropdown-item" href="/voc/phpdata/logout.php"><i class="fas fa-power-off mr-2"></i>Logout</a>
                            </div>
                        </li>
                        <!-- ================================================================== -->
                        <!-- =======================End Account Profile Menu=================== -->
                    </ul>
                </div>
            </nav>
        </div>
        <!-- ============================================================== -->
        <!-- end navbar -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- left sidebar -->
        <!-- ============================================================== -->
        <div class="nav-left-sidebar sidebar-dark">
          <div class="menu-list">
              <nav class="navbar navbar-expand-lg navbar-light">
                  <a class="d-xl-none d-lg-none" href="/voc/index.php">메인화면</a>
                  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                      <span class="navbar-toggler-icon"></span>
                  </button>
                  <div class="collapse navbar-collapse" id="navbarNav">
                      <ul class="navbar-nav flex-column">
                          <li class="nav-divider">
                              Menu
                          </li>
                          <li class="nav-item ">
                              <a class="nav-link active" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-1" aria-controls="submenu-1"><i class="fa fa-fw fa-user-circle"></i>메인화면<span class="badge badge-success">6</span></a>
                              <div id="submenu-1" class="collapse submenu" style="">
                                  <ul class="nav flex-column">
                                      <li class="nav-item">
                                          <a class="nav-link" href="/voc/index.php">메인화면</a>
                                      </li>
                                  </ul>
                              </div>
                          </li>
                          <li class="nav-item">
                              <a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-2" aria-controls="submenu-2"><i class="fa fa-fw fa-rocket"></i>VOC데이터</a>
                              <div id="submenu-2" class="collapse submenu" style="">
                                  <ul class="nav flex-column">
                                      <li class="nav-item">
                                          <a class="nav-link" href="/voc/data/index.php">VOC 통품전체</a>
                                      </li>
                                      <li class="nav-item">
                                          <a class="nav-link" href="/voc/data/sort.php">VOC 불만성유형</a>
                                      </li>
                                      <li class="nav-item">
                                          <a class="nav-link" href="/voc/data/subscribe.php">가입자 정보</a>
                                      </li>
                                  </ul>
                              </div>
                          </li>
                          <li class="nav-item">
                              <a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-3" aria-controls="submenu-3"><i class="fas fa-fw fa-chart-pie"></i>VOC차트</a>
                              <div id="submenu-3" class="collapse submenu" style="">
                                  <ul class="nav flex-column">
                                    <li class="nav-item">
                                        <a class="nav-link" href="/voc/chart/modelChart.php">모델차트</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="/voc/chart/manuChart.php">제조사차트</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="/voc/chart/keywordChart.php">키워드차트</a>
                                    </li>
                                  </ul>
                              </div>
                          </li>
                          <?php
                            if($_SESSION['adminAuth'] == 1){
                                  echo '
                                  <li class="nav-item ">
                                      <a class="nav-link" href="#" data-toggle="collapse" aria-expanded="false" data-target="#submenu-4" aria-controls="submenu-4"><i class="fab fa-fw fa-wpforms"></i>관리페이지</a>
                                      <div id="submenu-4" class="collapse submenu" style="">
                                          <ul class="nav flex-column">
                                              <li class="nav-item">
                                                  <a class="nav-link" href="/voc/admin/devices.php">단말리스트관리</a>
                                              </li>
                                              <li class="nav-item">
                                                  <a class="nav-link" href="/voc/admin/deviceAd.php">단말추가</a>
                                              </li>
                                              <li class="nav-item">
                                                  <a class="nav-link" href="/voc/admin/classes.php">분류리스트관리</a>
                                              </li>
                                              <li class="nav-item">
                                                  <a class="nav-link" href="/voc/admin/classAd.php">분류추가</a>
                                              </li>
                                              <li class="nav-item">
                                                  <a class="nav-link" href="/voc/admin/accounts.php">사용자관리</a>
                                              </li>
                                              <li class="nav-item">
                                                  <a class="nav-link" href="/voc/admin/settings.php">상태설정</a>
                                              </li>
                                          </ul>
                                      </div>
                                  </li>
                                  ';
                              }
                          ?>
                      </ul>
                  </div>
              </nav>
          </div>
        </div>
        <!-- ============================================================== -->
        <!-- end left sidebar -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- wrapper  -->
        <!-- ============================================================== -->
        <div class="dashboard-wrapper">
            <div class="dashboard-ecommerce">
                <div class="container-fluid dashboard-content ">
                    <!-- ============================================================== -->
                    <!-- pageheader  -->
                    <!-- ============================================================== -->
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="page-header">
                                <h2 class="pageheader-title">VOC 통품전체</h2>
                                <!-- <p class="pageheader-text">Nulla euismod urna eros, sit amet scelerisque torton lectus vel mauris facilisis faucibus at enim quis massa lobortis rutrum.</p> -->
                                <div class="page-breadcrumb">
                                    <nav aria-label="breadcrumb">
                                        <ol class="breadcrumb">
                                            <li class="breadcrumb-item"><a href="/voc/index.php" class="breadcrumb-link">메인화면</a></li>
                                            <li class="breadcrumb-item active" aria-current="page">VOC 통품전체</li>
                                        </ol>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ============================================================== -->
                    <!-- end pageheader  -->
                    <!-- ============================================================== -->
                    <div class="ecommerce-widget">
                        <!-- ============================ first Select Button ============================ -->
                        <div class="row">
                          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="card">
                              <div id='searchForm' class="card-body" style="padding-right:10px;">
                                <!-- onsubmit="return checkValue();" -->
                                <form id="searchForm" action="/voc/data/index.php?page=1" method="GET" onsubmit="return checkValue();">
                                  <input id="selectType" type="text" name="selectType" hidden>
                                  <ul id="searchList">
                                    <li>
                                      <label for="count_list" style="font-size:12px;">페이지</label>
                                    </li>
                                    <li>
                                      <select id="count_list" name="onePage" style="height:35px; width:50px">
                                        <option value='10'>10</option>
                                        <option value='20'>20</option>
                                        <option value='30'>30</option>
                                        <option value='50'>50</option>
                                        <option value='100'>100</option>
                                      </select>
                                    </li>
                                    <li>
                                      <label for="startDate" id="date" style="font-size:12px;">기간</label>
                                    </li>
                                    <li>
                                      <input id="startDate" type="date" name="startDate" style="height:35px;width:120px;">
                                    </li>
                                    <li>
                                      <input id="endDate" type="date" name="endDate" style="height:35px;width:120px;">
                                    </li>
                                    <li>
                                      <label for="searchSelect" style="font-size:12px;">항목</label>
                                    </li>
                                    <li>
                                      <select id="searchSelect" name="indexName" onchange="checkSelect();" style="height:35px; display:block;">
                                        <option value='issueId'>이슈ID</option>
                                        <option value='receiveDate'>등록일</option>
                                        <option value='model'>모델명</option>
                                        <option value='model2'>모델명2</option>
                                        <option value='manu'>제조사</option>
                                        <option value='netHead'>본부</option>
                                        <option value="swVer">SW 버전</option>
                                        <option value='class1'>메모분류</option>
                                        <option value='memo'>메모내용</option>
                                        <option value='model2,memo'>모델명2 + 메모내용</option>
                                        <option value='manu,memo'>제조사 + 메모내용</option>
                                        <option value='model2,class1'>모델명2 + 메모분류</option>
                                        <option value='manu,class1'>제조사 + 메모분류</option>
                                        <option value='model2,swVer'>모델명2 + SW 버전</option>
                                        <option value='model,memo'>모델명 + 메모내용</option>
                                        <option value='manu,memo'>제조사 + 메모내용</option>
                                        <option value='model,class1'>모델명 + 메모분류</option>
                                        <option value='manu,class1'>제조사 + 메모분류</option>
                                        <option value='model,swVer'>모델명 + SW 버전</option>
                                        <option value='netHead,memo'>본부 + 메모내용</option>
                                      </select>
                                    </li>
                                    <!-- <li>
                                      <label for="searchText" style="font-size:12px;">검색어</label>
                                    </li> -->
                                    <li>
                                      <input id="searchText" type="text" name="searchValue" placeholder="" style="height:35px;">
                                    </li>
                                    <li class='searchOp' hidden>
                                      <input id="searchText_2" class='searchOp' type="text" name="searchValue_2" hidden placeholder="" style="height:35px;">
                                    </li>
                                    <li>
                                      <input class="btn btn-info" id="searchButton" type="submit" value="검색" style="margin-left:20px; width:100px;" onclick="">
                                    </li>
                                  </ul>
                                </form>
                              </div>
                            </div>
                            <div class="card">
                              <div class="card-body">
                                <ul id="searchList">
                                  <li>
                                    <form id="export_excel" action="/voc/data/down/csvDownload.php" method="POST" onsubmit="return fileDownload();">
                                        <input type="text" name="re" value="<?php echo $_SERVER['REQUEST_URI'];?>" hidden style="display:none">
                                        <input type="text" name="base" value="<?php echo $download_base;?>" hidden style="display:none">
                                        <input type="submit" class="btn btn-primary" style="width:200px;" value="csv 다운로드">
                                    </form>
                                  </li>
                                </ul>
                              </div>
                            </div>
                          </div>
                        </div>
                        <!-- ============================ second Select Button =========================== -->
                        <div class="row">
                          <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="card table-responsive" style="padding:7px;">
                                <?php
                                      echo "<h4 class='text-muted' style='margin:12px;'>VOC LIST <span style='color:orange;'>".$total_count."</span> / ".$hole."&nbsp;&nbsp;&nbsp;&nbsp;<span style='color:blue;'>".$page." Page</span></h4>";
                                      echo "<script>
                                              document.getElementById('count_list').value ='".$onePage."';
                                              document.getElementById('selectType').value = '".$selectType."';
                                            </script>";
                                      if($flag == "Y" && $dateFlag == "N"){
                                        if($selectType == "0"){
                                          echo "<script>
                                                  document.getElementById('searchSelect').value ='".$selection."';
                                                  document.getElementById('searchText').value='".$keyword."';
                                                </script>";
                                        }
                                        else{
                                          echo "<script>
                                                  document.getElementById('searchSelect').value ='".$oriSelection."';
                                                  document.getElementById('searchText').value='".$keyword."';
                                                  document.getElementById('searchText_2').value='".$keyword_2."';
                                                </script>";
                                        }
                                      }else if($flag == "N" && $dateFlag == "Y"){
                                        echo "<script>
                                                document.getElementById('startDate').value ='".$startDate."';
                                                document.getElementById('endDate').value='".$endDate."';
                                              </script>";
                                      }else if($flag == "Y" && $dateFlag == "Y"){
                                        if($selectType == "0"){
                                          echo "<script>
                                                  document.getElementById('startDate').value ='".$startDate."';
                                                  document.getElementById('endDate').value='".$endDate."';
                                                  document.getElementById('searchSelect').value ='".$selection."';
                                                  document.getElementById('searchText').value='".$keyword."';
                                                </script>";
                                        }
                                        else{
                                          echo "<script>
                                                  document.getElementById('startDate').value ='".$startDate."';
                                                  document.getElementById('endDate').value='".$endDate."';
                                                  document.getElementById('searchSelect').value ='".$oriSelection."';
                                                  document.getElementById('searchText').value='".$keyword."';
                                                  document.getElementById('searchText_2').value='".$keyword_2."';
                                                </script>";
                                        }
                                      }
                                      $tableBody =
                                          "<table class='table table-hover' style='font-size:12px;'>
                                              <caption style='padding-left:10px;'><a href='/voc/data/index.php' target='_self' style='font-size:15px;'><strong>Move All</strong></a></caption>
                                                  <thread>
                                                      <tr style='background-color:#0000000f;'>
                                                          <th scope='col' class='text-center' style='width: 8%'>이슈ID</th>
                                                          <th scope='col' class='text-center' style='width: 9%'>접수일</th>
                                                          <th scope='col' class='text-center' style='width: 9%'>모델명</th>
                                                          <th scope='col' class='text-center' style='width: 9%'>모델명2</th>
                                                          <th scope='col' class='text-center' style='width: 11%'>제조사</th>
                                                          <th scope='col' class='text-center' style='width: 9%'>등록일</th>
                                                          <th scope='col' class='text-center' style='width: 6%'>본부</th>
                                                          <th scope='col' class='text-center' style='width: 9%'>SW 버전</th>
                                                          <th scope='col' class='text-center' style='width: 22%'>내용</th>
                                                          <th scope='col' class='text-center' style='width: 8%'>분류</th>
                                                      </tr>
                                                  </thread>";
                                       $tableBody = $tableBody."<tbody>";
                                       while($row = mysqli_fetch_assoc($result)){
                                              $tableBody = $tableBody.
                                                      "<tr>
                                                          <p class='datas' hidden>".$row['memo']."</p>
                                                          <td class='text-center'><a href='/voc/data/viewTotal.php?id=".$row['issueId']."' target='_blank' style='color:'>".$row['issueId']."</a></td>
                                                          <td class='text-center'>".$row['regiDate']."</td>
                                                          <td class='text-center'>".mb_strimwidth($row['model'],'0','20','..','utf-8')."</td>
                                                          <td class='text-center'>".$row['model2']."</td>
                                                          <td class='text-center'>".$row['manu']."</td>
                                                          <td class='text-center'>".$row['receiveDate']."</td>
                                                          <td class='text-center'>".str_replace("Infra본부","",$row['netHead'])."</td>
                                                          <td class='text-center'>".$row['swVer']."</td>
                                                          <td class='text-left'>".mb_strimwidth($row['memo'],'0','50','..','utf-8')."</td>
                                                          <td class='text-center'>".$row['class1']."</td>
                                                      </tr>";
                                       }
                                       mysqli_free_result($result);
                                       $tableBody = $tableBody."</tbody>";
                                       $tableBody = $tableBody."</table>";
                                       $tableBody = $tableBody."<hr/>";
                                       $tableBody = $tableBody."<nav class='text-center'>".$print."</div>";

                                       echo $tableBody;
                                ?>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
            <!-- ============================================================== -->
            <!-- footer -->
            <!-- ============================================================== -->
            <div class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                              Copyright © 2019 TestEnC. All rights reserved.
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <div class="text-md-right footer-links d-none d-sm-block">
                                <a href="http://gw.bwlee325.cafe24.com/groupware/index.php" target="_blank">Visit Groupware</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- end footer -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- end wrapper  -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- end main wrapper  -->
    <!-- ============================================================== -->
    <!-- Optional JavaScript -->
    <!-- jquery 3.3.1 -->
    <script src="/voc/assets/vendor/jquery/jquery-3.3.1.min.js"></script>
    <!-- bootstap bundle js -->
    <script src="/voc/assets/vendor/bootstrap/js/bootstrap.bundle.js"></script>
    <!-- slimscroll js -->
    <script src="/voc/assets/vendor/slimscroll/jquery.slimscroll.js"></script>
    <!-- main js -->
    <script src="/voc/assets/libs/js/main-js.js"></script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" src="/voc/assets/libs/js/table.js"></script>
    <script type="text/javascript">
    window.onload = function() {
      setMemoBalloon();
      checkSelect();
    }
    </script>
</body>
</html>
