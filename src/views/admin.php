<!DOCTYPE html>
<html lang="th">

<head>
    <title>
        <%= htmlWebpackPlugin.options.title %> | ADMIN | <%= pageTitle %>
    </title>
    <%= htmlWebpackPlugin.tags.headTags %>
</head>

<body>
    <?php include 'components/header.php'; ?>
    <?php include 'pages/<%= pageLoaded %>'; ?>
    <?php include 'components/footer.php'; ?>
    <%= htmlWebpackPlugin.tags.bodyTags %>
</body>

</html>