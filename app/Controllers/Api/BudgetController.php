<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Helpers\Response;
use App\Models\Budget;
use Exception;
use Throwable;

class BudgetController extends BaseController
{
    public function store()
    {
        log_message("info", "start method store on BudgetController");
        try {
            $request = [
                'source' => $this->request->getVar('source'),
                'year' => $this->request->getVar('year'),
                'value' => $this->request->getVar('value'),
                'description' => $this->request->getVar('description'),
            ];

            log_message("info", json_encode($request));

            $rule = [
                'source' => 'required|string',
                "year" => "required|integer",
                "value" => "required|integer",
                "description" => "required",
            ];

            if (!$this->validateData($request, $rule)) {
                log_message("info", "validation error method store on BudgetController");
                return Response::apiResponse("failed create budget", $this->validator->getErrors(), 422);
            }


            $data = [
                'source' => $request['source'],
                'year' => $request["year"],
                'value' => $request['value'],
                'description' => $request['description'],
            ];


            $budgetModel = new Budget();
            $budgetModel->insert($data);


            $data["id"] = $budgetModel->getInsertID();

            log_message("info", "end method store on BudgetController");
            return Response::apiResponse("success create budget", $data);
        } catch (Throwable $th) {
            log_message("warning", $th->getMessage());
            return Response::apiResponse($th->getMessage(), null, 400);
        }
    }

    public function update($id = null)
    {
        log_message("info", "start method update on BudgetController");
        try {
            $budgetModel = new Budget();
            $budget = $budgetModel->find($id);
            if (!$budget) {
                throw new Exception("budget not found");
            }

            $request = [
                'id' => $id,
                'source' => $this->request->getVar('source'),
                'year' => $this->request->getVar('year'),
                'value' => $this->request->getVar('value'),
                'description' => $this->request->getVar('description'),
            ];

            log_message("info", json_encode($request));


            $rule = [
                "id" => "required",
                'source' => 'required|string',
                "year" => "required|integer",
                "value" => "required|integer",
                "description" => "required",
            ];

            if (!$this->validateData($request, $rule)) {
                log_message("info", "validation error method update on BudgetController");
                return Response::apiResponse("failed update budget", $this->validator->getErrors(), 422);
            }


            $budget["source"] = $request['source'];
            $budget["year"] = $request['year'];
            $budget["value"] = $request['value'];
            $budget["description"] = $request['description'];

            $budgetModel->save($budget);


            log_message("info", "end method update on BudgetController");
            return Response::apiResponse("success update budget", $budget);
        } catch (Throwable $th) {
            log_message("warning", $th->getMessage());
            return Response::apiResponse($th->getMessage(), null, 400);
        }
    }

    public function delete($id = null)
    {
        log_message("info", "start method delete on BudgetController");
        log_message("info", $id);
        try {
            $budgetModel = new Budget();
            $budget = $budgetModel->find($id);
            if (!$budget) {
                throw new Exception("Budget not found");
            }

            $budgetModel->delete($id);

            log_message("info", "end method delete on BudgetController");
            return Response::apiResponse("success delete budget", null);
        } catch (Throwable $th) {
            log_message("warning", $th->getMessage());
            return Response::apiResponse($th->getMessage(), null, 400);
        }
    }

    public function get($id = null)
    {
        log_message("info", "start method get on BudgetController");
        log_message("info", $id);
        try {
            $budgetModel = new Budget();
            $budget = $budgetModel->find($id);
            if (!$budget) {
                throw new Exception("budget not found");
            }

            log_message("info", "end method get on BudgetController");
            return Response::apiResponse("success get budget", $budget);
        } catch (Throwable $th) {
            log_message("warning", $th->getMessage());
            return Response::apiResponse($th->getMessage(), null, 400);
        }
    }

    public function getAll()
    {
        log_message("info", "start method getAll on BudgetController");
        try {
            $budgetModel = new Budget();
            $budget = $budgetModel->findAll();

            log_message("info", "end method getAll on BudgetController");
            return Response::apiResponse("success getAll budget", $budget);
        } catch (Throwable $th) {
            log_message("warning", $th->getMessage());
            return Response::apiResponse($th->getMessage(), null, 400);
        }
    }
}
