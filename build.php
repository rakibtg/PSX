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
  // system('clear');

  $exp = "/[<^](forEach|for|if|print|elseIf)(\s+)?(\n+)?(\t+)?=(\s+)?(\n+)?(\t+)?['|\"](\s+)?(\n+)?(\t+)?(.+)['|\"$](\s+)?(\n+)?(\t+)?\/?(\s+)?(\n+)?(\t+)?[>$]/U";


  $dom = file_get_contents( './profile.elt.html' );
  $dom = preg_replace_callback( $exp, 'safeVar', $dom );

  $exp = "/[<^]\s*?\/\s*?(.*)\s*?[>$]/U";
  $dom = preg_replace_callback( $exp, 'safeLeaf', $dom );

  file_put_contents( './output/build.php', $dom );

  echo 'Done!' . "\n";