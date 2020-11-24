<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Column;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ColumnController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return $this->getJsonResponse(Response::HTTP_OK,null,auth()->user()->columns);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $validator = \Validator::make($request->only('name'),[
            'name'=>[
                'required','string','max:255'
            ]
        ]);

        if($validator->fails()){
            return $this->getJsonResponse(Response::HTTP_UNPROCESSABLE_ENTITY,"There are errors in your form",null,$validator->errors());
        }

        $column = auth()->user()->columns()->create($validator->validated());

        return $this->getJsonResponse(Response::HTTP_OK,"Column created successfully!",$column);
    }

    /**
     * @param Column $column
     * @param Request $request
     */
    public function sort(Column $column,Request $request)
    {
        /** @var Card $card */
        foreach ($request->get('cards') as $item)
        {
            $card = Card::find(\Arr::get($item, 'cardId'));

            if($card) {
                $card->order = \Arr::get($item, 'order');
                $card->column_id = \Arr::get($item, 'columnId');
                $card->save();
            }
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Column $column
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function delete(Column $column)
    {
        $column->delete();

        return $this->getJsonResponse(Response::HTTP_OK,"Column deleted successfully!");
    }
}
