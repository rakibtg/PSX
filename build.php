<?php 
  require_once './Builder.php';
  $builder = new Builder();

  function safeVar( $matches ) {
    // print_r( $matches );
    $token = trim( strtolower( $matches[ 1 ] ) );
    $code  = trim( $matches[ 2 ] );

    // echo $token;
    // exit();

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
  function safeClosingTags( $matches ) {
    $token = trim( strtolower( $matches[ 1 ] ) );
    // if ( $token == 'foreach') $safeCode = ''
    if ( in_array( $token, [ 'foreach', 'for', 'if', 'elseif', 'else' ] ) ) {
      $safeCode = '<?php } ?>';
    }
    else $safeCode = $matches[ 0 ];
    return $safeCode;
  }
  function safeIfElse( $matches ) {
    return '<?php } else { ?>';
  }
  function safeElseIf( $matches ) {
    return '<?php } else if ( '.$matches[1].' ) { ?>';
  }
  function safePrint( $matches ) {
    return '<?php echo '.$matches[ 1 ].'; ?>';
  }
  function safeImport( $matches ) {
    return '<?php require_once "'.$matches[1].'.elt.php"; ?>';
  }

  // Read the template file.
  $dom = file_get_contents( './profile.elt.html' );

  // if/elseif ... else
  $exp = $builder ->ClosingTags( '(if|elseif)' )
                  ->WhiteSpace()
                  ->Tags( 'else' )
                  ->WhiteSpace()
                  ->Make();
  $dom = preg_replace_callback( $exp, 'safeIfElse', $dom );

  // if ... else if
  $exp = $builder ->ClosingTags( 'if' )
                  ->WhiteSpace()
                  ->Tags( 'elseif', 'src' )
                  ->WhiteSpace()
                  ->Make();
  $dom = preg_replace_callback( $exp, 'safeElseIf', $dom );

  // print
  $exp = $builder ->Tags( 'print', 'src', true )->Make();
  $dom = preg_replace_callback( $exp, 'safePrint', $dom );

  // if
  $exp = $builder ->Tags( '(if|elseif|for|foreach)', 'src' )->Make();
  $dom = preg_replace_callback( $exp, 'safeVar', $dom );

  // import
  $exp = $builder ->Tags( 'import', 'src', true )->Make();
  $dom = preg_replace_callback( $exp, 'safeImport', $dom );

  // closing tags
  // $exp = "/[<^]\s*?\/\s*?(.*)\s*?[>$]/Ui";
  $exp = $builder ->ClosingTags( '(if|elseif|for|foreach|else)' )->Make();
  $dom = preg_replace_callback( $exp, 'safeClosingTags', $dom );

  // file_put_contents( './output/build.php', $dom );
  echo $dom . "\n";

  echo "\n-------------------------------------------\nDone!" . "\n";