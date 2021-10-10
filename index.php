<?php
$user_sql='';//数据库用户名
$password_sql='';//数据库密码
$sql_name='';//数据库名称
$sql_location='localhost';//数据库位置一般为 localhost

$user_ID="";//用户账号
$user_name="";//用户名字

$message_pieces=25;//加载信息个数,默认25


$con = mysqli_connect($sql_location,$user_sql,$password_sql,$sql_name);
//以下为sql语句(放在数据库里查询)，请一步一步输入
//CREATE TABLE user_message(time char(255),message mediumtext,now int(11));
//ALTER TABLE `user_message` ADD PRIMARY KEY(`time`)
//ALTER TABLE `user_message` ADD INDEX(`time`)
setcookie('user_sql',$user_sql,-1);//注意，这是数据库信息，为了帮助小白写的cookie
setcookie('password_sql',$password_sql,-1);//请注意更改
setcookie('sql_name',$sql_name,-1);
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
$spl_all = "SELECT COUNT(*) AS 'all' FROM `user_message`";
$sqlsses = mysqli_query($con,$spl_all);
$rowses = intval(mysqli_fetch_array($sqlsses)['all']);
if($rowses <= 25){
    $load_message = false;
}else{
    $load_message = true;
}
?>
<html>
    <head>
        <meta charset="uft-8" />
		<title>在线聊天</title>
        <style>
        html body{height:100%;width:100%;overflow-x:hidden;overflow-y:hidden;}
        #test{
            width:60%;
            height:16%;
            background-color: white;
            outline: 0;
            font-size: 16px;
            overflow-x: hidden;
            overflow-y: auto;
            position: absolute;
            top:calc(65% + 68px);
            border-top-style: solid;
            border-top-width: 1px;
            border-top-color:rgb(158 158 158);
            left:10%;
            -webkit-user-modify: read-write-plaintext-only;
        }
        [contentEditable=true]:empty:not(:focus):before{
            content:attr(data-text);
        }
        .bottom_enter{
            position:absolute;
            top:calc(%81 + 68px);
            left:10%;
            width:60%;
            height:4%;background-color:white
        }
        #message_enter{
            background: #4ca5f1;
            float: right;
            width: 95px;
            height: 28px;
            -webkit-border-radius: 4px;
            -moz-border-radius: 4px;
            border-radius: 4px;
            font-size: 14px;
            color: #fff;
            border:none;
            margin-right:5px ;
        }
        #message_enter:hover{
            background-color: #3fa5fd;
        }
        .list_button{
            width:100%;
            height:5%;
            border:none;
            background-color:rgb(240 240 244);
            color:rgb(7 119 180);
        }
        .list_button:hover{
            background-color: rgb(233 233 236);
        }
        </style>
         <script>
            //预加载代码
            //load_ajax(文件路径)GET进行异步
            var message_i
            <?php
            if($load_message == true){
                $times = $rowses - $message_pieces;
            }else if($load_message == false){
                $times = 1;
            }
            echo 'message_i = '.strval($times);
            ?>
            
            var send = false
            var getting = false
            function load_ajax(addrness,who,axaj){
                var xmlhttp;
                if(window.XMLHttpRequest){
                     xmlhttp=new XMLHttpRequest()
                }else{
                     xmlhttp=new ActiveXObject("Microsoft.XMLHTTP")
                }
                xmlhttp.onreadystatechange=function(){
                  if (xmlhttp.readyState==4 && xmlhttp.status==200){
                    result=xmlhttp.responseText
                    if (who == "get"){
                        if(result != ""){
                            message_i = message_i + 1
                            contents = result.substring(0,result.indexOf("{>.}/',.;:'")+11)
                            get_message(contents)
                            }
                            getting = false
                    }
                    if(result == "send_true"){
                        send = false
                    }
                    //执行异步后要执行的代码
                  }
                  if(xmlhttp.status == 404){
                      alert("文件丢失")
                  }
            }
                xmlhttp.open("GET",encodeURI(addrness),axaj);
                xmlhttp.send();
            }
            document.onkeydown = function (keydowned) {
            var theEvent = window.event || keydowned;
            var code = theEvent.keyCode || theEvent.which || theEvent.charCode;
            if (code == 13) {
                document.getElementById('message_enter').click()
                return false;
            }
        }
            function message_send(message_contentses,who){
                //message_content是消息内容
                //who是消息发送者的名字
                send = true
                if(message_contentses == ""){
                    return false
                }
                document.getElementById('message_show').scrollTo(0,document.getElementById('message_show').scrollHeight)
                document.getElementById('test').innerHTML = ""
                load_ajax("./send_message.php/?air="+Math.random()+"&username="+who+"&data="+message_contentses,"send",true)
            }
            function get_message(message_contents){ //数据格式必须是:   $user_name($user_id)/$text{>.}/',.;:'
                name=message_contents.substring(0,message_contents.indexOf("/"))
                text=message_contents.substring(message_contents.indexOf("/")+1,message_contents.indexOf("{>.}/',.;:'"))
                if(text == ""){
                    return false
                }
                var next = ""
                var texts = ""
                for (i=0;i<=Math.ceil(text.length/24);i++) {
                    texts = texts + text.substring(i*24,i*24+24) + "<br>"
                    next = next + "<br>"
                }
                names = name.substring(name.lastIndexOf('('),name.length)
                if(names == <?php echo'\'('.$user_ID.')'.'\''; ?>){
                all_text="<span style='font-size:10px;position:absolute;right:0'>"+name+"</span><br><span style='display:block;position:absolute;right:10px;color:rgb(1 102 158)'>"+texts+"</span>"+next
                }else{
                all_text="<span style='font-size:10px;position:absolute;left:0'>"+name+"</span><br><span style='display:block;position:absolute;left:10px;color:rgb(98 103 0)'>"+texts+"</span>"+next
                }
                old=document.getElementById('message_show').innerHTML
                now=old+all_text
                document.getElementById('message_show').innerHTML=now
                document.getElementById('message_show').scrollTo(0,document.getElementById('message_show').scrollHeight)
            }
            var message_loop = self.setInterval(function(){
                if(send != true){
                    if(getting == false){
                load_ajax("./get_message.php/?air="+Math.random()+"&number="+String(message_i),"get",true);
                getting = true
                    }
                }
            },10)
        </script>
    </head>
    <body style="background-image:url(./communication.jpeg);background-size:cover">
        <div id="message_head" style="background-color:rgb(235 234 234);width:80%;height:60px;border-top-left-radius:25px;border-top-right-radius:25px;position: absolute;left:10%;top:18px"><span id="message_head_content" style="position:absolute;top:12px;font-size:24px;left:10px">你正在与所有人聊天</span></div>
        <div id="message_show" style="overflow-x: hidden;overflow-y: auto;background-color:white;width:60%;height:65%;position: absolute;left:10%;top:68px;font-size:18px"></div>
        <div id="user_list" style="background-color:white;width:20%;height:87%;position: absolute;left:70%;top:68px;border-left-style: solid;border-left-width:1px;border-left-color:rgb(226 226 226);border-bottom-right-radius:25px"><button class='list_button'>公共聊天</button><br><?php  ?></div>
        <div id="test" contenteditable="true" data-text="输入消息"></div>
        <div style="position:absolute;top:calc(81% + 68px);left:10%;width:60%;height:6%;background-color:white"><span style="float:left;font-size:16px">最多输入500字</span><button id="message_enter" onclick="message_send(document.getElementById('test').innerHTML,<?php echo'\''.$user_name.'('.$user_ID.')'.'\''; ?>)">发送(Enter)</button></div>    
    </body>
</html>