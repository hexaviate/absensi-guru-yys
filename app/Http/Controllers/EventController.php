<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Tapel;
use App\Models\User;
use Illuminate\Http\Request;
use Validator;

class EventController extends Controller
{

    public function indexEventOperator()
    {
        $user = auth()->user();
        if (!$user->can('manage event')) {
            return redirect()->back()->with('error', 'Anda tidak punya permission');
        }

        $tapelAktif = Tapel::where('status', 'aktif')->first();
        $instansiOperator = $user->instansi()->first();
        //* selalu gunakan with setelah model untuk mencegah N+1 query problem
        $event = Event::with('tapel', 'instansi')->where('tapel_id', $tapelAktif->id)->where('instansi_id', $instansiOperator->id)->get();
        $semuaEvent = Event::with('tapel', 'instansi')->where('tapel_id', $tapelAktif->id)->get();

        if ($user->hasRole('admin_yayasan')) {
            $semuaEvent = Event::with('tapel', 'instansi')->where('tapel_id', $tapelAktif->id)->get();
            $eventAdmin = Event::with('tapel', 'instansi')->where('tapel_id', $tapelAktif->id)->where('instansi_id', 7)->get();
            return view('', compact('eventAdmin', 'semuaEvent'));

        }

        // route view jangan lupa untuk diganti
        return view('', compact('event', 'semuaEvent'));
    }

    public function createEventOperator()
    {
        $user = auth()->user();
        if (!$user->can('manage event')) {
            return redirect()->back()->with('error', 'Anda tidak punya permission');
        }

        $instansiOperator = $user->instansi()->first();
        $tapelAktif = Tapel::where('status', 'aktif');

        return view('', compact('instansiOperator', 'tapelAktif'));
    }

    public function storeEventOperator(Request $request)
    {
        $user = auth()->user();
        if (!$user->can('manage event')) {
            return redirect()->back()->with('error', 'Anda tidak punya permission');
        }

        $tapelAktif = Tapel::where('status', 'aktif')->first();
        $instansiOperator = $user->instansi()->first();


        $validator = Validator::make($request->all(), [
            'tapel_id' => 'required',
            'instansi_id' => 'required',
            "nama_event" => 'required',
            'keterangan' => 'required',
            'tanggal_mulai' => 'required',
            'tanggal_selesai' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors());
        }

        if ($request->instansi_id != $instansiOperator->id) {
            return redirect()->back()->with('error', 'Anda tidak terdaftar di instansi ini');
        }

        if ($user->hasRole('admin_yayasan')) {
            Event::create([
                "tapel_id" => $request->tapel_id,
                "instansi_id" => $request->instansi_id,
                "nama_event" => $request->nama_event,
                "keterangan" => $request->keterangan,
                "tipe" => $request->tipe,
                "tanggal_mulai" => $request->tanggal_mulai,
                "tanggal_selesai" => $request->tanggal_selesai
            ]);

            return redirect()->route()->with('success', 'Anda berhasil menginputkan data');
        }

        Event::create([
            "tapel_id" => $request->tapel_id,
            "instansi_id" => $request->instansi_id,
            "nama_event" => $request->nama_event,
            "keterangan" => $request->keterangan,
            "tipe" => 'internal',
            "tanggal_mulai" => $request->tanggal_mulai,
            "tanggal_selesai" => $request->tanggal_selesai
        ]);

        return redirect()->route()->with('success', 'Anda berhasil menginputkan data');
    }

    public function editEventOperator(string $id)
    {
        $user = auth()->user();
        if (!$user->can('manage event')) {
            return redirect()->back()->with('error', 'Anda tidak punya permission');
        }

        $instansiOperator = $user->instansi()->first();
        $tapelAktif = Tapel::where('status', 'aktif');
        $event = Event::findOrFail($id);

        return view('', compact('instansiOperator', 'tapelAktif', 'event'));
    }

    public function updateEventOperator(Request $request, string $id)
    {
        $user = auth()->user();
        if (!$user->can('manage event')) {
            return redirect()->back()->with('error', 'Anda tidak punya permission');
        }

        $tapelAktif = Tapel::where('status', 'aktif')->first();
        $instansiOperator = $user->instansi()->first();
        $event = Event::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'tapel_id' => 'required',
            'instansi_id' => 'required',
            "nama_event" => 'required',
            'keterangan' => 'required',
            'tanggal_mulai' => 'required',
            'tanggal_selesai' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors());
        }

        if ($request->instansi_id != $instansiOperator->id) {
            return redirect()->back()->with('error', 'Anda tidak terdaftar di instansi ini');
        }

        if ($user->hasRole('admin_yayasan')) {
            $event->update([
                "tapel_id" => $request->tapel_id,
                "instansi_id" => $request->instansi_id,
                "nama_event" => $request->nama_event,
                "keterangan" => $request->keterangan,
                "tipe" => $request->tipe,
                "tanggal_mulai" => $request->tanggal_mulai,
                "tanggal_selesai" => $request->tanggal_selesai
            ]);


            return redirect()->route()->with('success', 'Anda berhasil menginputkan data');
        }


        $event->update([
            "tapel_id" => $request->tapel_id,
            "instansi_id" => $request->instansi_id,
            "nama_event" => $request->nama_event,
            "keterangan" => $request->keterangan,
            "tipe" => 'internal',
            "tanggal_mulai" => $request->tanggal_mulai,
            "tanggal_selesai" => $request->tanggal_selesai
        ]);

        return redirect()->route()->with('success', 'Anda berhasil mengedit data');
    }

    public function deleteEventOperator(string $id)
    {
        $user = auth()->user();
        if (!$user->can('manage event')) {
            return redirect()->back()->with('error', 'Anda tidak punya permission');
        }

        $event = Event::findOrFail($id);
        if ($event->instansi_id != $user->instansi()->first()->id) {
            return redirect()->back()->with('error', 'Anda tidak terdaftar di instansi ini');
        }

        $event->delete();
        return redirect()->route()->with('success', 'Anda berhasil menghapus data');
    }

    //------------------------------------------------{Untuk User}-----------------------------------------------

    public function viewEventUser()
    {
        $user = auth()->user();
        if (!$user->can('view all event')) {
            return redirect()->back()->with('error', 'Anda tidak punya permission');
        }

        $tapelAktif = Tapel::where('status', 'aktif');
        $user = User::with([
            'instansi.event' => function ($query) {
                $query->whereHas('tapel', function ($q) {
                    $q->where('status', 'aktif');
                });
            }
        ])->find(auth()->id());

        $eventYayasan = Event::where('tipe', 'yayasan')->where('tapel_id', $tapelAktif->id);
        return view('', compact('user', 'eventYayasan'));

        //* cara untuk mengakses data event pada instansi yang dimili user adalah sebagai berikut
        // foreach ($user->instansi() as $instansi) {
        //     foreach ($instansi->event as $event) {
        //         echo $event->nama;
        //     }
        // }
    }
}
