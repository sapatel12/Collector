<?php

require '../../initiateTool.php';
ob_end_clean();

if (!isset($_POST['filename'])) exit;

$filename = $_POST['filename'];

if(is_file("Analyses/$filename.txt")
   AND $filename !== ''
   AND preg_match('/[^a-zA-Z0-9._ -]/', $filename) === 0
) {
    
  echo file_get_contents("Analyses/$filename.txt");  
      
} else {
  echo "failed to load";
}