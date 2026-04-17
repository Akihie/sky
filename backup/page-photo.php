<?php
/**
 * 摄影作品页面模板
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

        <div class="photo-container">
            <!-- 分类导航 -->
            <div class="photo-categories" id="photoCategories">
                <button class="category-btn active" data-category="all">
                    <span class="icon">📷</span>
                    <span class="text">全部</span>
                    <span class="count" id="countAll">0</span>
                </button>
                <button class="category-btn" data-category="landscape">
                    <span class="icon">🏔️</span>
                    <span class="text">风景</span>
                    <span class="count" id="countLandscape">0</span>
                </button>
                <button class="category-btn" data-category="city">
                    <span class="icon">🌃</span>
                    <span class="text">城市</span>
                    <span class="count" id="countCity">0</span>
                </button>
                <button class="category-btn" data-category="people">
                    <span class="icon">👤</span>
                    <span class="text">人像</span>
                    <span class="count" id="countPeople">0</span>
                </button>
                <button class="category-btn" data-category="food">
                    <span class="icon">🍜</span>
                    <span class="text">美食</span>
                    <span class="count" id="countFood">0</span>
                </button>
                <button class="category-btn" data-category="animal">
                    <span class="icon">🐱</span>
                    <span class="text">动物</span>
                    <span class="count" id="countAnimal">0</span>
                </button>
            </div>

            <!-- 瀑布流画廊 -->
            <div class="photo-gallery" id="photoGallery"></div>

            <!-- 加载状态 -->
            <div class="loading-state" id="loadingState">
                <div class="loading-spinner"></div>
                <span>加载中...</span>
            </div>
        </div>
    </div>
</div>

<!-- 图片预览模态框 -->
<div class="photo-modal" id="photoModal">
    <div class="modal-backdrop"></div>
    <div class="modal-content">
        <button class="modal-close" id="modalClose">×</button>
        <button class="modal-nav prev" id="modalPrev">‹</button>
        <button class="modal-nav next" id="modalNext">›</button>
        <div class="modal-image-wrapper">
            <img id="modalImage" src="" alt="">
        </div>
        <div class="modal-info">
            <h3 id="modalTitle"></h3>
            <p id="modalDesc"></p>
            <div class="modal-meta">
                <span id="modalDate"></span>
                <span id="modalLocation"></span>
            </div>
        </div>
    </div>
</div>

<script>
const photoData = [
    { id: 1, category: 'landscape', title: '山间晨雾', desc: '清晨的山谷被薄雾笼罩', src: 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800', date: '2024.03.20', location: '黄山' },
    { id: 2, category: 'landscape', title: '海边日落', desc: '夕阳染红了整片天空', src: 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=800', date: '2024.03.15', location: '三亚' },
    { id: 3, category: 'landscape', title: '森林小径', desc: '阳光透过树叶洒落', src: 'https://images.unsplash.com/photo-1448375240586-882707db888b?w=800', date: '2024.03.10', location: '张家界' },
    { id: 4, category: 'landscape', title: '雪山之巅', desc: '白雪皑皑的山峰', src: 'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?w=800', date: '2024.02.28', location: '玉龙雪山' },
    { id: 5, category: 'city', title: '城市夜景', desc: '霓虹灯下的都市', src: 'https://images.unsplash.com/photo-1514565131-fce0801e5785?w=800', date: '2024.03.18', location: '上海' },
    { id: 6, category: 'city', title: '老街巷弄', desc: '时光留下的痕迹', src: 'https://images.unsplash.com/photo-1519681393784-d120267933ba?w=800', date: '2024.03.12', location: '苏州' },
    { id: 7, category: 'city', title: '现代建筑', desc: '几何之美', src: 'https://images.unsplash.com/photo-1486325212027-8081e485255e?w=800', date: '2024.03.05', location: '北京' },
    { id: 8, category: 'people', title: '街头艺人', desc: '城市中的艺术家', src: 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=800', date: '2024.03.08', location: '成都' },
    { id: 9, category: 'people', title: '咖啡时光', desc: '午后的惬意', src: 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=800', date: '2024.02.20', location: '杭州' },
    { id: 10, category: 'food', title: '精致甜点', desc: '视觉与味觉的双重享受', src: 'https://images.unsplash.com/photo-1565958011703-44f9829ba187?w=800', date: '2024.03.22', location: '北京' },
    { id: 11, category: 'food', title: '日式料理', desc: '匠心之作', src: 'https://images.unsplash.com/photo-1579871494447-9811cf80d66c?w=800', date: '2024.03.16', location: '上海' },
    { id: 12, category: 'food', title: '咖啡艺术', desc: '杯中的风景', src: 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=800', date: '2024.03.01', location: '深圳' },
    { id: 13, category: 'animal', title: '慵懒午后', desc: '猫咪的惬意时光', src: 'https://images.unsplash.com/photo-1514888286974-6c03e2ca1dba?w=800', date: '2024.03.19', location: '家' },
    { id: 14, category: 'animal', title: '林间小鹿', desc: '森林中的精灵', src: 'https://images.unsplash.com/photo-1484406566174-9da000fda645?w=800', date: '2024.02.25', location: '奈良' },
    { id: 15, category: 'animal', title: '飞鸟', desc: '自由的翅膀', src: 'https://images.unsplash.com/photo-1444464666168-49d633b86797?w=800', date: '2024.02.18', location: '昆明' },
];

let currentCategory = 'all';
let currentIndex = 0;
let filteredPhotos = [];

document.addEventListener('DOMContentLoaded', function() {
    updateCounts();
    renderGallery('all');
    initCategoryButtons();
    initModal();
});

function updateCounts() {
    document.getElementById('countAll').textContent = photoData.length;
    document.getElementById('countLandscape').textContent = photoData.filter(p => p.category === 'landscape').length;
    document.getElementById('countCity').textContent = photoData.filter(p => p.category === 'city').length;
    document.getElementById('countPeople').textContent = photoData.filter(p => p.category === 'people').length;
    document.getElementById('countFood').textContent = photoData.filter(p => p.category === 'food').length;
    document.getElementById('countAnimal').textContent = photoData.filter(p => p.category === 'animal').length;
}

function renderGallery(category) {
    const gallery = document.getElementById('photoGallery');
    const loading = document.getElementById('loadingState');
    loading.style.display = 'flex';
    gallery.innerHTML = '';
    filteredPhotos = category === 'all' ? photoData : photoData.filter(p => p.category === category);

    setTimeout(() => {
        filteredPhotos.forEach((photo, index) => {
            const item = document.createElement('div');
            item.className = 'photo-item';
            item.dataset.index = index;
            item.innerHTML = '<div class="photo-wrapper"><img src="' + photo.src + '" alt="' + photo.title + '" loading="lazy"><div class="photo-overlay"><div class="photo-info"><h3>' + photo.title + '</h3><p>' + photo.location + '</p></div></div></div>';
            item.addEventListener('click', () => openModal(index));
            gallery.appendChild(item);
        });
        loading.style.display = 'none';
    }, 300);
}

function initCategoryButtons() {
    const buttons = document.querySelectorAll('.category-btn');
    buttons.forEach(btn => {
        btn.addEventListener('click', function() {
            buttons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            renderGallery(this.dataset.category);
        });
    });
}

function initModal() {
    const modal = document.getElementById('photoModal');
    document.getElementById('modalClose').addEventListener('click', closeModal);
    modal.querySelector('.modal-backdrop').addEventListener('click', closeModal);
    document.getElementById('modalPrev').addEventListener('click', () => navigateModal(-1));
    document.getElementById('modalNext').addEventListener('click', () => navigateModal(1));
    document.addEventListener('keydown', (e) => {
        if (modal.classList.contains('active')) {
            if (e.key === 'Escape') closeModal();
            if (e.key === 'ArrowLeft') navigateModal(-1);
            if (e.key === 'ArrowRight') navigateModal(1);
        }
    });
}

function openModal(index) {
    currentIndex = index;
    const photo = filteredPhotos[index];
    const modal = document.getElementById('photoModal');
    document.getElementById('modalImage').src = photo.src;
    document.getElementById('modalTitle').textContent = photo.title;
    document.getElementById('modalDesc').textContent = photo.desc;
    document.getElementById('modalDate').textContent = '📅 ' + photo.date;
    document.getElementById('modalLocation').textContent = '📍 ' + photo.location;
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    document.getElementById('photoModal').classList.remove('active');
    document.body.style.overflow = '';
}

function navigateModal(direction) {
    currentIndex = (currentIndex + direction + filteredPhotos.length) % filteredPhotos.length;
    const photo = filteredPhotos[currentIndex];
    document.getElementById('modalImage').src = photo.src;
    document.getElementById('modalTitle').textContent = photo.title;
    document.getElementById('modalDesc').textContent = photo.desc;
    document.getElementById('modalDate').textContent = '📅 ' + photo.date;
    document.getElementById('modalLocation').textContent = '📍 ' + photo.location;
}
</script>

<style>
.photo-container { margin-top: 32px; }

.photo-categories {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 28px;
    padding-bottom: 20px;
    border-bottom: 1px solid var(--border);
}

.category-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 18px;
    background: var(--bg-card);
    border: 1px solid var(--border);
    border-radius: var(--radius-full);
    color: var(--text-secondary);
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    transition: var(--transition);
}

.category-btn:hover { border-color: var(--primary); color: var(--primary); }

.category-btn.active {
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    border-color: transparent;
    color: white;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.category-btn .icon { font-size: 1rem; }

.category-btn .count {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 22px;
    height: 22px;
    padding: 0 6px;
    background: rgba(0,0,0,0.1);
    border-radius: var(--radius-full);
    font-size: 0.75rem;
    font-weight: 600;
}

.category-btn.active .count { background: rgba(255,255,255,0.2); }

.photo-gallery {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
}

.photo-item {
    position: relative;
    border-radius: var(--radius-lg);
    overflow: hidden;
    cursor: pointer;
    background: var(--bg-hover);
}

.photo-item:nth-child(4n+1) { grid-row: span 2; }

.photo-wrapper {
    position: relative;
    width: 100%;
    height: 100%;
    min-height: 200px;
}

.photo-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.photo-item:hover img { transform: scale(1.08); }

.photo-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, transparent 50%);
    opacity: 0;
    transition: opacity 0.3s ease;
    display: flex;
    align-items: flex-end;
}

.photo-item:hover .photo-overlay { opacity: 1; }

.photo-info { padding: 20px; color: white; }
.photo-info h3 { font-size: 1rem; font-weight: 600; margin: 0 0 4px; color: white; }
.photo-info p { font-size: 0.8125rem; margin: 0; opacity: 0.8; }

.loading-state {
    display: none;
    align-items: center;
    justify-content: center;
    gap: 12px;
    padding: 40px;
    color: var(--text-muted);
}

.loading-spinner {
    width: 24px;
    height: 24px;
    border: 2px solid var(--border);
    border-top-color: var(--primary);
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}

@keyframes spin { to { transform: rotate(360deg); } }

.photo-modal {
    position: fixed;
    inset: 0;
    z-index: 1000;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
}

.photo-modal.active { opacity: 1; visibility: visible; }
.modal-backdrop { position: absolute; inset: 0; background: rgba(0,0,0,0.9); }

.modal-content {
    position: relative;
    max-width: 90vw;
    max-height: 90vh;
    z-index: 1;
}

.modal-close {
    position: absolute;
    top: -40px;
    right: 0;
    width: 36px;
    height: 36px;
    background: rgba(255,255,255,0.1);
    border: none;
    border-radius: 50%;
    color: white;
    font-size: 24px;
    cursor: pointer;
    transition: var(--transition);
}

.modal-close:hover { background: rgba(255,255,255,0.2); }

.modal-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 48px;
    height: 48px;
    background: rgba(255,255,255,0.1);
    border: none;
    border-radius: 50%;
    color: white;
    cursor: pointer;
    transition: var(--transition);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
}

.modal-nav:hover { background: rgba(255,255,255,0.2); }
.modal-nav.prev { left: -70px; }
.modal-nav.next { right: -70px; }

.modal-image-wrapper {
    max-height: 75vh;
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-image-wrapper img {
    max-width: 100%;
    max-height: 75vh;
    object-fit: contain;
    border-radius: var(--radius);
}

.modal-info { text-align: center; padding: 20px; color: white; }
.modal-info h3 { font-size: 1.25rem; margin: 0 0 8px; color: white; }
.modal-info p { color: rgba(255,255,255,0.7); margin: 0 0 12px; }
.modal-meta { display: flex; justify-content: center; gap: 20px; font-size: 0.875rem; color: rgba(255,255,255,0.5); }

@media (max-width: 900px) {
    .photo-gallery { grid-template-columns: repeat(2, 1fr); }
    .modal-nav.prev { left: 10px; }
    .modal-nav.next { right: 10px; }
}

@media (max-width: 640px) {
    .photo-categories { gap: 8px; }
    .category-btn { padding: 8px 14px; font-size: 0.8125rem; }
    .category-btn .icon { display: none; }
    .photo-gallery { grid-template-columns: repeat(2, 1fr); gap: 10px; }
    .photo-item:nth-child(4n+1) { grid-row: span 1; }
    .modal-nav { width: 40px; height: 40px; }
}
</style>

<?php $this->need('sidebar.php'); ?>
<?php $this->need('footer.php'); ?>
