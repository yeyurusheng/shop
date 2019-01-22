<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Model\GoodsModel;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class GoodsController extends Controller{
    //use HasResourceActions;
    public function index(Content $content){
        return $content
            ->header('商品管理')
            ->description('商品列表')
            ->body($this->grid());
    }
    protected function grid()
    {
        $grid = new Grid(new GoodsModel());
        $grid->model()->orderBy('g_id','desc');   //商品id倒序

        $grid->g_id('商品id');
        $grid->g_name('商品名称');
        $grid->g_price('商品价格');
        $grid->g_store('商品库存');
        return $grid;
    }
    public function edit($id, Content $content)
    {
        return $content
            ->header('商品管理')
            ->description('编辑')
            ->body($this->form()->edit($id));
    }


    //创建
    public function create(Content $content)
    {
        return $content
            ->header('商品管理')
            ->description('添加')
            ->body($this->form());
        //echo '<pre>';print_r($_POST);echo '</pre>';
    }
    /** 添加 */
    public function store(){
        //echo '<pre>';print_r($_POST);echo '</pre>';
        $data=[
            'g_name'=>  $_POST['g_name'],
            'g_price' => $_POST['g_price'],
            'g_store' => $_POST['g_store']
        ];
        GoodsModel::insert($data);
    }

    /** 删除 */
    public function destroy($id)
    {

        $response = [
            'status' => true,
            'message'   => 'ok'
        ];
        GoodsModel::destroy($id);
        return $response;
    }

    /** 修改 */
    public function update($id){
        $data=[
            'g_name'=>$_POST['g_name'],
            'g_price'=>$_POST['g_price'],
            'g_store' => $_POST['g_store']
        ];
        GoodsModel::where(['g_id'=>$id])->update($data);
    }

    /** 展示 */
    public function show($id){

    }

    protected function form()
    {
        $form = new Form(new GoodsModel());

        $form->display('g_id', '商品ID');
        $form->text('g_name', '商品名称');
        $form->number('g_store', '库存');
        $form->currency('g_price', '价格')->symbol('¥');
        $form->ckeditor('content');
        return $form;
    }
}
