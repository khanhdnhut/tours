<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

BO::autoloadBO('post');

class InternalflightBO extends PostBO
{

    public $post_type = "internalflight";
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

    public function setInternalflightInfo($internalflightInfo)
    {
        if (!is_null($internalflightInfo)) {
            if (isset($internalflightInfo->image_id) && $internalflightInfo->image_id != "" && is_numeric($internalflightInfo->image_id)) {
                $this->image_id = $internalflightInfo->image_id;                
            }
            if (isset($internalflightInfo->image_url)) {
                $this->image_url = $internalflightInfo->image_url;                
            }
            if (isset($internalflightInfo->city_id)) {
                $this->city_id = $internalflightInfo->city_id;
            }
            if (isset($internalflightInfo->city_name)) {
                $this->city_name = $internalflightInfo->city_name;
            }
            if (isset($internalflightInfo->current_rating)) {
                $this->current_rating = $internalflightInfo->current_rating;
            }
            if (isset($internalflightInfo->current_rating)) {
                $this->vote_times = $internalflightInfo->vote_times;
            }
        }
    }
}
