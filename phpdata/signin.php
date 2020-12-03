
<!DOCTYPE html>
<html>
  <head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <meta name='description' content='Test EnC tech 서비스'>
    <meta name='author' content='hanrim'>
    <link rel='stylesheet' type='text/css' href='/voc/assets/libs/css/signin.css'>
    <link href='/bootstrap-3.3.4-dist/css/bootstrap.min.css' rel='stylesheet'>
    <link href="http://fonts.googleapis.com/earlyaccess/jejugothic.css" rel="stylesheet">
    <title>로그인</title>
  </head>

  <body>
      <div class='content'>
        <h3>VOMS Login</h3>
        <div id='sign_card'>
          <form id='login_form' name='loginform' enctype=''class='form-horizontal' action='/voc/phpdata/login_ck.php' method='POST' onsubmit='return loginCk()' >
              <div class='slot1'>
                <input class='form-control' type='text' id='formGroupInputId' name='id' placeholder='ID' autocomplete='on'>
                <input class='form-control' type='password' id='formGroupInputPass' name='password' placeholder='PASSWORD'>
              </div>
              <div class='slot2'>
                <input id='log_btn' type='submit' value='Log In'>
                <input id='sign_btn' type='button' value='Sign Up' onclick='movePage();'>
              </div>
          </form>
        </div>
    </div>
    <script type='text/javascript' src='/voc/assets/libs/js/login_ck.js'></script>
    <script type='text/javascript'>
      function movePage(){
        location.href='/voc/phpdata/signup.php';
      }
    </script>
  </body>
