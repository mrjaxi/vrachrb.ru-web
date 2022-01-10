<form id="test_form" action="" method="post">
  <?php
  echo $form['title'];
  ?>
  <i class="br10"></i>
  <input id="test" onclick="submitForm();return false;" type="submit" value="Отправить">
</form>


<script>
  var submitForm = function () {
    var s = $('#test_form').serialize();
    console.log(s);
    $.post('/test/?' + s, function (data) {
      console.log(data);
    });
  }
</script>