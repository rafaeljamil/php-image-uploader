<?php
//Script que pega imagens do formulário e as transforma em jpeg com diminuição de tamanho

//visualiza o que foi passado pelo form
//echo "<pre>";
//print_r($_FILES);

//Controle de tipos de imagens aceitos
$mime = [
    'image/jpeg',
    'image/gif',
    'image/png'
];

//Loop foreach pra tratar cada imagem enviada
foreach($_FILES as $file){

    //Checa se o arquivo enviado pelo form tem o tipo aceito.
    //Caso não seja dos tipos permitidos, o continue faz mudar para o próximo arquivo
    if(!in_array($file['type'], $mime)) continue;

    //print_r(getimagesize($file['tmp_name'])); //auxílio visual para entender o formato dos dados
    $size = getimagesize($file['tmp_name']); //Pega o tamanho da imagem
    $ratio = $size[0]/$size[1]; // pega a razão entre a largura e a altura da imagem
    $dest_path = dirname(__FILE__) . "/images/" . $file['name'] . ".jpeg"; //Caminho de salvamento da imagem
    $max = 499; //Valor de referência para o resizing
    //echo $ratio . "<br>"; //apenas para visualizar o ratio

    if($ratio > 1){ // se a largura for maior que a altura divide esta pelo ratio
        $new_width = $max;
        $new_height = $max/$ratio;
    }else{ // caso contrário multiplica a largura pelo ratio
        $new_width = $max*$ratio;
        $new_height = $max;
    }

    //Auxílio visual para checar os valores de altura e largura
    //echo "Altura: " . $new_height . "<br>";
    //echo "Largura: " . $new_width . "<br>";

    //define qual é a imagem de origem
    $src = imagecreatefromstring(file_get_contents($file['tmp_name']));
    //define o tamanho da imagem de destino
    $dst = imagecreatetruecolor($new_width, $new_height);
    //copia os dados da imagem de origem na imagem de destino com o novo tamanho
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $new_width, $new_height, $size[0], $size[1]);
    //destroi a origem temporária
    imagedestroy($src);
    //cria a saída da imagem para o browser ou arquivo. Nesse caso vai pro arquivo
    imagejpeg($dst, $dest_path, 50);

    //As novas imagens ficaram com menos de 30kb usando a qualidade padrão (75 ou vazio)
}