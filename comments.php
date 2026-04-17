<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<div id="comments" class="comments-wrapper">
    <?php $this->comments()->to($comments); ?>

    <?php if ($comments->have()): ?>
        <div class="comments-header">
            <h3 class="comments-count">
                <span class="count-icon">💬</span>
                <?php $this->commentsNum(_t('暂无评论'), _t('仅有 1 条评论'), _t('已有 %d 条评论')); ?>
            </h3>
        </div>

        <div class="comment-list">
            <?php $comments->listComments(); ?>
        </div>

        <div class="comments-nav">
            <?php $comments->pageNav('&laquo; 上一页', '下一页 &raquo;'); ?>
        </div>
    <?php else: ?>
        <div class="comments-empty">
            <span class="empty-icon">💭</span>
            <p>还没有评论，来抢沙发吧！</p>
        </div>
    <?php endif; ?>

    <?php if ($this->allow('comment')): ?>
    <div id="<?php $this->respondId(); ?>" class="respond">
        <div class="cancel-comment-reply">
            <?php $comments->cancelReply(); ?>
        </div>

        <h4 class="respond-title" id="response"><?php _e('发表评论'); ?></h4>

        <?php if($this->user->hasLogin()): ?>
        <div class="logged-in-info">
            <?php _e('登录身份：'); ?><a href="<?php $this->options->profileUrl(); ?>"><?php $this->user->screenName(); ?></a>
            <span class="split">·</span>
            <a href="<?php $this->options->logoutUrl(); ?>"><?php _e('退出'); ?> &raquo;</a>
        </div>
        <?php endif; ?>

        <form method="post" action="<?php $this->commentUrl() ?>" id="comment-form" class="comment-form">
            <div class="comment-form-top">
                <?php if(!$this->user->hasLogin()): ?>
                <div class="form-field">
                    <label for="author" class="required"><?php _e('称呼'); ?></label>
                    <input type="text" name="author" id="author" value="<?php $this->remember('author'); ?>" placeholder="你的名字" required />
                </div>
                <div class="form-field">
                    <label for="mail"<?php if ($this->options->commentsRequireMail): ?> class="required"<?php endif; ?>><?php _e('邮箱'); ?></label>
                    <input type="email" name="mail" id="mail" value="<?php $this->remember('mail'); ?>" placeholder="your@email.com" <?php if ($this->options->commentsRequireMail): ?>required<?php endif; ?> />
                </div>
                <div class="form-field">
                    <label for="url"<?php if ($this->options->commentsRequireURL): ?> class="required"<?php endif; ?>><?php _e('网站'); ?></label>
                    <input type="url" name="url" id="url" placeholder="https://" value="<?php $this->remember('url'); ?>" />
                </div>
                <?php endif; ?>
            </div>
            <div class="form-textarea">
                <textarea rows="5" name="text" id="textarea" class="textarea" placeholder="写下你的评论..." required><?php $this->remember('text'); ?></textarea>
            </div>
            <div class="form-footer">
                <button type="submit" class="submit-btn"><?php _e('发表评论'); ?></button>
            </div>
        </form>
    </div>
    <?php endif; ?>
</div>
