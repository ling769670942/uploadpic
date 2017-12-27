<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<link rel="stylesheet" type="text/css" href="webuploader-0.1.5/webuploader.css">
</head>
<body>
<div class="yx">
	<div class="sctp">
		<div class="uploader_input-box">
			<div class="uploader_input btn_tu"  id=""></div>
		</div>
		<input type="hidden" value="" class="path_tu path_tu_8">
		<div class="content">
			<textarea name="tu1c" id="" minlength="20" maxlength="200" placeholder="请为图片配20-200个字描述" class="tu_wen"></textarea>
		</div>
	</div>
	<div class="sctp">
		<div class="uploader_input-box">
			<div class="uploader_input btn_tu" id=""></div>
		</div>
		<input type="hidden" value="" class="path_tu path_tu_8">
		<div class="content">
			<textarea name="tu1c" id="" minlength="20" maxlength="200" placeholder="请为图片配20-200个字描述"  class="tu_wen"></textarea>
		</div>
	</div>

	<div class="sctp">
		<div class="uploader_input-box">
			<div class="uploader_input btn_tu" id=""></div>
		</div>
		<input type="hidden" value="" class="path_tu path_tu_8">
		<div class="content">
			<textarea name="tu1c" id="" minlength="20" maxlength="200" placeholder="请为图片配20-200个字描述"  class="tu_wen"></textarea>
		</div>
	</div>
</div>
<script type="text/javascript" src="webuploader-0.1.5/webuploader.js"></script>
<script type="text/javascript">
	// 初始化Web Uploader
	uploader_img()
	function uploader_img(){
	var url = $('#url').val();
	var uploader = WebUploader.create({
	    // 选完文件后，是否自动上传。
	    auto: true,
	    // swf文件路径
	    swf: './public/webuploader/Uploader.swf',
	    // 文件接收服务端。
	    server: url,
	    // 选择文件的按钮。可选。
	    // 内部根据当前运行是创建，可能是input元素，也可能是flash.
	    pick: '.btn_tu',
	    // 只允许选择图片文件。
	    accept: {
	        title: 'Images',
	        extensions: 'gif,jpg,jpeg,bmp,png',
	        mimeTypes: 'image/*'
	    }
	});
	// 当有文件添加进来的时候
	var l = '';
	$('.btn_tu').click(function(){
	// $('.ganshou_s').on('click','.btn_tu',function(){
		 l = $('.btn_tu').index(this);
	})
	uploader.on( 'fileQueued', function( file ) {
		console.log(file)
	    var $li = $(
	            '<div id="' + file.id + '" class="file-item thumbnail">' +
	                '<img>' +
	                '<div class="info" style="display:none">' + file.name + '</div>' +
	            '</div>'
	            ),
	        $img = $li.find('img');
	    // $list为容器jQuery实例
	    $('.btn_tu').eq(l).html( $li );
	    // 创建缩略图
	    // 如果为非图片文件，可以不用调用此方法。
	    // thumbnailWidth x thumbnailHeight 为 100 x 100
	    uploader.makeThumb( file, function( error, src ) {
	    	console.log('aaa'+file.name)
	        if ( error ) {
	            $img.replaceWith('<span>不能预览</span>');
	            return;
	        }
	        $img.attr( 'src', src );
	    });
	});
	// uploader.on( 'uploadError', function( file ) {
	//     var $li = $( '#'+file.id ),
	//         $error = $li.find('div.error');
	//     // 避免重复创建
	//     // if ( !$error.length ) {
	//     //     $error = $('<div class="error"></div>').appendTo( $li );
	//     // }

	//     $error.text('上传失败');
	// });
	uploader.on( 'uploadSuccess', function( file, response ) {
			$('.path_tu_8').eq(l).val( response.name );
	});
	}
</script>
</body>
</html>


<?php
	public function upload_tu()
    {
        $this -> ajax_upload('/Upload/image/');
    }
    function ajax_upload($path='file',$format='empty',$maxSize='52428800'){
    ini_set('max_execution_time', '0');
    // 去除两边的/
    $path=trim($path,'/');
    // 添加Upload根目录
    $path=strtolower(substr($path, 0,6))==='upload' ? ucfirst($path) : 'Upload/'.$path;
    // 上传文件类型控制
    $ext_arr= array(
            'image' => array('gif', 'jpg', 'jpeg', 'png', 'bmp'),
            'photo' => array('jpg', 'jpeg', 'png'),
            'flash' => array('swf', 'flv'),
            'media' => array('swf', 'flv', 'mp3', 'wav', 'wma', 'wmv', 'mid', 'avi', 'mpg', 'asf', 'rm', 'rmvb'),
            'file' => array('doc', 'docx', 'xls', 'xlsx', 'ppt', 'htm', 'html', 'txt', 'zip', 'rar', 'gz', 'bz2','pdf')
        );
    if(!empty($_FILES)){
        // 上传文件配置
        $config=array(
                'maxSize'   =>  $maxSize,               // 上传文件最大为50M
                'rootPath'  =>  './',                   // 文件上传保存的根路径
                'savePath'  =>  './'.$path.'/',         // 文件上传的保存路径（相对于根路径）
                'saveName'  =>  array('uniqid',''),     // 上传文件的保存规则，支持数组和字符串方式定义
                'autoSub'   =>  true,                   // 自动使用子目录保存上传文件 默认为true
                'exts'      =>    isset($ext_arr[$format])?$ext_arr[$format]:'',
            );
        // 实例化上传
        $upload=new \Think\Upload($config);
        // 调用上传方法
        $info=$upload->upload();
        $data=array();
        if(!$info){
            // 返回错误信息
            
            $error=$upload->getError();
            $data['error_info']=$error;
            echo json_encode($data);
        }else{
            // 返回成功信息
           
            foreach($info as $file){
            	// echo 2;
                $data['name']=trim($file['savepath'].$file['savename'],'.');
                echo json_encode($data);

            }
        }

    }
}
?>
