<?php
  function callbackForEcah( $matches ) {
    $src = $matches[ 1 ];
    $as  = $matches[ 2 ];
    return '<?php foreach( $'.$src.' as $'.$as.' ) { ?>';
  }
  function handleForEach( $template ) {
    $exp      = "/[<^]foreach[\s]+src\s*=['|\"](.*)['|\"][\s]+as\s*=['|\"](.*)['|\"]\s*[>$]/Ui";
    return preg_replace_callback( $exp, 'callbackForEcah', $template );
  }

  function callbackPrint( $matches ) {
    $variable = $matches[1];
    return '<?php echo $'.$variable.'; ?>';
  }
  function handlePrint( $template ) {
    $exp = "/[<^]\s*print\s+src\s*=\s*['|\"](.*)['|\"]\s*\/\s*[>$]/Ui";
    $template = preg_replace_callback( $exp, 'callbackPrint', $template );
    return $template;
  }



  // Read the template file.
  $template = file_get_contents( './oreo/profile.elt.html' );

  // Handle "foreach"
  $template = handleForEach( $template );

  // Handle "print"
  $template = handlePrint( $template );

  echo $template;