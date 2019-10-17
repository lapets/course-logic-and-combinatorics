<?php /*********************************************************
**
** material
** 
** material.php
**   Rendering of course material XML files as HTML webpages.
*/

////////////////////////////////////////////////////////////////
// Container class for materials data structure and the
// associated functionality.

global $material; $material = null;

class Material {

  // Common constants.
  public static $blocks = array(
      'definition' => 'Definition',
      'fact' => 'Fact',
      'theorem' => 'Theorem',
      'conjecture' => 'Conjecture',
      'algorithm' => 'Algorithm',
      'protocol' => 'Protocol',
      'example' => 'Example',
      'exercise' => 'Exercise'
    );

  // The configuration.
  private $material = null;
  
  public function Material ($m) {
  
    global $material;
    $material = $m;

    if (!isset($material))
      $material = array();
    if (!array_key_exists('file', $material) && !array_key_exists('content', $material))
      die(sprintf("material: no input file or content specified. Exiting."));
    if (!array_key_exists('path', $material))
      $material['path'] = '';
    if (!array_key_exists('toc', $material))
      $material['toc'] = 'true';
    if (!array_key_exists('message', $material))
      $material['message'] = '';

    $this->material = $material;
  }

  ///////////////////////////////////////////////////////////////////
  // XML Parsing and HTML rendering procedure for the table of
  // contents.

  public function html() {
    if (array_key_exists('file', $this->material))
      $xml = file_get_contents($this->material['file']);
    else if (array_key_exists('content', $this->material))
      $xml = $this->material['content'];

    echo '<?xml version="1.0" encoding="UTF-8"?>';
    echo "\n".'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
    echo "\n"."<html>"."\n";

    $tocHTML = material::parse_render_toc($this->material, $xml);
    material::parse_render($this->material, $xml, $tocHTML);
    
    echo "\n"."</html>"."\n"."<!--eof-->";
  }

  private static function parse_render_toc($material, $xml) {

    if ($material['toc'] === 'false' || $material['toc'] === false)
      return "";

    global $tocHTML; $tocHTML = "";
    global $counter; $counter = array(
        'section' => 1,
        'subsection' => 1,
        'assignment' => 1,
        'review' => 1,
        'midterm' => 1,
        'appendix' => 'A'
      );

    global $tagPath; $tagPath = '';

    if (!function_exists('parse_render_toc_lft')) {function parse_render_toc_lft($parser, $name, $attrs) {
      global $tocHTML;
      global $counter;
      global $tagPath;
      $tagPath .= '/'.$name;

      if ($tagPath == '/material')
        $tocHTML .= '<div class="toc"><ul>';
      if ($tagPath == '/material/section') {
        $id = $counter['section']; //$attrs['id'];
        $tocHTML .= ' <li>'.$counter['section'].'. <a href="#'.$id.'">'.$attrs['title']."</a>\n  <ul>";
      }
      if ($tagPath == '/material/review') {
        $id = 'R.'.$counter['review']; //$attrs['id'];
        $tocHTML .= ' <li><a href="#'.$id.'"><i>Review #'.$counter['review'].': '.$attrs['title']."</i></a>\n  <ul>";
      }
      if ($tagPath == '/material/midterm') {
        $id = 'M.'.$counter['midterm'];
        $tocHTML .= ' <li><a href="#'.$id.'"><b>Midterm: '.$attrs['title']."</b></a>\n  <ul>";
      }
      if ($tagPath == '/material/appendix') {
        $id = $counter['appendix']; //$attrs['id'];
        $tocHTML .= ' <li>Appendix '.$counter['appendix'].'. <a href="#'.$id.'">'.$attrs['title']."</a>\n  <ul>";
      }
      if ($tagPath == '/material/section/subsection') {
        $id = $counter['section'].'.'.$counter['subsection']; //$attrs['id'];
        $tocHTML .= 
            '  <li>'.$counter['section'].'.'.$counter['subsection'].'.'
          . ' <a href="#'.$id.'">'.$attrs['title'].'</a></li>';
      }
      if ($tagPath == '/material/appendix/subsection') {
        $id = $counter['appendix'].'.'.$counter['subsection']; //$attrs['id'];
        $tocHTML .= 
            '  <li>'.$counter['appendix'].'.'.$counter['subsection'].'.'
          . ' <a href="#'.$id.'">'.$attrs['title'].'</a></li>';
      }
      if ($tagPath == '/material/section/assignment') {
        $id = $counter['section'].'.'.$counter['subsection']; //$attrs['id'];
        $tocHTML .= 
            '  <li>'.$counter['section'].'.'.$counter['subsection'].'.'
          . ' <a href="#'.$id.'"><b>Assignment #'.$counter['assignment'].': '.$attrs['title'].'</b></a></li>';
      }
    }}
    if (!function_exists('parse_render_toc_val')) { function parse_render_toc_val($parser, $data) {
      // Nothing.
    }}
    if (!function_exists('parse_render_toc_rgt')) {function parse_render_toc_rgt($parser, $name) {
      global $tocHTML;
      global $counter;
      global $tagPath;
    
      if ($tagPath == '/material')
        $tocHTML .= '</ul></div>';
      if ($tagPath == '/material/section') {
        $tocHTML .= "\n  </ul>\n </li>";
        $counter['section']++;
        $counter['subsection'] = 1;
      }
      if ($tagPath == '/material/review') {
        $tocHTML .= "\n  </ul>\n </li>";
        $counter['review']++;
      }
      if ($tagPath == '/material/midterm') {
        $tocHTML .= "\n  </ul>\n </li>";
        $counter['midterm']++;
      }
      if ($tagPath == '/material/section/subsection') {
        $counter['subsection']++;
      }
      if ($tagPath == '/material/section/assignment') {
        $counter['subsection']++;
        $counter['assignment']++;
      }
      if ($tagPath == '/material/appendix') {
        $tocHTML .= "\n  </ul>\n </li>";
        $counter['appendix']++;
        $counter['subsection'] = 1;
      }
      if ($tagPath == '/material/appendix/subsection') {
        $counter['subsection']++;
      }

      $tagPath = substr($tagPath, 0, strlen($tagPath) - strlen($name) - 1);
    }}

    material::do_xml_parse("parse_render_toc_lft", "parse_render_toc_val", "parse_render_toc_rgt", $xml);
    return $tocHTML;
  }

  /////////////////////////////////////////////////////////////////////
  // XML Parsing and HTML rendering procedure for the document
  // contents.

  private static function parse_render($material, $xml, $tocHTML = "") {
    global $material;
    global $attributes; $attributes = array();
    global $hooks; $hooks = array();
    global $tocHTML;

    global $counter; $counter = array(
        'section' => 1,
        'subsection' => 1,
        'assignment' => 1,
        'review' => 1,
        'midterm' => 1,
        'appendix' => 'A'
      );
  
    global $tagPath; $tagPath = '';

    if (!function_exists('parse_render_lft')) {function parse_render_lft($parser, $name, $attrs) {
      global $material;
      global $attributes;
      global $hooks;
      global $counter;
      global $tagPath;
      global $tocHTML;
      $tagPath .= '/'.$name;
      $pathLeaf = material::pathLeaf($tagPath);

      // Update the hooks.
      array_push($hooks, (array_key_exists('hooks', $attrs)) ? $attrs['hooks'] : "");
      $attributes[] = $attrs;

      // Render the XML as HTML.
      if ($tagPath == '/material') {
        echo '<head>';
        echo "\n".'<meta charset="utf-8">';
        echo "\n".'<title>'.$attrs['title'].'</title>';
        echo "\n".'<link rel="stylesheet" href="'.$material['path'].'material.css">';
        echo "\n".'<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>';
        echo "\n".'<script type="text/javascript" src="'.$material['path'].'material.js"></script>';
        echo "\n".'</head>';
        echo "\n".'<body>';
        echo "\n".'<div class="material" id="material">';

        echo $material['message'];
        echo $tocHTML;
      }
      if ($tagPath == '/material/section' && (!array_key_exists('visible', $attrs) || $attrs['visible'] !== 'false')) {
        $id = $counter['section']; //$attrs['id'];
        echo "\n".'<a name="'.$id.'"></a>'."\n".'<div class="section"><hr style="margin-bottom:120px;"/>';
        echo '<h2 class="linked"><span class="link-title">'
           // . '[<a href="?id='.$id.'">page</a>]<br/>'
           . '[<a href="#'.$id.'">link</a>]&nbsp;&nbsp;'
           . '</span>'
           . '<span class="header_numeral">'.$counter['section'].'.</span> '.$attrs['title'].'</h2>';
      }
      if ($tagPath == '/material/review') {
        $id = 'R.'.$counter['review']; //$attrs['id'];
        echo '<a name="'.$id.'"></a><div class="review"><hr style="margin-bottom:120px;"/>';
        echo '<h2 class="linked"><span class="link-title">[<a href="#'.$id.'">link</a>]&nbsp;&nbsp;</span>'
           . '<span class="header_numeral">Review #'.$counter['review'].'.</span> '.$attrs['title'].'</h2>';
      }
      if ($tagPath == '/material/midterm') {
        $id = 'M.'.$counter['midterm']; //$attrs['id'];
        echo '<a name="'.$id.'"></a><div class="midterm"><hr style="margin-bottom:120px;"/>';
        echo '<h2 class="linked"><span class="link-title">[<a href="#'.$id.'">link</a>]&nbsp;&nbsp;</span>'
           . '<span class="header_numeral">Midterm.</span> '.$attrs['title'].'</h2>';
      }
      if ($tagPath == '/material/section/subsection' && (!array_key_exists('visible', $attrs) || $attrs['visible'] !== 'false')) {
        $id = $counter['section'].'.'.$counter['subsection']; //$attrs['id'];
        echo "\n  ".'<a name="'.$id.'"></a><div class="subsection">';
        echo '<h3 class="linked"><span class="link-title">[<a href="#'.$id.'">link</a>]&nbsp;&nbsp;</span>'
           . '<span class="header_numeral">'.$counter['section'].'.'.$counter['subsection'].'.</span> '
           . $attrs['title'].'</h3>';
      }
      if ($tagPath == '/material/section/assignment') {
        $id = $counter['section'].'.'.$counter['subsection']; //$attrs['id'];
        echo '<br/><hr/>'
           . '<a name="'.$id.'"></a>'
           . '<a name="assignment'.$counter['assignment'].'"></a>'
           . '<a name="hw'.$counter['assignment'].'"></a>'
           . '<div class="assignment">';
        echo '<h3 class="linked"><span class="link-title">[<a href="#'.$id.'">link</a>]&nbsp;&nbsp;</span>'
           . '<span class="header_numeral">'
           . $counter['section'].'.'.$counter['subsection'].'.</span> '
           . '<span class="assignment_title">Assignment #'.$counter['assignment'].': '.$attrs['title'].'</span></h3>';      
      }
      if ($pathLeaf === 'problems')  echo '<ol class="problems">';
      if ($pathLeaf === 'problem') echo '<li class="problem">';
      if ($pathLeaf === 'parts') echo '<ol class="parts">';
      if ($pathLeaf === 'part') echo '<li class="part">';

      if ($tagPath == '/material/appendix') {
        $id = $counter['appendix']; //$attrs['id'];
        echo '<a name="'.$id.'"></a><div class="appendix"><hr style="margin-bottom:120px;"/>';
        echo '<h2 class="linked"><span class="link-title">[<a href="#'.$id.'">link</a>]&nbsp;&nbsp;</span>'
           . '<span class="header_numeral">Appendix '.$counter['appendix'].'.</span> '.$attrs['title'].'</h2>';
      }
      if ($tagPath == '/material/appendix/subsection' && (!array_key_exists('visible', $attrs) || $attrs['visible'] !== 'false')) {
        $id = $counter['appendix'].'.'.$counter['subsection']; //$attrs['id'];
        echo '<a name="'.$id.'"></a><div class="subsection">';
        echo '<h3 class="linked"><span class="link-title">[<a href="#'.$id.'">link</a>]&nbsp;&nbsp;</span>'
           . '<span class="header_numeral">'.$counter['appendix'].'.'.$counter['subsection'].'.</span> '
           . $attrs['title'].'</h3>';      
      }

      // Categorized blocks.
      foreach (material::$blocks as $tag => $name) {
        if ( $tagPath == '/material/section/subsection/'.$tag
          || $tagPath == '/material/appendix/subsection/'.$tag
          || $tagPath == '/material/review/'.$tag ) {
          $id = array_key_exists('id', $attrs) ? $attrs['id'] : '';
          $link = array_key_exists('link', $attrs) ? $attrs['link'] : '#'.$id; 
          $classes = $tag.((array_key_exists('required', $attrs) && $attrs['required'] == 'true') ? '_required' : '');
          echo "\n".'<a name="'.$id.'"></a>'
             . '<div class="linked block" style="white-space:nowrap;">'
             . '<div style=" display:inline; vertical-align:middle;" class="link-block">[<a href="'.$link.'">link</a>]&nbsp;&nbsp;</div>'
             . '<div style=" width:100%; display:inline-block;">'
             . '<div style="width:auto;" class="'.$classes.'"><b>'.$name;
          if (array_key_exists('title', $attrs))
            echo ' ('.$attrs['title'].')';
          echo ':</b> ';
        }
      }

      // Assignment and exam instructions.
      if ($pathLeaf === "instructions") {
        echo '<div class="instructions">';
      }
    
      // Paragraphs with and without titles.
      if ($pathLeaf == "paragraph") {
        echo '<div class="paragraph">';
        if (array_key_exists('title', $attrs))
          echo '<b>'.$attrs['title'].'.</b> ';
      }

      // Ordered and unordered lists.
      if ($pathLeaf === "orderedlist") {
         echo '<ol'.((array_key_exists('style', $attrs)) ? ' style="'.$attrs['style'].'"' : '').'>';
      }
      if ($pathLeaf === "unorderedlist") echo '<ul>';
      if ($pathLeaf === "item") {
        echo '<li>';
        if (array_key_exists('title', $attrs))
          echo '<b>'.$attrs['title'].': </b>';
      }

      // Collections of inference rules.
      if ($pathLeaf === "inferences") {
        echo '<div class="inferences">';
      }
      if ($pathLeaf === "inference") {
        echo '<table class="inference"><tr>';
        if (array_key_exists('title', $attrs))
          echo '<td class="title">['.$attrs['title'].']</td>';
        echo '<td><table>';
      }
      if ($pathLeaf === "premises") {
        echo '<tr><td class="premises">&nbsp;';
      }
      if ($pathLeaf === "conclusion") {
        echo '<tr><td class="conclusion">&nbsp;';
      }
    
      // Solutions (in examples, exercises, and problems).
      if ($pathLeaf == "solution") echo "\n".'<div class="solution_container"><div class="solution">';

      // Source code and text blocks.
      if ($pathLeaf == "code") echo "\n".'<div class="code"><div class="source">'; //<pre>
      if ($pathLeaf == "text") echo "\n".'<span class="text">';
    }}
    if (!function_exists('parse_render_val')) { function parse_render_val($parser, $data) {
      global $hooks;
      global $counter;
      global $tagPath;
      $pathLeaf = material::pathLeaf($tagPath);

      // Render the XML as HTML.
      if ( $tagPath == '/material/section/subsection/definition'
        || $tagPath == '/material/appendix/subsection/definition'
        || $tagPath == '/material/section/subsection/fact'
        || $tagPath == '/material/appendix/subsection/fact'
        || $tagPath == '/material/section/subsection/theorem'
        || $tagPath == '/material/appendix/subsection/theorem'
        || $tagPath == '/material/section/subsection/conjecture'
        || $tagPath == '/material/appendix/subsection/conjecture'
        || $tagPath == '/material/section/subsection/algorithm'
        || $tagPath == '/material/appendix/subsection/algorithm'
        || $tagPath == '/material/section/subsection/protocol'
        || $tagPath == '/material/appendix/subsection/protocol'
        || $tagPath == '/material/section/subsection/example'
        || $tagPath == '/material/appendix/subsection/example'
        || $tagPath == '/material/section/subsection/exercise'
        || $tagPath == '/material/appendix/subsection/exercise'
        || $tagPath == '/material/review/exercise'
        || $tagPath == '/material/review/exercise'
        || $pathLeaf === 'paragraph'
        || $pathLeaf === 'solution'
        || $pathLeaf === 'code'
        || $pathLeaf === 'text'
        || $pathLeaf === 'instructions'
        || $pathLeaf === 'item'
        || $pathLeaf === 'premises'
        || $pathLeaf === 'conclusion'
         ) {
        // Apply the hooks.
        $out = $data;
        $applied = array();
        foreach ($hooks as $hooklist) {
          if (strlen($hooklist) > 0) {
            foreach (split(',', $hooklist) as $hook) {
              if (!in_array($hook, $applied)) {
                $out = call_user_func('material_hook_'.$hook, $out);
                $applied[] = $hook;
              }
            }
          }
        }
        if ($pathLeaf === 'text' || $pathLeaf === 'item')
          $out = trim($out);

        echo $out;
      }
    }}
    if (!function_exists('parse_render_rgt')) {function parse_render_rgt($parser, $name) {
      global $attributes;
      global $hooks;
      global $counter;
      global $tagPath;
      $pathLeaf = material::pathLeaf($tagPath);

      $attrs = array_pop($attributes);
    
      // Update the hooks.
      array_pop($hooks);

      // Render the XML as HTML.
      if ($tagPath == '/material')
        echo "\n".'</div></body>';
      if ($tagPath == '/material/section' && (!array_key_exists('visible', $attrs) || $attrs['visible'] !== 'false')) {
        echo "\n".'</div>';
        $counter['section']++;
        $counter['subsection'] = 1;
      }
      if ($tagPath == '/material/review') {
        echo "\n".'</div>';
        $counter['review']++;
      }
      if ($tagPath == '/material/midterm') {
        echo "\n".'</div>';
        $counter['midterm']++;
      }
      if ($tagPath == '/material/appendix') {
        echo '</div>';
        $counter['appendix']++;
        $counter['subsection'] = 1;
      }
      if ($tagPath == '/material/section/subsection' || $tagPath == '/material/appendix/subsection') {
        if (!array_key_exists('visible', $attrs) || $attrs['visible'] !== 'false') {
          echo '</div>';
          $counter['subsection']++;
        }
      }
      if ($tagPath == '/material/section/assignment') {
        echo "\n".'</div><hr/><br/>';
        $counter['subsection']++;
        $counter['assignment']++;
      }

      if ($pathLeaf === 'problems')  echo '</ol>';
      if ($pathLeaf === 'problem') echo '</li>';
      if ($pathLeaf === 'parts') echo '</ol>';
      if ($pathLeaf === 'part') echo '</li>';

      if ( $tagPath == '/material/section/subsection/definition'
        || $tagPath == '/material/appendix/subsection/definition'
        || $tagPath == '/material/section/subsection/fact'
        || $tagPath == '/material/appendix/subsection/fact'
        || $tagPath == '/material/section/subsection/theorem'
        || $tagPath == '/material/appendix/subsection/theorem'
        || $tagPath == '/material/section/subsection/conjecture'
        || $tagPath == '/material/appendix/subsection/conjecture'
        || $tagPath == '/material/section/subsection/algorithm'
        || $tagPath == '/material/appendix/subsection/algorithm'
        || $tagPath == '/material/section/subsection/protocol'
        || $tagPath == '/material/appendix/subsection/protocol'
        || $tagPath == '/material/section/subsection/example'
        || $tagPath == '/material/appendix/subsection/example'
        || $tagPath == '/material/section/subsection/exercise'
        || $tagPath == '/material/appendix/subsection/exercise'
        || $tagPath == '/material/review/exercise' ) {
        echo '</div></div></div>';     
      }

      if ( $pathLeaf === 'instructions' ) echo '</div>';

      if ( $pathLeaf === 'paragraph' ) echo '</div>';

      if ($pathLeaf == "orderedlist") echo '</ol>';
      if ($pathLeaf == "unorderedlist") echo '</ul>';
      if ($pathLeaf == "item") echo '</li>';

      if ($pathLeaf === "inferences") echo '</div>';
      if ($pathLeaf === "inference") echo '</table></td></tr></table>';
      if ($pathLeaf === "premises") echo '&nbsp;</td></tr>';
      if ($pathLeaf === "conclusion") echo '&nbsp;</td></tr>';

      if ($pathLeaf == "solution") echo '</div></div>';
      if ($pathLeaf == "text") echo '</span>';
      if ($pathLeaf == "code") echo '</div></div>'; //</pre>

      $tagPath = substr($tagPath, 0, strlen($tagPath) - strlen($name) - 1);
    }}

    material::do_xml_parse("parse_render_lft", "parse_render_val", "parse_render_rgt", $xml);
    return null;
  }

  ///////////////////////////////////////////////////////////////////
  // Functions for defining and invoking XML parsers.

  private static function mk_xml_parser($startF, $datF, $endF) {
    $xml_parser = xml_parser_create();
    xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, 0);
    xml_set_element_handler($xml_parser, $startF, $endF);
    xml_set_character_data_handler($xml_parser, $datF);
    return $xml_parser;
  }

  private static function do_xml_parse($startF, $datF, $endF, $xml) {
    $xml_parser = material::mk_xml_parser($startF, $datF, $endF);
    if (!xml_parse($xml_parser, $xml)) 
      die(
        sprintf(
          "XML error: %s at line %d", 
          xml_error_string(xml_get_error_code($xml_parser)),
          xml_get_current_line_number($xml_parser)
        )
      );
    xml_parser_free($xml_parser);
  }

  ///////////////////////////////////////////////////////////////////
  // Other utility functions.

  public static function pathLeaf($path) {
    $a = split("/", $path);
    return (count($a) < 1) ? null : $a[count($a)-1];
  }

  private static function endsWith($str, $suf) {
    return $suf === "" || substr($str, -strlen($suf)) === $str;
  }
}

/* eof */ ?>