<?php $this->need('header.php'); ?>
<div class="col-8" id="main">
	<div class="res-cons">
        <h3 class="archive-title"><?php $this->archiveTitle(array(
            'category'  =>  _t('分类 %s 下的文章'),
            'search'    =>  _t('包含关键字 %s 的文章'),
            'tag'       =>  _t('标签 %s 下的文章'),
            'author'    =>  _t('%s 发布的文章')
        ), '', ''); ?>
		</h3>
        <?php if ($this->have()): ?>
    	<?php while($this->next()): ?>
            <article class="post-card">
				<?php
					$firstImage = getFirstImage($this);
					$hasImage = !empty($firstImage);
				?>
				<?php if ($hasImage): ?>
				<div class="post-list-thumb">
					<img src="<?php echo $firstImage; ?>" alt="<?php $this->title(); ?>" />
				</div>
				<?php endif; ?>
				<div class="post-list-content<?php if (!$hasImage) echo ' post-list-content-full'; ?>">
					<header>
						<h2 class="post-title">
							<a href="<?php $this->permalink() ?>"><?php $this->title() ?></a>
						</h2>
					</header>
					<div class="post-content">
						<?php $this->excerpt(200, '...'); ?>
						<a href="<?php echo $this->permalink(); ?>" class="read-more">阅读全文>></a>
					</div>
    			</div>
    		</article>
		<?php endwhile; ?>
        <?php else: ?>
            <article class="post">
                <h2 class="post-title"><?php _e('没有找到内容'); ?></h2>
            </article>
        <?php endif; ?>
        <?php $this->pageNav('&laquo; 前一页', '后一页 &raquo;'); ?>
	</div>
</div>
<?php $this->need('sidebar.php'); ?>
<?php $this->need('footer.php'); ?>
