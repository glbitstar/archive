<?php

namespace App\Admin\Controllers;

use App\Models\OtherDesign;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class OtherDesignController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'OtherDesign';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new OtherDesign());

        $grid->column('id', __('Id'));

        $grid->column('other_major_category_id', __('Other Major Category'))->display(function($otherMajorCategoryId) {
            // Assuming the relationship is named 'category' in your LandingPage model
            return $this->other_major_category ? $this->other_major_category->name : '-';
        });
        $grid->column('name', __('Name'));
        $grid->column('image', __('Image'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

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
        $show = new Show(OtherDesign::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('other_major_category_id', __('Other Major category id'));
        $show->field('image', __('Image'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new OtherDesign());

        $other_categories = \App\Models\OtherCategory::pluck('name', 'id');

        $form->text('name', __('Name'));
        $other_major_categories = \App\Models\OtherMajorCategory::pluck('name', 'id');
        $form->select('other_major_category_id', __('Other major category id'))->options($other_major_categories);
        $form->image('image', __('Image'));

        return $form;
    }
}
