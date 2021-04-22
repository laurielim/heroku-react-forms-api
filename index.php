<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type");
    /**
     * GET /notes
     * return all notes
     * 
     * POST /notes
     * parameters must exist
     * if we have id, name, ingredients, difficulty
     * add a new recipe to our recipes array and save the json
     * 
     * PUT /recipes
     * parameters must exist
     * if we have id, name, ingredients, difficulty
     * find if there is a recipe with such id, if found, update
     * 
     * DELETE /recipes/{id}
     * delete recipe with the matching id if found
     * 
     */
$data = file_get_contents('notes.json');

// Assign request method to a variable
$request_method = $_SERVER['REQUEST_METHOD'];

switch($request_method) {
    case 'GET':
        get_notes();
        break;
    case 'POST':
        add_new_note();
        break;
    default: 
        echo json_encode(array('message' => 'An error has occured'));
        break;
}

function get_notes(){
    echo $GLOBALS['data'];
};
function add_new_note(){
    $formatted_data = json_decode($GLOBALS['data'], true);
    $notesCount = count($formatted_data['notes']);

    // Get form data
    $new_note = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

    if ($new_note === "application/json") {
        //Receive the RAW post data.
        $content = trim(file_get_contents("php://input"));
        $decoded = json_decode($content, true);
      }

    // Add an id
    $decoded['id'] = $notesCount + 1;
    // Make a copy of results
    $new_data = $formatted_data;
    // Add new note
    array_push($new_data['notes'], $decoded);
    // Convert array to json format
    $json_new_data = json_encode($new_data); 
    // Create new JSON file
    $write_file_result = file_put_contents('notes.json', $json_new_data);
};

?>