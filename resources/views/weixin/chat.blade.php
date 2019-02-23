{{--群发消息--}}
<!doctype html>
<html lang="zh">
<title>群发</title>
<h1  align="center">群发</h1>
<form action="/admin/group" method="post" enctype="multipart/form-data">
    {{csrf_field()}}
    text : <input type="text" name="group" >
    <input type="submit" value="SUBMIT">
</form>
</html>