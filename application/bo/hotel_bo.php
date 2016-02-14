<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

BO::autoloadBO('post');

class HotelBO extends PostBO
{

    public $post_type = "hotel";
    public $address;
    public $number_of_rooms;
    public $star;
    public $image_id;
    public $image_url;
    public $city_id;
    public $city_name;
    public $current_rating;
    public $vote_times;
    public $tag_list;

    function __construct()
    {
        parent::__construct();
    }

    public function setHotelInfo($hotelInfo)
    {
        if (!is_null($hotelInfo)) {
            if (isset($hotelInfo->address)) {
                $this->address = $hotelInfo->address;
            }
            if (isset($hotelInfo->number_of_rooms)) {
                $this->number_of_rooms = $hotelInfo->number_of_rooms;
            }
            if (isset($hotelInfo->star)) {
                $this->star = $hotelInfo->star;
            }
            if (isset($hotelInfo->image_id) && $hotelInfo->image_id != "" && is_numeric($hotelInfo->image_id)) {
                $this->image_id = $hotelInfo->image_id;                
            }
            if (isset($hotelInfo->image_url)) {
                $this->image_url = $hotelInfo->image_url;                
            }
            if (isset($hotelInfo->city_id)) {
                $this->city_id = $hotelInfo->city_id;
            }
            if (isset($hotelInfo->city_name)) {
                $this->city_name = $hotelInfo->city_name;
            }
            if (isset($hotelInfo->current_rating)) {
                $this->current_rating = $hotelInfo->current_rating;
            }
            if (isset($hotelInfo->current_rating)) {
                $this->vote_times = $hotelInfo->vote_times;
            }
        }
    }
}
