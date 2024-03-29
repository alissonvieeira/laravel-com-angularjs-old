<?php

namespace CodeProject\Services;

use CodeProject\Repositories\ProjectNoteRepository;
use CodeProject\Validators\ProjectNoteValidator;
use Illuminate\Contracts\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Prettus\Validator\Exceptions\ValidatorException;

class ProjectNoteService
{
    /**
     * @var ProjectNoteRepository
     */
    protected $repository;
    /**
     * @var ProjectNoteValidator
     */
    protected $validator;

    public function __construct(ProjectNoteRepository $repository, ProjectNoteValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    public function index($id)
    {
        try{

            return $this->repository->findWhere(['project_id' => $id]);

        }catch (ModelNotFoundException $e){
            return [
                'message' => 'Ocorreu um erro ao buscar os projetos'
            ];
        }
    }

    public function create(array $data)
    {
        try {

            $this->validator->with($data)->passesOrFail();
            return $this->repository->create($data);

        } catch (ValidatorException $e){
            return [
                'error' => true,
                'message' => $e->getMessageBag()
            ];
        }
    }

    public function find($id, $noteId)
    {
        try{

            return $this->repository->findWhere(['project_id' => $id, 'id' => $noteId]);

        }catch (ModelNotFoundException $e){
            return [
                'message' => 'Projeto não encontrado',
            ];
        }
    }

    public function update(array $data, $id)
    {
        try{

            $this->validator->with($data)->passesOrFail();
            return $this->repository->update($data, $id);

        } catch(ValidationException $e){
            return [
                'error' => true,
                'message' => $e->getMessageBag()
            ];
        }
    }

    public function delete($id)
    {
        try{

            return $this->repository->delete($id);

        }catch (ModelNotFoundException $e){
            return [
                'error'   => true,
                'message' => $e->getMessageBag()
            ];
        }
    }
}