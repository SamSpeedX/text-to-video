<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <tittle>Video generator</tittle>
    <link rel="stylesheet" href="assets/css/style.css">
  </head>
  <body>
    <from>
      <label for="head">Heading</label><br>
      <input type="text" id="head" > <br>
      
      <label for="text">You video idea</label> <br>
      <input type="text" id="text" >

      <button type="submit" onsubmit="submit()">Generate video</button>
    </from>
    <div class="result" id="response1"></div>
    <div class="result" id="response2"></div>
    <div class="result" id="response3"></div>
    
    <script src="assets/application/sam.js"></script>
  </body>
</html>
