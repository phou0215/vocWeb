<!DOCTYPE html>
<html>
  <head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <meta name='description' content='Test EnC tech 서비스'>
    <meta name='author' content='hanrim'>
    <link rel='stylesheet' type='text/css' href='/voc/assets/libs/css/signup.css'>
    <link href='/bootstrap-3.3.4-dist/css/bootstrap.min.css' rel='stylesheet'>
    <link href="http://fonts.googleapis.com/earlyaccess/jejugothic.css" rel="stylesheet">
    <title>회원가입</title>
  </head>

  <body>
    <div class='content'>
      <h3>VOMS Create Account</h3>
      <div id='sign_card'>
        <form name='join' method='post' action='/voc/phpdata/sign_ck.php' onsubmit='return signupCk();'>
          <div class='slot1'>
            <table class='table table-sm'>
              <tbody>
                  <tr>
                    <td class='label' >아이디*</td>
                    <td class='tableBody'><input class='form-control' type='text' size='30' name='id' placeholder='ID' style='color:black'></td>
                  </tr>
                  <tr>
                    <td class='label'>비밀번호*</td>
                    <td class='tableBody'><input class='form-control' type='password' size='30' name='password' placeholder='PASSWORD' style='color:black'></td>
                  </tr>
                  <tr>
                    <td class='label'>비밀번호 확인*</td>
                    <td class='tableBody'><input class='form-control' type='password' size='30' name='password2' placeholder='PASSWORD Check' style='color:black'></td>
                  </tr>
                  <tr>
                    <td class='label'>이름*</td>
                    <td class='tableBody'><input class='form-control' type='text' size='30' name='name' placeholder='NAME' style='color:black'></td>
                  </tr>
                  <tr>
                    <td class='label'>e-mail</td>
                    <td class='tableBody'><input class='form-control' type='email' size='30' name='email' placeholder='E-MAIL' style='color:black'></td>
                  </tr>
                  <tr hidden>
                    <td class='label' hidden>신청일</td>
                    <td class='tableBody' hidden><input id='reqDate' class='form-control' type='date' name=reqDate style='color:black'></td>
                  </tr>
              </tbody>
            </table>
          </div>
          <div class='slot2'>
              <input id='log_btn' type='button' value='Login Page' onclick='movePage();'>
              <input id='sign_btn' type='submit' value='Create an Account'>
          </div>
        </form>
      </div>
    </div>
    <script type='text/javascript' src='/voc/assets/libs/js/signup_ck.js'></script>
    <script type='text/javascript'>
      window.onload = function(){
          var date = new Date();
          var year = date.getFullYear();
          var month = date.getMonth()+1
          var day = date.getDate();
          if(month < 10){
              month = '0'+month;}
          if(day < 10){
              day = '0'+day;}
          var todayCon = year+'-'+month+'-'+day;
          document.getElementById('reqDate').defaultValue = todayCon;
      }
      function movePage(){
        location.href='/voc/phpdata/signin.php';
      }

    </script>
  </body>
</html>
