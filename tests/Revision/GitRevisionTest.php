<?php
/**
 * Created by PhpStorm.
 * User: Jenner
 * Date: 2016/5/23
 * Time: 17:06
 */

namespace Jenner\Rocket\Test;


use Jenner\Rocket\Revision\GitRevision;

class GitRevisionTest extends \PHPUnit_Framework_TestCase
{
    public function testCheckOut() {
        $git = new GitRevision();
        $git->setUrl("https://git.oschina.net/rocket_test/rocket.git");
        $git->setPath("/tmp/rocket");
        $git->setUserName("huyanpingz@126.com");
        $git->setPassword("987654321abc");
        echo $git->checkOut("master");
    }
}