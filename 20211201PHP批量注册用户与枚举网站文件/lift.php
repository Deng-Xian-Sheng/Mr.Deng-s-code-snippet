<?

/**
 * 穷举文件名
 */
ini_set("memory_limit", "-1"); //不限制内存占用
function get($url)
{
    @file_get_contents($url);
    $http_response_header[0]; //获取状态码
    $pattern_1 = "/([0-9]{3})/isU";
    preg_match_all($pattern_1, $http_response_header[0], $status_code, PREG_SET_ORDER);
    $status_code = $status_code[0][1];
    if ($status_code == 301 || $status_code == 302 || $status_code == 200 || $status_code == 303 || $status_code == 307 || $status_code == 206) {
        return true;
    }
    return false;
}
function start()
{
    $passwd = file('passwd.txt', FILE_IGNORE_NEW_LINES);
    if ($passwd == false) {
        exit('读取passwd错误');
    }
    $count = 0;
    for (;;) {
        $lift_next_value_2 = file_get_contents('lift-next-value.txt');
        if ($lift_next_value_2 === false) {
        } else {
            $count = $lift_next_value_2;
        }
        if ($count <= count($passwd)) {
        } else {
            break;
        }
        $count++;
        if (get('http://xdxw5.xyz/videofiles/' . $passwd[$count] . '.mp4') == true) {
            $lift_file_list = fopen('lift-file-list.txt', 'a+'); //穷举出的文件列表
            fwrite($lift_file_list, 'http://xdxw5.xyz/videofiles/' . $passwd[$count] . '.mp4');
            fclose($lift_file_list);
        }
        if (file_exists('lift-next-value.txt')) {
            unlink('lift-next-value.txt');
        }
        $lift_next_value = fopen('lift-next-value.txt', 'w+'); //写进度
        fwrite($lift_next_value, $count);
        fclose($lift_next_value);
    }
}
start();
