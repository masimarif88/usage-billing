<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
class UsageController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'units' => 'required|integer|min:1',
            'used_at' => 'nullable|date',
        ]);

        $request->user()->usageRecords()->create([
            'units' => $request->units,
            'user_id' => auth()->id(),
            'used_at' => $request->used_at ?? now(),
        ]);

        return response()->json(['message' => 'Usage recorded']);
    }

}
