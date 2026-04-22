<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdvanceIssueController extends Controller
{
    public function advanceIssue(Request $request)
    {
        dd($request->all());
    }
}
