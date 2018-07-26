<?php 
  function safeVar( $matches ) {
    // print_r( $matches );
    $token = trim( strtolower( $matches[ 1 ] ) );
    $code  = trim( $matches[ 11 ] );

    if      ( $token == 'foreach' ) $safeCode = 'foreach(' . $code .') {';
    else if ( $token == 'for'     ) $safeCode = 'for(' . $code .') {';
    else if ( $token == 'print'   ) $safeCode = 'echo ' . $code .';';
    else if ( $token == 'if'      ) $safeCode = 'if(' . $code .') {';
    else if ( $token == 'elseif'  ) $safeCode = 'elseif(' .$code .') {';
    else    $safeCode = '';

    return '<?php ' 
      . $safeCode
      . ' ?>';
  }
  function safeLeaf( $matches ) {
    $token = trim( strtolower( $matches[ 1 ] ) );
    // if ( $token == 'foreach') $safeCode = ''
    if ( in_array( $token, [ 'foreach', 'for', 'if', 'elseif' ] ) ) {
      $safeCode = '<?php } ?>';
    }
    else $safeCode = $matches[ 0 ];
    return $safeCode;
  }
  function safeElseIf( $matches ) {
    return '<?php } elseif('.$matches[ 9 ].') { ?>';
  }
  // Read the template file.
  $dom = file_get_contents( './profile.elt.html' );
  // Prepare the else-if's.
  $exp = "/[<^](\s+)?\/(\s+)?if(\s+)?>(\s+)?<(\s+)?elseif(\s+)?=(\s+)?['|\"](\s+)?(.*)(\s+)?['|\"](\s+)?[>$]/Ui";
  $dom = preg_replace_callback( $exp, 'safeElseIf', $dom );
  // Prepare the keywords.
  $exp = "/[<^](forEach|for|if|print|elseIf)(\s+)?(\n+)?(\t+)?=(\s+)?(\n+)?(\t+)?['|\"](\s+)?(\n+)?(\t+)?(.+)['|\"$](\s+)?(\n+)?(\t+)?\/?(\s+)?(\n+)?(\t+)?[>$]/Ui";
  $dom = preg_replace_callback( $exp, 'safeVar', $dom );
  // Prepare the end blocks.
  $exp = "/[<^]\s*?\/\s*?(.*)\s*?[>$]/Ui";
  $dom = preg_replace_callback( $exp, 'safeLeaf', $dom );

  file_put_contents( './output/build.php', $dom );
  // echo $dom . "\n";

  echo 'Done!' . "\n";