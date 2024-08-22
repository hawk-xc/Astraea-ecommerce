<?php

namespace App\Http\Controllers\Fo\contact;

use App\Repositories\ContactUsRepository;
use App\Repositories\AboutUsRepository;
use App\Repositories\VisitorMailRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ContactUsController extends Controller
{
    private ContactUsRepository $contactUsRepository;
    private AboutUsRepository $aboutUsRepository;
    private VisitorMailRepository $visitorMailRepository;
    protected $data = array();
    public function __construct(ContactUsRepository $contactUsRepository, AboutUsRepository $aboutUsRepository, VisitorMailRepository $visitorMailRepository)
    {
        $this->contactUsRepository = $contactUsRepository;
        $this->aboutUsRepository = $aboutUsRepository;
        $this->visitorMailRepository = $visitorMailRepository;
        $this->data['title'] = 'Contact';
        $this->data['view_directory'] = "guest.feature.contact";
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ref = $this->data;
        $data['contact'] = $this->contactUsRepository->getById('1');
        $data['about'] = $this->aboutUsRepository->getById('1');
        return view($this->data['view_directory'] . '.index', compact('ref', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $record = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ]);
        try {
            $this->visitorMailRepository->store($record);
            return redirect()->back()->with('toast_success', 'Your message has been sent successfully!');
        } catch (Exception $e) {
            // Menangani kesalahan saat menyimpan data
            return redirect()->back()->withErrors(['toast_warning' => 'There was an error sending your message. Please try again later.']);
        }
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
