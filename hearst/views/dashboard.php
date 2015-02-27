<html>
<head>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">

<!-- Optional theme -->
<link rel="stylesheet" href="../bootstrap/css/bootstrap-theme.min.css">

<!-- Latest compiled and minified jQuery -->
<script src="../bootstrap/js/jquery-2.1.3.min.js"></script>

<!-- Latest compiled and minified Bootstrap JavaScript -->
<script src="../bootstrap/js/bootstrap.min.js"></script>

<title>Bookmarks Full Stack App - Michael V. Battista</title>

</head>
<body class="col-md-12">

<h3 class="text-center">Bookmarks Full Stack App</h1>

<?php
    require_once('../controllers/main_controller.php');
    $controller = new MainController();
    $all_bookmarks = $controller->load_bookmarks();
    if (empty($all_bookmarks)) {
        echo '<h2>No bookmarks saved.</h2>';
    }
    else {
        echo '<div class="panel-group" id="bookmarks">'."\n";
        foreach ($all_bookmarks as $bm) {
            // Assign variables for easy interpolation in HEREDOC
            $id = $bm['id'];
            $name = $bm['name'];
            $url = $bm['url'];

            echo <<<"BOOKMARKITEM"
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="heading-$id" data-toggle="collapse" data-target="#collapse-$id" data-parent="#bookmarks">
      <h4 class="panel-title">
          <a href="$url" target="_blank">$name ($url)</a>
      </h4>
    </div>
    <div id="collapse-$id" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading-$id">
      <div class="panel-body">
        <form method="post" action='../controllers/update_controller.php' name="editBookmarkForm-1">
            <p><input type="text" id="edit-name-$id" name="name" class="form-control" placeholder="Enter Name" value="$name"></p>
            <p><input type="text" id="edit-url-$id" name="url" class="form-control" placeholder="Enter URL" value="$url"></p>
            <input type="hidden" id="edit-id" name="id" value="$id">
            <p class="text-right">
            <button type="submit" class="btn btn-primary" >Update bookmark</button>
        </form>
        <form method="post" action='../controllers/delete_controller.php' name="deleteBookmarkForm-1">
            <input type="hidden" id="delete-id" name="id" value="$id">
            <p class="text-right"><button type="submit" class="btn btn-danger" >Delete bookmark</button></p>
        </form>
      </div>
    </div>
  </div>
BOOKMARKITEM;

        }
        echo '</div>'."\n";
    }

?>

<!-- Button trigger modal -->
<p class="text-center">
<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#addBookmarkModal">
  Add new bookmark
</button>
</p>

<!-- Add new Bookmark modal -->
<div class="modal fade" id="addBookmarkModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Add New Bookmark</h4>
      </div>
      <div class="modal-body">
        <form method="post" action='../controllers/create_controller.php' name="addBookmarkForm">
            <p><input type="text" id="new-name" class="form-control" name="name" placeholder="Enter name" value=""></p>
            <p><input type="text" id="new-url" class="form-control" name="url" placeholder="Enter URL" value=""></p>
            <p class="text-right"> <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" >Add bookmark</button></p>
        </form>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

</body>
</html>