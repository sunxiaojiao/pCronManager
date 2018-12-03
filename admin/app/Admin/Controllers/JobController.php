<?php

namespace App\Admin\Controllers;

use App\Models\Job;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class JobController extends Controller
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
            ->header('任务列表')
            ->description('任务列表')
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
        $grid = new Grid(new Job);

        $grid->id('Id')->sortable();
        $grid->name('Name');
        $grid->command('Command');
        $grid->column('tag.name', 'tag Name');
        $grid->server_id('Server id')->sortable();
        $grid->cron('Cron');
        $grid->output('Output');
        $grid->max_concurrence('Max concurrence');
        $grid->status('Status')->display(function ($status) {
            return $status ? '启用' : '关闭';
        });
        $grid->create_time('Create time');
        $grid->update_time('Update time');
        $grid->last_run_time('Last run time')->sortable();

        $grid->filter(function ($filter) {
            // 设置created_at字段的范围查询
            $filter->between('created_at', 'Created Time')->datetime();
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
        $show = new Show(Job::findOrFail($id));

        $show->id('Id');
        $show->name('Name');
        $show->command('Command');
        $show->command_md5('Command md5');
        $show->server_id('Server id');
        $show->cron('Cron');
        $show->output('Output');
        $show->max_concurrence('Max concurrence');
        $show->status('Status')->using([
            '0' => '关闭',
            '1' => '启用',
        ]);
        $show->create_time('Create time');
        $show->update_time('Update time');
        $show->last_run_time('Last run time');
        $show->tag_id('Tag id');
        $show->tag('tag Name', function ($tag) {
            $tag->name();
        });

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Job);
        $form->text('name', 'Name');
        $form->text('command', 'Command');
        // $form->text('command_md5', 'Command md5')->value(md5(request('command')));
        $form->number('server_id', 'Server id');
        $form->text('cron', 'Cron');
        $form->text('output', 'Output');
        $form->number('max_concurrence', 'Max concurrence')->default(1);
        $form->switch('status', 'Status');
        $form->display('create_time', 'Create time');
        $form->display('update_time', 'Update time');
        // $form->display('last_run_time', 'Last run time');
        $form->number('tag_id', 'Tag id');

        $form->saving(function (Form $form) {
            $form->model()->command_md5 = md5($form->command);
        });

        return $form;
    }
}
