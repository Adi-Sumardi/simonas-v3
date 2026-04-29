<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Kegiatan;
use App\Http\Requests\EventRequest;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {

        return view('alumni.pages.calender.index', ['action'=> route('events.store')]);
    }
    
    public function listEvent(Request $request)
    {
        $start = date('Y-m-d', strtotime($request->start));
        $end = date('Y-m-d', strtotime($request->end));

        $events = Event::where('event_start_date', '>=', $start)
            ->where('event_end_date', '<=', $end)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->event_title,
                    'start' => $item->event_start_date,
                    'end' => date('Y-m-d', strtotime($item->event_end_date . '+1 days')),
                    'event_description' => $item->event_description,
                    'event_start_time' => $item->event_start_time,
                    'event_end_time' => $item->event_end_time,
                    'className' => ['bg-' . $item->category]
                ];
            });

        return response()->json($events);
    }
    
    public function indexUNI()
    {
        $role = auth()->user()->role;
        if($role ==="mahasiswa"){
            $layouts =  'mahasiswa.layouts.master-layouts';
        }
        elseif($role ==="alumni"){
            $layouts =  'alumni.layouts.master';
        }
        elseif($role ==="super"){
            $layouts =  'super.layouts.master';
        }
        $events= array();
        $warna = null;
        $warnacolor = 'rgb(128, 128, 128)';
        $kegiatan = Kegiatan::all();
        foreach ($kegiatan  as $kegiatan){
            if($kegiatan->penyelenggara == "YAPI")
            {
                  $warna = '#F1B9AC';
            }
            elseif($kegiatan->penyelenggara == "Direktorat Keasramaan")
            {
                $warna = '#F4D291';
            }
            elseif($kegiatan->penyelenggara == "Asrama Sunan Gunung Jati")
            {
                $warna = '#FAEBBD';
            }
            elseif($kegiatan->penyelenggara == "Asrama Sunan Giri")
            {
                $warna = '#BBEDBE';
            }

            $events[]=[
                'id' => $kegiatan->id,
                'title' => $kegiatan->nama_kegiatan,
                'start' => $kegiatan->waktu,
                'end' => date('Y-m-d',strtotime($kegiatan->waktu. '+1 days')),
                'tempat' => $kegiatan->tempat,
                'penyelenggara' => $kegiatan->penyelenggara,
                'jenis_kegiatan' => $kegiatan->jenis_kegiatan,
                'keterangan' => $kegiatan->keterangan,
                'file' => $kegiatan->file,
                'color'=>$warna,
                'textColor'=>$warnacolor,
                'tujuan' => $kegiatan->tujuan
            ];
        }

        return view('super.pages.master.calender',['events'=>$events,'layouts' => $layouts]);
    }

    public function listEventSuper(Request $request)
    {
        $warna = '#000000';
        $start = date('Y-m-d', strtotime($request->start));
        $end = date('Y-m-d', strtotime($request->end));
        $events = Kegiatan::where('waktu', '>=', $start)
            ->where('waktu', '<=', $end)
            ->get()
            ->map(function ($item) use ($warna) {
                return [
                    'id' => $item->id,
                    'title' => $item->nama_kegiatan,
                    'start' => $item->waktu,
                    'end' => date('Y-m-d', strtotime($item->waktu . '+1 days')),
                    'tempat' => $item->tempat,
                    'penyelenggara' => $item->penyelenggara,
                    'jenis_kegiatan' => $item->jenis_kegiatan,
                    'keterangan' => $item->keterangan,
                    'file' => $item->file,
                    'tujuan' => $item->tujuan,
                    'color' => $warna  // Use $warna directly
                ];
            });

        return response()->json($events);
    }

    public function listEventSuper1(Request $request)
    {
        $start = date('Y-m-d', strtotime($request->start));
        $end = date('Y-m-d', strtotime($request->end));

        $events = Kegiatan::where('waktu', '>=', $start)
            ->where('waktu', '<=', $end)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->nama_kegiatan,
                    'start' => $item->waktu,
                    'end' => date('Y-m-d', strtotime($item->waktu . '+1 days')),
                    'tempat' => $item->tempat,
                    'penyelenggara' => $item->penyelenggara,
                    'jenis_kegiatan' => $item->jenis_kegiatan,
                    'keterangan' => $item->keterangan,
                    'file' => $item->file,
                    'tujuan' => $item->tujuan
                ];
            });

        return response()->json($events);
    }

    public function listEventSuperMahasiswa(Request $request)
    {
        $start = date('Y-m-d', strtotime($request->start));
        $end = date('Y-m-d', strtotime($request->end));

        $events = Kegiatan::where('waktu', '>=', $start)
            ->where('waktu', '<=', $end)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->nama_kegiatan,
                    'start' => $item->waktu,
                    'end' => date('Y-m-d', strtotime($item->waktu . '+1 days')),
                    'tempat' => $item->tempat,
                    'penyelenggara' => $item->penyelenggara,
                    'jenis_kegiatan' => $item->jenis_kegiatan,
                    'keterangan' => $item->keterangan,
                    'file' => $item->file,
                    'tujuan' => $item->tujuan
                ];
            });

        return response()->json($events);
    }

    public function create(Event $event)
    {

        return view('alumni.pages.calender.event-form', ['data' => $event,'action'=> route('events.store')]);
    }

    public function store1(Request $request,Event $event)
    {
       $event->event_title =$request->title;
       $event->event_start_date =$request->start_date;
       $event->event_end_date =$request->end_date;
       $event->event_start_time =$request->strat_time;
       $event->event_end_time =$request->end_strat_time;
       $event->event_description =$request->category;
       $event->save();
        return $event;
     }

    public function show(Event $event)
    {
        // ... (tidak ada perubahan pada metode ini)
    }

    public function edit(Event $event)
    {
        $action = route('events.update', $event->id);
        return view('alumni.pages.calender.index', compact('event', 'action'));
    }

    public function update1(Request $request,$id)
    {

        dd($request->all());
        // return response()->json(['message' => 'Event updated successfully']);
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Delete data successfully'
        ]);
    }
}
