<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

BO::autoloadBO('post');

class NightlifeBO extends PostBO
{

    public $post_type = "nightlife";
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

    public function setNightlifeInfo($nightlifeInfo)
    {
        if (!is_null($nightlifeInfo)) {
            if (isset($nightlifeInfo->image_id) && $nightlifeInfo->image_id != "" && is_numeric($nightlifeInfo->image_id)) {
                $this->image_id = $nightlifeInfo->image_id;                
            }
            if (isset($nightlifeInfo->image_url)) {
                $this->image_url = $nightlifeInfo->image_url;                
            }
            if (isset($nightlifeInfo->city_id)) {
                $this->city_id = $nightlifeInfo->city_id;
            }
            if (isset($nightlifeInfo->city_name)) {
                $this->city_name = $nightlifeInfo->city_name;
            }
            if (isset($nightlifeInfo->current_rating)) {
                $this->current_rating = $nightlifeInfo->current_rating;
            }
            if (isset($nightlifeInfo->current_rating)) {
                $this->vote_times = $nightlifeInfo->vote_times;
            }
        }
    }
}
