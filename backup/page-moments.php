<?php    
/**  
 * 碎碎念页面模板
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
        
        <div class="moments-container">
            <!-- 时间线 -->
            <div class="moments-timeline">
                <!-- 2024年3月 -->
                <div class="moment-group">
                    <div class="group-date">
                        <span class="month">3月</span>
                        <span class="year">2024</span>
                    </div>
                    
                    <div class="moment-items">
                        <div class="moment-card">
                            <div class="moment-time">24日 14:32</div>
                            <div class="moment-content">
                                <p>今天天气真好，适合出去走走。阳光洒在身上，感觉整个人都暖洋洋的。🌸</p>
                            </div>
                            <div class="moment-meta">
                                <span class="location">📍 北京</span>
                                <span class="weather">☀️ 晴</span>
                            </div>
                        </div>
                        
                        <div class="moment-card with-image">
                            <div class="moment-time">20日 09:15</div>
                            <div class="moment-content">
                                <p>新买的键盘到了，手感真的很棒！敲代码的快乐又增加了。⌨️</p>
                                <div class="moment-images">
                                    <img src="https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=400" alt="keyboard">
                                </div>
                            </div>
                            <div class="moment-meta">
                                <span class="location">📍 北京</span>
                            </div>
                        </div>
                        
                        <div class="moment-card">
                            <div class="moment-time">15日 22:48</div>
                            <div class="moment-content">
                                <p>深夜写代码的时候，突然想到一个问题：为什么程序员总是喜欢在晚上工作？可能是因为夜晚安静，思路更清晰吧。🌙</p>
                            </div>
                            <div class="moment-meta">
                                <span class="mood">😴 困</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- 2024年2月 -->
                <div class="moment-group">
                    <div class="group-date">
                        <span class="month">2月</span>
                        <span class="year">2024</span>
                    </div>
                    
                    <div class="moment-items">
                        <div class="moment-card">
                            <div class="moment-time">28日 18:20</div>
                            <div class="moment-content">
                                <p>终于把博客主题更新完了，虽然还有很多细节需要调整，但整体效果还是比较满意的。继续加油！💪</p>
                            </div>
                            <div class="moment-meta">
                                <span class="location">📍 北京</span>
                                <span class="mood">😊 开心</span>
                            </div>
                        </div>
                        
                        <div class="moment-card with-image">
                            <div class="moment-time">14日 12:00</div>
                            <div class="moment-content">
                                <p>情人节快乐！给自己买了一束花，生活要有仪式感。🌹</p>
                                <div class="moment-images">
                                    <img src="https://images.unsplash.com/photo-1518709268805-4e9042af9f23?w=400" alt="flowers">
                                </div>
                            </div>
                            <div class="moment-meta">
                                <span class="mood">💕 幸福</span>
                            </div>
                        </div>
                        
                        <div class="moment-card">
                            <div class="moment-time">05日 10:30</div>
                            <div class="moment-content">
                                <p>周末在家整理书架，翻到了很多以前买的书，有些还没拆封... 今年的目标就是把它们都读完！📚</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- 2024年1月 -->
                <div class="moment-group">
                    <div class="group-date">
                        <span class="month">1月</span>
                        <span class="year">2024</span>
                    </div>
                    
                    <div class="moment-items">
                        <div class="moment-card">
                            <div class="moment-time">01日 00:00</div>
                            <div class="moment-content">
                                <p>新年快乐！🎉 2024年，希望能成为更好的自己。新的一年，新的开始！</p>
                            </div>
                            <div class="moment-meta">
                                <span class="mood">🎆 激动</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- 加载更多 -->
            <div class="load-more">
                <button class="load-more-btn">加载更多</button>
            </div>
        </div>
    </div>
</div>

<style>
.moments-container {
    margin-top: 32px;
}

.moments-timeline {
    position: relative;
}

/* 月份分组 */
.moment-group {
    margin-bottom: 40px;
}

.group-date {
    display: inline-flex;
    flex-direction: column;
    align-items: center;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: white;
    padding: 12px 24px;
    border-radius: var(--radius-lg);
    margin-bottom: 24px;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.group-date .month {
    font-size: 1.25rem;
    font-weight: 700;
    line-height: 1;
}

.group-date .year {
    font-size: 0.75rem;
    opacity: 0.8;
    margin-top: 2px;
}

/* 动态卡片 */
.moment-items {
    position: relative;
    padding-left: 32px;
}

.moment-items::before {
    content: '';
    position: absolute;
    left: 8px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: linear-gradient(180deg, var(--primary), var(--border));
    border-radius: 2px;
}

.moment-card {
    position: relative;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    padding: 20px;
    margin-bottom: 16px;
    transition: var(--transition);
}

.moment-card:hover {
    transform: translateX(4px);
    box-shadow: var(--shadow);
}

.moment-card::before {
    content: '';
    position: absolute;
    left: -28px;
    top: 24px;
    width: 12px;
    height: 12px;
    background: var(--primary);
    border-radius: 50%;
    border: 3px solid var(--bg-card);
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.3);
}

.moment-time {
    font-size: 0.8125rem;
    color: var(--text-muted);
    margin-bottom: 10px;
    font-variant-numeric: tabular-nums;
}

.moment-content p {
    margin: 0;
    color: var(--text-main);
    line-height: 1.75;
    font-size: 0.9375rem;
}

.moment-images {
    margin-top: 14px;
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 10px;
}

.moment-images img {
    width: 100%;
    height: 150px;
    object-fit: cover;
    border-radius: var(--radius);
    cursor: pointer;
    transition: var(--transition);
}

.moment-images img:hover {
    transform: scale(1.02);
}

.moment-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin-top: 14px;
    padding-top: 14px;
    border-top: 1px dashed var(--border-light);
    font-size: 0.8125rem;
    color: var(--text-muted);
}

.moment-meta span {
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

/* 加载更多 */
.load-more {
    text-align: center;
    padding: 20px 0;
}

.load-more-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 12px 32px;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius-full);
    color: var(--text-secondary);
    font-size: 0.9375rem;
    font-weight: 500;
    cursor: pointer;
    transition: var(--transition);
}

.load-more-btn:hover {
    background: var(--primary);
    border-color: var(--primary);
    color: white;
}

/* 响应式 */
@media (max-width: 640px) {
    .moment-items {
        padding-left: 24px;
    }
    
    .moment-items::before {
        left: 6px;
    }
    
    .moment-card {
        padding: 16px;
    }
    
    .moment-card::before {
        left: -22px;
        width: 10px;
        height: 10px;
    }
    
    .moment-images {
        grid-template-columns: 1fr;
    }
    
    .moment-images img {
        height: 180px;
    }
    
    .group-date {
        padding: 10px 20px;
    }
    
    .group-date .month {
        font-size: 1.125rem;
    }
}
</style>

<?php $this->need('sidebar.php'); ?>
<?php $this->need('footer.php'); ?>
