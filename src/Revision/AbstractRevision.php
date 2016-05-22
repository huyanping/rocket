<?php
/**
 * Created by PhpStorm.
 * User: Jenner
 * Date: 2016/5/22
 * Time: 12:52
 */

namespace Jenner\Rocket\Revision;


use Symfony\Component\Process\Exception\RuntimeException;
use Symfony\Component\Process\ProcessBuilder;

abstract class AbstractRevision
{

    /**
     * @var string command path
     */
    protected $command_path;

    /**
     * @var string project branch
     */
    protected $branch;
    /**
     * @var string project path
     */
    protected $path;
    /**
     * @var string version control url
     */
    protected $url;

    /**
     * @var string version control user name
     */
    protected $user_name;

    /**
     * @var string version control password
     */
    protected $password;

    /**
     * @return string
     */
    public function getBranch()
    {
        return $this->branch;
    }

    /**
     * @param $branch
     * @return $this
     */
    public function setBranch($branch)
    {
        $this->branch = $branch;
        return $this;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param $path
     * @return $this
     */
    public function setPath($path)
    {
        if (!file_exists($path)) {
            if (!mkdir($path, 0755, true)) {
                throw new RuntimeException("create path failed");
            }
        }
        $this->path = $path;
        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param $url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserName()
    {
        return $this->user_name;
    }

    /**
     * @param $user_name
     * @return $this
     */
    public function setUserName($user_name)
    {
        $this->user_name = $user_name;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param $password
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * check out a branch
     *
     * @param $branch
     * @return mixed
     */
    abstract public function checkOut($branch);

    /**
     * update current branch
     *
     * @return mixed
     */
    abstract public function update();

    /**
     * update current branch to version
     *
     * @param $version
     * @return mixed
     */
    abstract public function restore($version);

    /**
     * get all branches
     *
     * @return array
     */
    abstract public function getBranches();

    /**
     * get all tags
     *
     * @return array
     */
    abstract public function getTags();

    /**
     * get all commits
     *
     * @return array
     */
    abstract public function getCommits();

    protected function runCommand(ProcessBuilder $builder)
    {
        $process = $builder->getProcess();
        $process->run();
        if ($process->isSuccessful()) {
            return $process->getOutput();
        } else {
            throw new \RuntimeException("run command failed with message:" . PHP_EOL . $process->getErrorOutput());
        }
    }
}