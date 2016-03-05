<?php
/**
 * Author: https://github.com/davinder17s
 * Email: davinder17s@gmail.com
 * Repository: https://github.com/davinder17s/codeigniter-middleware
 */

class Home extends MY_Controller {

    protected function middleware()
    {
        return array('admin_auth');
    }

    public function index()
    {
        print_r($this->middlewares['admin_auth']->roles);
    }
}