<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        $this->display();
    }

    public function index2(){
        $this->display();
    }

    public function about() {
        $this->display();
    }

    public function contact() {
        $this->display();
    }
}