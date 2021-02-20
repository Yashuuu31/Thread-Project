<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashbordController extends Controller
{   
    public $moduleName = "Dashbord";
    public $view = "pages/dashbord";
    public function index(){
        $moduleName = $this->moduleName;
        return view("$this->view/index", compact('moduleName'));
    }
}
