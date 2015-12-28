<div style="width:74.4%" class="column" id="ja-current-content">
    <div class="ja-content-main clearfix" id="ja-content-main">
        <?php 
            echo URL;
        ?>
    </div>
</div>

<?php
    $module = array("findTours", "topBudgetTours", "howToBook", "travelNews");
    require VIEW_TEMPLATES_PATH . 'loadModule.php';  
?>


