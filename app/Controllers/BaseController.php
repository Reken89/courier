<?php

namespace App\Controllers;

use App\View\BaseView;
use App\Models\ScheduleModel;
use App\Models\RegionModel;
use App\Models\CourierModel;

class BaseController 
{
    public $view;
    public $schedule;
    public $region;
    public $courier;
    
    public function __construct() 
    {
        $this->view = new BaseView();
        $this->schedule = new ScheduleModel();
        $this->region = new RegionModel();
        $this->courier = new CourierModel();
    }
    
}

