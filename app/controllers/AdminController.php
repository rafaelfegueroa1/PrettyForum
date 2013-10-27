<?php
/**
 * User: Rogier
 * Date: 26-10-13
 * Time: 15:30
 *
 */

class AdminController extends BaseController {

    public function __construct()
    {
        $this->beforeFilter(function()
        {
            if(!Auth::user()->canAccessAdminMenu())
            {
                return View::make('layouts.error.standardError')
                    ->with('error', 'You do not have sufficient rights to visit this page.');
            }
        });
    }

}