<?php

namespace App\Models;

use Phormium\Model;

class Episode extends Model
{
    protected static $_meta = array(
        'database' => 'mysql',
        'table' => 'downloaded',
        'pk' => 'id'
    );

    public $id;
    public $show_id;
    public $episode_id;
    public $show_title;
    public $title;
    public $link;
    public $created_at;
}
