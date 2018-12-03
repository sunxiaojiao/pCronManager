<?php

namespace App\Admin\Controllers;

use App\Models\Vars;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class VarsController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('Index')
            ->description('description')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('Detail')
            ->description('description')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('Edit')
            ->description('description')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('Create')
            ->description('description')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Vars);

        $grid->id('Id');
        $grid->name('Name');
        $grid->value('Value');
        $grid->server_id('Server id');
        $grid->create_time('Create time');
        $grid->update_time('Update time');

        $grid->filter(function ($filter) {
            $filter->like('name', 'Name');
        });

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Vars::findOrFail($id));

        $show->id('Id');
        $show->name('Name');
        $show->value('Value');
        $show->server_id('Server id');
        $show->create_time('Create time');
        $show->update_time('Update time');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Vars);

        $form->text('name', 'Name');
        $form->text('value', 'Value');
        $form->number('server_id', 'Server id');
        $form->datetime('create_time', 'Create time')->default(date('Y-m-d H:i:s'));
        $form->datetime('update_time', 'Update time')->default(date('Y-m-d H:i:s'));

        return $form;
    }
}
