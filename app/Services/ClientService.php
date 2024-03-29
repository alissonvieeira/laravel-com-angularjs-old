<?php

namespace CodeProject\Services;


use CodeProject\Repositories\ClientRepository;
use CodeProject\Validators\ClientValidator;
use Illuminate\Contracts\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Prettus\Validator\Exceptions\ValidatorException;

class ClientService
{
    /**
     * @var ClientRepository
     */
    protected $repository;
    /**
     * @var ClientValidator
     */
    protected $validator;

    public function __construct(ClientRepository $repository, ClientValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    public function all(){
        try{

            return $this->repository->all();

        }catch (ModelNotFoundException $e){
            return [
                'message' => 'Ocorreu um erro ao buscar os clientes.',
            ];
        }
    }

    public function create(array $data){
        try {

            $this->validator->with($data)->passesOrFail();
            return $this->repository->create($data);

        } catch (ValidatorException $e){
            return [
                'error'   => true,
                'message' => $e->getMessageBag()
            ];
        }
    }

    public function find($id){
        try {

            return $this->repository->find($id);

        }catch (ModelNotFoundException $e){
            return [
                'message' => 'Client não encontrado',
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
                'error'   => true,
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