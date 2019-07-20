<?php

namespace App\Admin\Extensions;

use Encore\Admin\Admin;

class CopyJob
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
        $url = url("/admin/jobs/{$this->id}/copy");
        return "<a class='fa fa-copy' href='{$url}'></a>";
    }

    public function __toString()
    {
        return $this->render();
    }
}