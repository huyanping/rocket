<?php
/**
 * Created by PhpStorm.
 * User: Jenner
 * Date: 2016/5/22
 * Time: 13:05
 */

namespace Jenner\Rocket\Revision;


use League\Uri\Schemes\Http;
use Symfony\Component\Process\ProcessBuilder;

class GitRevision extends AbstractRevision
{

    /**
     * check out a branch
     *
     * @param $branch
     * @return mixed
     */
    public function checkOut($branch)
    {
        $builder = $this->getCommandBuilder()
            ->add('clone')
            ->add($this->buildUrl())
            ->add($this->path);
        echo $builder->getProcess()->getCommandLine() . PHP_EOL;

        return $this->runCommand($builder);
    }

    /**
     * update current branch
     *
     * @return mixed
     */
    public function update()
    {
        $builder = $this->getCommandBuilder()
            ->add("--git-dir=" . $this->path . "/.git")
            ->add("pull")
            ->add($this->buildUrl());

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
        $builder = $this->getCommandBuilder()
            ->add("--git-dir=" . $this->path . "/.git")
            ->add('reset')
            ->add('-q')
            ->add('--hard')
            ->add($version);

        return $this->runCommand($builder);
    }

    /**
     * get all branches
     *
     * @return array
     */
    public function getBranches()
    {
        $builder = $this->getCommandBuilder()
            ->add("--git-dir=" . $this->path . "/.git")
            ->add("branch -a");
        $out = $this->runCommand($builder);

        $history = [];
        $list = explode(PHP_EOL, $out);
        foreach ($list as $item) {
            $commitId = substr($item, 0, strpos($item, '-') - 1);
            $history[] = [
                'id'      => $commitId,
                'message' => $item,
            ];
        }
        return $history;
    }

    /**
     * get all tags
     *
     * @return mixed
     */
    public function getTags()
    {
        $builder = $this->getCommandBuilder()
            ->add("--git-dir=" . $this->path . "/.git")
            ->add("tag");
        $out = $this->runCommand($builder);

        $history = [];
        $list = explode(PHP_EOL, $out);
        foreach ($list as $item) {
            $commitId = substr($item, 0, strpos($item, '-') - 1);
            $history[] = [
                'id'      => $commitId,
                'message' => $item,
            ];
        }
        return $history;
    }

    /**
     * get all commits
     *
     * @return array
     */
    public function getCommits()
    {
        $builder = $this->getCommandBuilder()
            ->add("--git-dir=" . $this->path . '/.git')
            ->add('log --pretty="%h - %an %s"');
        $out = $this->runCommand($builder);

        $history = [];
        $list = explode(PHP_EOL, $out);
        foreach ($list as $item) {
            $commitId = substr($item, 0, strpos($item, '-') - 1);
            $history[] = [
                'id' => $commitId,
                'message' => $item,
            ];
        }

        return $history;
    }


    protected function buildUrl()
    {
        $url = Http::createFromString($this->url);
        if (!empty($this->user_name) &&
            !empty($this->password) &&
            strpos($url->getScheme(), "http") === 0
        ) {
            $url->withUserInfo($this->user_name, $this->password);
        }
        return $url->__toString();
    }

    /**
     * @return ProcessBuilder
     */
    protected function getCommandBuilder()
    {
        $builder = ProcessBuilder::create();
        $builder->setPrefix('/usr/bin/env');
        if (!empty($this->command_path)) {
            $builder->add($this->command_path . DIRECTORY_SEPARATOR . 'git');
        }
        return $builder;
    }
}