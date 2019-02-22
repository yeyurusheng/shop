{{--素材上传--}}
        <!doctype html>
<html lang="zh">
<title>素材上传</title>
<h1  align="center">素材上传</h1>
<form action="/weixin/test" method="post" enctype="multipart/form-data">
    {{csrf_field()}}
    name : <input type="text" name="material" >        <br><br><br>
    file : <input type="file" name="media" >        <br><br><br>
    <input type="submit" value="SUBMIT">    <br><br><br>
</form>
</html>
