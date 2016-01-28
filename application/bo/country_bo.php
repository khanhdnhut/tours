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
    public $edit_country_per_page = null;
    public $manage_country_columns_show = null;

}
