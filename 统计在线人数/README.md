# 统计在线人数 
基于[PHPSocketIO](https://github.com/walkor/phpsocket.io)

## 运行步骤

### 1.运行PHPSocketIO服务端
    php index.php start
![](http://www.884358.com/wp-content/uploads/2019/09/2019092517163334.png)
### 2.客户端网页代码如下：
	<div id="online_box"></div>
	<script src="https://cdn.bootcss.com/jquery/2.1.0/jquery.min.js"></script>
	<script src="https://cdn.bootcss.com/socket.io/1.3.7/socket.io.js"></script>
	<script>
		var socket = io('http://127.0.0.1:1001');
		var uid = getSessionId();	//替换为用户id，建议设置为session_id
		//连接后登录
		socket.on('connect', function(){
			socket.emit('login', uid);
		});
		
		//后端推送来在线数据时
		socket.on('update_online_count', function(online_stat){
			$('#online_box').html(online_stat);
		});
		
		function getSessionId(){
			var c_name = 'PHPSESSID';
			if(document.cookie.length>0){
			   c_start=document.cookie.indexOf(c_name + "=")
			   if(c_start!=-1){ 
				 c_start=c_start + c_name.length+1 
				 c_end=document.cookie.indexOf(";",c_start)
				 if(c_end==-1) c_end=document.cookie.length
				 return unescape(document.cookie.substring(c_start,c_end));
			   }
			}
		}
	</script>

### 3.运行效果
![](http://www.884358.com/wp-content/uploads/2019/09/2019092517162582.png)