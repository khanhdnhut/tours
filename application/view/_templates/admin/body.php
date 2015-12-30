<div role="main" id="wpbody">

    <div tabindex="0" aria-label="Main content" id="wpbody-content" style="overflow: hidden;">
        <div class="metabox-prefs" id="screen-meta">

            <div aria-label="Contextual Help Tab" tabindex="-1" class="hidden" id="contextual-help-wrap">
                <div id="contextual-help-back"></div>
                <div id="contextual-help-columns">
                    <div class="contextual-help-tabs">
                        <ul>

                            <li class="active" id="tab-link-overview">
                                <a aria-controls="tab-panel-overview" href="#tab-panel-overview">
                                    Overview								</a>
                            </li>

                            <li id="tab-link-help-navigation">
                                <a aria-controls="tab-panel-help-navigation" href="#tab-panel-help-navigation">
                                    Navigation								</a>
                            </li>

                            <li id="tab-link-help-layout">
                                <a aria-controls="tab-panel-help-layout" href="#tab-panel-help-layout">
                                    Layout								</a>
                            </li>

                            <li id="tab-link-help-content">
                                <a aria-controls="tab-panel-help-content" href="#tab-panel-help-content">
                                    Content								</a>
                            </li>
                        </ul>
                    </div>

                    <div class="contextual-help-sidebar">
                        <p><strong>For more information:</strong></p>
                        <p><a target="_blank" href="https://codex.wordpress.org/Dashboard_Screen">Documentation on Dashboard</a></p>
                        <p><a target="_blank" href="https://wordpress.org/support/">Support Forums</a></p>
                    </div>

                    <div class="contextual-help-tabs-wrap">

                        <div class="help-tab-content active" id="tab-panel-overview">
                            <p>Welcome to your WordPress Dashboard! This is the screen you will see when you log in to your site, and gives you access to all the site management features of WordPress. You can get help for any screen by clicking the Help tab in the upper corner.</p>
                        </div>

                        <div class="help-tab-content" id="tab-panel-help-navigation">
                            <p>The left-hand navigation menu provides links to all of the WordPress administration screens, with submenu items displayed on hover. You can minimize this menu to a narrow icon strip by clicking on the Collapse Menu arrow at the bottom.</p>
                            <p>Links in the Toolbar at the top of the screen connect your dashboard and the front end of your site, and provide access to your profile and helpful WordPress information.</p>
                        </div>

                        <div class="help-tab-content" id="tab-panel-help-layout">
                            <p>You can use the following controls to arrange your Dashboard screen to suit your workflow. This is true on most other administration screens as well.</p>
                            <p><strong>Screen Options</strong> &mdash; Use the Screen Options tab to choose which Dashboard boxes to show.</p>
                            <p><strong>Drag and Drop</strong> &mdash; To rearrange the boxes, drag and drop by clicking on the title bar of the selected box and releasing when you see a gray dotted-line rectangle appear in the location you want to place the box.</p>
                            <p><strong>Box Controls</strong> &mdash; Click the title bar of the box to expand or collapse it. Some boxes added by plugins may have configurable content, and will show a “Configure” link in the title bar if you hover over it.</p>
                        </div>

                        <div class="help-tab-content" id="tab-panel-help-content">
                            <p>The boxes on your Dashboard screen are:</p>
                            <p><strong>At A Glance</strong> &mdash; Displays a summary of the content on your site and identifies which theme and version of WordPress you are using.</p>
                            <p><strong>Activity</strong> &mdash; Shows the upcoming scheduled posts, recently published posts, and the most recent comments on your posts and allows you to moderate them.</p>
                            <p><strong>Quick Draft</strong> &mdash; Allows you to create a new post and save it as a draft. Also displays links to the 5 most recent draft posts you've started.</p>
                            <p><strong>WordPress News</strong> &mdash; Latest news from the official WordPress project, the <a href="https://planet.wordpress.org/">WordPress Planet</a>, and popular and recent plugins.</p>
                            <p><strong>Welcome</strong> &mdash; Shows links for some of the most common tasks when setting up a new site.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div aria-label="Screen Options Tab" tabindex="-1" class="hidden" id="screen-options-wrap">
                <form method="post" id="adv-settings">
                    <fieldset class="metabox-prefs">
                        <legend>Boxes</legend>
                        <label for="dashboard_right_now-hide">
                            <input type="checkbox" checked="checked" value="dashboard_right_now" id="dashboard_right_now-hide" name="dashboard_right_now-hide" class="hide-postbox-tog">At a Glance</label>
                        <label for="dashboard_activity-hide">
                            <input type="checkbox" checked="checked" value="dashboard_activity" id="dashboard_activity-hide" name="dashboard_activity-hide" class="hide-postbox-tog">Activity</label>
                        <label for="dashboard_quick_press-hide">
                            <input type="checkbox" checked="checked" value="dashboard_quick_press" id="dashboard_quick_press-hide" name="dashboard_quick_press-hide" class="hide-postbox-tog"><span class="hide-if-no-js">Quick Draft</span> <span class="hide-if-js">Drafts</span></label>
                        <label for="dashboard_primary-hide">
                            <input type="checkbox" checked="checked" value="dashboard_primary" id="dashboard_primary-hide" name="dashboard_primary-hide" class="hide-postbox-tog">WordPress News</label>
                        <label for="wp_welcome_panel-hide">
                            <input type="checkbox" checked="checked" id="wp_welcome_panel-hide">Welcome</label>
                    </fieldset>

                    <input type="hidden" value="9fa0eed072" name="screenoptionnonce" id="screenoptionnonce">
                </form>
            </div>
        </div>
        <div id="screen-meta-links">
            <div class="hide-if-no-js screen-meta-toggle" id="contextual-help-link-wrap">
                <button aria-expanded="false" aria-controls="contextual-help-wrap" class="button show-settings" id="contextual-help-link" type="button">Help</button>
            </div>
            <div class="hide-if-no-js screen-meta-toggle" id="screen-options-link-wrap">
                <button aria-expanded="false" aria-controls="screen-options-wrap" class="button show-settings" id="show-settings-link" type="button">Screen Options</button>
            </div>
        </div>

        <div class="wrap">
            <h1>Dashboard</h1>


            <div class="welcome-panel" id="welcome-panel">
                <input type="hidden" value="d90cc62c7b" name="welcomepanelnonce" id="welcomepanelnonce"> <a href="http://localhost:8082/wordpress1/wp-admin/?welcome=0" class="welcome-panel-close">Dismiss</a>
                <div class="welcome-panel-content">
                    <h2>Welcome to WordPress!</h2>
                    <p class="about-description">We have assembled some links to get you started:</p>
                    <div class="welcome-panel-column-container">
                        <div class="welcome-panel-column">
                            <h3>Get Started</h3>
                            <a href="http://localhost:8082/wordpress1/wp-admin/customize.php" class="button button-primary button-hero load-customize hide-if-no-customize">Customize Your Site</a>
                            <a href="http://localhost:8082/wordpress1/wp-admin/themes.php" class="button button-primary button-hero hide-if-customize">Customize Your Site</a>
                            <p class="hide-if-no-customize">or, <a href="http://localhost:8082/wordpress1/wp-admin/themes.php">change your theme completely</a></p>
                        </div>
                        <div class="welcome-panel-column">
                            <h3>Next Steps</h3>
                            <ul>
                                <li><a class="welcome-icon welcome-write-blog" href="http://localhost:8082/wordpress1/wp-admin/post-new.php">Write your first blog post</a></li>
                                <li><a class="welcome-icon welcome-add-page" href="http://localhost:8082/wordpress1/wp-admin/post-new.php?post_type=page">Add an About page</a></li>
                                <li><a class="welcome-icon welcome-view-site" href="http://localhost:8082/wordpress1/">View your site</a></li>
                            </ul>
                        </div>
                        <div class="welcome-panel-column welcome-panel-last">
                            <h3>More Actions</h3>
                            <ul>
                                <li>
                                    <div class="welcome-icon welcome-widgets-menus">Manage <a href="http://localhost:8082/wordpress1/wp-admin/widgets.php">widgets</a> or <a href="http://localhost:8082/wordpress1/wp-admin/nav-menus.php">menus</a></div>
                                </li>
                                <li><a class="welcome-icon welcome-comments" href="http://localhost:8082/wordpress1/wp-admin/options-discussion.php">Turn comments on or off</a></li>
                                <li><a class="welcome-icon welcome-learn-more" href="https://codex.wordpress.org/First_Steps_With_WordPress">Learn more about getting started</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div id="dashboard-widgets-wrap">
                <div class="metabox-holder" id="dashboard-widgets">
                    <div class="postbox-container" id="postbox-container-1">
                        <div class="meta-box-sortables ui-sortable" id="normal-sortables">
                            <div class="postbox " id="dashboard_right_now">
                                <button aria-expanded="true" class="handlediv button-link" type="button"><span class="screen-reader-text">Toggle panel: At a Glance</span><span aria-hidden="true" class="toggle-indicator"></span></button>
                                <h2 class="hndle ui-sortable-handle"><span>At a Glance</span></h2>
                                <div class="inside">
                                    <div class="main">
                                        <ul>
                                            <li class="post-count"><a href="edit.php?post_type=post">1 Post</a></li>
                                            <li class="page-count"><a href="edit.php?post_type=page">1 Page</a></li>
                                            <li class="comment-count"><a href="edit-comments.php">1 Comment</a></li>
                                            <li class="comment-mod-count hidden"><a href="edit-comments.php?comment_status=moderated">0 in moderation</a></li>
                                        </ul>
                                        <p id="wp-version-message"><span id="wp-version">WordPress 4.4 running <a href="themes.php">Twenty Sixteen</a> theme.</span></p>
                                        <p><a title="Your site is asking search engines not to index its content" href="options-reading.php">Search Engines Discouraged</a></p>
                                    </div>
                                </div>
                            </div>
                            <div class="postbox " id="dashboard_activity">
                                <button aria-expanded="true" class="handlediv button-link" type="button"><span class="screen-reader-text">Toggle panel: Activity</span><span aria-hidden="true" class="toggle-indicator"></span></button>
                                <h2 class="hndle ui-sortable-handle"><span>Activity</span></h2>
                                <div class="inside">
                                    <div id="activity-widget">
                                        <div class="activity-block" id="published-posts">
                                            <h3>Recently Published</h3>
                                            <ul>
                                                <li><span>Dec 20th, 8:36 am</span> <a href="http://localhost:8082/wordpress1/wp-admin/post.php?post=1&amp;action=edit">Hello world!</a></li>
                                            </ul>
                                        </div>
                                        <div class="activity-block" id="latest-comments">
                                            <h3>Comments</h3>
                                            <div data-wp-lists="list:comment" id="the-comment-list">
                                                <div class="comment even thread-even depth-1 comment-item approved" id="comment-1">

                                                    <img width="50" height="50" class="avatar avatar-50 photo avatar-default" srcset="http://2.gravatar.com/avatar/?s=100&amp;d=mm&amp;r=g 2x" src="http://2.gravatar.com/avatar/?s=50&amp;d=mm&amp;r=g" alt="">

                                                    <div class="dashboard-comment-wrap has-row-actions">
                                                        <p class="comment-meta">
                                                            From <cite class="comment-author"><a class="url" rel="external nofollow" href="https://wordpress.org/">Mr WordPress</a></cite> on <a href="http://localhost:8082/wordpress1/wp-admin/post.php?post=1&amp;action=edit">Hello world!</a> <span class="approve">[Pending]</span> </p>

                                                        <blockquote>
                                                            <p>Hi, this is a comment. To delete a comment, just log in and view the post's comments. There you will…</p>
                                                        </blockquote>
                                                        <p class="row-actions"><span class="approve"><a title="Approve this comment" class="vim-a" data-wp-lists="dim:the-comment-list:comment-1:unapproved:e7e7d3:e7e7d3:new=approved" href="comment.php?action=approvecomment&amp;p=1&amp;c=1&amp;_wpnonce=dcb60be1ed">Approve</a></span><span class="unapprove"><a title="Unapprove this comment" class="vim-u" data-wp-lists="dim:the-comment-list:comment-1:unapproved:e7e7d3:e7e7d3:new=unapproved" href="comment.php?action=unapprovecomment&amp;p=1&amp;c=1&amp;_wpnonce=dcb60be1ed">Unapprove</a></span><span class="reply hide-if-no-js"> | <a href="#" title="Reply to this comment" class="vim-r hide-if-no-js" onclick="window.commentReply & amp;
                                                                                    & amp;
                                                                                    commentReply.open('1', '1');
                                                                                    return false;">Reply</a></span><span class="edit"> | <a title="Edit comment" href="comment.php?action=editcomment&amp;c=1">Edit</a></span><span class="spam"> | <a title="Mark this comment as spam" class="vim-s vim-destructive" data-wp-lists="delete:the-comment-list:comment-1::spam=1" href="comment.php?action=spamcomment&amp;p=1&amp;c=1&amp;_wpnonce=e83bcbcbfe">Spam</a></span><span class="trash"> | <a title="Move this comment to the trash" class="delete vim-d vim-destructive" data-wp-lists="delete:the-comment-list:comment-1::trash=1" href="comment.php?action=trashcomment&amp;p=1&amp;c=1&amp;_wpnonce=e83bcbcbfe">Delete</a></span><span class="view"> | <a href="http://localhost:8082/wordpress1/?p=1&amp;cpage=1#comment-1" class="comment-link">View</a></span></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <ul class="subsubsub">
                                                <li class="all"><a href="http://localhost:8082/wordpress1/wp-admin/edit-comments.php?comment_status=all">All <span class="count">(<span class="all-count">1</span>)</span></a> |</li>
                                                <li class="moderated"><a href="http://localhost:8082/wordpress1/wp-admin/edit-comments.php?comment_status=moderated">Pending <span class="count">(<span class="pending-count">0</span>)</span></a> |</li>
                                                <li class="approved"><a href="http://localhost:8082/wordpress1/wp-admin/edit-comments.php?comment_status=approved">Approved <span class="count">(<span class="approved-count">1</span>)</span></a> |</li>
                                                <li class="spam"><a href="http://localhost:8082/wordpress1/wp-admin/edit-comments.php?comment_status=spam">Spam <span class="count">(<span class="spam-count">0</span>)</span></a> |</li>
                                                <li class="trash"><a href="http://localhost:8082/wordpress1/wp-admin/edit-comments.php?comment_status=trash">Trash <span class="count">(<span class="trash-count">0</span>)</span></a></li>
                                            </ul>
                                            <form method="get">
                                                <div style="display:none;" id="com-reply">
                                                    <div style="display:none;" id="replyrow">
                                                        <fieldset class="comment-reply">
                                                            <legend>
                                                                <span id="editlegend" class="hidden">Edit Comment</span>
                                                                <span id="replyhead" class="hidden">Reply to Comment</span>
                                                                <span id="addhead" class="hidden">Add new Comment</span>
                                                            </legend>

                                                            <div id="replycontainer">
                                                                <label class="screen-reader-text" for="replycontent">Comment</label>
                                                                <div class="wp-core-ui wp-editor-wrap html-active" id="wp-replycontent-wrap">
                                                                    <link media="all" type="text/css" href="http://localhost:8082/wordpress1/wp-includes/css/editor.min.css?ver=4.4" id="editor-buttons-css" rel="stylesheet">
                                                                    <div class="wp-editor-container" id="wp-replycontent-editor-container">
                                                                        <div class="quicktags-toolbar" id="qt_replycontent_toolbar">
                                                                            <input type="button" value="b" aria-label="Bold" class="ed_button button button-small" id="qt_replycontent_strong">
                                                                            <input type="button" value="i" aria-label="Italic" class="ed_button button button-small" id="qt_replycontent_em">
                                                                            <input type="button" value="link" aria-label="Insert link" class="ed_button button button-small" id="qt_replycontent_link">
                                                                            <input type="button" value="b-quote" aria-label="Blockquote" class="ed_button button button-small" id="qt_replycontent_block">
                                                                            <input type="button" value="del" aria-label="Deleted text (strikethrough)" class="ed_button button button-small" id="qt_replycontent_del">
                                                                            <input type="button" value="ins" aria-label="Inserted text" class="ed_button button button-small" id="qt_replycontent_ins">
                                                                            <input type="button" value="img" aria-label="Insert image" class="ed_button button button-small" id="qt_replycontent_img">
                                                                            <input type="button" value="ul" aria-label="Bulleted list" class="ed_button button button-small" id="qt_replycontent_ul">
                                                                            <input type="button" value="ol" aria-label="Numbered list" class="ed_button button button-small" id="qt_replycontent_ol">
                                                                            <input type="button" value="li" aria-label="List item" class="ed_button button button-small" id="qt_replycontent_li">
                                                                            <input type="button" value="code" aria-label="Code" class="ed_button button button-small" id="qt_replycontent_code">
                                                                            <input type="button" value="close tags" title="Close all open tags" class="ed_button button button-small" id="qt_replycontent_close">
                                                                        </div>
                                                                        <textarea id="replycontent" name="replycontent" cols="40" rows="20" class="wp-editor-area"></textarea>
                                                                    </div>
                                                                </div>

                                                            </div>

                                                            <div style="display:none;" id="edithead">
                                                                <div class="inside">
                                                                    <label for="author-name">Name</label>
                                                                    <input type="text" id="author-name" value="" size="50" name="newcomment_author">
                                                                </div>

                                                                <div class="inside">
                                                                    <label for="author-email">Email</label>
                                                                    <input type="text" id="author-email" value="" size="50" name="newcomment_author_email">
                                                                </div>

                                                                <div class="inside">
                                                                    <label for="author-url">URL</label>
                                                                    <input type="text" value="" size="103" class="code" name="newcomment_author_url" id="author-url">
                                                                </div>
                                                            </div>

                                                            <p class="submit" id="replysubmit">
                                                                <a class="save button-primary alignright" href="#comments-form">
                                                                    <span style="display:none;" id="addbtn">Add Comment</span>
                                                                    <span style="display:none;" id="savebtn">Update Comment</span>
                                                                    <span style="display:none;" id="replybtn">Submit Reply</span></a>
                                                                <a class="cancel button-secondary alignleft" href="#comments-form">Cancel</a>
                                                                <span class="waiting spinner"></span>
                                                                <span style="display:none;" class="error"></span>
                                                            </p>

                                                            <input type="hidden" value="" id="action" name="action">
                                                            <input type="hidden" value="" id="comment_ID" name="comment_ID">
                                                            <input type="hidden" value="" id="comment_post_ID" name="comment_post_ID">
                                                            <input type="hidden" value="" id="status" name="status">
                                                            <input type="hidden" value="-1" id="position" name="position">
                                                            <input type="hidden" value="0" id="checkbox" name="checkbox">
                                                            <input type="hidden" value="dashboard" id="mode" name="mode">
                                                            <input type="hidden" value="80348547e1" name="_ajax_nonce-replyto-comment" id="_ajax_nonce-replyto-comment">
                                                            <input type="hidden" value="2a6e6db66e" name="_wp_unfiltered_html_comment" id="_wp_unfiltered_html_comment"> </fieldset>
                                                    </div>
                                                </div>
                                            </form>
                                            <div id="trash-undo-holder" class="hidden">
                                                <div class="trash-undo-inside">Comment by <strong></strong> moved to the trash. <span class="undo untrash"><a href="#">Undo</a></span></div>
                                            </div>
                                            <div id="spam-undo-holder" class="hidden">
                                                <div class="spam-undo-inside">Comment by <strong></strong> marked as spam. <span class="undo unspam"><a href="#">Undo</a></span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="postbox-container" id="postbox-container-2">
                        <div class="meta-box-sortables ui-sortable" id="side-sortables">
                            <div class="postbox " id="dashboard_quick_press">
                                <button aria-expanded="true" class="handlediv button-link" type="button"><span class="screen-reader-text">Toggle panel: <span class="hide-if-no-js">Quick Draft</span> <span class="hide-if-js">Drafts</span></span><span aria-hidden="true" class="toggle-indicator"></span></button>
                                <h2 class="hndle ui-sortable-handle"><span><span class="hide-if-no-js">Quick Draft</span> <span class="hide-if-js">Drafts</span></span></h2>
                                <div class="inside">

                                    <form class="initial-form hide-if-no-js" id="quick-press" method="post" action="http://localhost:8082/wordpress1/wp-admin/post.php" name="post">


                                        <div id="title-wrap" class="input-text-wrap">
                                            <label id="title-prompt-text" for="title" class="prompt">

                                                Title </label>
                                            <input type="text" autocomplete="off" id="title" name="post_title">
                                        </div>

                                        <div id="description-wrap" class="textarea-wrap">
                                            <label id="content-prompt-text" for="content" class="prompt">What’s on your mind?</label>
                                            <textarea autocomplete="off" cols="15" rows="3" class="mceEditor" id="content" name="content"></textarea>
                                        </div>

                                        <p class="submit">
                                            <input type="hidden" value="post-quickdraft-save" id="quickpost-action" name="action">
                                            <input type="hidden" value="5" name="post_ID">
                                            <input type="hidden" value="post" name="post_type">
                                            <input type="hidden" value="43d398006d" name="_wpnonce" id="_wpnonce">
                                            <input type="hidden" value="/wordpress1/wp-admin/" name="_wp_http_referer">
                                            <input type="submit" value="Save Draft" class="button button-primary" id="save-post" name="save">
                                            <br class="clear">
                                        </p>

                                    </form>
                                </div>
                            </div>
                            <div class="postbox " id="dashboard_primary">
                                <button aria-expanded="true" class="handlediv button-link" type="button"><span class="screen-reader-text">Toggle panel: WordPress News</span><span aria-hidden="true" class="toggle-indicator"></span></button>
                                <h2 class="hndle ui-sortable-handle"><span>WordPress News</span></h2>
                                <div class="inside" style="">
                                    <div class="rss-widget">
                                        <ul>
                                            <li><a href="https://wordpress.org/news/2015/12/clifford/" class="rsswidget">WordPress 4.4 “Clifford”</a> <span class="rss-date">December 8, 2015</span>
                                                <div class="rssSummary">Version 4.4 of WordPress, named “Clifford” in honor of jazz trumpeter Clifford Brown, is available for download or update in your WordPress dashboard. New features in 4.4&nbsp;make your site more connected and responsive. Clifford also introduces a new default theme, Twenty Sixteen. Introducing Twenty Sixteen Our newest default theme, Twenty Sixteen, is a modern take […]</div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="rss-widget">
                                        <ul>
                                            <li><a href="http://wptavern.com/microsoft-adds-wordpress-data-source-and-template-to-windows-app-studio-beta" class="rsswidget">WPTavern: Microsoft Adds WordPress Data Source and Template to Windows App Studio Beta</a></li>
                                            <li><a href="http://wptavern.com/progress-report-adding-shiny-updates-for-wordpress-themes" class="rsswidget">WPTavern: Progress Report: Adding Shiny Updates for WordPress Themes</a></li>
                                            <li><a href="http://wptavern.com/add-telegram-to-jetpack-sharing-buttons" class="rsswidget">WPTavern: Add Telegram to Jetpack Sharing Buttons</a></li>
                                        </ul>
                                    </div>
                                    <div class="rss-widget">
                                        <ul>
                                            <li class="dashboard-news-plugin"><span>Popular Plugin:</span> <a class="dashboard-news-plugin-link" href="https://wordpress.org/plugins/ml-slider/">Meta Slider</a>&nbsp;<span>(<a title="Meta Slider" class="thickbox" href="plugin-install.php?tab=plugin-information&amp;plugin=ml-slider&amp;_wpnonce=cfc76f1faa&amp;TB_iframe=true&amp;width=772&amp;height=290">Install</a>)</span></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="postbox-container" id="postbox-container-3">
                        <div class="meta-box-sortables ui-sortable empty-container" id="column3-sortables"></div>
                    </div>
                    <div class="postbox-container" id="postbox-container-4">
                        <div class="meta-box-sortables ui-sortable empty-container" id="column4-sortables"></div>
                    </div>
                </div>

                <input type="hidden" value="c06e6bff32" name="closedpostboxesnonce" id="closedpostboxesnonce">
                <input type="hidden" value="83d37c26c0" name="meta-box-order-nonce" id="meta-box-order-nonce"> </div>
            <!-- dashboard-widgets-wrap -->

        </div>
        <!-- wrap -->


        <div class="clear"></div>
    </div>
    <!-- wpbody-content -->
    <div class="clear"></div>
</div>
<!-- wpbody -->
<div class="clear"></div>
</div>