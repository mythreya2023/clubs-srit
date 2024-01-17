<?php

// Get JSON data sent via POST
$jsonData = file_get_contents('php://input');


echo $_SERVER["HTTP_TYPE"];
// Optionally, you can process or validate the data here
if(isset($_SERVER["HTTP_TYPE"])&&$_SERVER["HTTP_TYPE"]=="create"){
// Write the JSON data to a file
$path='../votings/'.generateUniqueFileName();
file_put_contents($path, $jsonData);

echo "JSON data saved successfully.";


// sql functions.....
}
elseif(isset($_SERVER["HTTP_TYPE"])&&isset($_SERVER["HTTP_FNAME"])&&$_SERVER["HTTP_TYPE"]=="update"){
    // Write the JSON data to a file
    // $path='../votings/'.$_SERVER["HTTP_FNAME"];
    // file_put_contents($path, $jsonData);
    

    // Assuming the incoming data is JSON
    $data = json_decode($jsonData, true); // Convert JSON string to PHP array
    
    // Check if data is valid
    if (json_last_error() === JSON_ERROR_NONE) {
        // Path to the JSON file
        $jsonFilePath = '../votings/'.$_SERVER["HTTP_FNAME"];
    
        // Convert array back to JSON
        $jsonString = json_encode($data, JSON_PRETTY_PRINT);
    
        // Write the data to the file
        if (file_put_contents($jsonFilePath, $jsonString)) {
            echo json_encode(["message" => "Data successfully updated"]);
        } else {
            echo json_encode(["error" => "Failed to write to file"]);
        }
    } else {
        echo json_encode(["error" => "Invalid JSON data"]);
    }
    
    


    echo "JSON data saved successfully.";
    
    
    // sql functions.....
    }

elseif(isset($_SERVER["HTTP_TYPE"])&&$_SERVER["HTTP_TYPE"]=="make-live"){
    $path='../voteCounts/'.generateUniqueFileName();
    
    $jsonData = json_decode($jsonData,true);
    file_put_contents($path, $jsonData);
    
    echo "JSON data saved successfully.";
  
    // sql functions.....
}
  
function generateUniqueFileName() {
    $text = 'file'; // Fixed text part
    $timeStamp = uniqid(); // Generate a unique ID based on the current microsecond time
    $randomPart = substr(md5(mt_rand()), 0, 5); // Generate a random alphanumeric string and take 5 characters

    return $text . '_' . $timeStamp . '_' . $randomPart . '.json'; // .ext is a placeholder for file extension
}