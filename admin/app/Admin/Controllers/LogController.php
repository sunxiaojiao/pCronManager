<?php

namespace App\Admin\Controllers;

use App\Models\Log;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class LogController extends Controller
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
        $grid = $this->grid();
        $grid->model()->orderBy('id', 'desc');
        return $content
            ->header('Index')
            ->description('description')
            ->body($grid);
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
        $grid = new Grid(new Log);

        $grid->id('Id');
        $grid->command_id('Command id');
        $grid->server_id('Server id');
        $grid->command('Command');
        $grid->create_time('Create time');
        $grid->running_time('Running time');
        $grid->start_time('Start time');
        $grid->end_time('End time');
        $grid->uniq_id('Uniq id');
        $grid->pid('Pid');
        $grid->concurrent('Concurrent');

        $grid->filter(function ($filter) {
            $filter->between('created_at', 'Created Time')->datetime();
            $filter->equal('command_id', 'Command Id');
            $filter->equal('server_id', 'Server Id');
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
        $show = new Show(Log::findOrFail($id));

        $show->id('Id');
        $show->command_id('Command id');
        $show->server_id('Server id');
        $show->command('Command');
        $show->create_time('Create time');
        $show->running_time('Running time');
        $show->start_time('Start time');
        $show->end_time('End time');
        $show->uniq_id('Uniq id');
        $show->pid('Pid');
        $show->concurrent('Concurrent');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Log);

        $form->number('command_id', 'Command id');
        $form->number('server_id', 'Server id');
        $form->text('command', 'Command');
        $form->datetime('create_time', 'Create time')->default(date('Y-m-d H:i:s'));
        $form->number('running_time', 'Running time');
        $form->datetime('start_time', 'Start time')->default(date('Y-m-d H:i:s'));
        $form->datetime('end_time', 'End time')->default(date('Y-m-d H:i:s'));
        $form->text('uniq_id', 'Uniq id');
        $form->number('pid', 'Pid');
        $form->number('concurrent', 'Concurrent');

        return $form;
    }
}
