<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Event;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $event = Event::all()->sortBy('start')->values();
        return $event;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'name' => 'required|string',
            'start' => 'required|date',
        ]);
        $event = new Event;
        $event->name = $request->name;
        $event->details = $request->details;
        $event->color = $request->color;
        $event->start = $request->start;
        $event->save();
        return response()->json([
            'message' => 'Tạo sự kiện thành công!'
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $event = Event::find($id);
        if(is_null($event)){
            return response()->json('Record not found!', 404);
        }
        return response()->json($event, 200);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $event = Event::find($id);
        if(is_null($event)){
            return response()->json('Record not found!', 404);
        }
        $event->name = $request->name;
        $event->start = $request->start;
        $event->details = $request->details;
        $event->color = $request->color;
        $event->save();
        return response('Cập nhật sự kiện thành công!', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $event = Event::find($id);
        if(is_null($event)){
            return response()->json('Record not found!', 404);
        }
        $event->delete();
        return response('Xoá sự kiện thành công!', 200);
    }
}
