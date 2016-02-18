<link media="all" type="text/css" href="<?php echo PUBLIC_CSS ?>includes/tag.css?ver=4.4" id="dashicons-css" rel="stylesheet" />
<style>
    .column ul li {
        background: rgba(0, 0, 0, 0) url("<?php echo PUBLIC_IMG; ?>icon/bullet.gif") no-repeat scroll 20px 7px;
        line-height: 160%;
        margin-bottom: 5px;
        overflow: hidden;
        padding-left: 30px;
    }

</style>

<div style="width:74.4%; text-align: justify;" class="column" id="ja-current-content">
    <div class="ja-content-main clearfix" id="ja-content-main" style="padding-bottom: 10px;">
        <?php
        if (isset($this->countryBO) && $this->countryBO != NULL) {

            ?>
            <div itemtype="/Article" itemscope="" class="item-page">
                <h1 class="contentheading2 clearfix">
                    <?php
                    if (isset($this->countryBO->name)) {
                        echo ABOUT_TITLE . $this->countryBO->name;
                    }

                    ?>          
                </h1>
                <div class="article-tools clearfix"></div>
                <div class="article-content">

                    <?php
                    if (isset($this->countryBO->overview) && $this->countryBO->overview != "" && 
                        $this->countryBO->overview != NULL) {

                        ?>
                        <h2 id="<?php echo OVERVIEW_TITLE ?>">
                            <span style="color:#2C679D;"><?php echo OVERVIEW_TITLE ?></span>
                        </h2>
                        <p>
                            <?php
                            echo str_replace("\n", "<br>", $this->countryBO->overview);

                            ?>
                        </p>

                        <?php
                    }

                    if (isset($this->countryBO->history) && $this->countryBO->history != "" && 
                        $this->countryBO->history != NULL) {

                        ?>
                        <h2 id="<?php echo HISTORY_TITLE ?>">
                            <span style="color:#2C679D;"><?php echo HISTORY_TITLE ?></span>
                        </h2>
                        <p>
                            <?php
                            echo str_replace("\n", "<br>", $this->countryBO->history);

                            ?>
                        </p>

                        <?php
                    }

                    if (isset($this->countryBO->weather) && $this->countryBO->weather != "" && 
                        $this->countryBO->weather != NULL) {

                        ?>
                        <h2 id="<?php echo WEATHER_TITLE ?>">
                            <span style="color:#2C679D;"><?php echo WEATHER_TITLE ?></span>
                        </h2>
                        <p>
                            <?php
                            echo str_replace("\n", "<br>", $this->countryBO->weather);

                            ?>
                        </p>

                        <?php
                    }

                    if (isset($this->countryBO->image_weathers) && $this->countryBO->image_weathers != "" && 
                        $this->countryBO->image_weathers != NULL) {
                        foreach ($this->countryBO->image_weathers as $image) {
                            if (isset($image->image_url)) {

                                ?>
                                <p style="text-align: center;">
                                    <img data-u="image" src="<?php echo URL . $image->medium_large_url; ?>" style="max-width: 100%;"/>
                                </p>

                                <?php
                            }
                        }
                    }

                    if (isset($this->countryBO->passport_visa) && $this->countryBO->passport_visa != "" && 
                        $this->countryBO->passport_visa != NULL) {

                        ?>
                        <h2 id="<?php echo PASSPORT_VISA_TITLE ?>">
                            <span style="color:#2C679D;"><?php echo PASSPORT_VISA_TITLE ?></span>
                        </h2>
                        <p>
                            <?php
                            echo str_replace("\n", "<br>", $this->countryBO->passport_visa);

                            ?>
                        </p>

                        <?php
                    }
                    if (isset($this->countryBO->currency) && $this->countryBO->currency != "" && 
                        $this->countryBO->currency != NULL) {

                        ?>
                        <h2 id="<?php echo CURRENCY_TITLE ?>">
                            <span style="color:#2C679D;"><?php echo CURRENCY_TITLE ?></span>
                        </h2>
                        <p>
                            <?php
                            echo str_replace("\n", "<br>", $this->countryBO->currency);

                            ?>
                        </p>

                        <?php
                    }
                    if (isset($this->countryBO->phone_internet_service) && $this->countryBO->phone_internet_service != "" && 
                        $this->countryBO->phone_internet_service != NULL) {

                        ?>
                        <h2 id="<?php echo PHONE_INTERNET_SERVICE_TITLE ?>">
                            <span style="color:#2C679D;"><?php echo PHONE_INTERNET_SERVICE_TITLE ?></span>
                        </h2>
                        <p>
                            <?php
                            echo str_replace("\n", "<br>", $this->countryBO->phone_internet_service);

                            ?>
                        </p>

                        <?php
                    }
                    if (isset($this->countryBO->transportation) && $this->countryBO->transportation != "" && 
                        $this->countryBO->transportation != NULL) {

                        ?>
                        <h2 id="<?php echo TRANSPORTATION_TITLE ?>">
                            <span style="color:#2C679D;"><?php echo TRANSPORTATION_TITLE ?></span>
                        </h2>
                        <p>
                            <?php
                            echo str_replace("\n", "<br>", $this->countryBO->transportation);

                            ?>
                        </p>

                        <?php
                    }
                    if (isset($this->countryBO->food_drink) && $this->countryBO->food_drink != "" && 
                        $this->countryBO->food_drink != NULL) {

                        ?>
                        <h2 id="<?php echo FOOD_DRINK_TITLE ?>">
                            <span style="color:#2C679D;"><?php echo FOOD_DRINK_TITLE ?></span>
                        </h2>
                        <p>
                            <?php
                            echo str_replace("\n", "<br>", $this->countryBO->food_drink);

                            ?>
                        </p>

                        <?php
                    }
                    if (isset($this->countryBO->public_holiday) && $this->countryBO->public_holiday != "" && 
                        $this->countryBO->public_holiday != NULL) {
                        $public_holiday = explode("\n", trim($this->countryBO->public_holiday));
                        if (count($public_holiday) > 0) {

                            ?>
                            <h2 id="<?php echo PUBLIC_HOLIDAY_TITLE ?>">
                                <span style="color:#2C679D;"><?php echo PUBLIC_HOLIDAY_TITLE ?></span>
                            </h2>
                            <ul>

                                <?php
                                foreach ($public_holiday as $predeparture_check) {
                                    if ($predeparture_check != "" && $predeparture_check != NULL) {

                                        ?>
                                        <li>
                                            <?php echo $predeparture_check; ?>
                                        </li>                                            
                                        <?php
                                    }
                                }

                                ?>
                            </ul>

                            <?php
                        }
                    }
                    if (isset($this->countryBO->predeparture_check_list) && $this->countryBO->predeparture_check_list != "" && 
                        $this->countryBO->predeparture_check_list != NULL) {
                        $predeparture_check_list = explode("\n", trim($this->countryBO->predeparture_check_list));
                        if (count($predeparture_check_list) > 0) {

                            ?>
                            <h2 id="<?php echo PREDEPARTURE_CHECK_LIST_TITLE ?>">
                                <span style="color:#2C679D;"><?php echo PREDEPARTURE_CHECK_LIST_TITLE ?></span>
                            </h2>
                            <ul>

                                <?php
                                foreach ($predeparture_check_list as $predeparture_check) {
                                    if ($predeparture_check != "" && $predeparture_check != NULL) {

                                        ?>
                                        <li>
                                            <?php echo $predeparture_check; ?>
                                        </li>                                            
                                        <?php
                                    }
                                }

                                ?>
                            </ul>
                            <?php
                        }
                    }

                    if (isset($this->countryBO->tag_list) && count($this->countryBO->tag_list) > 0) {

                        ?>
                        <h2 id="<?php echo TAGS_TITLE ?>">
                            <span style="color:#2C679D;"><?php echo TAGS_TITLE ?></span>
                        </h2>
                        <ul class="tagList">
                            <?php
                            $tagArray = array();
                            foreach ($this->countryBO->tag_list as $tag) {
                                $tagArray[] = $tag->name;

                                ?>
                                <li style="background: transparent none repeat scroll 0% 0%; padding-left: 10px;"><a class="tag" href="<?php echo URL . CONTEXT_PATH_TAG_INFO . $tag->term_taxonomy_id . "/" . $tag->slug; ?>/" title=""><span class="arrow2"></span><?php echo $tag->name; ?></a></li>
                                        <?php
                                    }

                                    ?>
                        </ul><br>
                        <?php
                    }

                    ?>
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
$module = array("findTours", "destinationsVietnam", "aboutVietnam", "destinationsVietnam");
require VIEW_TEMPLATES_PATH . 'loadModule.php';

?>


