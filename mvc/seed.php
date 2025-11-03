<?php
function envv($k,$d=null){
  if (file_exists(__DIR__.'/.env')) {
    foreach (file(__DIR__.'/.env', FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES) as $l){
      if ($l[0]==='#' || strpos($l,'=')===false) continue;
      [$n,$v]=explode('=',$l,2);
      if ($n===$k) return trim($v);
    }
  }
  return $d;
}
$dsn = sprintf("pgsql:host=%s;port=%s;dbname=%s;",
  envv('DB_HOST','127.0.0.1'),
  envv('DB_PORT','5432'),
  envv('DB_DATABASE','pabwe_mvc')
);
$pdo = new PDO($dsn, envv('DB_USERNAME','postgres'), envv('DB_PASSWORD',''),
  [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);

// users
$insU = $pdo->prepare("INSERT INTO users(username,password_hash,nama)
VALUES(:u,:ph,:n) ON CONFLICT (username) DO NOTHING");
foreach ([
  ['glen','password123','Glen Rejeki'],
  ['andi','secret456','Andi Pratama'],
] as $u){
  $insU->execute([':u'=>$u[0],':ph'=>password_hash($u[1],PASSWORD_DEFAULT),':n'=>$u[2]]);
}

// books
$insB = $pdo->prepare("INSERT INTO books(title,author,description) VALUES(:t,:a,:d)");
foreach ([
  ['Jungle Book','R. Kipling','A classic adventure.'],
  ['Moonwalker','J. Walker','Short essays about walking.'],
  ['PHP for Dummies','Some Smart Guy','Intro to PHP.'],
] as $b){
  $insB->execute([':t'=>$b[0],':a'=>$b[1],':d'=>$b[2]]);
}

echo "Seeding finished.\n";
