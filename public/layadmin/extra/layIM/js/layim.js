/*

 @Name: layui WebIM 1.0.0
 @Author：贤心
 @Date: 2014-04-25
 @Blog: http://sentsin.com
 
 */
 
;!function(win, undefined){

var config = {
    msgurl: '私信地址',
    chatlogurl: '',
    aniTime: 200,
    right: -232,
    api: {
        friend: imconfig.app_url+'?pm-ajaxmyfollow.htm', //好友列表接口
		chatlog: imconfig.app_url+'?pm-ajaxlastchat.htm', //聊天记录接口
        group: '', //群组列表接口 
        groups: '', //群组成员接口
        sendurl: '' //发送消息接口
    },
    user: { //当前用户信息
        name: imconfig.username,
        face: imconfig.avatar
    },
    
    //自动回复内置文案，也可动态读取数据库配置
    autoReplay: [
        '您好，我现在有事不在，一会再和您联系。', 
    ],
    
    chating: {},
    hosts: (function(){
        var dk = location.href.match(/\:\d+/);
        dk = dk ? dk[0] : '';
        return 'http://' + document.domain + dk + '/';
    })(),
    json: function(url, data, callback, error){
        return $.ajax({
            type: 'POST',
            url: url,
            data: data,
            dataType: 'json',
            success: callback,
            error: error
        });
    },
    stopMP: function(e){
        e ? e.stopPropagation() : e.cancelBubble = true;
    }
}, dom = [$(window), $(document), $('html'), $('body')], xxim = {};

//主界面tab
xxim.tabs = function(index){
    var node = xxim.node;
    node.tabs.eq(index).addClass('xxim_tabnow').siblings().removeClass('xxim_tabnow');
    node.list.eq(index).show().siblings('.xxim_list').hide();
    if(node.list.eq(index).find('li').length === 0){
        xxim.getDates(index);
    }
};

//节点
xxim.renode = function(){
    var node = xxim.node = {
        tabs: $('#xxim_tabs>span'),
        list: $('.xxim_list'),
        online: $('.xxim_online'),
        setonline: $('.xxim_setonline'),
        onlinetex: $('#xxim_onlinetex'),
        xximon: $('#xxim_on'),
        layimFooter: $('#xxim_bottom'),
        xximHide: $('#xxim_hide'),
        xximSearch: $('#xxim_searchkey'),
        searchMian: $('#xxim_searchmain'),
        closeSearch: $('#xxim_closesearch'),
        layimMin: $('#layim_min')
    }; 
};

//主界面缩放
xxim.expend = function(){
    var node = xxim.node;
    if(xxim.layimNode.attr('state') !== '1'){
        xxim.layimNode.stop().animate({right: config.right}, config.aniTime, function(){
            node.xximon.addClass('xxim_off');
            try{
                localStorage.layimState = 1;
            }catch(e){}
            xxim.layimNode.attr({state: 1});
            node.layimFooter.addClass('xxim_expend').stop().animate({marginLeft: config.right}, config.aniTime/2);
            node.xximHide.addClass('xxim_show');
        });
    } else {
        xxim.layimNode.stop().animate({right: 1}, config.aniTime, function(){
            node.xximon.removeClass('xxim_off');
            try{
                localStorage.layimState = 2;
            }catch(e){}
            xxim.layimNode.removeAttr('state');
            node.layimFooter.removeClass('xxim_expend');
            node.xximHide.removeClass('xxim_show');
        });
        node.layimFooter.stop().animate({marginLeft: 0}, config.aniTime);
    }
};

//初始化窗口格局
xxim.layinit = function(){
    var node = xxim.node;
    
    //主界面
    try{
        /*
        if(!localStorage.layimState){       
            config.aniTime = 0;
            localStorage.layimState = 1;
        }
        */
        if(localStorage.layimState === '1'){
            xxim.layimNode.attr({state: 1}).css({right: config.right});
            node.xximon.addClass('xxim_off');
            node.layimFooter.addClass('xxim_expend').css({marginLeft: config.right});
            node.xximHide.addClass('xxim_show');
        }
    }catch(e){
        //layer.msg(e.message, 5, -1);
    }
};

//聊天窗口
xxim.popchat = function(param){
	
    var node = xxim.node, log = {};
    
    log.success = function(layero){
        layer.setMove();
     
        xxim.chatbox = layero.find('#layim_chatbox');
        log.chatlist = xxim.chatbox.find('.layim_chatmore>ul');
        
        log.chatlist.html('<li data-id="'+ param.id +'" type="'+ param.type +'"  id="layim_user'+ param.type + param.id +'"><span>'+ param.name +'</span><em>×</em></li>')
        xxim.tabchat(param, xxim.chatbox);
        
        //最小化聊天窗
        xxim.chatbox.find('.layer_setmin').on('click', function(){
            var indexs = layero.attr('times');
            layero.hide();
            node.layimMin.text(xxim.nowchat.name).show();
        });
        
        //关闭窗口
        xxim.chatbox.find('.layim_close').on('click', function(){
            var indexs = layero.attr('times');
            layer.close(indexs);
            xxim.chatbox = null;
            config.chating = {};
            config.chatings = 0;
        });
        
        //关闭某个聊天
        log.chatlist.on('mouseenter', 'li', function(){
            $(this).find('em').show();
        }).on('mouseleave', 'li', function(){
            $(this).find('em').hide();
        });
        log.chatlist.on('click', 'li em', function(e){
            var parents = $(this).parent(), dataType = parents.attr('type');
            var dataId = parents.attr('data-id'), index = parents.index();
            var chatlist = log.chatlist.find('li'), indexs;
            
            config.stopMP(e);
            
            delete config.chating[dataType + dataId];
            config.chatings--;
            
            parents.remove();
            $('#layim_area'+ dataType + dataId).remove();
            if(dataType === 'group'){
                $('#layim_group'+ dataType + dataId).remove();
            }
            
            if(parents.hasClass('layim_chatnow')){
                if(index === config.chatings){
                    indexs = index - 1;
                } else {
                    indexs = index + 1;
                }
                xxim.tabchat(config.chating[chatlist.eq(indexs).attr('type') + chatlist.eq(indexs).attr('data-id')]);
            }
            
            if(log.chatlist.find('li').length === 1){
                log.chatlist.parent().hide();
            } 
        });
        
        //聊天选项卡
        log.chatlist.on('click', 'li', function(){
            var othis = $(this), dataType = othis.attr('type'), dataId = othis.attr('data-id');
            xxim.tabchat(config.chating[dataType + dataId]);
        });
        
        //发送热键切换
        log.sendType = $('#layim_sendtype'), log.sendTypes = log.sendType.find('span');
        $('#layim_enter').on('click', function(e){
            config.stopMP(e);
            log.sendType.show();
        });
        log.sendTypes.on('click', function(){
            log.sendTypes.find('i').text('')
            $(this).find('i').text('√');
        });
        xxim.transmit();
    };
	
    
    log.html = '<div class="layim_chatbox" id="layim_chatbox">'
            +'<h6>'
            +'<span class="layim_move"></span>'
            +'    <a href="'+ param.url +'" class="layim_face" target="_blank"><img src="'+ param.face +'" ></a>'
            +'    <a href="'+ param.url +'" class="layim_names" target="_blank">'+ param.name +'</a>'
            +'    <span class="layim_rightbtn">'
            +'        <i class="layer_setmin"></i>'
            +'        <i class="layim_close"></i>'
            +'    </span>'
            +'</h6>'
            +'<div class="layim_chatmore" id="layim_chatmore">'
            +'    <ul class="layim_chatlist"></ul>'
            +'</div>'
            +'<div class="layim_groups" id="layim_groups"></div>'
            +'<div class="layim_chat">'
            +'    <div class="layim_chatarea" id="layim_chatarea">'
            +'        <ul class="layim_chatview layim_chatthis"  id="layim_area'+ param.type + param.id +'"></ul>'
            +'    </div>'
            +'    <div class="layim_tool">'
            +'        <i class="layim_addface" title="发送表情"></i>'
            +'        <a href="javascript:;"><i class="layim_addimage" title="上传图片"></i></a>'
            +'        <a href="javascript:;"><i class="layim_addfile" title="上传附件"></i></a>'
            +'        <a href="" target="_blank" class="layim_seechatlog"><i></i>聊天记录</a>'
            +'    </div>'
            +'    <textarea class="layim_write" id="layim_write"></textarea>'
            +'    <div class="layim_send">'
            +'        <div class="layim_sendbtn" id="layim_sendbtn">发送<span class="layim_enter" id="layim_enter"><em class="layim_zero"></em></span></div>'
            +'        <div class="layim_sendtype" id="layim_sendtype">'
            +'            <span><i>√</i>按Enter键发送</span>'
            +'            <span><i></i>按Ctrl+Enter键发送</span>'
            +'        </div>'
            +'    </div>'
            +'</div>'
            +'</div>';

    if(config.chatings < 1){
		$('.layim_close').trigger('click');
		$.get(imconfig.app_url+'?pm-ajaxlistbody-uid-'+param.id+'-ajax-0.htm',{},function(html){
			$.layer({
            type: 1,
            border: [0],
            title: false,
            shade: [0],
            area: ['620px', '493px'],
            move: '.layim_chatbox .layim_move',
            moveType: 1,
            closeBtn: false,
            offset: [(($(window).height() - 493)/2)+'px', ''],
            page: {
                html: html
            }, success: function(layero){
                log.success(layero);
            }
           })
			
		})
        
    } else {
        log.chatmore = xxim.chatbox.find('#layim_chatmore');
        log.chatarea = xxim.chatbox.find('#layim_chatarea');
        
        log.chatmore.show();
        
        log.chatmore.find('ul>li').removeClass('layim_chatnow');
        log.chatmore.find('ul').append('<li data-id="'+ param.id +'" type="'+ param.type +'" id="layim_user'+ param.type + param.id +'" class="layim_chatnow"><span>'+ param.name +'</span><em>×</em></li>');
        
        log.chatarea.find('.layim_chatview').removeClass('layim_chatthis');
        log.chatarea.append('<ul class="layim_chatview layim_chatthis" id="layim_area'+ param.type + param.id +'"></ul>');
        
        xxim.tabchat(param);
    }
    
    //群组
    log.chatgroup = xxim.chatbox.find('#layim_groups');
    if(param.type === 'group'){
        log.chatgroup.find('ul').removeClass('layim_groupthis');
        log.chatgroup.append('<ul class="layim_groupthis" id="layim_group'+ param.type + param.id +'"></ul>');
        xxim.getGroups(param);
    }
    //点击群员切换聊天窗
    log.chatgroup.on('click', 'ul>li', function(){
        xxim.popchatbox($(this));
    });
		
};

//定位到某个聊天队列
xxim.tabchat = function(param){
    var node = xxim.node, log = {}, keys = param.type + param.id;
    xxim.nowchat = param;
    
    xxim.chatbox.find('#layim_user'+ keys).addClass('layim_chatnow').siblings().removeClass('layim_chatnow');
    xxim.chatbox.find('#layim_area'+ keys).addClass('layim_chatthis').siblings().removeClass('layim_chatthis');
    xxim.chatbox.find('#layim_group'+ keys).addClass('layim_groupthis').siblings().removeClass('layim_groupthis');
    
    //xxim.chatbox.find('.layim_face>img').attr('src', param.face);
    xxim.chatbox.find('.layim_face, .layim_names').attr('href', param.href);
    //xxim.chatbox.find('.layim_names').text(param.name);
    
    //xxim.chatbox.find('.layim_seechatlog').attr('href', config.chatlogurl + param.id);

    log.groups = xxim.chatbox.find('.layim_groups');
    if(param.type === 'group'){
        log.groups.show();
    } else {
        log.groups.hide();
    }
    
    $('#layim_write').focus();
    
};

//弹出聊天窗
xxim.popchatbox = function(othis){
	if(check_login()===false) return;
    var node = xxim.node, dataId = othis.attr('data-id'), param = {
        id: dataId, //用户ID
        type: othis.attr('type'),
        name: othis.find('.xxim_onename').text(),  //用户名
        face: othis.find('.xxim_oneface').attr('src'),  //用户头像
        href: othis.attr('data-href')//config.hosts + '?you-index-uid-' + dataId+'.htm' //用户主页
    }, key = param.type + dataId,log={};
    othis.find("img").removeClass('havenewpm');
    if(!config.chating[key]){
        xxim.popchat(param);
        config.chatings++;
    } else {
        xxim.tabchat(param);
    }
    config.chating[key] = param;
    
    var chatbox = $('#layim_chatbox');
    if(chatbox[0]){
        node.layimMin.hide();
        chatbox.parents('.xubox_layer').show();
    }
	//聊天模版
            log.html = function(param, type){
                return '<li class="'+ (type === 'me' ? 'layim_chateme' : '') +'">'
                    +'<div class="layim_chatuser">'
                        + function(){
                            if(type === 'me'){
                                return '<span class="layim_chattime">'+ param.time +'</span>'
                                       +'<span class="layim_chatname">'+ param.name +'</span>'
                                       +'<img src="'+ param.face +'" >';
                            } else {
                                return '<img src="'+ param.face +'" >'
                                       +'<span class="layim_chatname">'+ param.name +'</span>'
                                       +'<span class="layim_chattime">'+ param.time +'</span>';      
                            }
                        }()
                    +'</div>'
                    +'<div class="layim_chatsay">'+ param.content +'<em class="layim_zero"></em></div>'
                +'</li>';
            };
            
            log.imarea = xxim.chatbox.find('#layim_area'+ key);
			log.imarea.scrollTop(log.imarea[0].scrollHeight);
			var chatlogurl=imconfig.chatlogurl.replace('[touid]',dataId);
	config.json(chatlogurl,{},function(datas){
		
		var i = 0, myflen = datas.data.length, str = '', item;

		for(; i < myflen; i++){
			log.imarea.append(log.html({
                    time: datas.data[i].time,
                    name: datas.data[i].name,
                    face: datas.data[i].face,
                    content: datas.data[i].content
                },datas.data[i].type));
		}
		log.imarea.scrollTop(log.imarea[0].scrollHeight);	
	},function(){});
};

//请求群员
xxim.getGroups = function(param){
    var keys = param.type + param.id, str = '',
    groupss = xxim.chatbox.find('#layim_group'+ keys);
    groupss.addClass('loading');
    config.json(config.api.groups, {}, function(datas){
        if(datas.status === 1){
            var ii = 0, lens = datas.data.length;
            if(lens > 0){
                for(; ii < lens; ii++){
                    str += '<li data-id="'+ datas.data[ii].id +'" type="one"><img src="'+ datas.data[ii].face +'" class="xxim_oneface"><span class="xxim_onename">'+ datas.data[ii].name +'</span></li>';
                }
            } else {
                str = '<li class="layim_errors">没有群员</li>';
            }
            
        } else {
            str = '<li class="layim_errors">'+ datas.msg +'</li>';
        }
        groupss.removeClass('loading');
        groupss.html(str);
    }, function(){
        groupss.removeClass('loading');
        groupss.html('<li class="layim_errors">请求异常</li>');
    });
};

//消息传输
xxim.transmit = function(){
    var node = xxim.node, log = {};
    node.sendbtn = $('#layim_sendbtn');
    node.imwrite = $('#layim_write');
    
    //发送
    log.send = function(){
        var data = {
            content: node.imwrite.val(),
			message:node.imwrite.val(),
            id: xxim.nowchat.id,
            sign_key: '', //密匙
            _: +new Date
        };

        if(data.content.replace(/\s/g, '') === ''){
            layer.tips('说点啥呗！', '#layim_write', 2);
            node.imwrite.focus();
        } else {
            //此处皆为模拟
            var keys = xxim.nowchat.type + xxim.nowchat.id;
            
            //聊天模版
            log.html = function(param, type){
                return '<li class="'+ (type === 'me' ? 'layim_chateme' : '') +'">'
                    +'<div class="layim_chatuser">'
                        + function(){
                            if(type === 'me'){
                                return '<span class="layim_chattime">'+ param.time +'</span>'
                                       +'<span class="layim_chatname">'+ param.name +'</span>'
                                       +'<img src="'+ param.face +'" >';
                            } else {
                                return '<img src="'+ param.face +'" >'
                                       +'<span class="layim_chatname">'+ param.name +'</span>'
                                       +'<span class="layim_chattime">'+ param.time +'</span>';      
                            }
                        }()
                    +'</div>'
                    +'<div class="layim_chatsay">'+ param.content +'<em class="layim_zero"></em></div>'
                +'</li>';
            };
            
            log.imarea = xxim.chatbox.find('#layim_area'+ keys);
            
            
			var sendurl=imconfig.sendurl.replace("[touid]", data.id);
			config.json(sendurl,data,function(json){
				if(json.status===0){alert(json.message);return false;}
				var myDate = new Date();
				log.imarea.append(log.html({
					time: myDate.getHours()+':'+myDate.getMinutes()+':'+myDate.getSeconds(),
					name: config.user.name,
					face: config.user.face,
					content: data.content
                }, 'me'));
				log.imarea.scrollTop(log.imarea[0].scrollHeight);
				},function(){});
	
            node.imwrite.val('').focus();
            log.imarea.scrollTop(log.imarea[0].scrollHeight);
            
            /*setTimeout(function(){
                log.imarea.append(log.html({
                    time: '2014-04-26 0:38',
                    name: xxim.nowchat.name,
                    face: xxim.nowchat.face,
                    content: config.autoReplay[(Math.random()*config.autoReplay.length) | 0]
                }));
                log.imarea.scrollTop(log.imarea[0].scrollHeight);
            }, 500);*/
            
            /*
            that.json(config.api.sendurl, data, function(datas){
            
            });
            */
        }
       
    };
    node.sendbtn.on('click', log.send);
    
    node.imwrite.keyup(function(e){
        if(e.keyCode === 13){
            log.send();
        }
    });
};

//事件
xxim.event = function(){
    var node = xxim.node;
    
    //主界面tab
    node.tabs.eq(0).addClass('xxim_tabnow');
    node.tabs.on('click', function(){
        var othis = $(this), index = othis.index();
        xxim.tabs(index);
    });
    
    //列表展收
    node.list.on('click', 'h5', function(){
        var othis = $(this), chat = othis.siblings('.xxim_chatlist'), parentss = othis.parent();
        if(parentss.hasClass('xxim_liston')){
            chat.hide();
            parentss.removeClass('xxim_liston');
        } else {
            chat.show();
            parentss.addClass('xxim_liston');
        }
    });
    
    //设置在线隐身
    node.online.on('click', function(e){
        config.stopMP(e);
        node.setonline.show();
    });
    node.setonline.find('span').on('click', function(e){
        var index = $(this).index();
        config.stopMP(e);
        if(index === 0){
            node.onlinetex.html('在线');
            node.online.removeClass('xxim_offline');
        } else if(index === 1) {
            node.onlinetex.html('隐身');
            node.online.addClass('xxim_offline');
        }
        node.setonline.hide();
    });
    
    node.xximon.on('click', xxim.expend);
    node.xximHide.on('click', xxim.expend);
    
    //搜索
    node.xximSearch.keyup(function(){
        var val = $(this).val().replace(/\s/g, '');
        if(val !== ''){
            node.searchMian.show();
            node.closeSearch.show();
            //此处的搜索ajax参考xxim.getDates
            node.list.eq(3).html('<li class="xxim_errormsg">没有符合条件的结果</li>');
        } else {
            node.searchMian.hide();
            node.closeSearch.hide();
        }
    });
    node.closeSearch.on('click', function(){
        $(this).hide();
        node.searchMian.hide();
        node.xximSearch.val('').focus();
    });
    
    //弹出聊天窗
    config.chatings = 0;
    node.list.on('click', '.xxim_childnode', function(){
        var othis = $(this);
        xxim.popchatbox(othis);
    });
    
    //点击最小化栏
    node.layimMin.on('click', function(){
        $(this).hide();
        $('#layim_chatbox').parents('.xubox_layer').show();
    });
    
    
    //document事件
    dom[1].on('click', function(){
        node.setonline.hide();
        $('#layim_sendtype').hide();
    });
};

//请求列表数据
xxim.getDates = function(index){
    var api = [config.api.chatlog,config.api.friend,config.api.group],
        node = xxim.node, myf = node.list.eq(index);
    myf.addClass('loading');

    config.json(api[index], {}, function(datas){
        if(datas.status === 1){
            var i = 0, myflen = datas.data.length, str = '', item,np='';

            if(myflen >= 1){
                if(index == 1){
                    for(; i < myflen; i++){
						str += '<li class="xxim_liston">'
                        +'<ul class="xxim_chatlist">';
						for(; i < myflen; i++){
							str += '<li data-id="'+ datas.data[i].id +'" data-href="'+datas.data[i].url+'" class="xxim_childnode" type="one"><img src="'+ datas.data[i].face +'"  class="xxim_oneface"><span  class="xxim_onename">'+ datas.data[i].name +'</span></li>'; 
						}
						str += '</ul></li>';
                    }
                } else {
                    str += '<li class="xxim_liston">'
                        +'<ul class="xxim_chatlist">';
                    for(; i < myflen; i++){
						
						if(datas.data[i].count>0){
						   np='havenewpm';
						}else{
							np='';
						}
                        str += '<li id="li_lastuser_'+ datas.data[i].id +'" data-id="'+ datas.data[i].id +'" class="xxim_childnode" type="one"><div class="img"><img src="'+ datas.data[i].face +'"  class="xxim_oneface '+np+'" id="userface_'+ datas.data[i].id +'"></div><span  class="xxim_onename">'+ datas.data[i].name +'</span><em class="xxim_time">'+ datas.data[i].time +'</em></li>'; 
                    }
                    str += '</ul></li><script>var npmu=$(".havenewpm");</script>';
                }
                myf.html(str);
            } else {
                myf.html('<li class="xxim_liston"><ul class="xxim_chatlist"></ul></li><li class="xxim_errormsg">您还没有联系人</li>');
            }
            myf.removeClass('loading');
        } else {
            myf.html('<li class="xxim_errormsg">'+ datas.msg +'</li>');
        }
    }, function(){
        myf.html('<li class="xxim_errormsg">请求失败</li>');
        myf.removeClass('loading');
    });
};

//渲染骨架
xxim.view = (function(){
	var tipshtml='<div times="1" showtime="3000" style="display:none;z-index: 19891015; width: 80px; height: auto; position: absolute; margin-left: 0px; left: 0px; top: -30px;" id="newpmtips" class="xubox_layer" type="tips"><div style="background-color:; z-index:19891015" class="xubox_main"><div class="xubox_tips" style="background-color: rgb(15, 166, 216); color: rgb(255, 255, 255); padding-right: 10px;"><div class="xubox_tipsMsg">有新信息</div><i class="layerTipsG layerTipsT" style="top:16px;background:none;border-right-color: rgb(15, 166, 216);"></i></div><span class="xubox_setwin"></span><span class="xubox_botton"></span></div></div>';
    var xximNode = xxim.layimNode = $('<div id="xximmm" class="xxim_main">'
            +'<div class="xxim_top" id="xxim_top">'
            +'  <div class="xxim_tabs" id="xxim_tabs"><span class="xxim_latechat"  title="最近聊天"><i></i></span><span class="xxim_tabfriend" title="我的关注"><i></i></span></div>'
            +'  <ul class="xxim_list" style="display:block"></ul>'
            +'  <ul class="xxim_list"></ul>'
			+'  <ul class="xxim_list"></ul>'
            +'</div>'
            +'<ul class="xxim_bottom" id="xxim_bottom">'
            +'<li class="xxim_online" id="xxim_online">'
                +'<i class="xxim_nowstate"></i><span id="xxim_onlinetex">在线</span>'
                +'<div class="xxim_setonline">'
                    +'<span><i></i>在线</span>'
                    +'<span class="xxim_setoffline"><i></i>隐身</span>'
                +'</div>'
            +'</li>'
            +'<li class="xxim_mymsg" id="xxim_mymsg" title="我的私信"><i></i><a href="###" id="layim_mypm"></a>'+tipshtml+'</li>'
            +'<li class="xxim_seter" id="xxim_seter" title="设置">'
                +'<i></i>'
                +'<a href="'+imconfig.app_url+'?my-homepage.htm"><div class="" style="height:100%">'
                +'</div>'
				+'</a>'
            +'</li>'
            +'<li class="xxim_hide" id="xxim_hide"><i></i></li>'
            +'<li id="xxim_on" class="xxim_icon xxim_on"></li>'
            +'<div class="layim_min" id="layim_min"></div>'
        +'</ul>'
    +'</div>');
    dom[3].append(xximNode);
    
    xxim.renode();
    xxim.getDates(0);
    xxim.event();
    xxim.layinit();
}());

//此处皆为模拟
         
//聊天模版
var chatlog={};
chatlog.html = function(param, type){
                return '<li class="'+ (type === 'me' ? 'layim_chateme' : '') +'">'
                    +'<div class="layim_chatuser">'
                        + function(){
                            if(type === 'me'){
                                return '<span class="layim_chattime">'+ param.time +'</span>'
                                       +'<span class="layim_chatname">'+ param.name +'</span>'
                                       +'<img src="'+ param.face +'" >';
                            } else {
                                return '<img src="'+ param.face +'" >'
                                       +'<span class="layim_chatname">'+ param.name +'</span>'
                                       +'<span class="layim_chattime">'+ param.time +'</span>';      
                            }
                        }()
                    +'</div>'
                    +'<div class="layim_chatsay">'+ param.content +'<em class="layim_zero"></em></div>'
                +'</li>';
            };
            
chatlog.imarea =function(keys){return xxim.chatbox.find('#layim_area'+ keys);};




// ------------------------> 短消息 start
	
	function userlist_to_html(userlist) {
		var s = 1;
		for(k in userlist) {
			var user = userlist[k];
			//s='<a data-id="'+ user.uid +'" data-href="" class="xxim_childnode" type="one" onclick="xxim.popchatbox($(this));"></a>';
			if(s==1){$("#layim_mypm").addClass('xxim_childnode').attr('data-id',user.uid).on('click',function(){var othis = $(this);xxim.popchatbox(othis);});}
			$('#li_lastuser_'+ user.uid ).remove();
			$('.xxim_chatlist').prepend('<li id="li_lastuser_'+ user.uid +'" data-id="'+ user.uid +'" class="xxim_childnode" type="one"><div class="img"><img src="'+ user.avatar_small +'"  class="xxim_oneface havenewpm" id="userface_'+ user.uid +'"></div><span  class="xxim_onename">'+ user.username +'</span><em class="xxim_time">'+ user.time +'</em></li>');
			s++;
		}
		
		return s;
	}
	// 如果有新短消息，除了全局提示以外，再做一个全局标记，实现模拟即时聊天。
	var g_newpm_userlist = null;	// 全局变量
	
	// 心跳频率  根据负载来调整，如果PV <10W: 1秒, <100w 2秒, <600w 3秒, 600w+, 5秒
	var g_newpm_delay =imconfig.pm_delay;
	
	function newpm() {
		var _this = this;
		_this.delay = g_newpm_delay;
		_this.t = null;
		_this.stop = function() {
			if(_this.t) clearTimeout(_this.t);
		};
		_this.run = function() {
			_this.stop();
			_this.t = setTimeout(function() {
			$.get(imconfig.pmnew_url, function(s) {
				var json=json_decode(s);
				if(error = json_error(json)) {return false;}
				if(json.status == 1) {
						var userlist = json.message;
						g_newpm_userlist = userlist;
						userlist_to_html(userlist);
						$("#newpmtips").show();
						//xxim.getDates(0);
						
						_this.delay = g_newpm_delay;
						_this.run();
				} else if(json.status == 2) {
					$("#newpmtips").hide();
					g_newpm_userlist = null;
					_this.delay = _this.delay * 2;
					_this.run();
				}else {

				}
				
			})
			/*config.json(imconfig.pmnew_url, {}, function(json){
				if(json.status == 1) {
						var userlist = json.message;
						g_newpm_userlist = userlist;
						userlist_to_html(userlist);
						$("#newpmtips").show();
						//xxim.getDates(0);
						
						_this.delay = g_newpm_delay;
						_this.run();
				} else if(json.status == 2) {
					$("#newpmtips").hide();
					g_newpm_userlist = null;
					_this.delay = _this.delay * 2;
					_this.run();
				}else {

				}
		},function(){});*/
			
			}, _this.delay);
		};
		return this;
	}
	
	
	var newpm_instance = new newpm(); 
	newpm_instance.run();
	
function newpmuser(){	
        var __this = this,ii=1;
		
		if(__this.pmlist_handle) clearInterval(_this.pmlist_handle);	// _this.pmlist_handle 全局变量，用来存储计时器句柄。
		
		__this.pmlist_handle = setInterval(function() {
					    if(ii%2==0){
							$('.havenewpm').addClass('move');
						}else{
						    $('.havenewpm').removeClass('move');
						}
                        ii++;
		}, 300);
		return this;
}
var npmuser=new newpmuser();
}(window);