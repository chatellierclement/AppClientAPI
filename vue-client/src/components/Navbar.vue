<template>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
       NANTES PROPRETE SERVICES
      </li>  
      <li class="nav-item active">
      <router-link class="nav-link" to="/"> Planning journalier <span class="sr-only">(current)</span></router-link>
      </li>
      <li class="nav-item">
      <router-link class="nav-link" to="/agenda"> Agenda </router-link>
      </li>
      <li class="nav-item">
      <router-link class="nav-link" to="/calendrier"> Rendez-vous </router-link>
      </li>
      <li class="nav-item">
      <button class="nav-link dropdown-toggle" type="button" data-toggle="collapse" data-target="#navbarDropdown" aria-controls="navbarDropdown" aria-expanded="false" aria-label="Toggle navigation">
        Facturation
      </button>
      <div class="collapse" id="navbarDropdown">
        <ul class="navbar-nav mr-auto">
        <li class="nav-item"> 
          <router-link class="nav-link" to="/modele"> Modèle de courriers </router-link>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Créer une facture</a>
        </li>
        </ul>
      </div>
      </li>
      <li class="nav-item">
      <a class="nav-link" href="#">Paramètres</a>
      </li>
    </ul>
    </div>
  </nav>
</template>

<script>
</script>

<style>
  @media (min-width: 992px) {
    nav {
      height:100vh
    }   
    .navbar-expand-lg .navbar-nav .dropdown-menu {
      position: static;
    }
  } 
  .navbar-nav {
    display: block
  } 
  .navbar {
    display: block
  } 
  .dropdown-menu {
    background: none;
    border: none;   
  }
  .nav-item button {    
    background: none;
    border: none;
    color:grey    
  }
  .nav-item button:focus, .nav-item button:active {
     outline: none !important;
     box-shadow: none;
  } 
</style>