<style type="text/css">
    #draggable {font-size: x-large; border: thin solid black;
        width: 5em; text-align: center; padding:10px}
</style>
<script type="text/javascript">
    $(function() {

        $('#draggable').draggable();

    });
</script>
<?

showMiddle()
?>
<div class="container">
    <div class="row">
    <h1>Заказ зеркал</h1>
    <div class="panel"><h4>Цена: 2857 руб. <a href="">Отправить заказ в производство</a></h4>        <p>Нарисуйте нужную вам форму зеркала, выберите параметры и мы начнем его производство</p></div>


    </div>
    <div class="row">
<span class="col-md-2"><div class="panel panel-default">
  <div class="panel-body">
      <h4>Выберите инструмент:</h4>
      <button type="button" class="btn btn-default" druggable="true" aria-label="Left Align">
  <span class="glyphicon glyphicon-font" aria-hidden="true" druggable="true">A</span>
</button><button type="button" class="btn btn-default" aria-label="Left Align">
  <span class="glyphicon glyphicon-font" aria-hidden="true"></span>
</button><button type="button" class="btn btn-default" aria-label="Left Align">
  <span class="glyphicon glyphicon-font" aria-hidden="true"></span>
</button><button type="button" class="btn btn-default" aria-label="Left Align">
  <span class="glyphicon glyphicon-font" aria-hidden="true"></span>
</button>


  </div>
</div></span>
<div class="col-md-6">
    <div class="panel panel-default">
  <div class="panel-body">
      <p>Графический редактор</p>
      <div class="input-group">
          <span class="input-group-addon" id="basic-addon1">Ширина</span>
          <input type="text" class="form-control" placeholder="1000 мм" aria-describedby="basic-addon1">
      </div>

      <div class="input-group">
          <span class="input-group-addon" id="basic-addon2">Высота&nbsp;</span>
          <input type="text" class="form-control" placeholder="1000 мм" aria-describedby="basic-addon2">
      </div>

      <img src="" height="600" width="520" class="droppable" />

  </div>
</div>
</div>
    <div class="col-md-2">

        <div class="panel panel-default">
            <div class="panel-body">
                <h2>Опции</h2>
                <h3>Обработка:</h3>
                <form>
                    <div class="radio">
                        <label><input name="styp" type="radio" />Шлифовка</label>
                    </div>
                    <div class="radio">
                        <label><input name="styp" type="radio" />Полировка</label>
                    </div>
                    <div class="radio">
                        <label><input name="styp" type="radio" />Фацет</label><select><option>15 мм</option></select>
                    </div>
                </form>
            </div>
        </div>
    </div>


    </div>

</div>
</body>
</html>