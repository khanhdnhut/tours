<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
BO::autoloadBO("taxonomy");

class CityBO extends TaxonomyBO
{

    public $taxonomy = "city";
    public $postBO;
    public $post_content_1 = "";
    public $post_content_2 = "";
    public $images;
    public $image_ids;
    public $current_rating;
    public $vote_times;
    public $tag_list;

}
