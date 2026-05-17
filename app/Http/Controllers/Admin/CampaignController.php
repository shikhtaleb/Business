<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SendCampaignJob;
use App\Models\Campaign;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CampaignController extends Controller
{
    public function index(): View
    {
        $campaigns = Campaign::orderBy('created_at', 'desc')->paginate(20);

        return view('admin.campaigns.index', compact('campaigns'));
    }

    public function create(): View
    {
        return view('admin.campaigns.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'subject_ar'  => 'required|string|max:255',
            'subject_en'  => 'nullable|string|max:255',
            'body_ar'     => 'required|string',
            'body_en'     => 'nullable|string',
            'target_lang' => 'nullable|in:ar,en,nl,de',
        ]);

        Campaign::create($data);

        return redirect()->route('admin.campaigns.index')->with('success', 'تم إنشاء الحملة البريدية بنجاح.');
    }

    public function edit(Campaign $campaign): View
    {
        return view('admin.campaigns.edit', compact('campaign'));
    }

    public function update(Request $request, Campaign $campaign): RedirectResponse
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'subject_ar'  => 'required|string|max:255',
            'subject_en'  => 'nullable|string|max:255',
            'body_ar'     => 'required|string',
            'body_en'     => 'nullable|string',
            'target_lang' => 'nullable|in:ar,en,nl,de',
        ]);

        $campaign->update($data);

        return redirect()->route('admin.campaigns.index')->with('success', 'تم تحديث الحملة بنجاح.');
    }

    public function destroy(Campaign $campaign): RedirectResponse
    {
        $campaign->delete();

        return redirect()->route('admin.campaigns.index')->with('success', 'تم حذف الحملة.');
    }

    public function send(Campaign $campaign): RedirectResponse
    {
        if ($campaign->status === 'sent') {
            return back()->with('error', 'هذه الحملة أُرسلت بالفعل.');
        }

        if ($campaign->status === 'sending') {
            return back()->with('error', 'الحملة جارٍ إرسالها الآن، يرجى الانتظار.');
        }

        $campaign->update(['status' => 'sending']);

        SendCampaignJob::dispatch($campaign);

        return back()->with('success', 'جاري إرسال الحملة...');
    }
}
