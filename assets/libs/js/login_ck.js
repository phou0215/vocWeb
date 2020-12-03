function loginCk(){
  var idValue = document.getElementsByName('id');
  var passValue = document.getElementsByName('password');
  if(idValue[0].value.trim() === ""){
    alert("아이디를 입력하여 주세요");
    idValue[0].focus();
    return false;
  }else if(passValue[0].value.trim() === ""){
    alert('비밀번호를 입력하여 주세요');
    passValue[0].focus();
    return false;
  }else{
    return true
  }
}
