<?php

namespace App\Http\Controllers;

use App\Models\d7;
use App\MyApplication\Services\PollFormRuleValidation;
use Illuminate\Http\Request;

class d1 extends Controller
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
        //
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
    public function show(d7 $ans_poll_user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(d7 $ans_poll_user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, d7 $ans_poll_user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(d7 $ans_poll_user)
    {
        //
    }
}
