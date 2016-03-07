<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

BO::autoloadBO('post');

class ShoppingBO extends PostBO
{

    public $post_type = "shopping";
    public $image_id;
    public $image_url;
    public $city_id;
    public $city_name;
    public $country_id;
    public $country_name;
    public $current_rating;
    public $vote_times;
    public $tag_list;

    function __construct()
    {
        parent::__construct();
    }

    public function setShoppingInfo($shoppingInfo)
    {
        if (!is_null($shoppingInfo)) {
            if (isset($shoppingInfo->image_id) && $shoppingInfo->image_id != "" && is_numeric($shoppingInfo->image_id)) {
                $this->image_id = $shoppingInfo->image_id;                
            }
            if (isset($shoppingInfo->image_url)) {
                $this->image_url = $shoppingInfo->image_url;                
            }
            if (isset($shoppingInfo->city_id)) {
                $this->city_id = $shoppingInfo->city_id;
            }
            if (isset($shoppingInfo->city_name)) {
                $this->city_name = $shoppingInfo->city_name;
            }
            if (isset($shoppingInfo->current_rating)) {
                $this->current_rating = $shoppingInfo->current_rating;
            }
            if (isset($shoppingInfo->current_rating)) {
                $this->vote_times = $shoppingInfo->vote_times;
            }
        }
    }
}
