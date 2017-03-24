<?php

namespace CodeProject\Services;

use CodeProject\Repositories\ProjectRepository;
use CodeProject\Validators\ProjectValidator;
use Illuminate\Contracts\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Prettus\Validator\Exceptions\ValidatorException;

class ProjectService
{
    /**
     * @var ProjectRepository
     */
    protected $repository;
    /**
     * @var ProjectValidator
     */
    protected $validator;

    public function __construct(ProjectRepository $repository, ProjectValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    public function all()
    {
        try{

            return $this->repository->with(['owner', 'client'])->all();

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
            return $this->repository->with(['owner', 'client'])->create($data);

        } catch (ValidatorException $e){
            return [
                'error' => true,
                'message' => $e->getMessageBag()
            ];
        }
    }

    public function find($id)
    {
        try{

            return $this->repository->with(['owner', 'client'])->find($id);

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
            return $this->repository->with(['owner', 'client'])->update($data, $id);

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

            return $this->repository->with(['owner', 'client'])->delete($id);

        }catch (ModelNotFoundException $e){
            return [
                'error'   => true,
                'message' => $e->getMessageBag()
            ];
        }
    }
}