<?php

namespace App\Http\Controllers;

use App\Models\Extensions;
use Exception;
use Illuminate\Http\Request;

class HelperController extends Controller
{
    protected $exception;
    public function getMessage()
    {
    }
    public function base64url_encode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_?'), '=');
    }

    public function base64url_decode(string $data): string
    {
        return base64_decode(str_pad(strtr($data, '-_?', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
    }

    public function inviteAgent($string)
    {
        $base64 = $this->base64url_decode($string);
        $data  =  json_decode($base64);

        if (!isset($data->expire)) {

            $exception = new Exception('Invited session is expired');
            return view('errors.401', compact('exception'));
        } elseif (!isset($data->user_id)) {

            $exception = new Exception('Invited session is expired');
            return view('errors.401', compact('extension'));
        } elseif (!isset($data->exten_id)) {

            $exception = new Exception('Invited session is expired');
            return view('errors.401', compact('exception'));
        }

        if ($data->expire - time() <= 0) {
            $exception = new Exception('Invited session is expired');
            return view('errors.401', compact('exception'));
        }

        $user_id    =  $data->user_id;
        $exten_id   =  $data->exten_id;
        session(['string_url' => $data->expire]);

        $extension = Extensions::where('id', $exten_id)->first();
        //if agent key is not exists
        if (Extensions::where('id', $exten_id)->count() < 1) {
            $exception = new Exception('Sorry extension agent not found!');
            return view('errors.401', compact('exception'));
        }

        //if matches like array/object
        if (preg_match_all('/\"[^:]*:/', $extension->agent)) {
            foreach (json_decode($extension->agent) as $agent) {
                if (isset($agent->time) && $agent->time === $data->expire) {
                    $exception = new Exception('Attempts to add agent number to extension');
                    return view('errors.401', compact('exception'));
                }
            }
        }

        return view('guest.add-agent', compact('user_id', 'exten_id'));
    }

    public function addNumber(Request $request)
    {
        $request->validate([
            'name'      => 'required|min:3|max:20',
            'number'    => 'required|min:8|max:14',
            'user_id'   => 'required',
            'exten_id'  => 'required',
        ]);

        if (Extensions::where('id', $request->exten_id)->count() < 1) {

            // return back()->withErrors([
            //     'error' => "couldn't find any extension!"
            // ]);
        }
        $extension = Extensions::where('id', $request->exten_id)->first();

        if (str_contains($extension->agent, $request->number)) {
            $exception = new Exception('already exists a number with this invitation!');
            return view('errors.401', compact('exception'));
        }

        if (preg_match_all('/\"[^:]*:/', $extension->agent)) {
            $agents = json_decode($extension->agent);
            array_push($agents, [
                'name'      => $request->name,
                'number'    => $request->number,
                'user_id'   => $extension->user_id,
                'time'      => session('string_url'),
            ]);
        } else {
            $agents = [
                [
                    'name'      => $request->name,
                    'number'    => $request->number,
                    'user_id'   => $extension->user_id,
                    'time'      => session('string_url'),
                ]
            ];
        }

        Extensions::where('id', $request->exten_id)->update([
            'agent'  => json_encode($agents)
        ]);

        return redirect()->to('login')->with([
            'status'  => 'success',
            'message' => 'Number add successfull!',
        ]);
    }
}
