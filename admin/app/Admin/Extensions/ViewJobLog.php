<?php

namespace App\Admin\Extensions;

use Encore\Admin\Admin;

class ViewJobLog
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    protected function script()
    {
    }

    protected function render()
    {
        Admin::script($this->script());
        $url = url("/admin/logs?command_id={$this->id}");
        return "<a class='fa fa-eercast grid-check-row' href='{$url}'></a>";
    }

    public function __toString()
    {
        return $this->render();
    }
}