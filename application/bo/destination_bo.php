<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
BO::autoloadBO("taxonomy");

class DestinationBO extends TaxonomyBO
{

    public $taxonomy = "destination";
    public $post_content_1 = "";
    public $post_content_2 = "";
    public $images;
    public $image_ids;

}
