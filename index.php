<?php

  $tmpl = file_get_contents( './template.html' );

  $tmpl = preg_replace(
    '{{{((\s+)?([if|for|foreach])+(\s+)?(\()(\s+)(.+)[)$](\s+))}}}', 
    '<?php $1 { ?>', 
    $tmpl 
  );

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