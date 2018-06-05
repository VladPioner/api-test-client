<?php
function debug($arr){echo "<pre>".print_r($arr,true)."</pre>";}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(isset($_POST['sbm'])){
        $params = [];
        for($i=0;$i<count($_POST['name']);$i++){
            if($_POST['name'][$i]){
                $params[$_POST['name'][$i]] = $_POST['val'][$i];
            }
        }

        $host = trim(trim(trim($_POST['host']),'/'),'\\');
        $host = str_replace('http://','',$host);
        $host = str_replace('https://','',$host);
        $host = 'http://'.$host;

        $data = json_encode($params);

        $curl = curl_init();
        curl_setopt($curl,CURLOPT_URL,$host);
        curl_setopt($curl,CURLOPT_POST,1);
        curl_setopt($curl,CURLOPT_POSTFIELDS,$data);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($curl,CURLOPT_HEADER,1);
        //curl_setopt($curl,CURLOPT_FOLLOWLOCATION,true);

        $response = curl_exec($curl);
        curl_close($curl);

        $response = explode("\r\n\r\n",$response);

        list($headers,$body) = $response;
        $arr_headers = explode(PHP_EOL,$headers);
        $arr_body = json_decode($body);

    }
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        #content{
            width:400px;
            margin: 10px auto;
        }
        .half{
            width: 45%;
            float: left;
        }
        input, button{
            width: 100%;
            margin-bottom: 10px;
        }
        .delim{
            width: 10%;
            float: left;
        }
        #add_param{
            text-align: center;
            border: 1px solid gainsboro;
            margin-bottom: 10px;
            cursor: pointer;
            background: #EFD8C5;
        }
        #result{
            border-collapse: collapse;
            border: 1px solid black;
            width: 100%;
        }
        #result td, #result th{
            border: 1px solid black;
        }
    </style>
</head>
<body>
<div id="content">
    <form action="" method="post">
        <input type="text" name="host" placeholder="адрес">
        <div id="params">
            <div class="half">
                <input type="text" name="name[]" placeholder="field1">
            </div>
            <div class="delim">&nbsp;&nbsp;&nbsp;&nbsp;=&nbsp;</div>
            <div class="half">
                <input type="text" name="val[]" placeholder="value1">
            </div>
        </div>
        <div style="clear: both;"></div>
        <div id="add_param">Добавить пареметр</div>
        <input type="submit" name="sbm">
    </form>
</div>

<div>
    <?php if($arr_headers[0]) : ?>
    <h2>Статус ответа: <?=$arr_headers[0]?></h2>
    <?php endif; ?>

    <?php if($arr_body) : ?>
    <table id="result">
        <?php if(is_array($arr_body)) : ?>
            <tr>
                <?php foreach (current($arr_body) as $key=>$val) : ?>
                <th><?=$key?></th>
                <?php endforeach; ?>
            </tr>
            <?php foreach ($arr_body as $field) : ?>
            <tr>
                <?php foreach ($field as $ceil) : ?>
                    <td><?=$ceil?></td>
                <?php endforeach; ?>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <?php foreach ($arr_body as $key=>$val) : ?>
                    <th><?=$key?></th>
                <?php endforeach; ?>
            </tr>
            <tr>
                <?php foreach ($arr_body as $val) : ?>
                    <td><?=$val?></td>
                <?php endforeach; ?>
            </tr>
        <?php endif ; ?>
    </table>
    <?php endif; ?>
    
</div>

<div>
    <p>Для примера можно послать запросы на следующие сервера:</p>
    <p>-<b> api-test.zzz.com.ua</b> Этот сервер выполнен на чистом PHP</p>
    <p>- <b>api-yii-test.zzz.com.ua</b> Этот сервер выполнен на фреймворке Yii2</p>
    <p>На этих серверах есть по две таблицы <b>product</b> и <b>category</b></p>
    <p>У них есть следующие поля</p>
    <p>Category:  <i>id, parent_id, name, get_all</i></p>
    <p>Product: <i>id, category_id, name	price, keywords, description, img, get_all, new, sale</i></p>
    <p>В данном примере, для удобства, в таблицах можно посмотреть все записи.</p>
    <p>Пример:</p>
    <p>Адрес <b>api-test.zzz.com.ua/product</b></p>
    <p>field1 – <b>get_all</b></p>
    <p>value1 - <b>1</b></p>

</div>

<script>

    var i = 2;

    document.getElementById('add_param').addEventListener('click',function(){
        var div_par1 = document.createElement('div');
        div_par1.setAttribute('class', 'half');

        var inp_par = document.createElement('input');
        inp_par.setAttribute('type', 'text');
        inp_par.setAttribute('name', 'name[]');
        inp_par.setAttribute('placeholder', 'field'+i);

        div_par1.appendChild(inp_par);


        var div_par2 = document.createElement('div');
        div_par2.setAttribute('class', 'half');

        var inp_val = document.createElement('input');
        inp_val.setAttribute('type', 'text');
        inp_val.setAttribute('name', 'val[]');
        inp_val.setAttribute('placeholder', 'value'+i);

        div_par2.appendChild(inp_val);


        var div_delim = document.createElement('div');
        div_delim.setAttribute('class', 'delim');
        div_delim.innerHTML = '&nbsp;&nbsp;&nbsp;&nbsp;=&nbsp;';

        var frag = document.createDocumentFragment();

        frag.appendChild(div_par1);
        frag.appendChild(div_delim);
        frag.appendChild(div_par2);

        var div_params = document.getElementById('params');
        div_params.appendChild(frag);

        i++;
    },false);



</script>
</body>
</html>
