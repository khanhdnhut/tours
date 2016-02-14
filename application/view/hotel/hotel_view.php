<div style="width:74.4%" class="column" id="ja-current-content">
    <div class="ja-content-main clearfix" id="ja-content-main">
        <?php
        if (isset($this->hotelBO) && $this->hotelBO != NULL) {

            ?>
            <div itemtype="/Article" itemscope="" class="item-page">
                <h1 class="contentheading2 clearfix"><?php
                    if (isset($this->hotelBO->post_title)) {
                        echo $this->hotelBO->post_title;
                    }

                    ?></h1>
                <div class="article-tools clearfix">                    
                    <div class="size-1 extravote">
                        <span itemtype="/AggregateRating" itemscope="" itemprop="aggregateRating" class="extravote-stars">
                            <meta content="2" itemprop="ratingCount">
                            <span itemprop="ratingValue" style="width:<?php
                            if (isset($this->hotelBO->current_rating) && is_numeric($this->hotelBO->current_rating)) {
                                echo $this->hotelBO->current_rating * 100 / 5;
                            } else {
                                echo 0;
                            }

                            ?>%;" class="current-rating" id="rating_623_0"><?php
                                  if (isset($this->hotelBO->current_rating) && is_numeric($this->hotelBO->current_rating)) {
                                      echo $this->hotelBO->current_rating;
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
                            if (isset($this->hotelBO->current_rating) && is_numeric($this->hotelBO->current_rating)) {
                                echo $this->hotelBO->current_rating;
                            } else {
                                echo 0;
                            }

                            ?> (<?php
                            if (isset($this->hotelBO->vote_times)) {
                                echo $this->hotelBO->vote_times;
                            } else {
                                echo 0;
                            }

                            ?> Votes)</span>
                    </div>
                </div>

                <div class="article-content">
                    <p>
                        <a target="_blank" title="<?php
                        if (isset($this->hotelBO->post_title)) {
                            echo $this->hotelBO->post_title;
                        }

                        ?>" rel="lightbox[623]" href="" class="thumbnail" style="">
                            <img style="float: right; height: 188px; width: 250px;" src="<?php
                            if (isset($this->hotelBO->image_url)) {
                                echo URL . $this->hotelBO->image_url;
                            }

                            ?>" class="maps img-border" alt="Sofitel Legend Metropole Hanoi Hotel">
                        </a>
                    </p>
                    <p><?php
                        if (isset($this->hotelBO->post_content)) {
                            echo str_replace("\n", "<br>", $this->hotelBO->post_content);
                        }

                        ?></p>
                    <p>
                        <?php
                        if (isset($this->hotelBO->address)) {

                            ?>
                            <strong>Address</strong>: <?php echo $this->hotelBO->address; ?><br>
                            <?php
                        }

                        if (isset($this->hotelBO->number_of_rooms)) {

                            ?>
                            <strong>Number of rooms</strong>: <?php echo $this->hotelBO->number_of_rooms; ?><br>
                            <?php
                        }

                        if (isset($this->hotelBO->tag_list) && count($this->hotelBO->tag_list) > 0) {

                            ?>
                            <strong>Tags</strong>: <?php
                            $tagArray = array();
                            foreach ($this->hotelBO->tag_list as $tag) {
                                $tagArray[] = $tag->name;
                            }
                            if (count($tagArray) > 0) {
                                echo join(' - ', $tagArray);
                            }

                            ?><br>
                            <?php
                        }

                        ?>
                    </p>
                    <?php
                    if (isset($this->hotelBO->star)) {

                        ?>
                        <div class="star<?php echo $this->hotelBO->star; ?>">
                            <?php
                        }

                        ?>

                        &nbsp;
                    </div>
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


