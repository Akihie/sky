<?php

function themeConfig($form) {
    $sidebarBlock = new Typecho_Widget_Helper_Form_Element_Checkbox('sidebarBlock',
    array('ShowRecentPosts' => _t('显示最新文章'),
    'ShowCategory' => _t('显示分类'),
    'ShowOther' => _t('显示其它杂项')),

    array('ShowRecentPosts', 'ShowCategory', 'ShowOther'), _t('侧边栏显示'));

    $form->addInput($sidebarBlock->multiMode());
}

// 主题启用时自动创建所需的分类
function themeOnActivated($installTheme) {
    if ($installTheme !== 'sky') return;

    $db = Typecho\Db::get();

    // 所需分类：slug => name
    $categories = [
        'devices'   => '设备',
        'footprint' => '足迹',
        'moments'   => '碎碎念',
        'photo'     => '摄影',
        'timeline'  => '时间线',
    ];

    foreach ($categories as $slug => $name) {
        $exist = $db->fetchRow($db->select('mid')->from('table.metas')
            ->where('slug = ? AND type = ?', $slug, 'category'));
        if (empty($exist)) {
            $db->query($db->insert('table.metas')
                ->rows(['name' => $name, 'slug' => $slug, 'type' => 'category', 'count' => 0, 'order' => 0]));
        }
    }
}

// 按 slug 获取分类 mid，找不到返回 null
function getCategoryMidBySlug($slug) {
    static $cache = [];
    if (isset($cache[$slug])) return $cache[$slug];
    $db = \Typecho\Db::get();
    $row = $db->fetchRow($db->select('mid')->from('table.metas')
        ->where('slug = ? AND type = ?', $slug, 'category'));
    $cache[$slug] = !empty($row) ? intval($row['mid']) : null;
    return $cache[$slug];
}

// 获取文章第一张图片
function getFirstImage($post, $size = '') {
    if (isset($post->fields->thumb) && !empty($post->fields->thumb)) {
        return $post->fields->thumb;
    }
    preg_match('/<img[^>]*src=["\']([^"\']+)["\'][^>]*>/i', $post->content, $matches);
    if (!empty($matches[1])) {
        return $matches[1];
    }
    return '';
}
