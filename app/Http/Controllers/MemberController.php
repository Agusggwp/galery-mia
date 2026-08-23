<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Media;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $className = $request->input('class');
        $generation = $request->input('generation');

        $query = Member::approved()->visible();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nickname', 'like', "%{$search}%")
                  ->orWhere('student_number', 'like', "%{$search}%");
            });
        }

        if ($className) {
            $query->where('class_name', $className);
        }

        if ($generation) {
            $query->where('generation', $generation);
        }

        $members = $query->orderBy('name', 'asc')->paginate(12)->withQueryString();

        // Get filter dropdown options
        $classes = Member::approved()->visible()->distinct()->pluck('class_name')->filter()->sort();
        $generations = Member::approved()->visible()->distinct()->pluck('generation')->filter()->sort();

        return view('members.index', compact('members', 'search', 'className', 'generation', 'classes', 'generations'));
    }

    public function show($slug)
    {
        $member = Member::where('slug', $slug)->approved()->visible()->firstOrFail();

        // Fetch related class photos for visual enrichment
        $relatedMedia = Media::where('is_visible', true)
            ->where('type', 'image')
            ->inRandomOrder()
            ->take(8)
            ->get();

        return view('members.show', compact('member', 'relatedMedia'));
    }
}
