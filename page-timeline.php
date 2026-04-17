<?php
/**
 * 时间线页面模板 - 汇总所有分类文章
 * @package custom
 */
$this->need('header.php');
?>
<div class="col-8" id="main">
    <div class="res-cons">
        <article class="post">
            <header>
                <h1 class="post-title"><?php $this->title(); ?></h1>
            </header>
            <div class="post-content">
                <?php $this->content(); ?>
            </div>
        </article>

        <div class="timeline-container">
            <?php
            $db = \Typecho\Db::get();

            // 读取所有已发布文章
            $rows = $db->fetchAll($db->select('c.cid, c.title, c.created, c.text, r.mid')
                ->from('table.contents c')
                ->join('table.relationships r', 'c.cid = r.cid', 'left')
                ->where('c.type = ?', 'post')
                ->where('c.status = ?', 'publish'));

            // 读取所有分类
            $allMetas = $db->fetchAll($db->select('mid, name, slug')->from('table.metas')->where('type=?', 'category'));
            $metaMap = [];
            foreach ($allMetas as $m) { $metaMap[$m['mid']] = $m; }

            // 读取自定义字段
            $fieldsMap = [];
            if (count($rows) > 0) {
                $allFields = $db->fetchAll($db->select()->from('table.fields'));
                $cids = array_column($rows, 'cid');
                foreach ($allFields as $f) {
                    if (in_array($f['cid'], $cids)) {
                        $fieldsMap[$f['cid']][$f['name']] = $f['str_value'];
                    }
                }
            }

            $posts = [];
            foreach ($rows as $row) {
                $cid = $row['cid'];
                $mid = intval($row['mid']);
                $meta = isset($metaMap[$mid]) ? $metaMap[$mid] : null;
                $row['category'] = $meta ? $meta['name'] : '';
                $row['category_slug'] = $meta ? $meta['slug'] : '';
                // 优先用封面图字段，其次内容第一张图
                $row['cover'] = isset($fieldsMap[$cid]['img']) && !empty($fieldsMap[$cid]['img'])
                    ? $fieldsMap[$cid]['img']
                    : (isset($fieldsMap[$cid]['cover']) && !empty($fieldsMap[$cid]['cover'])
                        ? $fieldsMap[$cid]['cover']
                        : '');
                $morePos = strpos($row['text'], '<!--more-->');
                $row['excerpt'] = $morePos !== false ? substr(strip_tags($row['text']), 0, $morePos) : mb_substr(strip_tags($row['text']), 0, 120);
                $row['year'] = date('Y', $row['created']);
                $row['month'] = date('m', $row['created']);
                $row['day'] = date('d', $row['created']);
                $posts[] = $row;
            }

            // 按时间倒序
            usort($posts, function($a, $b) { return $b['created'] - $a['created']; });
            $hasAny = count($posts) > 0;
            $currentYear = 0;
            ?>

            <?php if ($hasAny): ?>

            <?php foreach ($posts as $item): ?>
                <?php if ($item['year'] != $currentYear): ?>
                    <?php if ($currentYear > 0): ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    <div class="timeline-year-section">
                        <div class="timeline-year"><?php echo $item['year']; ?></div>
                        <div class="timeline-items">
                <?php $currentYear = $item['year'];
                endif; ?>

                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="timeline-date">
                        <span class="month"><?php echo $item['month']; ?>月</span>
                        <span class="day"><?php echo $item['day']; ?>日</span>
                    </div>
                    <div class="timeline-content">
                        <div class="content-inner">
                            <?php if (!empty($item['category'])): ?>
                            <span class="timeline-cat"><?php echo htmlspecialchars($item['category']); ?></span>
                            <?php endif; ?>
                            <span class="timeline-title"><?php echo htmlspecialchars($item['title']); ?></span>
                            <?php if (!empty($item['excerpt'])): ?>
                            <div class="timeline-excerpt"><?php echo htmlspecialchars($item['excerpt']); ?></div>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($item['cover'])): ?>
                        <div class="timeline-cover">
                            <img src="<?php echo htmlspecialchars($item['cover']); ?>" alt="" loading="lazy" />
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php if ($currentYear > 0): ?>
                        </div>
                    </div>
            <?php endif; ?>

            <?php else: ?>
            <div class="empty-tip">
                <p>暂无文章</p>
                <p>发表文章后将在此页面展示</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.timeline-container { margin-top: 32px; }
.timeline-year-section { margin-bottom: 40px; }
.timeline-year { font-size: 2rem; font-weight: 800; color: #3b82f6; margin-bottom: 24px; padding-bottom: 12px; border-bottom: 2px solid #e5e7eb; position: relative; }
.timeline-year::after { content: ''; position: absolute; bottom: -2px; left: 0; width: 60px; height: 2px; background: linear-gradient(90deg, #3b82f6, #60a5fa); }
.timeline-items { position: relative; padding-left: 32px; }
.timeline-items::before { content: ''; position: absolute; left: 8px; top: 0; bottom: 0; width: 2px; background: linear-gradient(180deg, #3b82f6, #e5e7eb); border-radius: 2px; }
.timeline-item { position: relative; display: flex; gap: 20px; padding: 16px 0; transition: transform 0.2s; align-items: flex-start; }
.timeline-item:hover { transform: translateX(4px); }
.timeline-dot { position: absolute; left: -28px; top: 20px; width: 14px; height: 14px; background: #3b82f6; border-radius: 50%; border: 3px solid #fff; box-shadow: 0 0 0 3px rgba(59,130,246,0.3); transition: transform 0.2s; z-index: 1; }
.timeline-item:hover .timeline-dot { transform: scale(1.3); box-shadow: 0 0 0 4px rgba(59,130,246,0.2); }
.timeline-date { flex-shrink: 0; width: 60px; text-align: right; padding-top: 2px; }
.timeline-date .month { display: block; font-size: 0.875rem; font-weight: 600; color: #6b7280; }
.timeline-date .day { display: block; font-size: 1.25rem; font-weight: 700; color: #1f2937; line-height: 1.2; }
.timeline-content { flex: 1; display: flex; background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden; transition: border-color 0.2s, box-shadow 0.2s; }
.timeline-item:hover .timeline-content { border-color: #3b82f6; box-shadow: 0 4px 12px rgba(59,130,246,0.1); }
.content-inner { flex: 1; padding: 14px 18px; }
.timeline-cover { width: 100px; flex-shrink: 0; overflow: hidden; background: #f3f4f6; align-self: stretch; }
.timeline-cover img { width: 100%; height: 100%; object-fit: cover; }
.timeline-cat { display: inline-block; font-size: 0.6875rem; font-weight: 600; padding: 2px 8px; border-radius: 999px; background: #f3f4f6; color: #6b7280; margin-bottom: 6px; }
.timeline-title { display: block; font-size: 1.0625rem; font-weight: 600; color: #1f2937; margin-bottom: 4px; line-height: 1.4; }
.timeline-excerpt { font-size: 0.875rem; color: #6b7280; line-height: 1.6; }
.empty-tip { text-align: center; padding: 48px; color: #6b7280; }
.empty-tip p { margin: 8px 0; }
@media (max-width: 640px) {
    .timeline-year { font-size: 1.5rem; }
    .timeline-items { padding-left: 24px; }
    .timeline-items::before { left: 6px; }
    .timeline-dot { left: -20px; width: 12px; height: 12px; }
    .timeline-date { width: 50px; }
    .timeline-date .day { font-size: 1.125rem; }
    .timeline-content { padding: 12px 14px; }
    .timeline-title { font-size: 0.9375rem; }
    .timeline-cover { width: 80px; }
}
</style>

<?php $this->need('sidebar.php'); ?>
<?php $this->need('footer.php'); ?>
