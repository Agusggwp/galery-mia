<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminMemberController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status', 'all');
        $search = $request->input('search');

        $query = Member::query();

        if ($status !== 'all') {
            if ($status === 'hidden') {
                $query->where('is_visible', false);
            } else {
                $query->where('status', $status);
            }
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nickname', 'like', "%{$search}%")
                  ->orWhere('student_number', 'like', "%{$search}%");
            });
        }

        $members = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        // Counts for tabs
        $counts = [
            'all' => Member::count(),
            'pending' => Member::where('status', 'pending')->count(),
            'approved' => Member::where('status', 'approved')->count(),
            'rejected' => Member::where('status', 'rejected')->count(),
            'hidden' => Member::where('is_visible', false)->count(),
        ];

        return view('admin.members.index', compact('members', 'status', 'search', 'counts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nickname' => 'required|string|max:100',
            'student_number' => 'required|string|max:50',
            'class_name' => 'required|string|max:50',
            'major' => 'required|string|max:100',
            'generation' => 'required|string|max:10',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'bio' => 'nullable|string',
            'instagram' => 'nullable|string',
            'whatsapp' => 'nullable|string',
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = 'member_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $photoPath = $file->storeAs('members', $filename, 'public');
        }

        Member::create([
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
            'is_instagram_public' => $request->has('is_instagram_public'),
            'is_whatsapp_public' => $request->has('is_whatsapp_public'),
            'is_visible' => true,
            'status' => $request->status,
            'approved_at' => $request->status === 'approved' ? now() : null,
            'approved_by' => $request->status === 'approved' ? auth()->id() : null,
        ]);

        return redirect()->route('admin.members.index')->with('success', 'Anggota berhasil ditambahkan!');
    }

    public function update(Request $request, Member $member)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nickname' => 'required|string|max:100',
            'student_number' => 'required|string|max:50',
            'class_name' => 'required|string|max:50',
            'major' => 'required|string|max:100',
            'generation' => 'required|string|max:10',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'bio' => 'nullable|string',
            'instagram' => 'nullable|string',
            'whatsapp' => 'nullable|string',
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $photoPath = $member->photo;
        if ($request->hasFile('photo')) {
            if ($member->photo && Storage::disk('public')->exists($member->photo)) {
                Storage::disk('public')->delete($member->photo);
            }
            $file = $request->file('photo');
            $filename = 'member_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $photoPath = $file->storeAs('members', $filename, 'public');
        }

        $member->update([
            'name' => $request->name,
            'nickname' => $request->nickname,
            'student_number' => $request->student_number,
            'class_name' => $request->class_name,
            'major' => $request->major,
            'generation' => $request->generation,
            'photo' => $photoPath,
            'bio' => $request->bio,
            'instagram' => $request->instagram,
            'whatsapp' => $request->whatsapp,
            'is_instagram_public' => $request->has('is_instagram_public'),
            'is_whatsapp_public' => $request->has('is_whatsapp_public'),
            'status' => $request->status,
            'approved_at' => $request->status === 'approved' && !$member->approved_at ? now() : $member->approved_at,
            'approved_by' => $request->status === 'approved' && !$member->approved_by ? auth()->id() : $member->approved_by,
        ]);

        return redirect()->back()->with('success', 'Data anggota berhasil diperbarui!');
    }

    public function approve(Member $member)
    {
        $member->update([
            'status' => 'approved',
            'is_visible' => true,
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', "Pendaftaran {$member->name} berhasil disetujui (Approved)!");
    }

    public function reject(Member $member)
    {
        $member->update([
            'status' => 'rejected',
        ]);

        return redirect()->back()->with('success', "Pendaftaran {$member->name} ditolak (Rejected).");
    }

    public function toggle(Member $member)
    {
        $member->update([
            'is_visible' => !$member->is_visible,
        ]);

        $statusText = $member->is_visible ? 'ditampilkan' : 'disembunyikan';
        return redirect()->back()->with('success', "Status visibilitas {$member->name} berhasil {$statusText}.");
    }

    public function destroy(Member $member)
    {
        if ($member->photo && Storage::disk('public')->exists($member->photo)) {
            Storage::disk('public')->delete($member->photo);
        }

        $member->delete();

        return redirect()->back()->with('success', 'Anggota berhasil dihapus!');
    }
}
