<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Userlist</title>
</head>
<body>
    <table border="1" cellpadding="7">
        <tr>
            <th>Username</th>
            <th>Имя</th>
            <th>Возраст</th>
            <th>Описание</th>
            <th>Статус</th>
        </tr>
        <?php foreach ($data as $row):?>
        <tr>
            <td><?php echo $row['username']?></td>
            <td><?php echo $row['name']?></td>
            <td><?php echo $row['age']?></td>
            <td><?php echo $row['about']?></td>
            <td><?php echo $row['adult']?></td>
        </tr>
        <?php endforeach;?>
    </table>
    <p><a href="/">На главную</a></p>
    <p><a href="/user">Назад</a></p>
</body>
</html>