<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="chrome=1">
  <meta name="viewport" content="initial-scale=1.0, user-scalable=no, width=device-width">
  <title>首页</title>
  <link rel="stylesheet" href="/resources/assets/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="/resources/assets/css/ee3.css">
  <link rel="stylesheet" href="/resources/assets/layui/css/layui.css">
</head>
<body>
  <nav id="course-nav" class="navbar navbar-default navbar-fixed-top" role="navigation" style="display:block;">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#">Edu Venture</a>
      </div>
      <div id="navbar" class="navbar-collapse collapse">
        <ul class="nav navbar-nav">
          <li id="view-course-li"><a id="view-course" href="/course/listing" target="_blank">查看已创建课程</a></li>
          <li id="create-course-li"><a id="create-course" href="javascript:;">创建新课程</a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?=empty($_SESSION['home_login_user']['username']) ? '未知用户' : $_SESSION['home_login_user']['username']?><span class="caret"></span></a>
            <ul class="dropdown-menu" role="menu">
              <li><a id="logout" href="/user/logout">退出</a></li>
            </ul>
          </li>
        </ul>
      </div><!--/.nav-collapse -->
    </div>
  </nav>
  <div id="amap" tabindex="0"></div>
  <div id="search"><input type="text" id="keyword" name="keyword" placeholder="请输入关键字：(选定后搜索)" /></div>
  <script type="text/javascript" src="http://webapi.amap.com/maps?v=1.3&key=aeff1d291396155533879f8f6f0a4265"></script>
  <script type="text/javascript" src="http://webapi.amap.com/demos/js/liteToolbar.js"></script>
  <script src="/resources/assets/js/jquery-1.11.1.min.js"></script>
  <script src="/resources/assets/bootstrap/js/bootstrap.min.js"></script>
  <script src="/resources/assets/layui/layui.js"></script>
  <script src="/resources/assets/js/index.js"></script>
  <!-- 矩形区域选择确认按钮 -->
  <div id="rectangle-control">
    <button id="rectangle-ok" class="layui-btn">确认选择</button>
    <button id="re-drag" class="layui-btn layui-btn-primary">重新拖选</button>
  </div>
  <!-- create course -->
  <div class="layui-form" id="create-course-form">
    <form id="create-form">
      <div class="layui-form-item">
        <label class="layui-form-label">课程题目</label>
        <div class="layui-input-block">
          <input type="text" name="name" placeholder="请输入课程题目" autocomplete="off" class="layui-input">
        </div>
      </div>
      <div class="layui-form-item layui-form-text">
        <label class="layui-form-label">课程描述</label>
        <div class="layui-input-block">
          <textarea name="desc" placeholder="请输入课程描述内容" class="layui-textarea"></textarea>
        </div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label">是否共享</label>
        <div class="layui-input-block">
          <select name="share">
            <option value="0" selected="selected">限本校使用</option>
            <option value="1">共享给所有学校</option>
          </select>
        </div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label">封面图</label>
        <div class="layui-input-block">
          <input type="file" name="cover-image" lay-type="images" class="layui-upload-file">
          <input type="hidden" name="image">
        </div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label">区域选择</label>
        <div class="layui-input-block">
          <div id="area-lt-lnglat"></div>
          <div id="area-rb-lnglat"></div>
          <div><button type="button" id="area-rectangle" class="layui-btn layui-btn-normal">选择区域</button></div>
          <input type="hidden" name="lt-lng">
          <input type="hidden" name="lt-lat">
          <input type="hidden" name="rb-lng">
          <input type="hidden" name="rb-lat">
        </div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label">年级</label>
        <div class="layui-input-block">
          <select name="grade">
            <option value="1">一年级</option>
            <option value="2">二年级</option>
          </select>
        </div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label">学科</label>
        <div class="layui-input-block">
          <select name="subject">
            <?php if(! empty($subjects)):foreach($subjects as $subject):?>
            <option value="<?=$subject['id']?>"><?=$subject['name']?></option>
            <?php endforeach;endif;?>
          </select>
        </div>
      </div>
      <div class="layui-form-item">
        <div class="layui-input-block">
          <button type="reset" class="layui-btn layui-btn-primary">取消</button>
          <button type="button" id="submit-create-course" class="layui-btn">创建课程</button>
        </div>
      </div>
    </form>
  </div>
</body>
</html>