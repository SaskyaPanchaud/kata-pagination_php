<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>PHPTest — Pagination</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <link rel="stylesheet" href="./assets/css/bootstrap.min.css" media="screen">
  <link rel="stylesheet" href="./assets/css/custom.min.css" media="screen">
  <link rel="stylesheet" href="./assets/css/style.css" media="screen">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
</head>

<body>
  <?php include("./include/menu.php"); ?>

  <div class="container">

    <div class="page-header" id="banner">
      <div class="row">
        <div class="col-lg-8 col-md-7 col-sm-6">
          <h1>Test PHP - Pagination</h1>
          <p class="lead">Liste des enregistrements</p>

        </div>
        <div class="col-lg-4 col-md-5 col-sm-6">
          <img src="./assets/img/ninjatunesmonkey.jpg" width="250px" />
        </div>

        <div class="col-lg-12">
          <h2 id="tables">Tables</h2>
          <table class="table table-striped table-hover" id="table">
            <thead>
              <tr>
                <th>status</th>
                <th>thumb</th>
                <th>format</th>
                <th>title</th>
                <th>catno</th>
                <th>year</th>
                <th>resource_url</th>
                <th>artist</th>
                <th>id</th>
              </tr>
            </thead>
          </table>
      </div>
    </div>

    <footer>
      <?php include("./include/footer.php"); ?>
    </footer>
  </div>

  <script>
    $('#table').DataTable({
      ajax: {
        url: './assets/data/records.json',
        dataSrc: 'releases'
      },
      columns: [
          { data: 'status' },
          { data: 'thumb' },
          { data: 'format' },
          { data: 'title' },
          { data: 'catno' },
          {
            data: 'year',
            defaultContent: ''
          },
          { data: 'resource_url' },
          { data: 'artist' },
          { data: 'id'}
      ],
      ordering: true,
      info: true,
      paging: true,
      searching: true
    });
  </script>

</html>
