<?php

namespace LTFramework\Traits;

use Illuminate\Http\Request;
use LTFramework\Services\Editor\Pipeline\RenderPipeline;

trait BuildEditTrait {


    /**
     * This function is for create new Object
     */
    public function create(Request $request) {
        $key = get_called_class().'@create';
        $edit = RenderPipeline::renderByKey($key,$this->buildViewEdit($request));
        return response()->json($edit->extract());
    }

    /**
     * This function is for update an Object
     */
    public function edit(Request $request, $id) {
        $key = get_called_class().'@edit';
        $edit = RenderPipeline::renderByKey($key,[$this->buildViewEdit($request,$id),$id]);
        return response()->json($edit->extract());
    }


    /**
     * This function is for show all entries
     */
    public function index(Request $request) {
        $key = get_called_class().'@index';
        $tab = RenderPipeline::renderByKey($key,$this->buildViewList($request));
        return response()->json($tab->extract()); 
    }

    /**
     * this function is for Store New entry
     */
    public function store(Request $request) {
        $key = get_called_class().'@store';
        list($edit,$oldRequest) = RenderPipeline::renderByKey($key,[$this->storeAndUpdate($request), $request]);
        return response()->json($edit->extract());
    }

    public function show(Request $request) {

    }

    public function destroy(Request $request) {
        
    }

    /**
     * This function is for Update an Entry
     */
    public function update(Request $request, $id) {
        $key = get_called_class().'@update';
        list($edit,$requestAndId) = RenderPipeline::renderByKey($key,[$this->storeAndUpdate($request, $id),$request,$id]); 
        return response()->json($edit->extract());
    }

    public function delete(Request $request, $class, $key = 'id') {
        $input = $request->all();
       
        $this->deleteItem($input, $class, $key);
        $tab = $this->buildViewList($request);
        return response()->json($tab->extract());
    }

    public function deleteItem($data, $class, $key = 'id') {
        $array = [];
        foreach ($data as $objId) {
            $id = $objId[$key];
            $array[] = $id;
        }

        $class::destroy($array);
    }
}