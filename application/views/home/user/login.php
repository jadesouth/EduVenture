<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>EduVenture Login Page</title>
  <!-- CSS -->
  <link rel="stylesheet" href="/resources/assets/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="/resources/assets/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="/resources/assets/css/form-elements.css">
  <link rel="stylesheet" href="/resources/assets/layui/css/layui.css">
  <link rel="stylesheet" href="/resources/assets/css/style.css">
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="/resources/assets/js/html5shiv.js"></script>
  <script src="/resources/assets/js/respond.min.js"></script>
  <![endif]-->
  <!-- Favicon and touch icons -->
  <link rel="shortcut icon" href="/resources/assets/ico/favicon.png">
  <link rel="apple-touch-icon-precomposed" sizes="144x144" href="/resources/assets/ico/apple-touch-icon-144-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="114x114" href="/resources/assets/ico/apple-touch-icon-114-precomposed.png">
  <link rel="apple-touch-icon-precomposed" sizes="72x72" href="/resources/assets/ico/apple-touch-icon-72-precomposed.png">
  <link rel="apple-touch-icon-precomposed" href="/resources/assets/ico/apple-touch-icon-57-precomposed.png">
</head>
<body>
<!-- Top content -->
<div class="top-content">
  <div class="inner-bg">
    <div class="container">
      <div class="row">
        <div class="col-sm-8 col-sm-offset-2 text">
          <h1><strong>EduVenture</strong> Login Form</h1>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-6 col-sm-offset-3 form-box">
          <div class="form-top">
            <div class="form-top-left">
              <h3 id="login-title">Login to our site</h3>
              <p>Enter your username and password to log on:</p>
            </div>
            <div class="form-top-right"><i class="fa fa-lock"></i></div>
          </div>
          <div class="form-bottom">
            <form role="form" id="login-form" class="login-form">
              <div class="form-group">
                <label class="sr-only" for="username">Username</label>
                <input type="text" name="username" placeholder="Username..." class="form-username form-control" id="form-username">
              </div>
              <div class="form-group">
                <label class="sr-only" for="password">Password</label>
                <input type="password" name="password" placeholder="Password..." class="form-password form-control" id="form-password">
              </div>
              <button id="login-in" type="button" class="btn">Sign in!</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Javascript -->
<script src="/resources/assets/js/jquery-1.11.1.min.js"></script>
<script src="/resources/assets/bootstrap/js/bootstrap.min.js"></script>
<script src="/resources/assets/js/jquery.backstretch.min.js"></script>
<script src="/resources/assets/js/scripts.js"></script>
<script src="/resources/assets/layui/layui.js"></script>
<!--[if lt IE 10]>
<script src="/resources/assets/js/placeholder.js"></script>
<![endif]-->
<script type="application/javascript">
  $(function() {
      // 加载 layui
      layui.use('layer', function(){
          var layer = layui.layer;
      });

      $("#login-in").click(function() {
          // 对用户输入进行验证
          var username = $('#form-username').val();
          if(undefined == username || '' == username || false == username) {
              layer.tips('请输入登录账号', '#form-username', {
                  tips: [2, '#F24100']
              });
              return false;
          }
          var $password = $('#form-password').val();
          if(undefined == $password || '' == $password || false == $password) {
              layer.tips('请输入登录密码', '#form-password', {
                  tips: [2, '#F24100']
              });
              return false;
          }

          $.ajax({
              type: "POST",
              url: "/user/login",
              data: $("#login-form").serialize(),
              dataType: "JSON",
              success: function(response){
                  if(0 == response.status) {
                      window.location.href = "<?=base_url('course')?>";
                  } else if(1 == response.status) {
                      layer.tips(response.msg, '#submit', {
                          tips: [1, '#F24100']
                      });
                  } else if(2 == response.status) {
                      layer.tips(response.msg, '#form-username', {
                          tips: [2, '#F24100']
                      });
                  } else if(3 == response.status) {
                      layer.tips(response.msg, '#form-password', {
                          tips: [2, '#F24100']
                      });
                  }
              }
          });
      });
  });
</script>
</body>
</html>