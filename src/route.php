<?php

// TODO: ルーティング作ってFirstRoute
switch ($mode) {
    case 'admin':
        $blog_title = '管理モード ' . $blog_title;
        break;
    case 'post':
        $blog_title = '投稿モード ' . $blog_title;
        break;
    default:
        break;
}