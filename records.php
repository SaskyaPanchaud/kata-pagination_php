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

        <?php
          # records.json -> array "releases" qui contient plusieurs elements / chaque element est une paire cle-valeur (element = ligne du tableau)
          
          # lecture du fichier et stocke sous forme de string
          $json_content = file_get_contents("./assets/data/records.json");

          # transformation de string a array (true pour transformer en associative array -> array avec cle assignee a chaque element)
          $array_data = json_decode($json_content, true);
          if ($array_data === null) {
            die('Erreur lors du decodage du fichier JSON !');
          }

          $data = $array_data["releases"];

          # affiche "cle : valeur" pour chaque ligne
          #foreach ($data as $row) {
          #  foreach ($row as $key => $value) {
          #    echo $key . " : " . $value . "<br>";
          #  }
          #}
        ?>

        <div class="col-lg-12">
          <h2 id="tables">Tables</h2>
          <table class="table table-striped table-hover ">

            <!-- construction header du tableau -->
            <thead>
              <tr>
                <th>#</th>
                <?php

                  # besoin de parcourir toutes les cles dans chaque element pour obtenir tous les noms de colonne possibles
                  $colNames = [];
                  foreach ($data as $row) {
                    foreach (array_keys($row) as $key) {
                      # si nom pas deja dans liste alors ajouter nom sinon passer a la suivante
                      if (!in_array($key, $colNames)) {
                        # ajoute cle au tableau
                        $colNames[] = $key;
                      }
                    }
                  }

                  foreach ($colNames as $colName) {
                    echo "<th>" . $colName . "</th>";
                  }
                ?>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td>Column content</td>
                <td>Column content</td>
                <td>Column content</td>
              </tr>
              <tr>
                <td>2</td>
                <td>Column content</td>
                <td>Column content</td>
                <td>Column content</td>
              </tr>
              <tr class="info">
                <td>3</td>
                <td>Column content</td>
                <td>Column content</td>
                <td>Column content</td>
              </tr>
              <tr class="success">
                <td>4</td>
                <td>Column content</td>
                <td>Column content</td>
                <td>Column content</td>
              </tr>
              <tr class="danger">
                <td>5</td>
                <td>Column content</td>
                <td>Column content</td>
                <td>Column content</td>
              </tr>
              <tr class="warning">
                <td>6</td>
                <td>Column content</td>
                <td>Column content</td>
                <td>Column content</td>
              </tr>
              <tr class="active">
                <td>7</td>
                <td>Column content</td>
                <td>Column content</td>
                <td>Column content</td>
              </tr>
            </tbody>
          </table>
        </div>

        <div class="col-lg-12">
          <h2 id="pagination">Pagination</h2>
            <ul class="pagination">
              <li class="disabled"><a href="#">&laquo;</a></li>
              <li class="active"><a href="#">1</a></li>
              <li><a href="#">2</a></li>
              <li><a href="#">3</a></li>
              <li><a href="#">4</a></li>
              <li><a href="#">5</a></li>
              <li><a href="#">&raquo;</a></li>
            </ul>
            <br />
            <ul class="pagination pagination-lg">
              <li class="disabled"><a href="#">&laquo;</a></li>
              <li class="active"><a href="#">1</a></li>
              <li><a href="#">2</a></li>
              <li><a href="#">3</a></li>
              <li><a href="#">&raquo;</a></li>
            </ul>
            <br />
            <ul class="pagination pagination-sm">
              <li class="disabled"><a href="#">&laquo;</a></li>
              <li class="active"><a href="#">1</a></li>
              <li><a href="#">2</a></li>
              <li><a href="#">3</a></li>
              <li><a href="#">4</a></li>
              <li><a href="#">5</a></li>
              <li><a href="#">&raquo;</a></li>
            </ul>
        </div>
      </div>
    </div>

    <footer>
      <?php include("./include/footer.php"); ?>
    </footer>
  </div>
  <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
  <script src="./assets/js/bootstrap.min.js"></script>

</html>
