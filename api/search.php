<?php

/**
 * 图片搜索接口
 */
$keyword = $_GET['keyword'] ?? $_POST['keyword'] ?? '';
if (!$keyword) {
    print_json(null, 500, '参数 keyword 缺失');
}
$page = $_GET['page'] ?? $_POST['page'] ?? 0;
$page_size = $_GET['page_size'] ?? $_POST['page_size'] ?? 40;
$start = $page * $page_size;
$url = 'https://vt.sm.cn/api/pic/list?query=' . urlencode($keyword) . '&start=' . $start . '&limit=' . $page_size;
$response = file_get_contents($url, false, stream_context_create(['http' => ['header' => 'User-Agent: apee']]));
$data = json_decode($response, true);
$img_list = $data['data']['hit']['imgInfo']['item'] ?? [];
$out_data = array_map(function ($item) {
    return [
        'small' => $item['img'],
        'big' => $item['bigPicCache'],
        'title' => $item['title']
    ];
}, $img_list);
print_json($out_data, 200, '获取成功');

function print_json($data, $code, $msg)
{
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode([
        'data' => $data,
        'code' => $code,
        'msg' => $msg
    ]);
    exit();
}
