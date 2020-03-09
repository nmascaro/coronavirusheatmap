<?php

# GitHub JHU repo: https://github.com/CSSEGISandData/COVID-19

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

class GitController extends Controller
{
    public function show($date)
    {
        $date = Carbon::parse($date)->format('m-d-Y');
        $url = 'https://raw.githubusercontent.com/CSSEGISandData/COVID-19/master/csse_covid_19_data/csse_covid_19_daily_reports/' . $date .'.csv';
        $row = 1;
        $rows = [];
        if (($handle = fopen($url, "r")) !== false) {
            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                $rows[] = $data;
            }
            fclose($handle);
        }
        array_walk($rows, function (&$a) use ($rows) {
            $a = array_combine($rows[0], $a);
        });
        array_shift($rows);
        $csv = [];
        foreach ($rows as $v) {
            $csv[] = [
                $v['Latitude'],
                $v['Longitude'],
                $v['Confirmed']
            ];
        }
        $csv = json_encode($csv);
        return view('welcome', compact('csv'));
    }
}
