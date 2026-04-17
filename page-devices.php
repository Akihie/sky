<?php
/**
 * 设备页面模板 - 双列网格卡片（图片上、内容下）
 * 字段：img(图片) / name(名称) / param(参数) / desc(介绍) / price(价格)
 * @package sky
 */
$this->need('header.php');
?>
<style>
.device-page { padding: 48px 0; background: #f7f8fa; min-height: 100vh; }
.device-header { display:flex; align-items:center; gap:16px; margin-bottom:28px; padding-bottom:20px; border-bottom:2px solid #e5e7eb; background:#fff; padding:20px 24px; border-radius:12px; }
.device-header .device-icon { width:48px;height:48px;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#3b82f6,#8b5cf6);border-radius:12px;font-size:24px; flex-shrink:0; }
.device-header h1 { font-size:1.25rem;font-weight:800;color:#1f2937;margin:0; }
.device-header p { font-size:.8rem;color:#6b7280;margin:4px 0 0; }
.device-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; }
.device-card { background:#fff;border:1px solid #e5e7eb;border-radius:12px;overflow:hidden;display:flex;flex-direction:column;transition:box-shadow .2s,transform .2s; }
.device-card:hover { box-shadow:0 6px 20px rgba(0,0,0,.1);transform:translateY(-2px); }
.device-card .device-img { width:100%;height:160px;flex-shrink:0;overflow:hidden;background:#f3f4f6;display:flex;align-items:center;justify-content:center; }
.device-card .device-img img { width:100%;height:100%;object-fit:cover; }
.device-card .device-img .no-img { color:#9ca3af;font-size:.75rem;text-align:center;padding:8px; }
.device-card .device-body { flex:1;padding:14px 16px;display:flex;flex-direction:column;gap:4px; }
.device-card .device-name { font-size:.9rem;font-weight:700;color:#111;margin:0; white-space:nowrap;overflow:hidden;text-overflow:ellipsis; }
.device-card .device-param { font-size:.75rem;color:#888;margin:0; white-space:nowrap;overflow:hidden;text-overflow:ellipsis; }
.device-card .device-desc { font-size:.8rem;color:#666;margin:0; flex:1; display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden; }
.device-card .device-footer { display:flex;align-items:center;justify-content:space-between;margin-top:6px; }
.device-card .device-price { font-size:.95rem;font-weight:700;color:#0d9488;margin:0; white-space:nowrap; }
.device-card .device-link { font-size:.75rem;color:#3b82f6;text-decoration:none; }
.device-card .device-link:hover { text-decoration:underline; }
.empty-tip { text-align:center;padding:64px 0;color:#9ca3af; }
.empty-tip p { margin:8px 0; }
@media(max-width:768px){ .device-grid{grid-template-columns:1fr;}.device-page{padding:24px 0;} }
</style>

<div class="col-8" id="main">
    <div class="res-cons">
        <article class="post">
            <header><h1 class="post-title"><?php $this->title(); ?></h1></header>
            <div class="post-content"><?php $this->content(); ?></div>
        </article>

        <div class="device-page">
            <?php
            $db = \Typecho\Db::get();
            $mid = intval($db->fetchRow($db->select('mid')->from('table.metas')
                ->where('slug=?', 'devices'))['mid']);
            $rows = $mid > 0
                ? $db->fetchAll($db->select('c.cid,c.title,c.text')->from('table.contents c')
                    ->join('table.relationships r','c.cid=r.cid','left')
                    ->where('c.type=?','post')->where('c.status=?','publish')
                    ->where('r.mid=?',$mid))
                : [];
            $posts = [];
            foreach($rows as $row){
                $fields = [];
                $f = $db->fetchAll($db->select()->from('table.fields')->where('cid=?',$row['cid']));
                foreach($f as $ff) $fields[$ff['name']] = $ff['str_value'];
                $thumb = !empty($fields['img']) ? $fields['img'] : '';
                if(empty($thumb)){
                    $imgMatch = [];
                    if(preg_match('/<img[^>]+src=["\']([^"\']+)["\'][^>]*>/i', $row['text'], $imgMatch)){
                        $thumb = $imgMatch[1];
                    }
                }
                $posts[] = [
                    'cid'=>$row['cid'],
                    'title'=>$row['title'],
                    'thumb'=>$thumb,
                    'fields'=>$fields,
                ];
            }
            $hasAny = count($posts)>0;
            ?>

            <?php if($hasAny): ?>
            <div class="device-header">
                <div class="device-icon">💻</div>
                <div>
                    <h1>我的装备库</h1>
                    <p>数码设备｜效率工具</p>
                </div>
            </div>
            <div class="device-grid">
                <?php foreach($posts as $post): ?>
                <div class="device-card">
                    <?php if(!empty($post['thumb'])): ?>
                    <div class="device-img"><img src="<?php echo htmlspecialchars($post['thumb']); ?>" alt="设备图"></div>
                    <?php else: ?>
                    <div class="device-img"><span class="no-img">暂无图片</span></div>
                    <?php endif; ?>
                    <div class="device-body">
                        <h3 class="device-name"><?php echo !empty($post['fields']['name']) ? htmlspecialchars($post['fields']['name']) : htmlspecialchars($post['title']); ?></h3>
                        <?php if(!empty($post['fields']['param'])): ?>
                        <p class="device-param"><?php echo htmlspecialchars($post['fields']['param']); ?></p>
                        <?php endif; ?>
                        <?php if(!empty($post['fields']['desc'])): ?>
                        <p class="device-desc"><?php echo htmlspecialchars($post['fields']['desc']); ?></p>
                        <?php endif; ?>
                        <?php if(!empty($post['fields']['price'])): ?>
                        <p class="device-price">¥<?php echo htmlspecialchars($post['fields']['price']); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="empty-tip">
                <p>暂无设备文章</p>
                <p>发表文章时选择「<strong>设备</strong>」分类，内容将在此页面展示</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php $this->need('footer.php'); ?>
