<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

BO::autoloadBO('post');
class AttachmentBO extends BO 
{
    /** @var PostBO */
    public $attachment_post;
    public $attached_file;
    public $attachment_metadata;

    function __construct()
    {
        
    }
}