<?php
//by jellycheng


class PhpQRCode{
    
	//processing form input
	//remember to sanitize user input in real-life solution !!!
	private $errorCorrectionLevel = 'H';		// L M Q H
	
	private $matrixPointSize = 3;				// 1 2 3 4 5 6 7 8 9 10
	
	//二维码内容-自定义内容
	private $date = 'CUSTOM_MALL';
	
	//png图保存目录位置
	private $pngTempDir		= '';
	//图片文件名
	private $pngTempName    = '';
    
	/**
	 * 设置
	 */
	public function set($key,$value){
		$this->$key = $value;
	}
	
	public function __construct() {
	    include_once __DIR__ . "/qrlib.php";
	}
	
    public function init(){
	    //of course we need rights to create temp dir
	    if (!file_exists($this->pngTempDir)) {
	        mkdir($this->pngTempDir, 0775, true);
	    }
	
		if ($this->pngTempName != '') {
            $filename = $this->pngTempDir . $this->pngTempName;
        } else {
           $filename = $this->pngTempDir . 'test' . md5($this->date.'|'.$this->errorCorrectionLevel.'|'.$this->matrixPointSize).'.png';
        }
	    if ($this->date != 'CUSTOM_MALL') {//自定义内容
	        //生成本地二维码图片文件
	        QRcode::png($this->date, $filename, $this->errorCorrectionLevel, $this->matrixPointSize, 2);
            if((defined('OSS_ENABLE') && OSS_ENABLE) || (defined('QINIU_ENABLE') && QINIU_ENABLE)){//把本地文件 上传到远程上传服务（如阿里云、七牛）
                //qiniu_uploaded_file('data/upload/' . ATTACH_STORE . '/' .$_SESSION['vid']. '/' . $this->pngTempName, $filename);
            }
	    } else {//生成本地二维码图片文件
	        QRcode::png('http://www.qianguopai.com', $filename, $this->errorCorrectionLevel, $this->matrixPointSize, 2);
	    }
	    //仅返回文件名
	    return basename($filename);
	    //各阶段消费时间
	    //QRtools::timeBenchmark();    
	}
}
