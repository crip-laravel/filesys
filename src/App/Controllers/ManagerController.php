<?php namespace Crip\Filesys\App\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class ManagerController
 * @package Crip\Filesys\App\Controllers
 */
class ManagerController extends BaseController
{
    /**
     * Show the manager UI for the crip filesys.
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function __invoke(Request $request)
    {
        $input = $request->all();
        return $this->package->view('master', compact('input'));
    }
}