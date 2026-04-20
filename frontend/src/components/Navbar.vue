<template>
  <!-- Navigation pour les appareils mobiles -->
  <nav class="mobile-navbar">
    <!-- lien vers la page du catalogue -->
    <router-link to="/catalogue" class="nav-item">
      <Home class="icon" /> <span>Catalogue</span>
    </router-link>
    <!-- lien vers la page du dashboard -->
    <router-link to="/dashboard" class="nav-item">
      <Wine class="icon" /><span>Celliers</span>
    </router-link>
    <!-- lien vers la page de la liste d'achats -->
    <router-link to="/liste-achats" class="nav-item">
      <ShoppingBasket class="icon" /><span>Achats</span>
    </router-link>
    <!-- lien vers la page du profil de l'usager -->
    <router-link to="/profil-usager" class="nav-item">
      <User class="icon" /><span>Profil</span>
    </router-link>
  </nav>
</template>
<script setup>
import { Home, Wine, ShoppingBasket, User } from "lucide-vue-next";
</script>
