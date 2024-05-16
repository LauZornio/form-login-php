<!-- Modifica Utente -->
<div class="content">
         
         <div class="form-register">
           <div class="form-container">
             <h2>Modifica Utente</h2>
             
             <div class="centratura <?php echo $varClass; ?>">
               <p><?php echo $varFormat; ?></p>
             </div>
             
             <form action="gestione_contatti.php#anchorEdit" method="post">
     
     
               <?php showEdit();  ?>      
     
               <div class="centratura">
                 <input type="submit" class="button" name="editDef" value="Procedi"></input>
               </div> <!-- end centratura (serve per centrare il button) -->
             </form>   
             
           </div> <!-- end form-container -->
         </div> <!-- end form-register -->
       
         
       </div>