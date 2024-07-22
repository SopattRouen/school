<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subject\Subject;
use Illuminate\Http\Request;

class SubjectController
{
    //
    public function getData(Request $req){

        // Declar Variable
        $data = Subject::select('*')
        ->with(['subject'])
        ;

        // ===>> Filter Data
        // By Key compared with Code or Name
        if ($req->key && $req->key != '') {

            $data = $data->where('id', 'LIKE', '%' . $req->key . '%')
            ->Orwhere('name', 'LIKE', '%' . $req->key . '%');
        }

        // By Product Type
        if ($req->subject && $req->subject != 0) {

            $data = $data->where('type_id', $req->subject);

        }

        $data = $data->orderBy('id', 'desc') // Order Data by Giggest ID to small
        ->paginate($req->limit ? $req->limit : 10,'per_page'); // Paginate Data

        // ===> Success Response Back to Client
        return response()->json($data, 200);

    }
}
