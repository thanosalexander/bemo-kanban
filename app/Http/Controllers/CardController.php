<?php

namespace App\Http\Controllers;

use App\Models\Card;
use App\Models\Column;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Column $column
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Column $column)
    {
        return $this->getJsonResponse(Response::HTTP_OK,null, $column->cards);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param Column $column
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Column $column,Request $request)
    {
        $validator = \Validator::make($request->only('title','description'),[
            'title'=>[
                'required','string','max:255'
            ],
            'description'=>[
                'required','string','max:255'
            ],
        ]);

        if($validator->fails()){
            return $this->getJsonResponse(Response::HTTP_UNPROCESSABLE_ENTITY,"There are errors in your form",null,$validator->errors());
        }

        $card = $column->cards()->create($validator->validated());

        return $this->getJsonResponse(Response::HTTP_OK,"Card created successfully!",$card);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Card $card
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Card $card)
    {
        \Log::info(
            $request->only('title','description','column_id','order')
        );

        $validator = \Validator::make($request->only('title','description','column_id','order'),[
            'title'=>[
                'sometimes','string','max:255'
            ],
            'description'=>[
                'sometimes','string','max:255'
            ],
            'column_id'=>[
                'sometimes','exists:columns,id'
            ],
            'order'=>[
                'sometimes','integer','min:0'
            ]
        ]);

        if($validator->fails()){
            return $this->getJsonResponse(Response::HTTP_UNPROCESSABLE_ENTITY,"There are errors in your form",null,$validator->errors());
        }


        $card->update($validator->validated());

        return $this->getJsonResponse(Response::HTTP_OK,"Card updated successfully!",$card->fresh());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Card $card
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function delete(Card $card)
    {
        $card->delete();

        return $this->getJsonResponse(Response::HTTP_OK,"Card deleted successfully!");
    }
}
