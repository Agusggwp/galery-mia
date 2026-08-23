<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMemberRequest;
use App\Models\Member;
use App\Models\MemberInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MemberFormController extends Controller
{
    public function show($token)
    {
        $invitation = MemberInvitation::where('token', $token)->first();

        if (!$invitation) {
            return view('member-form.invalid', ['reason' => 'Link formulir tidak ditemukan atau sudah tidak berlaku.']);
        }

        if (!$invitation->is_active) {
            return view('member-form.invalid', ['reason' => 'Link formulir ini saat ini sedang dinonaktifkan oleh Admin.']);
        }

        if ($invitation->isExpired()) {
            return view('member-form.invalid', ['reason' => 'Link formulir ini sudah melewati batas waktu kadaluarsa atau batas kuota pengisian.']);
        }

        return view('member-form.form', compact('invitation'));
    }

    public function store(StoreMemberRequest $request, $token)
    {
        $invitation = MemberInvitation::where('token', $token)->firstOrFail();

        if (!$invitation->isValid()) {
            return back()->withErrors(['token' => 'Formulir pendaftaran ini sudah tidak aktif atau kadaluarsa.']);
        }

        // Upload photo
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = 'member_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $photoPath = $file->storeAs('members', $filename, 'public');
        }

        // Create Member (Status: pending)
        $member = Member::create([
            'name' => $request->name,
            'nickname' => $request->nickname,
            'slug' => Member::generateUniqueSlug($request->name),
            'student_number' => $request->student_number,
            'class_name' => $request->class_name,
            'major' => $request->major,
            'generation' => $request->generation,
            'photo' => $photoPath,
            'bio' => $request->bio,
            'instagram' => $request->instagram,
            'whatsapp' => $request->whatsapp,
            'is_instagram_public' => false,
            'is_whatsapp_public' => false,
            'privacy_agreed' => true,
            'is_visible' => true,
            'status' => 'pending',
            'invitation_id' => $invitation->id,
        ]);

        // Increment submission count
        $invitation->increment('submission_count');

        return view('member-form.success', compact('member'));
    }
}
