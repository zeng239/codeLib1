function fishupload(con){
    var config = {
        'trigger':(con.trigger ? con.trigger : 'img_plus'), //上传图片的触发元素id
        'submit':(con.submit ? con.submit : 'uploadBtn'), //提交按钮id
        'accept': (con.accept ? con.accept : 'image/png, image/jpeg, image/gif, image/jpg'),   //可接受的文件类型
        'action_url':(con.action_url ? con.action_url :'picupload.php'),    //上传处理脚本
        'count':(con.count ? con.count : 5), //上传图片数量,
        'init_data':(con.init_data ? con.init_data : []),//初始数据
        'max_size':2048*1024    //最大字节上传图片最大值(单位字节)（ 2 M = 2097152 B ）
    }
    config.id = con.id ? con.id : config.trigger +'_fishupload';//生成的隐藏file dom 的id
  

    this.init = function(){
        //生成file dom 元素
        var eleHtml = '<input id="'+ config.id +'" style="display: none;"';
        if(config.count > 1){
            eleHtml += ' multiple="multiple" ';
        }
        eleHtml +=  'class="filepath" type="file" accept="'+config.accept +'">';
        $('body').append(eleHtml);

        //绑定选择图片触发元素id
        $("#"+config.trigger).click(function(){
            $("#"+config.id).trigger("click");
        });
        //预览区域
        $("#"+config.trigger).after('<div id="'+config.trigger+'_preview_box" class="preview_box"></div>');

        //初始化数据，可用于已经上传的图片编辑
        for(let i = 0;i < config.init_data.length;i++){
            $('<div class="imgbox">'
            +'<img src="'+config.init_data[i]+'" data-filetype="src">'
            +'<div class="del_img" onclick="delImg(this)">删除</div>'
            +'</div>').appendTo('#'+config.trigger+'_preview_box');
        }

        //绑定上传按钮id
        $("#"+config.submit).click(function(){  //上传至服务器
            var url = config.action_url;
            var images = [];
            $('#'+config.trigger +'_preview_box > .imgbox > img').each(function(i,el){
                images.push({'file':$(el).attr("src"), 'type':$(el).attr("data-filetype")});
            })
            if(images.length > 0){
                $.post(url,{'images':images},function(res){
                    console.log(res);
                    if(res.code == 1){
                        alert(res.msg);
                    }
                });
            }
        });
        
        $("#"+config.id).on("change",function(){
            var count =  $('#'+config.trigger +'_preview_box > .imgbox > img').length;  //选择图片数量
            var AllowImgFileSize = config.max_size; //上传图片最大值(单位字节)（ 2 M = 2097152 B ）超过2M上传失败
            var files = $("#"+config.id)[0].files;
            count += files.length;
            console.log(count+":"+ config.count);
            if(count > config.count){
                alert('不能上传超过' + config.count + '张图片');
                return;
            }
            for(let i = 0; i < files.length; i++){
               // console.log(files[i]);
                if (files[i]) {
                    var reader = new FileReader();
                    reader.readAsDataURL(files[i]); //将文件以Data URL形式读入
                    reader.onload = function(e) {
                        //var ImgFileSize = reader.result.substring(reader.result.indexOf(",") + 1).length;//截取base64码部分（可选可不选，需要与后台沟通）
                        if (AllowImgFileSize != 0 && AllowImgFileSize < reader.result.length) {
                            alert('上传失败，请上传不大于'+(config.max_size/1024/1024)+'M的图片！');
                            return;
                        }else{
                            //执行上传操作
                            let filetype = files[i].name.substr(-3, 3);
                            $('<div class="imgbox">'
                                +'<img src="'+e.target.result+'" data-filetype="'+ filetype +'">'
                                +'<div class="del_img" onclick="delImg(this)">删除</div>'
                                +'</div>').appendTo('#'+config.trigger+'_preview_box');
                            //console.log(i);
                        }
                    }
                }
            }
            dragImg(config.trigger+'_preview_box');    //重新绑定拖动
        })
    }
    
    
};

//绑定拖动
function dragImg(id){
    var el = document.getElementById(id);
    Sortable.create(el, { group: "imgPreview"});
}

//删除图片
function delImg(obj){
    $(obj).parent().remove();
}
