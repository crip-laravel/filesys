<?php namespace Crip\Filesys\App\Controllers;

use Crip\Filesys\TestCase;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Class TreeControllerTest
 * @package Controllers
 */
class TreeControllerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->ctrl()->store(new FormRequest([
            'folder' => '',
            'name' => 'level-1'
        ]));

        $this->ctrl()->store(new FormRequest([
            'folder' => 'level-1',
            'name' => 'level-2'
        ]));

        $this->ctrl()->store(new FormRequest([
            'folder' => 'level-1',
            'name' => 'level-2'
        ]));
    }

    /**
     * Dummy
     */
    public function testDummy()
    {
        self::assertTrue(true);
    }

    /*
     * Here a got an error from league package:
     * untimeException: SplFileInfo::getType(): Lstat failed for phpvfs58fe34349b2b8:/\level-1
     * and can`t test this functionality.

        public function testCanSeeTreeStructure()
        {
            $response = $this->treeCtrl()->__invoke();

            $info = $response->getData(true);
            dd($info);
        }*/

    /**
     * @return FolderController
     */
    private function ctrl()
    {
        return app()->make(FolderController::class);
    }

    /**
     * @return TreeController
     */
    private function treeCtrl()
    {
        return app()->make(TreeController::class);
    }
}