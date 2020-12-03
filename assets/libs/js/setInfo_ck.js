function formCk(){
    var searchDate = document.getElementsByName('searchDate');
    var regiDate = document.getElementsByName('regiDate');
    var address = document.getElementsByName('url');
    var targetModel = document.getElementsByName('targetModel');
    var issue = document.getElementsByName('issue');
    var reproduce = document.getElementsByName('testReproduce');
    var version = document.getElementsByName('softVersion');
    var testStroy = document.getElementsByName('testStory');
    var result = document.getElementsByName('result');
    var spendTime = document.getElementsByName('spendTime');
    var opValue = result[0].options[result[0].selectedIndex].value;

    if(searchDate[0].value.trim() == ""){
        alert("작성일을 입력하여 주세요");
        searchDate[0].focus();
        return false;
    }else if(regiDate[0].value.trim() == ""){
        alert('등록일을 입력하여 주세요');
        regiDate[0].focus();
        return false;
    }else if(address[0].value.trim() == ""){
        alert('카페주소를 입력하여 주세요');
        address[0].focus();
        return false;
    }else if(targetModel[0].value.trim() == ""){
        alert('대상단말 모델명을 입력하여 주세요');
        targetModel[0].focus();
        return false;
    }else if(issue[0].value.trim() == ""){
        alert('이슈내용을 입력하여 주세요');
        issue[0].focus();
        return false;
    }else if(reproduce[0].value.trim() == ""){
        alert('재현경로를 입력하여 주세요');
        reproduce[0].focus();
        return false;
    }else if(spendTime[0].value.trim() == ""){
        alert('소요시간을 입력하여 주세요');
        spendTime[0].focus();
        return false;
    }else if(version[0].value.trim() == ""){
        alert('소프트웨어 버전을 입력하여 주세요');
        version[0].focus();
        return false;
    }else if(opValue != "OK" && testStroy[0].value.trim() == ""){
          alert('시험결과를 입력해 주세요.');
          return false;
    }else{
        var recon = confirm('저장하시겠습니까?');
        if(recon){
            return true;
        }else{
            return false;
        }
    }
}
