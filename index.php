<?php
date_default_timezone_set('America/Sao_Paulo');

$path = __DIR__;
$server = $_SERVER['HTTP_HOST'] ?? 'host não identificado';
$date = date('d/m/Y H:i:s');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site em construção</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #f4f6f8;
            color: #222;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .box {
            background: #ffffff;
            max-width: 620px;
            width: 90%;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
            text-align: center;
        }

        h1 {
            color: #1f5eff;
            margin-bottom: 10px;
        }

        p {
            font-size: 16px;
            line-height: 1.5;
        }

        .info {
            margin-top: 25px;
            padding: 15px;
            background: #f0f3f7;
            border-radius: 8px;
            font-size: 14px;
            text-align: left;
            color: #444;
        }

        .ok {
            margin-top: 20px;
            display: inline-block;
            padding: 10px 18px;
            background: #16a34a;
            color: #fff;
            border-radius: 6px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="box">
        <h1>Site em construção</h1>
        <p>Este ambiente está ativo e carregando corretamente pelo servidor.</p>
        <p>Em breve, o sistema estará disponível neste endereço.</p>

        <div class="ok">PHP funcionando corretamente</div>

        <div class="info">
            <strong>Domínio acessado:</strong> <?php echo htmlspecialchars($server); ?><br>
            <strong>Diretório carregado:</strong> <?php echo htmlspecialchars($path); ?><br>
            <strong>Data/hora do servidor:</strong> <?php echo htmlspecialchars($date); ?>
        </div>
    </div>
</body>
</html>
