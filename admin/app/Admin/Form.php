<?php
namespace App\Admin;
use Encore\Admin\Form as OriginalForm;
use Encore\Admin\Form\Builder;

class Form extends OriginalForm
{
    public function copy($id)
    {
        $this->builder->setMode(Builder::MODE_CREATE);
        $this->setFieldValue($id);
        $this->setAction(url('/admin/jobs'));

        return $this;
    }
}