<?php

namespace CodeProject\Http\Controllers;

use Illuminate\Http\Request;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use CodeProject\Services\ProjectTaskService;
use CodeProject\Http\Requests\ProjectTaskCreateRequest;
use CodeProject\Http\Requests\ProjectTaskUpdateRequest;


class ProjectTasksController extends Controller
{
    /**
     * @var ProjectTaskService
     */
    protected $service;

    public function __construct(ProjectTaskService $service)
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

    public function show($id, $taskId)
    {
        return $this->service->find($id, $taskId);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $projectTask = $this->repository->find($id);

        return view('projectTasks.edit', compact('projectTask'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  ProjectTaskUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     */
    public function update(ProjectTaskUpdateRequest $request, $id)
    {

        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $projectTask = $this->repository->update($id, $request->all());

            $response = [
                'message' => 'ProjectTask updated.',
                'data'    => $projectTask->toArray(),
            ];

            if ($request->wantsJson()) {

                return response()->json($response);
            }

            return redirect()->back()->with('message', $response['message']);
        } catch (ValidatorException $e) {

            if ($request->wantsJson()) {

                return response()->json([
                    'error'   => true,
                    'message' => $e->getMessageBag()
                ]);
            }

            return redirect()->back()->withErrors($e->getMessageBag())->withInput();
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleted = $this->repository->delete($id);

        if (request()->wantsJson()) {

            return response()->json([
                'message' => 'ProjectTask deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'ProjectTask deleted.');
    }
}
