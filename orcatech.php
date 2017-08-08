<?php
//create array to make sure all items needed are present

$bROL = array(                       

  "Request" => isset($_GET['request']),

  "Offset" => isset($_GET['offset']),

  "Limit" => isset($_GET['limit']),

  );

  //find if any of the input variables are not found

if(in_array(false, $bROL, true)){    

    $incomplete = "Error: missing ";

  foreach($bROL as $k => $v){

    if($v == FALSE){

      if($incomplete == "Error: missing "){

        $incomplete .= $k;

      }

      else{

        $incomplete .= ", ";

        $incomplete .= $k;

      }

    }

  }

  echo $incomplete;

}

//all needed variables present

else{                                

  $request = $_GET['request'];

//make sure request string is a known type

  if(strtoupper($request) == "PERSON" || strtoupper($request) == "ITEMS"){

    $bRequest = true;

  }

  else{

    $bRequest = false;

  }

 

  $offset = $_GET['offset'];

  $limit = $_GET['limit'];

  //find if offset and limit are numeric as needed

  $bIsNumeric = array(

    "Offset NOT Numeric" => is_numeric($offset),

    "Limit NOT Numeric" => is_numeric($limit),

    "Request NOT Valid" => $bRequest,

    );

    if(in_array(false, $bIsNumeric, true)){

      $error = "Invalid Input: ";

      foreach($bIsNumeric as $k => $v){

        if($v == FALSE){

          if($error == "Invalid Input: "){

            $error .= $k;

         }

          else{

            $error .= ", ";

            $error .= $k;

          }

        }

      }
	  echo $error;
    }

    else{

//all variables are valid, proceed

      if(($handle = fopen($request . ".csv", "r")) != FALSE){

         $csv = array_map('str_getcsv', file($request . ".csv"));

         $arrHeaders = array();
         $arrData = array();
         
         foreach($csv[0] as $headers){
             $arrHeaders[] = $headers;
         }
         $count = $offset + $limit;
         for ($i = $offset; $i < $count && $i < sizeof($csv); $i++) {
            $arrNew = "";
            $index = 0;
            foreach($csv[$i] as $data){
             $arrNew .= json_encode(array($arrHeaders[$index] => $data,));
             $index++;
            }
            $arrData[] = $arrNew;
         }
         
         foreach($arrData as $jsonData){
             echo $jsonData;
         }

        }

        fclose($handle);

    }
}
?>

</body>

</html>