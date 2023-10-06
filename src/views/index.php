<!DOCTYPE html>
<html lang="th">
    
    <head>
        <title>
            <%= htmlWebpackPlugin.options.title %> | <%= pageTitle %>
        </title>
        <%= htmlWebpackPlugin.tags.headTags %>
    </head>
    
    <body>
        <?php include 'components/header.php'; ?>
        <?php include 'components/navbar.php'; ?>
        <?php include 'pages/<%= pageLoaded %>'; ?>
    <%= htmlWebpackPlugin.tags.bodyTags %>
</body>

</html>