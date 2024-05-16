<!-- metto qui la connessione al DB perchè è sopra alla header --> 
<!-- non tutte le header potrebbero avere la connessione a DB -->
<?php include "inc/db.php"; ?>
<?php include "functions.php"; ?>

<?php register(); ?>

<?php include("inc/header.php"); ?>

  <h1>Pagina di Registrazione</h1>
  
  <div class="content">
    <div class="structure">
      <div class="col-4 col-sx">
        <a href="gestione_contatti.php" class="button menus-sx" title="Vai alla pagina di Gestione dei Contatti" target="_self">Gestione Contatti</a>
      </div> <!-- end col-8 minimenu a sinistra-->
      
      <div class="col-8">
        <div class="form-register">
          <div class="form-container">
            <h2>Modulo di Registrazione</h2>

            <?php if (!empty($avvisoT)) {
                $varClass = "avvisoT";
                $varFormat = $avvisoT;
              } else {
                $varClass = "avvisoF";
                $varFormat = $avvisoF;
              }
            ?>
            
            <div class="centratura <?php echo $varClass; ?>">
              <p><?php echo $varFormat; ?></p>
            </div>
            
            <form action="index.php" method="post">
              <label>Nome:</label>
              <input type="text" name="nome" placeholder="Inserisci il tuo Nome" required>

              <label>Cognome:</label>
              <input type="text" name="cognome" placeholder="Inserisci il tuo Cognome" required>

              <label>Email:</label>
              <input type="email" name="email" placeholder="Inserisci la tua Mail" required>

              <div class="centratura">
                <input type="submit" class="button" name="register" value="Registrati"></input>
              </div> <!-- end centratura (serve per centrare il button) -->
            </form>   
            
          </div> <!-- end form-container -->
        </div> <!-- end form-register -->
      </div> <!-- end col-4 contenuto principale-->
    </div> <!-- end structure -->
  </div>
  
  <div class="spacer"></div>


<?php include("inc/footer.php"); ?>
