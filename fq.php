<?php
header("Content-Type:text/html;charset=gb2312");
/**
 * ���÷�ʽ�Ƚϼ�: echo getcontent($url); ����ץȡ��ҳ/Ҳ����ץȡͼƬ
 * ����ץȡ��ʱ����file_get_contents���ܻ����������cpu���ڴ�, �� curl��ʽ�򲻻�, ���һ�����αװ��׷�ֹ����ץȡ�����ַ�ip.
 * Author: Tekin  http://dev.yunnan.ws
 */
function getcontent($weburl)
{
	$user_IP = ($_SERVER["HTTP_VIA"]) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : $_SERVER["REMOTE_ADDR"];
	$user_IP = ($user_IP) ? $user_IP : $_SERVER["REMOTE_ADDR"];
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_HEADER, true); // ����HTTPͷ
	curl_setopt($curl, CURLOPT_TIMEOUT, 40);
	curl_setopt($curl, CURLOPT_URL, $weburl);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:' . $user_IP, 'CLIENT-IP:' . $user_IP)); //αװIPΪ�û�IP
	curl_setopt($curl, CURLOPT_REFERER, 'http://www.google.com'); //αװһ����·
	curl_setopt($curl, CURLOPT_USERAGENT, 'Baiduspider+(+http://www.google.com/search/spider.htm)'); //αװ�ɰٶ�֩�� 
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); //ץȡת��
	curl_setopt($curl, CURLOPT_BINARYTRANSFER, true) ;
	curl_setopt($curl, CURLOPT_ENCODING, 'gzip,deflate'); //gzip��ѹ
	curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322; .NET CLR 2.0.50727)");
	$data = curl_exec($curl);
	// $infos = (curl_getinfo($curl));//����ץȡ��ҳ������ֵ(����);
	curl_close($curl);
	return $data;
}
	$furl = 'http://dongtaiwang.com/loc/download.php';
	$udata = @getcontent($furl);
	$fgpattern = "|<div id=\"image_center\" align=\"center\"><a href=\"([^\s]*)\"\sclass=\"download\" target=\"_blank\">�������(.*?)</a></div>|";
	preg_match_all($fgpattern, $udata, $fgarr); //ʹ������ƥ������href=

    $title = $fgarr[2][0];
    $fgdurl = 'http://dongtaiwang.com'.$fgarr[1][0];
	echo '<pre><BR>FG���°汾:' . $title ;
    echo '<BR>���ص�ַ:' . $fgdurl .'</pre>';

//�ŵ��κ�֧��PHP curl�Ŀռ伴��ֱ������FQ����
if ($fgdurl ) {
      $content= @getcontent($fgdurl);
        header('cache-control:public');
        header('content-type:application/octet-stream');
        header('content-disposition:attachment; filename='.basename($fgdurl ));
        echo $content;
}
?>