<?php
/**
 * 简单的响应式模板 - 首页过滤版
 *
 * @package sky
 * @author mrdou
 * @version 1.4
 */
$this->need('header.php');

// ========== 核心：过滤掉自定义分类的文章 ==========
$db = \Typecho\Db::get();
$customMids = array_filter(array_map(function($slug) { return getCategoryMidBySlug($slug); }, ['devices', 'footprint', 'moments', 'photo', 'timeline']));

// 查出所有自定义分类文章的 cid
$rels = $db->fetchAll($db->select('cid')->from('typecho_relationships')
    ->where('mid IN ('.implode(',', $customMids).')'));
$customCids = array_column($rels, 'cid');

// 查出所有已发布文章，过滤掉自定义分类
$allPosts = $db->fetchAll($db->select('cid', 'title', 'slug', 'created', 'text')
    ->from('typecho_contents')
    ->where('type=?', 'post')
    ->where('status=?', 'publish')
    ->order('created', \Typecho\Db::SORT_DESC));

$homePosts = array_filter($allPosts, function($p) use ($customCids) {
    return !in_array($p['cid'], $customCids);
});
$homePosts = array_values($homePosts);
$total = count($homePosts);

// ========== 分页 ==========
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$pageSize = 10;
$offset = ($page - 1) * $pageSize;
$pagePosts = array_slice($homePosts, $offset, $pageSize);
$totalPages = max(1, ceil($total / $pageSize));

// 生成 pageNav HTML
$pageNav = '';
if ($total > $pageSize) {
    $prevDisabled = $page <= 1 ? '' : '<a href="?page='.($page-1).'" class="prev">&laquo; Previous</a>';
    $nextDisabled = $page >= $totalPages ? '' : '<a href="?page='.($page+1).'" class="next">Next &raquo;</a>';
    $pageNav = '<div class="page-nav">'.$prevDisabled.'<span class="page-numbers">'.$page.' / '.$totalPages.'</span>'.$nextDisabled.'</div>';
}

// ========== 渲染 ==========
?>
<div class="col-8" id="main">
    <div class="res-cons">
        <?php if (empty($pagePosts)): ?>
        <article class="post-card">
            <div class="post-list-content">
                <header><h2 class="post-title">暂无文章</h2></header>
            </div>
        </article>
        <?php else: ?>
        <?php foreach ($pagePosts as $post): ?>
        <?php
            // 生成永久链接：优先用 slug，否则用 cid
            $slug = !empty($post['slug']) ? $post['slug'] : $post['cid'];
            $permalink = '/index.php/'.$slug.'.html';
            // 提取第一张图
            $thumb = '';
            if (preg_match('/<img[^>]*src=["\']([^"\']+)["\'][^>]*>/i', $post['text'], $m)) {
                $thumb = $m[1];
            }
            // 提取摘要
            $morePos = strpos($post['text'], '<!--more-->');
            $excerpt = $morePos !== false
                ? substr(strip_tags($post['text']), 0, $morePos)
                : strip_tags(substr($post['text'], 0, 200));
            $hasImage = !empty($thumb);
        ?>
        <article class="post-card">
            <?php if ($hasImage): ?>
            <div class="post-list-thumb">
                <img src="<?php echo htmlspecialchars($thumb); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" />
            </div>
            <?php endif; ?>
            <div class="post-list-content<?php if (!$hasImage) echo ' post-list-content-full'; ?>">
                <header>
                    <h2 class="post-title">
                        <a href="<?php echo $permalink; ?>"><?php echo htmlspecialchars($post['title']); ?></a>
                    </h2>
                </header>
                <date class="post-meta">
                    <?php echo date('Y年m月d日 H:i:s', $post['created']); ?>
                </date>
                <div class="post-content">
                    <?php echo htmlspecialchars($excerpt) . '...'; ?>
                </div>
            </div>
        </article>
        <?php endforeach; ?>
        <?php echo $pageNav; ?>
        <?php endif; ?>
    </div>
</div>
<?php $this->need('sidebar.php'); ?>
<?php $this->need('footer.php'); ?>
