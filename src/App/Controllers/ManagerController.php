<?php namespace Crip\Filesys\App\Controllers;

use Crip\Core\Helpers\Str;
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
        $iconDir = $this->package->config('icons.url');

        $foldersUrl = action('\\' . $this->package->config('actions.folder') . '@show', '');
        $filesUrl = action('\\' . $this->package->config('actions.file') . '@show', '');
        $treeUrl = action('\\' . $this->package->config('actions.tree'));
        $dirIconUrl = $iconDir . $this->package->config('icons.files.dir');

        $requestInput = $request->all();
        $input = $this->stringify($requestInput);

        $authConfig = $this->package->config('authorization');
        $authorization = $this->stringify($authConfig);

        $thumbConfig = $this->package->config('thumbs');
        $thumbs = $this->stringify($thumbConfig);

        if (!is_null($this->package->config('resource_url', null))) {
            $view = $this->package->config('resource_url', null);

            return view($view,
                compact('input', 'filesUrl', 'foldersUrl', 'treeUrl', 'dirIconUrl',
                    'iconDir', 'authorization', 'thumbs'));
        }

        return $this->package->view('master',
            compact('input', 'filesUrl', 'foldersUrl', 'treeUrl', 'dirIconUrl',
                'iconDir', 'authorization', 'thumbs'));
    }

    /**
     * Stringify PHP array to json string and make it usable inside attribute
     * tags.
     * @param array $data
     * @return string
     */
    private function stringify(array $data): string
    {
        return str_replace('"', '\'', json_encode($data));
    }
}