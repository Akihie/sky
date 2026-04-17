<div id="secondary">
    <section class="widget">
        <form id="search" method="post" action="./">
            <input type="text" name="s" class="text" placeholder="搜索..." />
            <button type="submit" class="submit icon-search"></button>
        </form>
    </section>
    <?php if (!empty($this->options->sidebarBlock) && in_array('ShowRecentPosts', $this->options->sidebarBlock)): ?>
    <section class="widget">
        <h3 class="widget-title"><?php _e('最新文章'); ?></h3>
        <ul class="widget-list">
            <?php
            $db = \Typecho\Db::get();
            $customMids = [2, 3, 4, 5, 6];
            $rels = $db->fetchAll($db->select('cid')->from('typecho_relationships')
                ->where('mid IN ('.implode(',', $customMids).')'));
            $customCids = array_column($rels, 'cid');
            $recent = $db->fetchAll($db->select('cid, title, slug')
                ->from('typecho_contents')
                ->where('type=?', 'post')
                ->where('status=?', 'publish')
                ->order('created', \Typecho\Db::SORT_DESC)
                ->limit(10));
            $recent = array_filter($recent, function($p) use ($customCids) {
                return !in_array($p['cid'], $customCids);
            });
            foreach (array_slice($recent, 0, 10) as $p):
                $slug = !empty($p['slug']) ? $p['slug'] : $p['cid'];
                $url = '/index.php/'.$slug.'.html';
            ?>
            <li><a href="<?php echo $url; ?>"><?php echo htmlspecialchars($p['title']); ?></a></li>
            <?php endforeach; ?>
        </ul>
    </section>
    <?php endif; ?>

    <?php if (!empty($this->options->sidebarBlock) && in_array('ShowCategory', $this->options->sidebarBlock)): ?>
    <section class="widget">
        <h3 class="widget-title"><?php _e('分类'); ?></h3>
        <ul class="widget-list">
            <?php $this->widget('Widget_Metas_Category_List')
            ->parse('<li><a href="{permalink}">{name}</a> ({count})</li>'); ?>
        </ul>
    </section>
    <?php endif; ?>

    <?php if (!empty($this->options->sidebarBlock) && in_array('ShowOther', $this->options->sidebarBlock)): ?>
    <section class="widget">
        <h3 class="widget-title"><?php _e('其它'); ?></h3>
        <ul class="widget-list">
            <li><a href="<?php $this->options->feedUrl(); ?>"><?php _e('文章 RSS'); ?></a></li>
            <li><a href="<?php $this->options->commentsFeedUrl(); ?>"><?php _e('评论 RSS'); ?></a></li>
        </ul>
    </section>
    <?php endif; ?>
</div>
