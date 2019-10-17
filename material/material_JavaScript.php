<?php /*********************************************************
**
** material
** 
** material_JavaScript.php
**   Hook for simple markdown notation for JavaScript code
**   snippets.
*/

////////////////////////////////////////////////////////////////
//

if (!function_exists('tr')) {
  function tr($t,$x,$y) {
    return str_replace($x,$y,$t);
  }
}

if (!function_exists('startsWith')) {
  function startsWith($haystack, $needle) {
      return $needle === "" || strpos($haystack, $needle) === 0;
  }
}

function material_hook_JavaScript ($s) {
  $s = tr($s, "\r", "");

  $inp = $s;
  $s = "";
  $flag = array('line' => 0, 'delimited' => 0, 'literal' => 'none', 'escaped' => 0);
  $lineComment = 'false';
  for ($i = 0; $i < strlen($inp); $i++) {
    $c = $inp[$i];

    // Handle single-line newline-terminated comments.
    if ($c === '#' && $flag['line'] == 0 && $flag['delimited'] == 0 && $flag['literal'] == 'none') {
      $s .= '<span class="comment">';
      $s .= $c;
      $flag['line'] = 1;
    } else if ($c == "\n" && $flag['line'] == 1) {
      $s .= '</span>';
      $s .= $c;
      $flag['line'] = 0;
    }

    // Handle string literals.
    else if ($c == '"') {
      if ($flag['line'] == 0 && $flag['delimited'] == 0 && $flag['literal'] == 'none') {
        $s .= '<span class="literal">';
        $s .= $c;
        $flag['literal'] = '"';
      } else if ($flag['literal'] == '"') {
        $s .= $c;
        $s .= '</span>';
        $flag['literal'] = 'none';
      }
    }
    else if ($c == "'") {
      if ($flag['line'] == 0 && $flag['delimited'] == 0 && $flag['literal'] == 'none') {
        $s .= '<span class="literal">';
        $s .= $c;
        $flag['literal'] = "'";
      } else if ($flag['literal'] == "'") {
        $s .= $c;
        $s .= '</span>';
        $flag['literal'] = 'none';
      }
    }
    
    // Defaults.
    else if ($c === " ") {
      $s .= "&nbsp;";
    } else if ($c === "@") {

      // Handle built in commands.
      $handled = false;
      $commands = array(
          'function', 'return',
          'for', 'while', 'break', 'continue',
          'if', 'else'
        );
      $builtins = array(
          'exit',
          'print',
          'len',
          'range',
          'max',
          'min',
          'pow',
          'sum',
          'int',
          'type',
          'str',
          'dict',
          'list',
          'tuple',
          'None',
          'True',
          'False'
        );
      foreach ($builtins as $prefix) {
        if (startsWith(substr($inp, $i), '@'.$prefix)) {
          $s .= '<span class="builtin">' . $prefix . '</span>';
          $i += strlen('@'.$prefix)-1;
          $handled = true;
          break;
        }
      }
      foreach ($commands as $prefix) {
        if (startsWith(substr($inp, $i), '@'.$prefix)) {
          $s .= '<span class="keyword">' . $prefix . '</span>';
          $i += strlen('@'.$prefix)-1;
          $handled = true;
          break;
        }
      }
      if (!$handled)
        $s .= $c;
    } else {
      $s .= $c;
    }
  }

  $s = tr($s, ">>>", '<span style="color:#ABABAB;">&gt;&gt;&gt;</span>');
  $s = tr($s, "\n", "<br/>");
  return $s;
}

/*eof*/?>