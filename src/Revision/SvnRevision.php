<?php
/**
 * Created by PhpStorm.
 * User: Jenner
 * Date: 2016/5/22
 * Time: 14:19
 */

namespace Jenner\Rocket\Revision;


use Symfony\Component\Process\ProcessBuilder;

class SvnRevision extends AbstractRevision
{

    /**
     * check out a branch
     *
     * @param $branch
     * @return mixed
     */
    public function checkOut($branch)
    {
        $builder = $this->bindUserNameAndPassword($this->getCommandBuilder())
            ->add("checkout")->add($this->url)->add($this->path);

        return $this->runCommand($builder);
    }

    /**
     * update current branch
     *
     * @return mixed
     */
    public function update()
    {
        $builder = $this->bindUserNameAndPassword($this->getCommandBuilder())
            ->add("update")->add($this->path);

        return $this->runCommand($builder);
    }

    /**
     * update current branch to version
     *
     * @param $version
     * @return mixed
     */
    public function restore($version)
    {
        // TODO: Implement restore() method.
    }

    /**
     * get all branches
     *
     * @return mixed
     */
    public function getBranches()
    {
        // TODO: Implement getBranches() method.
    }

    /**
     * get all tags
     *
     * @return mixed
     */
    public function getTags()
    {
        // TODO: Implement getTags() method.
    }

    /**
     * get all commits
     *
     * @return mixed
     */
    public function getCommits()
    {
        // TODO: Implement getCommits() method.
    }

    /**
     * @return ProcessBuilder
     */
    protected function getCommandBuilder()
    {
        $builder = ProcessBuilder::create();
        $builder->setPrefix('/usr/bin/env');
        if (!empty($this->command_path)) {
            $builder->add($this->command_path . DIRECTORY_SEPARATOR . 'svn');
        }
        return $builder;
    }

    protected function bindUserNameAndPassword(ProcessBuilder $builder) {
        if(!empty($this->user_name) && !empty($this->password)) {
            $builder->add("--username '" . $this->user_name . "'")
                ->add("--password '" . $this->password . "'");
        }

        return $builder;
    }
}