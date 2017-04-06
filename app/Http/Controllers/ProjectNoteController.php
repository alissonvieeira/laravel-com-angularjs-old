<?php

namespace CodeProject\Http\Controllers;

use CodeProject\Services\ProjectNoteService;
use Illuminate\Http\Request;

use CodeProject\Http\Requests;
use CodeProject\Http\Controllers\Controller;

class ProjectNoteController extends Controller
{
    /**
     * @var ProjectNoteService
     */
    private $service;

    public function __construct(ProjectNoteService $service)
    {
        $this->service = $service;
    }

    public function index($id)
    {
        return $this->service->index($id);
    }

    public function store(Request $request)
    {
        return $this->service->create($request->all());
    }

    public function show($id, $noteId)
    {
        return $this->service->find($id, $noteId);
    }

    public function update(Request $request, $id, $noteId)
    {
        return $this->service->update($request->all(), $noteId);
    }

    public function destroy($id, $noteId)
    {
        $this->service->delete($id, $noteId);
    }
}
