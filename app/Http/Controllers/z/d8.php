<?php

namespace App\Http\Controllers;

use App\Models\d6;
use App\MyApplication\Services\PollFormRuleValidation;
use Illuminate\Http\Request;

class d8 extends Controller
{
    public function __construct()
    {
         $this->middleware(["auth:sanctum"]);
        $this->rules = new PollFormRuleValidation();
    }

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(AnswerPoll $answerPoll)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AnswerPoll $answerPoll)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AnswerPoll $answerPoll)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AnswerPoll $answerPoll)
    {
        //
    }
}
