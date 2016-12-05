var singleIndex, multiIndex, photoIndex, qaIndex;
var singleItemNum = 2;
var multiItemNum = 2;
var taskId = 0, taskName = '';
$(function() {
    // 返回任务列表
    $("#return-task-button").click(function() {
        taskName = '';
        taskId = 0;
        $("#topic-list-swap").hide();
        $("#task-list-swap").show();
    });
    var question_tbody = $("#question-tbody");
    // 删除问题数据
    question_tbody.on("click", ".but-question-del", function() {
        var questionE = $(this);
        var question = questionE.attr('data-question');
        if(undefined == question || '' == question || false == question) {
            layer.open({
                icon: 2,
                content: "删除题目操作非法"
            });
            return false;
        }
        $.ajax({
            type: "POST",
            url: "/question/delete",
            data: {question: question},
            dataType: "JSON",
            success: function(response){
                if(0 == response.status) {
                    layer.msg(response.msg, {
                        icon: 6,
                        time: 1000
                    }, function(){
                        questionE.parent().parent().remove();
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
    /* 单选题 */
    // 删除单选题目选项
    $("#single-form").on("click", ".but-del-option", function() {
        var parent = $(this).parent().parent().parent();
        parent.hide();
        parent.find("input[name='right']").attr("checked", false);
        parent.find("input[name='answer[]']").val('');
    });
    // 填充问题列表
    var questionTbodyFill = function(task) {
        $.ajax({
            type: "GET",
            url: "/question/listing",
            data: {task: taskId},
            dataType: "JSON",
            success: function(response){
                if(0 == response.status) {
                    var question = response.data.question_list;
                    var question_tbody = $("#question-tbody");
                    question_tbody.empty();
                    if(undefined == question) {
                        question_tbody.prepend('<tr><td colspan="5" class="text-center" style="padding:20px 0;">暂无题目数据</td></tr>');
                    } else {
                        for(var i in question) {
                            var tr = "";
                            tr += '<tr><td class="text-center">' + question[i].id +'</td>';
                            tr += '<td>' + taskName + '</td>';
                            tr += '<td>' + question[i].kind + '</td>';
                            tr += '<td>' + question[i].date_created + '</td>';
                            tr += '<td><button class="layui-btn layui-btn-mini layui-btn-danger but-question-del" data-question="';
                            tr += question[i].id + '"><i class="layui-icon">&#xe640;</i>&nbsp;删除</button>';
                            tr += '<button style="display:none;" class="layui-btn layui-btn-mini layui-btn-normal but-question-edit" data-question="';
                            tr += question[i].id + '"><i class="layui-icon">&#xe642;</i>&nbsp;编辑</button></td></tr>';
                            question_tbody.prepend(tr);
                        }
                    }
                    $("#topic-list-swap").show();
                    $("#task-list-swap").hide();
                } else {
                    layer.open({
                        icon: 2,
                        content: response.msg
                    });
                }
            }
        });
    };
    // 点击添加题目按钮获取题目列表
    $("#task-tbody").on("click", ".but-question-add", function() {
        var taskE = $(this);
        var task = taskE.attr('data-task');
        if(undefined == task || '' == task || false == task) {
            layer.open({
                icon: 2,
                content: "添加题目操作非法"
            });
            return false;
        }
        taskName = taskE.attr('data-task-name');
        taskId = task;
        questionTbodyFill();
    });
    // 单选添加取消回调方法
    var cancelSingle = function() {
        $("#single-form")[0].reset();
        layer.close(singleIndex);
    };
    // 点击添加单选题
    $("#add-single-button").click(function() {
        singleIndex = layer.open({
            type: 1
            , title: ['添加单选题', 'font-weight:bold;']
            , offset: ['10px', '10px']
            , area: ['510px', 'auto']
            , maxmin: true
            , shade: 0
            , moveType: 1
            , content: $("#single-swap")
            , cancel: cancelSingle
        });
        $("input[name='question-task']").val(taskId);
    });
    // 取消添加单选
    $("#cancel-single").click(cancelSingle);
    // 点击添加一项单选
    $("#add-single-item-button").click(function() {
        singleItemNum++;
        var item = '<div class="layui-form-item"><div class="layui-inline" style="margin-right:0"><label class="layui-form-label">选项' + singleItemNum +
            '</label><div class="layui-input-inline" style="width:auto;"><input type="radio" value="' + singleItemNum + '" name="right" title="选为正答">' +
            '<div class="layui-unselect layui-form-radio"><i class="layui-anim layui-icon"></i><span>选为正答</span></div></div>' +
            '<div class="layui-input-inline" style="width:220px;">' +
            '<input type="text" name="answer[]" placeholder="输入选项答案" autocomplete="off" class="layui-input"></div>' +
            '<div class="layui-input-inline del-item" style="width:auto;line-height:30px;">' +
            '<button type="button" class="layui-btn layui-btn-mini layui-btn-danger but-del-option"><i class="layui-icon">&#x1006;</i></button></div></div></div>';
        $("#add-single-swap").append(item);
        layui.form().render();
    });
    // 添加单选题数据
    $("#add-single-data").click(function() {
        $.ajax({
            type: 'POST'
            ,url: '/question/createSingleSelect'
            ,data: $('#single-form').serialize()
            ,dataType: 'JSON'
            ,success: function (response) {
                if(0 == response.status) {
                    layer.msg(response.msg, {
                        icon: 6,
                        time: 1000
                    }, function(){
                        questionTbodyFill();
                        layer.close(singleIndex);
                        $('#single-form')[0].reset();
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
    // 编辑单选问题获取数据  // TODO
    /* 多选题 */
    // 点击添加多选
    $("#add-multi-button").click(function() {
        multiIndex = layer.open({
            type: 1
            , title: ['添加多选题', 'font-weight:bold;']
            , offset: ['10px', '10px']
            , area: ['526x', 'auto']
            , maxmin: true
            , shade: 0
            , moveType: 1
            , content: $("#multi-swap")
            , cancel: cancelMulti
        });
        $("input[name='question-task']").val(taskId);
    });
    // 点击添加一项多
    $("#add-multi-item-button").click(function() {
        multiItemNum++;
        var item = '<div class="layui-form-item"><div class="layui-inline" style="margin-right:0"><label class="layui-form-label">选项' + multiItemNum +
            '</label><div class="layui-input-inline" style="width:auto;"><input type="checkbox" name="right[]" value="'+ multiItemNum +'" title="选为正答">' +
            '<div class="layui-unselect layui-form-checkbox"><span>选为正答</span><i class="layui-icon"></i></div></div>' +
            '<div class="layui-input-inline" style="width:220px;">' +
            '<input type="text" name="answer[]" placeholder="输入选项答案" autocomplete="off" class="layui-input"></div>' +
            '<div class="layui-input-inline del-item" style="width:auto;line-height:30px;">' +
            '<button type="button" class="layui-btn layui-btn-mini layui-btn-danger but-del-option"><i class="layui-icon">&#x1006;</i></button></div></div></div>';
        $("#add-multi-swap").append(item);
        layui.form().render();
    });
    // 删除多选题目选项
    $("#multi-form").on("click", ".but-del-option", function() {
        var parent = $(this).parent().parent().parent();
        parent.hide();
        parent.find("input[name='right[]']").attr("checked", false);
        parent.find("input[name='answer[]']").val('');
    });
    // 多选添加取消回调方法
    var cancelMulti = function() {
        $("#multi-form")[0].reset();
        layer.close(multiIndex);
    };
    // 取消添加单选
    $("#cancel-multi").click(cancelMulti);
    // 添加多选题数据
    $("#add-multi-data").click(function() {
        $.ajax({
            type: 'POST'
            ,url: '/question/createMultipleSelect'
            ,data: $('#multi-form').serialize()
            ,dataType: 'JSON'
            ,success: function (response) {
                if(0 == response.status) {
                    layer.msg(response.msg, {
                        icon: 6,
                        time: 1000
                    }, function(){
                        questionTbodyFill();
                        layer.close(multiIndex);
                        $('#multi-form')[0].reset();
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
    /* 拍照题 */
    // 点击添加拍照题
    $("#add-photo-button").click(function() {
        photoIndex = layer.open({
            type: 1
            , title: ['添加拍照题', 'font-weight:bold;']
            , offset: ['10px', '10px']
            , area: ['500px', 'auto']
            , maxmin: true
            , shade: 0
            , moveType: 1
            , content: $("#photo-swap")
            , cancel: function () {
                $("#photo-form")[0].reset();
                layer.close(photoIndex);
            }
        });
        $("input[name='question-task']").val(taskId);
    });
    // 取消按钮
    $("#cancel-photo").click(function() {
        $("#photo-form")[0].reset();
        layer.close(photoIndex);
    });
    // 添加拍照题数据
    $("#add-photo-data").click(function() {
        $.ajax({
            type: 'POST'
            ,url: '/question/createPhoto'
            ,data: $('#photo-form').serialize()
            ,dataType: 'JSON'
            ,success: function (response) {
                if(0 == response.status) {
                    layer.msg(response.msg, {
                        icon: 6,
                        time: 1000
                    }, function(){
                        questionTbodyFill();
                        layer.close(photoIndex);
                        $('#photo-form')[0].reset();
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
    /* 问答题 */
    // 点击添加问答题
    $("#add-qa-button").click(function() {
        qaIndex = layer.open({
            type: 1
            , title: ['添加问答题', 'font-weight:bold;']
            , offset: ['10px', '10px']
            , area: ['500px', 'auto']
            , maxmin: true
            , shade: 0
            , moveType: 1
            , content: $("#qa-swap")
            , cancel: function () {
                $("#qa-form")[0].reset();
                layer.close(qaIndex);
            }
        });
        $("input[name='question-task']").val(taskId);
    });
    // 取消按钮
    $("#cancel-qa").click(function() {
        $("#qa-form")[0].reset();
        layer.close(qaIndex);
    });
    // 添加问答题数据
    $("#add-qa-data").click(function() {
        $.ajax({
            type: 'POST'
            ,url: '/question/createQA'
            ,data: $('#qa-form').serialize()
            ,dataType: 'JSON'
            ,success: function (response) {
                if(0 == response.status) {
                    layer.msg(response.msg, {
                        icon: 6,
                        time: 1000
                    }, function(){
                        questionTbodyFill();
                        layer.close(qaIndex);
                        $('#qa-form')[0].reset();
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