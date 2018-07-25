<?php

  function safeVar( $matches ) {
    $safeVarString = htmlspecialchars_decode( $matches[ 1 ] );
    $safeVarString = str_replace( 'App->', 'App::', $safeVarString );
    return '<?php ' 
      . $safeVarString
      . ' ?>';
  }

  $tmpl = file_get_contents( './template.html' );

  $tmpl = htmlspecialchars( $tmpl );

  $tmpl = preg_replace_callback(
    '{{{((\s+)?([if|for|foreach])+(\s+)?(\()(\s+)(.+)[)$](\s+))}}}', 
    'safeVar', 
    $tmpl 
  );

  // exit();

/*  $tmpl = preg_replace(
    '{{{((\s+)?([if|for|foreach])+(\s+)?(\()(\s+)(.+)[)$](\s+))}}}', 
    '<?php ' . htmlspecialchars_decode( '$1' ) . ' xxxxs { ?>', 
    $tmpl 
  );
*/
  $tmpl = preg_replace(
    '{{{(\s+)?(endif|endforeach|endfor)(\s+)?}}}', 
    '<?php } ?>', 
    $tmpl 
  );

  $tmpl = preg_replace(
    '{{{(\s+)?(\$(.+))(\s+)?}}}', 
    '<?php echo $2 ?>', 
    $tmpl 
  );

  echo $tmpl;

  echo "\n";