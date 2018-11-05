
//非主楼评论弹层
$('.comment .actions a.reply').on('click', function () {
    initCommentData(this.id);
    showReplyForm();

});

//主楼回复弹层
$('.ui.content div.blue.button').on('click', function () {

    initCommentData(0);
    //$('.ui.page').dimmer('show');
    showReplyForm();
});

//邮箱校验
$(".ui.modal .form input[name='email']").blur(function (){
    var email = this.value;
    var preg = /^\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+$/;
    if (email && !preg.test(email)) {
        $('.ui.modal div.error.message').text('邮箱格式错误');
        $('.ui.modal div.error.message').show();
    } else {
        $('.ui.modal div.error.message').hide();
    }
});

//次楼回复
$('.ui.modal .submit.button').api({
    url: '/comment',
    method: 'POST',
    beforeSend: function(settings) {
        var data = $('.ui.modal form').form('get values', ['username', 'comment', 'email', 'nickstatus']);
        data.main_id = window.main_id;
        data.articles_id = $('.ui .content div.description').attr('id');
        if (data.comment) {
            $('.ui.modal div.error.message').hide();
            settings.data = data;
            return settings;
        } else {
            $('.ui.modal div.error.message').text('内容不能为空');
            $('.ui.modal div.error.message').show();
        }

    },
    beforeXHR: function (xhr) {
        xhr.setRequestHeader('X-CSRF-TOKEN',$('meta[name="csrf-token"]').attr('content'));
        return xhr;
    },
    onSuccess: function(response) {
        if (response.status == 200) {
            window.alert(response.msg);
            $('.tiny.modal').modal({allowMultiple: false});
            $('.tiny.modal').modal('hide');
            window.location.reload();
        } else {
            window.alert(response.msg);
        }

    }
});


//展示评论回复框
function showReplyForm ()
{
    $('.tiny.modal').modal('show');
    $('.tiny.modal').modal({allowMultiple: true});
    $('.ui.modal form').form('clear');
}

function initCommentData (main_id)
{
    window.main_id = main_id;
}
/*$('.ui.modal .submit.button').on('click', function () {
    var _self = this;
    var data = $('.ui.modal form').form('get values', ['name', 'content', 'email', 'nickstatus']);
    data._token = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: '/comment/create',
        data:data,
        type: 'post',
        dataType: 'json'
    }).done(function (response) {
        console.log(response);
    });
});*/

/*$('.ui.modal .submit.button').on('click', function () {
    var data = $('.ui.modal form').form('get values', ['name', 'content', 'email', 'nickstatus']);
    var _self = this;
    if (data.content ) {
        $('.ui.modal div.error.message').hide();
        _self.api({
            action: 'createComment',
            method: 'post',
            data: data,
            onResponse: function(response) {
                console.log(response);
            }
        });


    } else {
        $('.ui.modal div.error.message').text('内容不能为空');
        $('.ui.modal div.error.message').show();
    }
});*/



