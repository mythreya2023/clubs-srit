<?php
// Assuming $submission contains the submitted data for a quiz
// and $questionId and $selectedOption are obtained from the quiz submission.

$inputJSON = file_get_contents('php://input');
$myArray = json_decode($inputJSON, true);
$fileNameIpt=$_SERVER['HTTP_FILENAME'];

// Load the questions and counts
$jsonFilePath = '../voteCounts/'.$fileNameIpt;
$jsonString = file_get_contents($jsonFilePath);

$quizData = json_decode($jsonString, true);
// Update the count for the selected option

$questionId=0;
foreach ($quizData['cards'] as $card) {
    $questionId++;
    $optId=0;
  if (trim($card['card']) == $questionId) {
    foreach ($card['options'] as $option) {

      if (trim($option['op']) == trim($myArray[$questionId-1])) {
        
        $quizData['cards'][$questionId-1]["options"][$optId]['count'] = $option['count']+ 1;

        break ; // Exit both loops
      }
      
    $optId++;
    }
  }
}
// Save the updated counts back to the file
$jsonString = json_encode($quizData);
echo file_put_contents($jsonFilePath, $jsonString);
