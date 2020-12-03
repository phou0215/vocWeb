
jQuery(document).ready(function($) {
    'use strict';

    // ==============================================================
    // Notification list
    // ==============================================================
    if ($(".notification-list").length) {

        $('.notification-list').slimScroll({
            height: '250px'
        });

    }

    // ==============================================================
    // Menu Slim Scroll List
    // ==============================================================


    if ($(".menu-list").length) {
        $('.menu-list').slimScroll({

        });
    }

    // ==============================================================
    // Sidebar scrollnavigation
    // ==============================================================

    if ($(".sidebar-nav-fixed a").length) {
        $('.sidebar-nav-fixed a')
            // Remove links that don't actually link to anything

            .click(function(event) {
                // On-page links
                if (
                    location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') &&
                    location.hostname == this.hostname
                ) {
                    // Figure out element to scroll to
                    var target = $(this.hash);
                    target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
                    // Does a scroll target exist?
                    if (target.length) {
                        // Only prevent default if animation is actually gonna happen
                        event.preventDefault();
                        $('html, body').animate({
                            scrollTop: target.offset().top - 90
                        }, 1000, function() {
                            // Callback after animation
                            // Must change focus!
                            var $target = $(target);
                            $target.focus();
                            if ($target.is(":focus")) { // Checking if the target was focused
                                return false;
                            } else {
                                $target.attr('tabindex', '-1'); // Adding tabindex for elements not focusable
                                $target.focus(); // Set focus again
                            };
                        });
                    }
                };
                $('.sidebar-nav-fixed a').each(function() {
                    $(this).removeClass('active');
                })
                $(this).addClass('active');
            });

    }

    // ==============================================================
    // tooltip
    // ==============================================================
    if ($('[data-toggle="tooltip"]').length) {

            $('[data-toggle="tooltip"]').tooltip()

        }

     // ==============================================================
    // popover
    // ==============================================================
       if ($('[data-toggle="popover"]').length) {
            $('[data-toggle="popover"]').popover()

    }
     // ==============================================================
    // Chat List Slim Scroll
    // ==============================================================


        if ($('.chat-list').length) {
            $('.chat-list').slimScroll({
            color: 'false',
            width: '100%'


        });
    }
    // ==============================================================
    // dropzone script
    // ==============================================================

 //     if ($('.dz-clickable').length) {
 //            $(".dz-clickable").dropzone({ url: "/file/post" });
 // }

}); // AND OF JQUERY

function executeSelf(type){
  if(type == 'manu'){
    var element = document.getElementById('manu_select');
    var type_ele = document.getElementById('manu_type');
    var input_ele = document.getElementById('manu_input');
    var selected = element.options[element.selectedIndex].text;
    if(selected == "직접입력"){
      input_ele.setAttribute('style','margin-bottom:5px;');
      input_ele.setAttribute('name','manu');
      type_ele.setAttribute('value','input');
      element.removeAttribute('name');
    }else{
      input_ele.setAttribute('style','display:none;');
      input_ele.removeAttribute('name');
      element.setAttribute('name','manu');
      type_ele.setAttribute('value','select');
    }
  }else if(type == 'os'){
    var element = document.getElementById('os_select');
    var type_ele = document.getElementById('os_type');
    var input_ele = document.getElementById('os_input');
    var selected = element.options[element.selectedIndex].text;
    if(selected == "직접입력"){
      input_ele.setAttribute('style','margin-bottom:5px;');
      input_ele.setAttribute('name','os');
      type_ele.setAttribute('value','input');
      element.removeAttribute('name');
    }else{
      input_ele.setAttribute('style','display:none;');
      input_ele.removeAttribute('name');
      type_ele.setAttribute('value','select');
      element.setAttribute('name','os');
    }
  }
}

function infoCheck(){

  var model = document.getElementsByName('model');
  var regiDate = document.getElementsByName('regiDate');
  var con = confirm("단말 모델 정보를 등록하시겠습니까?");

  if(con){

    if(model[0].value.trim() == ""){
        alert("모델명을 입력하여 주세요.");
        model[0].focus();
        return false;
    }
    if(regiDate[0].value.trim() == ""){
      alert("등록일을 입력하여 주세요.");
      regiDate[0].focus();
      return false;
    }
    return true;
  }else{
    return false;
  }
}

function settingCheck(){

  var i = 0;
  var set_data = [];
  var con = confirm("단말 모델 정보를 등록하시겠습니까?");

  if(con){
    $('.setVal').each(function(index, item){

      var value = $(this).value().trim();
      if(value == ""){
        alert("설정값을 비울 수 없습니다.");
        return false;
      }
    });
    return true;
  }else{
    return false;
  }
}

// settingInit
//$(selector).val(value)
function settingInit(){

  var i = 0;
  var init_data = [5,5,20,5,5,5,20,5,5,5,20,5];
  $('.setVal').each(function(index, item){
    $(this).val(init_data[index]);
  });
  toastr.success('초기화', '초기 세팅값 입력');
}

function classInfoCheck(){
  var category = document.getElementById('category');

  if(category.value.trim() == ""){
      alert("Category 이름을 입력하여 주세요.");
      category.focus();
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

function userInfoCheck(){
  var ident = document.getElementById('ident');
  var name= document.getElementById('name');

  if(ident.value.trim() == ""){
      alert("ID를 입력하여 주세요.");
      ident.focus();
      return false;
  }else if(name.value.trim() == ""){
      alert("사용자 이름을 입력하여 주세요.");
      ident.focus();
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

function setFocus(no, checked, device_flag, device_keyword){
  if(checked == "0"){
    var flag = confirm('선택하신 단말에 대해 적용 하시겠습니까?');
    if(flag){
      if(device_keyword == undefined){
        location.href='/voc/admin/phpdata/addFocus.php?id='+no+'&type=1&flag='+device_flag;
      }else{
        location.href='/voc/admin/phpdata/addFocus.php?id='+no+'&type=1&flag='+device_flag+'&device_keyword='+device_keyword;
      }
    }else{
      return
    }
  }else{
    var flag = confirm('선택하신 단말에 대해 적용 해제 하시겠습니까?');
    if(flag){
      if(device_keyword == undefined){
        location.href='/voc/admin/phpdata/addFocus.php?id='+no+'&type=0&flag='+device_flag;
      }else{
        location.href='/voc/admin/phpdata/addFocus.php?id='+no+'&type=0&flag='+device_flag+'&device_keyword='+device_keyword;
      }
    }else{
      return
    }
  }
}

function setFocusOn(no, position, device_flag, deploy_flag){

  if(deploy_flag != 0){
    var count = document.getElementById('focusCount');
    var ele = document.getElementById('focusOn'+position);
    var focus_flag = ele.getAttribute('data');
    var con;
    if (focus_flag == 0){
      con = confirm("해당 모델에 대해 관심 등록을 하시겠습니까?");
    }else{
      con = confirm("해당 모델에 대해 관심 해제를 하시겠습니까?");
    }
    if (con){
      var jsonData;
      // toastr.success('www.leafcats.com', 'Toastr success!');
      // toastr.info('www.leafcats.com', 'Toastr info!');
      // toastr.warning('www.leafcats.com', 'Toastr warning!');
      // toastr.error('www.leafcats.com', 'Toastr error!');
      toastr.options = {
          closeButton: true,
          progressBar: true,
          showMethod: 'slideDown',
          preventDuplication: false,
          timeOut: 2000
      };
      console.log("/voc/admin/phpdata/addFocusOn.php?id="+no+"&type="+device_flag+"&flag="+focus_flag);
      var result= $.ajax({
                              url:"/voc/admin/phpdata/addFocusOn.php?id="+no+"&type="+device_flag+"&flag="+focus_flag,
                              type:"get",
                              dataType:"json",
                              // data:{"startDate":startDateManu, "endDate":endDateManu, "manus":manus},
                              async: false,
                              success: function (data){
                                // console.log(data);
                                jsonData = data;
                                if (focus_flag == 1){
                                  if(jsonData.message == 0){
                                    toastr.success('처리성공', '정상적으로 관심 해제 하였습니다.');
                                    ele.setAttribute('style','color:white; background-color:grey; border-color:grey;');
                                    ele.setAttribute('data', jsonData.message);
                                    var num = parseInt(count.innerText)-1;
                                    count.innerText = num;
                                  }else{
                                    toastr.error('처리실패', '처리에 실패하였습니다.');
                                  }
                                  console.log(jsonData.message);
                                }else{
                                  if(jsonData.message == 1){
                                    toastr.success('처리성공', '정상적으로 관심 등록 하였습니다.');
                                    ele.setAttribute('style','color:white; background-color:#ff407b; border-color:#ff407b;');
                                    ele.setAttribute('data', jsonData.message);
                                    var num = parseInt(count.innerText)+1;
                                    count.innerText = num;
                                  }else{
                                    toastr.error('처리실패', '처리에 실패하였습니다.');
                                  }
                                  console.log(jsonData.message);
                                }
                              },
                              error: function (request, status, error){
                                  console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
                              }
                          });
    }else{
      return
    }
  }else{
    alert('해당 모델의 Deploy를 먼저 적용해 주셔야 합니다.');
  }
}

function checkChange(){
  var con = confirm('리스트 노출을 변경하시겠습니까?')
  if(con){
    return true;
  }else{
    return false;
  }
}

function setfocusUser(no,checked){
  if(checked == "0"){
    var flag = confirm('선택하신 사용자에 대해 사용 허가를 하시겠습니까?');
    if(flag){
      location.href='/voc/admin/phpdata/userPermission.php?id='+no+'&type=1';
    }else{
      return true;}
  }else{
    var flag = confirm('선택하신 사용자에 대해 사용 해제를 하시겠습니까?');
    if(flag){
      location.href='/voc/admin/phpdata/userPermission.php?id='+no+'&type=0';
    }else{
      return true;
    }}
}

function setChart(no,checked){
  if(checked == "0"){
    var flag = confirm('선택하신 Category를 통계에 반영 하시겠습니까?');
    if(flag){
      location.href='/voc/admin/phpdata/addchart.php?no='+no+'&type=1';
    }else{
      return true;}
  }else{
    var flag = confirm('선택하신 Category를 통계에서 해제 하시겠습니까?');
    if(flag){
      location.href='/voc/admin/phpdata/addchart.php?no='+no+'&type=0';
    }else{
      return true;
    }}
}

function searchDevice(){
    //model_id".$i."'
    var input_field = document.getElementsByClassName('form-control form-control-lg');
    var text = input_field[0].value.replace(" ","").toLowerCase();
    var idx_location = "";
    if(text != ""){
      var elements = document.getElementsByClassName('font-24 m-b-10');
      var elements_card = document.getElementsByClassName('card');
      var count = elements.length;
      var i = 0;

      while(i < count){
        var modelName = elements[i].innerText.replace(" ","").toLowerCase();
        if(modelName.indexOf(text) != -1){
          idx_location = elements_card[i].getAttribute('id');
          break;
        }
        i++;
      }
    }
    if(idx_location != ""){
      location.href='#'+idx_location;
    }else{
      alert('조회하신 디바이스가 없습니다.');
    }
  }

function searchClass(){
    //model_id".$i."'
    var input_field = document.getElementsByClassName('form-control form-control-lg');
    var text = input_field[0].value.replace(" ","").toLowerCase();
    if(text == ""){
      idx_location = ""
    }else{
      var elements = document.getElementsByClassName('font-24 m-b-10');
      var elements_card = document.getElementsByClassName('card');
      var count = elements.length;
      var i = 0
      while(i<count){
        var className = elements[i].innerText.replace(" ","").toLowerCase();
        if(className.indexOf(text) != -1){
          idx = elements_card[i].getAttribute('id');
          idx_location = idx;
          break;
        }else{
          idx_location = "";
        }
       i++;
      }
      if(idx_location!= ""){
        location.href='#'+idx_location;
      }else{
        alert('조회하신 사용자 이름이 없습니다.');
      }
    }
}

function searchDevice(){
    //model_id".$i."'
    var input_field = document.getElementsByClassName('form-control form-control-lg');
    var text = input_field[0].value.replace(" ","").toLowerCase();
    var idx_location = "";
    if(text != ""){
      var elements = document.getElementsByClassName('font-24 m-b-10');
      var elements_card = document.getElementsByClassName('card');
      var count = elements.length;
      var i = 0;

      while(i < count){
        var modelName = elements[i].innerText.replace(" ","").toLowerCase();
        if(modelName.indexOf(text) != -1){
          idx_location = elements_card[i].getAttribute('id');
          break;
        }
        i++;
      }
    }
    if(idx_location != ""){
      location.href='#'+idx_location;
    }else{
      alert('조회하신 디바이스가 없습니다.');
    }
  }

function searchUser(){
    //model_id".$i."'
    var input_field = document.getElementsByClassName('form-control form-control-lg');
    var text = input_field[0].value.replace(" ","").toLowerCase();
    var idx_location = "";
    if(text != ""){
      var elements = document.getElementsByClassName('font-24 m-b-10');
      var elements_card = document.getElementsByClassName('card');
      var count = elements.length;
      var i = 0;

      while(i < count){
        var modelName = elements[i].innerText.replace(" ","").toLowerCase();
        if(modelName.indexOf(text) != -1){
          idx_location = elements_card[i].getAttribute('id');
          break;
        }
        i++;
      }
    }
    if(idx_location != ""){
      location.href='#'+idx_location;
    }else{
      alert('조회하신 사용자가 없습니다.');
    }
}

function setCheck(no){
  var element = document.getElementById(no)
  if(element.hasAttribute('checked')){
    element.removeAttribute('checked')
  }else{
    element.setAttribute('checked','checked')
  }

}

function totalSelect(){
  var element_tot = document.getElementById('btn_totSelect');
  var elements = document.getElementsByClassName('checkUp');
  var count = elements.length;
  var flag = element_tot.getAttribute('checkFlag');
  var i= 0;
  if(flag == 'false'){
    element_tot.innerText = '전체해제';
    element_tot.setAttribute('checkFlag','true');
    while(i<count){
      elements[i].setAttribute('checked','checked');
      elements[i].checked = true;
      i++;}
  }else{
    element_tot.innerText = '전체선택';
    element_tot.setAttribute('checkflag', 'false');
    while(i<count){
      elements[i].removeAttribute('checked');
      elements[i].checked = false;
      i++;}
    }
  }

function removeDeviceData(flag, device_keyword){
    var i = 0;
    var elements = document.getElementsByClassName('checkUp');
    var count = elements.length;
    var data = "";
    var selectedEle = new Array();
    while(i<count){
      var flag = elements[i].hasAttribute('checked');
      if(flag){
        var id_num = elements[i].getAttribute('id');
        selectedEle.push(id_num);
      }
      i++;
    }
    if(selectedEle.length == 0){
      alert('선택된 디바이스가 없습니다.');
    }else{
      var con = confirm('선택한 디바이스 정보를 삭제하시겠습니까?');
      if(con){
        i=0;
        while(i<selectedEle.length){
          data = data+selectedEle[i]+","
          i++;
        }
        data = data.substring(0,data.length-1);
        //idDataText
        if (device_keyword == undefined){
          location.href='/voc/admin/phpdata/removeDeviceData.php?id='+data+'&flag='+flag;
        }else{
          location.href='/voc/admin/phpdata/removeDeviceData.php?id='+data+'&flag='+flag+'&device_keyword='+device_keyword;
        }
      }
    }
}

function removeUserData(){
    var i = 0;
    var elements = document.getElementsByClassName('checkUp');
    var count = elements.length;
    var data = "";
    var selectedEle = new Array();
    while(i<count){
      var flag = elements[i].hasAttribute('checked');
      if(flag){
        var id_num = elements[i].getAttribute('id');
        selectedEle.push(id_num);
      }
      i++;
    }
    if(selectedEle.length == 0){
      alert('선택된 사용자가 없습니다.');
    }else{
      var con = confirm('선택한 사용자 정보를 삭제하시겠습니까?');
      if(con){
        i=0;
        while(i<selectedEle.length){
          data = data+selectedEle[i]+","
          i++;
        }
        data = data.substring(0,data.length-1);
        //idDataText
        location.href='/voc/admin/phpdata/removeUserData.php?id='+data;
      }
    }
}

function removeClassData(){
    var i = 0;
    var elements = document.getElementsByClassName('checkUp');
    var count = elements.length;
    var data = "";
    var selectedEle = new Array();
    while(i<count){
      var flag = elements[i].hasAttribute('checked');
      if(flag){
        var id_num = elements[i].getAttribute('id');
        selectedEle.push(id_num);
      }
      i++;
    }
    if(selectedEle.length == 0){
      alert('선택된 Class 이름이 없습니다.');
    }else{
      var con = confirm('선택한 Class 정보를 삭제하시겠습니까?');
      if(con){
        i=0;
        while(i<selectedEle.length){
          data = data+selectedEle[i]+","
          i++;
        }
        data = data.substring(0,data.length-1);
        //idDataText
        location.href='/voc/admin/phpdata/removeClassData.php?no='+data;
      }
    }
  }

function updateDeviceData(){
    var i = 0;
    var elements = document.getElementsByClassName('checkUp');
    var count = elements.length;
    var data = "";
    var selectedEle = new Array();
    while(i<count){
      var flag = elements[i].hasAttribute('checked');
      if(flag){
        var id_num = elements[i].getAttribute('id');
        selectedEle.push(id_num);
      }
      i++;
    }
    if(selectedEle.length == 0){
      alert('선택된 디바이스가 없습니다.');
    }else if(selectedEle.length > 1){
      alert('하나의 디바이스만 선택해 주세요.');
    }else{
      var con = confirm('선택한 디바이스 정보를 업데이트 하시겠습니까?');
      if(con){
        data = selectedEle[0];
        //idDataText
        location.href='/voc/admin/deviceUp.php?id='+data;
      }
    }
}

function updateUserData(){
    var i = 0;
    var elements = document.getElementsByClassName('checkUp');
    var count = elements.length;
    var data = "";
    var selectedEle = new Array();
    while(i<count){
      var flag = elements[i].hasAttribute('checked');
      if(flag){
        var id_num = elements[i].getAttribute('id');
        selectedEle.push(id_num);
      }
      i++;
    }
    if(selectedEle.length == 0){
      alert('선택된 사용자가 없습니다.');
    }else if(selectedEle.length > 1){
      alert('한명만 선택해 주세요.');
    }else{
      var con = confirm('선택한 사용자 정보를 업데이트 하시겠습니까?');
      if(con){
        data = selectedEle[0];
        //idDataText
        location.href='/voc/admin/accountsUp.php?id='+data;
      }
    }
}

function deviceDeploy(){

    var eles = document.getElementsByClassName('checkUp');
    var set_ele = document.getElementById('device_models_de');
    var i=0;
    var target_id = new Array();
    while(i < eles.length){
      if (eles[i].hasAttribute('checked')){
        target_id.push(eles[i].getAttribute('id'));
      }
      i++;
    }

    if (target_id.length != 0){
      var con = confirm("선택하신 모델에 대해서 적용하시겠습니까?");
      if (con){
        set_ele.setAttribute('value',target_id);
      }else{
        return false;
      }

    }else{
      alert("선택된 항목이 없습니다.")
      return false;
    }
    return true;
}

function deviceUndeploy(){

    var eles = document.getElementsByClassName('checkUp');
    var set_ele = document.getElementById('device_models_un');
    var i=0;
    var target_id = new Array();
    while(i < eles.length){
      if (eles[i].hasAttribute('checked')){
        target_id.push(eles[i].getAttribute('id'));
      }
      i++;
    }

    if (target_id.length != 0){
      var con = confirm("선택하신 모델에 대해서 적용하시겠습니까?");
      if (con){
        set_ele.setAttribute('value',target_id);
      }else{
        return false;
      }

    }else{
      alert("선택된 항목이 없습니다.")
      return false;
    }
    return true;
}

function moveClassAd(){
    location.href='/voc/admin/classAd.php'
  }
