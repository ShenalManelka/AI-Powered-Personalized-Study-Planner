<?php

namespace App\Http\Controllers;

use App\Models\SmartAlert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SmartAlertController extends Controller
{
    /**
     * Mark the given alert as read.
     */
    public function markAsRead(SmartAlert $alert)
    {
        // Ensure the alert belongs to the authenticated user
        if ($alert->user_id !== Auth::id()) {
            abort(403);
        }

        $alert->update(['is_read' => true]);

        return redirect()->back();
    }
}
