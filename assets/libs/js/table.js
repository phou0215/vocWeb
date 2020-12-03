

function fileDownload(){
  var downFlag = confirm('해당 VOC Data의 다운로드를 진행하시겠습니까?');
  if(downFlag == true){
  	return true;
  }else{
    return false;
  }
}

function setMemoBalloon(){
  var tableEles = document.getElementsByClassName('text-left');
  var dataEles = document.getElementsByClassName('datas')
  var i = 0;
  while(i < tableEles.length){
    tableEles[i].setAttribute('title', dataEles[i].innerText);
    i++;
  }
}

function checkValue(){
  var returnFlag = true;
  var input = document.getElementById('searchText');
  var input_2 = document.getElementById('searchText_2');
  var selectType = document.getElementById('selectType').value;
  var startEle = document.getElementById('startDate');
  var endEle = document.getElementById('endDate');
  var selectEle = document.getElementById('searchSelect');
  var startVal = startEle.value;
  var endVal = endEle.value;
  //단수 Select 조건인 경우
  if(selectType == "0"){
    if(input.value.trim() != "" && startVal == "" && endVal == ""){
      startEle.removeAttribute("name");
      input_2.removeAttribute("name");
      endEle.removeAttribute("name");
    }else if(input.value.trim() != "" && startVal != "" && endVal == ""){
      input_2.removeAttribute("name");
      endEle.removeAttribute("name");
    }else if(input.value.trim() != "" && startVal == "" && endVal != ""){
      input_2.removeAttribute("name");
      startEle.removeAttribute("name");
    }else if(input.value.trim() == "" && startVal != "" && endVal != ""){
      input.removeAttribute("name");
      input_2.removeAttribute("name");
      selectEle.removeAttribute("name");
    }else if(input.value.trim() == "" && startVal != "" && endVal == ""){
      endEle.removeAttribute("name");
      input_2.removeAttribute("name");
      input.removeAttribute("name");
      selectEle.removeAttribute("name");
    }else if(input.value.trim() == "" && startVal == "" && endVal != ""){
      startEle.removeAttribute("name");
      input_2.removeAttribute("name");
      input.removeAttribute("name");
      selectEle.removeAttribute("name");
    }else if(input.value.trim() == "" && startVal == "" && endVal == ""){
      startEle.removeAttribute("name");
      input_2.removeAttribute("name");
      endEle.removeAttribute("name");
      input.removeAttribute("name");
      selectEle.removeAttribute("name");
    }
    return returnFlag;
  //복수 Select 조건인 경우
  }else{
    if(input.value.trim() != "" && input_2.value.trim() != "" && startVal == "" && endVal == ""){
      startEle.removeAttribute("name");
      endEle.removeAttribute("name");
    }else if(input.value.trim() == "" || input_2.value.trim() == ""){
      alert('복수 검색에서는 검색어를 모두 입력하셔야 합니다.');
      returnFlag = false;
    }else if(input.value.trim() != "" && input_2.value.trim() != "" && startVal != "" && endVal == ""){
      endEle.removeAttribute("name");
    }else if(input.value.trim() != "" && input_2.value.trim() != "" && startVal == "" && endVal != ""){
      startEle.removeAttribute("name");
    }
  }
  return returnFlag
}

function checkValue_2(){

  var input = document.getElementById('searchText');
  var startEle = document.getElementById('startDate');
  var endEle = document.getElementById('endDate');
  var selectEle = document.getElementById('searchSelect');
  var startVal = startEle.value;
  var endVal = endEle.value;

  if(input.value.trim() != "" && startVal == "" && endVal == ""){
    startEle.removeAttribute("name");
    endEle.removeAttribute("name");
  }else if(input.value.trim() != "" && startVal != "" && endVal == ""){
    endEle.removeAttribute("name");
  }else if(input.value.trim() != "" && startVal == "" && endVal != ""){
    startEle.removeAttribute("name");
  }else if(input.value.trim() == "" && startVal != "" && endVal != ""){
    input.removeAttribute("name");
    selectEle.removeAttribute("name");
  }else if(input.value.trim() == "" && startVal != "" && endVal == ""){
    endEle.removeAttribute("name");
    input.removeAttribute("name");
    selectEle.removeAttribute("name");
  }else if(input.value.trim() == "" && startVal == "" && endVal != ""){
    startEle.removeAttribute("name");
    input.removeAttribute("name");
    selectEle.removeAttribute("name");
  }else if(input.value.trim() == "" && startVal == "" && endVal == ""){
    startEle.removeAttribute("name");
    endEle.removeAttribute("name");
    input.removeAttribute("name");
    selectEle.removeAttribute("name");
  }
}

function moveFirst(){
  location.href="/voc/data/index.php";
}

function checkSelect(){
  var ele = document.getElementById("searchSelect");
  var opEles = document.getElementsByClassName('searchOp');
  var selectType = document.getElementById('selectType');
  var inputEle = document.getElementById("searchText");
  var inputEle_2 = document.getElementById("searchText_2");
  var selectOp = ele.options[ele.selectedIndex].value;
  var incluText = ",";
  //placeholder 값 초기화
  inputEle.setAttribute("placeholder","");
  inputEle_2.setAttribute("placeholder","");

  //복수 조건 검색 여부 확인
  if(selectOp.indexOf(incluText) != -1){
    var i =0;
    while(i<opEles.length){
      opEles[i].removeAttribute('hidden');
      i++;
    }
    selectType.value = "1";
    inputEle.setAttribute("style","height:35px;width:100px;");
    inputEle_2.setAttribute("style","height:35px;width:100px;");

  }else{
    var i =0;
    while(i<opEles.length){
      opEles[i].setAttribute("hidden", "hidden");
      i++;
    }
    //등록일 확인
    if(selectOp == "receiveDate"){
        inputEle.setAttribute("placeholder","YYYY-MM-DD");
    }
    selectType.value = "0";
    inputEle.setAttribute("style","height:35px;");
    inputEle_2.setAttribute("style","height:35px;");
  }
}

function subsSelect(){
  var ele = document.getElementById("searchSelect");
  // var selectType = document.getElementById('selectType');
  var inputEle = document.getElementById("searchText");
  var selectOp = ele.options[ele.selectedIndex].value;

  //placeholder 값 초기화
  inputEle.setAttribute("placeholder","");

  //등록일 확인
  if(selectOp == "regiDate" || selectOp == "subsDate"){
      inputEle.setAttribute("placeholder","YYYY-MM-DD");
  }
  inputEle.setAttribute("style","height:35px;");
}
