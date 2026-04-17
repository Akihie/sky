<?php    
/**  
 * 我的设备页面模板
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
        
        <div class="devices-container">
            <!-- 生产力工具 -->
            <section class="device-category">
                <div class="category-header">
                    <div class="category-icon">💻</div>
                    <div class="category-info">
                        <h2 class="category-title">生产力工具</h2>
                        <p class="category-desc">提升工作效率的硬件设备</p>
                    </div>
                </div>
                <div class="device-grid">
                    <div class="device-card">
                        <div class="device-image">
                            <img src="https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=400" alt="MacBook Pro">
                        </div>
                        <div class="device-info">
                            <h3 class="device-name">MacBook Pro</h3>
                            <p class="device-spec">M1Pro 32G / 1TB</p>
                            <p class="device-desc">屏幕显示效果好、色彩准确、性能强劲、续航优秀。</p>
                        </div>
                    </div>
                    <div class="device-card">
                        <div class="device-image">
                            <img src="https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=400" alt="iPhone">
                        </div>
                        <div class="device-info">
                            <h3 class="device-name">iPhone 15 Pro</h3>
                            <p class="device-spec">白色 / 256G</p>
                            <p class="device-desc">主力手机，日常通讯与拍摄。</p>
                        </div>
                    </div>
                    <div class="device-card">
                        <div class="device-image">
                            <img src="https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=400" alt="Keyboard">
                        </div>
                        <div class="device-info">
                            <h3 class="device-name">机械键盘</h3>
                            <p class="device-spec">无线蓝牙</p>
                            <p class="device-desc">多设备连接，手感舒适。</p>
                        </div>
                    </div>
                    <div class="device-card">
                        <div class="device-image">
                            <img src="https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=400" alt="Mouse">
                        </div>
                        <div class="device-info">
                            <h3 class="device-name">MX Master 3S</h3>
                            <p class="device-spec">无线蓝牙</p>
                            <p class="device-desc">响应迅速，扩展能力强，适合办公。</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 出行装备 -->
            <section class="device-category">
                <div class="category-header">
                    <div class="category-icon">🎒</div>
                    <div class="category-info">
                        <h2 class="category-title">出行装备</h2>
                        <p class="category-desc">外出携带的设备</p>
                    </div>
                </div>
                <div class="device-grid">
                    <div class="device-card">
                        <div class="device-image">
                            <img src="https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=400" alt="Bag">
                        </div>
                        <div class="device-info">
                            <h3 class="device-name">双肩背包</h3>
                            <p class="device-spec">标准版</p>
                            <p class="device-desc">容量大，分区合理，通勤必备。</p>
                        </div>
                    </div>
                    <div class="device-card">
                        <div class="device-image">
                            <img src="https://images.unsplash.com/photo-1572569511254-d8f925fe2cbb?w=400" alt="Power Bank">
                        </div>
                        <div class="device-info">
                            <h3 class="device-name">移动电源</h3>
                            <p class="device-spec">20000mAh</p>
                            <p class="device-desc">小巧便携，支持快充。</p>
                        </div>
                    </div>
                    <div class="device-card">
                        <div class="device-image">
                            <img src="https://images.unsplash.com/photo-1606220588913-b3aacb4d2f46?w=400" alt="Earbuds">
                        </div>
                        <div class="device-info">
                            <h3 class="device-name">AirPods Pro</h3>
                            <p class="device-spec">第二代</p>
                            <p class="device-desc">降噪出色，多设备无缝切换。</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- 家庭娱乐 -->
            <section class="device-category">
                <div class="category-header">
                    <div class="category-icon">🎮</div>
                    <div class="category-info">
                        <h2 class="category-title">家庭娱乐</h2>
                        <p class="category-desc">居家娱乐设备</p>
                    </div>
                </div>
                <div class="device-grid">
                    <div class="device-card">
                        <div class="device-image">
                            <img src="https://images.unsplash.com/photo-1587202372775-e229f172b9d7?w=400" alt="PC">
                        </div>
                        <div class="device-info">
                            <h3 class="device-name">台式电脑</h3>
                            <p class="device-spec">i5 / RTX 3060</p>
                            <p class="device-desc">游戏与渲染主力机。</p>
                        </div>
                    </div>
                    <div class="device-card">
                        <div class="device-image">
                            <img src="https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?w=400" alt="Monitor">
                        </div>
                        <div class="device-info">
                            <h3 class="device-name">显示器</h3>
                            <p class="device-spec">27寸 2K 165Hz</p>
                            <p class="device-desc">色彩准确，刷新率高。</p>
                        </div>
                    </div>
                    <div class="device-card">
                        <div class="device-image">
                            <img src="https://images.unsplash.com/photo-1593359677879-a4bb92f829d1?w=400" alt="NAS">
                        </div>
                        <div class="device-info">
                            <h3 class="device-name">NAS</h3>
                            <p class="device-spec">群晖 DS220+</p>
                            <p class="device-desc">家庭数据中心，照片备份。</p>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

<style>
.devices-container {
    margin-top: 32px;
}

.device-category {
    margin-bottom: 48px;
}

.category-header {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 1px solid var(--border);
}

.category-icon {
    width: 56px;
    height: 56px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(244, 63, 94, 0.1));
    border-radius: var(--radius-lg);
    font-size: 28px;
}

.category-info {
    flex: 1;
}

.category-title {
    font-size: 1.375rem;
    font-weight: 700;
    color: var(--text-main);
    margin: 0 0 4px;
}

.category-desc {
    font-size: 0.875rem;
    color: var(--text-muted);
    margin: 0;
}

.device-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}

.device-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    overflow: hidden;
    transition: var(--transition);
}

.device-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
    border-color: transparent;
}

.device-image {
    height: 160px;
    overflow: hidden;
    background: var(--bg-hover);
}

.device-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.device-card:hover .device-image img {
    transform: scale(1.05);
}

.device-info {
    padding: 20px;
}

.device-name {
    font-size: 1.0625rem;
    font-weight: 600;
    color: var(--text-main);
    margin: 0 0 6px;
}

.device-spec {
    font-size: 0.8125rem;
    color: var(--primary);
    font-weight: 500;
    margin: 0 0 10px;
}

.device-desc {
    font-size: 0.875rem;
    color: var(--text-secondary);
    line-height: 1.6;
    margin: 0;
}

/* 响应式 */
@media (max-width: 768px) {
    .device-grid {
        grid-template-columns: 1fr;
    }
    
    .category-header {
        flex-direction: column;
        text-align: center;
    }
    
    .device-image {
        height: 180px;
    }
}

@media (max-width: 480px) {
    .category-icon {
        width: 48px;
        height: 48px;
        font-size: 24px;
    }
    
    .category-title {
        font-size: 1.25rem;
    }
    
    .device-info {
        padding: 16px;
    }
}
</style>

<?php $this->need('sidebar.php'); ?>
<?php $this->need('footer.php'); ?>
