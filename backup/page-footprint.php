<?php    
/**  
 * 足迹页面模板
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
        
        <div class="footprint-container">
            <!-- 统计信息 -->
            <div class="footprint-stats">
                <div class="stat-item">
                    <div class="stat-icon">📍</div>
                    <div class="stat-value">12</div>
                    <div class="stat-label">城市</div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon">🗺️</div>
                    <div class="stat-value">5</div>
                    <div class="stat-label">省份</div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon">🌍</div>
                    <div class="stat-value">1</div>
                    <div class="stat-label">国家</div>
                </div>
            </div>

            <!-- 地图容器 -->
            <div class="map-container">
                <div id="footprint-map" class="footprint-map"></div>
            </div>

            <!-- 足迹列表 -->
            <div class="footprint-list">
                <h3 class="section-title">
                    <span class="title-icon">📌</span>
                    我的足迹
                </h3>
                
                <div class="location-group">
                    <div class="group-header">
                        <span class="country-flag">🇨🇳</span>
                        <span class="country-name">中国</span>
                    </div>
                    
                    <div class="city-grid">
                        <div class="city-card" data-lat="39.9042" data-lng="116.4074">
                            <div class="city-status live">现居</div>
                            <div class="city-name">北京</div>
                            <div class="city-info">北京市</div>
                            <div class="city-date">2023年至今</div>
                        </div>
                        <div class="city-card" data-lat="31.2304" data-lng="121.4737">
                            <div class="city-status visited">去过</div>
                            <div class="city-name">上海</div>
                            <div class="city-info">上海市</div>
                            <div class="city-date">2022.08</div>
                        </div>
                        <div class="city-card" data-lat="30.5728" data-lng="104.0668">
                            <div class="city-status visited">去过</div>
                            <div class="city-name">成都</div>
                            <div class="city-info">四川省</div>
                            <div class="city-date">2023.04</div>
                        </div>
                        <div class="city-card" data-lat="30.2741" data-lng="120.1551">
                            <div class="city-status visited">去过</div>
                            <div class="city-name">杭州</div>
                            <div class="city-info">浙江省</div>
                            <div class="city-date">2023.06</div>
                        </div>
                        <div class="city-card" data-lat="22.5431" data-lng="114.0579">
                            <div class="city-status visited">去过</div>
                            <div class="city-name">深圳</div>
                            <div class="city-info">广东省</div>
                            <div class="city-date">2022.12</div>
                        </div>
                        <div class="city-card" data-lat="23.1291" data-lng="113.2644">
                            <div class="city-status visited">去过</div>
                            <div class="city-name">广州</div>
                            <div class="city-info">广东省</div>
                            <div class="city-date">2022.12</div>
                        </div>
                        <div class="city-card" data-lat="29.5630" data-lng="106.5516">
                            <div class="city-status visited">去过</div>
                            <div class="city-name">重庆</div>
                            <div class="city-info">重庆市</div>
                            <div class="city-date">2023.04</div>
                        </div>
                        <div class="city-card" data-lat="34.3416" data-lng="108.9398">
                            <div class="city-status visited">去过</div>
                            <div class="city-name">西安</div>
                            <div class="city-info">陕西省</div>
                            <div class="city-date">2021.10</div>
                        </div>
                        <div class="city-card" data-lat="36.0671" data-lng="120.3826">
                            <div class="city-status visited">去过</div>
                            <div class="city-name">青岛</div>
                            <div class="city-info">山东省</div>
                            <div class="city-date">2022.07</div>
                        </div>
                        <div class="city-card" data-lat="38.0428" data-lng="114.5149">
                            <div class="city-status visited">去过</div>
                            <div class="city-name">石家庄</div>
                            <div class="city-info">河北省</div>
                            <div class="city-date">2021.05</div>
                        </div>
                        <div class="city-card" data-lat="37.8706" data-lng="112.5489">
                            <div class="city-status visited">去过</div>
                            <div class="city-name">太原</div>
                            <div class="city-info">山西省</div>
                            <div class="city-date">2020.08</div>
                        </div>
                        <div class="city-card" data-lat="45.8038" data-lng="126.5350">
                            <div class="city-status visited">去过</div>
                            <div class="city-name">哈尔滨</div>
                            <div class="city-info">黑龙江省</div>
                            <div class="city-date">2020.01</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Leaflet 地图库 (本地) -->
<link rel="stylesheet" href="<?php $this->options->themeUrl('assets/leaflet/leaflet.css'); ?>" />
<script src="<?php $this->options->themeUrl('assets/leaflet/leaflet.js'); ?>"></script>
<script>
(function() {
    function initMap() {
        var mapEl = document.getElementById('footprint-map');
        if (!mapEl || typeof L === 'undefined') return;

        var map = L.map('footprint-map', {
            center: [35.8617, 104.1954],
            zoom: 4,
            scrollWheelZoom: false
        });

        // 使用高德地图卫星图
        L.tileLayer('https://webst0{s}.is.autonavi.com/appmaptile?style=6&x={x}&y={y}&z={z}&key=2c77cf0ff0a73d86ee1072c7bbd72c6e', {
            subdomains: ['1', '2', '3', '4'],
            maxZoom: 18,
            attribution: '&copy; 高德地图'
        }).addTo(map);
        
        // 卫星图标注层
        L.tileLayer('https://webst0{s}.is.autonavi.com/appmaptile?style=8&x={x}&y={y}&z={z}&key=2c77cf0ff0a73d86ee1072c7bbd72c6e', {
            subdomains: ['1', '2', '3', '4'],
            maxZoom: 18
        }).addTo(map);

        var cities = [
            { name: '北京', lat: 39.9042, lng: 116.4074, live: true },
            { name: '上海', lat: 31.2304, lng: 121.4737, live: false },
            { name: '成都', lat: 30.5728, lng: 104.0668, live: false },
            { name: '杭州', lat: 30.2741, lng: 120.1551, live: false },
            { name: '深圳', lat: 22.5431, lng: 114.0579, live: false },
            { name: '广州', lat: 23.1291, lng: 113.2644, live: false },
            { name: '重庆', lat: 29.5630, lng: 106.5516, live: false },
            { name: '西安', lat: 34.3416, lng: 108.9398, live: false },
            { name: '青岛', lat: 36.0671, lng: 120.3826, live: false },
            { name: '石家庄', lat: 38.0428, lng: 114.5149, live: false },
            { name: '太原', lat: 37.8706, lng: 112.5489, live: false },
            { name: '哈尔滨', lat: 45.8038, lng: 126.5350, live: false }
        ];

        cities.forEach(function(city) {
            var icon = L.divIcon({
                className: 'custom-marker',
                html: '<div class="marker ' + (city.live ? 'live' : 'visited') + '">📍</div>',
                iconSize: [24, 24],
                iconAnchor: [12, 24]
            });
            L.marker([city.lat, city.lng], { icon: icon })
                .addTo(map)
                .bindPopup('<strong>' + city.name + '</strong>');
        });

        document.querySelectorAll('.city-card').forEach(function(card, i) {
            card.addEventListener('click', function() {
                if (cities[i]) map.setView([cities[i].lat, cities[i].lng], 10);
            });
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initMap);
    } else {
        setTimeout(initMap, 100);
    }
})();
</script>

<style>
.footprint-container { margin-top: 32px; }
.footprint-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 32px; }
.stat-item { background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius-lg); padding: 24px; text-align: center; transition: var(--transition); }
.stat-item:hover { transform: translateY(-2px); box-shadow: var(--shadow); }
.stat-icon { font-size: 32px; margin-bottom: 8px; }
.stat-value { font-size: 2rem; font-weight: 800; color: var(--primary); line-height: 1; margin-bottom: 4px; }
.stat-label { font-size: 0.875rem; color: var(--text-muted); }
.map-container { margin-bottom: 32px; border-radius: var(--radius-lg); overflow: hidden; border: 1px solid var(--border); }
.footprint-map { height: 400px; background: #e8e8e8; }
.custom-marker { background: transparent !important; border: none !important; }
.custom-marker .marker { width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; font-size: 20px; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3)); }
.custom-marker .marker.live { animation: pulse 1.5s infinite; }
@keyframes pulse { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.3); } }
.section-title { display: flex; align-items: center; gap: 10px; font-size: 1.25rem; font-weight: 700; color: var(--text-main); margin-bottom: 20px; }
.title-icon { font-size: 1.5rem; }
.location-group { margin-bottom: 24px; }
.group-header { display: flex; align-items: center; gap: 10px; margin-bottom: 16px; padding-bottom: 12px; border-bottom: 1px solid var(--border); }
.country-flag { font-size: 1.5rem; }
.country-name { font-size: 1.125rem; font-weight: 600; color: var(--text-main); }
.city-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; }
.city-card { background: var(--bg-card); border: 1px solid var(--border); border-radius: var(--radius); padding: 16px; cursor: pointer; transition: var(--transition); position: relative; }
.city-card:hover { transform: translateY(-2px); box-shadow: var(--shadow); border-color: var(--primary); }
.city-status { position: absolute; top: 10px; right: 10px; font-size: 0.6875rem; padding: 2px 8px; border-radius: var(--radius-full); font-weight: 600; }
.city-status.live { background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: white; }
.city-status.visited { background: var(--bg-hover); color: var(--text-muted); }
.city-name { font-size: 1rem; font-weight: 600; color: var(--text-main); margin-bottom: 4px; }
.city-info { font-size: 0.8125rem; color: var(--text-secondary); margin-bottom: 6px; }
.city-date { font-size: 0.75rem; color: var(--text-muted); }
@media (max-width: 900px) { .city-grid { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 640px) { .footprint-stats { gap: 10px; } .stat-item { padding: 16px; } .stat-icon { font-size: 24px; } .stat-value { font-size: 1.5rem; } .footprint-map { height: 300px; } .city-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 400px) { .city-grid { grid-template-columns: 1fr; } }
</style>

<?php $this->need('sidebar.php'); ?>
<?php $this->need('footer.php'); ?>
