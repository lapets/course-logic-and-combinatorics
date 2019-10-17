<?php /*********************************************************
**
** Lecture and assignment materials.
** 
** m.php
**   Invokes the material instance for this course.
*/

////////////////////////////////////////////////////////////////
//

//ini_set('display_errors',1); 
//error_reporting(E_ALL);

// Load the library and rendering hooks.
include("material/material.php");
include("material/material_math.php");
include("material/material_Python.php");

// Build the course material data structure instance by setting
// the configuration parameters for the material invocation.
$m = new Material(
       array(
           'file' => '131.xml',
           'path' => 'material/',
           'message' => '<b>NOTE:</b> This page contains all the examples presented during the lectures, as well as all the homework assignments. <b><a href="index.html">Click here</a></b> to go back to the main page with the course information and schedule.<br/>',
           'toc' => 'true'
         )
      );

// Render the course materials in the specified XML file.
$m->html(); 

/*eof*/?>