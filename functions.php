<?php

function register() {
  global $avvisoF;
  global $avvisoT;
  global $connessioneDB;

  $avvisoF = "";
  $avvisoT = "";


  if (isset($_POST['register'])){
    //trim sanifica la stringa, togliendo gli spai vuoti
    $nome = trim($_POST['nome']);
    $cognome = trim($_POST['cognome']);
    $mail = trim($_POST['email']);


    // Controllare se i campi sono vuoti
    if (empty($nome) || empty($cognome) || empty($mail)) {
      $avvisoF = "Nessun dato deve rimanere vuoto.";
      //controllare se la mail è valida
    } elseif (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
      $avvisoF = "Inserisci un'email valida.";
    } else {
      //
      //VERIFICA l'esistenza della stessa mail nel DB
      //
      $stmtCheckmail = $connessioneDB->prepare("SELECT ID FROM utenti WHERE email = ?");
      $stmtCheckmail->bind_param("s", $mail);
      $stmtCheckmail->execute();
      $stmtCheckmail->store_result(); //informa mysql di memorizzare il set di risultati utili successivamente
      
      if ($stmtCheckmail->num_rows > 0) {
        $avvisoF = "La mail esiste già. Non puoi registarti con questa mail.";
        $stmtCheckmail->close();
      } else {

        //se non sono vuoti e filtervar, verifica che la mail sia valida
        if (!empty($nome) && !empty($cognome) && filter_var($mail, FILTER_VALIDATE_EMAIL)) {
          //
          //VERIFICA l'esistenza dell'utente nel DB
          //
          $stmtCheckUte = $connessioneDB->prepare("SELECT ID FROM utenti WHERE nome = ? AND cognome = ?");
          $stmtCheckUte->bind_param("ss", $nome, $cognome);
          $stmtCheckUte->execute();
          $stmtCheckUte->store_result(); //informa mysql di memorizzare il set di risultati utili successivamente
          
          if ($stmtCheckUte->num_rows > 0) {
            $avvisoF = "Il nome utente esiste già, scegli un altro nome e cognome.";
            $stmtCheckUte->close();  
          } else {
            //
            // INSERIRE Se passa il controllo utente
            // 
            $stmtInsert = $connessioneDB->prepare ("INSERT INTO utenti (nome, cognome, email) VALUES (?, ?, ?)");

            // Controllo se la preparazione della query ha avuto successo
            if (!$stmtInsert) {
              $avvisoF = "Errore nella preparazione della query: " . $connessioneDB->error;
              return;
            }

            $stmtInsert->bind_param("sss", $nome, $cognome, $mail);

            $success = $stmtInsert->execute();

            // Controllo se l'esecuzione della query è andata a buon fine
            if ($success) {
              $avvisoT = "I dati sono stati aggiunti con successo";
            } else {
              $avvisoF = "Impossibile aggiungere i dati: " . $stmtInsert->error;
            }

            // Chiudo lo statement
            $stmtInsert->close();
          }
        }
      }
    }
  } 
}

function show() {
  global $connessioneDB;

  $ute = mysqli_query($connessioneDB, "SELECT * FROM utenti");

  if (!$ute) {
    echo "Errore nel recupero degli utenti: " . mysqli_error($connessioneDB);
    return;
  }
  
  //inizio ciclo
  //attenzione è case sensitive
  while ($rowUte = mysqli_fetch_assoc($ute)) {
    $id = $rowUte["id"];
    $nome = htmlspecialchars($rowUte["nome"]);
    $cognome = htmlspecialchars($rowUte["cognome"]);
    $mail = htmlspecialchars($rowUte["email"]);
    
    echo '<!-- User Row -->';
    echo '<div class="user-row">';
    echo '<div class="user-cell id-cell">' . $id . '</div>';
    echo '<div class="user-cell name-cell">' . $nome . '</div>';
    echo '<div class="user-cell name-cell">' . $cognome . '</div>';
    echo '<div class="user-cell email-cell">' . $mail . '</div>';

    echo '<div class="user-cell id-cell"><a href="gestione_contatti.php?delete='.$id.'"><i class="fa-solid fa-xmark"></i></a></div>';

    echo '<div class="user-cell edit id-cell"><a href="gestione_contatti.php?edit='.$id.'#anchorEdit"><i class="fa-regular fa-pen-to-square"></i></a></div>';

    echo '</div>';
  }
}

function deleteUte() {
  // Controllo se è stato inviato un parametro "delete"
  if (isset($_GET["delete"])) {
      global $connessioneDB;
      global $note;
      $note="";

      // Prendo l'ID da eliminare e forzo la conversione in intero per sanitizzazione
      $utenteid = intval($_GET["delete"]);

      // Preparazione della query per evitare SQL injection
      $stmt = $connessioneDB->prepare("DELETE FROM utenti WHERE id = ?");
      if ($stmt === false) {
        // Gestione dell'errore di preparazione
        $note = "Errore nella preparazione della query: " . mysqli_error($connessioneDB);
        return;
      }

      // Collegamento dei parametri (bind) e esecuzione della query
      $stmt->bind_param("i", $utenteid);
      if ($stmt->execute()) {
        // Verifica che ci siano state righe effettivamente modificate
        if ($stmt->affected_rows > 0) {
          $note = "I dati sono stati cancellati.";
        } else {
          $note = "Nessun utente trovato con l'ID specificato o nessuna modifica effettuata.";
        }
      } else {
        // Messaggio di errore se la query non può essere eseguita
        $note = "Impossibile eliminare l'utente: " . $stmt->error;
      }

      // Chiusura dello statement
      $stmt->close();
  }
}


function showEdit() {
  global $avvisoF;
  global $avvisoT;
  global $connessioneDB;

  $avvisoF = "";
  $avvisoT = "";

  if(isset($_GET["edit"])) {
    $id = $_GET["edit"];

    $stmt = $connessioneDB->prepare("SELECT * FROM utenti WHERE id = ?");
    if ($stmt === false) {
      // Gestione dell'errore di preparazione
      $avvisoF = "Errore nella preparazione della query: " . mysqli_error($connessioneDB);
      return;
    }

    // Collegamento dei parametri (bind) e esecuzione della query
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
      // Verifica che ci siano state righe effettivamente modificate
      if ($stmt->affected_rows > 0) {
        $avvisoT = "Utente Trovato";
      } else {
        $avvisoF = "Nessun utente trovato con l'ID specificato";
      }
    } else {
      // Messaggio di errore se la query non può essere eseguita
      $avvisoF = "Impossibile aggiornare l'utente " . $stmt->error;
    }

    // Store the result to get properties
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows === 0) {
      $avvisoF = "Nessun utente trovato con l'ID specificato";
    } else {
      $avvisoT = "Utente Trovato";
      // Fetch the data
      while ($row = $result->fetch_assoc()) {
        echo "<label>Nome:</label>";
        echo "<input type=\"text\" name=\"nome\" value=\"".$row['nome']."\" required>";

        echo "<label>Cognome:</label>";
        echo "<input type=\"text\" name=\"cognome\" value=\"".$row['cognome']."\" required>";

        echo "<label>E-mail:</label>";
        echo "<input type=\"text\" name=\"mail\" value=\"".$row['email']."\" required>";

        //questo è nascosto e me lo devo portare per la modifica successiva
        echo "<input type=\"text\" name=\"id\" value=\"".$row['id']."\" hidden>";
      }

    // Chiusura dello statement
    $stmt->close();
    }

  }
} 


function update() {
  global $connessioneDB;
  global $notaUp;

  $notaUp = "";

  if (isset($_POST["editDef"])) {
    // Collecting user inputs safely using mysqli real_escape_string or prepare statements
    $nomeUp = mysqli_real_escape_string($connessioneDB, $_POST['nome']);
    $cognomeUp = mysqli_real_escape_string($connessioneDB, $_POST['cognome']);
    $mailUp = mysqli_real_escape_string($connessioneDB, $_POST['mail']);
    $idUp = (int)$_POST['id'];  // Casting to integer for safety
   

    // Using prepared statements to avoid SQL injection
    $stmt = $connessioneDB->prepare("UPDATE utenti SET email = ?, nome = ?, cognome = ? WHERE id = ?");
    if ($stmt === false) {
      $notaUp = "Errore nella preparazione della query: " . $connessioneDB->error;
      return;
    }

    // Binding parameters
    $stmt->bind_param("sssi", $mailUp, $nomeUp, $cognomeUp, $idUp);
    
    // Execute the statement
    if ($stmt->execute()) {
      $notaUp = "Utente $idUp aggiornato con successo!";
    } else {
      $notaUp = "Errore nell'aggiornamento dell'utente: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
  }
    
}
