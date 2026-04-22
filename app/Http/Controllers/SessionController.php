<?php

namespace App\Http\Controllers;

use App\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SessionController extends Controller
{
    public function index()
    {
        $sessions = Session::with('user')->get();
        return view('adminPanel.session.managesession', compact('sessions'));
    }

    public function store(Request $request)
    {
        Log::info('Store method called', $request->all());

        if (Session::where('user_id', auth()->id())->where('status', 'active')->exists()) {
            return response()->json(['error' => 'You already have an active session.'], 400);
        }

        $request->validate([
            'session_date' => 'required|date',
            'start_date' => 'required|date',
            'start_time' => 'required',
        ]);

        $session = new Session();
        $session->session_date = $request->session_date;
        $session->start_date = $request->start_date;
        $session->start_time = $request->start_time;
        $session->status = 'active';
        $session->user_id = auth()->id();

        if ($session->save()) {
            Log::info('Session created successfully', ['id' => $session->id]);
            return response()->json(['success' => true]);
        } else {
            Log::error('Failed to save session');
            return response()->json(['error' => 'Failed to save session'], 500);
        }
    }

    public function endSession(Request $request, $id)
    {
        // Log::info('endSession method called', ['id' => $id, 'data' => $request->all()]);

        $session = Session::findOrFail($id);
        $request->validate([
            'end_date' => 'required|date',
            'end_time' => 'required',
        ]);

        $session->end_date = $request->end_date;
        $session->end_time = $request->end_time;
        $session->status = 'ended';
        $session->save();

        Log::info('Session ended successfully', ['id' => $session->id]);
        return response()->json(['success' => true]);
    }
}