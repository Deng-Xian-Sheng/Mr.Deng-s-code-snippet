<?php
for ($count_1 = 1; $count_1 <= 30;) {
    /**
     * 获取代理IP，付费API方法
     */
    /*
    $url_4 = 'http://kuyukuyu.com/api/projects/get?uuid=8fa2e136-c847-48fb-b972-0bd16c33a97e'; //此代理ip获取地址并非永久有效
    $agent_1 = file_get_contents($url_4);
    //判断是否有错误
    if ($agent_1 == false) {
        error_log("\n*获取代理IP请求错误：" . $agent_1 . "*\n", 3, './log.txt');
    }
    $pattern_2 = "/([0-9]*\.[0-9]*\.[0-9]*\.[0-9]*)\:/isU";
    preg_match_all($pattern_2, $agent_1, $agent_ip_1, PREG_SET_ORDER); //值： echo $agent_ip_1[0][1];
    $pattern_3 = "/[0-9]*\.[0-9]*\.[0-9]*\.[0-9]*\:([0-9]*$)/isU";
    preg_match_all($pattern_3, $agent_1, $agent_port_1, PREG_SET_ORDER); //值： echo $agent_port_1[0][1];
    $agent_ip_1 = $agent_ip_1[0][1]; //补充
    $agent_port_1 = $agent_port_1[0][1];
    */

    /**
     * 设置代理IP,云函数方法
     */
    $agent_ip_1 = '127.0.0.1';
    $agent_port_1 = '2009'; //端口

    $id_1 = '132695'; //推广id
    /*
    获取reg.asp返回的数据，下一步要用；GET请求
    */
    $url_1 = 'http://xdxw5.xyz/reg.asp?opration=reg'; //请求地址
    $cookies = 'Origin=xdxw5%2Exyz; Ref=Blank; letwego=visiter=man; propagate=' . $id_1 . ';'; //搁浏览器上粘过来的Cookie
    $ch = curl_init(); //初始化cURL
    curl_setopt($ch, CURLOPT_URL, $url_1); //抓取指定网页
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //要求结果为字符串
    curl_setopt($ch, CURLOPT_HEADER, 0); //设置header
    curl_setopt($ch, CURLOPT_COOKIE, $cookies); // 带上COOKIE请求
    $reg_asp = curl_exec($ch); //执行并获得HTML内容
    // 检查是否有错误发生
    if (curl_errno($ch) != 0) {
        error_log("\n*reg.asp请求错误：" . curl_error($ch) . "*\n", 3, './log.txt');
    }
    curl_close($ch); //释放cURL句柄

    /*通过正则筛选出我们需要的参数*/
    $pattern_1 = "/uShield\:\s\'(.*)\'/isU";
    preg_match_all($pattern_1, $reg_asp, $uShield_value, PREG_SET_ORDER); // 值为：echo $uShield_value[0][1];

    /*
    带上刚刚获得的参数，发送GET请求，让平台乖乖注册用户。
    GET请求示例：http://xdxw5.xyz/Operate.asp?uName=demo3&uPass=demo3&uEmail=demo3&uPropagate=131859&uShield=902e0&optiontype=3
    为了让它显得更真实，我们想办法找一些英文单词；充当用户名
    我找到了金山词霸的免费开放API“每日一句”，依据日期获得一句美妙的英文
    GET请求示例：http://open.iciba.com/dsapi/?date=2021-11-27
    */

    /*
    * php生成某个范围内的随机时间 
    * @param $begintime  起始时间 格式为 Y-m-d
    * @param $endtime    结束时间 格式为 Y-m-d 
    * @param $is         是否是时间戳 格式为 Boolean  
    * 飞鸟慕鱼博客
    * http://www.feiniaomy.com 
    */
    $begintime = 20181127;
    $endtime = date('Ymd', time());
    $is = true;
    $begin = strtotime($begintime);
    $end = $endtime == "" ? mktime() : strtotime($endtime);
    $timestamp = rand($begin, $end);
    $time_rand = $is ? date("Y-m-d", $timestamp) : $timestamp;

    /**
     * 带上随机时间，请求“每日一句”API
     */
    $url_2 = 'http://open.iciba.com/dsapi/?date=' . $time_rand;
    $english_1 = file_get_contents($url_2);
    //判断是否有错误
    if ($english_1 == false) {
        error_log("*每日一句请求错误：" . $english_1 . "*", 3, './log.txt');
    }
    $english_1 = json_decode($english_1, true); //将获得的数据解json
    $english_1_array = explode(' ', $english_1['content']); //依据空格将句子分割为单词，并保存到数组
    $english_1_string = $english_1_array[mt_rand(0, count($english_1_array) - 1)]; //根据数组下标，随机获取单词

    /**
     * 去除标点符号
     */
    $Symbol = array(0 => '.', 1 => '?', 2 => '!', 3 => ',', 4 => ':', 5 => '...', 6 => ';', 7 => '-', 8 => '–', 9 => '—', 10 => '(', 11 => ')', 12 => '[', 13 => ']', 14 => '{', 15 => '}', 16 => '"', 17 => '\'', 18 => '`', 19 => '~', 20 => '%', 21 => '$', 22 => '^', 23 => '&', 24 => '*', 25 => '+', 26 => '=');
    foreach ($Symbol as $x) {
        $english_1_string =  str_replace($x, '', $english_1_string); //遍历数组替换标点符号为空字符串
    }

    $passwd_1 = substr(md5(time()), 0, 8); //生成随机密码
    $email_1 = mt_rand(123456, 1000000000) . '@qq.com'; //生成随机邮箱

    /**
     * 万事大吉，开始请求
     */
    $url_3 = 'http://xdxw5.xyz/Operate.asp?uName=' . $english_1_string . '&uPass=' . $passwd_1 . '&uEmail=' . $email_1 . '&uPropagate=' . $id_1 . '&uShield=' . $uShield_value[0][1] . '&optiontype=3'; //请求地址

    $cookies_2 = 'Origin=xdxw5%2Exyz; Ref=Blank; letwego=visiter=man; propagate= ' . $id_1 . ';'; //搁浏览器上粘过来的Cookie
    $ch_2 = curl_init(); //初始化cURL
    curl_setopt($ch_2, CURLOPT_URL, $url_3); //抓取指定网页
    curl_setopt($ch_2, CURLOPT_RETURNTRANSFER, 1); //要求结果为字符串
    curl_setopt($ch_2, CURLOPT_HEADER, 0); //设置header
    curl_setopt($ch_2, CURLOPT_COOKIE, $cookies_2); // 带上COOKIE请求
    curl_setopt($ch_2, CURLOPT_PROXY, $agent_ip_1); //代理服务器地址
    curl_setopt($ch_2, CURLOPT_PROXYPORT, $agent_port_1); //代理服务器端口
    $stauts_code = curl_exec($ch_2); //执行并获得HTML内容
    // 检查是否有错误发生
    if (curl_errno($ch_2) != 0) {
        error_log("\n*注册用户请求错误：" . curl_error($ch_2) . "*\n", 3, './log.txt');
    }
    curl_close($ch_2); //释放cURL句柄

    /**
     * 写入日志
     */
    $date_time1 = new DateTime();
    $log_string = $date_time1->format('Y-m-d H:i:s:v') . "\n" . '代理IP：' . $agent_ip_1 . ':' . $agent_port_1 . "\n" . '用户名：' . $english_1_string . "\n" . "密码：" . $passwd_1 . "\n" . "邮箱：" . $email_1 . "\n" . "uShield: " . $uShield_value[0][1] . "\n" . "状态码：" . $stauts_code . "\n\n";
    $log_1 = fopen('./log.txt', 'a+'); //打开文件
    fwrite($log_1, $log_string);
    fclose($log_1); //关闭文件

    if ($stauts_code == 1) {
        $count_1 += 1;
    }
    /**
     * 状态码：
     * 1   注册成功
     * 911 操作超时，uShield过期
     * 0   此ip已经注册
     */
}
