<?php    
/**  
 * 时间线页面模板
 * @package custom   
 */    
$this->need('header.php');
?>
<div class="col-8" id="main">
    <div class="res-cons">
        <article class="post">
            <header>
                <h1 class="post-title"><?php $this->title() ?></h1>
            </header>
            <div class="post-content">
                <?php $this->content(); ?>
            </div>
        </article>
        
        <div class="timeline-container">
            <?php 
            $this->widget('Widget_Contents_Post_Recent', 'pageSize=100')->to($posts);
            $currentYear = 0;
            $currentMonth = 0;
            ?>
            
            <?php while($posts->next()): ?>
                <?php 
                $postYear = date('Y', $posts->created);
                $postMonth = date('m', $posts->created);
                $postDay = date('d', $posts->created);
                ?>
                
                <?php if ($currentYear != $postYear): ?>
                    <?php if ($currentYear > 0): ?>
                        </div>
                    <?php endif; ?>
                    <div class="timeline-year-section">
                        <div class="timeline-year"><?php echo $postYear; ?></div>
                        <div class="timeline-items">
                <?php 
                $currentYear = $postYear;
                $currentMonth = 0;
                endif; 
                ?>
                
                <div class="timeline-item">
                    <div class="timeline-dot"></div>
                    <div class="timeline-date">
                        <span class="month"><?php echo $postMonth; ?>月</span>
                        <span class="day"><?php echo $postDay; ?>日</span>
                    </div>
                    <div class="timeline-content">
                        <a href="<?php $posts->permalink(); ?>" class="timeline-title"><?php $posts->title(); ?></a>
                        <div class="timeline-excerpt"><?php $posts->excerpt(80, '...'); ?></div>
                    </div>
                </div>
            <?php endwhile; ?>
            
            <?php if ($currentYear > 0): ?>
                        </div>
                    </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.timeline-container {
    margin-top: 32px;
}

.timeline-year-section {
    margin-bottom: 40px;
}

.timeline-year {
    font-size: 2rem;
    font-weight: 800;
    color: var(--primary);
    margin-bottom: 24px;
    padding-bottom: 12px;
    border-bottom: 2px solid var(--border);
    position: relative;
}

.timeline-year::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 60px;
    height: 2px;
    background: linear-gradient(90deg, var(--primary), var(--primary-light));
}

.timeline-items {
    position: relative;
    padding-left: 32px;
}

.timeline-items::before {
    content: '';
    position: absolute;
    left: 8px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: linear-gradient(180deg, var(--primary), var(--border));
    border-radius: 2px;
}

.timeline-item {
    position: relative;
    display: flex;
    gap: 20px;
    padding: 16px 0;
    transition: var(--transition);
}

.timeline-item:hover .timeline-dot {
    transform: scale(1.3);
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.2);
}

.timeline-dot {
    position: absolute;
    left: -28px;
    top: 20px;
    width: 14px;
    height: 14px;
    background: var(--primary);
    border-radius: 50%;
    border: 3px solid var(--bg-card);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
    transition: var(--transition);
    z-index: 1;
}

.timeline-date {
    flex-shrink: 0;
    width: 60px;
    text-align: right;
    padding-top: 2px;
}

.timeline-date .month {
    display: block;
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--text-secondary);
}

.timeline-date .day {
    display: block;
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text-main);
    line-height: 1.2;
}

.timeline-content {
    flex: 1;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 16px 20px;
    transition: var(--transition);
}

.timeline-item:hover .timeline-content {
    border-color: var(--primary);
    box-shadow: var(--shadow);
}

.timeline-title {
    display: block;
    font-size: 1.0625rem;
    font-weight: 600;
    color: var(--text-main);
    margin-bottom: 6px;
    line-height: 1.4;
}

.timeline-title:hover {
    color: var(--primary);
}

.timeline-excerpt {
    font-size: 0.875rem;
    color: var(--text-muted);
    line-height: 1.6;
}

/* 响应式 */
@media (max-width: 640px) {
    .timeline-year {
        font-size: 1.5rem;
    }
    
    .timeline-items {
        padding-left: 24px;
    }
    
    .timeline-items::before {
        left: 6px;
    }
    
    .timeline-dot {
        left: -20px;
        width: 12px;
        height: 12px;
    }
    
    .timeline-date {
        width: 50px;
    }
    
    .timeline-date .day {
        font-size: 1.125rem;
    }
    
    .timeline-content {
        padding: 12px 14px;
    }
    
    .timeline-title {
        font-size: 0.9375rem;
    }
}
</style>

<?php $this->need('sidebar.php'); ?>
<?php $this->need('footer.php'); ?>
