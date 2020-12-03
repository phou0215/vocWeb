function signupCk(){
  var idValue = document.getElementsByName('id');
  var pass1Value = document.getElementsByName('password');
  var pass2Value = document.getElementsByName('password2');
  var nameValue = document.getElementsByName('name');
  var mailValue = document.getElementsByName('email');
  if(idValue[0].value.trim() === ""){
    alert("아이디를 입력하여 주세요");
    idValue[0].focus();
    return false;

  }else if(pass1Value[0].value.trim() === ""){
    alert('비밀번호를 입력하여 주세요');
    pass1Value[0].focus();
    return false;

  }else if(pass2Value[0].value.trim() === ""){
    alert('비밀번호 확인을 입력하여 주세요');
    pass2Value[0].focus();
    return false;

  }else if(nameValue[0].value.trim() === ""){
    alert('이름을 입력하여 주세요');
    nameValue[0].focus();
    return false;

  // }else if(mailValue[0].value.trim() === ""){
  //   alert('이메일을 입력하여 주세요');
  //   mailValue[0].focus();
  //   return false;

  }else if(pass1Value[0].value.trim() != pass2Value[0].value.trim()){
    alert('비밀번호와 비밀번호 확인 입력값이 맞지 않습니다.\n다시 한번 확인해 주세요.');
    pass1Value[0].value = "";
    pass2Value[0].value = "";
    pass1Value[0].focus();
    return false;

  }else{
    return true;
  }
}
