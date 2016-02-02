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
    public $rating;

    function __construct()
    {
        parent::__construct();
        
    }
}
