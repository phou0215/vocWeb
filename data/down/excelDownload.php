<?php
    ini_set('memory_limit','-1');
    require_once($_SERVER['DOCUMENT_ROOT']."/config/config.php");
    require_once($_SERVER['DOCUMENT_ROOT']."/phpoffice/vendor/autoload.php");

    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    $objExcel = new Spreadsheet();

    // require_once($_SERVER['DOCUMENT_ROOT']."/voc/data/index.php");
    $conn = mysqli_connect($config['host'],$config['user'],$config['password']);
    mysqli_select_db($conn, $config['database']);

    $redirect = $_POST['re'];
    $base = $_POST['base'];

    // $objExcel->setActiveSheetIndex(0);
    $sheet = $objExcel->getActiveSheet();
    // $sheet = $objExcel->getActiveSheet();
    $sheet->setTitle("VOC_DATA(통품전체)");

    try{
        //set Cell width
        $sheet->getColumnDimension('A')->setWidth(11.88); $sheet->getColumnDimension('B')->setWidth(16.88);
        $sheet->getColumnDimension('C')->setWidth(10); $sheet->getColumnDimension('D')->setWidth(10);
        $sheet->getColumnDimension('E')->setWidth(10); $sheet->getColumnDimension('F')->setWidth(10);
        $sheet->getColumnDimension('G')->setWidth(10); $sheet->getColumnDimension('H')->setWidth(10);
        $sheet->getColumnDimension('I')->setWidth(10); $sheet->getColumnDimension('J')->setWidth(12.63);
        $sheet->getColumnDimension('K')->setWidth(10.88); $sheet->getColumnDimension('L')->setWidth(15.88);
        $sheet->getColumnDimension('M')->setWidth(15.88); $sheet->getColumnDimension('N')->setWidth(11.75);
        $sheet->getColumnDimension('O')->setWidth(18.25); $sheet->getColumnDimension('P')->setWidth(18.25);
        $sheet->getColumnDimension('Q')->setWidth(10); $sheet->getColumnDimension('R')->setWidth(11.75);
        $sheet->getColumnDimension('S')->setWidth(16.13); $sheet->getColumnDimension('T')->setWidth(14.5);
        $sheet->getColumnDimension('U')->setWidth(19.5); $sheet->getColumnDimension('V')->setWidth(10);
        $sheet->getColumnDimension('W')->setWidth(10); $sheet->getColumnDimension('X')->setWidth(10);
        $sheet->getColumnDimension('Y')->setWidth(10); $sheet->getColumnDimension('Z')->setWidth(10);

        $sheet->getColumnDimension('AA')->setWidth(10); $sheet->getColumnDimension('AB')->setWidth(15.88);
        $sheet->getColumnDimension('AC')->setWidth(15.88); $sheet->getColumnDimension('AD')->setWidth(30.63);
        $sheet->getColumnDimension('AE')->setWidth(10.63); $sheet->getColumnDimension('AF')->setWidth(11.88);
        $sheet->getColumnDimension('AG')->setWidth(11.88); $sheet->getColumnDimension('AH')->setWidth(32.88);
        $sheet->getColumnDimension('AI')->setWidth(32.88); $sheet->getColumnDimension('AJ')->setWidth(16.88);
        $sheet->getColumnDimension('AK')->setWidth(16.88); $sheet->getColumnDimension('AL')->setWidth(16.88);
        $sheet->getColumnDimension('AM')->setWidth(16.88); $sheet->getColumnDimension('AN')->setWidth(16.88);
        $sheet->getColumnDimension('AO')->setWidth(16.88);

        //Generation Data Table using PHPExcel
        $rowCount = 1;
        //voc_first Table
        $sheet->setCellValue('A'.$rowCount,'네트워크본부');
        $sheet->getStyle('A'.$rowCount)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A'.$rowCount)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('B'.$rowCount,'운용팀');
        $sheet->getStyle('B'.$rowCount)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('B'.$rowCount)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('C'.$rowCount,'운용사');
        $sheet->getStyle('C'.$rowCount)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('C'.$rowCount)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('D'.$rowCount,'접수일');
        $sheet->getStyle('D'.$rowCount)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('D'.$rowCount)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('E'.$rowCount,'접수시간');
        $sheet->getStyle('E'.$rowCount)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E'.$rowCount)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('F'.$rowCount,'상담유형1');
        $sheet->getStyle('F'.$rowCount)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('F'.$rowCount)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('G'.$rowCount,'상담유형2');
        $sheet->getStyle('G'.$rowCount)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('G'.$rowCount)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('H'.$rowCount,'상담유형3');
        $sheet->getStyle('H'.$rowCount)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('H'.$rowCount)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('I'.$rowCount,'상담유형4');
        $sheet->getStyle('I'.$rowCount)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('I'.$rowCount)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('J'.$rowCount,'등록일');
        $sheet->getStyle('J'.$rowCount)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('J'.$rowCount)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);


        //voc_second Table
        $sheet->setCellValue('K'.$rowCount,'상담사조치1');
        $sheet->getStyle('K'.$rowCount)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('K'.$rowCount)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('L'.$rowCount,'상담사조치2');
        $sheet->getStyle('L'.$rowCount)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('L'.$rowCount)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('M'.$rowCount,'상담사조치3');
        $sheet->getStyle('M'.$rowCount)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('M'.$rowCount)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('N'.$rowCount,'상담사조치4');
        $sheet->getStyle('N'.$rowCount)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('N'.$rowCount)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('O'.$rowCount,'단말기제조사');
        $sheet->getStyle('O'.$rowCount)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('O'.$rowCount)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('P'.$rowCount,'단말기모델명');
        $sheet->getStyle('P'.$rowCount)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('P'.$rowCount)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('Q'.$rowCount,'단말기모델명2');
        $sheet->getStyle('Q'.$rowCount)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('Q'.$rowCount)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('R'.$rowCount,'단말기코드');
        $sheet->getStyle('R'.$rowCount)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('R'.$rowCount)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('S'.$rowCount,'단말기출시일');
        $sheet->getStyle('S'.$rowCount)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('S'.$rowCount)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);


        //voc_third Table
        $sheet->setCellValue('T'.$rowCount,'HDVoice단말여부');
        $sheet->getStyle('T'.$rowCount)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('T'.$rowCount)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('U'.$rowCount,'NETWORK방식2');
        $sheet->getStyle('U'.$rowCount)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('U'.$rowCount)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('V'.$rowCount,'발생시기1');
        $sheet->getStyle('V'.$rowCount)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('V'.$rowCount)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('W'.$rowCount,'발생시기2');
        $sheet->getStyle('W'.$rowCount)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('W'.$rowCount)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('X'.$rowCount,'지역1');
        $sheet->getStyle('X'.$rowCount)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('X'.$rowCount)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('Y'.$rowCount,'지역2');
        $sheet->getStyle('Y'.$rowCount)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('Y'.$rowCount)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('Z'.$rowCount,'지역3');
        $sheet->getStyle('Z'.$rowCount)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('Z'.$rowCount)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('AA'.$rowCount,'시/도');
        $sheet->getStyle('AA'.$rowCount)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('AA'.$rowCount)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('AB'.$rowCount,'구/군명');
        $sheet->getStyle('AB'.$rowCount)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('AB'.$rowCount)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);


        //voc_fourth Table
        $sheet->setCellValue('AC'.$rowCount,'요금제코드명');
        $sheet->getStyle('AC'.$rowCount)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('AC'.$rowCount)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('AD'.$rowCount,'사용자AGENT');
        $sheet->getStyle('AD'.$rowCount)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('AD'.$rowCount)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('AE'.$rowCount,'단말기애칭');
        $sheet->getStyle('AE'.$rowCount)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('AE'.$rowCount)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('AF'.$rowCount,'USIM카드명');
        $sheet->getStyle('AF'.$rowCount)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('AF'.$rowCount)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('AG'.$rowCount,'댁내중계기여부');
        $sheet->getStyle('AG'.$rowCount)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('AG'.$rowCount)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('AH'.$rowCount,'메모');
        $sheet->getStyle('AH'.$rowCount)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('Ah'.$rowCount)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('AI'.$rowCount,'메모요약');
        $sheet->getStyle('AI'.$rowCount)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('AI'.$rowCount)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('AJ'.$rowCount,'메모분류');
        $sheet->getStyle('AJ'.$rowCount)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('AJ'.$rowCount)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('AK'.$rowCount,'메모분류2');
        $sheet->getStyle('AK'.$rowCount)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('AK'.$rowCount)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('AL'.$rowCount,'업데이트 유무');
        $sheet->getStyle('AL'.$rowCount)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('AL'.$rowCount)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('AM'.$rowCount,'해외로밍 유무');
        $sheet->getStyle('AM'.$rowCount)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('AM'.$rowCount)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('AN'.$rowCount,'소프트웨어');
        $sheet->getStyle('AN'.$rowCount)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('AN'.$rowCount)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->setCellValue('AO'.$rowCount,'이슈번호');
        $sheet->getStyle('AO'.$rowCount)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('AO'.$rowCount)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        $rowCount = 1;
        //load up voc_first data to php excel object
        $result = mysqli_query($conn, $base);
        mysqli_close($conn);
        while($row = mysqli_fetch_assoc($result)){
            $rowCount++;
            $sheet->setCellValue('A'.$rowCount, (string)$row['netHead']);
            $sheet->setCellValue('B'.$rowCount, (string)$row['manageTeam']);
            $sheet->setCellValue('C'.$rowCount, (string)$row['manageCo']);
            $sheet->setCellValue('D'.$rowCount, (string)$row['regiDate']);
            $sheet->setCellValue('E'.$rowCount,(string)$row['regiTime']);
            $sheet->setCellValue('F'.$rowCount, (string)$row['counsel1']);
            $sheet->setCellValue('G'.$rowCount,(string)$row['counsel2']);
            $sheet->setCellValue('H'.$rowCount, (string)$row['counsel3']);
            $sheet->setCellValue('I'.$rowCount, (string)$row['counsel4']);
            $sheet->setCellValue('J'.$rowCount, (string)$row['receiveDate']);

            $sheet->setCellValue('K'.$rowCount, (string)$row['action1']);
            $sheet->setCellValue('L'.$rowCount, (string)$row['action2']);
            $sheet->setCellValue('M'.$rowCount, (string)$row['action3']);
            $sheet->setCellValue('N'.$rowCount, (string)$row['action4']);
            $sheet->setCellValue('O'.$rowCount,(string)$row['manu']);
            $sheet->setCellValue('P'.$rowCount, (string)$row['model']);
            $sheet->setCellValue('Q'.$rowCount,(string)$row['model2']);
            $sheet->setCellValue('R'.$rowCount, (string)$row['devCode']);
            $sheet->setCellValue('S'.$rowCount, (string)$row['devLaunchDate']);

            $sheet->setCellValue('T'.$rowCount, (string)$row['hdvoiceFlag']);
            $sheet->setCellValue('U'.$rowCount, (string)$row['netMethod2']);
            $sheet->setCellValue('V'.$rowCount, (string)$row['ocSpot1']);
            $sheet->setCellValue('W'.$rowCount, (string)$row['ocSpot2']);
            $sheet->setCellValue('X'.$rowCount,(string)$row['loc1']);
            $sheet->setCellValue('Y'.$rowCount, (string)$row['loc2']);
            $sheet->setCellValue('Z'.$rowCount,(string)$row['loc3']);
            $sheet->setCellValue('AA'.$rowCount, (string)$row['state']);
            $sheet->setCellValue('AB'.$rowCount, (string)$row['district']);

            $sheet->setCellValue('AC'.$rowCount, (string)$row['planCode']);
            $sheet->setCellValue('AD'.$rowCount, (string)$row['userAgent']);
            $sheet->getStyle('AD'.$rowCount)->getAlignment()->setWrapText(true);
            $sheet->setCellValue('AE'.$rowCount, (string)$row['petName']);
            $sheet->setCellValue('AF'.$rowCount, (string)$row['usimName']);
            $sheet->setCellValue('AG'.$rowCount,(string)$row['repeaterFlag']);
            $sheet->setCellValue('AH'.$rowCount, (string)$row['memo']);
            $sheet->getStyle('AH'.$rowCount)->getAlignment()->setWrapText(true);
            $sheet->setCellValue('AI'.$rowCount,(string)$row['memoSum']);
            $sheet->getStyle('AI'.$rowCount)->getAlignment()->setWrapText(true);
            $sheet->setCellValue('AJ'.$rowCount, (string)$row['class1']);
            $sheet->setCellValue('AK'.$rowCount, (string)$row['class2']);
            $sheet->setCellValue('AL'.$rowCount, (string)$row['updateFlag']);
            $sheet->setCellValue('AM'.$rowCount, (string)$row['roamFlag']);
            $sheet->setCellValue('AN'.$rowCount, (string)$row['swVer']);
            $sheet->setCellValue('AO'.$rowCount, (string)$row['issueId']);
          }

        $filename = "VOC_DATA(".date('Y-m-d H:i:s').")";
        $filename = iconv("UTF-8", "euc-kr", $filename);

        // Redirect output to a client’s web browser(Excel5)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'.$filename.'.xlsx"');
        $writer = new Xlsx($objExcel);
      	$writer->save('php://output');
        exit();
        // echo "<script>location.href='".$redirect."';</script>";
      }catch(Exception $e){
        echo  $e->getMessage();
        mysqli_free_result($result);
        mysqli_close($conn);
        exit();
      }
?>
