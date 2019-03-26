<?php
require __DIR__.'/vendor/autoload.php';
use \App\Common\Log;
use \App\Nodb\Json;
use \App\Moysklad\Products;
use \App\Moysklad\ProductsMetadata;

if(!isset($_SESSION['user']) && !isset($_SESSION["password"])){
    if(isset($_POST["user"]) && isset($_POST["password"])){
        $_SESSION['user'] = $_POST["user"];
        $_SESSION['password'] = $_POST["password"];
        putenv('MOYSKLAD_USER='.$_POST["user"]);
        putenv('MOYSKLAD_PASSWORD='.$_POST["password"]);
    }else  header('Location: login.php');
}

$certificates = new Json();
?>
<!doctype html>
<html>
<head>
    <title>Мойсклад утилиты</title>
    <link rel="stylesheet" type="text/css" href="semantic/dist/semantic.min.css">
    <script
      src="https://code.jquery.com/jquery-3.1.1.min.js"
      integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
      crossorigin="anonymous"></script>
    <script src="semantic/dist/semantic.min.js"></script>
    <script>
        var ajax = {
            response:(r)=>{
                if(!r.success) {
                    alert(`Ошибка выполнения ${r.data.error}`);
                    return;
                }
                if(r.reload) document.location.reload();
                if(r.request=='random'){
                    const data = r.data
                    const table = data.map( (d,i) => {
                        return `<div class="ui item">
                            <div class="content">
                                <div class="header">
                                    <code>${i+1}.</code>
                                    &nbsp;<a target="_blank" href="${d.product.meta.uuidHref}">${d.product.name}</a>
                                </div>
                                <div class="description">${d.certificate}</div>
                            </div>
                        </div>`;
                    }).join('');
                    // console.log('random response',table,data);
                    $(`<div class="ui modal">
                        <div class="header">Выполено <code>${data.length}</code></div>
                        <div class="content">
                            <div class="ui items">
                                ${table}
                            </div>
                        </div>
                    </div>`).appendTo('body').modal('show');
                }
            },
            random:(that)=>{
                const $that = $(that)
                $that.addClass('spinner loading').removeClass('random').prop('disabled',true);
                $.ajax({
                    url:`ajax.php?type=random`,
                    dataType:"json",
                    complete:(x,d,s)=>{
                        $that.removeClass('spinner loading').addClass('random').prop('disabled',false);
                        try{
                            ajax.response(JSON.parse(x.responseText));
                        }
                        catch(e){
                            console.warn(e);
                        }

                    }
                });
            },
            add:(v)=>{
                const value = $(v).val();
                $.ajax({
                    url:`ajax.php?type=certificate.add&value=${value}`,
                    dataType:"json",
                    complete:(x,d,s)=>{
                        try{
                            ajax.response(JSON.parse(x.responseText));
                        }
                        catch(e){
                            console.warn(e);
                        }

                    }
                });
            },
            delete:(v)=>{
                $.ajax({
                    url:`ajax.php?type=certificate.delete&value=${v}`,
                    dataType:"json",
                    complete:(x,d,s)=>{
                        try{
                            ajax.response(JSON.parse(x.responseText));
                        }
                        catch(e){
                            console.warn(e);
                        }

                    }
                });
            }
        };
    </script>
</head>
<body>
    <div class="ui container">

            <div class="ui left floated header">
                МОЙ<span class="ui blue color">СКЛАД</span>
            </div>
            <button class="ui icon green button right floated" onclick="ajax.random(this)">
                <i class="ui random icon"></i>Установить рандомно
            </button>

        <br />
        <br />
        <div class="ui dividing header">Список сертификатов</div>
        <div class="ui divided relaxed items">
            <?php
                foreach($certificates->get() as $certificate){
                    echo "<div class=\"ui item\">
                            <div class=\"content\">
                                <div class=\"header\"><code>#{$certificate->id}</code> {$certificate->value}</div>
                                <div class=\"meta\">
                                    <a class=\"cinema\" href=\"javascript:ajax.delete('{$certificate->id}')\"><i class=\"ui close icon\"></i>Удалить</a>
                                </div>
                                <div class=\"description\">
                                <p></p>
                            </div>
                            <!-- <div class=\"extra\">
                                <div class=\"ui right floated primary button\">
                                    Buy tickets
                                    <i class=\"right chevron icon\"></i>
                                </div>
                            </div> -->
                        </div>
                    </div>";
                }
            ?>
        </div>
        <div class="ui segment">

            <div class="ui form">
                <div class="ui fields">
                    <div class="field eight wide">
                        <label>Значение</label>
                        <input class="ui input" id="add_value"/>
                    </div>
                    <div class="field">
                        <label>&nbsp;</label>
                        <button class="ui icon button" onclick="ajax.add('#add_value')">
                            <i class="ui plus icon"></i>Добавить
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</body>
</html>
