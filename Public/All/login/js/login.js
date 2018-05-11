$(function(){

    $(".sign_button_choose").click(function(){
        $(".login_choose").css("display","none");
        $(".sign_choose").css("display","block");
        $(".login_button_choose").css("color","white");
        $(".sign_button_choose").css("color","red");
    });
    $(".login_button_choose").click(function(){
        $(".login_choose").css("display","block");
        $(".sign_choose").css("display","none");
        $(".login_button_choose").css("color","red");
        $(".sign_button_choose").css("color","white");
    });

    $(".login_confirm").click(function(){
        console.log($(this));
        var par = $(this).parent().prevAll(".am-form-group");
        var name = par.find(".user_name").val();
        var password = par.find(".user_password").val();
        console.log(name);
        console.log(password);
        $.ajax({
                url:'/Auth/Login/Login/Login',
                type: 'POST',
                data: {
                    name:name,
                    password:password
                },
                success: function(data) {
                    var dataObj = eval("(" + data + ")");
                    if(dataObj['status']==200){
                        alert(dataObj['message']);
                        self.location='/Auth/Login/Main/Main';
                    }else if(dataObj['status']==400){
                        alert(dataObj['message']);
                    }else{
                        alert("未知错误");
                    }
                },
                fail: function() {
                    alert("fail");
                }
        });
    });
    $(".sign_confirm").click(function(){
        console.log($(this));
        var par = $(this).parent().prevAll(".am-form-group");
        var name = par.find(".user_name").val();
        var password = par.find(".user_password").val();
        var user_nickname = par.find(".user_nickname").val();
        console.log(name);
        console.log(password);
        $.ajax({
                url:'/Auth/Login/Login/Sign',
                type: 'POST',
                data: {
                    name:name,
                    password:password,
                    user_nickname:user_nickname
                },
                success: function(data) {
                    var dataObj = eval("(" + data + ")");
                    if(dataObj['status']==200){
                        alert(dataObj['message']);
                        self.location='/Auth/Login/Main/Main';
                    }else if(dataObj['status']==400){
                        alert(dataObj['message']);
                    }else{
                        alert("未知错误");
                    }
                },
                fail: function() {
                    alert("fail");
                }
        });
    });
});