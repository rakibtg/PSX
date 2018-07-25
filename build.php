<?php 

  system('clear');
  // [<^](forEach|for|if|print)(\s+)?(\n+)?(\t+)?=(\s+)?(\n+)?(\t+)?['|"](\s+)?(\n+)?(\t+)?(.+)['|"$](\s+)?(\n+)?(\t+)?\/?(\s+)?(\n+)?(\t+)?[>$]
  $dom = file_get_contents( './profile.elt.html' );

  echo $dom;

  echo "\n";