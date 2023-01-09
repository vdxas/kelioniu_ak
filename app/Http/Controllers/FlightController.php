<?php

namespace App\Http\Controllers;

use App\Models\Flight;
use App\Models\FlightChangeLog;
use Illuminate\Http\Request;

class FlightController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $flights = Flight::with('departure_airport_data')
            ->with('arrival_airport_data')
            ->get();

        return $flights;
    }


    /**
     * Display a listing of the resource by timezone.
     *
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function index_tz($tz)
    {
        $flights = \App\Models\Flight::with('departure_airport_data')
            ->with('arrival_airport_data')
            ->get();

        if ($tz) {
            $tz = str_replace('|', '/', $tz);
            foreach ($flights as $i => $flight) {

                $departure_time = new \DateTime($flight['departure_time'], new \DateTimeZone($flight['departure_tz']));
                $departure_time->setTimezone(new \DateTimeZone($tz));
                $flights[$i]['_departure_time'] = $departure_time->format('Y-m-d H:i:s');

                $arrival_time = new \DateTime($flight['arrival_time'], new \DateTimeZone($flight['arrival_tz']));
                $arrival_time->setTimezone(new \DateTimeZone($tz));
                $flights[$i]['_arrival_time'] = $arrival_time->format('Y-m-d H:i:s');

                $flights[$i]['_arrival_tz'] = $flights[$i]['_departure_tz'] = $tz;
            }
        }
        return $flights;
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $flight = new Flight();
        $flight->fill($request->all());
        $flight->save();

        return $request->all();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Flight $flight
     * @return \Illuminate\Http\Response
     */
    public function show(Flight $flight)
    {
        //
    }

    /**
     * Display timezones data.
     *
     * @return \Illuminate\Http\Response
     */
    public function timezones()
    {
        $tz = \App\Models\Flight::generate_timezone_list();
        return $tz;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Flight $flight
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Flight $flight
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Flight $flight)
    {
        $flight = Flight::find($request->id);
        $old_data = json_encode($flight);
        $flight->update($request->all());

        //get update changes
        $changes = $flight->getChanges();
        //save update changes
        $log = new FlightChangeLog();
        $log->changes = json_encode($changes);
        $log->old_data = $old_data;
        $log->flight_id = $request->id;
        $log->save();

        return $flight;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Flight $flight
     * @return \Illuminate\Http\Response
     */
    public function destroy(Flight $flight)
    {
        //
    }
}
