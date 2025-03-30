
</body>
</html>
<h2 class="addbarril">AGREGAR BARRIL</h2>

<section class="section-agregar">
   <div class="contenedor-form">
       <form action="./procesarbarril.php" method="POST" class="form-agregar">
           <?php if (isset($_GET['mensaje']) && $_GET['mensaje'] == 'error') : ?>
               <p style="color: red;">Error al agregar el barril. Por favor, intente de nuevo.</p>
           <?php endif; ?>

           <label for="codigo">CODIGO</label>
           <input type="text" name="codigo" required>

           <label for="litros">LITROS</label>
           <select name="litros" required>
               <option value="20">20</option>
               <option value="30">30</option>
               <option value="50">50</option>
           </select>

           <input type="hidden" name="estado" value="VACIO">

           <button type="submit">AGREGAR</button>
       </form>
   </div>
</section>



