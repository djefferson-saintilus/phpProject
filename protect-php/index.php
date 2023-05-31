<?php
// index.php

include './protect.php';

// Rest of your PHP code for the page
// ...
?>
<!DOCTYPE html>
<html>
<head>
  <title>Basic HTML Page</title>
  <style>
    /* CSS styles */
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 20px;
    }
    
    h1 {
      color: #333;
    }
    
    #myButton {
      padding: 10px 20px;
      background-color: #4CAF50;
      color: #fff;
      border: none;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <h1>Welcome to my Basic HTML Page!</h1>
  
  <button id="myButton">Click Me</button>
  
  <script>
    // JavaScript code
    var button = document.getElementById('myButton');
    
    button.addEventListener('click', function() {
      alert('Button clicked!');
    });
  </script>
</body>
</html>
