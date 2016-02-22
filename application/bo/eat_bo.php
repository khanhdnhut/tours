<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

BO::autoloadBO('post');

class EatBO extends PostBO
{

    public $post_type = "eat";
    public $address;
    public $number_of_rooms;
    public $star;
    public $images;
    public $image_ids;
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

    public function setEatInfo($eatInfo)
    {
        if (!is_null($eatInfo)) {
            if (isset($eatInfo->address)) {
                $this->address = $eatInfo->address;
            }
            if (isset($eatInfo->number_of_rooms)) {
                $this->number_of_rooms = $eatInfo->number_of_rooms;
            }
            if (isset($eatInfo->star)) {
                $this->star = $eatInfo->star;
            }
            if (isset($eatInfo->image_id) && $eatInfo->image_id != "" && is_numeric($eatInfo->image_id)) {
                $this->image_id = $eatInfo->image_id;                
            }
            if (isset($eatInfo->image_url)) {
                $this->image_url = $eatInfo->image_url;                
            }
            if (isset($eatInfo->city_id)) {
                $this->city_id = $eatInfo->city_id;
            }
            if (isset($eatInfo->city_name)) {
                $this->city_name = $eatInfo->city_name;
            }
            if (isset($eatInfo->current_rating)) {
                $this->current_rating = $eatInfo->current_rating;
            }
            if (isset($eatInfo->current_rating)) {
                $this->vote_times = $eatInfo->vote_times;
            }
        }
    }
}
