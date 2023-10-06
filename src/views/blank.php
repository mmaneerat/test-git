<?php include 'includes/functions.php'; ?>
<!DOCTYPE html>
<html lang="th">

<head>
    <title>
        <%= htmlWebpackPlugin.options.title %> | <%= pageTitle %>
    </title>
    <%= htmlWebpackPlugin.tags.headTags %>
</head>

<body>
    <?php include 'pages/<%= pageLoaded %>'; ?>
    <%= htmlWebpackPlugin.tags.bodyTags %>
    <!-- <?php echo "Last modified: " . date("F d Y H:i:s.", getlastmod()); ?> -->
</body>

</html>