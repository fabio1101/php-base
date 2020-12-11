<?php

abstract class Jobs_Abstract
{
    /**
     * Method to be called in all the jobs to make them run.
     *
     * @return bool
     */
    abstract public function run();
}
