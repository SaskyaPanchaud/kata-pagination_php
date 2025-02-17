<?php
  session_start();
  if (!isset($_SESSION["nbRowsPerPage"])) {
    $_SESSION["nbRowsPerPage"] = 12;
  }
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
          <!-- formulaire pour obtenir nombre de lignes par page entre par utilisateur -->
          <form method="POST" action="records.php">
            <label>Nombre de résultats à afficher par page :</label>
            <?php echo "<input type=\"number\" id=\"nbResPerPage\" name=\"nbResultsPerPage\" value=" . $_SESSION["nbRowsPerPage"] . "/>"; ?>
            <button type="submit">Appliquer</button>
          </form>
          <!-- legende pour couleur des lignes -->
          <p><br><b>Légendes (selon année) :</b></p>
          <ul>
            <li style="color:Grey;">pas de donnée</li>
            <li style="color:Violet;">avant 1991</li>
            <li style="color:Red;">entre 1991 et 2000</li>
            <li style="color:Orange;">entre 2001 et 2010</li>
            <li style="color:Green;">après 2010</li>
          </ul>

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
          <table class="table table-striped table-hover" id="table">

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
                      if (!in_array($key, $colNames) && ($key != "id")) {
                        # ajoute cle au tableau sauf si cle = id
                        $colNames[] = $key;
                      }
                    }
                  }
                  $colNames[] = "image"; # remplacer id par image pour mettre miniature

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
                $tempNbRowsPerPage = (int) round($_POST['nbResultsPerPage']);
                if ($tempNbRowsPerPage < 1) {
                  $_SESSION["nbRowsPerPage"] = 1;
                } else if ($tempNbRowsPerPage > $nbRows) {
                  $_SESSION["nbRowsPerPage"] = $nbRows;
                } else {
                  $_SESSION["nbRowsPerPage"] = $tempNbRowsPerPage;
                }
              }
              $nbPages = ceil($nbRows / $_SESSION["nbRowsPerPage"]);

              # obtenir numero de page en cours
              $page_number = 1;
              if (isset($_GET['page']) && is_numeric($_GET['page'])) {
                $page_number = (int) round($_GET['page']);
                if ($page_number < 1) {
                  $page_number = 1;
                } else if ($page_number > $nbPages) {
                  $page_number = $nbPages;
                }
              } else {
                $page_number = 1;
              }

              # inclure fichier pour se connecter a api
              include("./include/api.php");
            ?>

            <?php
              # fonction utilisee pour colorer les lignes
              function findColorDependingYear($year) {
                if ($year === NULL) {
                  return "";
                }

                if ($year > 2010) {
                  return " class=\"success\"";
                } else if ($year > 2000) {
                  return " class=\"warning\"";
                } else if ($year > 1990) {
                  return " class=\"danger\"";
                } else {
                  return " class=\"info\"";
                }
              }
            ?>

            <?php
              # fonction utilisee pour recuperer image disque
              function getImage($release_id) {
                global $client; # car sinon php considere que client n'est pas defini
                $release = $client->getRelease([
                  'id' => $release_id
                ]);

                if (!empty($release['images']) && isset($release['images'][0]['uri'])) {
                    $imageBase64 = base64_encode($client->getHttpClient()->get($release['images'][0]['uri'])->getBody()->getContents());
                    return $imageBase64;
                } else {
                    return NULL;
                }
              }
            ?>

            <!-- construction body du tableau -->
            <tbody>
              <?php
                $numFirstRow = ($page_number - 1) * $_SESSION["nbRowsPerPage"];
                $numLastRow = $numFirstRow + $_SESSION["nbRowsPerPage"] <= $nbRows ? $numFirstRow + $_SESSION["nbRowsPerPage"] : $nbRows;

                # affichage des lignes
                for ($i = $numFirstRow; $i < $numLastRow; $i++) {
                  $color = findColorDependingYear($data[$i]["year"]);
                  echo "<tr" . $color . ">";
                  echo "<td>" . ($i + 1) . "</td>";
                  foreach ($colNames as $colName) {
                    if ($colName === "image") {
                      echo "<td><img src=\"data:image/jpeg;base64," . getImage($data[$i]["id"]) . "\" alt=\"\" height=\"50px\" width=\"50px\"></td>";
                    } else {
                      echo "<td>" . $data[$i][$colName] . "</td>";
                    }
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
              $nbPagesMax = 6;
              if ($nbPages < $nbPagesMax) {
                for ($i = 1; $i <= $nbPages; $i++) {
                  if ($page_number === $i) {
                    echo "<li class=\"active\"><a href=\"?page=" . $i . "\">" . $i . "</a></li>";
                  } else {
                    echo "<li><a href=\"?page=" . $i . "\">" . $i . "</a></li>";
                  }
                }
              } else {

                $nbPagesAdapted = 2;
                $firstPage = 1;
                $lastPage = $nbPages;

                for ($i = 1; $i <= $nbPages; $i++) {
                  if (($i <= $nbPagesAdapted) || ($i > ($nbPages - $nbPagesAdapted)) || ($i === ($page_number - 1)) || ($i === ($page_number + 1))) {
                    $pageActive = ($page_number === $i) ? " class=\"active\"" : "";
                    echo "<li" . $pageActive . "><a href=\"?page=" . $i . "\">" . $i . "</a></li>";
                  } else if ($page_number === $i) {
                    echo "<li class=\"active\"><a href=\"?page=" . $i . "\">" . $i . "</a></li>";
                  } else if ($i === ($nbPagesAdapted + 1) && !($page_number <= $nbPagesAdapted)) {
                    echo "<li><a href=\"?page=" . ((int) round(($page_number - $firstPage) / 2)) . "\">...</a></li>";
                  } else if ($i === (int) ($nbPages - $nbPagesAdapted) && !($page_number > ($nbPages - $nbPagesAdapted))) {
                    echo "<li><a href=\"?page=" . ((int) (round(($lastPage - $page_number) / 2) + $page_number)) . "\">...</a></li>";
                  }
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

  <script>
    $('#table').DataTable({
      ordering: true,
      info: false,
      paging: false,
      searching: false
    });
  </script>

</html>
