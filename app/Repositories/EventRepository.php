<?php

namespace App\Repositories;

use App\Interfaces\EventInterface;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Cviebrock\EloquentSluggable\Services\SlugService;

class EventRepository implements EventInterface
{
    public function getAll()
    {
        return Event::orderBy('updated_at', 'desc')->get(['id', 'title', 'tanggal_acara', 'is_active']);
    }

    public function getById($id)
    {
        return Event::where('id', $id)->orderBy('updated_at', 'desc')->first(['id', 'title', 'tanggal_acara', 'description', 'cover_image', 'is_active']);
    }

    public function store($data)
    {
        return Event::create($data);
    }

    public function edit($id, $data)
    {
        return Event::whereId($id)->update($data);
    }

    public function destroy($id)
    {
        return Event::destroy($id);
    }

    public function sluggable($title)
    {
        return SlugService::createSlug(Event::class, 'slug', $title);
    }

    public function getAllFo()
    {
        return DB::table('events')
                ->leftJoin('users', 'events.created_by', '=', 'users.id')
                ->select(
                    'events.title',
                    'users.name',
                    'events.slug',
                    DB::raw("LEFT(events.description, 120) as description"),
                    'events.cover_image',
                    'events.is_active',
                    'events.created_by',
                    DB::raw("DATE_FORMAT(events.updated_at, '%d %M %Y') as updated_at")
                )
                ->where('events.is_active', '=', '1')
                ->orderBy('events.updated_at', 'desc')
                ->paginate(15);
    }

    public function getBeranda()
    {
        return DB::table('events')
                ->leftJoin('users', 'events.created_by', '=', 'users.id')
                ->select(
                    'events.title',
                    'users.name',
                    'events.slug',
                    DB::raw("LEFT(events.description, 120) as description"),
                    'events.cover_image',
                    'events.is_active',
                    'events.created_by',
                    DB::raw("DATE_FORMAT(events.updated_at, '%d %M %Y') as updated_at")
                )
                ->where('events.is_active', '=', '1')
                ->orderBy('events.updated_at', 'desc')
                ->limit(3)->get();
    }

    public function GetTotal()
    {
        return Event::where('events.is_active', '=', '1')->count();
    }

    public function getBySlugFo($slug)
    {
        return DB::table('events')
                ->leftJoin('users', 'events.created_by', '=', 'users.id')
                ->select(
                    'events.title',
                    'users.name',
                    'events.description',
                    'events.cover_image',
                    'events.is_active',
                    'events.created_by',
                    DB::raw("DATE_FORMAT(events.updated_at, '%d %M %Y') as updated_at")
                )
                ->where('events.is_active', '=', '1')
                ->where('events.slug', '=', $slug)
                ->orderBy('events.updated_at', 'desc')
                ->first();
    }
}
