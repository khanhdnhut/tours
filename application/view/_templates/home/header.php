<!DOCTYPE html>
<html id="ls-global" slick-uniqueid="3" xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-gb" lang="en-gb">
    <head>
        <title>
            <?php
            if (isset($head_title))
                echo $head_title;
            else
                echo HEADER_TITLE_DEFAULT;
            ?>
        </title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
        <meta name="robots" content="index,follow"/>
        <meta name="keywords" content="
        <?php
        if (isset($head_meta_keywords))
            $head_meta_keywords;
        else
            echo HEADER_META_KEYWORDS_DEFAULT;
        ?>"/>

        <meta name="description" content="
        <?php
        if (isset($head_meta_description))
            $head_meta_description;
        else
            echo HEADER_META_DESCRIPTION_DEFAULT;
        ?>"/>

        <meta property="og:site_name" content="
        <?php
        if (isset($head_meta_og_site_name))
            $head_meta_og_site_name;
        else
            echo HEADER_META_OG_SITE_NAME_DEFAULT;
        ?>"/>

        <meta property="og:type" content="
        <?php
        if (isset($head_meta_og_type))
            $head_meta_og_type;
        else
            echo HEADER_META_OG_TYPE_DEFAULT;
        ?>"/>

        <meta property="og:url" content="
        <?php
        if (isset($head_meta_og_url))
            $head_meta_og_url;
        else
            echo URL;
        ?>"/>

        <meta property="og:title" content="
        <?php
        if (isset($head_meta_og_title))
            $head_meta_og_title;
        else
            echo HEADER_META_OG_TITLE_DEFAULT;
        ?>"/>


        <meta property="og:description" content="
        <?php
        if (isset($head_meta_og_description))
            $head_meta_og_description;
        else
            echo HEADER_META_OG_DESCRIPTION_DEFAULT;
        ?>"/>

        <meta property="og:image" content="
        <?php
        if (isset($head_meta_og_image))
            PUBLIC_IMG . $head_meta_og_image;
        else
            echo PUBLIC_IMG . HEADER_META_OG_IMAGE;
        ?>"/>

        <meta rel="canonical" href="
        <?php
        if (isset($head_canonical))
            $head_canonical;
        else
            echo URL;
        ?>"/>

        <meta rel="apple-touch-icon-precomposed" sizes="72x72" href="
        <?php
        if (isset($head_apple_touch_icon_precomposed_72))
            PUBLIC_IMG . $head_apple_touch_icon_precomposed_72;
        else
            echo PUBLIC_IMG . HEADER_APPLE_TOUCH_ICON_PRECOMPOSED_72_DEFAULT;
        ?>"/>

        <meta rel="apple-touch-icon-precomposed" sizes="144x144" href="
        <?php
        if (isset($head_apple_touch_icon_precomposed_114))
            PUBLIC_IMG . $head_apple_touch_icon_precomposed_114;
        else
            echo PUBLIC_IMG . HEADER_APPLE_TOUCH_ICON_PRECOMPOSED_114_DEFAULT;
        ?>"/>

        <meta rel="apple-touch-icon-precomposed" sizes="57x57" href="
        <?php
        if (isset($head_apple_touch_icon_precomposed_57))
            PUBLIC_IMG . $head_apple_touch_icon_precomposed_57;
        else
            echo PUBLIC_IMG . HEADER_APPLE_TOUCH_ICON_PRECOMPOSED_57_DEFAULT;
        ?>"/>

        <meta rel="apple-touch-icon-precomposed" sizes="1x1" href="
        <?php
        if (isset($head_apple_touch_icon_precomposed_1))
            PUBLIC_IMG . $head_apple_touch_icon_precomposed_1;
        else
            echo PUBLIC_IMG . HEADER_APPLE_TOUCH_ICON_PRECOMPOSED_1_DEFAULT;
        ?>"/>

        <link rel="nokia-touch-icon" href="
        <?php
        if (isset($head_nokia_touch_icon))
            PUBLIC_IMG . $head_nokia_touch_icon;
        else
            echo PUBLIC_IMG . HEADER_NOKIA_TOUCH_ICON_DEFAULT;
        ?>"/>

        <link rel="shortcut icon" type="image/x-icon" href="
        <?php
        if (isset($head_shortcut_icon))
            PUBLIC_IMG . $head_shortcut_icon;
        else
            echo PUBLIC_IMG . HEADER_SHORTCUT_ICON_DEFAULT;
        ?>"/>

        <link href="<?php echo PUBLIC_CSS; ?>a49076c99c6cdb6c02f556b8d568a1e4.css" type="text/css" rel="stylesheet"/>
        <script async="" src="//www.google-analytics.com/analytics.js"></script>
        <script async="" src="<?php echo PUBLIC_JS; ?>analytics.js"></script>
        <script src="<?php echo PUBLIC_JS; ?>bfa91aa4ba6dbba485619f96e31645f7.js" type="application/javascript"></script>

        <!--[if ie]><link href="<?php echo PUBLIC_CSS; ?>template-ie.css" type="text/css" rel="stylesheet" /><![endif]-->
        <!--[if ie]><link href="<?php echo PUBLIC_CSS; ?>template-ie-2.css" type="text/css" rel="stylesheet" /><![endif]-->
        <!--[if ie 7]><link href="<?php echo PUBLIC_CSS; ?>template-ie7.css" type="text/css" rel="stylesheet" /><![endif]-->
        <!--[if ie 7]><link href="<?php echo PUBLIC_CSS; ?>template-ie7-2.css" type="text/css" rel="stylesheet" /><![endif]-->

        <link rel="stylesheet" href="data:text/css,@import%20url%28%27%20https%3A//static.tacdn.com/css2/widget/cdswidSSP-v2414594452b.css%20%27%29%3B"/>
        <script type="text/javascript" src="<?php echo PUBLIC_JS; ?>cdswidgets_m-c-v2102084671b.js"></script>
        <link type="text/css" href="<?php echo PUBLIC_CSS; ?>skin.css" rel="stylesheet"/>
        <link rel="stylesheet" href="data:text/css,@import%20url%28%27%20https%3A//static.tacdn.com/css2/widget/cdswidSSP-v2414594452b.css%20%27%29%3B"/>
        <script type="text/javascript" src="https://static.tacdn.com/js3/widget/cdswidgets_m-c-v2102084671b.js"></script>
        <link type="text/css" href="<?php echo PUBLIC_CSS; ?>skin.css" rel="stylesheet"/>
    </head>
