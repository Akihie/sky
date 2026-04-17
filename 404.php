<!DOCTYPE HTML>
<html>
<head>
  <meta charset="<?php $this->options->charset(); ?>" />
  <meta name="viewport" content="width=device-width,user-scalable=no">
  <?php if ($this->is('index')): ?>
    <title><?php $this->options->title(); ?></title>
  <?php else: ?>
    <title><?php $this->archiveTitle('.', '', ' - '); ?><?php $this->options->title(); ?></title>
  <?php endif; ?>
  <link rel="stylesheet" href="<?php $this->options->adminUrl('css/normalize.css'); ?>">
  <link rel="stylesheet" href="<?php $this->options->themeUrl('style.css'); ?>">
  <?php $this->header("generator=&template=&"); ?>
</head>
<body>
<div class="body404">
<div class="info404">
  <header id="header404">
    <div class="site-name404"><i>404</i></div>
  </header>
  <section>
    <div class="title404"><p>每一个平凡的日常<br/>都是连续发生中的奇迹</p></div>
    <a class="index404" rel="nofollow" href="<?php $this->options->siteUrl(); ?>">回首页看看</a>
  </section>
  <footer id="footer404">
    &copy; <?php echo date('Y'); ?> <a href="<?php $this->options->siteUrl(); ?>"><?php $this->options->title(); ?></a>
  </footer>
</div>
</div>
<?php $this->footer(); ?>
</body>
</html>
