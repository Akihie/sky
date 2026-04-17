<?php
/**
 * 足迹页面模板 - 地图+卡片网格
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

        <div class="footprint-container">
            <?php
            $db = \Typecho\Db::get();
            $mid = intval($db->fetchRow($db->select('mid')->from('table.metas')
                ->where('slug=?', 'footprint'))['mid']);
            $rows = $mid > 0
                ? $db->fetchAll($db->select('c.cid, c.title, c.created, c.text')
                    ->from('table.contents c')
                    ->join('table.relationships r', 'c.cid = r.cid', 'left')
                    ->where('c.type = ?', 'post')
                    ->where('c.status = ?', 'publish')
                    ->where('r.mid = ?', $mid))
                : [];

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
                $row['city'] = isset($fieldsMap[$cid]['city']) ? $fieldsMap[$cid]['city'] : '';
                $row['lat'] = isset($fieldsMap[$cid]['lat']) ? $fieldsMap[$cid]['lat'] : '';
                $row['lng'] = isset($fieldsMap[$cid]['lng']) ? $fieldsMap[$cid]['lng'] : '';
                $row['country'] = isset($fieldsMap[$cid]['country']) && !empty($fieldsMap[$cid]['country']) ? $fieldsMap[$cid]['country'] : '中国';
                $row['visit_date'] = isset($fieldsMap[$cid]['visit_date']) && !empty($fieldsMap[$cid]['visit_date']) ? $fieldsMap[$cid]['visit_date'] : date('Y.m', $row['created']);
                $row['is_live'] = isset($fieldsMap[$cid]['is_live']) && $fieldsMap[$cid]['is_live'] == '1';
                $posts[] = $row;
            }

            $mapPins = array_filter($posts, function($p) { return !empty($p['lat']) && !empty($p['lng']); });
            $byCountry = [];
            foreach ($posts as $p) {
                $c = $p['country'];
                if (!isset($byCountry[$c])) $byCountry[$c] = [];
                $byCountry[$c][] = $p;
            }
            $hasAny = count($posts) > 0;
            ?>

            <div class="footprint-stats">
                <div class="stat-item">
                    <div class="stat-icon">📍</div>
                    <div class="stat-value"><?php echo count(array_filter(array_column($posts, 'city'))); ?></div>
                    <div class="stat-label">城市</div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon">🗺️</div>
                    <div class="stat-value"><?php echo count($byCountry); ?></div>
                    <div class="stat-label">国家</div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon">📝</div>
                    <div class="stat-value"><?php echo count($posts); ?></div>
                    <div class="stat-label">足迹</div>
                </div>
            </div>

            <?php if (count($mapPins) > 0): ?>
            <div class="map-container">
                <div id="footprint-map" class="footprint-map"></div>
            </div>
            <?php endif; ?>

            <div class="footprint-list">
                <h3 class="section-title"><span class="title-icon">📌</span>我的足迹</h3>

                <?php foreach ($byCountry as $country => $items): ?>
                <div class="location-group">
                    <div class="group-header">
                        <span class="country-flag">🇨🇳</span>
                        <span class="country-name"><?php echo htmlspecialchars($country); ?></span>
                    </div>
                    <div class="city-grid">
                        <?php foreach ($items as $item): ?>
                        <div class="city-card<?php if (!empty($item['lat']) && !empty($item['lng'])): ?> map-pin" data-lat="<?php echo htmlspecialchars($item['lat']); ?>" data-lng="<?php echo htmlspecialchars($item['lng']); ?><?php endif; ?>">
                            <?php if ($item['is_live']): ?>
                            <div class="city-status live">现居</div>
                            <?php else: ?>
                            <div class="city-status visited">去过</div>
                            <?php endif; ?>
                            <div class="city-name"><?php echo htmlspecialchars($item['city'] ?: $item['title']); ?></div>
                            <?php if (!empty($item['text'])): ?>
                            <div class="city-info"><?php echo $item['text']; ?></div>
                            <?php endif; ?>
                            <div class="city-date"><?php echo htmlspecialchars($item['visit_date']); ?></div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>

                <?php if (!$hasAny): ?>
                <div class="empty-tip">
                    <p>暂无足迹文章</p>
                    <p>发表文章时选择「<strong>足迹</strong>」分类，并填写自定义字段</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="<?php $this->options->themeUrl('assets/leaflet/leaflet.css'); ?>" />
<script src="<?php $this->options->themeUrl('assets/leaflet/leaflet.js'); ?>"></script>
<script>
(function() {
    function initMap() {
        var mapEl = document.getElementById('footprint-map');
        if (!mapEl) return;
        var cities = <?php echo json_encode($mapPins); ?>;
        if (!cities || !cities.length) return;

        var first = cities[0];
        var map = L.map('footprint-map', {
            center: [parseFloat(first.lat), parseFloat(first.lng)],
            zoom: 4,
            scrollWheelZoom: false
        });
        L.tileLayer('https://webst0{s}.is.autonavi.com/appmaptile?style=6&x={x}&y={y}&z={z}&key=2c77cf0ff0a73d86ee1072c7bbd72c6e', {
            subdomains: ['1','2','3','4'], maxZoom: 18, attribution: '© 高德地图'
        }).addTo(map);

        cities.forEach(function(city) {
            var icon = L.divIcon({
                className: 'custom-marker',
                html: '<div class="marker ' + (city.is_live ? 'live' : 'visited') + '">📍</div>',
                iconSize: [24, 24], iconAnchor: [12, 24]
            });
            L.marker([parseFloat(city.lat), parseFloat(city.lng)], { icon: icon })
                .addTo(map)
                .bindPopup('<strong>' + (city.city || city.title) + '</strong>');
        });

        document.querySelectorAll('.city-card.map-pin').forEach(function(card) {
            card.addEventListener('click', function() {
                var lat = parseFloat(this.dataset.lat);
                var lng = parseFloat(this.dataset.lng);
                if (!isNaN(lat) && !isNaN(lng)) map.setView([lat, lng], 10);
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
.stat-item { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 24px; text-align: center; transition: transform 0.2s, box-shadow 0.2s; }
.stat-item:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
.stat-icon { font-size: 32px; margin-bottom: 8px; }
.stat-value { font-size: 2rem; font-weight: 800; color: #3b82f6; line-height: 1; margin-bottom: 4px; }
.stat-label { font-size: 0.875rem; color: #6b7280; }
.map-container { margin-bottom: 32px; border-radius: 12px; overflow: hidden; border: 1px solid #e5e7eb; }
.footprint-map { height: 400px; background: #e8e8e8; }
.custom-marker { background: transparent !important; border: none !important; }
.custom-marker .marker { width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; font-size: 20px; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3)); }
.custom-marker .marker.live { animation: pulse 1.5s infinite; }
@keyframes pulse { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.3); } }
.section-title { display: flex; align-items: center; gap: 10px; font-size: 1.25rem; font-weight: 700; color: #1f2937; margin-bottom: 20px; }
.title-icon { font-size: 1.5rem; }
.location-group { margin-bottom: 24px; }
.group-header { display: flex; align-items: center; gap: 10px; margin-bottom: 16px; padding-bottom: 12px; border-bottom: 1px solid #e5e7eb; }
.country-flag { font-size: 1.5rem; }
.country-name { font-size: 1.125rem; font-weight: 600; color: #1f2937; }
.city-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; }
.city-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 8px; padding: 16px; transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s; position: relative; }
.city-card.map-pin { cursor: pointer; }
.city-card.map-pin:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.08); border-color: #3b82f6; }
.city-status { position: absolute; top: 10px; right: 10px; font-size: 0.6875rem; padding: 2px 8px; border-radius: 999px; font-weight: 600; }
.city-status.live { background: linear-gradient(135deg, #3b82f6, #1d4ed8); color: white; }
.city-status.visited { background: #f3f4f6; color: #6b7280; }
.city-name { font-size: 1rem; font-weight: 600; color: #1f2937; margin-bottom: 4px; }
.city-info { font-size: 0.8125rem; color: #6b7280; margin-bottom: 6px; }
.city-date { font-size: 0.75rem; color: #9ca3af; }
.empty-tip { text-align: center; padding: 48px; color: #6b7280; }
.empty-tip p { margin: 8px 0; }
@media (max-width: 900px) { .city-grid { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 640px) { .footprint-stats { gap: 10px; } .stat-item { padding: 16px; } .stat-icon { font-size: 24px; } .stat-value { font-size: 1.5rem; } .footprint-map { height: 300px; } .city-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 400px) { .city-grid { grid-template-columns: 1fr; } }
</style>

<?php $this->need('sidebar.php'); ?>
<?php $this->need('footer.php'); ?>
