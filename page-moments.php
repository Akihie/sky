<?php
/**
 * 碎碎念页面模板
 * @package sky
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

        <div class="moments-container">
            <?php
            $db = \Typecho\Db::get();
            $mid = getCategoryMidBySlug('moments');
            $rows = $db->fetchAll($db->select('c.cid, c.created, c.text')
                ->from('typecho_contents c')
                ->join('typecho_relationships r', 'c.cid = r.cid', 'left')
                ->where('c.type = ?', 'post')
                ->where('c.status = ?', 'publish')
                ->where('r.mid = ?', $mid)->order('created', \Typecho\Db::SORT_DESC));

            // 读取自定义字段（str_value 才是字符串字段的存储列）
            $fieldsMap = [];
            if (count($rows) > 0) {
                $allFields = $db->fetchAll($db->select()->from('typecho_fields'));
                $cids = array_column($rows, 'cid');
                foreach ($allFields as $f) {
                    if (in_array($f['cid'], $cids)) {
                        // type=str 用 str_value, type=int 用 int_value, type=float 用 float_value
                        $type = $f['type'];
                        if ($type === 'int') {
                            $fieldsMap[$f['cid']][$f['name']] = intval($f['int_value']);
                        } elseif ($type === 'float') {
                            $fieldsMap[$f['cid']][$f['name']] = floatval($f['float_value']);
                        } else {
                            $fieldsMap[$f['cid']][$f['name']] = $f['str_value'];
                        }
                    }
                }
            }

            // 从 Markdown 内容中提取图片 URL
            // 支持: ![alt](url) 和 ![alt][ref] + [ref]: url
            function extractImagesFromText($text) {
                $imgs = [];

                // 1. 提取 ![alt][n] reference-style 定义 [n]: url
                $refMap = [];
                if (preg_match_all('/^\s*\[(\d+)\]:\s*(\S+)/m', $text, $refMatches, PREG_SET_ORDER)) {
                    foreach ($refMatches as $m) {
                        $refMap[$m[1]] = $m[2];
                    }
                }

                // 2. 提取 ![alt](url) inline style
                if (preg_match_all('/!\[([^\]]*)\]\(([^)\s"]+)\)/', $text, $inlineMatches, PREG_SET_ORDER)) {
                    foreach ($inlineMatches as $m) {
                        $url = trim($m[2]);
                        if (!empty($url) && strpos($url, 'data:image') !== 0) {
                            $imgs[] = $url;
                        }
                    }
                }

                // 3. 提取 ![alt][ref] 然后查表
                if (preg_match_all('/!\[([^\]]*)\]\[(\d+)\]/', $text, $refUsageMatches, PREG_SET_ORDER)) {
                    foreach ($refUsageMatches as $m) {
                        $refNum = $m[2];
                        if (isset($refMap[$refNum])) {
                            $url = $refMap[$refNum];
                            if (!empty($url) && strpos($url, 'data:image') !== 0) {
                                $imgs[] = $url;
                            }
                        }
                    }
                }

                // 4. 也支持命名的 reference style ![alt][name]
                if (preg_match_all('/!\[([^\]]*)\]\[([^\]]+)\]/', $text, $namedMatches, PREG_SET_ORDER)) {
                    foreach ($namedMatches as $m) {
                        $refName = $m[2];
                        // 尝试在文末找 [name]: url
                        if (preg_match('/^\s*\[' . preg_quote($refName, '/') . '\]:\s*(\S+)/m', $text, $namedRef)) {
                            $url = trim($namedRef[1]);
                            if (!empty($url) && strpos($url, 'data:image') !== 0 && !in_array($url, $imgs)) {
                                $imgs[] = $url;
                            }
                        }
                    }
                }

                return array_values(array_unique($imgs));
            }

            $posts = [];
            foreach ($rows as $row) {
                $cid = $row['cid'];
                $fields = isset($fieldsMap[$cid]) ? $fieldsMap[$cid] : [];

                // 图片优先取自定义字段 images，否则从文章内容提取
                $imagesField = isset($fields['images']) ? trim($fields['images']) : '';
                if (!empty($imagesField)) {
                    $imgs = array_filter(array_map('trim', explode(',', $imagesField)));
                } else {
                    $imgs = extractImagesFromText($row['text']);
                }

                $posts[] = [
                    'cid'      => $cid,
                    'created'  => $row['created'],
                    'text'     => $row['text'],
                    'location' => isset($fields['location']) ? $fields['location'] : '',
                    'weather'  => isset($fields['weather'])  ? $fields['weather']  : '',
                    'mood'     => isset($fields['mood'])     ? $fields['mood']     : '',
                    'tags'     => isset($fields['tags'])     ? $fields['tags']     : '',
                    'images'   => $imgs,
                    'ym'       => date('Y', $row['created']) . '-' . date('m', $row['created']),
                    'day'      => date('d', $row['created']),
                    'time'     => date('H:i', $row['created']),
                ];
            }

            // 按年月分组降序
            $byYM = [];
            foreach ($posts as $p) {
                $ym = $p['ym'];
                if (!isset($byYM[$ym])) $byYM[$ym] = [];
                $byYM[$ym][] = $p;
            }
            krsort($byYM);
            $hasAny = count($posts) > 0;
            ?>

            <?php if ($hasAny): ?>
            <div class="moments-timeline">
                <?php foreach ($byYM as $ym => $items): ?>
                <?php
                    $parts = explode('-', $ym);
                    $year  = $parts[0];
                    $month = ltrim($parts[1], '0') . '月';
                ?>
                <div class="moment-group">
                    <div class="group-date">
                        <span class="month"><?php echo $month; ?></span>
                        <span class="year"><?php echo $year; ?></span>
                    </div>
                    <div class="moment-items">
                        <?php foreach ($items as $item): ?>
                        <div class="moment-card<?php if (!empty($item['images'])): ?> with-image<?php endif; ?>">

                            <!-- 时间和状态徽章行 -->
                            <div class="moment-meta-row">
                                <span class="moment-time"><?php echo $item['day']; ?>日 <?php echo $item['time']; ?></span>
                                <?php if (!empty($item['location'])): ?>
                                <span class="moment-badge location-badge">📍 <?php echo htmlspecialchars($item['location']); ?></span>
                                <?php endif; ?>
                                <?php if (!empty($item['weather'])): ?>
                                <span class="moment-badge weather-badge"><?php echo htmlspecialchars($item['weather']); ?></span>
                                <?php endif; ?>
                                <?php if (!empty($item['mood'])): ?>
                                <span class="moment-badge mood-badge"><?php echo htmlspecialchars($item['mood']); ?></span>
                                <?php endif; ?>
                                <?php if (!empty($item['tags'])): ?>
                                <span class="moment-badge tags-badge"><?php echo htmlspecialchars($item['tags']); ?></span>
                                <?php endif; ?>
                            </div>

                            <!-- 文字内容（无标题）-->
                            <div class="moment-content">
                                <?php echo $item['text']; ?>
                            </div>

                            <!-- 图片 -->
                            <?php if (!empty($item['images'])): ?>
                            <div class="moment-images img-count-<?php
    $ic = count($item['images']);
    if ($ic == 1) echo '1';
    elseif ($ic == 2) echo '2';
    elseif ($ic == 4) echo '4';
    elseif ($ic == 3) echo '3';
    elseif ($ic >= 5 && $ic <= 6) echo '6';
    else echo '9';
?>">
                                <?php foreach ($item['images'] as $img): ?>
                                <div class="moment-img-wrap">
                                    <img src="<?php echo htmlspecialchars($img); ?>" alt="moment image" loading="lazy" />
                                </div>
                                <?php endforeach; ?>
                            </div>
                            <?php endif; ?>

                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="empty-tip">
                <p>暂无碎碎念</p>
                <p>发表文章时选择「<strong>碎碎念</strong>」分类，内容将在此页面展示</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
/* ===== 碎碎念时间线 ===== */
.moments-container { margin-top: 32px; }
.moments-timeline { position: relative; }
.moment-group { margin-bottom: 48px; }

.group-date {
    display: inline-flex;
    flex-direction: column;
    align-items: center;
    background: linear-gradient(135deg, #3b82f6, #6366f1);
    color: #fff;
    padding: 12px 28px;
    border-radius: 16px;
    margin-bottom: 28px;
    box-shadow: 0 4px 16px rgba(99, 102, 241, 0.35);
}
.group-date .month { font-size: 1.4rem; font-weight: 700; line-height: 1; letter-spacing: 0.05em; }
.group-date .year { font-size: 0.75rem; opacity: 0.75; margin-top: 4px; letter-spacing: 0.1em; }

.moment-items { position: relative; padding-left: 36px; }
.moment-items::before {
    content: '';
    position: absolute;
    left: 10px; top: 8px; bottom: 0;
    width: 2px;
    background: linear-gradient(180deg, #818cf8, #c7d2fe, transparent);
    border-radius: 2px;
}

.moment-card {
    position: relative;
    background: #fff;
    border: 1px solid #f1f5f9;
    border-radius: 16px;
    padding: 20px 24px 18px;
    margin-bottom: 20px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}
.moment-card:hover {
    transform: translateX(6px);
    box-shadow: 0 6px 20px rgba(99,102,241,0.12);
}
.moment-card::before {
    content: '';
    position: absolute;
    left: -30px; top: 26px;
    width: 14px; height: 14px;
    background: linear-gradient(135deg, #818cf8, #6366f1);
    border-radius: 50%;
    border: 3px solid #fff;
    box-shadow: 0 0 0 3px rgba(99,102,241,0.25);
}

.moment-meta-row {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 14px;
}
.moment-time {
    font-size: 0.8rem;
    color: #94a3b8;
    font-variant-numeric: tabular-nums;
    margin-right: 4px;
}
.moment-badge {
    display: inline-flex;
    align-items: center;
    gap: 3px;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 0.78rem;
    font-weight: 500;
    white-space: nowrap;
}
.location-badge { background: #fef3c7; color: #92400e; }
.weather-badge { background: #dbeafe; color: #1e40af; }
.mood-badge { background: #fce7f3; color: #9d174d; }
.tags-badge { background: #e0e7ff; color: #3730a3; }

.moment-content {
    font-size: 0.9375rem;
    line-height: 1.8;
    color: #334155;
}
.moment-content p { margin: 0 0 6px; }
.moment-content p:last-child { margin-bottom: 0; }

.moment-images {
    margin-top: 16px;
    display: grid;
    gap: 6px;
}

/* ===== 微信朋友圈风格图片布局 ===== */

/* 单图：宽度撑满，最大480px，图片保持原始比例（contain完整显示） */
.img-count-1 { grid-template-columns: 1fr; max-width: 520px; }
.img-count-1 .moment-img-wrap {
    max-height: 400px;
    overflow: hidden;
    border-radius: 10px;
}
.img-count-1 .moment-img-wrap img {
    width: 100%;
    height: 100%;
    max-height: 400px;
    object-fit: contain;
    display: block;
    border-radius: 10px;
}

/* 两张图：并排两个正方形 */
.img-count-2 { grid-template-columns: 1fr 1fr; }
.img-count-2 .moment-img-wrap { aspect-ratio: 1 / 1; }
.img-count-2 .moment-img-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.3s ease;
    cursor: pointer;
}
.img-count-2 .moment-img-wrap img:hover { transform: scale(1.03); }

/* 三张图：左边一张大图，右边两张小图 */
.img-count-3 { grid-template-columns: 1fr 1fr; grid-template-rows: 1fr 1fr; }
.img-count-3 .moment-img-wrap { aspect-ratio: 1 / 1; overflow: hidden; border-radius: 6px; }
.img-count-3 .moment-img-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.3s ease;
    cursor: pointer;
}
.img-count-3 .moment-img-wrap:first-child {
    grid-row: span 2;
    aspect-ratio: auto;
}
.img-count-3 .moment-img-wrap:first-child img { height: 100%; }
.img-count-3 .moment-img-wrap img:hover { transform: scale(1.03); }

/* 四张图：2x2 方阵 */
.img-count-4 { grid-template-columns: repeat(2, 1fr); }
.img-count-4 .moment-img-wrap { aspect-ratio: 1 / 1; }
.img-count-4 .moment-img-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.3s ease;
    cursor: pointer;
}
.img-count-4 .moment-img-wrap img:hover { transform: scale(1.03); }

/* 六张图：2x3 方阵（3列） */
.img-count-6 { grid-template-columns: repeat(3, 1fr); }
.img-count-6 .moment-img-wrap { aspect-ratio: 1 / 1; }
.img-count-6 .moment-img-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.3s ease;
    cursor: pointer;
}
.img-count-6 .moment-img-wrap img:hover { transform: scale(1.03); }

/* 九张图：3x3 方阵 */
.img-count-9 { grid-template-columns: repeat(3, 1fr); }
.img-count-9 .moment-img-wrap { aspect-ratio: 1 / 1; }
.img-count-9 .moment-img-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.3s ease;
    cursor: pointer;
}
.img-count-9 .moment-img-wrap img:hover { transform: scale(1.03); }

/* 通用图片样式（兜底） */
.moment-img-wrap { overflow: hidden; border-radius: 10px; }
.moment-img-wrap img { width: 100%; object-fit: cover; display: block; }

.empty-tip { text-align: center; padding: 60px 20px; color: #94a3b8; }
.empty-tip p { margin: 10px 0; font-size: 0.9375rem; }

@media (max-width: 640px) {
    .moment-items { padding-left: 26px; }
    .moment-items::before { left: 7px; }
    .moment-card { padding: 16px 18px; }
    .moment-card::before { left: -22px; top: 22px; width: 12px; height: 12px; }
    .group-date { padding: 10px 22px; }
    .group-date .month { font-size: 1.2rem; }
    .moment-badge { font-size: 0.72rem; padding: 2px 8px; }

    /* 移动端图片缩小 */
    .img-count-1 { max-width: 100%; }
    .img-count-1 .moment-img-wrap { max-height: 280px; }
    .moment-images { gap: 4px; }
}
</style>

<?php $this->need('sidebar.php'); ?>
<?php $this->need('footer.php'); ?>
