/**
 * Created by wangnan on 16-10-27.
 */
var editCourseIndex, addTaskIndex, editTaskIndex;
var viewCourseSwap, editCourseSwap, taskListSwap, addTaskSwap, editTaskSwap;
var editTaskRectangleLTLng, editTaskRectangleLTLat, editTaskRectangleRBLng, editTaskRectangleRBLat;
var amap,search;
var _onMouseDown, _onMouseUp;
var mouseTool;
var viewTaskButton, addTaskButton, taskCancelButton, addTaskSubmitButton;
var courseID = 0;
var courseName = '';
var map = new AMap.Map('amap', {
    resizeEnable: true,
    zoom: 11,
    center: [116.39, 39.9],
    keyboardEnable: false
});
AMap.plugin(['AMap.Autocomplete', 'AMap.PlaceSearch', 'AMap.ToolBar', 'AMap.Scale', 'AMap.MouseTool'], function () {
    var autoOptions = {
        city: "北京", //城市，默认全国
        input: "keyword"//使用联想输入的input的id
    };
    autocomplete = new AMap.Autocomplete(autoOptions);
    var placeSearch = new AMap.PlaceSearch({
        city: '北京',
        map: map
    });
    AMap.event.addListener(autocomplete, "select", function (e) {
        placeSearch.search(e.poi.name)
    });
    map.addControl(new AMap.ToolBar({"position": "RB", "liteStyle": true}));
    // 鼠标工具
    mouseTool = new AMap.MouseTool(map);
});
// 绘制矩形
function iRectangle() {
    mouseTool.close(true);
    mouseTool.rectangle();
}

$(function() {
    // 获取节点
    viewCourseSwap = $("#view-course-swap");
    editCourseSwap = $("#edit-course-swap");
    taskListSwap = $("#task-list-swap");
    addTaskSwap = $("#add-task-swap");
    editTaskSwap = $("#edit-task-swap");
    amap = $("#amap");
    search = $("#search");
    viewTaskButton = $(".view-task-button");
    addTaskButton = $("#add-task-button");
    taskCancelButton = $("#task-cancel-button");
    addTaskSubmitButton = $("#add-task-submit-button");
    // layui
    var layer, form, upload;
    layui.use(['layer', 'form', 'upload'], function () {
        layer = layui.layer;
        form = layui.form();
        upload = layui.upload();
        // 上传课程封面图
        layui.upload({
            elem: $('.course-image')
            ,url: '/upload/courseCoverImage'
            ,ext: 'jpg|png|gif|jpeg'
            ,success: function(response){
                if(0 == response.status) {
                    layer.msg(response.msg, {
                        icon: 6,
                        time: 1000
                    });
                    // 赋值
                    $("input[name='course-image']").val(response.data.cover);
                } else {
                    layer.open({
                        icon: 2,
                        content: response.msg
                    });
                }
            }
        });
        // 上传任务封面图
        layui.upload({
            elem: $('.task-image')
            ,url: '/upload/taskCoverImage'
            ,ext: 'jpg|png|gif|jpeg'
            ,success: function(response){
                if(0 == response.status) {
                    layer.msg(response.msg, {
                        icon: 6,
                        time: 1000
                    });
                    // 赋值
                    $("input[name='task-image']").val(response.data.cover);
                } else {
                    layer.open({
                        icon: 2,
                        content: response.msg
                    });
                }
            }
        });
        // 编辑任务上传封面图
        layui.upload({
            elem: $('.edit-task-image')
            ,url: '/upload/taskCoverImage'
            ,ext: 'jpg|png|gif|jpeg'
            ,success: function(response){
                if(0 == response.status) {
                    layer.msg(response.msg, {
                        icon: 6,
                        time: 1000
                    });
                    // 赋值
                    $("input[name='edit-task-image']").val(response.data.cover);
                } else {
                    layer.open({
                        icon: 2,
                        content: response.msg
                    });
                }
            }
        });
    });
    // 点击编辑课程按钮
    $(".edit-course-button").click(function() {
        // 获取编的课程数据
        var course = $(this).attr('data-course');
        if(undefined == course || '' == course || false == course) {
            layer.open({
                icon: 2,
                content: "非法编辑课程操作"
            });
            return false;
        }
        $.ajax({
            type: "GET",
            url: "/course/modify",
            data: {course: course},
            dataType: "JSON",
            success: function(response){
                if(0 == response.status) {
                    var data = response.data;
                    // 给表单赋值
                    $("input[name='course']").val(data.id);
                    $("input[name='name']").val(data.name);
                    $("select[name='school']").val(data.school_id);
                    $("textarea[name='desc']").val(data.description);
                    $("select[name='share']").val(data.is_share);
                    $("input[name='course-image']").val(data.tn_file_id);
                    var lt_lnglat = "左上经纬(" + data.ul_lon + ", " + data.ul_lat + ")";
                    $("#area-lt-lnglat").html(lt_lnglat);
                    var rb_lnglat = "右下经纬(" + data.br_lon + ", " + data.br_lat + ")";
                    $("#area-rb-lnglat").html(rb_lnglat);
                    $("input[name='lt-lng']").val(data.ul_lon);
                    $("input[name='lt-lat']").val(data.ul_lat);
                    $("input[name='rb-lng']").val(data.br_lon);
                    $("input[name='rb-lat']").val(data.br_lat);
                    $("select[name='grade']").val(data.grade_id);
                    $("select[name='subject']").val(data.subject);
                    form.render();

                    viewCourseSwap.hide();
                    amap.show();
                    search.show();
                    editCourseIndex = layer.open({
                        type: 1
                        , title: ['编辑课程', 'font-weight:bold;']
                        , offset: ['10px', '10px']
                        , area: ['500px', 'auto']
                        , maxmin: true
                        , shade: 0
                        , moveType: 1
                        , content: editCourseSwap
                        , cancel: function () {
                            map.off('mousedown', _onMouseDown);
                            map.off('mouseup', _onMouseUp);
                            $('#modify-course-form')[0].reset();
                            viewCourseSwap.show();
                            amap.hide();
                            search.hide();
                            layer.close(editCourseIndex);
                            $('#area-lt-lnglat').html('');
                            $('#area-rb-lnglat').html('');
                            $("#rectangle-control").hide();
                        }
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
    // 取消课程编辑
    $("#cancel-modify-course").click(function () {
        viewCourseSwap.show();
        amap.hide();
        search.hide();
        layer.close(editCourseIndex);
        $('#area-lt-lnglat').html('');
        $('#area-rb-lnglat').html('');
        $("#rectangle-control").hide();
    });
    // 点击课程选择选择区域
    $('#area-rectangle').click(function () {
        mouseTool.close(true);
        layer.min(editCourseIndex);
        layer.msg('鼠标在地图上拖动即可选择矩形区域', {icon: 6});
        mouseTool.rectangle();
        _onMouseDown = function (e) {
            rectangleLTLng = e.lnglat.getLng();
            rectangleLTLat = e.lnglat.getLat();
        };
        map.on('mousedown', _onMouseDown);
        _onMouseUp = function (e) {
            rectangleRBLng = e.lnglat.getLng();
            rectangleRBLat = e.lnglat.getLat();
            $("#rectangle-control").show();
            mouseTool.close(false);
        };
        map.on('mouseup', _onMouseUp);
    });
    // 课程区域重选
    $('#re-drag').click(function () {
        iRectangle();
        $("#rectangle-control").hide();
    });
    // 课程区域选择OK
    $('#rectangle-ok').click(function () {
        mouseTool.close(true);
        map.off('mousedown', _onMouseDown);
        map.off('mouseup', _onMouseUp);
        $("#rectangle-control").hide();
        $('#area-rectangle').html('重选区域');
        var ltLngLat = '左上经纬(' + rectangleLTLng + ',' + rectangleLTLat + ')';
        var rbLngLat = '右下经纬(' + rectangleRBLng + ',' + rectangleRBLat + ')';
        $('#area-lt-lnglat').html(ltLngLat);
        $('#area-rb-lnglat').html(rbLngLat);
        layer.restore(editCourseIndex);
        $("input[name='lt-lng']").val(rectangleLTLng);
        $("input[name='lt-lat']").val(rectangleLTLat);
        $("input[name='rb-lng']").val(rectangleRBLng);
        $("input[name='rb-lat']").val(rectangleRBLat);
    });
    // 点击查看任务按钮
    viewTaskButton.click(function() {
        // 获取任务数据
        var course = $(this).attr('data-course');
        if(undefined == course || '' == course || false == course) {
            layer.open({
                icon: 2,
                content: "查看任务操作非法"
            });
            return false;
        }
        courseID = course;
        courseName = $(this).attr('data-title');
        $("input[name='task-course']").val(course);
        $.ajax({
            type: "GET",
            url: "/task/listing",
            data: {course: course},
            dataType: "JSON",
            success: function(response){
                if(0 == response.status) {
                    var task = response.data.task_list;
                    var task_tbody = $("#task-tbody");
                    task_tbody.empty();
                    if(undefined == task) {
                        task_tbody.prepend('<tr><td colspan="5" class="text-center" style="padding:20px 0;">暂无任务数据</td></tr>');
                    } else {
                        for(var i in task) {
                            var tr = "";
                            tr += '<tr><td class="text-center">' + task[i].id +'</td>';
                            tr += '<td>' + task[i].course_name + '</td>';
                            tr += '<td>' + task[i].task_name + '</td>';
                            tr += '<td>' + task[i].date_create + '</td>';
                            tr += '<td><button class="layui-btn layui-btn-mini layui-btn-danger but-task-del" data-task="';
                            tr += task[i].id + '"><i class="layui-icon">&#xe640;</i>&nbsp;删除</button>';
                            tr += '<button class="layui-btn layui-btn-mini layui-btn-normal but-task-edit" data-task="';
                            tr += task[i].id + '"><i class="layui-icon">&#xe642;</i>&nbsp;编辑</button>';
                            tr += '<button class="layui-btn layui-btn-mini but-question-add" data-task="';
                            tr += task[i].id + '" data-task-name="' + task[i].task_name + '"><i class="layui-icon">&#xe60a;</i>&nbsp;添加题目</button></td></tr>';
                            task_tbody.prepend(tr);
                        }
                    }
                } else {
                    layer.open({
                        icon: 2,
                        content: response.msg
                    });
                }
            }
        });
        viewCourseSwap.hide();
        taskListSwap.show();
    });
    $("#return-course-button").click(function() {
        courseID = 0;
        viewCourseSwap.show();
        taskListSwap.hide();
        $("#task-tbody").empty();
    });
    // 点击添加任务按钮
    var taskFunction = function() {
        $("#course-title").html(courseName);
        $("input[name='task-center-lng']").val('');
        $("input[name='task-center-lat']").val('');
        $.ajax({
            type: "GET",
            url: "/task/create",
            data: {course: courseID},
            dataType: "JSON",
            success: function(response){
                if(0 == response.status) {
                    var latlng = response.data;
                    if(null != latlng && undefined != latlng && '' != latlng) {
                        var courseSWLng, courseSWLat, courseNELng, courseNELat;
                        courseSWLng = latlng.ul_lon;
                        courseSWLat = latlng.br_lat;
                        courseNELng = latlng.br_lon;
                        courseNELat = latlng.ul_lat;
                        var Bounds = new AMap.Bounds(new AMap.LngLat(courseSWLng, courseSWLat), new AMap.LngLat(courseNELng, courseNELat));
                        map.setBounds(Bounds);
                        map.setLimitBounds(Bounds);
                        //map.setStatus({dragEnable: false, doubleClickZoom: false});
                        map.setZoom(15);
                        amap.show();
                        addTaskIndex = layer.open({
                            type: 1
                            , title: ['添加任务', 'font-weight:bold;']
                            , offset: ['10px', '10px']
                            , area: ['500px', 'auto']
                            , maxmin: true
                            , shade: 0
                            , moveType: 1
                            , content: addTaskSwap
                            , cancel: cancelCreateTaskButton
                        });
                    } else {
                        layer.open({
                            icon: 2,
                            content: '获取课程区域失败!'
                        });
                    }
                } else {
                    layer.open({
                        icon: 2,
                        content: response.msg
                    });
                }
            }
        });
    };
    addTaskButton.click(taskFunction);
    // 点击添加任务取消按钮
    var cancelCreateTaskButton = function() {
        amap.hide();
        map.off('mousedown', _onMouseDown);
        map.off('mouseup', _onMouseUp);
        mouseTool.close(true);
        layer.close(addTaskIndex);
        $('#task-add-form')[0].reset();
        $('#task-area-lnglat').html('任务区域中心点经纬(暂无, 暂无)');
        $("input[name='task-course']").val(0);
        $("#course-title").html('');
        $("#task-rectangle-control").hide();
    };
    taskCancelButton.click(cancelCreateTaskButton);
    // 点击添加任务按钮数据
    addTaskSubmitButton.click(function() {
        $("input[name='task-course']").val(courseID);
        $.ajax({
            type: 'POST'
            ,url: '/task/create'
            ,data: $('#task-add-form').serialize()
            ,dataType: 'JSON'
            ,success: function (response) {
                if(0 == response.status) {
                    layer.msg(response.msg, {
                        icon: 6,
                        time: 1000
                    }, function(){
                        $.ajax({
                            type: "GET",
                            url: "/task/listing",
                            data: {course: courseID},
                            dataType: "JSON",
                            success: function(response){
                                if(0 == response.status) {
                                    var task = response.data.task_list;
                                    var task_tbody = $("#task-tbody");
                                    task_tbody.empty();
                                    if(undefined == task) {
                                        task_tbody.prepend('<tr><td colspan="5" class="text-center" style="padding:20px 0;">暂无任务数据</td></tr>');
                                    } else {
                                        for(var i in task) {
                                            var tr = "";
                                            tr += '<tr><td class="text-center">' + task[i].id +'</td>';
                                            tr += '<td>' + task[i].course_name + '</td>';
                                            tr += '<td>' + task[i].task_name + '</td>';
                                            tr += '<td>' + task[i].date_create + '</td>';
                                            tr += '<td><button class="layui-btn layui-btn-mini layui-btn-danger but-task-del" data-task="';
                                            tr += task[i].id + '"><i class="layui-icon">&#xe640;</i>&nbsp;删除</button>';
                                            tr += '<button class="layui-btn layui-btn-mini layui-btn-normal but-task-edit" data-task="';
                                            tr += task[i].id + '"><i class="layui-icon">&#xe642;</i>&nbsp;编辑</button>';
                                            tr += '<button class="layui-btn layui-btn-mini but-question-add" data-task="';
                                            tr += task[i].id + '" data-task-name="' + task[i].task_name + '"><i class="layui-icon">&#xe60a;</i>&nbsp;添加题目</button></td></tr>';
                                            task_tbody.prepend(tr);
                                        }
                                    }
                                } else {
                                    layer.open({
                                        icon: 2,
                                        content: response.msg
                                    });
                                }
                            }
                        });
                        map.off('mousedown', _onMouseDown);
                        map.off('mouseup', _onMouseUp);
                        layer.close(addTaskIndex);
                        amap.hide();
                        $('#task-add-form')[0].reset();
                        $('#task-area-lnglat').html('任务区域中心店经纬(暂无, 暂无)');
                        $("input[name='task-course']").val(0);
                        $("#course-title").html('');
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
    // 任务区域选择
    $('#task-area-rectangle').click(function () {
        mouseTool.close(true);
        layer.min(addTaskIndex);
        layer.msg('鼠标在地图上拖动即可选择矩形区域', {icon: 6});
        mouseTool.rectangle();
        _onMouseDown = function (e) {
            taskRectangleLTLng = e.lnglat.getLng();
            taskRectangleLTLat = e.lnglat.getLat();
        };
        map.on('mousedown', _onMouseDown);
        _onMouseUp = function (e) {
            taskRectangleRBLng = e.lnglat.getLng();
            taskRectangleRBLat = e.lnglat.getLat();
            $("#task-rectangle-control").show();
            mouseTool.close(false);
        };
        map.on('mouseup', _onMouseUp);
    });
    // 任务区域选择OK
    $('#task-rectangle-ok').click(function () {
        mouseTool.close(true);
        map.off('mousedown', _onMouseDown);
        map.off('mouseup', _onMouseUp);
        $("#task-rectangle-control").hide();
        $('#task-area-rectangle').html('重选区域');
        // southWest:LngLat, northEast:LngLat
        var taskSWLng = (parseFloat(taskRectangleLTLng) - parseFloat(taskRectangleRBLng)) > 0.000001 ? taskRectangleRBLng : taskRectangleLTLng;
        var taskSWLat = (parseFloat(taskRectangleLTLat) - parseFloat(taskRectangleRBLat)) > 0.000001 ? taskRectangleRBLat : taskRectangleLTLat;
        var taskNELng = (parseFloat(taskRectangleLTLng) - parseFloat(taskRectangleRBLng)) > 0.000001 ? taskRectangleLTLng : taskRectangleRBLng;
        var taskNELat = (parseFloat(taskRectangleLTLat) - parseFloat(taskRectangleRBLat)) > 0.000001 ? taskRectangleLTLat : taskRectangleRBLat;
        var taskCenter = new AMap.Bounds(new AMap.LngLat(taskSWLng, taskSWLat), new AMap.LngLat(taskNELng, taskNELat)).getCenter();
        var taskCenterLng = taskCenter.getLng( );
        var taskCenterLat = taskCenter.getLat( );
        var taskLngLat = '任务区域中心店经纬(' + taskCenterLng + ', ' + taskCenterLat + ')';
        $('#task-area-lnglat').html(taskLngLat);
        $("input[name='task-center-lng']").val(taskCenterLng);
        $("input[name='task-center-lat']").val(taskCenterLat);
        layer.restore(addTaskIndex);
        layer.style(addTaskIndex, {
            height: 'auto'
        });
    });
    // 任务区域重选
    $('#task-re-drag').click(function () {
        iRectangle();
        $("#task-rectangle-control").hide();
    });
    // 临时关闭
    //$('.close-msg').click(function() {
    //    layer.closeAll();
    //});
    $('.del-item').delegate('button', 'click', function() {
        $(this).parent().parent().remove();
    });
    // modify course data
    $('#submit-modify-course').click(function () {
        $.ajax({
            type: 'POST'
            ,url: '/course/modify'
            ,data: $('#modify-course-form').serialize()
            ,dataType: 'JSON'
            ,success: function (response) {
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
    // 删除任务数据按钮
    var task_tbody_e = $("#task-tbody");
    task_tbody_e.on("click", ".but-task-del", function() {
        var taskE = $(this);
        var task = taskE.attr('data-task');
        if(undefined == task || '' == task || false == task) {
            layer.open({
                icon: 2,
                content: "删除任务操作非法"
            });
            return false;
        }
        $.ajax({
            type: "POST",
            url: "/task/delete",
            data: {task: task},
            dataType: "JSON",
            success: function(response){
                if(0 == response.status) {
                    layer.msg(response.msg, {
                        icon: 6,
                        time: 1000
                    }, function(){
                        taskE.parent().parent().remove();
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
    // 点击编辑任务按钮
    task_tbody_e.on("click", ".but-task-edit", function() {
        $("input[name='task-center-lng']").val('');
        $("input[name='task-center-lat']").val('');
        var taskE = $(this);
        var task = taskE.attr('data-task');
        if(undefined == task || '' == task || false == task) {
            layer.open({
                icon: 2,
                content: "编辑任务操作非法"
            });
            return false;
        }
        $.ajax({
            type: "GET",
            url: "/task/modify",
            data: {task: task},
            dataType: "JSON",
            success: function(response){
                if(0 == response.status) {
                    var latlng = response.data.latlng;
                    var data = response.data.task;
                    // 给表单赋值
                    $("input[name='edit-course-task']").val(data.id);
                    $("input[name='edit-task-name']").val(data.name);
                    $("input[name='edit-task-image']").val(data.color);
                    $('#edit-task-area-lnglat').html('任务区域中心点经纬(' + data.lon +', ' + data.lat + ')');
                    $("input[name='task-center-lng']").val(data.lon);
                    $("input[name='task-center-lat']").val(data.lat);
                    $("#edit-course-title").html(courseName);
                    form.render();

                    if(null != latlng && undefined != latlng && '' != latlng) {
                        var courseSWLng, courseSWLat, courseNELng, courseNELat;
                        courseSWLng = latlng.ul_lon;
                        courseSWLat = latlng.br_lat;
                        courseNELng = latlng.br_lon;
                        courseNELat = latlng.ul_lat;
                        var Bounds = new AMap.Bounds(new AMap.LngLat(courseSWLng, courseSWLat), new AMap.LngLat(courseNELng, courseNELat));
                        map.setBounds(Bounds);
                        map.setLimitBounds(Bounds);
                        map.setStatus({dragEnable: false, doubleClickZoom: false});
                        map.setZoom(15);
                    }
                    amap.show();
                    editTaskIndex = layer.open({
                        type: 1
                        , title: ['修改任务', 'font-weight:bold;']
                        , offset: ['10px', '10px']
                        , area: ['500px', 'auto']
                        , maxmin: true
                        , shade: 0
                        , moveType: 1
                        , content: editTaskSwap
                        , cancel: cancelEditTask
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
    // 取消编辑任务方法
    var cancelEditTask = function () {
        amap.hide();
        layer.close(editTaskIndex);
        map.off('mousedown', _onMouseDown);
        map.off('mouseup', _onMouseUp);
        $('#modify-task-form')[0].reset();
        $("#edit-course-title").html('');
        $('#task-area-lnglat').html('任务区域中心点经纬(暂无, 暂无)');
        $("input[name='edit-crouse-task']").val(0);
        $("#edit-task-rectangle-control").hide();
    };
    // 取消任务编辑按钮
    $("#edit-task-cancel-button").click(cancelEditTask);
    // 编辑任务数据
    $('#edit-task-submit-button').click(function () {
        $.ajax({
            type: 'POST'
            ,url: '/task/modify'
            ,data: $('#task-edit-form').serialize()
            ,dataType: 'JSON'
            ,success: function (response) {
                if(0 == response.status) {
                    layer.msg(response.msg, {
                        icon: 6,
                        time: 1000
                    }, function(){
                        $.ajax({
                            type: "GET",
                            url: "/task/listing",
                            data: {course: courseID},
                            dataType: "JSON",
                            success: function(response){
                                if(0 == response.status) {
                                    var task = response.data.task_list;
                                    var task_tbody = $("#task-tbody");
                                    task_tbody.empty();
                                    if(undefined == task) {
                                        task_tbody.prepend('<tr><td colspan="5" class="text-center" style="padding:20px 0;">暂无任务数据</td></tr>');
                                    } else {
                                        for(var i in task) {
                                            var tr = "";
                                            tr += '<tr><td class="text-center">' + task[i].id +'</td>';
                                            tr += '<td>' + task[i].course_name + '</td>';
                                            tr += '<td>' + task[i].task_name + '</td>';
                                            tr += '<td>' + task[i].date_create + '</td>';
                                            tr += '<td><button class="layui-btn layui-btn-mini layui-btn-danger but-task-del" data-task="';
                                            tr += task[i].id + '"><i class="layui-icon">&#xe640;</i>&nbsp;删除</button>';
                                            tr += '<button class="layui-btn layui-btn-mini layui-btn-normal but-task-edit" data-task="';
                                            tr += task[i].id + '"><i class="layui-icon">&#xe642;</i>&nbsp;编辑</button>';
                                            tr += '<button class="layui-btn layui-btn-mini but-question-add" data-task="';
                                            tr += task[i].id + '"><i class="layui-icon">&#xe60a;</i>&nbsp;添加题目</button></td></tr>';
                                            task_tbody.prepend(tr);
                                        }
                                    }
                                } else {
                                    layer.open({
                                        icon: 2,
                                        content: response.msg
                                    });
                                }
                            }
                        });
                        map.off('mousedown', _onMouseDown);
                        map.off('mouseup', _onMouseUp);
                        layer.close(editTaskIndex);
                        amap.hide();
                        $('#task-edit-form')[0].reset();
                        $('#edit-task-area-lnglat').html('任务区域中心店经纬(暂无, 暂无)');
                        $("input[name='task-center-lng']").val('');
                        $("input[name='task-center-lat']").val('');
                        $("#edit-course-title").html('');
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
    // 编辑任务区域选择
    $('#edit-task-area-rectangle').click(function () {
        mouseTool.close(true);
        layer.min(editTaskIndex);
        layer.msg('鼠标在地图上拖动即可选择矩形区域', {icon: 6});
        mouseTool.rectangle();
        _onMouseDown = function (e) {
            editTaskRectangleLTLng = e.lnglat.getLng();
            editTaskRectangleLTLat = e.lnglat.getLat();
        };
        map.on('mousedown', _onMouseDown);
        _onMouseUp = function (e) {
            editTaskRectangleRBLng = e.lnglat.getLng();
            editTaskRectangleRBLat = e.lnglat.getLat();
            $("#edit-task-rectangle-control").show();
            mouseTool.close(false);
        };
        map.on('mouseup', _onMouseUp);
    });
    // 编辑任务区域选择OK
    $('#edit-task-rectangle-ok').click(function () {
        mouseTool.close(true);
        map.off('mousedown', _onMouseDown);
        map.off('mouseup', _onMouseUp);
        $("#edit-task-rectangle-control").hide();
        $('#edit-task-area-rectangle').html('重选区域');
        // southWest:LngLat, northEast:LngLat
        var taskSWLng = (parseFloat(editTaskRectangleLTLng) - parseFloat(editTaskRectangleRBLng)) > 0.000001 ? editTaskRectangleRBLng : editTaskRectangleLTLng;
        var taskSWLat = (parseFloat(editTaskRectangleLTLat) - parseFloat(editTaskRectangleRBLat)) > 0.000001 ? editTaskRectangleRBLat : editTaskRectangleLTLat;
        var taskNELng = (parseFloat(editTaskRectangleLTLng) - parseFloat(editTaskRectangleRBLng)) > 0.000001 ? editTaskRectangleLTLng : editTaskRectangleRBLng;
        var taskNELat = (parseFloat(editTaskRectangleLTLat) - parseFloat(editTaskRectangleRBLat)) > 0.000001 ? editTaskRectangleLTLat : editTaskRectangleRBLat;
        var taskCenter = new AMap.Bounds(new AMap.LngLat(taskSWLng, taskSWLat), new AMap.LngLat(taskNELng, taskNELat)).getCenter();
        var taskCenterLng = taskCenter.getLng();
        var taskCenterLat = taskCenter.getLat();
        var taskLngLat = '任务区域中心店经纬(' + taskCenterLng + ', ' + taskCenterLat + ')';
        $('#edit-task-area-lnglat').html(taskLngLat);
        $("input[name='task-center-lng']").val(taskCenterLng);
        $("input[name='task-center-lat']").val(taskCenterLat);
        layer.restore(editTaskIndex);
        layer.style(editTaskIndex, {
            height: 'auto'
        });
    });
    // 编辑任务区域重选
    $('#edit-task-re-drag').click(function () {
        iRectangle();
        $("#edit-task-rectangle-control").hide();
    });
});