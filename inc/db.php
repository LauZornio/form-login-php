<?php

    // Connessione al database usando mysqli
    $connessioneDB = mysqli_connect('localhost', 'root', 'root', 'prog-form-login');

    // Gestione errori
    if (mysqli_connect_errno()) {
        // Registra l'errore in un file log (errore_generale.log)
        error_log("Errore di connessione al database: " . mysqli_connect_error(), 3, "/path/to/errore_generale.log");
        // Messaggio generico per gli utenti
        echo "C'è stato un problema nel connettersi al database. Riprova più tardi.";
        exit;  // Interrompe l'esecuzione in caso di errore
    }

