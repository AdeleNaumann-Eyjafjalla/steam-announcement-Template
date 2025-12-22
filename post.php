<?php
/**
 * 文章页模板
 * 
 * @package SteamAnnouncement
 */

$this->need('header.php');
?>  
<div class="page-wrapper">
    <!-- 左侧导航 - Steam风格 -->
    <nav class="main-navigation">
        <ul class="nav-menu">
            <?php
            // 获取侧边导航菜单配置
            $menuConfig = $this->options->sidebarMenu;
            $menuItems = parseSidebarMenu($menuConfig);
            
            foreach ($menuItems as $item) {
                $isActive = false;
                $isGroupTitle = ($item['url'] == '#');
                
                // 检查是否为当前页面
                if (!$isGroupTitle) {
                    $currentUrl = $this->request->getPathinfo();
                    $itemUrl = parse_url($item['url'], PHP_URL_PATH);
                    $isActive = ($currentUrl == $itemUrl);
                }
                
                if ($isGroupTitle) {
                    // 分组标题
                    echo '<li class="nav-item"><span class="nav-group-title">' . htmlspecialchars($item['name']) . '</span></li>';
                } else {
                    // 普通菜单项
                    echo '<li class="nav-item"><a href="' . htmlspecialchars($item['url']) . '" class="nav-link' . ($isActive ? ' active' : '') . '">' . htmlspecialchars($item['name']) . '</a></li>';
                }
            }
            
            // 显示Typecho页面 - 修复显示URL而非标题的bug
            $this->widget('Widget_Contents_Page_List')->to($pages);
            if ($pages->have()) {
                echo '<li class="nav-item"><span class="nav-group-title">页面</span></li>';
                while($pages->next()):
                    // 检查当前页面是否为活跃状态
                    $isActive = ($this->is('page') && $this->cid == $pages->cid);
                    // 获取页面标题和链接
                    $pageTitle = $pages->title;
                    $pagePermalink = $pages->permalink;
                    // 确保标题和链接是字符串类型
                    if (!is_string($pageTitle)) {
                        $pageTitle = (string)$pageTitle;
                    }
                    if (!is_string($pagePermalink)) {
                        $pagePermalink = (string)$pagePermalink;
                    }
                    // 输出转义后的HTML
                    echo '<li class="nav-item"><a href="' . htmlspecialchars($pagePermalink) . '" class="nav-link' . ($isActive ? ' active' : '') . '">' . htmlspecialchars($pageTitle) . '</a></li>';
                endwhile;
            }
            ?>       
        </ul>
    </nav>
    
    <!-- 主内容区 -->
    <div class="content-container">
        <main class="main-content">
            <div class="container">
                <article class="single-post">
                    <!-- 文章特色图片 -->
                    <?php $thumbnail = $this->fields->thumbnail; ?>
                    <?php if ($thumbnail): ?>
                    <div class="post-featured-image">
                        <img src="<?php echo $thumbnail; ?>" alt="<?php $this->title(); ?>">
                    </div>
                    <?php endif; ?>
                    
                    <header class="post-header">
                        <h1 class="post-title"><?php $this->title(); ?></h1>
                        <div class="post-meta">
                            <span class="post-meta-item">
                                <i class="far fa-calendar"></i>
                                <?php $this->date('Y-m-d H:i'); ?>
                            </span>
                            <span class="post-meta-item">
                                <i class="far fa-user"></i>
                                <?php $this->author(); ?>
                            </span>
                            <span class="post-meta-item">
                                <i class="far fa-folder"></i>
                                <?php $this->category(', '); ?>
                            </span>
                            <span class="post-meta-item">
                                <i class="far fa-comment"></i>
                                <?php $this->commentsNum('暂无评论', '1条评论', '%d条评论'); ?>
                            </span>
                        </div>
                    </header>
                    
                    <div class="post-content">
                        <?php $this->content(); ?>
                    </div>
                    
                    <footer class="post-footer">
                        <div class="post-tags">
                            <i class="fas fa-tags"></i>
                            <?php $this->tags(' ', true, '无标签'); ?>
                        </div>
                    </footer>
                </article>
                
                <!-- 评论区 - 使用Typecho标准评论模板 -->
                <section class="comments-section">
                    <?php $this->need('comments.php'); ?>
                </section>
                
                <!-- 相关文章 -->
                <div class="related-posts">
                    <h3 class="related-title">相关文章</h3>
                    <div class="related-list">
                        <?php $this->related(5)->to($relatedPosts); ?>
                        <?php if($relatedPosts->have()): ?>
                            <?php while($relatedPosts->next()): ?>
                            <article class="related-card">
                                <?php
                                    // 获取相关文章封面图
                                    $relatedThumbnail = $relatedPosts->fields->thumbnail;
                                    $relatedThumbnailUrl = $relatedThumbnail ? $relatedThumbnail : 'https://via.placeholder.com/120x68';
                                ?>
                                <div class="related-card-thumbnail">
                                    <img src="<?php echo $relatedThumbnailUrl; ?>" alt="<?php $relatedPosts->title(); ?>">
                                </div>
                                <div class="related-card-content">
                                    <h4 class="related-post-title">
                                        <a href="<?php $relatedPosts->permalink(); ?>" title="<?php $relatedPosts->title(); ?>"><?php $relatedPosts->title(); ?></a>
                                    </h4>
                                    <div class="related-post-meta">
                                        <span><?php $relatedPosts->date('Y-m-d'); ?></span>
                                    </div>
                                </div>
                            </article>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p>暂无相关文章</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </main>
        
        <?php $this->need('footer.php'); ?>
    </div>
</div>