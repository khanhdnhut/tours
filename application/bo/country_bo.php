<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
BO::autoloadBO("taxonomy");

class CountryBO extends TaxonomyBO
{

    public $taxonomy = "country";
    public $postBO;
    
    public $overview = "";
    public $history = "";
    public $weather = "";    
    public $passport_visa = "";
    public $currency = "";
    public $phone_internet_service = "";
    public $transportation = "";
    public $food_drink = "";
    public $public_holiday = "";
    public $predeparture_check_list = "";
    
    public $image_weather_ids;
    public $image_weathers;
    public $tag_list;

}
