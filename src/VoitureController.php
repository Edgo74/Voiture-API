<?php

class VoitureController
{

    public function __construct(private VoitureManager $manager)
    {
    }

    public function processRequest(string $method,  ?string $id): void
    {
        if ($id == null) {
            if ($method == "GET") {
                echo json_encode($this->manager->getAll());
            } else if ($method == "POST") {

                $data =  (array)json_decode(file_get_contents("php://input"), true);

                $errors = $this->getValidationErrors($data);

                if (!empty($errors)) {
                    $this->respondUnprocessableEntity($errors);
                    return;
                }

                $id = $this->manager->create($data);
                $this->respondCreated($id);
            } else {
                $this->respondMethodNotAllowed("GET, POST");
            }
        } else {

            $voiture = $this->manager->get($id);

            if ($voiture === false) {
                $this->respondNotFound($id);
                return;
            }
            switch ($method) {
                case "GET":
                    echo json_encode($voiture);
                    break;
                case "PATCH":
                    $data =  (array)json_decode(file_get_contents("php://input"), true);

                    $errors = $this->getValidationErrors($data, false);

                    if (!empty($errors)) {
                        $this->respondUnprocessableEntity($errors);
                        return;
                    }

                    $rows = $this->manager->update($id, $data);
                    echo json_encode(["message" => "voiture updated", "rows" => $rows]);
                    break;
                case "DELETE":
                    $rows = $this->manager->delete($id);
                    echo json_encode(["message" => "voiture deleted", "rows" => $rows]);
                    break;
                default:
                    $this->respondMethodNotAllowed("GET, PATCH, DELETE");
            }
        }
    }

    private function respondMethodNotAllowed(string $allowed_methods): void
    {
        http_response_code(405);
        header("Allow: $allowed_methods");
    }

    private function respondNotFound(string $id): void
    {
        http_response_code(404);
        echo json_encode(["message" => "La voiture $id demandée n'existe pas"]);
    }

    private function respondCreated(string $id): void
    {
        http_response_code(201);
        echo json_encode(["message" => "voiture créée", "id" => $id]);
    }

    private function respondUnprocessableEntity(array $errors): void
    {
        http_response_code(422);
        echo json_encode(["errors" => $errors]);
    }

    private function getValidationErrors(array $data, bool $is_new = true): array
    {
        $errors = [];

        if ($is_new && empty($data["titre"])) {
            $errors[] = "Le titre est obligatoire";
        } else if ($is_new && empty($data["carburant"])) {
            $errors[] = "Le carburant est obligatoire";
        } else if ($is_new && empty($data["image"])) {
            $errors[] = "L'image est obligatoire";
        } else if ($is_new && empty($data["immatriculation"])) {
            $errors[] = "L'immatriculation est obligatoire";
        } else if ($is_new && empty($data["type"])) {
            $errors[] = "Le type est obligatoire";
        }

        if ($is_new && empty($data["year"])) {
            $errors[] = "L'année doit être un entier";
        } else if ($is_new && (empty($data["kilometre"]) || !is_int($data["kilometre"]))) {
            $errors[] = "Le kilometre doit être un entier";
        } else if ($is_new && (empty($data["price"]) || !is_int($data["price"]))) {
            $errors[] = "Le prix doit être un entier";
        } else if ($is_new && (empty($data["garantie"]) || !is_int($data["garantie"]))) {
            $errors[] = "La garantie doit être un entier";
        }

        return $errors;
    }
}
