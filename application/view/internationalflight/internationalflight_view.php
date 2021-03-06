<!--<link media="all" type="text/css" href="<?php echo PUBLIC_CSS ?>includes/tag.css?ver=4.4" id="dashicons-css" rel="stylesheet" />-->

<div style="width:74.4%" class="column" id="ja-current-content">
    <div class="ja-content-main clearfix" id="ja-content-main">
        <?php
        if (isset($this->internationalflightBO) && $this->internationalflightBO != NULL) {

            ?>
            <div itemtype="/Article" itemscope="" class="item-page">
                <h1 class="contentheading2 clearfix"><?php
                    if (isset($this->internationalflightBO->post_title)) {
                        echo $this->internationalflightBO->post_title;
                    }

                    ?></h1>
                <div class="article-tools clearfix">                    
                    <div class="size-1 extravote">
                        <span itemtype="/AggregateRating" itemscope="" itemprop="aggregateRating" class="extravote-stars">
                            <meta content="2" itemprop="ratingCount">
                            <span itemprop="ratingValue" style="width:<?php
                            if (isset($this->internationalflightBO->current_rating) && is_numeric($this->internationalflightBO->current_rating)) {
                                echo $this->internationalflightBO->current_rating * 100 / 5;
                            } else {
                                echo 0;
                            }

                            ?>%;" class="current-rating" id="rating_623_0"><?php
                                  if (isset($this->internationalflightBO->current_rating) && is_numeric($this->internationalflightBO->current_rating)) {
                                      echo $this->internationalflightBO->current_rating;
                                  } else {
                                      echo 0;
                                  }

                                  ?></span>
                            <span class="extravote-star"><a class="ev-5-stars" title="0.5 out of 5" onclick="javascript:JVXVote(623, 0.5, 8, 2, '0', 1, 1, 1);" href="">1</a></span>
                            <span class="extravote-star"><a class="ev-10-stars" title="1 out of 5" onclick="javascript:JVXVote(623, 1, 8, 2, '0', 1, 1, 1);" href="">1</a></span>
                            <span class="extravote-star"><a class="ev-15-stars" title="1.5 out of 5" onclick="javascript:JVXVote(623, 1.5, 8, 2, '0', 1, 1, 1);" href="">1</a></span>
                            <span class="extravote-star"><a class="ev-20-stars" title="2 out of 5" onclick="javascript:JVXVote(623, 2, 8, 2, '0', 1, 1, 1);" href="">1</a></span>
                            <span class="extravote-star"><a class="ev-25-stars" title="2.5 out of 5" onclick="javascript:JVXVote(623, 2.5, 8, 2, '0', 1, 1, 1);" href="">1</a></span>
                            <span class="extravote-star"><a class="ev-30-stars" title="3 out of 5" onclick="javascript:JVXVote(623, 3, 8, 2, '0', 1, 1, 1);" href="">1</a></span>
                            <span class="extravote-star"><a class="ev-35-stars" title="3.5 out of 5" onclick="javascript:JVXVote(623, 3.5, 8, 2, '0', 1, 1, 1);" href="">1</a></span>
                            <span class="extravote-star"><a class="ev-40-stars" title="4 out of 5" onclick="javascript:JVXVote(623, 4, 8, 2, '0', 1, 1, 1);" href="">1</a></span>
                            <span class="extravote-star"><a class="ev-45-stars" title="4.5 out of 5" onclick="javascript:JVXVote(623, 4.5, 8, 2, '0', 1, 1, 1);" href="">1</a></span>
                            <span class="extravote-star"><a class="ev-50-stars" title="5 out of 5" onclick="javascript:JVXVote(623, 5, 8, 2, '0', 1, 1, 1);" href="">1</a></span>
                        </span>
                        <span id="extravote_623_0" class="extravote-info">Rating <?php
                            if (isset($this->internationalflightBO->current_rating) && is_numeric($this->internationalflightBO->current_rating)) {
                                echo $this->internationalflightBO->current_rating;
                            } else {
                                echo 0;
                            }

                            ?> (<?php
                            if (isset($this->internationalflightBO->vote_times)) {
                                echo $this->internationalflightBO->vote_times;
                            } else {
                                echo 0;
                            }

                            ?> Votes)</span>
                    </div>
                </div>

                <div class="article-content">
                    <p>
                        <a target="_blank" title="<?php
                        if (isset($this->internationalflightBO->post_title)) {
                            echo $this->internationalflightBO->post_title;
                        }

                        ?>" rel="lightbox[623]" href="" class="thumbnail" style="">
                            <img style="float: right; height: 188px; width: 250px;" src="<?php
                            if (isset($this->internationalflightBO->image_url)) {
                                echo URL . $this->internationalflightBO->image_url;
                            }

                            ?>" class="maps img-border" alt="Sofitel Legend Metropole Hanoi Internationalflight">
                        </a>
                    </p>
                    <p><?php
                        if (isset($this->internationalflightBO->post_content)) {
                            echo $this->internationalflightBO->post_content;
//                            echo str_replace("\n", "<br>", $this->internationalflightBO->post_content);
                        }

                        ?></p>                    
                </div>
            </div>
            <?php
        } else {
            $this->renderFeedbackMessages();
        }

        ?>
    </div>
</div>

<?php
$module = array("findTours", "hotelsVietnam", "aboutVietnam", "destinationsVietnam");
require VIEW_TEMPLATES_PATH . 'loadModule.php';

?>


