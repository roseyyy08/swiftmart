<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $members = Member::paginate(10);

        $totalMember = Member::count();
        $totalPoinBeredar = Member::sum('points');
        $rataRataPoin = $totalMember > 0 ? round($totalPoinBeredar / $totalMember) : 0;

        return view('members.index', compact('members', 'totalMember', 'totalPoinBeredar', 'rataRataPoin'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('members.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required|string',
        ], [
            'name.required' => 'Nama harus diisi!',
            'phone.required' => 'Nomor telepon harus diisi!',
        ]);

        Member::create([
            'name' => $request->name,
            'phone' => $request->phone
        ]);

        return redirect()
        ->route('members.index')
        ->with('success', 'Member berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $member = Member::findOrFail($id);
        return view('members.edit', compact('member'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required|string',
        ], [
            'name.required' => 'Nama harus diisi!',
            'phone.required' => 'Nomor telepon harus diisi!',
        ]);

        $member = Member::findOrFail($id);
        $member->name= $request->name;
        $member->phone= $request->phone;

        $member->save();
        return redirect()
        ->route('members.index')
        ->with('success', 'Member berhasil diubah!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $member = Member::findOrFail($id);
        $member->delete();

        return redirect()
        ->route('members.index')
        ->with('success', 'Member berhasil dihapus!');
    }
}
