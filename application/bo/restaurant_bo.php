<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

BO::autoloadBO('post');

class RestaurantBO extends PostBO
{

    public $post_type = "restaurant";
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

    public function setRestaurantInfo($restaurantInfo)
    {
        if (!is_null($restaurantInfo)) {
            if (isset($restaurantInfo->image_id) && $restaurantInfo->image_id != "" && is_numeric($restaurantInfo->image_id)) {
                $this->image_id = $restaurantInfo->image_id;                
            }
            if (isset($restaurantInfo->image_url)) {
                $this->image_url = $restaurantInfo->image_url;                
            }
            if (isset($restaurantInfo->city_id)) {
                $this->city_id = $restaurantInfo->city_id;
            }
            if (isset($restaurantInfo->city_name)) {
                $this->city_name = $restaurantInfo->city_name;
            }
            if (isset($restaurantInfo->current_rating)) {
                $this->current_rating = $restaurantInfo->current_rating;
            }
            if (isset($restaurantInfo->current_rating)) {
                $this->vote_times = $restaurantInfo->vote_times;
            }
        }
    }
}
