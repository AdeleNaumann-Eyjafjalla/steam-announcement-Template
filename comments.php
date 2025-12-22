<?php
/**
 * 评论模板
 * 
 * @package SteamAnnouncement
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit;
?>

<div id="comments" class="comments-area">
    <h3 class="comments-title">
        <?php $this->commentsNum('暂无评论', '1条评论', '%d条评论'); ?>
    </h3>
    
    <div id="respond" class="comment-respond">
        <h3 id="reply-title" class="comment-reply-title">
            <?php _e('发表评论'); ?>
            <?php if ($this->user->hasLogin()): ?>
            <span class="logged-in-as">
                <?php _e('登录身份: '); ?>
                <a href="<?php $this->options->profileUrl(); ?>" class="username"><?php $this->user->screenName(); ?></a>.
                <a href="<?php $this->options->logoutUrl(); ?>" title="<?php _e('退出登录'); ?>" class="logout-link"><?php _e('退出'); ?></a>
            </span>
            <?php endif; ?>
        </h3>
        
        <form action="<?php $this->commentUrl() ?>" method="post" id="commentform" class="comment-form">
            <?php if (!$this->user->hasLogin()): ?>
            <div class="comment-form-fields">
                <div class="comment-form-author">
                    <label for="author"><?php _e('昵称'); ?> <span class="required">*</span></label>
                    <input type="text" name="author" id="author" class="text" size="30" value="<?php $this->remember('author'); ?>">
                </div>
                <div class="comment-form-email">
                    <label for="email"><?php _e('邮箱'); ?> <span class="required">*</span></label>
                    <input type="email" name="email" id="email" class="text" size="30" value="<?php $this->remember('email'); ?>">
                </div>
                <div class="comment-form-url">
                    <label for="url"><?php _e('网站'); ?></label>
                    <input type="url" name="url" id="url" class="text" size="30" value="<?php $this->remember('url'); ?>">
                </div>
            </div>
            <?php endif; ?>
            
            <div class="comment-form-comment">
                <label for="textarea"><?php _e('评论内容'); ?> <span class="required">*</span></label>
                <textarea rows="8" cols="50" name="text" id="textarea" class="textarea"><?php $this->remember('text'); ?></textarea>
            </div>
            
            <div class="form-submit">
                <input type="submit" name="submit" id="submit" class="submit btn btn-primary" value="<?php _e('提交评论'); ?>">
                <?php $this->security->hash(); ?>
                <input type="hidden" name="remember" value="1">
                <input type="hidden" name="_" value="<?php echo $this->security->getToken($this->permalink()); ?>">
            </div>
        </form>
    </div>
    
    <?php $this->comments()->to($comments); ?>
    <?php if ($comments->have()): ?>
    <ol class="comment-list">
        <?php $comments->threadedComments(); ?>
    </ol>
    
    <?php $comments->pageNav('上一页', '下一页', 1, '...', array('wrapTag' => 'div', 'wrapClass' => 'comment-navigation', 'itemTag' => 'span', 'textTag' => 'a', 'currentClass' => 'current')); ?>
    
    <?php endif; ?>
    
    <!-- 加载Typecho评论JS -->
    <script type="text/javascript">
    (function() {
        window.TypechoComment = {
            dom : function(id) {
                return document.getElementById(id);
            },
            create : function(tag, attr) {
                var el = document.createElement(tag);
                for (var key in attr) {
                    el.setAttribute(key, attr[key]);
                }
                return el;
            },
            reply : function(cid, coid) {
                var comment = this.dom(cid), parent = comment.parentNode,
                response = this.dom('respond'), input = this.dom('comment-parent'),
                form = 'form' == response.tagName ? response : response.getElementsByTagName('form')[0],
                textarea = response.getElementsByTagName('textarea')[0];
                
                if (null == input) {
                    input = this.create('input', {
                        'type' : 'hidden',
                        'name' : 'parent',
                        'id' : 'comment-parent'
                    });
                    form.appendChild(input);
                }
                input.setAttribute('value', coid);
                
                if (null == this.dom('comment-form-place-holder')) {
                    var holder = this.create('div', {
                        'id' : 'comment-form-place-holder'
                    });
                    response.parentNode.insertBefore(holder, response);
                }
                
                comment.appendChild(response);
                this.dom('cancel-comment-reply-link').style.display = '';
                
                if (null != textarea && 'text' == textarea.name) {
                    textarea.focus();
                }
                
                return false;
            },
            cancelReply : function() {
                var response = this.dom('respond'), holder = this.dom('comment-form-place-holder'),
                input = this.dom('comment-parent');
                
                if (null != input) {
                    input.parentNode.removeChild(input);
                }
                
                if (null == holder) {
                    return true;
                }
                
                this.dom('cancel-comment-reply-link').style.display = 'none';
                holder.parentNode.insertBefore(response, holder);
                return false;
            }
        };
    })();
    </script>
</div>
