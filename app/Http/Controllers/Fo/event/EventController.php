<?php
namespace App\Http\Controllers\fo\event;

use App\Repositories\ContactUsRepository;
use App\Repositories\EventRepository;
use App\Repositories\AboutUsRepository;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;

class EventController extends Controller
{
    private ContactUsRepository $contactUsRepository;
    private EventRepository $EventRepository;
    private AboutUsRepository $aboutUsRepository;

    protected $data = array();
    public function __construct(ContactUsRepository $contactUsRepository, EventRepository $EventRepository, AboutUsRepository $aboutUsRepository)
    {
        $this->contactUsRepository = $contactUsRepository;
        $this->aboutUsRepository = $aboutUsRepository;
        $this->eventRepository = $EventRepository;
        $this->data['title'] = 'Event';
        $this->data['view_directory'] = "guest.feature.event";
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ref = $this->data;
        $data['events'] = $this->eventRepository->getAllFo();
        $data['etotal'] = $this->eventRepository->getTotal();
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $ref = $this->data;
        $data['event'] = $this->eventRepository->getBySlugFo($slug);
        $data['contact'] = $this->contactUsRepository->getById('1');
        $data['about'] = $this->aboutUsRepository->getById('1');
        return view($this->data['view_directory'] . '.detail', compact('ref', 'data'));
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
