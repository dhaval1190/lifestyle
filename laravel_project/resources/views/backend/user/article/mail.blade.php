<!DOCTYPE html>
<html>
<head>
    <title>$subject</title>
    <style>   
        .btn {
            height: 30px;
            color: #ffffff;
            background-color: #009ef7;
            text-align: center;
            vertical-align: middle;
            border-radius: 5%;
            border:none;
        }
        a{
            text-decoration: none;
        }
    </style>
</head>
<body>    
    
    <p>Article Created By : {{$article_by}}.</p>

    <p> Date : {{$date}}.</p>

    <button class="btn"><a href="{{ route('admin.items.edit', $item_id) }}" class="link" style="color:#ffffff;">Approve</a></button>

</body>
</html>