<?php namespace Crip\Filesys\App\Controllers;

use Illuminate\Http\Request;

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
        $filesUrl = action('\\' . $this->package->config('actions.file') . '@show', '');
        $foldersUrl = action('\\' . $this->package->config('actions.folder') . '@show', '');
        $treeUrl = action('\\' . $this->package->config('actions.tree'));
        $iconDir = $this->package->config('icons.url');
        $dirIconUrl = $iconDir . $this->package->config('icons.files.dir');

        return $this->package->view('master',
            compact('input', 'filesUrl', 'foldersUrl', 'treeUrl', 'dirIconUrl', 'iconDir'));
    }
}