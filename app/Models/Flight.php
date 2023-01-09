<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    use HasFactory;

    protected $fillable = ['arrival_airport','arrival_time','arrival_tz','available_seats','departure_airport','departure_time','departure_tz'];

    function departure_airport_data() {
        return $this->belongsTo(Airport::class, 'departure_airport', 'id', 'airports');
    }

    function arrival_airport_data() {
        return $this->belongsTo(Airport::class, 'arrival_airport', 'id', 'airports');
    }

    static function generate_timezone_list()
    {
        static $regions = array(
            // \DateTimeZone::AFRICA,
             \DateTimeZone::AMERICA,
            // \DateTimeZone::ANTARCTICA,
             \DateTimeZone::ASIA,
            // \DateTimeZone::ATLANTIC,
            // \DateTimeZone::AUSTRALIA,
            \DateTimeZone::EUROPE,
            // \DateTimeZone::INDIAN,
            // \DateTimeZone::PACIFIC,
        );

        $timezones = array();
        foreach( $regions as $region )
        {
            $timezones = array_merge( $timezones, \DateTimeZone::listIdentifiers( $region ) );
        }

        $timezone_offsets = array();
        foreach( $timezones as $timezone )
        {
            $tz = new \DateTimeZone($timezone);
            $timezone_offsets[$timezone] = $tz->getOffset(new \DateTime);
        }

        // sort timezone by offset
        asort($timezone_offsets);

        $timezone_list = array();
        foreach( $timezone_offsets as $timezone => $offset )
        {
            $offset_prefix = $offset < 0 ? '-' : '+';
            $offset_formatted = gmdate( 'H:i', abs($offset) );

            $pretty_offset = "UTC${offset_prefix}${offset_formatted}";

            $timezone_list[$timezone] = str_replace('/','|',$timezone);
        }

        return $timezone_list;
    }
}
