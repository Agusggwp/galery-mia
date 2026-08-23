<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMemberInvitationRequest;
use App\Models\MemberInvitation;
use Illuminate\Http\Request;

class MemberInvitationController extends Controller
{
    public function index()
    {
        $invitations = MemberInvitation::withCount('members')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.member-invitations.index', compact('invitations'));
    }

    public function store(StoreMemberInvitationRequest $request)
    {
        $token = MemberInvitation::generateUniqueToken(16);

        MemberInvitation::create([
            'token' => $token,
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => $request->has('is_active') ? true : false,
            'expires_at' => $request->expires_at,
            'max_submissions' => $request->max_submissions,
            'submission_count' => 0,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('admin.member-invitations.index')->with('success', 'Link formulir undangan berhasil dibuat!');
    }

    public function toggle(MemberInvitation $member_invitation)
    {
        $member_invitation->update([
            'is_active' => !$member_invitation->is_active,
        ]);

        $status = $member_invitation->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->back()->with('success', "Link undangan berhasil {$status}!");
    }

    public function destroy(MemberInvitation $member_invitation)
    {
        $member_invitation->delete();

        return redirect()->back()->with('success', 'Link undangan berhasil dihapus!');
    }
}
