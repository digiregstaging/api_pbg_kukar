<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Helpers\Response;
use App\Helpers\Upload;
use App\Models\Document;
use Exception;
use Throwable;

class DocumentController extends BaseController
{
    public function store()
    {
        log_message("info", "start method store on DocumentController");
        try {
            $request = [
                'doc_name' => $this->request->getVar('doc_name'),
                'base_64' => $this->request->getVar('base_64'),
                'type' => $this->request->getVar('type'),
                'additional_data_id' => $this->request->getVar('additional_data_id'),
                'ext' => $this->request->getVar('ext'),
            ];

            log_message("info", json_encode($request));

            $rule = [
                'doc_name' => 'required|string',
                "base_64" => "required",
                "type" => "required|integer",
                "additional_data_id" => "required|integer",
                "ext" => "required",
            ];

            if (!$this->validateData($request, $rule)) {
                log_message("info", "validation error method store on DocumentController");
                return Response::apiResponse("failed create document", $this->validator->getErrors(), 422);
            }


            $url = "";

            if (!isset(Document::$ext[$request["ext"]])) {
                throw new Exception("invalid ext");
            }

            if ($request["ext"] == "application/pdf") {
                $url = Upload::setBase64($request["base_64"])
                    ->setFileName(Document::$type[$request["type"]] . "_" . date("Y-m-d", time()))
                    ->setPath("assets/documents/" . Document::$type[$request["type"]] . "/")
                    ->savePdf();
            }

            if (!isset(Document::$type[$request["type"]])) {
                throw new Exception("invalid type");
            }

            $data = [
                'doc_name' => $request['doc_name'],
                'url' => $url,
                'type' => Document::$type[$request["type"]],
                'additional_data_id' => $request['additional_data_id'],
                'ext' => $request['ext'],
            ];


            $documentModel = new Document();
            $documentModel->insert($data);


            $data["id"] = $documentModel->getInsertID();

            log_message("info", "end method store on DocumentController");
            return Response::apiResponse("success create document", $data);
        } catch (Throwable $th) {
            log_message("warning", $th->getMessage());
            return Response::apiResponse($th->getMessage(), null, 400);
        }
    }

    // public function update($id = null)
    // {
    //     log_message("info", "start method update on DocumentController");
    //     try {
    //         $documentModel = new Document();
    //         $document = $documentModel->find($id);
    //         if (!$document) {
    //             throw new Exception("document not found");
    //         }

    //         $request = [
    //             'id' => $id,
    //             'document' => $this->request->getVar('document'),
    //             'activity' => $this->request->getVar('activity'),
    //             'sub_activity' => $this->request->getVar('sub_activity'),
    //             'description' => $this->request->getVar('description'),
    //         ];

    //         log_message("info", json_encode($request));


    //         $rule = [
    //             "id" => "required",
    //             'document' => 'required|string',
    //             "activity" => "required|string",
    //             "sub_activity" => "required|string",
    //             "description" => "required",
    //         ];

    //         if (!$this->validateData($request, $rule)) {
    //             log_message("info", "validation error method update on DocumentController");
    //             return Response::apiResponse("failed update document", $this->validator->getErrors(), 422);
    //         }


    //         $document["document"] = $request['document'];
    //         $document["activity"] = $request['activity'];
    //         $document["sub_activity"] = $request['sub_activity'];
    //         $document["description"] = $request['description'];

    //         $documentModel->save($document);


    //         log_message("info", "end method update on DocumentController");
    //         return Response::apiResponse("success update document", $document);
    //     } catch (Throwable $th) {
    //         log_message("warning", $th->getMessage());
    //         return Response::apiResponse($th->getMessage(), null, 400);
    //     }
    // }

    public function delete($id = null)
    {
        log_message("info", "start method delete on DocumentController");
        log_message("info", $id);
        try {
            $documentModel = new Document();
            $document = $documentModel->find($id);
            if (!$document) {
                throw new Exception("document not found");
            }

            $url_without_static_url = explode("https://api.pbg.kukar.geoportal.co.id", $document["url"]);
            log_message("info", json_encode($url_without_static_url));
            if (!isset($url_without_static_url[1])) {
                throw new Exception("invalid path 1");
            }

            $url_without_hastag = explode("#", $url_without_static_url[1]);
            log_message("info", json_encode($url_without_hastag));
            if (!isset($url_without_hastag[0])) {
                throw new Exception("invalid path 2");
            }

            $fileToDelete = "public" . $url_without_hastag[0];

            if (file_exists($fileToDelete)) {
                if (unlink($fileToDelete)) {
                    log_message("info", "success delete file " . $fileToDelete);
                } else {
                    log_message("info", "failed delete file " . $fileToDelete);
                }
            } else {
                log_message("info", "file not found " . $fileToDelete);
            }

            $documentModel->delete($id);

            log_message("info", "end method delete on DocumentController");
            return Response::apiResponse("success delete document", null);
        } catch (Throwable $th) {
            log_message("warning", $th->getMessage());
            return Response::apiResponse($th->getMessage(), null, 400);
        }
    }

    // public function get($id = null)
    // {
    //     log_message("info", "start method get on DocumentController");
    //     log_message("info", $id);
    //     try {
    //         $documentModel = new Document();
    //         $document = $documentModel->find($id);
    //         if (!$document) {
    //             throw new Exception("document not found");
    //         }

    //         log_message("info", "end method get on DocumentController");
    //         return Response::apiResponse("success get document", $document);
    //     } catch (Throwable $th) {
    //         log_message("warning", $th->getMessage());
    //         return Response::apiResponse($th->getMessage(), null, 400);
    //     }
    // }

    public function getAll()
    {
        log_message("info", "start method getAll on DocumentController");
        try {
            $request = [
                'additional_data_id' => $this->request->getGet('additional_data_id'),
                'type' => $this->request->getGet('type'),
            ];

            log_message("info", json_encode($request));
            $documentModel = new Document();

            if ($request["additional_data_id"]) {
                $documentModel = $documentModel->where("additional_data_id", $request["additional_data_id"]);
            }

            if ($request["type"]) {
                $type = isset(Document::$type[$request["type"]]) ? Document::$type[$request["type"]] : "";
                $documentModel = $documentModel->where("type", $type);
            }

            $document = $documentModel->findAll();

            log_message("info", "end method getAll on DocumentController");
            return Response::apiResponse("success getAll document", $document);
        } catch (Throwable $th) {
            log_message("warning", $th->getMessage());
            return Response::apiResponse($th->getMessage(), null, 400);
        }
    }
}
