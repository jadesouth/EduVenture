<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="chrome=1">
  <meta name="viewport" content="initial-scale=1.0, user-scalable=no, width=device-width">
  <title>课程列表</title>
  <link rel="stylesheet" href="/resources/assets/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="/resources/assets/css/ee3.css">
  <link rel="stylesheet" href="/resources/assets/layui/css/layui.css">
</head>
<body>
<!-- view course -->
<div id="view-course-swap">
  <table class="table table-hover">
    <thead><tr>
      <th class="text-center">课程ID</th><th>课程名称</th><th>年级</th><th>学科</th><th>建立时间</th><th>操作</th>
    </tr></thead>
    <tbody>
    <?php if(! empty($course_list)):foreach($course_list as $course):?>
    <tr>
      <td class="text-center"><?=$course['id']?></td>
      <td><?=$course['name']?></td>
      <td><?=empty($grades[$course['grade_id']]) ? '未指定' : $grades[$course['grade_id']]?></td>
      <td><?=empty($subjects[$course['subject']]) ? '未指定' : $subjects[$course['subject']]?></td>
      <td><?=$course['date_created']?></td>
      <td>
        <button class="layui-btn layui-btn-mini layui-btn-danger but-course-delete" data-course="<?=$course['id']?>"><i class="layui-icon">&#xe640;</i>&nbsp;删除</button>
        <button class="layui-btn layui-btn-mini layui-btn-normal edit-course-button" data-course="<?=$course['id']?>"><i class="layui-icon">&#xe642;</i>&nbsp;编辑</button>
        <button class="layui-btn layui-btn-mini view-task-button" data-course="<?=$course['id']?>" data-title="<?=$course['name']?>"><i class="layui-icon">&#xe60a;</i>&nbsp;查看任务</button>
        <?php if(0 == $course['is_ready']):?>
        <button class="layui-btn layui-btn-mini layui-btn-warm but-course-release" data-course="<?=$course['id']?>"><i class="layui-icon">&#xe609;</i>&nbsp;发布课程</button>
        <?php else:?>
        <button class="layui-btn layui-btn-mini layui-btn-disabled"><i class="layui-icon">&#xe609;</i>&nbsp;已经发布</button>
        <?php endif;?>
      </td>
    </tr>
    <?php endforeach;else:?>
    <tr><td colspan="6" class="text-center" style="padding:30px 0;font-size:16px;">暂无课程数据</td></tr>
    <?php endif;?>
    </tbody>
  </table>
  <?=empty($page) ? '' : $page?>
</div>
<!-- map -->
<div id="amap" tabindex="0" style="display:none;"></div>
<div id="search" style="display:none;"><input type="text" id="keyword" name="keyword" placeholder="请输入关键字：(选定后搜索)" /></div>
<script type="text/javascript" src="http://webapi.amap.com/maps?v=1.3&key=aeff1d291396155533879f8f6f0a4265"></script>
<script type="text/javascript" src="http://webapi.amap.com/demos/js/liteToolbar.js"></script>
<script src="/resources/assets/js/jquery-1.11.1.min.js"></script>
<script src="/resources/assets/bootstrap/js/bootstrap.min.js"></script>
<script src="/resources/assets/layui/layui.js"></script>
<script src="/resources/assets/js/course_list.js"></script>
<script src="/resources/assets/js/question.js"></script>
<!-- edit course -->
<div class="layui-form" id="edit-course-swap" style="display:none;">
  <form id="modify-course-form">
    <div class="layui-form-item">
      <label class="layui-form-label">课程题目</label>
      <div class="layui-input-block">
        <input type="text" name="name" placeholder="请输入课程题目" autocomplete="off" class="layui-input">
        <input type="hidden" name="course">
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
          <option value="0">限本校使用</option>
          <option value="1">共享给所有学校</option>
        </select>
      </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">封面图</label>
      <div class="layui-input-block">
        <input type="file" name="image" class="layui-upload-file course-image">
        <input type="hidden" name="course-image" value="">
      </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">区域选择</label>
      <div class="layui-input-block">
        <div id="area-lt-lnglat">左上经纬(0, 0)</div>
        <div id="area-rb-lnglat">右下经纬(0, 0)</div>
        <div><button type="button" id="area-rectangle" class="layui-btn layui-btn-normal">选择区域</button></div>
        <input value="" type="hidden" name="lt-lng">
        <input value="" type="hidden" name="lt-lat">
        <input value="" type="hidden" name="rb-lng">
        <input value="" type="hidden" name="rb-lat">
      </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">年级</label>
      <div class="layui-input-block">
        <select name="grade">
          <?php if(! empty($grades)):foreach($grades as $grade_k => $grade_v):?>
            <option value="<?=$grade_k?>"><?=$grade_v?></option>
          <?php endforeach;endif;?>
        </select>
      </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">学校</label>
      <div class="layui-input-block">
          <select name="school">
              <?php if(! empty($schools)):foreach($schools as $school_k => $school_v):?>
                  <option value="<?=$school_k?>"><?=$school_v?></option>
              <?php endforeach;endif;?>
          </select>
      </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label">学科</label>
      <div class="layui-input-block">
        <select name="subject">
          <?php if(! empty($subjects)):foreach($subjects as $subject_id => $subject_value):?>
            <option value="<?=$subject_id?>"><?=$subject_value?></option>
          <?php endforeach;endif;?>
        </select>
      </div>
    </div>
    <div class="layui-form-item">
      <div class="layui-input-block">
        <button type="reset" id="cancel-modify-course" class="layui-btn layui-btn-primary">取消</button>
        <button type="button" id="submit-modify-course" class="layui-btn" lay-submit lay-filter="*">保存</button>
      </div>
    </div>
    <!-- 更多表单结构排版请移步文档左侧【页面元素-表单】一项阅览 -->
  </form>
</div>
<!-- 编辑课程矩形区域选择确认按钮 -->
<div id="rectangle-control">
  <button id="rectangle-ok" class="layui-btn">确认选择</button>
  <button id="re-drag" class="layui-btn layui-btn-primary">重新拖选</button>
</div>
<!-- task list -->
<div id="task-list-swap">
  <div id="add-task-button-div">
    <button id="return-course-button" class="layui-btn layui-btn layui-btn-primary">返回课程</button>
    <button class="layui-btn" id="add-task-button">添加任务</button>
  </div>
  <table class="table table-hover">
    <thead><tr>
      <th class="text-center">任务序号</th><th>课程名称</th><th>任务标题</th><th>建立时间</th><th>操作</th>
    </tr></thead>
    <tbody id="task-tbody"></tbody>
  </table>
</div>
<!-- add task -->
<div id="add-task-swap">
  <form class="layui-form" id="task-add-form">
    <!-- add task -->
    <div id="add-task-form">
      <div class="layui-form-item">
        <label class="layui-form-label">所属课程</label>
        <div class="layui-input-block">
          <div class="layui-input" style="border:none;" id="course-title">课程标题</div>
          <input type="hidden" name="task-course">
        </div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label">任务图</label>
        <div class="layui-input-block">
            <input type="file" name="image" lay-type="images" class="layui-upload-file task-image">
            <input type="hidden" name="task-image">
        </div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label">任务热点</label>
        <div class="layui-input-block">
          <div id="task-area-lnglat">任务区域中心点经纬(未选择, 未选择)</div>
          <div><button type="button" id="task-area-rectangle" class="layui-btn layui-btn-normal">选择任务区域</button></div>
          <input type="hidden" name="task-center-lng">
          <input type="hidden" name="task-center-lat">
        </div>
      </div>
      <div class="layui-form-item">
        <label class="layui-form-label">任务标题</label>
        <div class="layui-input-block">
          <input type="text" name="name" placeholder="请输入任务标题" autocomplete="off" class="layui-input">
        </div>
      </div>
      <div class="layui-form-item" style="text-align:center;">
        <div class="layui-input-block">
          <button type="button" id="add-task-submit-button" class="layui-btn">完成</button>
          <button type="button" id="task-cancel-button" class="layui-btn layui-btn-primary">取消</button>
        </div>
      </div>
    </div>
  </form>
</div>
<!-- edit task -->
<div id="edit-task-swap">
    <form class="layui-form" id="task-edit-form">
        <!-- add task -->
        <div id="add-task-form">
            <div class="layui-form-item">
                <label class="layui-form-label">所属课程</label>
                <div class="layui-input-block">
                    <div class="layui-input" style="border:none;" id="edit-course-title"></div>
                    <input type="hidden" name="edit-course-task">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">任务图</label>
                <div class="layui-input-block">
                    <input type="file" name="image" lay-type="images" class="layui-upload-file edit-task-image">
                    <input type="hidden" name="edit-task-image">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">任务热点</label>
                <div class="layui-input-block">
                    <div id="edit-task-area-lnglat">任务区域中心点经纬(未选择, 未选择)</div>
                    <div><button type="button" id="edit-task-area-rectangle" class="layui-btn layui-btn-normal">选择任务区域</button></div>
                    <input type="hidden" name="task-center-lng">
                    <input type="hidden" name="task-center-lat">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">任务标题</label>
                <div class="layui-input-block">
                    <input type="text" name="edit-task-name" placeholder="请输入任务标题" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item" style="text-align:center;">
                <div class="layui-input-block">
                    <button type="button" id="edit-task-submit-button" class="layui-btn">完成</button>
                    <button type="button" id="edit-task-cancel-button" class="layui-btn layui-btn-primary">取消</button>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- 任务区域选择确认按钮 -->
<div id="task-rectangle-control">
  <button id="task-rectangle-ok" class="layui-btn">确认选择</button>
  <button id="task-re-drag" class="layui-btn layui-btn-primary">重新拖选</button>
</div>
<!-- 编辑任务区域选择确认按钮 -->
<div id="edit-task-rectangle-control">
    <button id="edit-task-rectangle-ok" class="layui-btn">确认选择</button>
    <button id="edit-task-re-drag" class="layui-btn layui-btn-primary">重新拖选</button>
</div>
<!-- 题目列表 -->
<div id="topic-list-swap">
  <div id="topic-type-select-swap">
    <button id="return-task-button" class="layui-btn layui-btn layui-btn-primary">返回任务</button>
    <button id="add-single-button" class="layui-btn layui-btn-big">添加单选题</button>
    <button id="add-multi-button" class="layui-btn layui-btn-big">添加多选题</button>
    <button id="add-photo-button" class="layui-btn layui-btn-big">添加拍照题</button>
    <button id="add-qa-button" class="layui-btn layui-btn-big">添加问答题</button>
  </div>
  <div id="topic-list">
    <table class="table table-hover">
      <thead><tr>
        <th class="text-center">题目序号</th><th>所属任务名称</th><th>题目类型</th><th>建立时间</th><th>操作</th>
      </tr></thead>
      <tbody id="question-tbody"><tr>
          <td class="text-center">1</td>
          <td>Column content</td>
          <td>Column content</td>
          <td>Column content</td>
          <td>
              <button class="layui-btn layui-btn-mini layui-btn-danger"><i class="layui-icon">&#xe640;</i>&nbsp;删除</button>
              <button class="layui-btn layui-btn-mini layui-btn-normal edit-task-button"><i class="layui-icon">&#xe642;</i>&nbsp;编辑</button>
          </td>
      </tr></tbody>
    </table>
  </div>
</div>
<!-- 单选 -->
<div id="single-swap">
  <form class="layui-form" id="single-form">
    <div class="layui-form-item">
      <label class="layui-form-label">问题</label>
      <div class="layui-input-block">
        <input type="text" name="name" placeholder="请输入问题内容" autocomplete="off" class="layui-input">
        <input type="hidden" name="question-task">
      </div>
    </div>
    <div id="add-single-swap">
      <div class="layui-form-item">
        <div class="layui-inline" style="margin-right:0">
          <label class="layui-form-label">选项1</label>
          <div class="layui-input-inline" style="width:auto;">
            <input type="radio" value="1" name="right" title="选为正答">
          </div>
          <div class="layui-input-inline" style="width:220px;">
            <input type="text" name="answer[]" placeholder="输入选项答案" autocomplete="off" class="layui-input">
          </div>
        </div>
      </div>
      <div class="layui-form-item">
        <div class="layui-inline">
          <label class="layui-form-label">选项2</label>
          <div class="layui-input-inline" style="width:auto;">
            <input type="radio" value="2" name="right" title="选为正答">
          </div>
          <div class="layui-input-inline" style="width:220px;">
            <input type="text" name="answer[]" placeholder="输入选项答案" autocomplete="off" class="layui-input">
          </div>
        </div>
      </div>
    </div>
    <div class="layui-form-item">
      <div class="layui-input-block">
        <button id="add-single-item-button" type="button" class="layui-btn layui-btn-normal">加一项</button>
      </div>
    </div>
    <div class="layui-form-item" style="text-align:center;">
      <div class="layui-input-block">
        <button type="button" class="layui-btn" id="add-single-data">完成</button>
        <button type="button" class="layui-btn layui-btn-primary" id="cancel-single">取消</button>
      </div>
    </div>
  </form>
</div>
<!-- 多选 -->
<div id="multi-swap">
  <form class="layui-form" id="multi-form">
    <div class="layui-form-item">
      <label class="layui-form-label">问题</label>
      <div class="layui-input-block">
        <input type="text" name="name" placeholder="请输入问题内容" autocomplete="off" class="layui-input">
        <input type="hidden" name="question-task">
      </div>
    </div>
    <div id="add-multi-swap">
      <div class="layui-form-item">
        <div class="layui-inline" style="margin-right:0">
          <label class="layui-form-label">选项1</label>
          <div class="layui-input-inline" style="width:auto;">
            <input type="checkbox" name="right[]" value="1" title="选为正答">
          </div>
          <div class="layui-input-inline" style="width:220px;">
            <input type="text" name="answer[]" placeholder="输入选项答案" autocomplete="off" class="layui-input">
          </div>
        </div>
      </div>
      <div class="layui-form-item">
        <div class="layui-inline">
          <label class="layui-form-label">选项2</label>
          <div class="layui-input-inline" style="width:auto;">
            <input type="checkbox" name="right[]" value="2" title="选为正答">
          </div>
          <div class="layui-input-inline" style="width:220px;">
            <input type="text" name="answer[]" placeholder="输入选项答案" autocomplete="off" class="layui-input">
          </div>
        </div>
      </div>
    </div>
    <div class="layui-form-item">
      <div class="layui-input-block">
        <button id="add-multi-item-button" type="button" class="layui-btn layui-btn-normal">加一项</button>
      </div>
    </div>
    <div class="layui-form-item" style="text-align:center;">
      <div class="layui-input-block">
        <button type="button" class="layui-btn" id="add-multi-data">完成</button>
        <button type="button" class="layui-btn layui-btn-primary" id="cancel-multi">取消</button>
      </div>
    </div>
  </form>
</div>
<!-- 拍照 -->
<div id="photo-swap">
  <form class="layui-form" id="photo-form">
    <div class="layui-form-item">
      <label class="layui-form-label" style="width:120px;">问题</label>
      <div class="layui-input-block" style="margin-left:130px;">
        <input type="text" name="name" placeholder="请输入问题内容" autocomplete="off" class="layui-input">
        <input type="hidden" name="question-task">
      </div>
    </div>
    <div class="layui-form-item">
      <label class="layui-form-label" style="width:120px;">上传图片数量</label>
      <div class="layui-input-block" style="margin-left:130px;">
        <select name="num">
          <option value="1">上传1张</option>
          <option value="2">上传2张</option>
          <option value="3" selected>上传3张</option>
          <option value="4">上传4张</option>
          <option value="5">上传5张</option>
          <option value="6">上传6张</option>
        </select>
      </div>
    </div>
    <div class="layui-form-item" style="text-align:center;">
      <div class="layui-input-block">
        <button type="button" class="layui-btn" id="add-photo-data">完成</button>
        <button type="button" class="layui-btn layui-btn-primary" id="cancel-photo">取消</button>
      </div>
    </div>
  </form>
</div>
<!-- 问答 -->
<div id="qa-swap">
  <form class="layui-form" id="qa-form">
    <div class="layui-form-item">
      <label class="layui-form-label">问题</label>
      <div class="layui-input-block">
        <textarea name="qa" required lay-verify="required" placeholder="请输入问题内容" class="layui-textarea"></textarea>
        <input type="hidden" name="question-task">
      </div>
    </div>
    <div class="layui-form-item" style="text-align:center;">
      <div class="layui-input-block">
        <button type="button" class="layui-btn" id="add-qa-data">完成</button>
        <button type="button" class="layui-btn layui-btn-primary" id="cancel-qa">取消</button>
      </div>
    </div>
  </form>
</div>
<script>
$(function() {
    // 删除课程
    $(".but-course-delete").click(function() {
        var course = $(this).attr('data-course');
        if(undefined == course || '' == course || false == course) {
            layer.open({
                icon: 2,
                content: "删除课程操作非法"
            });
            return false;
        }
        $.ajax({
            type: "POST",
            url: "/course/delete",
            data: {course: course},
            dataType: "JSON",
            success: function(response){
                if(0 == response.status) {
                    layer.msg(response.msg, {
                        icon: 6,
                        time: 1000
                    }, function(){
                        window.location.reload();
                    });
                } else {
                    layer.open({
                        icon: 2,
                        content: response.msg
                    });
                }
            }
        });
    });
    // 发布课程
    $(".but-course-release").click(function() {
        var course = $(this).attr('data-course');
        if(undefined == course || '' == course || false == course) {
            layer.open({
                icon: 2,
                content: "发布课程操作非法"
            });
            return false;
        }
        $.ajax({
            type: "POST",
            url: "/course/release",
            data: {course: course},
            dataType: "JSON",
            success: function(response){
                if(0 == response.status) {
                    layer.msg(response.msg, {
                        icon: 6,
                        time: 1000
                    }, function(){
                        window.location.reload();
                    });
                } else {
                    layer.open({
                        icon: 2,
                        content: response.msg
                    });
                }
            }
        });
    });
});
</script>
</body>
</html>