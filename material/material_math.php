<?php /*********************************************************
**
** material
** 
** material_math.php
**   Hook for simple markdown mathematics notation.
*/

////////////////////////////////////////////////////////////////
//

if (!function_exists('tr')) {
  function tr($t,$x,$y) {
    return str_replace($x,$y,$t);
  }
}

function material_hook_math ($s) {

  $s=tr($s, '#[', '</td><td><table cellpadding="0" cellspacing="0" style="display:inline;"><tr><td class="html_frac_lft">&nbsp;</td><td><table cellpadding="0" cellspacing="0" style="font-size:12px;"><tr><td style="white-space:nowrap;">');
  $s=tr($s, '#;', '</td></tr><tr><td style="white-space:nowrap;">');
  $s=tr($s, '#,', '</td><td style="padding-left:8px; white-space:nowrap;">');
  $s=tr($s, '#]', '</td></tr></table></td><td class="html_frac_rgt">&nbsp;</td></tr></table></td><td>');

  $s=tr($s, '@(', '</td><td><table cellpadding="0" cellspacing="0" style="display:inline;"><tr><td class="html_frac_lft">&nbsp;</td><td><table cellpadding="0" cellspacing="0" style="font-size:12px;"><tr><td style="white-space:nowrap;">');
  $s=tr($s, '@;', '</td></tr><tr><td style="border-top:1px solid #000000; white-space:nowrap;">');
  $s=tr($s, '@)', '</td></tr></table></td><td class="html_frac_rgt">&nbsp;</td></tr></table></td><td>');

  $s=tr($s, '#(', '<span style="font-size:24px;">(</span></td><td><table cellpadding="0" cellspacing="0" style="display:inline;"><tr><td class="html_frac_lft">&nbsp;</td><td><table cellpadding="0" cellspacing="0" style="font-size:12px;"><tr><td style="white-space:nowrap;">');
  $s=tr($s, '#;', '</td></tr><tr><td style="white-space:nowrap;">');
  $s=tr($s, '#)', '</td></tr></table></td><td class="html_frac_rgt">&nbsp;</td></tr></table></td><td><span style="font-size:24px;">)</span>');

  
  $s=tr($s, '%{^', '<span style="text-decoration:overline;">');
  $s=tr($s, '}%', '</span>');

  $s=tr($s, '^{\\phi(%m)-1}', '<sup>\\phi(%m)-1</sup>');
  $s=tr($s, '^{\\phi(%m)}', '<sup>\\phi(%m)</sup>');
  $s=tr($s, '^{\\phi(%m) \cdot %k}', '<sup>\\phi(%m) \cdot %k</sup>');
  $s=tr($s, '^{%k}', '<sup><i>k</i></sup>');
  $s=tr($s, '^{%d}', '<sup><i>d</i></sup>');
  $s=tr($s, '^{%i}', '<sup><i>i</i></sup>');
  $s=tr($s, '^{%a \cdot %b}', '<sup>%a \cdot %b</sup>');
  $s=tr($s, '^{%a}', '<sup><i>a</i></sup>');
  $s=tr($s, '^{%b}', '<sup><i>b</i></sup>');
  $s=tr($s, '^{%d}', '<sup><i>d</i></sup>');
  $s=tr($s, '^{%e}', '<sup><i>e</i></sup>');
  $s=tr($s, '^{%n}', '<sup><i>n</i></sup>');
  $s=tr($s, '^{%m}', '<sup><i>m</i></sup>');
  $s=tr($s, '^{%c}', '<sup><i>c</i></sup>');
  $s=tr($s, '^{%y}', '<sup><i>y</i></sup>');
  $s=tr($s, '^{1/2}', '<sup>1/2</sup>');
  $s=tr($s, '^{%n/2}', '<sup>%n/2</sup>');
  $s=tr($s, '^{%p-1}', '<sup>%p-1</sup>');
  $s=tr($s, '^{%p-2}', '<sup>%p-2</sup>');
  $s=tr($s, '^{%n-1}', '<sup>%n-1</sup>');
  $s=tr($s, '^{%m-1}', '<sup>%m-1</sup>');
  $s=tr($s, '^{%k-1}', '<sup>%k-1</sup>');
  $s=tr($s, '^{%d+1}', '<sup><i>d</i>+1</sup>');
  $s=tr($s, '^{%k+1}', '<sup><i>k</i>+1</sup>');
  $s=tr($s, '^{%z}', '<sup><i>z</i></sup>');
  $s=tr($s, '^{%z_2}', '<sup><i>z</i><sub>2</sub></sup>');
  $s=tr($s, '_0', '<sub>0</sub>');
  $s=tr($s, '_1', '<sub>1</sub>');
  $s=tr($s, '_2', '<sub>2</sub>');
  $s=tr($s, '_3', '<sub>3</sub>');
  $s=tr($s, '_4', '<sub>4</sub>');
  $s=tr($s, '_5', '<sub>5</sub>');
  $s=tr($s, '_6', '<sub>6</sub>');
  $s=tr($s, '_7', '<sub>7</sub>');
  $s=tr($s, '_8', '<sub>8</sub>');
  $s=tr($s, '_{10}', '<sub>10</sub>');
  $s=tr($s, '^0', '<sup>0</sup>');
  $s=tr($s, '^1', '<sup>1</sup>');
  $s=tr($s, '^2', '<sup>2</sup>');
  $s=tr($s, '^3', '<sup>3</sup>');
  $s=tr($s, '^4', '<sup>4</sup>');
  $s=tr($s, '^5', '<sup>5</sup>');
  $s=tr($s, '^6', '<sup>6</sup>');
  $s=tr($s, '^7', '<sup>7</sup>');
  $s=tr($s, '^8', '<sup>8</sup>');
  $s=tr($s, '^9', '<sup>9</sup>');
  $s=tr($s, '^{-1}', '<sup>-1</sup>');
  $s=tr($s, '^{11}', '<sup>11</sup>');
  $s=tr($s, '^{16}', '<sup>16</sup>');
  $s=tr($s, '^{8*32}', '<sup>8 \cdot 32</sup>');
  $s=tr($s, '^{256}', '<sup>256</sup>');
  $s=tr($s, '^{21}', '<sup>21</sup>');
  $s=tr($s, '_{32}', '<sub>32</sub>');
  $s=tr($s, '_{50}', '<sub>50</sub>');
  $s=tr($s, '_{51}', '<sub>51</sub>');
  $s=tr($s, '_{100}', '<sub>100</sub>');
  $s=tr($s, '_{%i}', '<sub><i>i</i></sub>');
  $s=tr($s, '_{%m}', '<sub><i>m</i></sub>');
  $s=tr($s, '_{%n}', '<sub><i>n</i></sub>');
  $s=tr($s, '_{%N}', '<sub><i>N</i></sub>');
  $s=tr($s, '_{%i-1}', '<sub><i>i</i>-1</sub>');
  $s=tr($s, '_{%n-1}', '<sub><i>n</i>-1</sub>');
  $s=tr($s, '_{%k-1}', '<sub><i>k</i>-1</sub>');
  $s=tr($s, '_{%k}', '<sub><i>k</i></sub>');
  $s=tr($s, '_{%g}', '<sub><i>g</i></sub>');
  $s=tr($s, '_{%j}', '<sub><i>j</i></sub>');
  $s=tr($s, '\\lfloor', '&lfloor;');
  $s=tr($s, '\\rfloor', '&rfloor;');
  $s=tr($s, '\\emptyset', '&empty;');
  $s=tr($s, '\\forall', '&forall;');
  $s=tr($s, '\\exists', '&exist;');
  $s=tr($s, '\\gcd', 'gcd');
  $s=tr($s, '\\max', 'max');
  $s=tr($s, '\\min', 'min');
  $s=tr($s, '\\dom', 'dom');
  $s=tr($s, '\\log', 'log');
  $s=tr($s, '\\ln', 'ln');
  $s=tr($s, '%-', '&#8722;');
  $s=tr($s, '\\sqrt', '&radic;');
  $s=tr($s, '\\phi', '&phi;');
  $s=tr($s, '\\varphi', '&phi;');
  $s=tr($s, '\\lambda', '&lambda;');
  $s=tr($s, '\\tau', '&tau;');
  $s=tr($s, '\\pm', '&plusmn;');
  $s=tr($s, '\\Gamma', '&Gamma;');
  $s=tr($s, '\\vdash', '&#8866;');
  $s=tr($s, '\\langle', '&lang;');
  $s=tr($s, '\\rangle', '&rang;');
  $s=tr($s, '\\0', '<b>0</b>');
  $s=tr($s, '\\1', '<b>1</b>');
  $s=tr($s, '\\top', '&#8868;');
  $s=tr($s, '\\bot', '&perp;');
  $s=tr($s, '%[', '<i style="text-decoration:underline;">');
  $s=tr($s, ']%', '</i>');

  $ops = array(
      '=' => '=',
      '&gt;' => '&gt;',
      '&lt;' => '&lt;',
      '\\gt' => '&gt;',
      '\\lt' => '&lt;',
      ':=' => ':=',
      '::=' => '::=',
      'iff' => 'iff',
      '\\models' => '&#8872;', //&#8871;
      '\\rightarrow' => '<span style="font-size:12px;">&#8594;</span>',
      '\\leftarrow' => '<span style="font-size:12px;">&#8592;</span>',
      '\\downarrow' => '<span style="font-size:12px;">&#8595;</span>',
      '\\uparrow' => '<span style="font-size:12px;">&#8593;</span>',
      '\\Leftrightarrow' => '<span style="font-size:16px;">&#8660;</span>',
      '\\Rightarrow' => '<span style="font-size:16px;">&rArr;</span>',
      '|' => '|',
      '\\nmid' => '&#8740;',
      '\\vdots' => '&#8942;',
      '\\leq' => '&le;',
      '\\geq' => '&ge;',
      '\\neq' => '&ne;',
      '\\not\\in' => '&notin;',
      '\\in' => '<span style="font-size:12px;">&#8712;</span>',
      '\\subset' => '&sub;',
      '\\cup' => '&cup;',
      '\\cap' => '&cap;',
      '\\times' => '&#215',
      '\\Downarrow' => '&dArr;',
      '\\Sigma' => '&Sigma;',
      '\\sigma' => '&sigma;',
      '\\uplus' => '&#8846;',
      '\\oplus' => '&oplus;',
      '\\otimes' => '&otimes;',
      '\\mapsto' => '&#x21A6;',
      '\\neg' => '&not;',
      '\\wedge' => '&and;',
      '\\vee' => '&or;',
      '\\mod' => 'mod',
      '\\log' => 'log',
      '\\cdot' => '&sdot;',
      '\\not\\equiv' => '&#8802;',
      '\\equiv' => '&equiv;',
      '\\cong' => '&cong;',
      '\\approx' => '&approx;',
      '\\sim' => '&sim;',
      '\\varepsilon' => '&epsilon;',
      '\\circ' => '<span style="font-size:10px;">o</span>',
      '%~' => '&nbsp;&nbsp;&nbsp;&nbsp;'
    );

  foreach (str_split('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') as $v)
    $s = str_replace('%'.$v, '<i>'.$v.'</i>', $s);
  foreach (str_split('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789') as $v)
    $s = str_replace('@'.$v, '<b>'.$v.'</b>', $s);

  $s = str_replace("\\begin{eqnarray}\n","\\begin{eqnarray}",$s);
  $s = str_replace('\\begin{eqnarray}', '<table style="padding-left:20px; margin:4px 0px 4px 0px;"><tr><td style="text-align:right; white-space:nowrap;"><table style="width:100%;"><tr><td style="text-align:right;">',$s);
  foreach ($ops as $rel => $relH)
    $s = str_replace('& '.$rel.' &', '<td></tr></table></td><td style="text-align:center;"> '.$relH.' </td><td><table style="white-space:nowrap;"><tr><td style="white-space:nowrap;">',$s);
  $s = str_replace('\\\\', '</td></tr></table></td></tr><tr><td style="text-align:right;"><table style="width:100%;"><tr><td style="text-align:right;">',$s);
  $s = str_replace('%%', '</td></tr></table></td></tr><tr><td style="text-align:right;"><table style="width:100%;"><tr><td style="text-align:right;">',$s);
  $s = str_replace('\\end{eqnarray}', '</td></tr></table></td></tr></table>',$s);

  foreach ($ops as $str => $html)
    $s = str_replace($str, $html, $s);

  $s=tr($s, '\\Z', '&#8484;');
  $s=tr($s, '\\N', '&#8469;');
  $s=tr($s, '\\R', '&#8477;');
  $s=tr($s, '\\U', '<b><i>U</i></b>');
  $s=tr($s, '\\D', '<b><i>D</i></b>');
  $s=tr($s, '\\powerset', '&weierp;');
  $s=tr($s, '\\Pr', 'Pr');

  return $s;
}

/*eof*/?>