<?php
  session_start();
?>

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
          <!-- formulaire pour obtenir nombre de lignes par page entre par utilisateur -->
          <form method="POST" action="records.php">
            <label>Nombre de résultats à afficher par page :</label>
            <?php echo "<input type=\"number\" id=\"nbResPerPage\" name=\"nbResultsPerPage\" value=" . $_SESSION["nbRowsPerPage"] . "/>"; ?>
            <button type="submit">Appliquer</button>
          </form>
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

            <?php
              # donnes globales a la page

              # recuperer donnee utilisateur
              $nbRows = count($data);
              if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $tempNbRowsPerPage = (int) $_POST['nbResultsPerPage'];
                if (!($tempNbRowsPerPage < 1 || $tempNbRowsPerPage > $nbRows)) {
                  $_SESSION["nbRowsPerPage"] = $tempNbRowsPerPage;
                }
              }
              $nbPages = ceil($nbRows / $_SESSION["nbRowsPerPage"]);

              # obtenir numero de page en cours
              $page_number = 1;
              if (isset($_GET['page']) && is_numeric($_GET['page'])) {
                $page_number = (int) $_GET['page'];
                if ($page_number < 1) {
                  $page_number = 1;
                } else if ($page_number > $nbPages) {
                  $page_number = $nbPages;
                }
              } else {
                $page_number = 1;
              }
            ?>

            <!-- construction body du tableau -->
            <tbody>
              <?php
                $numFirstRow = ($page_number - 1) * $_SESSION["nbRowsPerPage"];
                $numLastRow = $numFirstRow + $_SESSION["nbRowsPerPage"] <= $nbRows ? $numFirstRow + $_SESSION["nbRowsPerPage"] : $nbRows;

                # affichage des lignes
                for ($i = $numFirstRow; $i < $numLastRow; $i++) {
                  echo "<tr>";
                  echo "<td>" . ($i + 1) . "</td>";
                  foreach ($colNames as $colName) {
                      echo "<td>" . $data[$i][$colName] . "</td>";
                  }
                  echo "</tr>";
                }
              ?>
            </tbody>
          </table>
        </div>

        <div class="col-lg-12">
          <ul class="pagination">
            <?php

              # bouton pour revenir en arriere
              if ($page_number > 1) {
                echo "<li><a href=\"?page=" . ($page_number - 1) . "\">&laquo;</a></li>";
              } else {
                echo "<li class=\"disabled\"><a href=\"?page=" . $page_number . "\">&laquo;</a></li>";
              }

              # boutons pour toutes les pages
              for ($i = 1; $i <= $nbPages; $i++) {
                if ($page_number === $i) {
                  echo "<li class=\"active\"><a href=\"?page=" . $i . "\">" . $i . "</a></li>";
                } else {
                  echo "<li><a href=\"?page=" . $i . "\">" . $i . "</a></li>";
                }
              }

              # bouton pour aller en avant
              if ($page_number < $nbPages) {
                echo "<li><a href=\"?page=" . ($page_number + 1). "\">&raquo;</a></li>";
              } else {
                echo "<li class=\"disabled\"><a href=\"?page=" . $page_number . "\">&raquo;</a></li>";
              }
            ?>
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
